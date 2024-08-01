<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('public/show-article.html.twig', [
            'article' => $article
        ]);
    }

    #
}