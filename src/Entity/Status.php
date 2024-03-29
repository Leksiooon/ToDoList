<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatusRepository")
 */
class Status
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ToDoList", inversedBy="statuses")
     */
    private $toDoLists;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="status")
     */
    private $tasks;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getToDoLists(): ?ToDoList
    {
        return $this->ToDoLists;
    }

    public function setToDoLists(?ToDoList $toDoLists): self
    {
        $this->toDoLists = $toDoLists;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setStatus($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getStatus() === $this) {
                $task->setStatus(null);
            }
        }

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function create(string $name, ToDoList $toDoList, $position = "null")
    {
        $this->setName($name);
        $this->setToDoLists($toDoList);
        if($position == "null")
            $this->setPosition($toDoList->getStatuses()->count());
        else
            $this->setPosition($position);
    }
}
