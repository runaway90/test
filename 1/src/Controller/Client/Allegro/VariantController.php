<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\VariantOperations;
use Symfony\Component\HttpFoundation\Request;

class VariantController extends MainAllegroController
{

    public function createOfferWithManyVariants(Request $request, VariantOperations $variantOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $variantUUID =  $request->headers->get('variantUUID');
        $data = $variantOperations->create($user, $variantUUID, $body);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function getOfferWithManyVariants(Request $request, VariantOperations $variantOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $variantUUID =  $request->headers->get('variantUUID');
        $data = $variantOperations->get($user, $variantUUID);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function getAllOfferWithManyVariants(Request $request, VariantOperations $variantOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $data = $variantOperations->getAll($user);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

    public function deleteOfferWithManyVariants(Request $request, VariantOperations $variantOperations)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $variantUUID =  $request->headers->get('variantUUID');
        $data = $variantOperations->delete($user, $variantUUID);
        $status = $this->checkErrorStatus($data);
        return $this->apiJsonResponse($data, $status);
    }

}
