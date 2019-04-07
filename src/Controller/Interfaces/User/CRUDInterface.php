<?php

namespace App\Controller\Interfaces\User;

use App\Services\UserOperations;
use Symfony\Component\HttpFoundation\Request;

interface CRUDInterface
{
    public function create(Request $request, UserOperations $userOperations);

    public function update();

    public function delete();

}
