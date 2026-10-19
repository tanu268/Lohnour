$routes = @('/', '/services/', '/about/', '/contact/', '/blog/', '/guides/', '/pathway/corporate-ai-training/', '/coaching/')
foreach ($r in $routes) {
    $url = "https://pro.lohnour.com" + $r
    $header = (curl.exe -s -k -I $url | Select-Object -First 1).Trim()
    Write-Host "$r -> $header"
}
