$root = "d:\Sites\oxfordhomestudy.com"
$ht = Get-Content "$root\.htaccess"
$sources = New-Object 'System.Collections.Generic.HashSet[string]'
foreach ($line in $ht) {
    $line = $line.Trim()
    if ($line -match 'RewriteRule\s+\^(.+?)\s+(\S+)\s+\[.*R=30[12]') {
        $patternRaw = $Matches[1].Trim()
        if ($patternRaw -match '\(\.\*\)|\(\?:|\(\?|\(\$|\(\d|\+\|\[') { continue }
        $src = ($patternRaw -replace '\\-','-' -replace '\\\.','.' -replace '/\?\$','' -replace '/\?$','' -replace '\?$','' -replace '\$$','').Trim('/').ToLower()
        if ($src) { [void]$sources.Add($src) }
    }
}

$codeFiles = Get-ChildItem -Path $root -Recurse -Include *.php,*.html -File | Where-Object {
    $_.FullName -notmatch '\\(lms|admin|vendor|postmark|node_modules|Resources\\admin_|cron)\\' -and $_.Name -notmatch ' Copy'
}

$hits = @()
foreach ($file in $codeFiles) {
    $content = Get-Content -LiteralPath $file.FullName -Raw -ErrorAction SilentlyContinue
    if (-not $content) { continue }
    foreach ($src in $sources) {
        $needle = "/$src"
        if ($content.Contains($needle) -or $content.Contains("$src.php")) {
            $hits += [PSCustomObject]@{ File = $file.FullName.Replace("$root\", ''); Source = $src }
        }
    }
}

Write-Output "Internal link hits to redirect sources: $($hits.Count)"
$hits | Sort-Object File, Source -Unique | Format-Table -AutoSize
