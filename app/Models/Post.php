<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_path',
        'video_path',
        'status',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Comments on this post
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    /**
     * All comments including replies
     */
    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Polymorphic relationship: Reactions on this post
     */
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    /**
     * Get reaction counts grouped by type
     */
    public function reactionCounts()
    {
        return $this->reactions()
                    ->selectRaw('type, count(*) as count')
                    ->groupBy('type')
                    ->pluck('count', 'type');
    }

    /**
     * Check if a user has reacted to this post
     */
    public function hasUserReacted($userId)
    {
        return $this->reactions()->where('user_id', $userId)->exists();
    }

    /**
     * Get the user's reaction to this post
     */
    public function userReaction($userId)
    {
        return $this->reactions()->where('user_id', $userId)->first();
    }

    /**
     * Bookmarks on this post
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Users who bookmarked this post
     */
    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    /**
     * Check if a user has bookmarked this post
     */
    public function isBookmarkedBy($userId)
    {
        return $this->bookmarkedBy()->where('user_id', $userId)->exists();
    }

    /**
     * Tags relationship
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * Scope: Published posts only
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: Draft posts only
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope: Trending posts (most reactions in given time period)
     */
    public function scopeTrending($query, $days = 7)
    {
        return $query->published()
            ->withCount('reactions')
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('reactions_count', 'desc');
    }

    /**
     * Scope: Search by title or description
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Filter by tag
     */
    public function scopeByTag($query, $tagSlug)
    {
        return $query->whereHas('tags', function($q) use ($tagSlug) {
            $q->where('slug', $tagSlug);
        });
    }

    /**
     * Scope: Popular posts by views/reactions
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->published()
            ->withCount(['reactions', 'comments'])
            ->orderBy('reactions_count', 'desc')
            ->limit($limit);
    }
}