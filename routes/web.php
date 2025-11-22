<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [PostController::class, 'welcome'])->name('welcome');
Route::get('/blogs', [PostController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{post}', [PostController::class, 'show'])->name('blogs.show');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Authentication Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post')->middleware('guest');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [PostController::class, 'dashboard'])->name('dashboard');

    // Post Routes
    Route::get('/dashboard/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/dashboard', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Story Routes
    Route::prefix('stories')->name('stories.')->group(function () {
        Route::get('/', [App\Http\Controllers\StoryController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\StoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\StoryController::class, 'store'])->name('store');
        Route::get('/{userId}', [App\Http\Controllers\StoryController::class, 'show'])->name('show');
        Route::delete('/{story}', [App\Http\Controllers\StoryController::class, 'destroy'])->name('destroy');
        Route::post('/{story}/react', [App\Http\Controllers\StoryController::class, 'react'])->name('react');
        Route::delete('/{story}/unreact', [App\Http\Controllers\StoryController::class, 'unreact'])->name('unreact');
    });

    // Follow Routes
    Route::post('/users/{user}/follow', [App\Http\Controllers\FollowController::class, 'follow'])->name('users.follow');
    Route::post('/users/{user}/unfollow', [App\Http\Controllers\FollowController::class, 'unfollow'])->name('users.unfollow');

    // Reaction Routes
    Route::post('/posts/{post}/react', [App\Http\Controllers\ReactionController::class, 'reactToPost'])->name('posts.react');
    Route::delete('/posts/{post}/unreact', [App\Http\Controllers\ReactionController::class, 'unreactToPost'])->name('posts.unreact');

    // Comment Routes
    Route::post('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');

    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::get('/recent', [App\Http\Controllers\NotificationController::class, 'recent'])->name('recent');
        Route::post('/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
    });

    // Bookmark Routes
    Route::prefix('bookmarks')->name('bookmarks.')->group(function () {
        Route::get('/', [App\Http\Controllers\BookmarkController::class, 'index'])->name('index');
        Route::post('/posts/{post}', [App\Http\Controllers\BookmarkController::class, 'store'])->name('store');
        Route::delete('/posts/{post}', [App\Http\Controllers\BookmarkController::class, 'destroy'])->name('destroy');
    });

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::post('/change-password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('change-password');
    });
});

// Public Profile Routes
Route::get('/users/{user}', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
Route::get('/users/{user}/followers', [App\Http\Controllers\FollowController::class, 'followers'])->name('users.followers');
Route::get('/users/{user}/following', [App\Http\Controllers\FollowController::class, 'following'])->name('users.following');

// Search Routes
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::get('/search/autocomplete', [App\Http\Controllers\SearchController::class, 'autocomplete'])->name('search.autocomplete');

// Explore Routes
Route::get('/explore', [App\Http\Controllers\ExploreController::class, 'index'])->name('explore');
Route::get('/explore/trending', [App\Http\Controllers\ExploreController::class, 'trending'])->name('explore.trending');
Route::get('/explore/popular-users', [App\Http\Controllers\ExploreController::class, 'popularUsers'])->name('explore.popular-users');
Route::get('/tags/{tag}', [App\Http\Controllers\ExploreController::class, 'byTag'])->name('tags.show');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/ban', [App\Http\Controllers\Admin\AdminController::class, 'banUser'])->name('users.ban');
    Route::post('/users/{user}/make-admin', [App\Http\Controllers\Admin\AdminController::class, 'makeAdmin'])->name('users.make-admin');
    Route::post('/users/{user}/remove-admin', [App\Http\Controllers\Admin\AdminController::class, 'removeAdmin'])->name('users.remove-admin');
    
    // Post Management
    Route::get('/posts', [App\Http\Controllers\Admin\AdminController::class, 'posts'])->name('posts');
    Route::delete('/posts/{post}', [App\Http\Controllers\Admin\AdminController::class, 'deletePost'])->name('posts.delete');
    
    // Comment Management
    Route::get('/comments', [App\Http\Controllers\Admin\AdminController::class, 'comments'])->name('comments');
    Route::delete('/comments/{comment}', [App\Http\Controllers\Admin\AdminController::class, 'deleteComment'])->name('comments.delete');
});