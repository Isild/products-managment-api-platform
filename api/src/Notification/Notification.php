<?php

namespace App\Notification;

class Notification
{
    public function __construct(private string $title, private string $message, private string $messageHtml, private string $recipient)
    {
        $this->title = $title;
        $this->message = $message;
        $this->recipient = $recipient;
        $this->messageHtml = $messageHtml;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function set(string $title)
    {
        $this->title = $title;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function getMessageHtml()
    {
        return $this->messageHtml;
    }

    public function setMessageHtml(string $messageHtml)
    {
        $this->messageHtml = $messageHtml;
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient)
    {
        $this->recipient = $recipient;
    }
}
