<?php

namespace App\Controller\Front;

use App\Entity\Member;
use App\Form\SelectArticleType;
use App\Repository\ArticleRepository;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('producten', name: 'article.index', methods: ['GET'])]
    public function index(Request $request, MemberRepository $memberRepository, ArticleRepository $articleRepository): Response
    {
        $member = $memberRepository->find($request->getSession()->get('member', 0));

        if (! $member){
            $this->addFlash('error', 'Je moet je eerst inloggen!');
            return $this->redirect($this->generateUrl('member.index'));
        }

        if ($member->getArticle()){
            $this->addFlash('error', 'Je hebt al eerder een artikel gekozen!');
            return $this->redirect($this->generateUrl('member.index'));
        }

        $articles = $articleRepository->findAll();

        $form = $this->createForm(SelectArticleType::class);

        return $this->render('front/article.html.twig', [
            'member' => $member,
            'articles' => $articles,
            'form' => $form,
        ]);
    }

    #[Route('producten', name: 'article.store', methods: ['POST'])]
    public function store(Request $request, MemberRepository $memberRepository, ArticleRepository $articleRepository, EntityManagerInterface $manager): Response
    {
        $member = $memberRepository->find($request->getSession()->get('member', 0));

        if (! $member){
            $this->addFlash('error', 'Je moet je eerst inloggen!');
            return $this->redirect($this->generateUrl('member.index'));
        }

        if ($member->getArticle()){
            $this->addFlash('error', 'Je hebt al eerder een artikel gekozen!');
            return $this->redirect($this->generateUrl('member.index'));
        }

        $form = $this->createForm(SelectArticleType::class);
        $form->handleRequest($request);

        if (! $form->isSubmitted() && ! $form->isValid()) {
            return $this->redirect($this->generateUrl('article.index'));
        }

        $article = $articleRepository->find($form->get('articleId')->getData());

        $member->setArticle($article);
        $manager->persist($member);
        $manager->flush();

        return $this->redirect($this->generateUrl('thankYou.index'));
    }
}
