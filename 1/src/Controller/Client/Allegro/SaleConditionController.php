<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\SaleConditionElements;
use Symfony\Component\HttpFoundation\Request;

class SaleConditionController extends MainAllegroController
{

    public function getAllConditions(Request $request, SaleConditionElements $conditionElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }

        if ($user instanceof AllegroUserAccounts) {
            $responseArray['warranties'] = $conditionElements->getGuarantyVariants($user);
            $responseArray['impliedWarranties'] = $conditionElements->getImpliedVariants($user);
            $responseArray['returnPolicies'] = $conditionElements->getReturnPolitic($user);
            $responseArray['additionalServicesGroups'] = $conditionElements->getAdditionalServices($user);
            $responseArray['errors'] = null;
            return $this->apiJsonResponse( $responseArray);
        } else {
            return $user;
        }
    }

    public function getOfferContact(Request $request, SaleConditionElements $conditionElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }

        if ($user instanceof AllegroUserAccounts) {
            $responseArray['message'] = $conditionElements->getContactInfo($user);
            $responseArray['errors'] = null;
            return $this->apiJsonResponse($responseArray);
        } else {
            return $user;
        }

    }

}
