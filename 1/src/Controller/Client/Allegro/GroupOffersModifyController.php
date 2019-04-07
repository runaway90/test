<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\GroupOffersModifyOperations;
use Symfony\Component\HttpFoundation\Request;

class GroupOffersModifyController extends MainAllegroController
{
    //price
    public function putPriceForOffersGroup(Request $request, GroupOffersModifyOperations $modifyOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());

        $data = $modifyOperations->priceChange($user, $body);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function getStatusCommandPriceForOffersGroup(Request $request, GroupOffersModifyOperations $modifyOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());

        $data = $modifyOperations->priceStatusOrTasks($user, $body);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function getTaskPriceForOffersGroup(Request $request, GroupOffersModifyOperations $modifyOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());

        $data = $modifyOperations->priceStatusOrTasks($user, $body, '/tasks');
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }


    //quantity
    public function putQuantityForOffersGroup(Request $request, GroupOffersModifyOperations $modifyOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());

        $data = $modifyOperations->quantityChange($user, $body);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function getStatusCommandQuantityForOffersGroup(Request $request, GroupOffersModifyOperations $modifyOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());

        $data = $modifyOperations->quantityStatusOrTasks($user, $body);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function getTaskQuantityForOffersGroup(Request $request, GroupOffersModifyOperations $modifyOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());

        $data = $modifyOperations->quantityStatusOrTasks($user, $body, '/tasks');
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }


    //modify
    public function putModificationForOffersGroup(Request $request, GroupOffersModifyOperations $modifyOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());

        $data = $modifyOperations->modifyOffer($user, $body);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function getStatusCommandModificationForOffersGroup(Request $request, GroupOffersModifyOperations $modifyOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());

        $data = $modifyOperations->modifyStatusOrTasks($user, $body);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function getTaskModificationForOffersGroup(Request $request, GroupOffersModifyOperations $modifyOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());

        $data = $modifyOperations->modifyStatusOrTasks($user, $body, '/tasks');
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

}
