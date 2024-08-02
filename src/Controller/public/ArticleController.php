<?php

namespace App\Controller\public;

use App\Repository\ArticleRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController{
    #[Route('/articles', name: 'articles')]
    public function listArticle(ArticleRepository $articleRepository){
        // je stock dans $article tous les articles trouvé.
        $articles = $articleRepository->findAll();
        // je renvoie vers la page d'affichage html.
        return $this->render('public/articles.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/article/{id}', name: 'article')]
    public function showArticle(int $id, ArticleRepository $articleRepository){
        $article = $articleRepository->find($id);
        // si l'article n'est pas trouvé ou non publié
        if(!$article || !$article->isPublished()){
            $html = $this->renderView('public/404.html.twig');
            //renvoyé vers la page 404
            return new Response($html,404);
        }
        //je renvoie vers la page d'affichage de l'article.
        return $this->render('public/show-article.html.twig', [
            'article' => $article
        ]);
    }








}