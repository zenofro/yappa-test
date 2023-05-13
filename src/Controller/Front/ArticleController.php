<?php

namespace App\Controller\Front;

use App\Entity\Member;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('{id}/producten', name: 'article.index', methods: ['GET'])]
    public function index(Member $member): Response
    {
        return $this->render('front/article.html.twig', [
            'member' => $member,
        ]);
    }
}
