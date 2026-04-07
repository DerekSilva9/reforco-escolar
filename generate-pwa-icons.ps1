# Script PowerShell para gerar icones PWA sem depender do ImageMagick.

param(
    [string]$SourceImage = (Join-Path $PSScriptRoot 'public\images\favicon-32x32.png')
)

$ImagesDir = Join-Path $PSScriptRoot 'public\images'

if (-not (Test-Path $SourceImage)) {
    Write-Host "Arquivo nao encontrado: $SourceImage" -ForegroundColor Red
    exit 1
}

if (-not (Test-Path $ImagesDir)) {
    New-Item -ItemType Directory -Path $ImagesDir | Out-Null
}

Add-Type -AssemblyName System.Drawing

$resolvedSource = Resolve-Path $SourceImage
$source = [System.Drawing.Image]::FromFile($resolvedSource)

function New-PwaImage {
    param(
        [string]$OutputPath,
        [int]$Width,
        [int]$Height,
        [double]$Scale,
        [string]$Background
    )

    $bitmap = New-Object System.Drawing.Bitmap $Width, $Height
    $graphics = [System.Drawing.Graphics]::FromImage($bitmap)
    $graphics.Clear([System.Drawing.ColorTranslator]::FromHtml($Background))
    $graphics.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
    $graphics.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
    $graphics.PixelOffsetMode = [System.Drawing.Drawing2D.PixelOffsetMode]::HighQuality
    $graphics.CompositingQuality = [System.Drawing.Drawing2D.CompositingQuality]::HighQuality

    $targetWidth = [int][Math]::Round($Width * $Scale)
    $targetHeight = [int][Math]::Round($Height * $Scale)
    $ratio = [Math]::Min($targetWidth / $source.Width, $targetHeight / $source.Height)
    $drawWidth = [int][Math]::Round($source.Width * $ratio)
    $drawHeight = [int][Math]::Round($source.Height * $ratio)
    $x = [int][Math]::Round(($Width - $drawWidth) / 2)
    $y = [int][Math]::Round(($Height - $drawHeight) / 2)

    $graphics.DrawImage($source, (New-Object System.Drawing.Rectangle $x, $y, $drawWidth, $drawHeight))
    $bitmap.Save($OutputPath, [System.Drawing.Imaging.ImageFormat]::Png)

    $graphics.Dispose()
    $bitmap.Dispose()
}

$targets = @(
    @{ Name = 'icon-192x192.png'; Width = 192; Height = 192; Scale = 0.92; Background = '#FFFFFF' },
    @{ Name = 'icon-512x512.png'; Width = 512; Height = 512; Scale = 0.92; Background = '#FFFFFF' },
    @{ Name = 'icon-192x192-maskable.png'; Width = 192; Height = 192; Scale = 0.70; Background = '#0891b2' },
    @{ Name = 'icon-512x512-maskable.png'; Width = 512; Height = 512; Scale = 0.70; Background = '#0891b2' },
    @{ Name = 'badge-72x72.png'; Width = 72; Height = 72; Scale = 0.78; Background = '#0891b2' },
    @{ Name = 'shortcut-presenca-96x96.png'; Width = 96; Height = 96; Scale = 0.82; Background = '#0f766e' },
    @{ Name = 'shortcut-financeiro-96x96.png'; Width = 96; Height = 96; Scale = 0.82; Background = '#155e75' }
)

foreach ($target in $targets) {
    $outputPath = Join-Path $ImagesDir $target.Name
    New-PwaImage -OutputPath $outputPath -Width $target.Width -Height $target.Height -Scale $target.Scale -Background $target.Background
    Write-Host "Gerado: $outputPath" -ForegroundColor Green
}

$source.Dispose()

Write-Host ""
Write-Host "Concluido. Os icones foram gerados usando a logo informada." -ForegroundColor Cyan
