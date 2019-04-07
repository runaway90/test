<?php

namespace App\Controller\Client\Allegro\Authorization;

use App\Controller\Client\Allegro\MainAllegroController;
use App\Controller\Client\Interfaces\AuthorizationInterface;
use App\Entity\AllegroTokens;
use App\Entity\AllegroActivateDevice;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceApplication;
use App\Services\Allegro\AuthorizationProcess;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthorizationController
 * @package App\Controller\Client\Allegro\Authorization
 */
class AuthorizationController extends MainAllegroController implements AuthorizationInterface
{
    /**
     * @param Request $request
     * @param AuthorizationProcess $authorization
     * @return Response
     */
    public function authorization(Request $request, AuthorizationProcess $authorization)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }

        if ($user instanceof AllegroUserAccounts) {
            $device = $user->getActiveAllegroDevice();
            if (!$device) {
                $contents = $authorization->getNewAllegroDeviceCode();
                $authorization->createNewDeviceCode($contents, $user);
            }

            /** @var AllegroActivateDevice $device */
            $device = $user->getActiveAllegroDevice();

            if ($request->isMethod('GET')) {
                switch ($device) {
                    case($device->getStatus() == 'used'):
                        return $this->apiJsonResponse([
                            'message' => 'You have already Allegro access token, please send POST on same route for see it',
                            'link' => $device->getVarificationUriComplited(),
                            'errors' => null],
                            303);
                    case($device->getStatus() == 'active'):
                        return $this->apiJsonResponse([
                            'message' => 'You need activate device code by link -> ' . $device->getVarificationUriComplited(),
                            'link' => $device->getVarificationUriComplited(),
                            'errors' => null],
                            201);
                }
            }

            /** @var AllegroTokens $token */
            $token = $user->getAccessAllegroToken();
            if (!$token) {
                $contentForToken = $authorization->getAllegroTokenForDevice($device->getDeviceCode());
                $token = $authorization->createNewToken($contentForToken, $user);
            }

            $authorization->setUsedDeviceStatus($user);
            return $this->apiJsonResponse([
                'message' => 'Congratulate! You give account permissions to MS',
//            'token' => $token->getAccessToken(),
                'errors' => null], 200);
        } else {
            return $user;
        }

    }


    public function registerAccount(Request $request, AuthorizationProcess $authorization)
    {
//        return $this->apiJsonResponse($request->headers->all());
        /** @var MicroserviceApplication $application */
        $application = $this->getEntityManager()->getRepository(MicroserviceApplication::class)->findOneBy(['appSecret' => $request->getPassword()]);
        $response = $authorization->createNewAccountAllegro($request, $application);

        if ($response === true) {
            return $this->apiJsonResponse([
                'message' => 'Congratulate! You register Allegro account named ' . $request->headers->get('name'),
                'errors' => null], 201);
        } else {
            return $this->apiJsonResponse([
                'message' => 'Error',
                'errors' => $response], 418);
        }

    }

}
