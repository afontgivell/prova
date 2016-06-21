<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

// comment...

class UserController extends Controller
{

    /**
     * @Route("/", name="app_user_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository('AppBundle:User');
        $userList = $userRepository->findAll();
        return $this->render(':user:index.html.twig', [
            'userList' => $userList,
        ]);
    }

    /**
     * @Route("/new", name="app_show_new")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showNewUserAction()
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $action = 'app_add_new';
        $submitButton = 'add user';

        return $this->render(':user:detail.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'action' => $action,
            'submitButton' => $submitButton,
        ]);
    }

    /**
     * @Route("/add", name="app_add_new")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addUserAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash("messages", "user added");
            $this->addFlash("messages", "(".$user->getUsername().")");

            return $this->redirect($this->generateUrl('app_user_index'));
        }else{
            $this->addFlash("messages", "some errors where found.");

            return $this->render(':user:detail.html.twig',
                [
                    'user' => $user,
                    'form' => $form->createView(),
                    'action' => 'app_add_new',
                    'submitButton' => 'add user',
                ]
            );
        }
    }

    /**
     * @Route("/showupdate/{id}", name="app_show_update")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showUpdateUserAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository('AppBundle:User');
        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);

        $action = 'app_do_update';
        $submitButton = 'update user';

        return $this->render(':user:detail.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'action' => $action,
            'submitButton' => $submitButton,
        ]);
    }

    /**
     * @Route("/doupdate", name="app_do_update")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doUpdateUserAction(Request $request)
    {
        $id = $request->request->get('id');

        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository('AppBundle:User');
        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isValid()){
            $entityManager->flush();
            $this->addFlash("messages", "user updated");
            $this->addFlash("messages", "(".$user->getUsername().")");
            return $this->redirect($this->generateUrl('app_user_index'));
        }else{
            $this->addFlash("some errors where found.");

            return $this->render(':user:detail.html.twig',
                [
                    'user' => $user,
                    'form' => $form->createView(),
                    'action' => 'app_do_update',
                    'submitButton' => 'update user',
                ]
            );
        }
    }

    /**
     * @Route("/delete/{id}", name="app_delete_user")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteUserAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository('AppBundle:User');
        $user = $userRepository->find($id);

        $username = $user->getUsername();

        $entityManager->remove($user);
        $entityManager->flush();
        
        $this->addFlash("messages", "user deleted");
        $this->addFlash("messages", "(".$user->getUsername().")");

        return $this->redirectToRoute('app_user_index');
    }
}
