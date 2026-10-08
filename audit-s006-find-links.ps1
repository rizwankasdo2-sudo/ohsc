$root = "d:\Sites\oxfordhomestudy.com"
$ht = Get-Content "$root\.htaccess"
$redirectMap = @{}
foreach ($line in $ht) {
    $line = $line.Trim()
    if ($line -match 'RewriteRule\s+\^(.+?)\s+(\S+)\s+\[.*R=30[12]') {
        $patternRaw = $Matches[1].Trim()
        if ($patternRaw -match '\(\.\*\)|\(\?:|\(\?|\(\$|\(\d|\+\|\[') { continue }
        $src = ($patternRaw -replace '\\-','-' -replace '\\\.','.' -replace '/\?\$','' -replace '/\?$','' -replace '\?$','' -replace '\$$','').Trim('/').ToLower()
        $tgt = ($Matches[2] -replace '^https?://[^/]+','' -replace '\?.*$','').Trim('/')
        if ($src -and $tgt -and -not $redirectMap.ContainsKey($src)) {
            $redirectMap[$src] = $tgt
        }
    } elseif ($line -match '^Redirect\s+301\s+(\S+)\s+(\S+)') {
        $src = $Matches[1].Trim('/').ToLower()
        $tgt = ($Matches[2] -replace '^https?://[^/]+','').Trim('/')
        if ($src -and $tgt -and -not $redirectMap.ContainsKey($src)) {
            $redirectMap[$src] = $tgt
        }
    }
}

$codeFiles = Get-ChildItem -Path $root -Recurse -Include *.php,*.html,*.js -File | Where-Object {
    $_.FullName -notmatch '\\(lms|admin|vendor|postmark|node_modules|Resources\\admin_|cron|stripe|PHPMailer|OHC)\\' -and
    $_.Name -notmatch ' Copy' -and
    $_.Name -notmatch '^temp\.php$'
}

$hits = @()
foreach ($file in $codeFiles) {
    $lines = Get-Content -LiteralPath $file.FullName -ErrorAction SilentlyContinue
    if (-not $lines) { continue }
    $lineNum = 0
    foreach ($line in $lines) {
        $lineNum++
        foreach ($src in $redirectMap.Keys) {
            $patterns = @(
                "/$src`"",
                "/$src'",
                "/$src<",
                "/$src ",
                "/$src;",
                "/$src\",
                "com/$src`"",
                "com/$src'",
                "$src.php"
            )
            foreach ($pat in $patterns) {
                if ($line.Contains($pat)) {
                    $hits += [PSCustomObject]@{
                        File = $file.FullName.Replace("$root\", '')
                        Line = $lineNum
                        Source = $src
                        Target = $redirectMap[$src]
                        Snippet = $line.Trim().Substring(0, [Math]::Min(120, $line.Trim().Length))
                    }
                    break
                }
            }
        }
    }
}

Write-Output "Exact internal link hits: $($hits.Count)"
$hits | Sort-Object File, Line | Format-Table -AutoSize -Wrap
