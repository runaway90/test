<?php

namespace App\Controller\Interfaces\Package\Status;

use App\Services\PackageOperations;
use Symfony\Component\HttpFoundation\Request;

interface PackageDeliveredInterface
{

    public function packageDelivered(Request $request, PackageOperations $packageOperations);

}
