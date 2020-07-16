<?php

namespace App\EventListener;

use Vich\UploaderBundle\Event\Event;

class ResizeImageListener
{
    public function onVichUploaderPostUpload(Event $event)
    {
        $object = $event->getObject();
        $mapping = $event->getMapping();

        dd($object->getImageFile());

        // do your stuff with $object and/or $mapping...
    }

}