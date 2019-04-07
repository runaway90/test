<?php
namespace App\Controller\Client\Interfaces;

use App\Services\Allegro\AuthorizationProcess;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface AuthorizationInterface
 * @package App\Controller\Client\Interfaces
 */
interface AuthorizationInterface
{
    /**
     * @param Request $request
     * @param AuthorizationProcess $authorization
     */
    public function authorization(Request $request, AuthorizationProcess $authorization);

}