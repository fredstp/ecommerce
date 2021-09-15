<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Twig\Environment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class PurchasesListController extends AbstractController
{

    protected $security;
    protected $router;
    protected $twig;

    public function __construct(Security $security, RouterInterface $router, Environment $twig)
    {
        $this->security = $security;
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * @Route("/purchases", name="purchase_index")
     */
    public function index()
    {
        // 1. Nous devons nous assurer que la personne est connectée 
        //(sinon retour à la page d'acceuil) -> Security

        /**
         * @var User
         */
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException("Vous devez être connecté pour accéder à vos commandes !");
        }

        // 2. Nous voulons savoir QUI est connectée -> Security

        // 3. Nous voulons passer l'utilisateur connecté à twig afin d'afficher ses commandes 
        // Environment de twig / Response
        $html = $this->twig->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
        return new Response($html);
    }
}
