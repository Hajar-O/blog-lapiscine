<?php
namespace App\Controller\admin;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminArticleController extends AbstractController
{
    #[Route('/admin/article', name: 'admin_article')]
    public function listArticles(ArticleRepository $articleRepository){
        $articles = $articleRepository->findAll();

        return $this->render('admin/list-articles.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/admin/delete/article/{id}', name: 'admin_delete_article')]
    public function deleteArticle(int $id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager){

        $article = $articleRepository->find($id);
        if(!$article){
            $html404= $this->renderView('admin/404.html.twig');
            return new Response($html404, 404);
        }
        try{
            $entityManager->remove($article);
            $entityManager->flush();

            //permet d'enregister un message das la sessionn de PHP
            //ce message sea afficher grâce à twig.
            $this->addFlash('success', 'l article à bien été supprimé');

        } catch(\Exception $exception){
            return $this->render('admin/500.html.twig', [
                'error' => $exception->getMessage()
            ]);
        }
        return $this->redirectToRoute('admin_article');
    }
    
}