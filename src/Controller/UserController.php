<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Task;
use App\Entity\ToDoList;
use App\Form\ToDoListType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $lists = $em->getRepository(ToDoList::class)->findBy(array('user' => $user));


        //form of creating ToDoList
        $form = $this->createFormBuilder()
            ->add('name')
            ->add('statuses', ChoiceType::class, [
                'choices' => [
                    'Do zrobienia' => 'Do zrobienia',
                    'W trakcie realizacji' => 'W trakcie realizacji',
                    'Zakończone' => 'Zakończone'
                ],
                'multiple' => true,
                'required' => false
            ])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $todolist = new ToDoList();
            $todolist->setName($form['name']->getData());
            $todolist->setUser($user);

            $statuses = $form['statuses']->getData();


            if($statuses != null){
                foreach ($statuses as $value)
                {
                    $status = new Status();
                    $status->setName($value);
                    $status->setToDoLists($todolist);

                    $em->persist($status);
                }
            }


            $em->persist($todolist);
            $em->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('user/dashboard.html.twig',[
            'user' => $user,
            'form' => $form->createView(),
            'lists' => $lists
        ]);
    }

    /**
     * @Route("/toDoList/{id}", name="toDoList")
     */
    public function toDoList(int $id, Request $request)
    {
        $toDoList = $this->getDoctrine()->getRepository(ToDoList::class)->find($id);

        $statuses = $this->getDoctrine()->getRepository(Status::class)->findBy(
            ['toDoLists' => $toDoList],
            ['position' => 'ASC']
        );

        //form of add new statuses
        $formNewStatus = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $formNewStatus->handleRequest($request);

        if($formNewStatus->isSubmitted() && $formNewStatus->isValid())
        {
            $status = new Status();
            $status->create($formNewStatus['name']->getData(),$toDoList);

            $em = $this->getDoctrine()->getManager();

            $em->persist($status);
            $em->flush($status);

            return $this->redirectToRoute('toDoList', ['id' => $id ]);
        }

        return $this->render('user/toDoList.html.twig',[
            'user' => $this->getUser(),
            'toDoList' => $toDoList,
            'statuses' => $statuses,
            'formNewStatus' => $formNewStatus->createView()
        ]);
    }


}
