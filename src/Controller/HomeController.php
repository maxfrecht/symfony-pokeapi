<?php

namespace App\Controller;

use App\Pokedex\TypeApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private TypeApi $typeApi;

    /**
     * HomeController constructor.
     * @param TypeApi $typeApi
     */
    public function __construct(TypeApi $typeApi)
    {
        $this->typeApi = $typeApi;
    }

    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'types' => $this->typeApi->getAllObjectTypes()
        ]);
    }
}
