<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TaskRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\{Request, Response};

class TaskSearchController extends BaseApiController
{
    /**
     * @Rest\Get(path="search", name="search")
     *
     * @param Request $request
     * @param TaskRepository $taskRepository
     *
     * @return Response
     */
    public function search(Request $request, TaskRepository $taskRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $tasks = $taskRepository->findUserTasksByCriteria($user->getId(), $request->get('filters'));
        $view = $this->view($tasks);

        return $this->handleView($view);
    }
}
