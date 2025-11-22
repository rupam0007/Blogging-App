

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Blog - Where Stories Come Alive</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .fade-in-up {
      animation: fadeInUp 0.8s ease-out;
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .scale-up {
      transition: transform 0.3s ease;
    }
    .scale-up:hover {
      transform: scale(1.05);
    }
    .gradient-text {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .hero-gradient {
      background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e1b4b 100%);
    }
  </style>
</head>
<body class="bg-gray-950 text-gray-100">

  <!-- Navigation -->
  <header class="bg-gray-900/95 backdrop-blur-sm shadow-lg fixed w-full z-50 border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
      <div class="flex items-center space-x-2">
        <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
          <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
          <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
        </svg>
        <h1 class="text-2xl sm:text-3xl font-extrabold gradient-text">Smart Blog</h1>
      </div>
      <nav class="hidden md:flex space-x-6 items-center text-sm">
        <a href="#features" class="text-gray-300 hover:text-purple-400 transition font-medium">Features</a>
        <a href="#recent-posts" class="text-gray-300 hover:text-purple-400 transition font-medium">Recent Posts</a>
        <a href="{{ route('blogs.index') }}" class="text-gray-300 hover:text-purple-400 transition font-medium">All Blogs</a>
        
        @auth
          <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all">
            Dashboard
          </a>
          <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-sm text-red-400 hover:text-red-300 transition font-medium">Logout</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="px-4 py-2 bg-gray-800 text-white font-semibold rounded-lg shadow hover:bg-gray-700 transition">
            Login
          </a>
          <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
            Get Started
          </a>
        @endauth
      </nav>
      
      <!-- Mobile Menu Button -->
      <button class="md:hidden text-gray-300">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="pt-32 pb-20 text-center hero-gradient overflow-hidden relative">
    <div class="absolute inset-0 opacity-10">
      <div class="absolute top-20 left-10 w-72 h-72 bg-purple-500 rounded-full blur-3xl"></div>
      <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
      <div class="inline-block px-4 py-2 bg-purple-500/10 border border-purple-500/30 rounded-full mb-6 fade-in-up">
        <span class="text-purple-400 font-semibold text-sm">✨ Where Stories Come Alive</span>
      </div>
      
      <h2 class="text-4xl sm:text-5xl md:text-7xl font-extrabold mb-6 gradient-text fade-in-up leading-tight">
        Blog, Share & Connect<br/>Like Never Before
      </h2>
      
      <p class="text-lg sm:text-xl md:text-2xl mb-10 text-gray-300 max-w-3xl mx-auto fade-in-up leading-relaxed" style="animation-delay: 0.2s;">
        The ultimate blogging platform that combines powerful writing tools with social media features. 
        Create stunning blogs, share 24-hour stories, and build your community.
      </p>
      
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12 fade-in-up" style="animation-delay: 0.4s;">
        @auth
          <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold text-lg rounded-xl shadow-2xl hover:shadow-green-500/50 transition-all scale-up w-full sm:w-auto">
            🚀 Go to Dashboard
          </a>
        @else
          <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold text-lg rounded-xl shadow-2xl hover:shadow-purple-500/50 transition-all scale-up w-full sm:w-auto">
            🚀 Start Writing for Free
          </a>
          <a href="{{ route('login') }}" class="px-8 py-4 bg-gray-800/80 backdrop-blur-sm text-white font-semibold text-lg rounded-xl border-2 border-gray-700 hover:border-purple-500 transition-all w-full sm:w-auto">
            Sign In
          </a>
        @endauth
      </div>

      <!-- Feature Badges -->
      <div class="flex flex-wrap justify-center gap-4 text-sm fade-in-up" style="animation-delay: 0.6s;">
        <div class="flex items-center space-x-2 bg-gray-800/50 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-700">
          <span class="text-2xl">📝</span>
          <span class="text-gray-300">Rich Text Blogs</span>
        </div>
        <div class="flex items-center space-x-2 bg-gray-800/50 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-700">
          <span class="text-2xl">📸</span>
          <span class="text-gray-300">24h Stories</span>
        </div>
        <div class="flex items-center space-x-2 bg-gray-800/50 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-700">
          <span class="text-2xl">💬</span>
          <span class="text-gray-300">Comments & Reactions</span>
        </div>
        <div class="flex items-center space-x-2 bg-gray-800/50 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-700">
          <span class="text-2xl">👥</span>
          <span class="text-gray-300">Follow System</span>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Features Section -->
  <section id="features" class="py-20 bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="text-center mb-16">
        <h3 class="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-4 gradient-text">Why Choose Smart Blog?</h3>
        <p class="text-gray-400 text-lg max-w-2xl mx-auto">Experience the perfect blend of blogging and social networking</p>
      </div>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="bg-gradient-to-br from-purple-900/20 to-gray-900/20 p-8 rounded-2xl border border-purple-500/20 hover:border-purple-500/40 transition-all scale-up">
          <div class="text-4xl mb-4">✍️</div>
          <h4 class="text-2xl font-bold mb-3 text-white">Rich Text Blogging</h4>
          <p class="text-gray-400">Create beautiful blogs with images, videos, and rich text formatting. SEO-friendly URLs and draft options included.</p>
        </div>

        <!-- Feature 2 -->
        <div class="bg-gradient-to-br from-blue-900/20 to-gray-900/20 p-8 rounded-2xl border border-blue-500/20 hover:border-blue-500/40 transition-all scale-up">
          <div class="text-4xl mb-4">📸</div>
          <h4 class="text-2xl font-bold mb-3 text-white">Instagram-like Stories</h4>
          <p class="text-gray-400">Share quick updates that disappear after 24 hours. Perfect for behind-the-scenes content and daily updates.</p>
        </div>

        <!-- Feature 3 -->
        <div class="bg-gradient-to-br from-pink-900/20 to-gray-900/20 p-8 rounded-2xl border border-pink-500/20 hover:border-pink-500/40 transition-all scale-up">
          <div class="text-4xl mb-4">❤️</div>
          <h4 class="text-2xl font-bold mb-3 text-white">Reactions & Comments</h4>
          <p class="text-gray-400">Express yourself with multiple reaction types (like, love, laugh, wow, sad, angry) and nested comment threads.</p>
        </div>

        <!-- Feature 4 -->
        <div class="bg-gradient-to-br from-green-900/20 to-gray-900/20 p-8 rounded-2xl border border-green-500/20 hover:border-green-500/40 transition-all scale-up">
          <div class="text-4xl mb-4">👥</div>
          <h4 class="text-2xl font-bold mb-3 text-white">Follow System</h4>
          <p class="text-gray-400">Build your audience by following creators you love. Get personalized feeds from people you follow.</p>
        </div>

        <!-- Feature 5 -->
        <div class="bg-gradient-to-br from-yellow-900/20 to-gray-900/20 p-8 rounded-2xl border border-yellow-500/20 hover:border-yellow-500/40 transition-all scale-up">
          <div class="text-4xl mb-4">@</div>
          <h4 class="text-2xl font-bold mb-3 text-white">Mentions & Tags</h4>
          <p class="text-gray-400">Mention other users with @username and tag your posts to increase discoverability.</p>
        </div>

        <!-- Feature 6 -->
        <div class="bg-gradient-to-br from-red-900/20 to-gray-900/20 p-8 rounded-2xl border border-red-500/20 hover:border-red-500/40 transition-all scale-up">
          <div class="text-4xl mb-4">🔔</div>
          <h4 class="text-2xl font-bold mb-3 text-white">Real-time Notifications</h4>
          <p class="text-gray-400">Get instant alerts for mentions, likes, new followers, comments, and story views.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Recent Posts Section -->
  <section id="recent-posts" class="py-20 bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="text-center mb-12">
        <h3 class="text-3xl sm:text-4xl font-extrabold mb-4 text-white">Latest from the Community</h3>
        <p class="text-gray-400 text-lg">Discover amazing stories from our creators</p>
      </div>
      
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($posts as $post)
          <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 hover:border-purple-500/50 transition-all scale-up group">
            @if ($post->image_path)
              <div class="h-48 overflow-hidden">
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
              </div>
            @else
              <div class="h-48 bg-gradient-to-br from-purple-900/30 to-blue-900/30 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
            @endif
            
            <div class="p-6">
              <h4 class="text-xl font-bold mb-2 text-white group-hover:text-purple-400 transition">{{ $post->title }}</h4>
              <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ Str::limit($post->description, 100) }}</p>
              
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold text-sm">
                    {{ substr($post->user->name ?? 'G', 0, 1) }}
                  </div>
                  <span class="text-sm text-gray-400">{{ $post->user->name ?? 'Guest' }}</span>
                </div>
                <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-full text-center py-12">
            <svg class="w-20 h-20 mx-auto text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            <p class="text-gray-500 text-lg">No posts yet. Be the first to create something amazing!</p>
          </div>
        @endforelse
      </div>
      
      @if(count($posts) > 0)
        <div class="text-center mt-12">
          <a href="{{ route('blogs.index') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all scale-up">
            View All Posts →
          </a>
        </div>
      @endif
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-20 bg-gradient-to-r from-purple-900/30 to-blue-900/30 border-t border-gray-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
      <h3 class="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-6 text-white">Ready to Share Your Story?</h3>
      <p class="text-xl text-gray-300 mb-8">Join thousands of creators who are already using Smart Blog</p>
      
      @guest
        <a href="{{ route('register') }}" class="inline-block px-10 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold text-lg rounded-xl shadow-2xl hover:shadow-purple-500/50 transition-all scale-up">
          Create Your Free Account Now
        </a>
      @endguest
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-950 text-gray-500 py-12 border-t border-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="grid md:grid-cols-4 gap-8 mb-8">
        <div class="col-span-2">
          <div class="flex items-center space-x-2 mb-4">
            <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
              <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
            </svg>
            <h3 class="text-xl font-bold gradient-text">Smart Blog</h3>
          </div>
          <p class="text-gray-400 mb-4">Where stories come alive. Blog, share stories, and connect with creators worldwide.</p>
          <div class="flex space-x-4">
            <a href="#" class="text-gray-400 hover:text-purple-400 transition">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-purple-400 transition">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
            </a>
          </div>
        </div>
        
        <div>
          <h4 class="text-white font-bold mb-4">Platform</h4>
          <ul class="space-y-2">
            <li><a href="#features" class="hover:text-purple-400 transition">Features</a></li>
            <li><a href="{{ route('blogs.index') }}" class="hover:text-purple-400 transition">Explore Blogs</a></li>
            <li><a href="#" class="hover:text-purple-400 transition">How it Works</a></li>
          </ul>
        </div>
        
        <div>
          <h4 class="text-white font-bold mb-4">Support</h4>
          <ul class="space-y-2">
            <li><a href="#" class="hover:text-purple-400 transition">Help Center</a></li>
            <li><a href="#" class="hover:text-purple-400 transition">Privacy Policy</a></li>
            <li><a href="#" class="hover:text-purple-400 transition">Terms of Service</a></li>
          </ul>
        </div>
      </div>
      
      <div class="border-t border-gray-900 pt-8 text-center">
        <p class="text-gray-500">© 2025 Smart Blog. All rights reserved.</p>
        <p class="text-sm text-gray-600 mt-2">Built with ❤️ using Laravel & Tailwind CSS</p>
      </div>
    </div>
  </footer>

</body>
</html>