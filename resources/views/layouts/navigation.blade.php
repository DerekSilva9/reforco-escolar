@once
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    .nav-link-item {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.8625rem;
        font-weight: 500;
        letter-spacing: 0.04em;
        color: #93c5fd;
        padding: 0.375rem 0.85rem;
        border-radius: 6px;
        transition: all 0.18s ease;
        text-decoration: none;
        position: relative;
        min-height: 32px;
        display: inline-flex;
        align-items: center;
    }
    .nav-link-item:hover {
        color: #eff6ff;
        background: rgba(255,255,255,0.07);
    }
    .nav-link-item.active {
        color: #e0f2fe;
        background: rgba(34, 211, 238, 0.12);
    }
    .nav-link-item.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 50%;
        transform: translateX(-50%);
        width: 18px;
        height: 2px;
        background: #22d3ee;
        border-radius: 2px;
    }
    .nav-divider {
        width: 1px;
        height: 20px;
        background: rgba(255,255,255,0.12);
    }
    .user-btn {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 500;
        color: #bfdbfe;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 8px;
        padding: 0.45rem 0.95rem;
        transition: all 0.18s;
        min-height: 36px;
        display: inline-flex;
        align-items: center;
    }
    .user-btn:hover {
        background: rgba(255,255,255,0.1);
        color: #eff6ff;
    }
    .theme-btn {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 8px;
        padding: 0.4rem;
        transition: all 0.18s;
        cursor: pointer;
    }
    .theme-btn:hover {
        background: rgba(255,255,255,0.11);
    }
    .resp-nav-link {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.95rem;
        font-weight: 500;
        color: #93c5fd;
        display: block;
        padding: 0.7rem 1.25rem;
        transition: all 0.15s;
        letter-spacing: 0.02em;
        text-decoration: none;
        min-height: 44px;
        display: flex;
        align-items: center;
    }
    .resp-nav-link:hover, .resp-nav-link.active {
        background: rgba(34, 211, 238, 0.1);
        color: #e0f2fe;
    }
</style>
@endonce

<nav x-data="{ open: false }" class="bg-blue-950 text-amber-50 border-b border-blue-900/40 shadow-sm">
    <div class="max-w-7xl mx-auto px-3 md:px-6 lg:px-8">
        <div class="flex justify-between h-16 md:h-16 items-center">

            {{-- ── LOGO ── --}}
            <div class="flex items-center gap-5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <x-application-logo class="w-[38px] h-[42px]" />

                    {{-- School name --}}
                    <div class="flex flex-col leading-none">
                        <span style="font-family:'Cormorant Garamond',serif; font-size:1.3rem; font-weight:700; letter-spacing:0.02em; color:#e0f2fe; line-height:1.1;" class="group-hover:text-white transition-colors">
                            {{ config('app.name', 'Jardim do Saber') }}
                        </span>
                    </div>
                </a>

                {{-- Divider --}}
                <div class="nav-divider hidden sm:block"></div>

                {{-- ── NAV LINKS (desktop) ── --}}
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>

                    @if (Auth::user()?->isAdmin() || Auth::user()?->isProfessor())
                        <a href="{{ route('turmas.index') }}"
                           class="nav-link-item {{ request()->routeIs('turmas.*') ? 'active' : '' }}">
                            Turmas
                        </a>
                        <a href="{{ route('alunos.index') }}"
                           class="nav-link-item {{ request()->routeIs('alunos.*') ? 'active' : '' }}">
                            Alunos
                        </a>
                        <a href="{{ route('presenca.index') }}"
                           class="nav-link-item {{ request()->routeIs('presenca.*') ? 'active' : '' }}">
                            Presença
                        </a>
                    @endif

                     @if (Auth::user()?->isAdmin())
                         <a href="{{ route('financeiro.index') }}"
                            class="nav-link-item {{ request()->routeIs('financeiro.*') ? 'active' : '' }}">
                             Financeiro
                         </a>
                        <a href="{{ route('admin.notices.index') }}"
                           class="nav-link-item {{ request()->routeIs('admin.notices.*') ? 'active' : '' }}">
                            Recados
                        </a>
                         <a href="{{ route('admin.users.index') }}"
                            class="nav-link-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                             Usuários
                         </a>
                     @endif
                </div>
            </div>

            {{-- ── RIGHT SIDE (desktop) ── --}}
            <div class="hidden sm:flex items-center gap-3">

                {{-- Dark mode toggle --}}
                <button id="theme-toggle" type="button" class="theme-btn" title="Alternar tema">
                    <svg id="sun-icon" class="w-4 h-4 text-amber-300 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.536l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.828-2.828a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414zm.464-4.536l.707-.707a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414zm-2.828 2.828a1 1 0 01-1.414 0l-.707-.707a1 1 0 011.414-1.414l.707.707a1 1 0 010 1.414zM4.929 4.929a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    <svg id="moon-icon" class="w-4 h-4 text-blue-300/70" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>

                {{-- User dropdown --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="user-btn inline-flex items-center gap-2">
                            <span style="width:22px;height:22px;border-radius:5px;background:rgba(34,211,238,0.15);border:1px solid rgba(34,211,238,0.35);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#22d3ee;font-family:'DM Sans',sans-serif;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-cyan-500/60" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- ── HAMBURGER (mobile) ── --}}
            <div class="-me-2 flex items-center sm:hidden gap-2">
                <button id="theme-toggle-mobile" type="button" class="theme-btn">
                    <svg id="sun-icon-mobile" class="w-5 h-5 text-amber-300 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.536l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.828-2.828a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414zm.464-4.536l.707-.707a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414zm-2.828 2.828a1 1 0 01-1.414 0l-.707-.707a1 1 0 011.414-1.414l.707.707a1 1 0 010 1.414zM4.929 4.929a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    <svg id="moon-icon-mobile" class="w-4 h-4 text-blue-300/70" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>

                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-blue-300 hover:text-white hover:bg-blue-900/40 transition duration-150">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ── RESPONSIVE MENU ── --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-blue-900/40">
        <div class="py-3">
            <a href="{{ route('dashboard') }}" class="resp-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>

            @if (Auth::user()?->isAdmin() || Auth::user()?->isProfessor())
                <a href="{{ route('turmas.index') }}" class="resp-nav-link {{ request()->routeIs('turmas.*') ? 'active' : '' }}">Turmas</a>
                <a href="{{ route('alunos.index') }}" class="resp-nav-link {{ request()->routeIs('alunos.*') ? 'active' : '' }}">Alunos</a>
                <a href="{{ route('presenca.index') }}" class="resp-nav-link {{ request()->routeIs('presenca.*') ? 'active' : '' }}">Presença</a>
            @endif

             @if (Auth::user()?->isAdmin())
                 <a href="{{ route('financeiro.index') }}" class="resp-nav-link {{ request()->routeIs('financeiro.*') ? 'active' : '' }}">Financeiro</a>
                 <a href="{{ route('admin.notices.index') }}" class="resp-nav-link {{ request()->routeIs('admin.notices.*') ? 'active' : '' }}">Recados</a>
                 <a href="{{ route('admin.users.index') }}" class="resp-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Usuários</a>
             @endif
        </div>

        <div class="pt-4 pb-3 border-t border-blue-900/40">
            <div class="px-5 mb-3">
                <div style="font-family:'Cormorant Garamond',serif;" class="text-base font-semibold text-blue-100">{{ Auth::user()->name }}</div>
                <div style="font-family:'DM Sans',sans-serif;" class="text-xs text-cyan-500/70">{{ Auth::user()->email }}</div>
            </div>
            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}" class="resp-nav-link">Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="resp-nav-link"
                       onclick="event.preventDefault(); this.closest('form').submit();">Sair</a>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    function setupThemeToggle() {
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleBtnMobile = document.getElementById('theme-toggle-mobile');
        const sunIcon = document.getElementById('sun-icon');
        const moonIcon = document.getElementById('moon-icon');
        const sunIconMobile = document.getElementById('sun-icon-mobile');
        const moonIconMobile = document.getElementById('moon-icon-mobile');
        const htmlElement = document.documentElement;

        function updateIcons() {
            const isDark = htmlElement.classList.contains('dark');
            sunIcon?.classList.toggle('hidden', !isDark);
            moonIcon?.classList.toggle('hidden', isDark);
            sunIconMobile?.classList.toggle('hidden', !isDark);
            moonIconMobile?.classList.toggle('hidden', isDark);
        }

        function toggleTheme() {
            const isDark = htmlElement.classList.contains('dark');
            htmlElement.classList.toggle('dark', !isDark);
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
            updateIcons();
        }

        themeToggleBtn?.addEventListener('click', toggleTheme);
        themeToggleBtnMobile?.addEventListener('click', toggleTheme);
        updateIcons();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupThemeToggle);
    } else {
        setupThemeToggle();
    }
</script>
