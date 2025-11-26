<nav class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50" x-data="searchComponent()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex items-center">
                <a href="{{ route('welcome') }}" class="flex-shrink-0 flex items-center space-x-2">
                    <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                    </svg>
                    <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-blue-500">
                        Smart Blog
                    </span>
                </a>
            </div>

            <div class="flex-1 flex items-center justify-center px-2 lg:ml-6 lg:justify-end">
                <div class="max-w-lg w-full lg:max-w-xs relative">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        
                        <input id="search" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-xl leading-5 bg-gray-800 text-gray-300 placeholder-gray-400 focus:outline-none focus:bg-gray-700 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 sm:text-sm transition duration-150 ease-in-out" 
                               placeholder="Search posts or people..." 
                               type="search"
                               x-model="query"
                               @input.debounce.300ms="performSearch()"
                               @click.away="isOpen = false"
                               @focus="isOpen = true"
                               autocomplete="off">
                               
                        <div x-show="isLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="animate-spin h-4 w-4 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    <div x-show="isOpen && (results.posts?.length > 0 || results.users?.length > 0)" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="origin-top-right absolute right-0 mt-2 w-full rounded-xl shadow-lg bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-700 focus:outline-none"
                         style="display: none;">
                        
                        <template x-if="results.posts?.length > 0">
                            <div class="py-1">
                                <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Posts
                                </div>
                                <template x-for="post in results.posts" :key="post.id">
                                    <a :href="'/posts/' + post.id" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white group">
                                        <div class="flex items-center">
                                            <svg class="mr-3 h-4 w-4 text-gray-500 group-hover:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                            </svg>
                                            <span x-text="post.title"></span>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </template>

                        <template x-if="results.users?.length > 0">
                            <div class="py-1">
                                <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    People
                                </div>
                                <template x-for="user in results.users" :key="user.id">
                                    <a :href="'/users/' + user.id" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white">
                                        <div class="flex items-center">
                                            <img :src="user.avatar || 'https://ui-avatars.com/api/?name=' + user.name" class="h-6 w-6 rounded-full mr-3">
                                            <div>
                                                <p x-text="user.name" class="font-medium"></p>
                                                <p x-text="'@' + user.username" class="text-xs text-gray-500"></p>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </template>
                        
                        <div class="py-2 bg-gray-700/50 rounded-b-xl">
                            <a :href="'/search?q=' + query" class="block text-center text-xs text-purple-400 hover:text-purple-300 font-medium">
                                View all results for "<span x-text="query"></span>"
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-8 ml-6">
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-300 hover:text-white transition">Feed</a>
                <a href="{{ route('explore') }}" class="text-sm font-medium text-gray-300 hover:text-white transition">Explore</a>
                
                @auth
                    <button class="text-gray-400 hover:text-white relative">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-gray-900"></span>
                    </button>

                    <div class="relative ml-3" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 focus:ring-offset-gray-900">
                            <img class="h-8 w-8 rounded-full bg-gray-700" src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" alt="">
                        </button>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="origin-top-right absolute right-0 mt-2 w-48 rounded-xl shadow-lg py-1 bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none"
                             style="display: none;">
                            <a href="{{ route('profile.show', Auth::id()) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Your Profile</a>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Sign out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white">Log in</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 transition shadow-lg shadow-purple-500/30">Get Started</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    function searchComponent() {
        return {
            query: '',
            results: { posts: [], users: [] },
            isOpen: false,
            isLoading: false,

            performSearch() {
                if (this.query.length < 2) {
                    this.results = { posts: [], users: [] };
                    this.isOpen = false;
                    return;
                }

                this.isLoading = true;
                this.isOpen = true;

                fetch(`/search/autocomplete?q=${this.query}`)
                    .then(response => response.json())
                    .then(data => {
                        this.results = data;
                        this.isLoading = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                    });
            }
        }
    }
</script>