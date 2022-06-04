<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Users;
use App\Entity\PrivateMessages;
use App\Form\messageType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Knp\Component\Pager\PaginatorInterface;


class PrivateMessageController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function messages(Request $request, PaginatorInterface $paginator){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $private_message = new PrivateMessages();
        $form = $this->createForm(messageType::class, $private_message, array('empty_data'=>$user));

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $private_message->setEmitter($user);
                $private_message->setCreatedAt(new \DateTime("now"));
                $private_message->setReaded(0);

                $em->persist($private_message);
                $flush = $em->flush();
                if($flush == null){
                    $status = "El mensaje privado se a enviado";
                }else{
                    $status = "El mensaje privado no se a enviado";
                };
            }else{
                $status = "El mensaje privado no se a enviado";
            };
            return $this->redirect("messages");
        }

        $private_message = $this->getPrivateMessages($request, 'remited', $paginator);
        return $this->render('message.html.twig', [
            'form' => $form->createView(),
            'pagination'  => $private_message
        ]);
    }

    /**
     * @Route("/enviados", name="sendedAction")
     */
    public function sendedAction(Request $request, PaginatorInterface $paginator){
        $private_message = $this->getPrivateMessages($request, "sended", $paginator);
        return $this->render('sended.html.twig', [
            'pagination'  => $private_message
        ]);
    }

    public function getPrivateMessages($request, $type = null, $paginator){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user_id = $user->getId();
        
        if($type == "sended" || $type == null){
            $dql = "SELECT p FROM App:PrivateMessages p WHERE p.emitter = $user_id ORDER BY p.id DESC";
        }else{
            $dql = "SELECT p FROM App:PrivateMessages p WHERE p.receiver = $user_id ORDER BY p.id DESC";
        }

        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 4
        );
        return $pagination;
    }



 }
?> 