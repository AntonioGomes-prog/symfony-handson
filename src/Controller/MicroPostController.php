<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


final class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAll(),
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    public function add(Request $request, MicroPostRepository $posts): Response
    {
        $microPost = new MicroPost();
        $form = $this->createFormBuilder($microPost) // Associamos a entidade
            ->add('title', TextType::class)
            ->add('text', TextareaType::class)
            ->add('submit', SubmitType::class, ['label' => 'Save'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $microPost->setCreated(new \DateTime());
            $posts->add($microPost, true);
            $this->addFlash('success', 'Your micro post have been added!');

            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render(
            'micro_post/add.html.twig',
            [
                'form' => $form
            ]
        );
    }
}