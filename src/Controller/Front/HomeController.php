<?php

namespace App\Controller\Front;

use App\Entity\Listing;
use App\Form\ListingType;
use App\Repository\ListingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;

class HomeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) { }

    #[Route('/', name: 'app_home')]
    public function index(
        ListingRepository $ListingRepository, 
        PaginatorInterface $paginator, 
        Request $request,
    ): Response
    {
        $qb = $ListingRepository->getQbAll();


        $listings = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('front/home/index.html.twig', [
            'listings' => $listings,
        ]);
    }

    #[Route('/new', name: 'app_listing_new')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(ListingType::class, new Listing());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->em->persist($data);
            $this->em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('front/listing/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/{title}', name: 'app_listing_show', methods: ['GET'])]
    public function show(
        Listing $listing,
        ): Response
    {
        return $this->render('front/listing/detail.html.twig', [
            'listing' => $listing,
        ]);
    }



}
