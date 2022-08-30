<?php

namespace App\Controller\Front;

use App\Entity\Listing;
use App\Repository\ListingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;

class HomeController extends AbstractController
{
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
            10
        );

        return $this->render('front/home/index.html.twig', [
            'listings' => $listings,
        ]);
    }

    #[Route('/{title}', name: 'app_listing_show', methods: ['GET'])]
    public function show(
        Listing $listing,
        Request $request,
        PaginatorInterface $paginator,
        ): Response
    {
        return $this->render('front/listing/detail.html.twig', [
            'listing' => $listing,
        ]);
    }
}
