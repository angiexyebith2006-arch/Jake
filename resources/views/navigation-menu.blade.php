<nav x-data="{ open: false, activeModule: '' }" class="bg-gradient-to-r from-yellow-400 to-yellow-500 shadow-lg border-b border-yellow-600">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('perfil.index') }}">
                        <img src="{{ asset('images/logo.png')}}" alt="Logo JAKE" class="h-12 w-12 rounded-lg">
                    </a>
                    <span class="ml-3 text-xl font-bold text-gray-800">
                        {{ config('app.name', 'JAKE') }}
                    </span>
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
                    <x-nav-link href="{{ route('programacion.index') }}" :active="request()->routeIs('programacion.*')"
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
                    
                    <!-- chat grupal -->
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
                <!-- Settings Dropdown - MODIFICADO para usar sesión personalizada -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-gradient-to-r from-yellow-300 to-yellow-400 hover:from-yellow-400 hover:to-yellow-500 focus:outline-none focus:bg-yellow-400 active:bg-yellow-500 transition ease-in-out duration-150 shadow-md">
                                    {{ session('usuario_api.nombre', 'Usuario') }}
                                    <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </span>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('perfil.index') }}">
                                {{ __('My Profile') }}
                            </x-dropdown-link>

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
            <x-responsive-nav-link href="{{ route('perfil.index') }}" :active="request()->routeIs('perfil.*')">
                <i class="fas fa-user-circle mr-2"></i>{{ __('Perfil') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('asistencia.index') }}" :active="request()->routeIs('asistencia.*')">
                <i class="fa fa-user-check mr-2"></i>{{ __('Asistencia') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('autorizaciones.index') }}" :active="request()->routeIs('autorizaciones.*')">
                <i class="fas fa-clipboard-check mr-2"></i>{{ __('Autorizaciones') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('programacion.index') }}" :active="request()->routeIs('programacion.*')">
                <i class="fas fa-calendar-alt mr-2"></i>{{ __('Programación') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('finanzas.index') }}" :active="request()->routeIs('finanzas.*')">
                <i class="fas fa-chart-line mr-2"></i>{{ __('Finanzas') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('chatgrupal.index') }}" :active="request()->routeIs('chatgrupal.*')">
                <i class="fas fa-comments mr-2"></i>{{ __('Chat Grupal') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div>
                    <div class="font-medium text-base text-gray-800">{{ session('usuario_api.nombre', 'Usuario') }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ session('usuario_api.correo', '') }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('navigation', () => ({
                activeModule: '',
                modules: {
                    'perfil': 0,
                    'asistencia': 1,
                    'autorizaciones': 2,
                    'programacion': 3,
                    'finanzas': 4,
                    'chatgrupal': 5
                },
                
                init() {
                    this.detectActiveModule();
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
                    else if (path.includes('autorizaciones')) this.activeModule = 'autorizaciones';
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
                    const position = this.modules[this.activeModule] * 96;
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
        
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>
</nav>