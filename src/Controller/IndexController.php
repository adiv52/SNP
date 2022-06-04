<?php

namespace App\Controller;

use App\Entity\Publications;
use App\Form\PublicationType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Users;
use App\Entity\Following;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;



class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        

        $publication = new Publications();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()){
            $publication->setUser($user);
            $publication->setCreatedAt(new \DateTime("now"));
            $em->persist($publication);
            $flush = $em->flush();

            if ($flush == null){
                $status = "Publicacion guardada correctamente";
            }else{
                $status = "Publicacion no guardada correctamente";
            }
        }

        $publication = $this->getPublications($request, $paginator);

        return $this->render('index.html.twig', [
            'form' => $form->createView(),
            'pagination'    => $publication
        ]);
    }

    public function getPublications($request, $paginator){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $publicationRepo = $em->getRepository(Publications::class);
        $following_repo = $em->getRepository(Following::class);
        
        $following = $following_repo->findBy(array('user' => $user));
        $following_array = array();
        foreach($following as $follow){
            $following_array[] = $follow->getFollowed();
        }
        $query = $publicationRepo->createQueryBuilder('p')
            ->where('p.user = (:user_id) OR p.user IN (:following)')
            ->setParameter('user_id', $user->getId())
            ->setParameter('following', $following_array)
            ->orderBy('p.id', 'DESC')
            ->getQuery();
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 4
        );
        return $pagination;
    }
 }
?> 