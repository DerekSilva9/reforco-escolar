# PWA (Progressive Web App) - Jardim do Saber

## 📱 O que foi implementado

Este projeto agora é um **Progressive Web App** completo! Aqui está o que você pode fazer:

### ✨ Funcionalidades

1. **Instalação como App Nativo**
    - Adicionar à tela inicial (iOS e Android)
    - Executa em modo fullscreen sem barra de navegação
    - Ícone customizado na home screen

2. **Acesso Offline**
    - Visualizar dados anteriormente carregados
    - Página elegante quando sem conexão
    - Sincronização automática quando reconectar

3. **Performance Otimizada**
    - Cache inteligente de assets (CSS, JS, imagens)
    - Carregamento rápido com service worker
    - Dados sempre disponíveis mesmo offline

4. **Push Notifications** (pronto para integração)
    - Alertas de mensalidades atrasadas
    - Notificações de faltas
    - Avisos importantes da escola

---

## 🚀 Como Usar

### No Navegador

#### Android Chrome

1. Acesse a aplicação
2. Menu (⋮) → "Instalar app"
3. Confirme

#### iOS Safari

1. Acesse a aplicação
2. Compartilhar (↑) → "Adicionar à Tela Inicial"
3. Confirme

#### Desktop (Opcional)

1. Chrome/Edge → Menu (⋮) → "Instalar 'Jardim do Saber'"
2. Abre como PWA na área de trabalho

---

## 📂 Arquivos Principais

### `/public/manifest.json`

Metadados da PWA:

- Nome e descrição
- Cores (tema)
- Ícones
- Atalhos rápidos
- Screenshots

### `/public/service-worker.js`

Gerenciador de cache e offline:

- Estratégia de cache por tipo de arquivo
- Página offline elegante
- Suporte a background sync
- Suporte a push notifications

### `/public/pwa-init.js`

Inicialização da PWA no navegador:

- Registra service worker
- Solicita permissão de notificações
- Gerencia prompt de instalação

### `/resources/views/offline.blade.php`

Página mostrada quando sem conexão

---

## 🎨 Próximos Passos: Gerar Ícones

Você precisa criar ícones nos seguintes tamanhos e colocá-los em `/public/images/`:

```
✅ icon-192x192.png          (quadrado, sem transparência)
✅ icon-192x192-maskable.png (quadrado com padding para "maskable")
✅ icon-512x512.png          (grande, sem transparência)
✅ icon-512x512-maskable.png (grande com padding para "maskable")
✅ badge-72x72.png           (ícone pequeno para notificações)
✅ shortcut-presenca-96x96.png    (atalho: presença)
✅ shortcut-financeiro-96x96.png  (atalho: financeiro)
✅ screenshot-540x720.png    (mobile screenshot)
✅ screenshot-1280x720.png   (desktop screenshot)
```

### Como Gerar Ícones

#### Opção 1: Online (Recomendado)

1. Vá em https://www.favicon-generator.org/
2. Faça upload do logo original
3. Baixe os ícones PNG
4. Coloque na pasta `/public/images/`

#### Opção 2: ImageMagick (CLI)

```bash
# Converter logo original para diferentes tamanhos
convert logo.png -resize 192x192 public/images/icon-192x192.png
convert logo.png -resize 512x512 public/images/icon-512x512.png
convert logo.png -resize 72x72 public/images/badge-72x72.png
```

#### Opção 3: Figma/Photoshop

- Criar design responsivo
- Exportar em diferentes resoluções
- Otimizar com TinyPNG

---

## 🔔 Ativar Push Notifications (Futuro)

Para enviar notificações, você precisará:

1. **Backend**: Configurar Web Push

```php
// Exemplo
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

$webPush = new WebPush([
    'VAPID' => [
        'subject'   => 'mailto:seu-email@exemplo.com',
        'publicKey' => env('VAPID_PUBLIC_KEY'),
        'privateKey'=> env('VAPID_PRIVATE_KEY'),
    ]
]);
```

2. **Frontend**: Já está pronto em `/public/service-worker.js`

3. **Instalar Package** (quando precisar):

```bash
composer require minishlink/web-push
```

---

## 📊 Status de Implementação

| Feature            | Status                    | Detalhes                        |
| ------------------ | ------------------------- | ------------------------------- |
| Manifest           | ✅ Completo               | Todos os metadados configurados |
| Service Worker     | ✅ Completo               | Cache inteligente implementado  |
| Offline Page       | ✅ Completo               | Página bonita e funcional       |
| Ícones             | ⏳ Pendente               | Precisa gerar os arquivos PNG   |
| Push Notifications | ⏳ Pronto para integração | Código backend esperando VAPID  |
| Background Sync    | ✅ Pronto                 | Sincroniza quando reconectar    |

---

## 🧪 Testar a PWA

### Chrome DevTools

1. F12 → Application
2. Aba "Manifest" → Veja se está carregando
3. Aba "Service Workers" → Deve estar ativo
4. Aba "Cache Storage" → Vê os dados em cache

### Simular Offline

1. F12 → Network
2. Marque "Offline"
3. Recarregue página
4. Deve mostrar a página elegante de offline

### Testar Instalação

1. Abra app em Chrome mobile
2. Menu → "Install app"
3. Confirme
4. Deve adicionar à tela inicial

---

## 🔧 Troubleshooting

### Service Worker não registra

- Verificar console (F12 → Console)
- Certificar que `/pwa-init.js` carrega
- Em dev local, aceitar certificado auto-assinado

### Ícone não aparece

- Certificar que arquivos PNG existem em `/public/images/`
- Limpar cache do navegador
- Tentar em navegador diferente

### Offline page não carrega

- Verificar rota existe: `/offline`
- Service worker deve estar ativo
- Limpar cache da aplicação

---

## 📚 Referências

- [MDN: Progressive Web Apps](https://developer.mozilla.org/pt-BR/docs/Web/Progressive_web_apps)
- [Google: PWA Checklist](https://web.dev/pwa-checklist/)
- [Web.dev: Install Prompt](https://web.dev/customize-install/)
- [Service Workers API](https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API)

---

## 💡 Dicas

1. **Sempre teste em dispositivo real** - Emulador pode ter comportamentos diferentes
2. **HTTPS é necessário em produção** - PWAs só funcionam com protocolo seguro
3. **Versione seu cache** - Altere `CACHE_VERSION` quando atualizar assets
4. **Monitore performance** - Use Lighthouse (F12) para auditar

---

**Desenvolvido com ♡ para Jardim do Saber**
