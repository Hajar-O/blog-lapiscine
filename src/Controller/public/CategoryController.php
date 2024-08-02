<?php

namespace App\Controller\public;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController{

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