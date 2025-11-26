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

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
                    ->whereNull('parent_id')
                    ->orderBy('created_at', 'desc');
    }

    public function allComments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function reactionCounts()
    {
        return $this->reactions()
                    ->selectRaw('type, count(*) as count')
                    ->groupBy('type')
                    ->pluck('count', 'type');
    }

    public function hasUserReacted($userId)
    {
        return $this->reactions()->where('user_id', $userId)->exists();
    }

    public function userReaction($userId)
    {
        return $this->reactions()->where('user_id', $userId)->first();
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    public function isBookmarkedBy($userId)
    {
        return $this->bookmarkedBy()->where('user_id', $userId)->exists();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeTrending($query, $days = 7)
    {
        return $query->published()
            ->withCount('reactions')
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('reactions_count', 'desc');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeByTag($query, $tagSlug)
    {
        return $query->whereHas('tags', function($q) use ($tagSlug) {
            $q->where('slug', $tagSlug);
        });
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->published()
            ->withCount(['reactions', 'comments'])
            ->orderBy('reactions_count', 'desc')
            ->limit($limit);
    }
}