<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\AttachmentElements;
use Symfony\Component\HttpFoundation\Request;

class AttachmentController extends MainAllegroController
{
    public function createAttachment(Request $request, AttachmentElements $attachmentElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }
        $body = json_decode($request->getContent());
        $data = $attachmentElements->addAttachment($user, $body->type, $body->file_url);
        return $this->apiJsonResponse($data);
    }
}
