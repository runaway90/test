<?php

namespace App\Controller\Package;

use App\Entity\Package;
use Symfony\Component\HttpFoundation\Response;

class GetPackageInformation
{
    public function getStatus(Package $id)
    {
        if(!$id){
            return new Response('UUID of package not found', 404);
        }else{
            return new Response('Status is - '. $id->getStatus(), 200);
        }
    }
}