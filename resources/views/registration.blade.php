<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Smart Blog</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .gradient-text {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
  </style>
</head>
<body class="bg-gray-950 text-gray-100 flex items-center justify-center min-h-screen p-4 py-12">
  <div class="w-full max-w-3xl">
    <!-- Logo/Brand -->
    <div class="text-center mb-8">
      <a href="{{ route('welcome') }}" class="inline-flex items-center space-x-2 mb-2">
        <svg class="w-10 h-10 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
          <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
          <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
        </svg>
        <span class="text-3xl font-extrabold gradient-text">Smart Blog</span>
      </a>
      <p class="text-gray-400 text-sm">Create your account and start sharing</p>
    </div>

    <div class="bg-gray-900 p-8 rounded-2xl shadow-2xl border border-gray-800">
      <h2 class="text-2xl font-bold mb-8 text-center text-white">Create Your Account</h2>
    
    @if ($errors->any())
        <div class="bg-red-900/20 border border-red-700 text-red-300 p-4 rounded-xl mb-6">
            <p class="font-bold mb-2">Whoops! Please correct the following errors:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('register.post') }}" method="POST" class="space-y-6">
      @csrf 
      <div>
        <h3 class="text-xl font-semibold text-purple-400 mb-4">Personal Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="name" class="block text-gray-400 font-medium mb-1">Full Name</label>
            <input type="text" id="name" name="name" required 
                   value="{{ old('name') }}"
                   class="w-full bg-gray-700 text-gray-200 px-4 py-3 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 placeholder-gray-500" placeholder="John Doe">
            @error('name')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
          </div>
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
            <label for="dob" class="block text-gray-400 font-medium mb-1">Date of Birth</label>
            <input type="date" id="dob" name="dob" required 
                   value="{{ old('dob') }}"
                   class="w-full bg-gray-700 text-gray-200 px-4 py-3 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
            @error('dob')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="phone" class="block text-gray-400 font-medium mb-1">Phone Number</label>
            <input type="tel" id="phone" name="phone" required 
                   value="{{ old('phone') }}"
                   class="w-full bg-gray-700 text-gray-200 px-4 py-3 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 placeholder-gray-500" placeholder="+1 (555) 123-4567">
            @error('phone')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="password" class="block text-gray-400 font-medium mb-1">Password</label>
            <input type="password" id="password" name="password" required class="w-full bg-gray-700 text-gray-200 px-4 py-3 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
            @error('password')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="confirm_password" class="block text-gray-400 font-medium mb-1">Confirm Password</label>
            <input type="password" id="confirm_password" name="password_confirmation" required class="w-full bg-gray-700 text-gray-200 px-4 py-3 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
          </div>
        </div>
      </div>

      <div class="text-center pt-4">
        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-3 rounded-xl hover:shadow-lg hover:shadow-purple-500/50 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all duration-300">
          Create Account
        </button>
      </div>
    </form>

    <div class="mt-6 text-center">
      <p class="text-gray-400 text-sm">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 font-semibold">Sign in instead</a>
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