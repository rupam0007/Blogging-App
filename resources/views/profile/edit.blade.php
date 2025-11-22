@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Edit Profile</h1>
            <p class="text-gray-400">Update your profile information</p>
        </div>

        <!-- Profile Form -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-8 mb-6">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Avatar Upload -->
                <div class="mb-6">
                    <label class="block text-white font-medium mb-3">Profile Picture</label>
                    <div class="flex items-center space-x-6">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-full" id="avatar-preview">
                        @else
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-3xl font-bold" id="avatar-placeholder">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <img src="" alt="Avatar" class="w-24 h-24 rounded-full hidden" id="avatar-preview">
                        @endif
                        <div>
                            <input type="file" name="avatar" id="avatar-input" accept="image/*" class="hidden">
                            <label for="avatar-input" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg cursor-pointer inline-block transition-all">
                                Change Avatar
                            </label>
                            <p class="text-gray-400 text-sm mt-2">JPG, PNG or GIF (max 2MB)</p>
                        </div>
                    </div>
                    @error('avatar')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cover Photo Upload -->
                <div class="mb-6">
                    <label class="block text-white font-medium mb-3">Cover Photo</label>
                    <div class="relative h-48 rounded-xl overflow-hidden bg-gradient-to-r from-purple-600 to-pink-600">
                        @if(auth()->user()->cover_photo)
                            <img src="{{ Storage::url(auth()->user()->cover_photo) }}" alt="Cover" class="w-full h-full object-cover" id="cover-preview">
                        @else
                            <img src="" alt="Cover" class="w-full h-full object-cover hidden" id="cover-preview">
                        @endif
                        <input type="file" name="cover_photo" id="cover-input" accept="image/*" class="hidden">
                        <label for="cover-input" class="absolute inset-0 flex items-center justify-center cursor-pointer hover:bg-black hover:bg-opacity-30 transition-all">
                            <div class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg">
                                Change Cover Photo
                            </div>
                        </label>
                    </div>
                    @error('cover_photo')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-white font-medium mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full bg-gray-900 text-white rounded-lg px-4 py-3 border border-gray-700 focus:border-purple-500 focus:outline-none">
                    @error('name')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div class="mb-6">
                    <label for="username" class="block text-white font-medium mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-400">@</span>
                        <input type="text" name="username" id="username" value="{{ old('username', auth()->user()->username) }}" class="w-full bg-gray-900 text-white rounded-lg pl-9 pr-4 py-3 border border-gray-700 focus:border-purple-500 focus:outline-none">
                    </div>
                    @error('username')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div class="mb-6">
                    <label for="bio" class="block text-white font-medium mb-2">Bio</label>
                    <textarea name="bio" id="bio" rows="4" class="w-full bg-gray-900 text-white rounded-lg px-4 py-3 border border-gray-700 focus:border-purple-500 focus:outline-none" placeholder="Tell us about yourself...">{{ old('bio', auth()->user()->bio) }}</textarea>
                    @error('bio')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-white font-medium mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full bg-gray-900 text-white rounded-lg px-4 py-3 border border-gray-700 focus:border-purple-500 focus:outline-none">
                    @error('email')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-all">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-8">
            <h2 class="text-2xl font-bold text-white mb-6">Change Password</h2>
            <form method="POST" action="{{ route('profile.change-password') }}">
                @csrf

                <!-- Current Password -->
                <div class="mb-6">
                    <label for="current_password" class="block text-white font-medium mb-2">Current Password</label>
                    <input type="password" name="current_password" id="current_password" required class="w-full bg-gray-900 text-white rounded-lg px-4 py-3 border border-gray-700 focus:border-purple-500 focus:outline-none">
                    @error('current_password')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-6">
                    <label for="password" class="block text-white font-medium mb-2">New Password</label>
                    <input type="password" name="password" id="password" required class="w-full bg-gray-900 text-white rounded-lg px-4 py-3 border border-gray-700 focus:border-purple-500 focus:outline-none">
                    @error('password')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-white font-medium mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full bg-gray-900 text-white rounded-lg px-4 py-3 border border-gray-700 focus:border-purple-500 focus:outline-none">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-all">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Avatar preview
document.getElementById('avatar-input').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            preview.src = event.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Cover photo preview
document.getElementById('cover-input').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            const preview = document.getElementById('cover-preview');
            preview.src = event.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
