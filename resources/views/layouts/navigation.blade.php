<nav x-data="{ open: false }" class="bg-gradient-to-r from-blue-950 via-blue-950 to-blue-900 text-amber-50 border-b border-blue-900/40 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-amber-50" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if (Auth::user()?->isAdmin() || Auth::user()?->isProfessor())
                        <x-nav-link :href="route('turmas.index')" :active="request()->routeIs('turmas.*')">
                            Turmas
                        </x-nav-link>

                        <x-nav-link :href="route('alunos.index')" :active="request()->routeIs('alunos.*')">
                            Alunos
                        </x-nav-link>

                        <x-nav-link :href="route('presenca.index')" :active="request()->routeIs('presenca.*')">
                            Presença
                        </x-nav-link>
                    @endif

                    @if (Auth::user()?->isAdmin())
                        <x-nav-link :href="route('financeiro.index')" :active="request()->routeIs('financeiro.*')">
                            Financeiro
                        </x-nav-link>

                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            Usuários
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <!-- Dark Mode Toggle -->
                <button id="theme-toggle" type="button" class="p-2 rounded-lg bg-blue-900/40 hover:bg-blue-900/60 transition-colors" title="Toggle dark mode">
                    <svg id="sun-icon" class="w-5 h-5 text-amber-300 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.536l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.828-2.828a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414zm.464-4.536l.707-.707a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414zm-2.828 2.828a1 1 0 01-1.414 0l-.707-.707a1 1 0 011.414-1.414l.707.707a1 1 0 010 1.414zM4.929 4.929a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    <svg id="moon-icon" class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-blue-900/40 text-sm leading-4 font-medium rounded-md text-amber-100 bg-blue-950 hover:bg-blue-900/40 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden gap-2">
                <button id="theme-toggle-mobile" type="button" class="p-2 rounded-lg bg-blue-900/40 hover:bg-blue-900/60 transition-colors">
                    <svg id="sun-icon-mobile" class="w-5 h-5 text-amber-300 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.536l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.828-2.828a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414zm.464-4.536l.707-.707a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414zm-2.828 2.828a1 1 0 01-1.414 0l-.707-.707a1 1 0 011.414-1.414l.707.707a1 1 0 010 1.414zM4.929 4.929a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    <svg id="moon-icon-mobile" class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
                </button>
            
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-amber-100 hover:text-white hover:bg-blue-900/40 focus:outline-none focus:bg-blue-900/40 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (Auth::user()?->isAdmin() || Auth::user()?->isProfessor())
                <x-responsive-nav-link :href="route('turmas.index')" :active="request()->routeIs('turmas.*')">
                    Turmas
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('alunos.index')" :active="request()->routeIs('alunos.*')">
                    Alunos
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('presenca.index')" :active="request()->routeIs('presenca.*')">
                    Presença
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()?->isAdmin())
                <x-responsive-nav-link :href="route('financeiro.index')" :active="request()->routeIs('financeiro.*')">
                    Financeiro
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    Usuários
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-blue-900/40">
            <div class="px-4">
                <div class="font-medium text-base text-amber-50">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-amber-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
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
            if (isDark) {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
                sunIconMobile.classList.remove('hidden');
                moonIconMobile.classList.add('hidden');
            } else {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
                sunIconMobile.classList.add('hidden');
                moonIconMobile.classList.remove('hidden');
            }
        }

        function toggleTheme() {
            const isDark = htmlElement.classList.contains('dark');
            if (isDark) {
                htmlElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                htmlElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateIcons();
        }

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', toggleTheme);
        }
        if (themeToggleBtnMobile) {
            themeToggleBtnMobile.addEventListener('click', toggleTheme);
        }

        updateIcons();
    }

    document.addEventListener('DOMContentLoaded', setupThemeToggle);
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupThemeToggle);
    } else {
        setupThemeToggle();
    }
</script>
