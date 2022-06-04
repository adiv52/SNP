<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Following;
use App\Entity\Users;
use App\Entity\Notifications;
use App\Repository\UsersRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\notificationService;


class FollowController extends AbstractController
{
    /**
     * @Route("/follow", name="followAction")
     */
    public function followAction(Request $request){
        $user = $this->getUser();
        $followed_id = $request->get('followed');
        $em = $this->getDoctrine()->getManager();
        $user_repo = $em -> getRepository(Users::class);
        $followed = $user_repo->find($followed_id);

        $following = new Following();
        $following->setUser($user);
        $following->setFollowed($followed);

        $em->persist($following);
        $flush = $em->flush(); 
        $Setnotification = $this->setNotficiations($followed, 'follow', $user->getId()); 
        if($flush == null){
            $status = "Ahora estas siguiendo a este usuario";
        }else{
            $status = "No se ha podido seguir a este usuario";
        }

        return new Response($status);
    }
    
    
    /**
     * @Route("/unfollow", name="unfollowAction")
     */
    public function unfollowAction(Request $request){
        $user = $this->getUser();
        $followed_id = $request->get('followed');
        $em = $this->getDoctrine()->getManager();
        $following_repo = $em->getRepository(Following::class);
        $followed = $following_repo->findOneBy(
            array(
                'user'=>$user, 
                'followed'=>$followed_id
        ));
        $em->remove($followed);
        $flush = $em->flush(); 

        if($flush == null){
            $status = "Has dejado de seguir a este usuario";
        }else{
            $status = "No has dejado de seguir a este usuario";
        }

        return new Response($status);
    }

    /**
     * @Route("/follows", name="follows")
     */
    public function follows(Request $request, $nickname = null, PaginatorInterface $paginator){
        $em = $this->getDoctrine()->getManager();
        if($nickname != null){
            $user_repo = $em->getRepository(User::class);
            $user = $user_repo->findOneBy(array("nick"=>$nickname));
        }else{
            $user = $this->getUser();
        }
        $user_id = $user->getId();
        $dql = "SELECT f FROM App:Following f WHERE f.user = $user_id ORDER BY f.id DESC";
        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 5
        );

        return $this->render('friends.html.twig',array(
            "user"     => $user,
            'type'    => 'follows',
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/followed", name="followed")
     */
    public function followed(Request $request, $nickname = null, PaginatorInterface $paginator){
        $em = $this->getDoctrine()->getManager();
        if($nickname != null){
            $user_repo = $em->getRepository(User::class);
            $user = $user_repo->findOneBy(array("nick"=>$nickname));
        }else{
            $user = $this->getUser();
        }
        $user_id = $user->getId();
        $dql = "SELECT f FROM App:Following f WHERE f.followed = $user_id ORDER BY f.id DESC";
        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 5
        );

        return $this->render('followed.html.twig',array(
            "user"     => $user,
            'type'    => 'followed',
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/unfollows", name="unfollows")
     */
    public function unfollows(Request $request, $nickname = null, PaginatorInterface $paginator){
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT u FROM App:Users u";
        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 5
        );

        return $this->render('users.html.twig',array(
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/search", name="searchAction")
     */
    public function searchAction(Request $request, PaginatorInterface $paginator){
        $em = $this->getDoctrine()->getManager();
        
        $search = $request->query->get("search", null);
        if($search == null){
            return $this->redirectToRoute("index");
        }

        $dql = "SELECT u FROM App:Users u WHERE u.name LIKE :search OR u.surname LIKE :search OR u.nick LIKE :search ORDER BY u.id ASC";
        $query = $em->createQuery($dql)->setParameter('search', "%$search%");
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 5
        );

        return $this->render('users.html.twig',array(
            'pagination' => $pagination
        ));
    }
    

    public function setNotficiations($user, $type, $typeId, $extra = null){
        $em = $this->getDoctrine()->getManager();
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
?> 