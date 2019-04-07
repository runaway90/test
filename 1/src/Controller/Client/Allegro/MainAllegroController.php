<?php

namespace App\Controller\Client\Allegro;

use App\Controller\Client\Traits\APIResponseTrait;
use App\Controller\Client\Traits\DoctrineManagerTrait;
use App\Entity\AllegroUserAccounts;
use App\Services\Microservice\RegistrationManagerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Allegro\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class MainAllegroController
 * @package App\Controller\Client\Allegro
 */
class MainAllegroController extends AbstractController
{
    use DoctrineManagerTrait;
    use APIResponseTrait;

    public function getAndCheckUser(Request $request)
    {
        $userUUID = $request->headers->get('uuid');

        /** @var AllegroUserAccounts $user */
        $user = $this->getDoctrine()->getRepository(AllegroUserAccounts::class)->findOneBy(['uuid' => $userUUID]);
//        if (!$user) {
//                $this->apiJsonResponse($this->userNotFoundError(),406);
//        }

        return $user;
    }

    public function checkErrorStatus($data)
    {
        if (array_key_exists('error', $data) || array_key_exists('errors', $data) ) {
            return 400;
        }else{
            return 200;
        }
    }

    public function userNotFoundError()
    {
        return ["error" => 'User not found, please correct account UUID in You request!'];
    }

    public function notFoundRequiredParam()
    {
        return ["error" => 'This request must to have one or more required parameter/s! Check it please.'];
    }

    public function pleaseRemoveParameter()
    {
        return ["error" => 'Please delete not need parameter or chose one from many parameters'];
    }

}
