<nav x-data="{ open: false, activeModule: '' }" class="bg-gradient-to-r from-yellow-400 to-yellow-500 shadow-lg border-b border-yellow-600">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <img src="{{ asset('images/logo.png')}}" alt="Logo JAKE" class="h-12 w-12 rounded-lg" href="{{ route('perfil.index') }}" class="ml-3 text-xl font-bold text-gray-800 hover:text-gray-900 transition-colors duration-200">
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex relative">
                    <!-- Indicador deslizante -->
                    <div x-ref="slider" class="absolute bottom-0 h-1 bg-white rounded-full transition-all duration-300 ease-in-out" 
                         :style="{
                             width: '80px',
                             transform: `translateX(${getSliderPosition()})`
                         }"
                         style="width: 80px; display: none;"></div>

                    
                    
                    <!-- Perfil -->
                    <x-nav-link href="{{ route('perfil.index') }}" :active="request()->routeIs('perfil.*')"
                               @click="setActiveModule('perfil')"
                               x-data="{ module: 'perfil' }"
                               :class="{ 'bg-yellow-300 shadow-lg': activeModule === 'perfil' }"
                               class="flex items-center px-4 py-2 rounded-lg transition-all duration-300 hover:bg-yellow-300 hover:shadow-md mx-1 relative group">
                        <i class="fas fa-user-circle mr-2 text-gray-700"></i>
                        <span class="text-gray-800 font-medium">{{ __('Perfil') }}</span>
                    </x-nav-link>
                    
                    <!-- Asistencia -->
                    <x-nav-link href="{{ route('asistencia.index') }}" :active="request()->routeIs('asistencia.*')"
                               @click="setActiveModule('asistencia')"
                               x-data="{ module: 'asistencia' }"
                               :class="{ 'bg-yellow-300 shadow-lg': activeModule === 'asistencia' }"
                               class="flex items-center px-4 py-2 rounded-lg transition-all duration-300 hover:bg-yellow-300 hover:shadow-md mx-1 relative group">
                        <i class="fa fa-user-check mr-2 text-gray-700"></i>
                        <span class="text-gray-800 font-medium">{{ __('Asistencia') }}</span>
                    </x-nav-link>

                        <!-- autorizaciones -->
                    <x-nav-link href="{{ route('autorizaciones.index') }}" :active="request()->routeIs('autorizaciones.*')"
                               @click="setActiveModule('autorizaciones')"
                               x-data="{ module: 'autorizaciones' }"
                               :class="{ 'bg-yellow-300 shadow-lg': activeModule === 'autorizaciones' }"
                               class="flex items-center px-4 py-2 rounded-lg transition-all duration-300 hover:bg-yellow-300 hover:shadow-md mx-1 relative group">
                        <i class="fas fa-clipboard-check mr-2 text-gray-700"></i>
                        <span class="text-gray-800 font-medium">{{ __('Autorizaciones') }}</span>
                    </x-nav-link>
                   
                   <!-- Programación -->
                    <x-nav-link href="{{ route('programacion.index') }}" :active="request()->routeIs('programación.*')"
                               @click="setActiveModule('programacion')"
                               x-data="{ module: 'programacion' }"
                               :class="{ 'bg-yellow-300 shadow-lg': activeModule === 'programacion' }"
                               class="flex items-center px-4 py-2 rounded-lg transition-all duration-300 hover:bg-yellow-300 hover:shadow-md mx-1 relative group">
                        <i class="fas fa-calendar-alt mr-2 text-gray-700"></i>
                        <span class="text-gray-800 font-medium">{{ __('Programación') }}</span>
                    </x-nav-link>
                    
                    <!-- Finanzas -->
                    <x-nav-link href="{{ route('finanzas.index') }}" :active="request()->routeIs('finanzas.*')"
                               @click="setActiveModule('finanzas')"
                               x-data="{ module: 'finanzas' }"
                               :class="{ 'bg-yellow-300 shadow-lg': activeModule === 'finanzas' }"
                               class="flex items-center px-4 py-2 rounded-lg transition-all duration-300 hover:bg-yellow-300 hover:shadow-md mx-1 relative group">
                        <i class="fas fa-chart-line mr-2 text-gray-700"></i>
                        <span class="text-gray-800 font-medium">{{ __('Finanzas') }}</span>
                    </x-nav-link>
                    
                    <!-- chat grupals -->
                    <x-nav-link href="{{ route('chatgrupal.index') }}" :active="request()->routeIs('chatgrupal.*')"
                               @click="setActiveModule('chatgrupal')"
                               x-data="{ module: 'chatgrupal' }"
                               :class="{ 'bg-yellow-300 shadow-lg': activeModule === 'chatgrupal' }"
                               class="flex items-center px-4 py-2 rounded-lg transition-all duration-300 hover:bg-yellow-300 hover:shadow-md mx-1 relative group">
                        <i class="fas fa-comments mr-2 text-gray-700"></i>
                        <span class="text-gray-800 font-medium">{{ __('Chat Grupal') }}</span>
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-gradient-to-r from-yellow-300 to-yellow-400 hover:from-yellow-400 hover:to-yellow-500 focus:outline-none focus:bg-yellow-400 active:bg-yellow-500 transition ease-in-out duration-150 shadow-md">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-yellow-300 transition shadow-md">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-gradient-to-r from-yellow-300 to-yellow-400 hover:from-yellow-400 hover:to-yellow-500 focus:outline-none focus:bg-yellow-400 active:bg-yellow-500 transition ease-in-out duration-150 shadow-md">
                                        Admin

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-yellow-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
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
            <x-responsive-nav-link href="{{ route('perfil.index') }}" :active="request()->routeIs('perfil/*')">
                {{ __('pER') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('navigation', () => ({
                activeModule: '',
                modules: {
                    'dashboard': 1,
                    'perfil': 0,
                    'asistencia': 2,
                    'programacion': 3,
                    'finanzas': 4,
                    'chatgrupal': 5
                },
                
                init() {
                    // Detectar módulo activo inicial basado en la URL
                    this.detectActiveModule();
                    
                    // Observar cambios en la URL
                    window.addEventListener('popstate', () => {
                        this.detectActiveModule();
                    });
                },
                
                setActiveModule(module) {
                    this.activeModule = module;
                    this.$nextTick(() => {
                        this.updateSliderPosition();
                    });
                },
                
                detectActiveModule() {
                    const path = window.location.pathname;
                    if (path.includes('perfil')) this.activeModule = 'perfil';
                    else if (path.includes('asistencia')) this.activeModule = 'asistencia';
                    else if (path.includes('programacion')) this.activeModule = 'programacion';
                    else if (path.includes('finanzas')) this.activeModule = 'finanzas';
                    else if (path.includes('chatgrupal')) this.activeModule = 'chatgrupal';
                    else this.activeModule = 'perfil';
                    
                    this.$nextTick(() => {
                        this.updateSliderPosition();
                    });
                },
                
                getSliderPosition() {
                    if (!this.activeModule || !this.modules[this.activeModule]) return '0px';
                    
                    const position = this.modules[this.activeModule] * 96; // 80px width + 16px margin
                    return `${position}px`;
                },
                
                updateSliderPosition() {
                    const slider = this.$refs.slider;
                    if (slider) {
                        slider.style.display = 'block';
                    }
                }
            }));
        });
    </script>

    <style>
        .router-link-active.router-link-exact-active {
            background-color: rgb(253 224 71);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Efecto de deslizamiento suave */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>
</nav>