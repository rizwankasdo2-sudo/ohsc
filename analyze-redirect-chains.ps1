$htaccess = Get-Content 'd:\Sites\oxfordhomestudy.com\.htaccess' -Raw
$rules = @()

function Convert-PatternToRegex {
    param([string]$Pattern)
    $p = $Pattern.Trim()
    if ($p.EndsWith('$')) { $p = $p.Substring(0, $p.Length - 1) }
    $p = $p -replace '/\?\$', '/?$'
    $p = $p -replace '\?$', '?$'
    $p = '^{0}$' -f $p
    $p = $p -replace '\\-', '-'
    $p = $p -replace '\\\.', '.'
    return $p
}

function Normalize-Path {
    param([string]$Path)
    if ([string]::IsNullOrWhiteSpace($Path)) { return '' }
    $p = $Path.Trim()
    if ($p -match '^https?://[^/]+(.*)$') { $p = $Matches[1] }
    $p = $p -replace '\?.*$', ''
    if ($p -eq '' -or $p -eq '/') { return '' }
    return $p.Trim('/').ToLower()
}

foreach ($line in ($htaccess -split "`n")) {
    $line = $line.Trim()
    if ($line -match '^Redirect\s+301\s+(\S+)\s+(\S+)') {
        $src = Normalize-Path $Matches[1]
        $tgt = Normalize-Path $Matches[2]
        $rules += [PSCustomObject]@{ Regex = '^' + [regex]::Escape($src) + '$'; Source = $src; Target = $tgt; Raw = $line }
        continue
    }
    if ($line -match 'RewriteRule\s+\^(.+?)\s+(\S+)\s+\[.*R=30[12]') {
        $patternRaw = $Matches[1].Trim()
        $targetRaw = $Matches[2]
        if ($patternRaw -match '\(\.\*\)|\(\?:|\(\?|\(\$|\(\d|\+\|\[') { continue }
        $regex = Convert-PatternToRegex $patternRaw
        $example = $patternRaw -replace '\\-', '-' -replace '\\\.', '.' -replace '/\?\$', '' -replace '/\?$', '' -replace '\?$', '' -replace '\$$', ''
        $rules += [PSCustomObject]@{ Regex = $regex; Source = (Normalize-Path $example); Target = (Normalize-Path $targetRaw); Raw = $line }
    }
}

function Find-RuleForPath {
    param([string]$Path)
    $normalized = Normalize-Path $Path
    foreach ($rule in $rules) {
        if ($normalized -match $rule.Regex) { return $rule }
    }
    return $null
}

$chains = @{}
foreach ($rule in $rules) {
    if (-not $rule.Source) { continue }
    $steps = @($rule.Source)
    $raws = @($rule.Raw)
    $current = $rule.Target
    $steps += $current
    $guard = 0
    while ($guard -lt 10) {
        $guard++
        $nextRule = Find-RuleForPath $current
        if (-not $nextRule) { break }
        if ($nextRule.Target -eq $current) { break }
        $raws += $nextRule.Raw
        $current = $nextRule.Target
        $steps += $current
    }
    if ($steps.Count -gt 2) {
        $key = $steps -join ' -> '
        if (-not $chains.ContainsKey($key)) {
            $chains[$key] = [PSCustomObject]@{ Hops = $steps.Count - 1; Chain = $key; StartRule = $rule.Raw }
        }
    }
}

Write-Output "Rules parsed: $($rules.Count)"
Write-Output "Chains found: $($chains.Count)"
Write-Output ''
$chains.GetEnumerator() | ForEach-Object { $_.Value } | Sort-Object Hops -Descending | ForEach-Object {
    Write-Output ("[{0} hops] {1}" -f $_.Hops, $_.Chain)
}

# Also report targets that match any other rule source (potential chain entry points)
Write-Output ''
Write-Output '--- Targets that are also redirect sources ---'
$hits = @()
foreach ($rule in $rules) {
    if (-not $rule.Target) { continue }
    $match = Find-RuleForPath $rule.Target
    if ($match -and $match.Source -ne $rule.Source) {
        $hits += [PSCustomObject]@{ From = $rule.Source; To = $rule.Target; NextRuleSource = $match.Source }
    }
}
$hits | Sort-Object From, To -Unique | Select-Object -First 50 | Format-Table -AutoSize
