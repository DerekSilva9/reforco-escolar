#!/usr/bin/env bash

# Quick reference card - Reforço Escolar Docker

cat << 'EOF'

╔══════════════════════════════════════════════════════════════════╗
║ ║
║ 🐳 REFORÇO ESCOLAR - DOCKER QUICK START GUIDE 🐳 ║
║ ║
╚══════════════════════════════════════════════════════════════════╝

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🚀 INICIAR TUDO (3 PASSOS)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1️⃣ WINDOWS:
• Duplo-clique em: docker-setup.bat
OU abra PowerShell e digite:
.\docker-setup.bat

2️⃣ LINUX/macOS:
chmod +x docker-setup.sh
./docker-setup.sh

3️⃣ AGUARDE... (leva ~5 minutos na primeira vez)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🌐 ACESSAR A APLICAÇÃO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

App: http://localhost
MailHog: http://localhost:8025 (testar emails)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
⚡ COMANDOS PRINCIPAIS (com Make)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

make up → Iniciar containers
make down → Parar containers
make logs → Ver logs da aplicação
make shell → Acessar bash do container
make migrate → Rodar migrations
make test → Executar testes
make artisan cmd="..." → Executar comando artisan
make help → Ver todos os comandos

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🐳 COMANDOS DOCKER (sem Make)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

docker-compose up -d
docker-compose down
docker-compose logs -f app
docker-compose exec app bash
docker-compose exec app php artisan migrate

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📋 SERVIÇOS DISPONÍVEIS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

🔵 app (PHP-FPM) → Aplicação Laravel
🟢 nginx (port 80) → Web Server
🟠 db (port 3306) → MySQL Database
🔴 redis (port 6379) → Cache
💌 mailhog (port 8025) → Email Testing

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🔑 CREDENCIAIS PADRÃO (mudar em .env para produção!)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

MySQL:
Host: db (dentro do Docker) ou localhost (de fora)
Usuário: laravel
Senha: laravel
Database: reforco_escolar

Redis:
Host: redis
Port: 6379

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📚 DOCUMENTAÇÃO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

DOCKER.md → Guia completo com exemplos
ANALISE_DOCKER.md → Stack técnico e análise detalhada
DOCKER-SETUP-CHECKLIST.md → Checklist e troubleshooting

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🔧 CONFIGURAR PARA PRODUÇÃO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1. Edite .env com suas informações:
   APP_DEBUG=false
   APP_URL=https://seu-dominio.com
   DB_PASSWORD=sua-senha-segura
   SCHOOL_ADDRESS, SCHOOL_WHATSAPP, etc

2. Configure SSL:
   • Coloque certificados em docker/nginx/ssl/
   • Descomente HTTPS em docker/nginx/conf.d/app.conf

3. Deploy:
   docker-compose up -d --build

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🐛 PROBLEMAS COMUNS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

❌ "Port already in use"
→ Mude em .env: NGINX_PORT=8080

❌ "MySQL not connecting"
→ Aguarde 10s e tente: docker-compose restart db

❌ "Permission denied"
→ Run: docker-compose exec app chmod -R 775 storage

❌ "npm/composer not found"
→ Run: docker-compose exec app npm install
docker-compose exec app composer install

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
💾 BACKUP & RESTORE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

# Backup

make backup-db

# Restore

make restore-db file="backup.sql"

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📊 MONITORAR APLICAÇÃO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Ver status de containers:
docker-compose ps

Ver logs em tempo real:
make logs

Entrar em um container:
make shell

Executar comandos:
make artisan cmd="tinker"

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

✨ Tudo pronto! Execute docker-setup.bat (Windows) ou docker-setup.sh
(Linux/macOS) para começar!

╔══════════════════════════════════════════════════════════════════╗
║ 🚀 BORA CODAR! 🚀 ║
╚══════════════════════════════════════════════════════════════════╝

EOF
