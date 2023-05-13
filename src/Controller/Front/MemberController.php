<?php

namespace App\Controller\Front;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    #[Route('/', name: 'member.index', methods: ['GET'])]
    public function index(): Response
    {
        $form = $this->createForm(MemberType::class);

        return $this->render('front/login.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/', name: 'member.login', methods: ['POST'])]
    public function login(Request $request, MemberRepository $memberRepository): Response
    {

        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if (! $form->isSubmitted() && ! $form->isValid()) {
            return $this->render('front/login.html.twig', [
                'form' => $form
            ]);
        }

        $day = $form->get('day')->getData();
        $month = $form->get('month')->getData();
        $year = $form->get('year')->getData();

        $birthDate = DateTimeImmutable::createFromFormat('!d-m-Y', "{$day}-{$month}-{$year}");

        $existingMember = $memberRepository->findOneBy([
            'birthDate' => $birthDate,
            'membershipNumber' => $form->get('membershipNumber')->getData()
        ]);

        if (! $existingMember) {
            $this->addFlash('error', 'Geen lid gevonden!');
            return $this->redirect($this->generateUrl('member.index'));
        }

        return $this->redirect($this->generateUrl('member.index'));
    }
}