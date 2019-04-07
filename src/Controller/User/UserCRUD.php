<?php

namespace App\Controller\User;

use App\Controller\Interfaces\User\CRUDInterface;
use App\Controller\MainController;
use App\Entity\User;
use App\Services\UserOperations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserCRUD extends MainController implements CRUDInterface
{
    public function create(Request $request, UserOperations $userOperations): Response
    {
        /** @var User $user */
        $user = $this->checkUserUUID($request);
        if (!$user) {
            $requestBody = json_decode($request->getContent());
            $errorsInBody = $userOperations->checkBodyStructureForCreateUser($requestBody);

            if ($errorsInBody) {
                return new Response($errorsInBody, 401);
            } else {
                $userOperations->saveNewUser($requestBody, $request->headers->get('uuid'));
                return new Response('User with UUID ' . $requestBody->uuid . 'was created', 200);
            }
        }else{
            return new Response('User already registered', 401);
        }

    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

}