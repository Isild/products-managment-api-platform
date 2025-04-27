<?php

namespace App\EventListener;

use App\Entity\ProductEntity;
use App\Notification\EmailChannellNotification;
use App\Notification\Notification;
use App\Notification\NotificationManager;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Psr\Log\LoggerInterface;

class ProductEntityUpdatedLoggerListener
{
    public const TITLE = 'Edited Product Entity';
    public const MESSAGE = 'Edited product with id: %s.';
    public const MESSAGE_HTML = 'Edited product with id: <p> %s </p>.';

    public function __construct(private readonly LoggerInterface $logger, private NotificationManager $notificationManager, private EmailChannellNotification $emailChannellNotification)
    {

    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof ProductEntity) {
            $this->logger->info(sprintf($this::MESSAGE, $entity->getId()));

            $this->generateMailNotification($entity);
        }
    }

    private function generateMailNotification(ProductEntity $productEntity): void
    {
        $notification = new Notification(
                $this::TITLE,
            sprintf($this::MESSAGE, $productEntity->getId()),
            sprintf($this::MESSAGE_HTML, $productEntity->getId()),
            $_ENV['RECIPIENT_EMAIL']
        );

        $this->notificationManager->addChannel($this->emailChannellNotification);
        $this->notificationManager->notify($notification);
    }
}
