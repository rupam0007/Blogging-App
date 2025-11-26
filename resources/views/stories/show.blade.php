@extends('layouts.app')

@section('content')
<div class="fixed inset-0 bg-black z-50 flex items-center justify-center">
    <!-- Story Viewer -->
    <div class="w-full max-w-md h-full relative">
        @php
            $userStories = $stories->values();
            $currentStory = $userStories->first();
        @endphp

        <!-- Progress Bars -->
        <div class="absolute top-0 left-0 right-0 flex space-x-1 p-4 z-10">
            @foreach($userStories as $index => $story)
                <div class="flex-1 h-0.5 bg-gray-600 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full story-progress" data-index="{{ $index }}" style="width: {{ $index === 0 ? '0%' : '0%' }}"></div>
                </div>
            @endforeach
        </div>

        <!-- User Info -->
        <div class="absolute top-6 left-4 right-4 flex items-center justify-between z-30">
            <div class="flex items-center space-x-3">
                @if($currentStory->user->avatar)
                    <img src="{{ Storage::url($currentStory->user->avatar) }}" alt="{{ $currentStory->user->name }}" class="w-10 h-10 rounded-full border-2 border-white">
                @else
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full border-2 border-white flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($currentStory->user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="text-white font-semibold text-sm">{{ $currentStory->user->name }}</p>
                    <p class="text-gray-300 text-xs">{{ $currentStory->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <!-- Close Button -->
            <a href="{{ route('stories.index') }}" class="text-white hover:text-gray-300 relative z-30">
                <span class="material-symbols-outlined text-2xl">close</span>
            </a>
        </div>

        <!-- Story Content -->
        <div id="story-container" class="h-full">
            @foreach($userStories as $index => $story)
                <div class="story-slide {{ $index === 0 ? '' : 'hidden' }}" data-story-id="{{ $story->id }}" data-index="{{ $index }}">
                    @if($story->media_type === 'image')
                        <img src="{{ Storage::url($story->media_path) }}" alt="Story" class="w-full h-full object-contain">
                    @else
                        <video class="w-full h-full object-contain" id="video-{{ $index }}">
                            <source src="{{ Storage::url($story->media_path) }}" type="video/mp4">
                        </video>
                    @endif

                    <!-- Caption -->
                    @if($story->caption)
                        <div class="absolute bottom-20 left-0 right-0 px-6">
                            <p class="text-white text-center text-sm bg-black bg-opacity-50 rounded-lg p-3">{{ $story->caption }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Navigation Areas (Tap Left/Right) -->
        <button id="prev-story" class="absolute left-0 top-0 bottom-0 w-1/3 cursor-pointer z-20 focus:outline-none"></button>
        <button id="next-story" class="absolute right-0 top-0 bottom-0 w-2/3 cursor-pointer z-20 focus:outline-none"></button>

        <!-- Reaction & Delete Buttons -->
        <div class="absolute bottom-6 left-0 right-0 px-6 flex items-center justify-between z-30">
            <!-- Reaction Button -->
            <div class="flex items-center space-x-2">
                <button id="reaction-btn" data-reacted="false" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-3 rounded-full transition-all relative z-30">
                    <span class="material-symbols-outlined text-2xl heart-icon">favorite</span>
                </button>
                <span id="reaction-count" class="text-white text-sm font-medium"></span>
            </div>

            <!-- Delete Button (if owner) -->
            @if(auth()->id() === $currentStory->user_id)
                <form method="POST" action="{{ route('stories.destroy', $currentStory) }}" id="delete-story-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" id="delete-btn" class="bg-red-600 hover:bg-red-700 text-white p-3 rounded-full transition-all relative z-30">
                        <span class="material-symbols-outlined text-2xl">delete</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
let currentIndex = 0;
const stories = @json($userStories->values());
const totalStories = stories.length;
let progressInterval;

function init() {
    updateStory(0);
    startProgress();
}

function updateStory(index) {
    document.querySelectorAll('.story-slide').forEach((slide, i) => {
        slide.classList.toggle('hidden', i !== index);
    });
    
    document.querySelectorAll('.story-progress').forEach((bar, i) => {
        if (i < index) {
            bar.style.width = '100%';
        } else if (i === index) {
            bar.style.width = '0%';
        } else {
            bar.style.width = '0%';
        }
    });

    const currentSlide = document.querySelector(`.story-slide[data-index="${index}"]`);
    const video = currentSlide.querySelector('video');
    if (video) {
        video.play();
        document.querySelectorAll('video').forEach(v => {
            if (v !== video) v.pause();
        });
    }

    currentIndex = index;
    updateReactionDisplay();
}

function startProgress() {
    clearInterval(progressInterval);
    const duration = 5000;
    const interval = 50;
    let elapsed = 0;

    const progressBar = document.querySelector(`.story-progress[data-index="${currentIndex}"]`);
    
    progressInterval = setInterval(() => {
        elapsed += interval;
        const percentage = (elapsed / duration) * 100;
        progressBar.style.width = `${percentage}%`;

        if (elapsed >= duration) {
            nextStory();
        }
    }, interval);
}

function nextStory() {
    if (currentIndex < totalStories - 1) {
        updateStory(currentIndex + 1);
        startProgress();
    } else {
        clearInterval(progressInterval);
        // Don't auto-redirect, just mark as viewed
        const progressBar = document.querySelector(`.story-progress[data-index="${currentIndex}"]`);
        progressBar.style.width = '100%';
    }
}

function prevStory() {
    if (currentIndex > 0) {
        updateStory(currentIndex - 1);
        startProgress();
    }
}

document.getElementById('next-story').addEventListener('click', nextStory);
document.getElementById('prev-story').addEventListener('click', prevStory);

document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') nextStory();
    if (e.key === 'ArrowLeft') prevStory();
    if (e.key === 'Escape') window.location.href = '{{ route("stories.index") }}';
});

const reactionBtn = document.getElementById('reaction-btn');
reactionBtn.addEventListener('click', async () => {
    const storyId = stories[currentIndex].id;
    const response = await fetch(`/stories/${storyId}/react`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ type: 'like' })
    });
    
    if (response.ok) {
        const data = await response.json();
        const heartIcon = reactionBtn.querySelector('.heart-icon');
        
        if (data.reacted) {
            heartIcon.style.fontVariationSettings = "'FILL' 1";
            heartIcon.style.color = '#ef4444';
            reactionBtn.setAttribute('data-reacted', 'true');
        } else {
            heartIcon.style.fontVariationSettings = "'FILL' 0";
            heartIcon.style.color = 'white';
            reactionBtn.setAttribute('data-reacted', 'false');
        }
        
        document.getElementById('reaction-count').textContent = data.reaction_count > 0 ? data.reaction_count : '';
        
        stories[currentIndex].reactions_count = data.reaction_count;
        stories[currentIndex].user_reacted = data.reacted;
    }
});

        
function updateReactionDisplay() {
    const currentStory = stories[currentIndex];
    const reactionBtn = document.getElementById('reaction-btn');
    const heartIcon = reactionBtn.querySelector('.heart-icon');
    const reactionCount = document.getElementById('reaction-count');
    
    if (currentStory.user_reacted) {
        heartIcon.style.fontVariationSettings = "'FILL' 1";
        heartIcon.style.color = '#ef4444';
        reactionBtn.setAttribute('data-reacted', 'true');
    } else {
        heartIcon.style.fontVariationSettings = "'FILL' 0";
        heartIcon.style.color = 'white';
        reactionBtn.setAttribute('data-reacted', 'false');
    }
    
    reactionCount.textContent = currentStory.reactions_count > 0 ? currentStory.reactions_count : '';
}

        
const deleteBtn = document.getElementById('delete-btn');
if (deleteBtn) {
    deleteBtn.addEventListener('click', async () => {
        if (!confirm('Delete this story?')) return;
        
        const storyId = stories[currentIndex].id;
        const response = await fetch(`/stories/${storyId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            window.location.href = '{{ route("stories.index") }}';
        }
    });
}

init();
</script>
@endsection
