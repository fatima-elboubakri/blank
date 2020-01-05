<?php
namespace App\Doctrine\Subscriber;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class UserSubscriber implements EventSubscriber
{
    private $encoder;
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if (!$entity instanceof User) {
            return;
        }
        $entity->setPassword($this->encoder->encodePassword(
            $entity,
            $entity->getPassword()
        ));
    }
    public function preUpdate(PreUpdateEventArgs $event)
    {
        $entity = $event->getObject();
        if (!$entity instanceof User) {
            return;
        }
        if ($event->hasChangedField('password')) {
            $encoded = $this->encoder->encodePassword(
                $entity,
                $event->getNewValue('password')
            );
            $event->setNewValue('password', $encoded);
        }
    }
}