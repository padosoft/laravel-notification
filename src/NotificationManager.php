<?php
/**
 * Copyright (c) Padosoft.com 2018.
 */

namespace Padosoft\Laravel\Notification;


use Illuminate\Notifications\Notification as NotificationInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Padosoft\Laravel\Notification\Models\Mynotifications;
use Padosoft\Laravel\Notification\BaseNotification;

class NotificationManager implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notification_message;
    protected $notification;
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    public function __construct(NotificationInterface $notification)
    {
        $this->tries = config('padosoft-notification.max_tries');
        $this->notification_message = $notification;
    }

    protected function setNotificationChannels(array $methods, $notifications)
    {
        if (!is_a($this->notification_message, BaseNotification::class)) {
            $methods = $this->notification_message->via($notifications);
        }

        if (is_a($this->notification_message, BaseNotification::class)) {
            $this->notification_message->setPreferredChannel($methods);
        }
    }

    protected function logSendedNotification($notification, $user, $methods)
    {
        if (!config('padosoft-notification.audit.send') || $notification->audit_active != 1) {
            return;
        }

        $messages = [];
        foreach ($methods as $method) {
            $method_name = 'to' . ucfirst($method);
            $messages[$method] = $this->notification_message->$method_name($user);
        }
        activity()->performedOn($notification)->withProperties([
            'user' => $user,
            'channels' => $methods,
            'messages' => $messages
        ])->log('Notification send');

    }

    public function handle()
    {
        $notifications = Mynotifications::ofClass($this->notification_message)->first();
        $this->notification = $notifications;
        if ($notifications === null) {
            activity()->withProperties([
                'info' => 'Notification ' . get_class($this->notification_message) . ' not found'
            ])->log('error');

            return true;
        }
        if ($notifications->active != 1) {
            if (config('padosoft-notification.audit.send') && $notifications->audit_active == 1) {
                activity()->performedOn($notifications)->log('error');
            }

            return true;
        }
        $sent_users = collect([]);
        $users = $notifications->notifications_users()->with('users')->get();
        foreach ($users as $user) {
            if ($sent_users->contains($user->users_ID)) {
                continue;
            }

            $methods = $user->methods->toArray();

            $this->setNotificationChannels($methods, $notifications);

            Notification::send([$user->users], $this->notification_message);
            $sent_users->prepend($user->users_ID, $user->users_ID);
            $this->logSendedNotification($notifications, $user->users, $methods);
        }

        $roles = $notifications->notifications_roles()->with('users')->get();
        foreach ($roles as $role) {
            foreach ($role->users as $user) {
                if ($sent_users->contains($user->id)) {
                    continue;
                }
                $methods = $role->methods->toArray();

                $this->setNotificationChannels($methods, $notifications);

                Notification::send([$user], $this->notification_message);
                $sent_users->prepend($user->id, $user->id);
                $this->logSendedNotification($notifications, $user, $methods);
            }
        }
    }

    /**
     * The job failed to process.
     *
     * @param \Exception $exception
     *
     * @return void
     */
    public function failed(\Exception $exception)
    {
        // Send user notification of failure, etc...
        if (config('padosoft-notification.audit.failed') && $this->notification->audit_active == 1) {
            activity()->performedOn($this->notification)->withProperties([
                'exception' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ])->log('Notification error');
        }
    }
}