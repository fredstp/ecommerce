<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CategoryController extends AbstractController
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function renderMenuList()
    {

        $categories = $this->categoryRepository->findAll();

        return $this->render('category/_menu.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/category/create", name="category_create")
     */
    public function create(FormFactoryInterface $form, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $category = new Category;

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setSlug(strtolower($slugger->slug($category->getName())));

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('homepage');
        };

        $formView = $form->createView();

        return $this->render('category/create.html.twig', [
            'formView' => $formView,
        ]);
    }

    /**
     * @Route("/admin/category/{id}/edit", name="category_edit")
     * 
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas le droit d'accéder à cette ressource")
     */
    public function edit($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        // $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Vous n'avez pas le droit d'accéder à cette ressource");

        $category = $categoryRepository->find($id);

        if (!$category) {
            throw new NotFoundHttpException("Cette catégorie n'existe pas");
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();


        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $formView,
        ]);
    }
}
