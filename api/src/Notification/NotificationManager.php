<?php

namespace App\Notification;

class NotificationManager
{
    public function __construct(private iterable $channels = [])
    {

    }

    public function addChannel(NotificationChannelInterface $channel): void
    {
        $this->channels[] = $channel;
    }

    public function notify(Notification $notification): void
    {
        foreach ($this->channels as $channel) {
            $channel->send($notification);
        }
    }
}
