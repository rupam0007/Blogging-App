<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <a href="{{ route('welcome') }}" class="inline-flex items-center space-x-2 mb-2">
                <svg class="w-10 h-10 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                </svg>
                <span class="text-3xl font-extrabold gradient-text">Smart Blog</span>
            </a>
            <p class="text-gray-400 text-sm">Welcome back! Log in to continue</p>
        </div>

        <div class="bg-gray-900 p-8 rounded-2xl shadow-2xl border border-gray-800">
            <h2 class="text-2xl font-bold mb-6 text-center text-white">Sign In</h2>

        @if ($errors->any())
            <div class="bg-red-900/20 border border-red-700 text-red-300 p-4 rounded-xl mb-6">
                <p class="font-bold mb-2">Login Failed:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-gray-400 font-medium mb-1">Email</label>
                <input type="email" id="email" name="email" required 
                       value="{{ old('email') }}"
                       class="w-full bg-gray-700 text-gray-200 px-4 py-3 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 placeholder-gray-500" placeholder="you@example.com">
                @error('email')
                    <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-gray-400 font-medium mb-1">Password</label>
                <input type="password" id="password" name="password" required 
                       class="w-full bg-gray-700 text-gray-200 px-4 py-3 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                @error('password')
                    <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                    <label for="remember" class="ml-2 block text-sm text-gray-400">Remember me</label>
                </div>
                <a href="#" class="text-sm text-blue-400 hover:text-blue-300">Forgot Password?</a>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-3 rounded-xl hover:shadow-lg hover:shadow-purple-500/50 transition-all duration-300">
                    Sign In
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-400 text-sm">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300 font-semibold">Create one now</a>
            </p>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-800 text-center">
            <a href="{{ route('welcome') }}" class="text-gray-500 hover:text-gray-400 text-sm transition">
                ← Back to Home
            </a>
        </div>
    </div>
    </div>
</body>
</html>