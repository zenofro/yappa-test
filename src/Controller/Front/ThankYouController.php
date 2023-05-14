<?php

namespace App\Controller\Front;

use App\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThankYouController extends AbstractController
{
    #[Route('bedankt', name: 'thankYou.index', methods: ['GET'])]
    public function index(Request $request, MemberRepository $memberRepository): Response
    {
        $member = $memberRepository->find($request->getSession()->get('member', 0));

        if (! $member){
            $this->addFlash('error', 'Je moet je eerst inloggen!');
            return $this->redirect($this->generateUrl('member.index'));
        }

        if (! $member->getArticle()){
            return $this->redirect($this->generateUrl('member.index'));
        }

        $request->getSession()->remove('member');

        return $this->render('front/thankYou.html.twig', [
            'member' => $member,
        ]);
    }
}
