<?php

namespace App\Notification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailChannellNotification implements NotificationChannelInterface
{
    public function __construct(private MailerInterface $mailer)
    {

    }

    public function send(Notification $notification): void
    {
        $email = (new Email())
            ->from($_ENV['FROM_EMAIL'])
            ->to($notification->getRecipient())
            ->subject($notification->getTitle())
            ->text($notification->getMessage())
            ->html($notification->getMessageHtml());

        $this->mailer->send($email);
    }
}
