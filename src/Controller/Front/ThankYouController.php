<?php

namespace App\Controller\Front;

use App\Entity\Member;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThankYouController extends AbstractController
{
    #[Route('{id}/thank-you', name: 'thankYou.index', methods: ['GET'])]
    public function index(Member $member): Response
    {
        if (! $member->getArticle()){
            return $this->redirect($this->generateUrl('member.index'));
        }

        return $this->render('front/thankYou.html.twig', [
            'member' => $member,
        ]);
    }
}
