<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\ToDoList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
