<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroParameter;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Allegro\ParameterElements;
use App\Services\Microservice\OperationLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParameterController extends MainAllegroController
{
    public function getActualParameterForCategory(Request $request, ParameterElements $parameterElements, OperationLogger $logger)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }

        if ($user instanceof AllegroUserAccounts) {
            $allegroCategoryId = $request->get('allegro_category_id');
            if (!$allegroCategoryId) {
                return $this->apiJsonResponse(['Errors' => ['Please add category parameter in request']], 500);
            }

            $change = false;
            $parameters = $parameterElements->getParameter($user, $allegroCategoryId);

            foreach ($parameters as $param) {
                $findParam = $this->getDoctrine()->getRepository(AllegroParameter::class)->findOneBy(['allegroId' => $param->id]);
                if (!$findParam) {
                    $parameterElements->saveOneParameter($param);
                    $change = true;
                }
            }

            if ($change) {
                $logger->logSimpleOperation(
                    ParameterElements::LOG_OP_SAVE_PARAMETERS_OF_CATEGORY,
                    MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
                    get_called_class(),
                    $user);
            }

            return $this->apiJsonResponse(['message' => 'Parameters for category ' . $allegroCategoryId . ' wrote to DB']);
        } else {
            return $user;
        }
    }

}
