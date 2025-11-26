<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'trending');

        $posts = Cache::remember("explore_{$filter}_posts", 900, function() use ($filter) {
            return $this->getPostsByFilter($filter);
        });

        $popularTags = Cache::remember('popular_tags', 1800, function() {
            return Tag::popular(10)->get();
        });

        $topCreators = Cache::remember('top_creators', 3600, function() {
            return User::topCreators(10)->get();
        });

        return view('explore.index', compact('posts', 'popularTags', 'topCreators', 'filter'));
    }

    private function getPostsByFilter($filter)
    {
        $query = Post::with(['user', 'reactions', 'comments', 'tags']);

        switch ($filter) {
            case 'trending':
                return $query->trending(7)->paginate(12);
            
            case 'popular':
                return $query->popular(20)->paginate(12);
            
            case 'latest':
                return $query->published()->latest()->paginate(12);
            
            case 'following':
                if (Auth::check()) {
                    $followingIds = Auth::user()->following()->pluck('following_id')->toArray();
                    return $query->published()
                        ->whereIn('user_id', $followingIds)
                        ->latest()
                        ->paginate(12);
                }
                return $query->published()->latest()->paginate(12);
            
            default:
                return $query->trending(7)->paginate(12);
        }
    }

    public function trending(Request $request)
    {
        $days = $request->get('days', 7);
        $limit = $request->get('limit', 10);

        $posts = Post::with(['user:id,name,username,avatar', 'reactions', 'comments'])
            ->trending($days)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }

    public function popularUsers(Request $request)
    {
        $limit = $request->get('limit', 10);

        $users = User::popular($limit)->get();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    public function byTag($tagSlug)
    {
        $tag = Tag::where('slug', $tagSlug)->firstOrFail();
        
        $posts = Post::with(['user', 'reactions', 'comments', 'tags'])
            ->byTag($tagSlug)
            ->published()
            ->latest()
            ->paginate(12);

        return view('explore.tag', compact('tag', 'posts'));
    }
}