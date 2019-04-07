<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroTokens;
use App\Entity\AllegroActivateDevice;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceApplication;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Microservice\OperationLogger;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthorizationProcess
 * @package App\Services\Allegro
 */
class AuthorizationProcess extends MainAppService
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;
    /**
     * @var OperationLogger $logger
     */
    protected $logger;

    /**
     * CategoryElements constructor.
     * @param EntityManagerInterface $entityManager
     * @param OperationLogger $logger
     */
    public function __construct(EntityManagerInterface $entityManager, OperationLogger $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }


    /**
     * @return object
     */
    public function getNewAllegroDeviceCode(): object
    {
        $url = $this->getUrl();
        $client = new RequestController();
        $response = $client->createClientRequest()->post($url . '/auth/oauth/device?client_id=' . getenv('ALLEGRO_DEVICE_ID'), [
            'auth' => [
                getenv('ALLEGRO_DEVICE_ID'),
                getenv('ALLEGRO_DEVICE_SECRET')
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        ]);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    /**
     * @param string $deviceCode
     * @return object
     */
    public function getAllegroTokenForDevice(string $deviceCode)
    {
        $url = $this->getUrl();

        $client = new RequestController();
        $response = $client->createClientRequest()->post($url . '/auth/oauth/token?grant_type=urn%3Aietf%3Aparams%3Aoauth%3Agrant-type%3Adevice_code&device_code=' . $deviceCode, [
            'auth' => [
                getenv('ALLEGRO_DEVICE_ID'),
                getenv('ALLEGRO_DEVICE_SECRET')
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);

        $contents = json_decode($response->getBody()->getContents());

        return $contents;
    }

    /**
     * @param AllegroTokens $token
     * @return object
     */
    public function getAllegroRefreshToken(AllegroTokens $token)
    {
        $url = $this->getUrl();

        /** @var AllegroUserAccounts $user */
        $user = $token->getUser();
        $redirect = $user->getActiveAllegroDevice()->getVarificationUriComplited();

        $client = new RequestController();
        $response = $client->createClientRequest()->post($url . '/auth/oauth/token?grant_type=refresh_token&refresh_token=' . $token->getRefreshToken() . '&redirect_uri=' . explode("?", $redirect)[0], [ //
            'auth' => [
                getenv('ALLEGRO_DEVICE_ID'),
                getenv('ALLEGRO_DEVICE_SECRET')
            ],
        ]);

        $contents = json_decode($response->getBody()->getContents());
        return $contents;
    }
    /**
     * @param Request $request
     * @param MicroserviceApplication $application
     * @return boolean|array
     */
    public function createNewAccountAllegro(Request $request, MicroserviceApplication $application)
    {
        $errors = [];

//        $login = $this->entityManager->getRepository(AllegroUserAccounts::class)->findOneBy(['login' => $request->headers->get('login')]);
//        if ($login) {
//            array_push($errors, 'Account with same LOGIN was registered, login is ' . $request->headers->get('login'));
//        }
//        $pas = $this->entityManager->getRepository(AllegroUserAccounts::class)->findOneBy(['password' => $request->headers->get('password')]);
//        if ($pas) {
//            array_push($errors, 'Account with same PASSWORD was registered, please change password');
//        }
        /** @var AllegroUserAccounts $account */
        $account = $this->entityManager->getRepository(AllegroUserAccounts::class)->findOneBy(['uuid' => $request->headers->get('uuid')]);

        if ($account) {
            array_push($errors, 'Account with same UUID was registered, name of this user is ' . $account->getName());
        }

        if (!$errors) {
            /** @var AllegroUserAccounts $user */
            $user = (new AllegroUserAccounts())
                ->setName($request->headers->get('name'))
                ->setUuid($request->headers->get('uuid'))
//                ->setLogin($request->headers->get('login'))
//                ->setPassword($request->headers->get('password'))
                ->setSellerId('not yet')
                ->setApplication($application);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->logger->logSimpleOperation(
                AllegroUserAccounts::LOG_OP_REGISTER_NEW_ACC,
                MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
                get_called_class());

            return true;
        }else{
            return $errors;
        }
    }

    /**
     * @param $contents
     * @param AllegroUserAccounts $user
     * @return AllegroActivateDevice
     */
    public function createNewDeviceCode($contents, AllegroUserAccounts $user): AllegroActivateDevice
    {
        $device = (new AllegroActivateDevice())
            ->setDeviceCode($contents->device_code)
            ->setRequestIntervalTime(Carbon::now()->addSeconds($contents->interval))
            ->setStatus('active')
            ->setUserCode($contents->user_code)
            ->setVarificationUriComplited($contents->verification_uri_complete)
            ->setCreateAt(Carbon::now());

        $device->setUser($user);
        $user->setActiveAllegroDevice($device);

        $this->entityManager->persist($device);
        $this->entityManager->flush();

        $this->logger->logSimpleOperation(
            AllegroActivateDevice::LOG_OP_CREATE_DEVICE_CODE,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class(),
            $user);

        return $device;
    }

    /**
     * @param AllegroUserAccounts $user
     * @param string $status
     */
    public function setUsedDeviceStatus(AllegroUserAccounts $user, string $status = 'used')
    {
        /** @var AllegroActivateDevice $device */
        $device = $user->getActiveAllegroDevice();

        $device->setStatus($status);
        $this->entityManager->persist($device);
        $this->entityManager->flush();

        $this->logger->logSimpleOperation(
            AllegroActivateDevice::LOG_OP_CHANGE_DEVICE_STATUS,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class(),
            $user);

    }

    /**
     * @param $content
     * @param AllegroUserAccounts $user
     * @return AllegroTokens
     */
    public function createNewToken($content, AllegroUserAccounts $user): AllegroTokens
    {
        $token = (new AllegroTokens())
            ->setAccessToken($content->access_token)
            ->setRefreshToken($content->refresh_token)
            ->setCreateAt(Carbon::now())
            ->setFinishTo(Carbon::now()->addSeconds($content->expires_in))
            ->setTokenType($content->token_type)
            ->setScope($content->scope)
            ->setJti($content->jti)
            ->setTokenKind('access')
            ->setRedirectUri('');

        $token->setUser($user);
        $user->setAccessAllegroToken($token);

        $code = base64_decode($token->getAccessToken());
        $changeCode = substr($code, strpos($code, "}", 2) + 2);
        $lastVersion = explode(',', substr($changeCode, 0, strpos($changeCode, "scope") - 1));
        $sellerId = substr($lastVersion[1], 13, 8);

        $user->setSellerId($sellerId);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        $this->logger->logSimpleOperation(
            AllegroTokens::LOG_OP_CREATE_TOKEN,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class(),
            $user);
        return $token;
    }

    /**
     * @param $content
     * @param AllegroTokens $token
     * @return AllegroTokens
     */
    public function refreshToken($content, AllegroTokens $token): AllegroTokens
    {
        $token->setTokenKind('refresh')
            ->setAccessToken($content->access_token)
            ->setRefreshToken($content->refresh_token)
            ->setCreateAt(Carbon::now())
            ->setFinishTo(Carbon::now()->addSeconds($content->expires_in))
            ->setTokenType($content->token_type)
            ->setJti($content->jti);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        $this->logger->logSimpleOperation(
            AllegroTokens::LOG_OP_REFRESH_TOKEN,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class().'/tokenID/'.$token->getId(),
            $token->getUser());

        return $token;
    }

    //TODO log
}
