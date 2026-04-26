# 🚀 GUIA DE DEPLOY - REFORÇO ESCOLAR

## Resumo de Opções de Deploy

| Plataforma                    | Custo       | Facilidade | Recomendado    |
| ----------------------------- | ----------- | ---------- | -------------- |
| **DigitalOcean App Platform** | $12-15/mês  | ⭐⭐⭐⭐⭐ | ✅ Iniciantes  |
| **Heroku**                    | $7-25/mês   | ⭐⭐⭐⭐⭐ | ✅ Muito fácil |
| **Railway**                   | $5-20/mês   | ⭐⭐⭐⭐⭐ | ✅ Simples     |
| **AWS ECS**                   | Variável    | ⭐⭐⭐     | Pro            |
| **Google Cloud Run**          | $0.10-2/mês | ⭐⭐⭐⭐   | Escalável      |
| **VPS (Linode, Vultr)**       | $6-12/mês   | ⭐⭐⭐     | Controle total |

---

## 🟢 OPÇÃO 1: Railway (Mais Fácil) ⭐ Recomendado

### Setup em 10 minutos

1. **Criar conta**
    - Acesse: https://railway.app
    - Sign up com GitHub

2. **Deploy via GitHub**

    ```bash
    git push origin main
    ```

    - Railway detecta automaticamente Docker
    - Cria containers e deploys

3. **Variáveis de Ambiente**
    - Dashboard → Variables
    - Copie de `.env.docker`:
        ```
        APP_NAME=Reforço Escolar
        APP_ENV=production
        APP_DEBUG=false
        APP_URL=https://seu-app.railway.app
        DB_CONNECTION=mysql
        REDIS_URL=redis://seu-redis:6379
        ```

4. **Banco de Dados**
    - Add MySQL plugin
    - Railway cria automaticamente
    - Connections já configuradas

5. **Pronto! 🎉**
    - Acesse: https://seu-app.railway.app

### Custo

- Banco de dados: ~$3/mês
- App: ~$5/mês (consumo)
- Total: ~$8-10/mês

---

## 🔵 OPÇÃO 2: DigitalOcean App Platform

### Setup

1. **Criar Droplet** (VPS)

    ```bash
    - Tamanho: $5/mês (Basic)
    - Distribuição: Ubuntu 22.04
    - Région: São Paulo (mais próximo)
    ```

2. **SSH e Clonar Projeto**

    ```bash
    ssh root@seu-ip-digital-ocean

    # Instalar Docker
    curl -fsSL https://get.docker.com -o get-docker.sh
    sh get-docker.sh

    # Clonar repo
    git clone https://github.com/seu-usuario/reforco-escolar.git
    cd reforco-escolar
    ```

3. **Preparar Variáveis**

    ```bash
    cp .env.docker .env

    # Edite .env com suas configurações
    nano .env
    ```

4. **Build e Deploy**

    ```bash
    docker-compose up -d --build

    # Rodar migrations
    docker-compose exec app php artisan migrate --force
    ```

5. **Configurar Nginx (Reverse Proxy)**
    ```bash
    # Já configurado no docker-compose!
    # Acesse: http://seu-ip
    ```

### Custo

- Droplet: $5/mês
- Banco de dados gerenciado: +$15/mês (opcional)
- Total: $5-20/mês

---

## 🟣 OPÇÃO 3: Heroku

Heroku descontinuou free tier, mas ainda suporta Docker.

```bash
# Instalar Heroku CLI
# https://devcenter.heroku.com/articles/heroku-cli

# Login
heroku login

# Criar app
heroku create seu-app-name

# Adicionar MySQL
heroku addons:create jawsdb:kitefin

# Deploy
git push heroku main

# Rodar migrations
heroku run "php artisan migrate"
```

### Custo

- Dynos: ~$7/mês (eco)
- Banco: ~$15/mês
- Total: ~$22/mês

---

## ☁️ OPÇÃO 4: AWS ECS (Scalável)

### Arquitetura

```
CloudFront (CDN) → ALB (Load Balancer) → ECS Cluster
                                        ├─ App Containers
                                        ├─ MySQL RDS
                                        └─ ElastiCache Redis
```

### Setup (mais complexo)

```bash
# 1. Push da imagem para ECR
aws ecr create-repository --repository-name reforco-escolar

# Tag
docker tag reforco-escolar-app:latest \
  123456789.dkr.ecr.us-east-1.amazonaws.com/reforco-escolar:latest

# Push
docker push 123456789.dkr.ecr.us-east-1.amazonaws.com/reforco-escolar:latest

# 2. Criar ECS Cluster (via AWS Console ou CLI)
# 3. Criar Task Definition
# 4. Criar Service
```

### Custo

- EC2: ~$10/mês
- RDS MySQL: ~$15/mês
- ALB: ~$20/mês
- ElastiCache: ~$15/mês
- Total: ~$60+/mês

---

## 🌐 OPÇÃO 5: VPS Simples (Linode/Vultr)

### Setup Manual

```bash
# 1. SSH no servidor
ssh root@seu-servidor

# 2. Instalar Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh

# 3. Clonar projeto
git clone https://github.com/seu-usuario/reforco-escolar.git
cd reforco-escolar

# 4. Setup
cp .env.docker .env
# Editar .env com configs reais
nano .env

# 5. Deploy
docker-compose up -d --build

# 6. Migrar BD
docker-compose exec -T app php artisan migrate --force
```

### Configurar SSL com Certbot

```bash
# Instalar Certbot
sudo apt-get install certbot python3-certbot-nginx

# Gerar certificado
sudo certbot certonly --standalone -d seu-dominio.com

# Copiar para Docker
sudo cp /etc/letsencrypt/live/seu-dominio.com/fullchain.pem docker/nginx/ssl/cert.pem
sudo cp /etc/letsencrypt/live/seu-dominio.com/privkey.pem docker/nginx/ssl/key.pem

# Descomente HTTPS em docker/nginx/conf.d/app.conf
# Restart
docker-compose restart nginx
```

### Custo

- VPS: $6-12/mês
- Domínio: ~$12/ano
- Total: $6-12/mês

---

## ⚙️ COMMON SETUP FOR ALL PLATFORMS

### 1. Variáveis de Ambiente Críticas

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com

# Senha FORTE!
DB_PASSWORD=SenhaFORTE123!@#
REDIS_PASSWORD=RedisPassword123!@#

# Mail - Configure com seu SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app

# Informações da escola
SCHOOL_ADDRESS="Sua Escola, 123"
SCHOOL_WHATSAPP="(11) 99999-9999"
SCHOOL_EMAIL="contato@suaescola.com"
```

### 2. Executar Migrations

```bash
docker-compose exec -T app php artisan migrate --force
```

### 3. Gerar APP_KEY

```bash
docker-compose exec app php artisan key:generate --show
```

### 4. Caches para Produção

```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### 5. Backup Automático

```bash
# Cron job para backup diário (adicione ao crontab)
0 2 * * * cd /path/to/app && docker-compose exec -T db mysqldump -u root -p${DB_PASSWORD} ${DB_DATABASE} > /backups/db_$(date +\%Y\%m\%d).sql
```

---

## 📱 MONITORAMENTO

### Logs

```bash
# Ver logs em tempo real
docker-compose logs -f app

# Logs persistentes
docker-compose logs app > app.log
```

### Health Checks

```bash
# Verificar se app está saudável
docker-compose ps

# Todos devem estar "Up"
```

### Backup de Segurança

```bash
# Fazer backup diário
docker-compose exec -T db mysqldump -u laravel -p${DB_PASSWORD} reforco_escolar > backup_$(date +%Y%m%d).sql

# Manter últimos 7 dias
find . -name "backup_*.sql" -mtime +7 -delete
```

---

## 🔐 SEGURANÇA PARA PRODUÇÃO

### Checklist

- ✅ `APP_DEBUG=false` no .env
- ✅ `APP_ENV=production` no .env
- ✅ APP_KEY gerada e segura
- ✅ HTTPS/SSL configurado
- ✅ Senhas seguras no banco
- ✅ CORS configurado
- ✅ Rate limiting ativo
- ✅ Backup automático
- ✅ Logs monitorados
- ✅ Firewall configurado

### Firewall (ufw)

```bash
# SSH
sudo ufw allow 22/tcp

# HTTP
sudo ufw allow 80/tcp

# HTTPS
sudo ufw allow 443/tcp

# MySQL (apenas interno)
sudo ufw allow from 127.0.0.1 to any port 3306

# Enable
sudo ufw enable
```

---

## 🔄 CI/CD (GitHub Actions)

Crie `.github/workflows/deploy.yml`:

```yaml
name: Deploy

on:
    push:
        branches: [main]

jobs:
    deploy:
        runs-on: ubuntu-latest
        steps:
            - uses: actions/checkout@v3

            - name: Deploy via SSH
              uses: appleboy/ssh-action@master
              with:
                  host: ${{ secrets.HOST }}
                  username: ${{ secrets.USER }}
                  key: ${{ secrets.SSH_KEY }}
                  script: |
                      cd /app
                      git pull origin main
                      docker-compose up -d --build
                      docker-compose exec -T app php artisan migrate --force
```

---

## 📊 TROUBLESHOOTING

### Aplicação lenta

```bash
# Verificar logs
docker-compose logs app | tail -50

# Executar migrations
docker-compose exec app php artisan migrate

# Clear caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
```

### Banco de dados cheio

```bash
# Verificar tamanho
docker-compose exec db du -sh /var/lib/mysql

# Arquivos antigos
docker-compose exec app php artisan storage:cleanup
```

### Memória insuficiente

```bash
# Aumentar limit em docker-compose.yml:
# memory: 1g

docker-compose up -d --build
```

---

## 📚 REFERÊNCIAS

- [Docker Docs](https://docs.docker.com/)
- [Laravel Deploy](https://laravel.com/docs/deployment)
- [Railway Docs](https://docs.railway.app/)
- [DigitalOcean Docs](https://docs.digitalocean.com/)
- [AWS ECS Docs](https://docs.aws.amazon.com/ecs/)

---

## 🎯 RECOMENDAÇÃO FINAL

Para **iniciar rapidamente**: **Railway** ou **Heroku**
Para **controle total**: **VPS (Linode/Vultr)**
Para **escalabilidade**: **AWS ECS** ou **Google Cloud**

Comece simples, escale depois! 🚀
