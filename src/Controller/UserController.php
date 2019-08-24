<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        $user = $this->getUser();
        return $this->render('user/dashboard.html.twig',[
            'user' => $user,
        ]);
    }

    /**
     * @Route("/myLists", name="myLists")
     */
    public function myLists()
    {
        $user = $this->getUser();

        //todo Encja: ToDoList, Tasks, Category, Status
        return $this->render('mylists.html.twig',[
            'user' => $user,

        ]);
    }
}
