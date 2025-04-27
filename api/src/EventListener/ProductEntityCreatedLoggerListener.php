<?php

namespace App\EventListener;

use App\Entity\ProductEntity;
use App\Notification\EmailChannellNotification;
use App\Notification\Notification;
use App\Notification\NotificationManager;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Psr\Log\LoggerInterface;

class ProductEntityCreatedLoggerListener
{
    public const TITLE = 'New Product Entity';
    public const MESSAGE = 'Created product: %s.';
    public const MESSAGE_HTML = 'Created product: <p> %s </p>.';

    public function __construct(private readonly LoggerInterface $logger, private NotificationManager $notificationManager, private EmailChannellNotification $emailChannellNotification)
    {

    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof ProductEntity) {
            $this->logger->info(sprintf($this::MESSAGE, $entity->getName()));

            $this->generateMailNotification($entity);
        }
    }

    private function generateMailNotification(ProductEntity $productEntity): void
    {
        $notification = new Notification(
                $this::TITLE,
            sprintf($this::MESSAGE, $productEntity->getName()),
            sprintf($this::MESSAGE_HTML, $productEntity->getName()),
            $_ENV['RECIPIENT_EMAIL']
        );

        $this->notificationManager->addChannel($this->emailChannellNotification);
        $this->notificationManager->notify($notification);
    }
}
