<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\SaleOfferElements;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SaleOffersController extends MainAllegroController
{

    public function sendDraft(Request $request, SaleOfferElements $offerElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $offerElements->sendDraft($user, $body->offer);
        return $this->apiJsonResponse($data);
    }

    public function deleteDraft(Request $request, SaleOfferElements $offerElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $offerElements->removeDraft($user, $body->offer);
        return $this->apiJsonResponse($data);
    }

    public function sendManySaleOffer(Request $request, SaleOfferElements $offerElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        foreach ($body->offers as $oneOffer) {
            $data = $offerElements->sendDraft($user, $oneOffer);
            if (array_key_exists('id', $data)) {
                $responseArray[] = ['offer' => $data, 'errors' => null];
            } else {
                $responseArray[] = ['data' => null, 'errors' => $data];
            }
        }
        return $this->apiJsonResponse($responseArray);
    }

    public function putOfferInformation(Request $request, SaleOfferElements $offerElements)
    {
        /** @var array|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user === null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $offerElements->putOfferInfo($user, $body->offer);
        return $this->apiJsonResponse($data);
    }

    public function getOfferInformation(Request $request, SaleOfferElements $offerElements)
    {
        /** @var array|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user === null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $data = $offerElements->getOfferInfo($user, $request->headers->get('offerId'));
        return $this->apiJsonResponse($data);
    }

    public function publicationOffer(Request $request, SaleOfferElements $offerElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $offerElements->changeStatusAndScheduleOffer($user, $body->offerId, $body->commandId);
        return $this->apiJsonResponse($data);
    }

    public function setScheduleAndActivateOffer(Request $request, SaleOfferElements $offerElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $offerElements->changeStatusAndScheduleOffer($user, $body->offerId, $body->commandId, 'ACTIVATE', $body->schedule);
        return $this->apiJsonResponse($data);
    }

    public function endOffer(Request $request, SaleOfferElements $offerElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $offerElements->changeStatusAndScheduleOffer($user, $body->offerId, $body->commandId, 'END');
        return $this->apiJsonResponse($data);
    }

    public function getSimpleStatusOfferCommand(Request $request, SaleOfferElements $offerElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $offerElements->takeSimpleStatusOffer($user, $body->commandId);
        return $this->apiJsonResponse($data);
    }

    public function getDifficultStatusOfferCommand(Request $request, SaleOfferElements $offerElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $offerElements->takeDifficultStatusOffer($user, $body->commandId);
        return $this->apiJsonResponse($data);
    }

    public function changeBuyNowPrice(Request $request, SaleOfferElements $offerElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $offerElements->changeTheBuyNowPrice($user, $body->offerId, $body->commandId, $body->amount, $body->currency);
        return $this->apiJsonResponse($data);
    }

}
