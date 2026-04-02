<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogSensitiveActions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Logar ações DELETE, POST e PATCH sensíveis
        if (in_array($request->method(), ['DELETE', 'POST', 'PATCH'])) {
            $sensitiveRoutes = [
                'alunos.destroy',
                'alunos.store',
                'alunos.update',
                'admin.users.destroy',
                'admin.users.store',
                'turmas.destroy',
                'turmas.update',
            ];

            $routeName = $request->route()?->getName();
            
            if (in_array($routeName, $sensitiveRoutes)) {
                Log::channel('security')->info(
                    'Ação sensível detectada',
                    [
                        'user_id' => auth()->id(),
                        'user_name' => auth()->user()?->name,
                        'action' => $request->method(),
                        'route' => $routeName,
                        'ip' => $request->ip(),
                        'url' => $request->url(),
                        'status' => $response->getStatusCode(),
                    ]
                );
            }
        }

        return $response;
    }
}
