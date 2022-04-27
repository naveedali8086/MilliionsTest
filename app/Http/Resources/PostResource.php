<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{

//    private $show_all_likes;
//
//    public function __construct($show_all_likes = false)
//    {
//        $this->show_all_likes = $show_all_likes;
//    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'image' => asset('storage/' . $this->image),
            'description' => $this->description,
            'author' => new UserResource(User::find($this->author)),
            'total_likes' => $this->users()->where('post_user.is_liked', 1)->count(),
            'title' => $this->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // adding last 5 usernames who liked the post
            'usernames' => $this->users()->where('post_user.is_liked', 1)->orderBy('post_user.updated_at')->limit(5)->pluck('users.name'),
        ];

    }
}
