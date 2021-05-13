<?php

namespace App\Controller;

use App\Entity\{Task, Todo, User};
use App\Form\TaskFormType;
use App\Repository\{TaskRepository, TodoRepository};
use Doctrine\ORM\{NonUniqueResultException, OptimisticLockException, ORMException};
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\{Request, Response};

/**
 * Class TaskController
 *
 * @package App\Controller
 *
 * @Rest\Route(path="/todos/{listId<\d+>}/tasks")
 */
class TaskController extends BaseApiController
{
    /**
     * @Rest\Get(path="", name="get_all_list_tasks")
     *
     * @param int $listId
     * @param TaskRepository $repository
     *
     * @return Response
     */
    public function list(int $listId, TaskRepository $repository): Response
    {
        $todos = $repository->findUserTasksByTodoId($this->getUser()->getId(), $listId);
        $view = $this->view($todos, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post(path="", name="create_task")
     *
     * @param int $listId
     * @param Request $request
     * @param TodoRepository $todoRepository
     *
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(int $listId, Request $request, TodoRepository $todoRepository): Response
    {
        $task = new Task();

        /** @var User $userId */
        $user = $this->getUser();
        $data = $this->getRequestData($request);
        /** @var Todo $list */
        $list = $todoRepository->findOneBy(['id' => $listId, 'user' => $user]);
        if (!$list) {
            return $this->createResponse(['user_id' => $user->getId(), 'id' => $listId], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(TaskFormType::class, $task);
        $errorsView = $this->validate($form, $data);
        if ($errorsView) {
            return $this->handleView($errorsView);
        }
        $task->setTodo($list);
        $todoRepository->saveEntity($task);

        return $this->createResponse($task->toArray());
    }

    /**
     * @Rest\Delete(path="/{taskId<\d+>}", name="delete_task")
     *
     * @param int $listId
     * @param int $taskId
     * @param TaskRepository $repository
     *
     * @return Response
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(int $listId, int $taskId, TaskRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Task $task */
        $task = $repository->findUserTaskByTodoIdAndTaskId($user->getId(), $listId, $taskId);

        if (!$task) {
            return $this->createResponse(
                [
                    'id' => $listId,
                    'user_id' => $user->getId(),
                    'taskId' => $taskId
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $repository->deleteEntity($task);

        return $this->createResponse($task->toArray(), Response::HTTP_OK);
    }

    /**
     * @Rest\Put(path="/{taskId<\d+>}/done", name="done_task")
     *
     * @param int $listId
     * @param int $taskId
     * @param TaskRepository $repository
     *
     * @return Response
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function done(int $listId, int $taskId, TaskRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Task $task */
        $task = $repository->findUserTaskByTodoIdAndTaskId($user->getId(), $listId, $taskId);

        if (!$task) {
            return $this->createResponse(
                [
                    'id' => $listId,
                    'user_id' => $user->getId(),
                    'taskId' => $taskId
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        $task->setIsDone(true);
        $repository->saveEntity($task);

        return $this->createResponse($task->toArray());
    }
}
