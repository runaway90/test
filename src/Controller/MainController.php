<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{

    protected function checkUserUUID(Request $request): User
    {
        $userUUID = $request->headers->get('uuid');

        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['uuid' => $userUUID]);

        return $user;
    }

}