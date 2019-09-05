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
                foreach ($statuses as $key=>$value)
                {
                    $status = new Status();
                    $status->create($value,$todolist,$key);

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
}
