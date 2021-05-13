<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ManyToOne(targetEntity="Todo", inversedBy="tasks")
     * @JoinColumn(name="todo_id", referencedColumnName="id", nullable=false)
     * @Serializer\Exclude
     */
    private ?Todo $todo;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $is_done;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsDone(): ?bool
    {
        return $this->is_done;
    }

    /**
     * @param bool $isDone
     *
     * @return $this
     */
    public function setIsDone(bool $isDone): self
    {
        $this->is_done = $isDone;

        return $this;
    }

    /**
     * @return Todo|null
     */
    public function getTodo(): ?Todo
    {
        return $this->todo;
    }

    /**
     * @param Todo $todo
     *
     * @return $this
     */
    public function setTodo(Todo $todo): self
    {
        $this->todo = $todo;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'is_done' => $this->getIsDone(),
        ];
    }
}
