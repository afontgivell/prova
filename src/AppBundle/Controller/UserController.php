<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
        $action = 'app_add_new';
        $submitButton = 'add user';
        return $this->render(':user:detail.html.twig', [
            'user' => $user,
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
        $username = $request->request->get('username');
        $email = $request->request->get('email');

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash("messages", "user added");
        $this->addFlash("messages", "(".$user->getUsername().")");

        return $this->redirect($this->generateUrl('app_user_index'));
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

        $action = 'app_do_update';
        $submitButton = 'update user';

        return $this->render(':user:detail.html.twig', [
            'user' => $user,
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
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository('AppBundle:User');
        $user = $userRepository->find($id);
        
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setUpdatedAt(new \DateTime());

        $entityManager->flush();

        $this->addFlash("messages", "user updated");
        $this->addFlash("messages", "(".$user->getUsername().")");

        return $this->redirectToRoute('app_user_index');
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
