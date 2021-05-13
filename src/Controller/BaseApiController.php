<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\{Request, Response};

class BaseApiController extends AbstractFOSRestController
{
    /**
     * @param Request $request
     *
     * @return array
     */
    protected function getRequestData(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }

    /**
     * @param FormInterface $form
     * @param array $data
     *
     * @return View|null
     */
    protected function validate(FormInterface $form, array $data): ?View
    {
        $form->submit($data);
        if (!$form->isSubmitted() | !$form->isValid()) {
            return $this->view($form->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return null;
    }

    /**
     * @param array $data
     * @param int $code
     *
     * @return Response
     */
    protected function createResponse(array $data, int $code = Response::HTTP_CREATED): Response
    {
        $view = $this->view($data, $code);

        return $this->handleView($view);
    }
}
