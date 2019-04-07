<?php
namespace App\Security;

use App\Api\ApiProblem;
use App\Api\ApiProblemException;
use App\Entity\MicroserviceApplication;
use App\Repository\ApplicationRepository;
use App\Repository\MicroserviceApplicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{

    /**
     * @var MicroserviceApplicationRepository
     */
    private $applicationRepository;

    public function __construct(MicroserviceApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $apiProblem = new ApiProblem(401,ApiProblem::TOKEN_REQUIRED);
        throw new ApiProblemException($apiProblem);
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Basic ');
    }

    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');

        return substr($authorizationHeader, 6);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        list($appId, $appSecret) = explode(":", base64_decode($credentials));
        $user = $this->applicationRepository->findOneBy(['appId' => $appId, 'appSecret' => $appSecret]);
        if(!$user){
            throw new CustomUserMessageAuthenticationException('invalid_api_token', [], 401);
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
       $apiProblem = new ApiProblem($exception->getCode(), $exception->getMessage());

       throw new ApiProblemException($apiProblem);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
//        dd($request->headers->get('Authorization'));

        // nothing to do
    }

    public function supportsRememberMe()
    {
        return false;
    }
}