<?php

namespace App\Services\Microservice;

use App\Entity\AllegroUserAccounts;
use App\Services\BaseService;
use App\Entity\MicroserviceApplication;
use App\Entity\MicroserviceApplicationIps;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class RegistrationManagerService extends BaseService
{
    /**
     * @param Request $request
     * @return MicroserviceApplication|null
     */
    public function createApplication(Request $request): ?MicroserviceApplication
    {
        $appParams = $this->getAppIdAndSecret();
        if (!$appParams) return null;
        list('appId' => $appId, 'appSecret' => $appSecret) = $appParams;
        $application = (new MicroserviceApplication())
            ->setUri($request->getClientIp())
            ->setAppId($appId)
            ->setAppSecret($appSecret);
        foreach ($request->getClientIps() as $appIp) {
            $applicationIp = (new MicroserviceApplicationIps())
                ->setIp($appIp);
            $application->addApplicationIp($applicationIp);
        }
        $this->saveObject($application);
        return $application;
    }

    /**
     * @return array|null
     */
    private function getAppIdAndSecret()
    {
        try {
            $appId = Uuid::uuid4()->toString();
            $appSecret = md5(random_bytes(200));
        } catch (\Exception $e) {
            $this->logError($e);
            return null;
        }
        return compact('appId', 'appSecret');
    }

    public function createTestApplication(string $name, string $url = 'test'): MicroserviceApplication
    {
        $application = (new MicroserviceApplication())
            ->setAppId(Uuid::uuid4()->toString())
            ->setAppSecret(md5(random_bytes(200)))
            ->setUri($url)
            ->setName($name);

        $this->getEM()->persist($application);
        $this->getEM()->flush();

        return $application;

//        $account = new AllegroUserAccounts();
//        $account->setApplication($application)
//            ->setUuid(md5(random_bytes(200)))
//            ->setName($name);
//
//        $this->getEM()->persist($account);
//        $this->getEM()->flush();

    }
}