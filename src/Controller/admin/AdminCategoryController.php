<?php
namespace App\Controller\admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class AdminCategoryController extends AbstractController{

    #[Route('/admin/category', name: 'admin_category')]
    public function listCategories(CategoryRepository $categoryRepository){

        $category = $categoryRepository->findAll();

        return $this->render('admin/list-category.html.twig', [
            'categories' => $category
        ]);
    }

    #[Route('/admin/category/insert', name: 'admin_category_insert')]
    public function insertCategory(EntityManagerInterface $entityManager,Request $request)
    {
        $category = new Category();

        $categoryForm = $this->createForm( CategoryType::class, $category);
        $categoryForm->handleRequest($request);
        if($categoryForm->isSubmitted() && $categoryForm->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'la categorie à bien été ajouté');
            return $this->redirectToRoute('admin_category');
        }

        $categoryCreateformView = $categoryForm->createView();
        return $this->render('admin/insert-category.html.twig', [
            'categoryForm' => $categoryCreateformView
        ]);
    }

    #[Route('/admin/category/delete/{id}', name: 'admin_category_delete')]
    public function deleteCategory(int $id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {

        $category = $categoryRepository->find($id);

        if (!$category) {
            $html = $this->renderView('admin/404.html.twig');
            return new Response($html, 404);
        }
        try {
            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash('success', 'la catégorie à bien été supprimé');
        } catch (\Exception $exception) {
            return $this->render('admin/500.html.twig', [
                'error' => $exception->getMessage()
            ]);
        }
        return $this->redirectToRoute('admin_category');
    }

    #[Route('/admin/category/update/{id}', name: 'admin_category_update')]
    public function updateCategory(int $id, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository){
        $category = $categoryRepository->find($id);

        $categoryCreateForm = $this->createForm( CategoryType::class, $category);
        $categoryCreateForm->handleRequest($request);


        if($categoryCreateForm->isSubmitted() && $categoryCreateForm->isValid()){
            $category->setUpdatedAt(new \DateTime('NOW'));
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Categorie enregistré');

        }
        $categoryCreateformView = $categoryCreateForm->createView();

        return $this->render('admin/update-category.html.twig', [
            'categoryForm' => $categoryCreateformView,
        ]);
    }
}