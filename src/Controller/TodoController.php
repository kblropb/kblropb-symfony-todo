<?php

namespace App\Controller;

use App\Entity\{Todo, User};
use App\Form\TodoFormType;
use App\Repository\TodoRepository;
use Doctrine\ORM\{OptimisticLockException, ORMException};
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\{Request, Response};

/**
 * Class TodoController
 *
 * @package App\Controller
 *
 * @Rest\Route(path="todos")
 */
class TodoController extends BaseApiController
{
    /**
     * @Rest\Get(path="", name="get_all_todos")
     */
    public function list(TodoRepository $repository): Response
    {
        $todos = $repository->findBy(['user' => $this->getUser()]);
        $view = $this->view($todos, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post(path="", name="create_todo")
     *
     * @param Request $request
     * @param TodoRepository $repository
     *
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Request $request, TodoRepository $repository): Response
    {
        $todo = new Todo();

        $data = $this->getRequestData($request);

        $form = $this->createForm(TodoFormType::class, $todo);
        $errorsView = $this->validate($form, $data);
        if ($errorsView) {
            return $this->handleView($errorsView);
        }
        $todo->setUser($this->getUser());
        $repository->saveEntity($todo);

        return $this->createResponse($todo->toArray());
    }

    /**
     * @Rest\Delete(path="/{listId<\d+>}", name="delete_todo")
     *
     * @param int $listId
     * @param TodoRepository $repository
     *
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(int $listId, TodoRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Todo $todo */
        $todo = $repository->findOneBy(['user' => $user, 'id' => $listId]);
        if (!$todo) {
            return $this->createResponse(['id' => $listId, 'user_id' => $user->getId()], Response::HTTP_NOT_FOUND);
        }
        $repository->deleteEntity($todo);

        return $this->createResponse((array)$todo, Response::HTTP_OK);
    }
}
