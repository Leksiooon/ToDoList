<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/task/delete/{id}", name="deleteTask")
     */
    public function index(int $id)
    {
        $em = $this->getDoctrine()->getManager();

        $tmpObj = $em->getRepository(Task::class)->find($id);
        $tmpToDoList = $tmpObj->getToDoList();

        $em->remove($tmpObj);
        $em->flush();

        return $this->redirectToRoute('toDoList', ['id' => $tmpToDoList->getId()]);
    }

   /**
    * @Route("/task/move/{id}/{move}", name="move")
    */
   public function move(int $id, string $move)
   {
       $em = $this->getDoctrine()->getManager();

       $task = $em->getRepository(Task::class)->find($id);
       $toDoList = $task->getToDoList();
       $status = $task->getStatus();

       $newPosition = 0;

       if($move == 'right')
           $newPosition = 1;
       if($move == 'left')
           $newPosition = -1;

       $newStatus = $em->getRepository(Status::class)->findOneBy([
           'toDoLists' => $toDoList,
           'position' => $status->getPosition() + $newPosition
       ]);

       $task->setStatus($newStatus);

       $em->persist($task);
       $em->flush();

       return $this->redirectToRoute('toDoList', ['id' => $toDoList->getId()]);
   }
}
