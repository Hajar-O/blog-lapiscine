<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController{
    #[Route('/articles', name: 'articles')]
    public function listArticle(ArticleRepository $articleRepository){
        $articles = $articleRepository->findAll();

        return $this->render('public/articles.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/article/{id}', name: 'article')]
    public function showArticle(int $id, ArticleRepository $articleRepository){
        $article = $articleRepository->find($id);

        if(!$article || !$article->isPublished()){
            $html = $this->renderView('public/404.html.twig');
            return new Response($html,404);
        }
        return $this->render('public/show-article.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/categories', name: 'categories')]
    public function showCategories(CategoryRepository $categoryRepository) :Response{
        $category = $categoryRepository->findAll();

        return $this->render('public/categories.html.twig', [
            'categories' => $category
        ]);
    }

    #[Route('article-category/{id}', name: 'article-category')]
    public function showArticleCategory(int $id, CategoryRepository $categoryRepository): Response{
        $category = $categoryRepository->find($id);


        if(!$category){
            $html = $this->renderView('public/404.html.twig');
            return new Response($html,404);
        }
        $title = $category->getTitle();
        return $this->render('public/article-from-category.html.twig', [
            'category' => $category,
            'title' => $title
        ]);
    }






}