<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        .media-preview {
            display: none;
        }
        .media-preview.active {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-gray-400 hover:text-white mb-4 transition">
                <span class="material-symbols-outlined mr-2">arrow_back</span>
                Back to Dashboard
            </a>
            <h2 class="text-4xl font-bold bg-gradient-to-r from-purple-400 to-blue-500 bg-clip-text text-transparent">Create New Post</h2>
            <p class="text-gray-400 mt-2">Share your thoughts with the world</p>
        </div>

        @if (session('success'))
            <div class="bg-green-600/20 border border-green-500 text-green-300 p-4 rounded-xl mb-6 flex items-center">
                <span class="material-symbols-outlined mr-3">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Title -->
            <div class="bg-gray-900 p-6 rounded-2xl border border-gray-800">
                <label for="title" class="flex items-center text-lg font-semibold text-gray-200 mb-3">
                    <span class="material-symbols-outlined mr-2 text-purple-400">title</span>
                    Post Title
                </label>
                <input type="text" id="title" name="title" required value="{{ old('title') }}"
                       placeholder="Enter an engaging title..."
                       class="w-full bg-gray-800 text-gray-100 px-4 py-3 border border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition placeholder-gray-500">
                @error('title')<p class="text-sm text-red-400 mt-2 flex items-center"><span class="material-symbols-outlined text-sm mr-1">error</span>{{ $message }}</p>@enderror
            </div>

            <!-- Description -->
            <div class="bg-gray-900 p-6 rounded-2xl border border-gray-800">
                <label for="description" class="flex items-center text-lg font-semibold text-gray-200 mb-3">
                    <span class="material-symbols-outlined mr-2 text-blue-400">description</span>
                    Content
                </label>
                <textarea id="description" name="description" required rows="8"
                          placeholder="Write your post content here..."
                          class="w-full bg-gray-800 text-gray-100 px-4 py-3 border border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-500 resize-none">{{ old('description') }}</textarea>
                @error('description')<p class="text-sm text-red-400 mt-2 flex items-center"><span class="material-symbols-outlined text-sm mr-1">error</span>{{ $message }}</p>@enderror
            </div>
            
            <!-- Media Upload (Image or Video) -->
            <div class="bg-gray-900 p-6 rounded-2xl border border-gray-800">
                <label class="flex items-center text-lg font-semibold text-gray-200 mb-3">
                    <span class="material-symbols-outlined mr-2 text-pink-400">perm_media</span>
                    Media (Image or Video)
                </label>
                <div class="space-y-4">
                    <div class="relative">
                        <input type="file" id="blog_media" name="blog_media" 
                               accept="image/*,video/*"
                               onchange="previewMedia(this)"
                               class="hidden">
                        <label for="blog_media" class="flex flex-col items-center justify-center w-full h-40 bg-gray-800 border-2 border-dashed border-gray-700 rounded-xl cursor-pointer hover:bg-gray-750 hover:border-purple-500 transition group">
                            <span class="material-symbols-outlined text-5xl text-gray-600 group-hover:text-purple-400 transition">cloud_upload</span>
                            <p class="text-gray-400 group-hover:text-gray-300 mt-2">Click to upload image or video</p>
                            <p class="text-gray-600 text-sm mt-1">JPG, PNG, GIF, MP4, MOV (Max 100MB)</p>
                        </label>
                    </div>
                    
                    <!-- Media Preview -->
                    <div id="mediaPreview" class="media-preview rounded-xl overflow-hidden border border-gray-700">
                        <img id="imagePreview" class="w-full h-auto hidden">
                        <video id="videoPreview" class="w-full h-auto hidden" controls></video>
                        <button type="button" onclick="removeMedia()" class="mt-3 flex items-center text-red-400 hover:text-red-300 text-sm">
                            <span class="material-symbols-outlined text-sm mr-1">delete</span>
                            Remove media
                        </button>
                    </div>
                </div>
                @error('blog_media')<p class="text-sm text-red-400 mt-2 flex items-center"><span class="material-symbols-outlined text-sm mr-1">error</span>{{ $message }}</p>@enderror
            </div>

            <!-- Status Toggle -->
            <div class="bg-gray-900 p-6 rounded-2xl border border-gray-800">
                <label class="flex items-center text-lg font-semibold text-gray-200 mb-4">
                    <span class="material-symbols-outlined mr-2 text-green-400">visibility</span>
                    Post Visibility
                </label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="status" value="published" checked class="w-4 h-4 text-purple-600 focus:ring-purple-500">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-green-400 mr-2">public</span>
                            <div>
                                <p class="text-gray-200 font-medium">Publish Now</p>
                                <p class="text-gray-500 text-sm">Everyone can see this post</p>
                            </div>
                        </div>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="status" value="draft" class="w-4 h-4 text-purple-600 focus:ring-purple-500">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-gray-400 mr-2">draft</span>
                            <div>
                                <p class="text-gray-200 font-medium">Save as Draft</p>
                                <p class="text-gray-500 text-sm">Only you can see this</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex space-x-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-4 rounded-xl hover:from-purple-700 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-purple-500/50 flex items-center justify-center">
                    <span class="material-symbols-outlined mr-2">send</span>
                    Create Post
                </button>
                <a href="{{ route('dashboard') }}" class="px-6 py-4 bg-gray-800 text-gray-300 font-semibold rounded-xl hover:bg-gray-700 transition flex items-center">
                    <span class="material-symbols-outlined">close</span>
                </a>
            </div>
        </form>
    </div>

    <script>
        function previewMedia(input) {
            const preview = document.getElementById('mediaPreview');
            const imagePreview = document.getElementById('imagePreview');
            const videoPreview = document.getElementById('videoPreview');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        videoPreview.classList.add('hidden');
                    } else if (file.type.startsWith('video/')) {
                        videoPreview.src = e.target.result;
                        videoPreview.classList.remove('hidden');
                        imagePreview.classList.add('hidden');
                    }
                    preview.classList.add('active');
                }
                
                reader.readAsDataURL(file);
            }
        }
        
        function removeMedia() {
            const input = document.getElementById('blog_media');
            const preview = document.getElementById('mediaPreview');
            const imagePreview = document.getElementById('imagePreview');
            const videoPreview = document.getElementById('videoPreview');
            
            input.value = '';
            imagePreview.src = '';
            videoPreview.src = '';
            imagePreview.classList.add('hidden');
            videoPreview.classList.add('hidden');
            preview.classList.remove('active');
        }
    </script>
</body>
</html>