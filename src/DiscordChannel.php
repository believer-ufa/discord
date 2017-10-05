<?php

namespace NotificationChannels\Discord;

use Illuminate\Notifications\Notification;
use Log;

class DiscordChannel
{
    /**
     * @var \NotificationChannels\Discord\Discord
     */
    protected $discord;

    /**
     * @param \NotificationChannels\Discord\Discord $discord
     */
    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return array
     *
     * @throws \NotificationChannels\Discord\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $channel = $notifiable->routeNotificationFor('discord')) {
            return;
        }

        $message = $notification->toDiscord($notifiable);

        if (empty(config('services.discord.token'))) {
            Log::debug("Message to discord channel №{$channel}.\nText: {$message->body}");

            return;
        }
        
        return $this->discord->send($channel, [
            'content' => $message->body,
            'embed' => $message->embed,
        ]);
    }
}
