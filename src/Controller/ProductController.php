<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractController
{
    /**
     * On veut d'abord afficher une catégorie
     * @Route("/{slug}", name="product_category")
     */
    public function category(Request $req, $slug, CategoryRepository $repo): Response
    {
        $category = $repo->findOneBy([
            'slug' => $slug
        ]);

        // dd($category);

        if (!$category) {
            // throw new NotFoundHttpException("La catégorie que vous recherchez n'existe pas");
            // on peut utiliser un "racourci" fourni par la classe AbstractController 
            throw $this->createNotFoundException('not_exist');
        }

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     */

    public function show($slug, ProductRepository $repo)
    {
        $product = $repo->findOneBy([
            'slug' => $slug
        ]);

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }


    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute("product_show", [
                "category_slug" => $product->getCategory()->getSlug(),
                "slug" => $product->getSlug()
            ]);
        }

        $formView = $form->createView();
        return $this->render('product/create.html.twig', [
            'product' => $product,
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     */

    public function edit($id, Request $request, ProductRepository $repo, EntityManagerInterface $em)
    {
        $product = $repo->findOneBy(['id' => $id]);
        $form = $this->createForm(ProductType::class, $product);
        // Attention pour rappel (au lieu de passer $product ci-dessus)
        // $form->setData($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $em->persist($product);
            $em->flush();

            return $this->redirectToRoute("product_show", [
                "category_slug" => $product->getCategory()->getSlug(),
                "slug" => $product->getSlug()
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $form->createView()
        ]);
    }
}
