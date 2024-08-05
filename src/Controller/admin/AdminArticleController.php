<?php
namespace App\Controller\admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/admin/insert/article', name: 'admin_insert_article')]
    public function insertArticle(EntityManagerInterface $entityManager, Request $request){
        // on a créé une classe de "gabarit de formulaire HTML" avec php bin/console make:form

        // je créé une instance de la classe d'entité article
        $article = new Article();


        // permet de générer une instance de la classe de gabarit de formulaire
        // et de la lier avec l'instance de l'entité
        $articleForm = $this->createForm(ArticleType::class, $article);

            $articleForm->handleRequest($request);

                if($articleForm->isSubmitted() && $articleForm->isValid()){
                    $entityManager->persist($article);
                    $entityManager->flush();

                    $this->addFlash('success', 'l article à bien été ajouté');

                    $this->redirectToRoute('admin_article');
                }
        $articleCreateFormView = $articleForm->createView();
                return $this->render('admin/insert-article.html.twig', [
                    'articleForm' => $articleCreateFormView
                ]);
    }

    #[Route('/admin/article/update/{id}', 'admin_update_article')]
    public function updateArticle(int $id, Request $request, EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        $articleCreateForm = $this->createForm(ArticleType::class, $article);

        $articleCreateForm->handleRequest($request);

        if ($articleCreateForm->isSubmitted() && $articleCreateForm->isValid()) {
            $article->setUpdatedAt(new \DateTime('NOW'));
            $entityManager->persist($article);
            $entityManager->flush();


            $this->addFlash('success', 'article enregistré');
            return $this->redirectToRoute('admin_article');
        }

        $articleCreateFormView = $articleCreateForm->createView();

        return $this->render('admin/update_article.html.twig', [
            'articleForm' => $articleCreateFormView
        ]);
    }
}