<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\DeliveryService;
use Symfony\Component\HttpFoundation\Request;

class DeliveryController extends MainAllegroController
{

    public function getActualParameterForCategory(Request $request, DeliveryService $deliveryService)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }

        $response = $deliveryService->getAllDeliveryMethods($request);
        $responseArray['message'] = $response;
        $responseArray['errors'] = null;
        return $this->apiJsonResponse($responseArray);
    }

    public function createShippingRate(Request $request, DeliveryService $deliveryService = null)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
//        $response = $deliveryService->createNewShippingRate($request);
        return $this->apiJsonResponse(['message' => 'NOT WORKING NOW!'], 201);
    }

    public function getShippingRates(Request $request, DeliveryService $deliveryService = null)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }

        $response = $deliveryService->getShippingRates($request);
        $responseArray['message'] = $response;
        $responseArray['errors'] = null;
        return $this->apiJsonResponse($responseArray);
    }

}
