<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\PhotoElements;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhotoController extends MainAllegroController
{

    public function sendPhotoByUrl(Request $request, PhotoElements $photoElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }

        $body = json_decode($request->getContent());

        $data = $photoElements->sendPhotoElement($user, $body->photo_url);

        if (array_key_exists('location', $data)) {
            $responseArray['photo'] = ['data' => $data, 'errors' => null];
        } else {
            $responseArray = ['data' => null, 'errors' => $data];
        }

        return $this->apiJsonResponse($responseArray);

    }

    public function sendManyPhotoByUrl(Request $request, PhotoElements $photoElements)
    {
        set_time_limit(0);

        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }

        $body = json_decode($request->getContent());
        $numberOfPhoto = 1;
        foreach ($body->photo_urls as $url) {
            $data = $photoElements->sendPhotoElement($user, $url);
            if (array_key_exists('location', $data)) {
                $responseArray['photo_' . $numberOfPhoto] = ['data' => $data, 'errors' => null];
            } else {
                $responseArray['photo_' . $numberOfPhoto] = ['data' => null, 'errors' => $data];
            }
            $numberOfPhoto++;
        }

        return $this->apiJsonResponse($responseArray);
    }
}
