<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeUnlikeRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostCreated;
use App\Traits\ResponseGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    use ResponseGenerator;

    /**
     * Display a listing of the posts.
     *
     */
    public function index()
    {
        $posts = Post::latest('created_at')->paginate(config('misc.page_len'));

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created post.
     *
     * @param StorePostRequest $request
     * @return PostResource
     */
    public function store(StorePostRequest $request): PostResource
    {
        // storing post's image on public disk
        $path = $request->file('image')->store("/", 'public');

        $post = new Post();
        $post->fill($request->only('description', 'title'));
        $post->author = auth()->id();
        $post->image = $path;
        $post->save();

        // notify all users (except creator) about a newly created post
        Notification::send(User::query()->where('id', '!=', auth()->id())->get(), new PostCreated($post));

        return new PostResource($post);
    }


    /**
     * Display the specified post
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }


    /**
     * Update the specified post.
     *
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return PostResource
     */
    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        // storing post's image (if present) on public disk
        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store("/", 'public');
        }

        $post->update($request->only(['description', 'title']));

        return new PostResource($post);
    }


    /**
     * Remove the specified post
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        // Making sure user is deleting his/her own post
        // Note: the authorization logic could be moved into a separate "policy" class, if it were complex
        Gate::allowIf(function ($user) use ($post) {
            return $post->author === $user->id;
        });

        if ($post->delete()) {
            Storage::disk('public')->delete($post->image);
        }

        return response()->json(null, 204);
    }

    public function likeUnlikePost(LikeUnlikeRequest $request, Post $post)
    {

        if (auth()->user()->posts()->where('post_id', $post->id)->exists()) {

            auth()->user()->posts()->updateExistingPivot($post->id, ['is_liked' => $request->input('is_liked')]);

        } else {
            auth()->user()->posts()->attach($post->id, ['is_liked' => $request->input('is_liked')]);
        }

        $this->has_err = false;

        return $this->sendResponse();

    }

}
