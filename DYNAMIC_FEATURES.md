# Dynamic Features Implementation Guide

## Overview
This document outlines all dynamic features implemented in the Smart Blog application following Laravel best practices and modern web development patterns.

---

## 🏗️ Architecture Foundation

### 1. Polymorphic Relationships ✅ IMPLEMENTED

#### Reactions Model
- **Purpose**: Universal reaction system (like, love, laugh, angry, sad, wow)
- **Applies To**: Posts, Stories
- **Implementation**: `reactable_type` and `reactable_id` columns
- **Usage**:
```php
// Add reaction
$post->reactions()->create([
    'user_id' => auth()->id(),
    'type' => 'like'
]);

// Check if user reacted
$post->hasUserReacted($userId);

// Get reaction counts by type
$post->reactionCounts();
```

#### Comments Model
- **Purpose**: Nested comment system with unlimited reply depth
- **Implementation**: Self-referencing via `parent_id`
- **Features**:
  - Top-level comments
  - Nested replies
  - @username mentions with notifications
- **Usage**:
```php
// Add comment
$post->allComments()->create([
    'user_id' => auth()->id(),
    'content' => 'Great post!',
    'parent_id' => null // or comment_id for reply
]);

// Get top-level comments
$post->comments()->get();

// Get all comments including replies
$post->allComments()->get();
```

### 2. Many-to-Many Relationships ✅ IMPLEMENTED

#### Follow System
- **Pivot Table**: `follows`
- **Columns**: `follower_id`, `following_id`
- **Usage**:
```php
// Follow a user
$user->following()->attach($targetUser->id);

// Unfollow
$user->following()->detach($targetUser->id);

// Check if following
$user->isFollowing($targetUser->id);

// Get followers/following
$user->followers()->get();
$user->following()->get();
```

#### Bookmark System
- **Pivot Table**: `bookmarks`
- **Columns**: `user_id`, `post_id`
- **Usage**:
```php
// Bookmark a post
Bookmark::create(['user_id' => auth()->id(), 'post_id' => $post->id]);

// Check if bookmarked
$user->hasBookmarked($postId);

// Get bookmarked posts
$user->bookmarkedPosts()->get();
```

#### Tag System ✅ NEW
- **Pivot Table**: `post_tag`
- **Columns**: `post_id`, `tag_id`
- **Features**: Auto-slug generation, popular tags scope
- **Usage**:
```php
// Attach tags to post
$post->tags()->attach([1, 2, 3]);

// Get popular tags
Tag::popular(10)->get();

// Filter posts by tag
Post::byTag('laravel')->get();
```

---

## ⚡ Dynamic API Endpoints (AJAX-Ready)

### Follow/Unfollow
**Endpoint**: `POST /users/{user}/follow`  
**Returns**:
```json
{
    "success": true,
    "message": "You are now following John Doe",
    "followers_count": 125
}
```

**Endpoint**: `DELETE /users/{user}/unfollow`  
**Returns**:
```json
{
    "success": true,
    "message": "You unfollowed John Doe",
    "followers_count": 124
}
```

### Reactions
**Endpoint**: `POST /posts/{post}/react`  
**Payload**: `{ "type": "like|love|laugh|angry|sad|wow" }`  
**Returns**:
```json
{
    "success": true,
    "message": "Reaction added!",
    "reaction_count": 45
}
```

**Endpoint**: `DELETE /posts/{post}/unreact`  
**Returns**:
```json
{
    "success": true,
    "reaction_count": 44
}
```

### Comments
**Endpoint**: `POST /posts/{post}/comments`  
**Payload**: `{ "content": "Great post!", "parent_id": null }`  
**Returns**:
```json
{
    "success": true,
    "message": "Comment posted!",
    "comment": { /* full comment object with user */ },
    "comments_count": 23
}
```

**Endpoint**: `PUT /comments/{comment}`  
**Payload**: `{ "content": "Updated comment" }`  
**Returns**:
```json
{
    "success": true,
    "message": "Comment updated!",
    "comment": { /* updated comment */ }
}
```

**Endpoint**: `DELETE /comments/{comment}`  
**Returns**:
```json
{
    "success": true,
    "message": "Comment deleted!",
    "comments_count": 22
}
```

### Bookmarks
**Endpoint**: `POST /bookmarks/posts/{post}`  
**Returns**:
```json
{
    "success": true,
    "message": "Post bookmarked!"
}
```

**Endpoint**: `DELETE /bookmarks/posts/{post}`  
**Returns**:
```json
{
    "success": true,
    "message": "Bookmark removed!"
}
```

### Notifications
**Endpoint**: `GET /notifications/unread-count`  
**Returns**:
```json
{
    "count": 5
}
```

**Endpoint**: `POST /notifications/{notification}/read`  
**Returns**:
```json
{
    "success": true
}
```

**Endpoint**: `POST /notifications/mark-all-read`  
**Returns**:
```json
{
    "success": true,
    "message": "All notifications marked as read"
}
```

### Explore (Trending Content) ✅ NEW
**Endpoint**: `GET /explore/trending?days=7&limit=10`  
**Returns**:
```json
{
    "success": true,
    "posts": [ /* trending posts */ ]
}
```

**Endpoint**: `GET /explore/popular-users?limit=10`  
**Returns**:
```json
{
    "success": true,
    "users": [ /* popular users */ ]
}
```

---

## 🔔 Real-Time Notifications System

### Notification Types
1. **Follow**: When someone follows you
2. **Reaction**: When someone reacts to your post
3. **Comment**: When someone comments on your post
4. **Reply**: When someone replies to your comment
5. **Mention**: When someone mentions you with @username

### Mention Detection ✅ IMPLEMENTED
The system automatically detects `@username` mentions in comments:

```php
// In CommentController@processMentions()
preg_match_all('/@(\w+)/', $content, $matches);
foreach ($matches[1] as $username) {
    $user = User::where('username', $username)->first();
    // Create notification for mentioned user
}
```

### Usage in Frontend
```javascript
// Example: Update notification count via AJAX
fetch('/notifications/unread-count')
    .then(res => res.json())
    .then(data => {
        document.getElementById('notification-badge').textContent = data.count;
    });
```

---

## 🎯 Query Scopes for Performance ✅ IMPLEMENTED

### Post Model Scopes
```php
// Published posts only
Post::published()->get();

// Draft posts only
Post::draft()->get();

// Trending posts (most reactions in last N days)
Post::trending(7)->get();

// Search by title/description
Post::search('laravel')->get();

// Filter by tag
Post::byTag('laravel')->get();

// Popular posts (by reactions/comments)
Post::popular(10)->get();
```

### User Model Scopes
```php
// Search users
User::search('john')->get();

// Active users (with posts)
User::active()->get();

// Top creators (most posts)
User::topCreators(10)->get();

// Popular users (most followers)
User::popular(10)->get();
```

### Tag Model Scopes
```php
// Popular tags (most posts)
Tag::popular(10)->get();
```

---

## ⚙️ Performance Optimizations ✅ IMPLEMENTED

### 1. Database Indexes
All frequently queried columns have indexes:
- Posts: `status`, `created_at`, composite indexes
- Comments: `post_id`, `user_id`, `parent_id`
- Reactions: `type`, `reactable_type`, `reactable_id`
- Follows: `follower_id`, `following_id`
- Bookmarks: `user_id`, `post_id`
- Notifications: `user_id`, `is_read`
- Stories: `user_id`, `expires_at`
- Users: `username`, `role`

### 2. Eager Loading (N+1 Prevention)
All controllers use proper eager loading:

```php
// PostController@welcome
Post::with(['user:id,name,username,avatar', 'reactions', 'comments', 'tags'])
    ->published()
    ->get();

// PostController@show
$post->load([
    'user:id,name,username,avatar,bio',
    'reactions.user:id,name,username,avatar',
    'comments.user:id,name,username,avatar',
    'comments.replies.user:id,name,username,avatar',
    'tags'
]);

// ExploreController@index
Post::with(['user', 'reactions', 'comments', 'tags'])
    ->trending(7)
    ->paginate(12);
```

### 3. Caching Strategy
ExploreController implements caching:

```php
// Cache trending posts for 15 minutes
Cache::remember("explore_{$filter}_posts", 900, function() {
    return Post::trending(7)->paginate(12);
});

// Cache popular tags for 30 minutes
Cache::remember('popular_tags', 1800, function() {
    return Tag::popular(10)->get();
});

// Cache top creators for 1 hour
Cache::remember('top_creators', 3600, function() {
    return User::topCreators(10)->get();
});
```

---

## 📋 Implementation Checklist

### Backend ✅
- [x] Reaction model with polymorphic relationship
- [x] Comment model with self-referencing
- [x] Follow system (many-to-many)
- [x] Bookmark system (many-to-many)
- [x] Tag system (many-to-many)
- [x] Mention detection in comments
- [x] JSON responses in all controllers
- [x] Query scopes for filtering/searching
- [x] Database indexes for performance
- [x] Eager loading in controllers
- [x] ExploreController for trending content
- [x] Notification system

### Frontend (To Implement)
- [ ] AJAX handlers for follow/unfollow buttons
- [ ] AJAX handlers for reactions (heart icons)
- [ ] Real-time comment submission
- [ ] Notification dropdown with live updates
- [ ] Search autocomplete
- [ ] Infinite scroll for feeds
- [ ] Story viewer carousel
- [ ] Tag cloud/filter UI

---

## 🚀 Next Steps for Full Dynamic Experience

### 1. Frontend JavaScript Implementation
Create `resources/js/dynamic.js`:

```javascript
// Example: Follow button with AJAX
document.querySelectorAll('.follow-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        e.preventDefault();
        const userId = btn.dataset.userId;
        const isFollowing = btn.dataset.following === 'true';
        
        const url = isFollowing 
            ? `/users/${userId}/unfollow` 
            : `/users/${userId}/follow`;
        
        const response = await fetch(url, {
            method: isFollowing ? 'DELETE' : 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            btn.textContent = isFollowing ? 'Follow' : 'Following';
            btn.dataset.following = !isFollowing;
            document.querySelector('.followers-count').textContent = data.followers_count;
        }
    });
});
```

### 2. Laravel Echo Setup (Optional)
For real-time notifications, configure Laravel Echo with Pusher:

```javascript
// resources/js/bootstrap.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Listen for notifications
Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        // Update notification bell
        updateNotificationBadge();
    });
```

### 3. Rich Text Editor
Integrate CKEditor or TinyMCE:

```html
<textarea id="post-content"></textarea>
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#post-content'));
</script>
```

---

## 📚 API Reference Summary

| Feature | Endpoint | Method | Returns JSON |
|---------|----------|--------|--------------|
| Follow | `/users/{user}/follow` | POST | ✅ |
| Unfollow | `/users/{user}/unfollow` | DELETE | ✅ |
| React | `/posts/{post}/react` | POST | ✅ |
| Unreact | `/posts/{post}/unreact` | DELETE | ✅ |
| Comment | `/posts/{post}/comments` | POST | ✅ |
| Update Comment | `/comments/{comment}` | PUT | ✅ |
| Delete Comment | `/comments/{comment}` | DELETE | ✅ |
| Bookmark | `/bookmarks/posts/{post}` | POST | ✅ |
| Unbookmark | `/bookmarks/posts/{post}` | DELETE | ✅ |
| Notifications | `/notifications/unread-count` | GET | ✅ |
| Mark Read | `/notifications/{notification}/read` | POST | ✅ |
| Trending | `/explore/trending` | GET | ✅ |
| Popular Users | `/explore/popular-users` | GET | ✅ |

---

## 🎓 Best Practices Applied

1. **RESTful API Design**: All endpoints follow REST conventions
2. **Eloquent Relationships**: Proper use of polymorphic and many-to-many
3. **Query Optimization**: Scopes, indexes, and eager loading
4. **Caching**: Strategic caching for expensive queries
5. **DRY Principle**: Reusable scopes and methods
6. **Security**: CSRF protection, authorization checks
7. **Performance**: Database indexes on all foreign keys
8. **Scalability**: Polymorphic design allows easy extension

---

## 💡 Tips for Frontend Integration

1. **Always include CSRF token**:
```javascript
headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
}
```

2. **Check for AJAX requests** in Blade:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

3. **Use Alpine.js for simple interactivity** (already integrated):
```html
<div x-data="{ count: 0 }">
    <button @click="count++">Like (<span x-text="count"></span>)</button>
</div>
```

4. **Implement loading states**:
```javascript
btn.disabled = true;
btn.textContent = 'Loading...';
// ... fetch request
btn.disabled = false;
```

---

## 🔒 Security Considerations

1. **Authorization**: All controllers check user permissions
2. **Validation**: All inputs validated before database operations
3. **CSRF Protection**: Required for all state-changing operations
4. **XSS Prevention**: Blade automatically escapes output
5. **SQL Injection**: Eloquent ORM protects against SQL injection
6. **Rate Limiting**: Consider adding rate limiting to API endpoints

---

**Last Updated**: November 22, 2025  
**Laravel Version**: 12.x  
**Status**: ✅ Backend Complete | 🚧 Frontend Pending
