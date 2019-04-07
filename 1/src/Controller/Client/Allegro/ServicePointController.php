<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\ServicePointElements;
use Symfony\Component\HttpFoundation\Request;

class ServicePointController extends MainAllegroController
{

    public function createServicePoint(Request $request, ServicePointElements $servicePointElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $servicePointElements->create($user, $body->point);

        return $this->apiJsonResponse($data);

    }

    public function getServicePoint(Request $request, ServicePointElements $servicePointElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $data = $servicePointElements->getPoint($user);

        return $this->apiJsonResponse($data);

    }

    public function getDetailPoint($id, Request $request, ServicePointElements $servicePointElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $data = $servicePointElements->getPointDetail($user, $id);

        return $this->apiJsonResponse($data);

    }

    public function modifyPoint(Request $request, ServicePointElements $servicePointElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $servicePointElements->modify($user, $body->point);

        return $this->apiJsonResponse($data);

    }

    public function deletePoint($id, Request $request, ServicePointElements $servicePointElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $data = $servicePointElements->delete($user, $id);

        return $this->apiJsonResponse($data);

    }

}
