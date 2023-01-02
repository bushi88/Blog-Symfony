<?php

namespace App\Controller;

use App\Services\CategoriesServices;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    public function __construct(CategoriesServices $categoriesServices)
    {
        $categoriesServices->updateSession();
    }

    #[Route('/', name: 'app_index')]
    // modification de la ligne ci-dessous suite création $session section 11 cours 3
    // public function hello(Request $request ,ArticleRepository $repoArticle, CategoryRepository $repoCat): Response
    public function hello(Request $request, ArticleRepository $repoArticle): Response
    {
        $articles = $repoArticle->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles,
            // plus besoin de la ligne ci-dessous suite création $session
            // 'categories' => $categories,
        ]);
    }

    #[Route('/article/{slug}', name: 'app_single_article')]
    // modification de la ligne ci-dessous suite création $session
    // public function single(ArticleRepository $repoArticle, CategoryRepository $repoCat, string $slug): Response
    public function single(ArticleRepository $repoArticle, string $slug): Response
    {
        $article = $repoArticle->findOneBySlug($slug);
        // plus besoin de la ligne ci-dessous suite création $session
        // $categories = $repoCat->findAll();

        return $this->render('blog/single.html.twig', [
            'controller_name' => 'BlogController',
            'article' => $article,
            // plus besoin de la ligne ci-dessous suite création $session
            // 'categories' => $categories,
        ]);
    }

    #[Route('/category/{slug}', name: 'app_article_by_category')]
    public function article_by_category(ArticleRepository $repoArticle,  CategoryRepository $repoCat, string $slug): Response
    {
        $category = $repoCat->findOneBySlug($slug);  // on recherche la seule catégorie qui a ce slug       

        $articles = [];

        if ($category) {
            // getArticles pour récupérer les "collections" => voir fichier \vendor\doctrine\collections\src\Collection.php 
            // getValues pour récupérer les valeurs du tableau
            $articles = $category->getArticles()->getValues();
        }
        // plus besoin de la ligne ci-dessous suite création $session
        // $categories = $repoCat->findAll();

        return $this->render('blog/articles_by_category.html.twig', [
            'controller_name' => 'BlogController',
            'category' => $category,
            'articles' => $articles,
            // plus besoin de la ligne ci-dessous suite création $session
            // 'categories' => $categories,
        ]);
    }
}
