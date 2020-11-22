<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostUpdateType;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param PostRepository $postRepo
     * @return Response
     */
    public function index(PostRepository $postRepo)
    {

        $posts = $postRepo->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name'   => 'Nicolas',
            'posts'             => $posts
        ]);
    }

    /**
     * @Route("/posts/{id}", name="show_post")
     * @param Post $post
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function show(Post $post ,Request $request , EntityManagerInterface $em)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $comment->setCreatedAt( new DateTime());   // attribut la date
        $comment->setPost($post);                   // attache a un post
        $form->handleRequest($request);             // recupère le formulaire
                                                    //check si le formulaire a ete envoyer
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($comment);                 // rend le comment persistent
            $em->flush();                           // enregistre en bdd
        }

        return $this->render('home/post.html.twig', [
            'post'              => $post,
            'form'              => $form->createView()

        ]);
    }

    /**
     * @Route("/admin" , name="admin")
     * @param PostRepository $postRepo
     * @return Response
     */
    public function admin(PostRepository $postRepo)
    {
        $posts = $postRepo->findAll();
        return $this->render('admin/admin.html.twig', [
            'posts' => $posts
        ]);

    }

    /**
     * @Route("/admin/delete/{id}" , name="admin_delete")
     * @param Post $post
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Post $post, EntityManagerInterface $em)
    {

        $mypost = $em->getRepository(Post::class)->find($post->getId());
        if (!$mypost) {
            throw $this->createNotFoundException('On ne trouve pas l\'entité post');
        }
        $em->persist($mypost);
        $em->remove($mypost);
        $em->flush();

        return $this->redirect($this->generateUrl('admin'));
    }

    /**
     * @Route("/admin/update/{id}" , name="admin_update")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Post $post
     * @return Response
     */
    public function update(Request $request, EntityManagerInterface $em, Post $post)
    {
        $form = $this->createForm(PostUpdateType::class, $post);
        $post->setCreatedAt( new DateTime());      // attribut la date
        $form->handleRequest($request);             // recupère le formulaire

            //check si le formulaire a ete envoyer
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($post);                    // rend le comment persistent
            $em->flush();                           // enregistre en bdd

            return $this->redirect($this->generateUrl('admin'));
        }
        return $this->render('admin/update.html.twig', [
            'form'              => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/create", name="admin_create")
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $post = new Post();
        $form = $this->createForm(CommentType::class, $post);
        $post->setCreatedAt( new DateTime());   // attribut la date
        $form->handleRequest($request);         // recupère le formulaire
                                                //check si le formulaire a ete envoyer
        if ($form->isSubmitted() && $form->isValid()){

            $em->persist($post);                 // rend le comment persistent
            $em->flush();                        // enregistre en bdd

            return $this->redirect($this->generateUrl('admin'));
        }
    }
}
