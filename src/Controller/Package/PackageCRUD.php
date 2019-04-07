<?php

namespace App\Controller\Package;

use App\Controller\Interfaces\Package\CRUDInterface;
use App\Controller\MainController;
use App\Services\PackageOperations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PackageCRUD extends MainController implements CRUDInterface
{
    public function create(Request $request, PackageOperations $packageOperations): Response
    {
        $user = $this->checkUserUUID($request);
        if (!$user) {
            return new Response('User not found', 401);
        }

        $requestBody = json_decode($request->getContent());
        $errorsInBody = $packageOperations->checkBodyStructureForCreatePackage($requestBody);

        if ($errorsInBody) {
            return new Response($errorsInBody, 401);
        } else {
            $packageOperations->saveNewPackage($requestBody, $user);
            return new Response('User with UUID ' . $requestBody->uuid . 'was created', 200);
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
