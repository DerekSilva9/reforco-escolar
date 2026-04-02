@php
    $user = auth()->user();
    $displayName = $user?->name;

    $todayLong = \Carbon\Carbon::now()
        ->locale('pt_BR')
        ->translatedFormat('l, j \\d\\e F \\d\\e Y');

    $ctaUrl = auth()->check() ? route('dashboard') : route('login');
    $ctaLabel = auth()->check() ? 'Acessar o painel' : 'Área do Aluno';

    $schoolAddress = config('school.address');
    $schoolWhatsapp = config('school.whatsapp');
    $schoolEmail = config('school.email');
    $schoolMapsUrl = config('school.maps_url');

    $schoolAddress = filled($schoolAddress) ? $schoolAddress : 'Avenida Vingt Rosado - Portal Da Chapada, Apodi/RN';
    $schoolWhatsapp = filled($schoolWhatsapp) ? $schoolWhatsapp : '(84) 99476-7155';
    $schoolEmail = filled($schoolEmail) ? $schoolEmail : 'contato@suaescola.com.br';
    $schoolMapsUrl = filled($schoolMapsUrl) ? $schoolMapsUrl : 'https://www.google.com/maps/place/R.+Vingt+Rosado,+1023,+Apodi+-+RN,+59700-000/@-5.6506121,-37.8020545,17z/data=!3m1!4b1!4m5!3m4!1s0x7baf778eb90fbcb:0x3c6e98d7665eaa78!8m2!3d-5.6506121!4d-37.8020545?entry=ttu&g_ep=EgoyMDI2MDMyMy4xIKXMDSoASAFQAw%3D%3D';

    $schoolMapsEmbedUrl = null;
    if (is_string($schoolMapsUrl) && preg_match('/@(-?\\d+(?:\\.\\d+)?),(-?\\d+(?:\\.\\d+)?)/', $schoolMapsUrl, $matches)) {
        $schoolMapsEmbedUrl = 'https://www.google.com/maps?q='.$matches[1].','.$matches[2].'&z=17&output=embed';
    }

    $bannerImageCandidates = [
        'images/escola-banner.jpg',
        'images/escola-banner.jpeg',
        'images/escola-banner.png',
        'images/escola-banner.webp',
    ];

    $bannerImageUrl = null;
    foreach ($bannerImageCandidates as $candidate) {
        if (file_exists(public_path($candidate))) {
            $bannerImageUrl = asset($candidate);
            break;
        }
    }
    $bannerImageUrl ??= asset('images/escola-banner.jpg');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Jardim do Saber Profª Auri Mota') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">

        <style>
            :root {
                --paper: #FDFCF8;
                --paper-muted: #F7F3EC;
                --ink: #0f172a;
            }

            body.page {
                font-family: 'Libre Baskerville', ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;
                color: var(--ink);
                background:
                    radial-gradient(1100px 520px at 85% -8%, rgba(34, 211, 238, 0.25), transparent 60%),
                    radial-gradient(950px 520px at 10% 12%, rgba(22, 163, 74, 0.14), transparent 55%),
                    linear-gradient(180deg, var(--paper) 0%, var(--paper-muted) 60%, var(--paper) 100%);
            }

            .font-title {
                font-family: 'Cormorant Garamond', ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;
            }

            .font-ui {
                font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            }

            @media (prefers-reduced-motion: reduce) {
                html {
                    scroll-behavior: auto !important;
                }
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="page min-h-screen antialiased">
        <a href="#conteudo" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:shadow-sm">
            Pular para o conteúdo
        </a>

        <header class="sticky top-0 z-40 border-b border-slate-900/10 bg-[#FDFCF8]/90 text-slate-950 shadow-sm backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="/" class="flex items-center gap-3 group">
                    <x-application-logo class="w-[38px] h-[42px]" />
                    <div class="leading-tight">
                        <div class="text-lg font-semibold tracking-tight text-slate-950 group-hover:text-slate-900 transition-colors font-title">
                            {{ config('app.name', 'Jardim do Saber Profª Auri Mota') }}
                        </div>
                        <div class="text-xs text-slate-700/80">Educação infantil • acolhimento e descobertas</div>
                    </div>
                </a>

                <nav class="hidden items-center gap-6 text-sm font-medium text-slate-800 md:flex font-ui">
                    <a href="#diferenciais" class="hover:text-slate-950 transition-colors">Diferenciais</a>
                    <a href="#contato" class="hover:text-slate-950 transition-colors">Contato</a>
                </nav>

                <a href="{{ $ctaUrl }}"
                   class="font-ui inline-flex items-center justify-center gap-2 rounded-lg bg-cyan-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-700/30 focus:ring-offset-2 focus:ring-offset-[#FDFCF8]">
                    {{ $ctaLabel }}
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </a>
            </div>
        </header>

        <section aria-label="Banner da escola" class="border-b border-slate-900/10">
            {{-- Coloque a foto do banner em: public/images/escola-banner.(jpg|jpeg|png|webp) --}}
            <div class="relative h-56 w-full sm:h-80"
                 role="img"
                 aria-label="Foto da escola"
                 style="background-image: linear-gradient(90deg, rgba(8,145,178,0.10), rgba(22,163,74,0.08)), url('{{ $bannerImageUrl }}'); background-size: cover; background-position: center 45%;">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/45 via-slate-950/10 to-transparent" aria-hidden="true"></div>
            </div>
        </section>

        <main id="conteudo">
            <section class="py-8 sm:py-12">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <div class="overflow-hidden rounded-lg border border-slate-900/10 bg-white/70 shadow-sm">
                        <div class="relative h-44 sm:h-60" role="img" aria-label="Imagem de destaque da escola (placeholder)">
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-200 via-[#FDFCF8] to-emerald-200"></div>
                            <div class="absolute inset-0 opacity-[0.35]" aria-hidden="true"
                                 style="background-image: repeating-linear-gradient(45deg, rgba(8,145,178,0.06) 0px, rgba(8,145,178,0.06) 1px, transparent 1px, transparent 12px), radial-gradient(900px 420px at 10% 10%, rgba(22,163,74,0.20), transparent 60%);">
                            </div>
                            <svg viewBox="0 0 220 150"
                                 class="pointer-events-none absolute -right-20 -top-14 h-44 w-72 text-emerald-700/25"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="M12 132 C 60 35, 135 170, 208 18" />
                                <path d="M72 68 C 62 60, 62 48, 76 44 C 90 40, 96 54, 86 64 C 80 70, 78 70, 72 68 Z" fill="currentColor" stroke="none" />
                                <path d="M116 44 C 106 36, 104 26, 118 22 C 132 18, 138 32, 128 40 C 122 46, 120 46, 116 44 Z" fill="currentColor" stroke="none" />
                                <path d="M160 68 C 150 60, 150 48, 164 44 C 178 40, 184 54, 174 64 C 168 70, 166 70, 160 68 Z" fill="currentColor" stroke="none" />
                            </svg>
                            <div class="relative flex h-full items-end p-6 sm:p-10">
                                <div class="max-w-md rounded-lg border border-white/20 bg-slate-950/70 px-4 py-3 text-white shadow-sm backdrop-blur">
                                    <div class="font-ui text-xs font-semibold uppercase tracking-wider text-cyan-200">Acolhimento e descobertas</div>
                                    <div class="mt-1 text-sm font-semibold">Brincar • Descobrir • Crescer</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-[#FDFCF8]/80 px-6 py-10 sm:px-10">

                            <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-tight sm:text-4xl font-title text-slate-950">
                                Um jardim onde aprender floresce.
                            </h1>

                            <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-700">
                                Educação infantil com afeto, rotina e aprendizagem lúdica — para a criança se desenvolver com segurança e autonomia.
                            </p>

                            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                                <a href="{{ $ctaUrl }}"
                                   class="font-ui inline-flex items-center justify-center gap-2 rounded-lg bg-cyan-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-700/30 focus:ring-offset-2 focus:ring-offset-[#FDFCF8]">
                                    {{ $ctaLabel }}
                                    <span>→</span>
                                </a>

                                <a href="#contato"
                                   class="font-ui inline-flex items-center justify-center gap-2 rounded-lg border border-slate-900/10 bg-white/75 px-5 py-3 text-sm font-semibold text-slate-900 shadow-sm transition-colors hover:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-700/25 focus:ring-offset-2 focus:ring-offset-[#FDFCF8]">
                                    Fale com a escola
                                    <span class="text-cyan-700">→</span>
                                </a>
                            </div>

                            <dl class="mt-8 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-lg border border-slate-900/10 bg-white/75 p-4 shadow-sm">
                                    <dt class="font-ui text-xs font-semibold uppercase tracking-wider text-slate-600">Acolhimento</dt>
                                    <dd class="mt-1 text-sm font-semibold text-slate-900">No dia a dia</dd>
                                </div>
                                <div class="rounded-lg border border-slate-900/10 bg-white/75 p-4 shadow-sm">
                                    <dt class="font-ui text-xs font-semibold uppercase tracking-wider text-slate-600">Rotina</dt>
                                    <dd class="mt-1 text-sm font-semibold text-slate-900">Manhã e tarde</dd>
                                </div>
                                <div class="rounded-lg border border-slate-900/10 bg-white/75 p-4 shadow-sm">
                                    <dt class="font-ui text-xs font-semibold uppercase tracking-wider text-slate-600">Comunicação</dt>
                                    <dd class="mt-1 text-sm font-semibold text-slate-900">Com responsáveis</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </section>

            <section id="diferenciais" class="py-10 sm:py-14 bg-cyan-50/30 border-y border-slate-900/10">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <h2 class="text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl font-title">
                        Diferenciais
                    </h2>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-700">
                        Acolhimento, rotina e comunicação com a família — para a criança aprender com leveza e propósito.
                    </p>

                    <div class="mt-8 grid gap-6 md:grid-cols-3">
                        <div class="rounded-lg border border-slate-900/10 border-t-4 border-t-cyan-700 bg-white/75 p-6 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="grid h-11 w-11 place-items-center rounded-lg border border-slate-900/10 bg-cyan-50">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5 text-cyan-900" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7Z" />
                                        <path d="M12 9.5a2.2 2.2 0 1 0 0 .1Z" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-slate-950 font-title">Espaço acolhedor</h3>
                            </div>
                            <p class="mt-3 text-sm leading-relaxed text-slate-700">
                                Um ambiente bonito e seguro, pensado para o bem-estar e para a autonomia da criança.
                            </p>
                        </div>

                        <div class="rounded-lg border border-slate-900/10 border-t-4 border-t-slate-950 bg-white/75 p-6 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="grid h-11 w-11 place-items-center rounded-lg border border-slate-900/10 bg-slate-50">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5 text-slate-900" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-4-7 4V5a2 2 0 0 1 2-2z" />
                                        <path d="M9 7h6M9 10h6" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-slate-950 font-title">Comunicação com a família</h3>
                            </div>
                            <p class="mt-3 text-sm leading-relaxed text-slate-700">
                                Recados, presença e observações em um só lugar, com clareza e linguagem simples.
                            </p>
                        </div>

                        <div class="rounded-lg border border-slate-900/10 border-t-4 border-t-emerald-700 bg-white/75 p-6 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="grid h-11 w-11 place-items-center rounded-lg border border-slate-900/10 bg-emerald-50">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5 text-emerald-800" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 8v4l3 3" />
                                        <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-slate-950 font-title">Rotina com leveza</h3>
                            </div>
                            <p class="mt-3 text-sm leading-relaxed text-slate-700">
                                Previsibilidade e carinho para criar hábitos, vínculo e um aprender mais tranquilo.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-10 sm:py-14">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <div class="rounded-lg border border-slate-900/10 bg-slate-950 p-6 shadow-sm sm:p-8">
                        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-xl font-semibold tracking-tight text-white sm:text-2xl font-title">
                                    Acompanhe recados, presença e observações
                                </h2>
                                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-200">
                                    A Área do Aluno reúne informações do dia a dia para o responsável acompanhar com tranquilidade e clareza.
                                </p>
                            </div>
                            <a href="{{ $ctaUrl }}"
                               class="font-ui inline-flex items-center justify-center gap-2 rounded-lg bg-cyan-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 focus:ring-offset-2 focus:ring-offset-slate-950">
                                {{ auth()->check() ? 'Abrir o painel' : 'Entrar na Área do Aluno' }}
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="contato" class="py-10 sm:py-14 bg-[#FDFCF8]/70 border-y border-slate-900/10">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <div class="grid gap-8 lg:grid-cols-2">
                        <div>
                            <h2 class="text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl font-title">
                                Contato e localização
                            </h2>
                            <p class="mt-3 text-sm leading-relaxed text-slate-700">
                                Venha conhecer o espaço. Será um prazer receber você e sua família.
                            </p>

                            <div class="mt-6 space-y-4">
                                <div class="rounded-lg border border-slate-900/10 bg-white/75 p-5 shadow-sm">
                                    <div class="font-ui text-xs font-semibold uppercase tracking-wider text-slate-900">Endereço</div>
                                    <div class="mt-1 text-sm text-slate-700">{{ $schoolAddress }}</div>
                                    <a class="mt-2 font-ui inline-flex items-center gap-2 text-sm font-semibold text-cyan-800 underline decoration-cyan-700/40 underline-offset-4 hover:decoration-cyan-700"
                                       href="{{ $schoolMapsUrl }}" target="_blank" rel="noreferrer">
                                        Abrir no mapa <span>↗</span>
                                    </a>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-lg border border-slate-900/10 bg-white/75 p-5 shadow-sm">
                                        <div class="font-ui text-xs font-semibold uppercase tracking-wider text-emerald-800">WhatsApp</div>
                                        <div class="mt-1 text-sm text-slate-700">{{ $schoolWhatsapp }}</div>
                                    </div>
                                    <div class="rounded-lg border border-slate-900/10 bg-white/75 p-5 shadow-sm">
                                        <div class="font-ui text-xs font-semibold uppercase tracking-wider text-cyan-800">E-mail</div>
                                        <div class="mt-1 text-sm text-slate-700">{{ $schoolEmail }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg border border-slate-900/10 bg-white/75 p-6 shadow-sm">
                            <h3 class="text-base font-semibold text-slate-950 font-title">Mapa</h3>
                            <p class="mt-1 text-sm text-slate-700">
                                Veja como chegar até a escola.
                            </p>

                            @if ($schoolMapsEmbedUrl)
                                <div class="mt-5 overflow-hidden rounded-lg border border-slate-900/10 bg-white">
                                    <iframe
                                        title="Mapa de localização"
                                        src="{{ $schoolMapsEmbedUrl }}"
                                        class="h-64 w-full"
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            @else
                                <div class="mt-5 grid grid-cols-6 gap-2 rounded-lg border border-slate-900/10 bg-cyan-50/40 p-4">
                                    <div class="col-span-4 h-28 rounded-lg border border-slate-900/10 bg-white/70"></div>
                                    <div class="col-span-2 h-28 rounded-lg border border-slate-900/10 bg-white/60"></div>
                                    <div class="col-span-3 h-14 rounded-lg border border-slate-900/10 bg-white/70"></div>
                                    <div class="col-span-3 h-14 rounded-lg border border-slate-900/10 bg-white/60"></div>
                                </div>
                            @endif

                            <a href="{{ $schoolMapsUrl }}" target="_blank" rel="noreferrer"
                               class="mt-5 font-ui inline-flex items-center justify-center gap-2 rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-950/30 focus:ring-offset-2 focus:ring-offset-white">
                                Ver no Google Maps <span>↗</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-900/10 bg-[#FDFCF8]/70">
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-slate-700">
                        <span class="font-semibold text-slate-950 font-title">{{ config('app.name', 'Jardim do Saber Profª Auri Mota') }}</span>
                        <span class="mx-2 text-cyan-700">•</span>
                        <span>Acolhimento, rotina e descobertas</span>
                    </div>
                    <div class="font-ui text-xs text-slate-600">© {{ now()->year }}. Todos os direitos reservados.</div>
                </div>
            </div>
        </footer>
    </body>
</html>
