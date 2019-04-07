<?php

namespace App\Controller\Interfaces\Package\Status;

use App\Services\PackageOperations;
use Symfony\Component\HttpFoundation\Request;

interface PackageInTransportInterface
{

    public function packageInTransport(Request $request, PackageOperations $packageOperations);

}
