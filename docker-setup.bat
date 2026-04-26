@echo off
REM Script de setup inicial do Docker para Windows
REM Reforço Escolar

setlocal enabledelayedexpansion

echo ====================================
echo   SETUP DOCKER - REFORÇO ESCOLAR
echo ====================================
echo.

REM Verificar se Docker está instalado
docker --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker não está instalado!
    echo Por favor, instale Docker Desktop: https://www.docker.com/products/docker-desktop
    pause
    exit /b 1
)

echo ✅ Docker detectado
echo.

REM Verificar se Docker Compose está instalado
docker-compose --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker Compose não está instalado!
    echo Docker Desktop já inclui Docker Compose automaticamente.
    pause
    exit /b 1
)

echo ✅ Docker Compose detectado
echo.

REM Copiar .env.docker para .env
if exist ".env" (
    echo ℹ️  Arquivo .env já existe, pulando cópia
) else (
    echo 📋 Criando arquivo .env...
    copy ".env.docker" ".env" >nul
    echo ✅ .env criado a partir de .env.docker
)

echo.
echo 🔨 Buildando imagens Docker...
docker-compose build

if errorlevel 1 (
    echo ❌ Erro ao fazer build das imagens!
    pause
    exit /b 1
)

echo.
echo 🐳 Iniciando containers...
docker-compose up -d

if errorlevel 1 (
    echo ❌ Erro ao iniciar containers!
    pause
    exit /b 1
)

echo.
echo ⏳ Aguardando banco de dados (10 segundos)...
timeout /t 10 /nobreak

echo.
echo 🔑 Gerando APP_KEY...
docker-compose exec -T app php artisan key:generate

echo.
echo 🔄 Executando migrations...
docker-compose exec -T app php artisan migrate --force

echo.
echo 📦 Instalando dependências Node...
docker-compose exec -T app npm install

echo.
echo 🎨 Buildando assets...
docker-compose exec -T app npm run build

echo.
echo ✅ ==========================================
echo ✅ Setup concluído com sucesso!
echo ✅ ==========================================
echo.
echo 🌐 Acesse a aplicação: http://localhost
echo 📧 MailHog UI: http://localhost:8025
echo.
echo Próximos passos:
echo 1. Personalize as informações da escola em .env
echo 2. Visite http://localhost no seu navegador
echo 3. Para parar: docker-compose down
echo 4. Para ver logs: docker-compose logs -f app
echo.
echo Para mais comandos, veja: DOCKER.md
echo.
pause
