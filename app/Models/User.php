<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $username
 * @property string $email
 * @property string $role
 * @property string|null $bio
 * @property string|null $avatar
 * @property string|null $cover_photo
 * @method HasMany posts()
 * @method HasMany stories()
 * @method HasMany comments()
 * @method HasMany reactions()
 * @method HasMany notifications()
 * @method BelongsToMany following()
 * @method BelongsToMany followers()
 * @method BelongsToMany bookmarkedPosts()
 * @method bool isFollowing(int $userId)
 * @method bool hasBookmarked(int $postId)
 * @method bool isAdmin()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the route key name for Laravel route model binding
     * Always use ID to avoid issues with NULL usernames
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Get the value for the route key
     */
    public function getRouteKey()
    {
        return $this->id;
    }

    /**
     * Retrieve the model for a bound value (supports both username and ID)
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // If value is empty or null, fail
        if (empty($value)) {
            return null;
        }

        // If numeric, treat as ID
        if (is_numeric($value)) {
            return $this->where('id', $value)->firstOrFail();
        }
        
        // Otherwise, try username first
        $user = $this->where('username', $value)->first();
        
        // If not found by username, try as ID (for numeric strings)
        return $user ?: $this->where('id', $value)->firstOrFail();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'password',
        'dob',
        'phone',
        'village',
        'post',
        'police_station',
        'district',
        'bio',
        'avatar',
        'cover_photo',
    ];

    /**
     * Define the one-to-many relationship with the Post model.
     * This is the method that fixes the "User::posts() undefined" error.
     */
    public function posts(): HasMany
    {
        // One User can have many Posts
        return $this->hasMany(Post::class);
    }

    /**
     * Stories relationship
     */
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    /**
     * Active (non-expired) stories
     */
    public function activeStories()
    {
        return $this->hasMany(Story::class)->active();
    }

    /**
     * Comments relationship
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Reactions relationship
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    /**
     * Notifications received
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Unread notifications
     */
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->unread();
    }

    /**
     * Users that this user is following
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    /**
     * Users that are following this user
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    /**
     * Check if this user is following another user
     */
    public function isFollowing($userId): bool
    {
        return $this->following()->where('following_id', $userId)->exists();
    }

    /**
     * Check if this user is followed by another user
     */
    public function isFollowedBy($userId): bool
    {
        return $this->followers()->where('follower_id', $userId)->exists();
    }

    /**
     * Media files uploaded by user
     */
    public function mediaFiles()
    {
        return $this->hasMany(MediaFile::class);
    }

    /**
     * Bookmarks relationship
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Bookmarked posts
     */
    public function bookmarkedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'bookmarks')->withTimestamps();
    }

    /**
     * Check if user has bookmarked a post
     */
    public function hasBookmarked($postId): bool
    {
        return $this->bookmarkedPosts()->where('post_id', $postId)->exists();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Scope: Search users by name, username, or email
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Active users with posts
     */
    public function scopeActive($query)
    {
        return $query->has('posts');
    }

    /**
     * Scope: Top creators by post count
     */
    public function scopeTopCreators($query, $limit = 10)
    {
        return $query->withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit($limit);
    }

    /**
     * Scope: Popular users by followers
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->limit($limit);
    }
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}