<?php

namespace App\Controller\Front;

use App\Entity\Member;
use App\Form\SelectArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('{id}/producten', name: 'article.index', methods: ['GET'])]
    public function index(Member $member, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        $form = $this->createForm(SelectArticleType::class);

        return $this->render('front/article.html.twig', [
            'member' => $member,
            'articles' => $articles,
            'form' => $form,
        ]);
    }

    #[Route('{id}/producten', name: 'article.store', methods: ['POST'])]
    public function store(Request $request, Member $member, ArticleRepository $articleRepository, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(SelectArticleType::class);
        $form->handleRequest($request);

        if (! $form->isSubmitted() && ! $form->isValid()) {
            return $this->redirect($this->generateUrl('article.index', ['id' => $member->getId()]));
        }

        $article = $articleRepository->find($form->get('articleId')->getData());

        $member->setArticle($article);
        $manager->persist($member);
        $manager->flush();

        return $this->redirect($this->generateUrl('thankYou.index', ['id' => $member->getId()]));
    }
}
