<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PostCreated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The post instance.
     */
    protected Post $post;

    /**
     * Create a new notification instance.
     *
     * @param Post $post
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
//            'id' => $this->post->id,
//            'image' => asset('storage/' . $this->post->image),
//            'description' => $this->post->description,
//            'author' => $this->post->author,
//            'title' => $this->post->title,
//            'created_at' => $this->post->created_at,
//            'updated_at' => $this->post->updated_at,
        ];
    }
}
