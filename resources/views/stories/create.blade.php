@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('stories.index') }}" class="text-purple-400 hover:text-purple-300 flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Stories
            </a>
            <h1 class="text-4xl font-bold text-white mb-2">Create Story</h1>
            <p class="text-gray-400">Share a moment (disappears in 24 hours)</p>
        </div>

        <!-- Create Story Form -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-8">
            <form method="POST" action="{{ route('stories.store') }}" enctype="multipart/form-data" id="story-form">
                @csrf

                <!-- Media Upload -->
                <div class="mb-6">
                    <label class="block text-white font-medium mb-3">Upload Media</label>
                    
                    <!-- Upload Area -->
                    <div class="relative">
                        <input type="file" name="media" id="media-input" accept="image/*,video/*" required class="hidden">
                        <label for="media-input" class="block cursor-pointer">
                            <div id="upload-area" class="border-2 border-dashed border-gray-600 rounded-xl p-12 text-center hover:border-purple-500 transition-all">
                                <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-gray-400 mb-2">Click to upload image or video</p>
                                <p class="text-gray-500 text-sm">PNG, JPG, GIF, MP4 up to 10MB</p>
                            </div>
                        </label>
                    </div>

                    <!-- Preview Area -->
                    <div id="preview-area" class="mt-4 hidden">
                        <div class="relative aspect-[9/16] max-w-sm mx-auto rounded-xl overflow-hidden bg-black">
                            <img id="image-preview" class="hidden w-full h-full object-cover">
                            <video id="video-preview" class="hidden w-full h-full object-cover" controls></video>
                            
                            <!-- Remove Button -->
                            <button type="button" id="remove-media" class="absolute top-4 right-4 bg-red-600 hover:bg-red-700 text-white p-2 rounded-full transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @error('media')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Caption -->
                <div class="mb-6">
                    <label for="caption" class="block text-white font-medium mb-3">Caption (Optional)</label>
                    <textarea name="caption" id="caption" rows="3" class="w-full bg-gray-900 text-white rounded-lg px-4 py-3 border border-gray-700 focus:border-purple-500 focus:outline-none" placeholder="Add a caption to your story...">{{ old('caption') }}</textarea>
                    @error('caption')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-900 bg-opacity-30 border border-blue-700 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-blue-300 text-sm font-medium mb-1">About Stories</p>
                            <p class="text-blue-200 text-sm">Your story will be visible to your followers for 24 hours, then automatically deleted.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-lg font-semibold transition-all flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Share Story
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const mediaInput = document.getElementById('media-input');
const uploadArea = document.getElementById('upload-area');
const previewArea = document.getElementById('preview-area');
const imagePreview = document.getElementById('image-preview');
const videoPreview = document.getElementById('video-preview');
const removeBtn = document.getElementById('remove-media');

mediaInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (event) => {
        uploadArea.classList.add('hidden');
        previewArea.classList.remove('hidden');

        if (file.type.startsWith('image/')) {
            imagePreview.src = event.target.result;
            imagePreview.classList.remove('hidden');
            videoPreview.classList.add('hidden');
        } else if (file.type.startsWith('video/')) {
            videoPreview.src = event.target.result;
            videoPreview.classList.remove('hidden');
            imagePreview.classList.add('hidden');
        }
    };
    reader.readAsDataURL(file);
});

removeBtn.addEventListener('click', () => {
    mediaInput.value = '';
    uploadArea.classList.remove('hidden');
    previewArea.classList.add('hidden');
    imagePreview.src = '';
    videoPreview.src = '';
});
</script>
@endsection
