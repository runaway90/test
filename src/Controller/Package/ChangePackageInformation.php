<?php

namespace App\Controller\Package;

use App\Controller\Interfaces\Package\Status\PackageInTransportInterface;
use App\Controller\Interfaces\Package\Status\PackageDeliveredInterface;
use App\Controller\MainController;
use App\Entity\Package;
use App\Services\PackageOperations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangePackageInformation extends MainController implements PackageDeliveredInterface, PackageInTransportInterface

{
    public function packageDelivered(Request $request, PackageOperations $packageOperations)
    {
        /** @var Package $package */
        $package = $packageOperations->findPackageByUUID($request);
        if(!$package){
            return new Response('UUID of package not found', 401);
        }else{
            $packageOperations->setStatus($package, Package::PACKAGE_STATUS_DELIVERED);
        }

    }

    public function packageInTransport(Request $request, PackageOperations $packageOperations)
    {
        /** @var Package $package */
        $package = $packageOperations->findPackageByUUID($request);
        if(!$package){
            return new Response('UUID of package not found', 401);
        }else{
            $packageOperations->setStatus($package, Package::PACKAGE_STATUS_IN_TRANSPORT);
        }
    }

}