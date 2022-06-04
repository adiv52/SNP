<?php

namespace App\Controller;

use App\Entity\Publications;
use App\Form\PublicationType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Users;
use App\Entity\Notifications;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;



class NotificationsController extends AbstractController
{
    /**
     * @Route("/notifications", name="notificationsIndex")
     */
    public function notificationsIndex(Request $request, PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user_id = $user->getId();
        $dql = "SELECT n From App:Notifications n WHERE n.user = $user_id ORDER BY n.id DESC";
        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 15
        );

        return $this->render('notifications.html.twig', [
            'user'          => $user,
            'pagination'    => $pagination
        ]);
    }
    /**
     * @Route("/numNotif", name="numNotif")
     */
    public function countNotifications(){
        $em = $this->getDoctrine()->getManager();
        $notification_repo = $em->getRepository(Notifications::class);
        $notifications = $notification_repo->findBy(array(
            'user'   => $this->getUser(),
            'readed' => 0
        ));

        return new Response(count($notifications));
    }
 }
?> 