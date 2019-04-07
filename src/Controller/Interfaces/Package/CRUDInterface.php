<?php

namespace App\Controller\Interfaces\Package;

use App\Services\PackageOperations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CRUDInterface
{
    public function create(Request $request, PackageOperations $packageOperations): Response;

    public function update();

    public function delete();

}
