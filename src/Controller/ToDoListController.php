<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Task;
use App\Entity\ToDoList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/toDoList/{id}", name="toDoList")
     */
    public function toDoList(int $id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $toDoList = $em->getRepository(ToDoList::class)->find($id);

        $statuses = $em->getRepository(Status::class)->findBy(
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

            $em->persist($status);
            $em->flush($status);

            return $this->redirectToRoute('toDoList', ['id' => $id ]);
        }

        //form of creating Task
        $formNewTask = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('idStatus',HiddenType::class)
            ->add('save',SubmitType::class)
            ->getForm();

        $formNewTask->handleRequest($request);

        if($formNewTask->isSubmitted() && $formNewTask->isValid())
        {
            $task = new Task();

            $task->setName($formNewTask['name']->getData());
            $task->setDescription($formNewTask['description']->getData());

            $tmpStatus = $em->getRepository(Status::class)->find($formNewTask['idStatus']->getData());
            $task->setStatus($tmpStatus);
            $task->setToDoList($toDoList);

            $em->persist($task);
            $em->flush();
        }

        return $this->render('user/toDoList.html.twig',[
            'user' => $this->getUser(),
            'toDoList' => $toDoList,
            'statuses' => $statuses,
            'formNewStatus' => $formNewStatus->createView(),
            'formNewTask' => $formNewTask->createView()
        ]);
    }

    /**
     * @Route("toDoList/{id}/delete", name="deleteToDoList")
     */
    public function deleteToDoList(int $id)
    {
        $em = $this->getDoctrine()->getManager();

        $toDoList = $em->getRepository(ToDoList::class)->find($id);

        $em->remove($toDoList);
        $em->flush();

        return $this->redirectToRoute('dashboard');
    }
}
