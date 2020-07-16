<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Functions;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\Email;

class SecurityController extends Controller {

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils) {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        // //
        $form = $this->get('form.factory')
                ->createNamedBuilder(null)
                ->add('_username', null, [
                    'label' => 'Nom d\'utilisateur',
                ])
                ->add('_password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class, ['label' => 'Mot de passe'])
                ->add('_remember_me', \Symfony\Component\Form\Extension\Core\Type\CheckboxType::class, [
                    'label' => 'Se souvenir de moi',
                    'required' => FALSE
                ])
                // ->add('ok', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, ['label' => 'Ok', 'attr' => ['class' => 'btn-primary btn-block']])
                ->getForm();
        return $this->render('security/login.html.twig', [
                    'mainNavLogin' => true, 'title' => 'Connexion',
                    //
                    'form' => $form->createView(),
                    'last_username' => $lastUsername,
                    'error' => $error,
        ]);
        // return $this->render('security/test.html.twig');
    }

    /**
     * @Route("/reset-password", name="reset_password")
     */
    public function resetPassword(Request $request, UserRepository $userRepository, Functions $functions, UserPasswordEncoderInterface $passwordEncoder/*,\Swift_Mailer $mailer*/, Email $mail) {
        
        $error = FALSE;
        $form = $this->get('form.factory')
                ->createNamedBuilder(null)
                ->add('_email', null, [
                    'label' => 'Email',
                ])
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $email = $request->request->get('_email');
            //On verifie si un utilisateur ayant cet email existe
            $user = $userRepository->findOneByEmail($email);
            if($user){
                $password = $functions->generatePassword();
                //On met a jour le mot de passe de l'utilisateur dans la base de donnees
                $encodedPassword = $passwordEncoder->encodePassword($user, $password);
                $user->setPassword($encodedPassword);
                $this->getDoctrine()->getManager()->flush();

                $mail->send(
                    'Reinitailisation du mot de passe',
                    $user->getEmail(),
                    $this->renderView(
                        // templates/emails/registration.html.twig
                        'emails/reset_password.html.twig',
                        array(
                            'password' => $password,
                            'user' => $user
                        )
                    )
                );

                return $this->redirectToRoute('success_reset');
            }else{
                $error = TRUE;
            }
        }

        return $this->render('security/reset_password.html.twig', [
                    //
                    'form' => $form->createView(),
                    'error' => $error
        ]);
        // return $this->render('security/test.html.twig');
    }

    /**
     * @Route("/success-reset", name="success_reset")
     */
    public function successReset(Request $request) {

        return $this->render('security/success_reset_password.html.twig', [
                    
        ]);
    }

}