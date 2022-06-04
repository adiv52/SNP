<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Users;
use App\Entity\Following;
use App\Entity\Publications;
use App\Form\RegisterType;
use App\Form\editUserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Knp\Component\Pager\PaginatorInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder){
        $user = new Users();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()){
            $user->setRole('ROLE_USER');
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $flush = $em->flush();

            if ($flush == null){
                $status = "Te has registrado correctamente";
                return $this->redirect("login");
            }else{
                $status = "No te has registrado correctamente";
            }
        }

        
        return $this->render('register.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $autenticationUtils){
        $error = $autenticationUtils->getLastAuthenticationError();

        $lastUsername = $autenticationUtils->getLastUsername();

        return $this->render('login.html.twig', array(
            'error' => $error,
            'last_username' => $lastUsername
        ));
        return $this->redirect("");
    }
    /**
     * @Route("/login", name="logout")
     */
    public function logout(){
        return $this->render('login.html.twig', [
            
        ]);
    }
    /**
     * @Route("/my-data/{id}", name="setings", requirements={"id":"\d+"})
     */
    public function setings(Request $request){
        $user = $this->getUser();
        $form = $this->createForm(editUserType::class, $user);

        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()){
            //subir archivos
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $flush = $em->flush();

            if ($flush == null){
                $status = "Has actualizado los datos correctamente";
            }else{
                $status = "No has actualizado los datos correctamente";
            }
        }
        return $this->render('setings.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/my-profile/{id}", name="profile", requirements={"id":"\d+"})
     */
    public function profile(Request $request, Users $user, PaginatorInterface $paginator){
        $em = $this->getDoctrine()->getManager();
        $user_id = $user->getId();
        $dql = "SELECT p FROM App:Publications p WHERE p.user = $user_id ORDER BY p.id DESC";
        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 4
        );
        $statsfilter = $this->userStatsFilter($user);
        return $this->render('myProfile.html.twig',array(
            'pagination'    => $pagination,
            'user'          => $user,
            'stats'         => $statsfilter
        ));
    }

    public function userStatsFilter($user){
        $em = $this->getDoctrine()->getManager();
        $following_repo = $em->getRepository(Following::class);
        $publications_repo = $em->getRepository(Publications::class);

        $user_following = $following_repo->findBy(array('user'=>$user));
        $user_followers = $following_repo->findBy(array('followed'=>$user));
        $user_publications = $publications_repo->findBy(array('user'=>$user));

        $result = array(
            'following' => count($user_following),
            'followers' => count($user_followers),
            'publications'  => count($user_publications)
        );

        return $result;
    }

 }
?> 