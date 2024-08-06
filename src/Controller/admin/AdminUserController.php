<?php

namespace App\Controller\admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_insert_user')]
    public function insertAdmin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        if ($request->getMethod() === 'POST') {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $user = new User();

            try {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $password
                );

                $user->setEmail($email);
                $user->setPassword($hashedPassword);
                $user->setRoles(['ROLE_ADMIN']);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'utilisateur créé');
            } catch (\Exception $exception) {
                // attention, éviter de renvoyer le message directement
                // récupéré depuis les erreurs SQL
                $this->addFlash('error', $exception->getMessage());
            }

        }
        return $this->render('admin/insert-user.html.twig');
    }
}
