<?php

namespace App\Controller\Client\Microservice;

use App\Controller\Client\Traits\APIResponseTrait;
use App\Entity\AllegroUserAccounts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MainMSController
 * @package App\Controller\Client\Microservice
 */
class MainMSController extends AbstractController
{
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
