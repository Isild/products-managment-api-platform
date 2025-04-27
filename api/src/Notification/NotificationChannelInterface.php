<?php

namespace App\Notification;

interface NotificationChannelInterface
{
    public function send(Notification $notification): void;
}
