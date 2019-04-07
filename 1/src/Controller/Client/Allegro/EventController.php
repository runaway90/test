<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\EventOperations;
use Symfony\Component\HttpFoundation\Request;

class EventController extends MainAllegroController
{

    public function orderEvents(Request $request, EventOperations $eventOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $data = $eventOperations->getOrderEvents($user);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function eventStatistic(Request $request, EventOperations $eventOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $data = $eventOperations->getOrderEventStatistics($user);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function userOrders(Request $request, EventOperations $eventOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $data = $eventOperations->getOrderEventStatistics($user);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function detailOrder($id, Request $request, EventOperations $eventOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $data = $eventOperations->getOrderDetails($user, $id);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function parcelList($id, Request $request, EventOperations $eventOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $data = $eventOperations->getTracking($user, $id);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function addParcel($id, Request $request, EventOperations $eventOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $json = json_decode($request->getContent());
        $data = $eventOperations->createTrackingParcel($user, $id, $json->parcel);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);

    }

    public function lineItemMapping(Request $request, EventOperations $eventOperations)
    {
        $lineItemId = $request->get('lineItemId');
        $dealId= $request->get('dealId');
        if(!$dealId && !$lineItemId){
            return $this->apiJsonResponse($this->notFoundRequiredParam(), 400);
        }elseif ($dealId && $lineItemId){
            return $this->apiJsonResponse($this->pleaseRemoveParameter(), 400);
        }

        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $data = $eventOperations->getMappingLineItem($user, $lineItemId, $dealId);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);

    }

}
