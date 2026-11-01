# 🔐 Deenio Keystore Generator Script
param(
    [string]$Password = ""
)

$keytoolPath = "C:\Program Files\Android\Android Studio\jbr\bin\keytool.exe"
if (-not (Test-Path $keytoolPath)) {
    # Fallback to keytool in PATH
    $keytoolPath = "keytool"
}

if ([string]::IsNullOrWhiteSpace($Password)) {
    $Password = Read-Host -Prompt "Enter a strong password for your Keystore"
}

if ([string]::IsNullOrWhiteSpace($Password)) {
    Write-Error "Password cannot be empty. Aborted."
    exit 1
}

$keystoreFile = (Join-Path $PSScriptRoot "upload-keystore.jks")
$keyPropertiesFile = (Join-Path $PSScriptRoot "key.properties")

if (Test-Path $keystoreFile) {
    Write-Warning "Keystore file already exists at: $keystoreFile"
    $overwrite = Read-Host "Do you want to overwrite it? (y/N)"
    if ($overwrite -ne "y" -and $overwrite -ne "Y") {
        Write-Host "Operation cancelled."
        exit 0
    }
    Remove-Item $keystoreFile -Force
}

Write-Host "Generating Keystore with keytool..." -ForegroundColor Cyan
& $keytoolPath -genkeypair -v `
    -keystore $keystoreFile `
    -alias deenio_upload `
    -keyalg RSA `
    -keysize 2048 `
    -validity 10000 `
    -storepass $Password `
    -keypass $Password `
    -dname "CN=Deenio, OU=App, O=Huipper, L=Dhaka, ST=Dhaka, C=BD"

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Keystore created successfully at: $keystoreFile" -ForegroundColor Green
    
    # Write key.properties
    $keyPropertiesContent = @"
storePassword=$Password
keyPassword=$Password
keyAlias=deenio_upload
storeFile=upload-keystore.jks
"@
    Set-Content -Path $keyPropertiesFile -Value $keyPropertiesContent -Encoding UTF8
    Write-Host "✅ key.properties configured successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "⚠️  IMPORTANT: Please take a backup of '$keystoreFile' and save your password in a safe place (e.g. Google Drive / Password Manager). If you lose this keystore, you will not be able to update your app on Play Store!" -ForegroundColor Yellow
} else {
    Write-Error "Failed to generate keystore."
}
