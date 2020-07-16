<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\ConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="admin_user_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('backend/user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'page' => "utilisateur",
            'activePage' => "utilisateur_liste"
        ]);
    }

    /**
     * @Route("/new", name="admin_user_new", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder,
        ConfigurationRepository $configurationRepos
    ): Response
    {
        $user = new User();
        $configuration = $configurationRepos->find(1);
        $user->setConfiguration($configuration);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'succes',
                'Votre utilisateur a été créé avec succès!'
            );

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('backend/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'page' => "utilisateur",
            'activePage' => "utilisateur_nouveau"
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user): Response
    {
        $lastPassword = $user->getPassword();

        // if ($request->query->get('password') === NULL) {
        //     $user->setPassword('otogue86');
        // }
        $form = $this->createForm(UserType::class, $user)
            ->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'succes',
                'Vos modifications ont été enregistrées!'
            );

            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }

        return $this->render('backend/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'page' => "utilisateur",
            'activePage' => "utilisateur_modifier"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash(
                'suppression',
                'Votre utilisateur a ete supprime!'
            );
        }

        return $this->redirectToRoute('admin_user_index');
    }

    /**
     * @Route("/{id}/reset-password", name="admin_user_reset_password", methods="GET|POST")
     */
    public function resetPassword(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user)
            ->remove('nom')
            ->remove('prenom')
            ->remove('email')
            ->remove('username')
            ->remove('isActive')
            ->remove('roles')
            ->remove('imageFile')
            ->remove('fonction')
            ->remove('facebook')
            ->remove('googleplus')
            ->remove('twitter')
            ->remove('linkedin');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'succes',
                'Votre mot de passe a ete reinitialise!'
            );

            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }

        return $this->render('backend/user/reset_password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'page' => "utilisateur",
            'activePage' => "utilisateur_modifier"
        ]);
    }


}
