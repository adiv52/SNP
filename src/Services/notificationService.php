<?php

namespace App\Services;

use App\Entity\Notifications;

class notificationService{
    public $manager;
    public function __construct($manager){
        $this->manager = $manager;
    }
    public function set($user, $type, $typeId, $extra = null){
        $em = $this->manager;
        $notification = new Notifications();
        $notification->setUser($user);
        $notification->setType($type);
        $notification->setTypeId($typeId);
        $notification->setReaded(0);
        $notification->setCreatedAt(new \DateTime("now"));
        $notification->setExtra($extra);
        $em->persist($notification);
        $flush = $em->flush();
        if($flush == null){
            $status = true;
        }else{
            $status = false;
        }
        return $status;
    }
}