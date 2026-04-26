# 🐳 Guia Docker - Reforço Escolar

## 📋 Pré-requisitos

- **Docker**: [Instalar Docker](https://docs.docker.com/get-docker/)
- **Docker Compose**: [Instalar Docker Compose](https://docs.docker.com/compose/install/)

## 🚀 Iniciando o Projeto

### 1. Preparar o arquivo de ambiente

```bash
cp .env.docker .env
```

### 2. Gerar APP_KEY

Se ainda não tiver a APP_KEY gerada:

```bash
# Usando Docker
docker-compose run --rm app php artisan key:generate

# Ou localmente se tiver PHP instalado
php artisan key:generate
```

Depois adicione a chave ao arquivo `.env`:

```bash
APP_KEY=base64:SUA_CHAVE_AQUI
```

### 3. Build e iniciar os containers

```bash
# Build das imagens e start dos containers
docker-compose up -d

# Ou com rebuild
docker-compose up -d --build
```

### 4. Verificar status

```bash
docker-compose ps
```

Todos os containers devem estar com status **Up**.

## 📊 Estrutura dos Serviços

| Serviço     | Container               | Porta     | Descrição                   |
| ----------- | ----------------------- | --------- | --------------------------- |
| **app**     | reforco-escolar-app     | 9000      | Aplicação Laravel (PHP-FPM) |
| **nginx**   | reforco-escolar-nginx   | 80/443    | Web Server                  |
| **db**      | reforco-escolar-db      | 3306      | MySQL Database              |
| **redis**   | reforco-escolar-redis   | 6379      | Cache & Session Store       |
| **mailhog** | reforco-escolar-mailhog | 1025/8025 | Email Testing               |

## 🔗 Acessar a Aplicação

- **Aplicação**: http://localhost
- **MailHog UI**: http://localhost:8025 (para testar emails)
- **Banco de Dados**: localhost:3306
- **Redis**: localhost:6379

## 🛠️ Comandos Úteis

### Gerenciar Containers

```bash
# Parar containers
docker-compose down

# Parar e remover volumes (atenção: deleta dados do BD)
docker-compose down -v

# Ver logs da aplicação
docker-compose logs -f app

# Ver logs do nginx
docker-compose logs -f nginx

# Ver logs do banco de dados
docker-compose logs -f db
```

### Executar Comandos Laravel

```bash
# Migrations
docker-compose exec app php artisan migrate

# Seeders
docker-compose exec app php artisan db:seed

# Tinker (Laravel shell)
docker-compose exec app php artisan tinker

# Cache clear
docker-compose exec app php artisan cache:clear

# Config cache
docker-compose exec app php artisan config:cache

# View cache
docker-compose exec app php artisan view:cache
```

### Comandos Composer

```bash
# Instalar dependências
docker-compose exec app composer install

# Atualizar dependências
docker-compose exec app composer update

# Executar teste
docker-compose exec app composer test
```

### Comandos Node/NPM

```bash
# Instalar dependências
docker-compose exec app npm install

# Build assets
docker-compose exec app npm run build

# Dev assets (hot reload)
docker-compose exec app npm run dev
```

## 🗄️ Banco de Dados

### Acessar MySQL

```bash
# Via Docker
docker-compose exec db mysql -u laravel -p reforco_escolar

# Ou via client local (se instalado)
mysql -h localhost -u laravel -p reforco_escolar
```

**Credenciais:**

- Usuário: `laravel`
- Senha: `laravel`
- Database: `reforco_escolar`
- Host: `localhost` (de fora) ou `db` (dentro do Docker)

## 📧 Testar Emails

1. Acesse MailHog em http://localhost:8025
2. Qualquer email enviado aparecerá lá
3. Configure `MAIL_MAILER=smtp` no `.env` para usar MailHog

## 🔍 Troubleshooting

### Erro: "Cannot connect to Docker daemon"

```bash
# Iniciar Docker Desktop ou daemon
# Ou no Linux:
sudo systemctl start docker
```

### Erro: "Port already in use"

```bash
# Mudar portas no docker-compose.yml ou .env
NGINX_PORT=8080
DB_PORT=3307
```

### Banco de dados não conecta

```bash
# Aguardar MySQL inicializar (pode levar alguns segundos)
# Ver logs
docker-compose logs db

# Reiniciar banco
docker-compose restart db
```

### Permissão negada em storage/

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R 1000:33 storage bootstrap/cache
```

## 🔐 SSL/HTTPS (Produção)

Para habilitar HTTPS:

1. Coloque os certificados em `docker/nginx/ssl/`
2. Descomente a configuração HTTPS em `docker/nginx/conf.d/app.conf`
3. Altere o `server_name` para seu domínio
4. Reinicie os containers: `docker-compose restart nginx`

## 📦 Build para Produção

```bash
# Build otimizado
docker-compose -f docker-compose.yml build

# Push para registry (ex: Docker Hub)
docker tag reforco-escolar-app seu-usuario/reforco-escolar:latest
docker push seu-usuario/reforco-escolar:latest
```

## 📝 Variáveis de Ambiente

Edite `.env` para configurar:

```env
# Aplicação
APP_DEBUG=false          # false em produção
APP_URL=http://seu-dominio.com

# Banco de Dados
DB_PASSWORD=sua-senha-segura
DB_ROOT_PASSWORD=sua-senha-root-segura

# Mail
MAIL_MAILER=smtp
MAIL_HOST=seu-smtp.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@example.com
MAIL_PASSWORD=sua-senha

# Informações da Escola
SCHOOL_ADDRESS="..."
SCHOOL_WHATSAPP="..."
SCHOOL_EMAIL="..."
SCHOOL_MAPS_URL="..."
```

## 🚨 Backup & Restore

### Backup do Banco de Dados

```bash
docker-compose exec db mysqldump -u laravel -p reforco_escolar > backup.sql
```

### Restaurar do Backup

```bash
docker-compose exec -T db mysql -u laravel -p reforco_escolar < backup.sql
```

## 🎯 Próximos Passos

1. ✅ Customizar `.env` com suas informações
2. ✅ Gerar APP_KEY
3. ✅ Executar migrations
4. ✅ Testar a aplicação em http://localhost
5. ✅ Configurar SSL para produção
6. ✅ Fazer backup das configurações

---

**Dúvidas?** Consulte a [Documentação Docker](https://docs.docker.com/) ou [Laravel Docs](https://laravel.com/docs)
