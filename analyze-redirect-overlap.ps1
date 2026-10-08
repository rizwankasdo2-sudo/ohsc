$lines = Get-Content 'd:\Sites\oxfordhomestudy.com\.htaccess'
$pairs = foreach ($line in $lines) {
    if ($line -match 'RewriteRule\s+\^(.+?)\s+(\S+)\s+\[.*R=30[12]') {
        $patternRaw = $Matches[1].Trim()
        if ($patternRaw -match '\(\.\*\)|\(\?:|\(\?|\(\$|\(\d|\+\|\[') { continue }
        $src = ($patternRaw -replace '\\-','-' -replace '\\\.','.' -replace '/\?\$','' -replace '/\?$','' -replace '\?$','' -replace '\$$','').Trim('/').ToLower()
        $tgt = ($Matches[2] -replace '^https?://[^/]+','' -replace '\?.*$','').Trim('/').ToLower()
        if ($src -and $tgt) {
            [PSCustomObject]@{ Source = $src; Target = $tgt; Pattern = $patternRaw; Raw = $line.Trim() }
        }
    } elseif ($line -match '^Redirect\s+301\s+(\S+)\s+(\S+)') {
        $tgt = ($Matches[2] -replace '^https?://[^/]+','' -replace '\?.*$','').Trim('/').ToLower()
        [PSCustomObject]@{
            Source = $Matches[1].Trim('/').ToLower()
            Target = $tgt
            Pattern = $Matches[1]
            Raw = $line.Trim()
        }
    }
}

$chains = @()
foreach ($pair in $pairs) {
    $nextRules = $pairs | Where-Object { $_.Source -eq $pair.Target }
    foreach ($n in $nextRules) {
        if ($n.Raw -eq $pair.Raw) { continue }
        $chains += [PSCustomObject]@{
            Chain = "$($pair.Source) -> $($pair.Target) -> $($n.Target)"
            Step1 = $pair.Raw
            Step2 = $n.Raw
        }
    }
}

Write-Output "=== Genuine 2-hop redirect chains (exact path match) ==="
$chains | Sort-Object Chain -Unique | ForEach-Object {
    Write-Output $_.Chain
    Write-Output ("  1) {0}" -f $_.Step1)
    Write-Output ("  2) {0}" -f $_.Step2)
    Write-Output ''
}

Write-Output ("Total genuine 2-hop chains: " + @($chains | Sort-Object Chain -Unique).Count)

Write-Output ''
Write-Output '=== Duplicate source rules (conflicts, not chains) ==='
$pairs | Group-Object Source | Where-Object { $_.Count -gt 1 } | ForEach-Object {
    Write-Output ("Source: {0}" -f $_.Name)
    foreach ($g in $_.Group) { Write-Output ("  -> {0}" -f $g.Target) }
}

Write-Output ''
Write-Output '=== Trailing-slash normalization redirects ==='
$pairs | Where-Object { $_.Source -eq ($_.Target + '') -or $_.Pattern -match '/\?\$' -or $_.Pattern -match '/\?$' } | Select-Object -First 20 | ForEach-Object {
    Write-Output ("{0} -> {1}" -f $_.Source, $_.Target)
}
