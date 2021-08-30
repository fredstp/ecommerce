<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Bezhanov\Faker\Provider\Placeholder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category")
     */
    public function category($slug, CategoryRepository $categoryRepository)
    {

        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            throw $this->createNotFoundException("La catégorie n'existe pas");
        }

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     */
    public function show($slug, ProductRepository $productRepository)
    {

        $product = $productRepository->findOneBy([
            'slug' => $slug

        ]);

        if (!$product) {
            throw $this->createNotFoundException("Le produit n'existe pas");
        };

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(FormFactoryInterface $factory, Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {

        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $product = $form->getData();
            $product->setSlug(strtolower($slugger->slug($product->getName())));

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]);
        };

        $formview = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formview
        ]);
    }

    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     */
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator)
    {
        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $form->getName();

            dd($form);
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]);
        };


        $formView = $form->createView();

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView,
        ]);
    }
}
