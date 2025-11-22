<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'bio' => 'System Administrator',
            ]
        );

        // Create test users
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $users[] = \App\Models\User::firstOrCreate(
                ['email' => "user$i@example.com"],
                [
                    'name' => "User $i",
                    'username' => "user$i",
                    'password' => bcrypt('password'),
                    'role' => 'user',
                    'bio' => "This is user $i's bio",
                ]
            );
        }

        // Create tags
        $tags = [];
        $tagNames = ['Technology', 'Travel', 'Food', 'Lifestyle', 'Photography'];
        foreach ($tagNames as $tagName) {
            $tags[] = \App\Models\Tag::create([
                'name' => $tagName,
                'slug' => \Illuminate\Support\Str::slug($tagName),
                'description' => "Posts about $tagName",
            ]);
        }

        // Create posts for each user
        foreach ($users as $user) {
            for ($i = 1; $i <= 3; $i++) {
                $post = \App\Models\Post::create([
                    'user_id' => $user->id,
                    'title' => "Post $i by {$user->name}",
                    'description' => "This is the description for post $i by {$user->name}. " . str_repeat('Lorem ipsum dolor sit amet, consectetur adipiscing elit. ', 10),
                    'status' => $i % 3 === 0 ? 'draft' : 'published',
                ]);

                // Attach 1-2 random unique tags
                $randomTagIds = collect($tags)->random(rand(1, 2))->pluck('id')->toArray();
                $post->tags()->attach($randomTagIds);
            }
        }

        // Create follows
        foreach ($users as $user) {
            foreach ($users as $otherUser) {
                if ($user->id !== $otherUser->id && rand(0, 1)) {
                    $user->following()->attach($otherUser->id);
                }
            }
        }

        // Create reactions
        $posts = \App\Models\Post::where('status', 'published')->get();
        foreach ($posts as $post) {
            foreach ($users as $user) {
                if (rand(0, 1)) {
                    \App\Models\Reaction::create([
                        'user_id' => $user->id,
                        'reactable_id' => $post->id,
                        'reactable_type' => 'App\\Models\\Post',
                        'type' => ['like', 'love', 'wow'][rand(0, 2)],
                    ]);
                }
            }
        }

        // Create comments
        foreach ($posts as $post) {
            foreach (array_slice($users, 0, rand(1, 3)) as $user) {
                \App\Models\Comment::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                    'content' => "Great post! This is a comment by {$user->name}.",
                ]);
            }
        }

        // Create bookmarks
        foreach ($users as $user) {
            $randomPosts = $posts->random(rand(1, 3));
            foreach ($randomPosts as $post) {
                \App\Models\Bookmark::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                ]);
            }
        }

        // Create notifications
        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                $fromUser = $users[array_rand($users)];
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'from_user_id' => $fromUser->id,
                    'type' => ['follow', 'reaction', 'comment'][rand(0, 2)],
                    'notifiable_type' => 'App\\Models\\Post',
                    'notifiable_id' => $posts->random()->id,
                    'content' => "{$fromUser->name} interacted with your post",
                    'read_at' => $i % 2 === 0 ? now() : null,
                ]);
            }
        }

        $this->command->info('Test data seeded successfully!');
    }
}
