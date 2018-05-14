<?php

namespace Padosoft\Laravel\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BaseNotification extends Notification
{
    use Queueable;

    protected $preferredChannel = ['mail'];

    protected $notification_subject;
    protected $notification_message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $message, string $subject)
    {
        //
        $this->notification_message = $message;
        $this->notification_subject = $subject;
    }

    public function setPreferredChannel(array $channels)
    {
        $this->preferredChannel = $channels;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        if (count($this->preferredChannel) > 0) {
            return $this->preferredChannel;
        }

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $oggetto = $this->notification_subject;
        $messaggio = $this->notification_message;

        return (new MailMessage)->view('emails.notifications.notification',
            compact('oggetto', 'messaggio'))->subject($this->notification_subject);


    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            //
            'notification_subject' => $this->notification_subject,
            'notification_message' => $this->notification_message,
        ];
    }

    /**
     * Get the Nexmo / SMS representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        $unicode = (strlen($this->notification_message) != strlen(utf8_decode($this->notification_message)));
        $message = (new NexmoMessage)
            ->content($this->notification_message);
        $sender = config('padosoft-notification.sms_sender') != '' ?: config('services.nexmo.sms_from');
        if ($sender != '') {
            $message->from($sender);
        }

        return ($unicode) ? $message->unicode() : $message;

    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $message=(new SlackMessage)->info();
        if (config('padosoft-notification.slack.sender')!=''){
            $message->from(config('padosoft-notification.slack.sender'), config('padosoft-notification.slack.sender_icon'));
        }else{
            $message->from(config('app.name'), ':robot_face:');
        }

        $message->content($this->notification_message);
        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            //
            'notification_subject' => $this->notification_subject,
            'notification_message' => $this->notification_message,
        ];
    }
}
