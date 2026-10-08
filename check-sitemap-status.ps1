$sitemapUrl = 'https://www.oxfordhomestudy.com/sitemap.xml'
$outFile = 'd:\Sites\oxfordhomestudy.com\sitemap-non-200.csv'
$ua = 'Mozilla/5.0 (compatible; SitemapChecker/1.0)'

Write-Output 'Fetching sitemap...'
$xmlContent = (Invoke-WebRequest -Uri $sitemapUrl -UseBasicParsing -TimeoutSec 120 -UserAgent $ua).Content
[xml]$xml = $xmlContent
$urls = @($xml.urlset.url | ForEach-Object { [string]$_.loc })
Write-Output "Total URLs: $($urls.Count)"

$failed = New-Object System.Collections.Generic.List[object]
$i = 0

foreach ($url in $urls) {
    $i++
    $status = & curl.exe -s -o NUL -w '%{http_code}' -L --max-time 25 -A $ua $url 2>$null
    if (-not $status) { $status = '0' }
    $status = [int]$status

    if ($status -ne 200) {
        $failed.Add([PSCustomObject]@{ Status = $status; Url = $url })
        Write-Output "NON-200 [$status] $url"
    }

    if ($i % 100 -eq 0) {
        Write-Output "Checked $i / $($urls.Count)..."
    }
}

Write-Output '---SUMMARY---'
Write-Output "Total: $($urls.Count)"
Write-Output "Non-200: $($failed.Count)"
$failed | Group-Object Status | Sort-Object Name | ForEach-Object {
    Write-Output ("Status {0}: {1}" -f $_.Name, $_.Count)
}

if ($failed.Count -gt 0) {
    $failed | Export-Csv -Path $outFile -NoTypeInformation
    Write-Output "Saved details to $outFile"
}
