<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

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
}
