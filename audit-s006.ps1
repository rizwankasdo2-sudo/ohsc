$ErrorActionPreference = "Stop"
$base = "https://www.oxfordhomestudy.com"
$ua = "S006Audit/1.0"
$root = "d:\Sites\oxfordhomestudy.com"

function Normalize-Path([string]$Path) {
    if ([string]::IsNullOrWhiteSpace($Path)) { return '' }
    $p = $Path.Trim()
    if ($p -match '^https?://[^/]+(.*)$') { $p = $Matches[1] }
    $p = $p -replace '\?.*$',''
    if ($p -eq '' -or $p -eq '/') { return '' }
    return $p.Trim('/').ToLower()
}

$ht = Get-Content "$root\.htaccess"
$sources = New-Object 'System.Collections.Generic.HashSet[string]'
foreach ($line in $ht) {
    $line = $line.Trim()
    if ($line -match 'RewriteRule\s+\^(.+?)\s+(\S+)\s+\[.*R=30[12]') {
        $patternRaw = $Matches[1].Trim()
        if ($patternRaw -match '\(\.\*\)|\(\?:|\(\?|\(\$|\(\d|\+\|\[') { continue }
        $src = ($patternRaw -replace '\\-','-' -replace '\\\.','.' -replace '/\?\$','' -replace '/\?$','' -replace '\?$','' -replace '\$$','').Trim('/').ToLower()
        if ($src) { [void]$sources.Add($src) }
    } elseif ($line -match '^Redirect\s+301\s+(\S+)\s+(\S+)') {
        [void]$sources.Add((Normalize-Path $Matches[1]))
    }
}

$idx = (Invoke-WebRequest -Uri "$base/sitemap.xml" -UseBasicParsing -TimeoutSec 60 -UserAgent $ua).Content
[xml]$xmlIdx = $idx
$allUrls = @()
foreach ($sm in $xmlIdx.sitemapindex.sitemap) {
    $content = (Invoke-WebRequest -Uri $sm.loc -UseBasicParsing -TimeoutSec 120 -UserAgent $ua).Content
    [xml]$xml = $content
    $allUrls += @($xml.urlset.url | ForEach-Object { [string]$_.loc })
}

$sitemapHits = @()
foreach ($url in $allUrls) {
    $path = Normalize-Path $url
    if ($sources.Contains($path)) { $sitemapHits += $url }
}

Write-Output "=== S-006 AUDIT SUMMARY ==="
Write-Output "Redirect sources parsed: $($sources.Count)"
Write-Output "Live sitemap URLs: $($allUrls.Count)"
Write-Output "Sitemap URLs that are redirect sources: $($sitemapHits.Count)"
if ($sitemapHits.Count -gt 0) {
    Write-Output "-- Sitemap redirect hits --"
    $sitemapHits | ForEach-Object { Write-Output $_ }
}

Write-Output ""
Write-Output "=== Live multi-hop check (all redirect sources, max 3 concurrent batches) ==="
$multi = @()
$i = 0
foreach ($src in $sources) {
    $i++
    $url = "$base/$src"
    $hops = 0
    $current = $url
    $seen = New-Object 'System.Collections.Generic.HashSet[string]'
    while ($hops -lt 5) {
        if (-not $seen.Add($current.ToLower())) { break }
        $headers = curl.exe -sI -A $ua --max-time 15 $current 2>$null
        $status = ($headers | Select-String '^HTTP/' | Select-Object -First 1).Line
        if ($status -notmatch ' 30[12] ') { break }
        $hops++
        $loc = (($headers | Select-String -Pattern '^Location:' -CaseSensitive:$false | Select-Object -First 1).Line -replace '^Location:\s*','').Trim()
        if (-not $loc) { break }
        $current = $loc
    }
    if ($hops -gt 1) { $multi += [PSCustomObject]@{ Source = $src; Hops = $hops; Final = $current } }
    if ($i % 100 -eq 0) { Write-Output "Checked $i / $($sources.Count) redirect sources..." }
}
Write-Output "Multi-hop redirect sources: $($multi.Count)"
$multi | Select-Object -First 20 | Format-Table -AutoSize

Write-Output ""
Write-Output "=== Internal code links to redirect sources (active site files) ==="
$codeFiles = Get-ChildItem -Path $root -Recurse -Include *.php,*.html -File |
    Where-Object { $_.FullName -notmatch '\\(lms|admin|vendor|postmark|node_modules|Resources\\admin_)\\' -and $_.Name -notmatch ' Copy' }
$internalHits = @()
foreach ($file in $codeFiles) {
    $content = Get-Content -LiteralPath $file.FullName -Raw -ErrorAction SilentlyContinue
    if (-not $content) { continue }
    foreach ($src in $sources) {
        if ($content -match [regex]::Escape("/$src") -or $content -match [regex]::Escape("/$src/") -or $content -match [regex]::Escape("$src.php")) {
            $internalHits += [PSCustomObject]@{ File = $file.FullName.Replace("$root\\", ''); Source = $src }
        }
    }
}
Write-Output "Internal link hits to redirect sources: $($internalHits.Count)"
$internalHits | Sort-Object File, Source -Unique | Select-Object -First 40 | Format-Table -AutoSize
