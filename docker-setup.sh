#!/bin/bash
# Script de setup inicial do Docker

set -e

echo "🚀 Iniciando setup da aplicação no Docker..."
echo ""

# Verificar se Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale Docker primeiro."
    exit 1
fi

# Verificar se Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale Docker Compose primeiro."
    exit 1
fi

echo "✅ Docker e Docker Compose detectados"
echo ""

# Copiar arquivo de ambiente
if [ ! -f .env ]; then
    echo "📋 Criando arquivo .env..."
    cp .env.docker .env
    echo "✅ .env criado a partir de .env.docker"
else
    echo "ℹ️  .env já existe, pulando cópia"
fi

echo ""
echo "🔨 Buildando imagens Docker..."
docker-compose build

echo ""
echo "🐳 Iniciando containers..."
docker-compose up -d

echo ""
echo "⏳ Aguardando banco de dados ficar pronto..."
sleep 10

echo ""
echo "🔑 Gerando APP_KEY..."
docker-compose exec -T app php artisan key:generate --show > /tmp/appkey.txt
APP_KEY=$(cat /tmp/appkey.txt | grep "base64" | cut -d'=' -f2 | xargs)

if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY não foi gerada automaticamente"
    echo "Execute manualmente: docker-compose exec app php artisan key:generate"
else
    sed -i "s/APP_KEY=/APP_KEY=$APP_KEY/" .env
    echo "✅ APP_KEY gerada e configurada"
fi

echo ""
echo "🔄 Executando migrations..."
docker-compose exec -T app php artisan migrate --force

echo ""
echo "💾 Executando seeders (opcional)..."
docker-compose exec -T app php artisan db:seed || true

echo ""
echo "🧹 Limpando caches..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

echo ""
echo "📦 Instalando dependências Node..."
docker-compose exec -T app npm install || true

echo ""
echo "🎨 Buildando assets..."
docker-compose exec -T app npm run build || true

echo ""
echo "✅ =========================================="
echo "✅ Setup concluído com sucesso!"
echo "✅ =========================================="
echo ""
echo "🌐 Acesse a aplicação em: http://localhost"
echo "📧 MailHog UI em: http://localhost:8025"
echo ""
echo "Próximos passos:"
echo "1. Personalize as informações da escola em .env"
echo "2. Visite http://localhost para testar"
echo "3. Para parar os containers: docker-compose down"
echo "4. Para ver logs: docker-compose logs -f app"
echo ""
echo "Para mais comandos, veja: make help ou cat DOCKER.md"
echo ""
