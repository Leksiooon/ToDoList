<?php

namespace App\Controller;

use App\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractController
{
    /**
     * @Route("/status/delete/{id}", name="deleteStatus")
     */
    public function deleteStatus(int $id)
    {
       $em = $this->getDoctrine()->getManager();

       $status = $em->getRepository(Status::class)->find($id);
       $toDoList = $status->getToDoLists();

       $statuses = $em->getRepository(Status::class)->findBy(
           ['toDoLists' => $toDoList],
           ['position' => 'ASC']
       );

       $start = $status->getPosition() + 1;
       for ($x = $start; $x < count($statuses); $x++)
       {
           $em->persist($statuses[$x]->setPosition($statuses[$x]->getPosition()-1));
       }

       $em->remove($status);
       $em->flush();

       return $this->redirectToRoute('toDoList', ['id' => $toDoList->getId()]);
    }
}
