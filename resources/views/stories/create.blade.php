@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8 overflow-y-auto" style="min-height: 100vh; max-height: 100vh;">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('stories.index') }}" class="text-purple-400 hover:text-purple-300 flex items-center mb-4">
                <span class="material-symbols-outlined text-xl mr-2">arrow_back</span>
                Back to Stories
            </a>
            <h1 class="text-4xl font-bold text-white mb-2">Create Story</h1>
            <p class="text-gray-400">Share a moment (disappears in 24 hours)</p>
        </div>

        <!-- Create Story Form -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 sm:p-8">
            <form method="POST" action="{{ route('stories.store') }}" enctype="multipart/form-data" id="story-form" class="space-y-6">
                @csrf

                <!-- Media Upload -->
                <div>
                    <label class="block text-white font-medium mb-3">Upload Media</label>
                    
                    <!-- Upload Area -->
                    <div class="relative">
                        <input type="file" name="media" id="media-input" accept="image/*,video/*" required class="hidden">
                        <label for="media-input" class="block cursor-pointer">
                            <div id="upload-area" class="border-2 border-dashed border-gray-600 rounded-xl p-8 text-center hover:border-purple-500 transition-all">
                                <span class="material-symbols-outlined text-gray-500 mx-auto mb-3" style="font-size: 48px;">cloud_upload</span>
                                <p class="text-gray-400 mb-1 font-medium">Click to upload image or video</p>
                                <p class="text-gray-500 text-sm">PNG, JPG, GIF, MP4 up to 50MB</p>
                            </div>
                        </label>
                    </div>

                    <!-- Preview Area -->
                    <div id="preview-area" class="mt-4 hidden">
                        <div class="relative w-full max-w-xs mx-auto rounded-xl overflow-hidden bg-black" style="max-height: 300px;">
                            <img id="image-preview" class="hidden w-full h-auto max-h-[300px] object-contain">
                            <video id="video-preview" class="hidden w-full h-auto max-h-[300px] object-contain" controls></video>
                            
                            <!-- Remove Button -->
                            <button type="button" id="remove-media" class="absolute top-3 right-3 bg-red-600 hover:bg-red-700 text-white p-2 rounded-full transition-all z-10 shadow-lg">
                                <span class="material-symbols-outlined text-lg">close</span>
                            </button>
                        </div>
                    </div>

                    @error('media')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Caption -->
                <div>
                    <label for="caption" class="block text-white font-medium mb-3">Caption (Optional)</label>
                    <textarea name="caption" id="caption" rows="2" maxlength="500" class="w-full bg-gray-900 text-white rounded-lg px-4 py-3 border border-gray-700 focus:border-purple-500 focus:outline-none resize-none" placeholder="Add a caption to your story...">{{ old('caption') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Max 500 characters</p>
                    @error('caption')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-900 bg-opacity-30 border border-blue-700 rounded-lg p-3">
                    <div class="flex items-start">
                        <span class="material-symbols-outlined text-blue-400 mr-2 mt-0.5 flex-shrink-0 text-lg">info</span>
                        <div>
                            <p class="text-blue-300 text-sm font-medium mb-0.5">About Stories</p>
                            <p class="text-blue-200 text-xs">Your story will be visible to your followers for 24 hours, then automatically deleted.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-lg font-semibold transition-all flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined text-xl mr-2">send</span>
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
