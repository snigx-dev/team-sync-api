<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Task $task)
    {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have been assigned a new task.')
            ->line('**Task:** ' . $this->task->title)
            ->when($this->task->description, function ($mail) {
                return $mail->line('**Description:** ' . $this->task->description);
            })
            ->line('**Priority:** ' . ucfirst($this->task->priority))
            ->line('**Status:** ' . ucfirst(str_replace('_', ' ', $this->task->status)))
            ->when($this->task->due_date, function ($mail) {
                return $mail->line('**Due Date:** ' . $this->task->due_date->format('M d, Y H:i'));
            })
            ->action('View Task', url("/tasks/{$this->task->id}"))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'task_priority' => $this->task->priority,
            'task_status' => $this->task->status,
            'due_date' => $this->task->due_date?->toIso8601String(),
            'message' => "You have been assigned to task: {$this->task->title}",
            'action_url' => url("/tasks/{$this->task->id}"),
        ];
    }
}
