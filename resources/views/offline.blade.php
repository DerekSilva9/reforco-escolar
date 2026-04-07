<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sem Conexão - Jardim do Saber</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon {
            font-size: 80px;
            margin-bottom: 24px;
            display: block;
        }

        h1 {
            color: #1f2937;
            margin: 0 0 12px 0;
            font-size: 28px;
            font-weight: 700;
        }

        .subtitle {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        p {
            color: #6b7280;
            margin: 0 0 32px 0;
            line-height: 1.6;
            font-size: 16px;
        }

        .hints {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            text-align: left;
        }

        .hint {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }

        .hint:last-child {
            margin-bottom: 0;
        }

        .hint-icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .hint-text {
            color: #374151;
            font-size: 14px;
            line-height: 1.5;
        }

        .hint-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        button, a {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .status {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }

        .status-text {
            color: #9ca3af;
            font-size: 13px;
        }

        .spinner {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid #e5e7eb;
            border-top-color: #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 6px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <span class="icon">📵</span>
        <p class="subtitle">Status da Conexão</p>
        <h1>Sem Conexão à Internet</h1>
        <p>Verifique sua conexão com a internet e tente novamente.</p>

        <div class="hints">
            <div class="hint">
                <div class="hint-icon">💾</div>
                <div class="hint-text">
                    <div class="hint-title">Recursos salvos</div>
                    <div>Arquivos basicos do app continuam disponiveis mesmo sem conexao.</div>
                </div>
            </div>
            <div class="hint">
                <div class="hint-icon">🔄</div>
                <div class="hint-text">
                    <div class="hint-title">Atualize ao reconectar</div>
                    <div>Assim que a internet voltar, recarregue a pagina para ver os dados mais recentes.</div>
                </div>
            </div>
        </div>

        <div class="actions">
            <button class="btn-primary" onclick="location.reload()">
                Tentar Novamente
            </button>
            <a href="/" class="btn-secondary">Voltar ao Início</a>
        </div>

        <div class="status">
            <div class="status-text">
                <span class="spinner"></span>
                Monitorando conexão...
            </div>
        </div>
    </div>

    <script>
        // Monitor connection status
        function checkConnection() {
            fetch('/manifest.json', { method: 'HEAD', cache: 'no-store' })
                .then(() => {
                    console.log('Connected! Reloading...');
                    // Small delay to ensure connection is stable
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(err => {
                    console.log('Still offline');
                });
        }

        // Check every 2 seconds
        setInterval(checkConnection, 2000);

        // Also check on online event
        window.addEventListener('online', () => {
            console.log('Connection restored!');
            setTimeout(() => location.reload(), 500);
        });
    </script>
</body>
</html>
