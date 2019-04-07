<?php

namespace App\Controller\Client\Microservice;


use App\Entity\AllegroCategories;
use App\Entity\AllegroParameter;
use App\Entity\AllegroParameterDictionary;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Allegro\ParameterElements;
use App\Services\Microservice\OperationLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParameterController extends MainMSController
{

    /**
     * @return Response
     */
    public function getAll(): Response
    {
        $parameters = $this->getDoctrine()->getRepository(AllegroParameter::class)->findAll();
        if (!$parameters) {
            return $this->apiJsonResponse(['device_communicate' => 'Parameter not found, please contact with support!', Response::HTTP_LOCKED]);
        }

        /** @var AllegroParameter $parameter */
        $data = [];
        foreach ($parameters as $parameter) {
//            dd($parameter->getType());

            $dataParam = [];
            switch ($parameter->getType()) {
                case 'float':
                    $dataParam = $parameter->getRestrictions();
                    break;

                case 'string':
                    $dataParam = $parameter->getRestrictions();
                    break;

                case 'dictionary':
                    $characteristics = $parameter->getParametersDictionary();
                    /** @var AllegroParameterDictionary $characteristic */
                    foreach ($characteristics as $characteristic) {

                        $dataChar = [
                            'allegro_id' => $characteristic->getAllegroId(),
                            'allegro_value' => $characteristic->getAllegroValue()
                        ];

                        array_push($dataParam, $dataChar);

                    }
                    break;

                case 'integer':
                    $dataParam = $parameter->getRestrictions();
                    break;
            }

            $parameterData = [
                'parameter_id' => $parameter->getAllegroid(),
                'parameter_name' => $parameter->getAllegroName(),
                'parameter_required' => $parameter->getRequired(),
                'param_type' => $parameter->getType(),
                'characteristic' => $dataParam
            ];
            array_push($data, $parameterData);
        }
        return $this->apiJsonResponse(['parameters' => $data]);

    }

    /**
     * @param AllegroParameter $parameter
     * @return Response
     */
    public function getParameterById(AllegroParameter $parameter): Response
    {
        if (!$parameter) {
            return $this->apiJsonResponse(['device_communicate' => 'Parameter not found, please contact with support!', Response::HTTP_LOCKED]);
        }

        $dataParam = [];
        $characteristics = $parameter->getParametersDictionary();
        /** @var AllegroParameterDictionary $characteristic */
        foreach ($characteristics as $characteristic) {

            $dataChar = [
                'allegro_id' => $characteristic->getAllegroId(),
                'allegro_value' => $characteristic->getAllegroValue()
            ];

            array_push($dataParam, $dataChar);

        }

        $data = [
            'parameter' => $parameter->getAllegroName(),
            'allegro_id' => $parameter->getAllegroid(),
            'parameter_required' => $parameter->getRequired(),
            'param_type' => $parameter->getType(),
            'characteristic' => $dataParam
        ];

        return $this->apiJsonResponse($data);
    }

    /**
     * @param ParameterElements $parameterElements
     * @param OperationLogger $logger
     * @return Response
     */
    public function getParametersByCategoryId($categoryId, Request $request, ParameterElements $parameterElements, OperationLogger $logger): Response
    {
//        /** @var AllegroUserAccounts|null $user */
//        $user = $this->getAndCheckUser($request);
//        if ($user == null) {
//            return $this->apiJsonResponse($this->userNotFoundError(), 406);
//        }
        $user = $this->getDoctrine()->getRepository(AllegroUserAccounts::class)->findOneBy(['name' => 'Contelizer']);
        /** @var AllegroCategories $category */
        $category = $this->getDoctrine()->getRepository(AllegroCategories::class)->findOneBy(['allegroId' => $categoryId]);
        if ($category) {

            $parameters = $this->getDoctrine()->getRepository(AllegroParameter::class)->findBy(['allegroCategory' => $category->getId()]);
//            return $this->apiJsonResponse($parameters);

            if (!$parameters) {
                $getParameters = $parameterElements->getParameter($user, $category->getAllegroId());
                foreach ($getParameters as $oneParameter) {
                    $parameterElements->saveOneParameter($oneParameter, $category);
                }

                $logger->logSimpleOperation(
                    $parameterElements::LOG_OP_SAVE_PARAMETERS_OF_CATEGORY,
                    MicroserviceOperationLogs::APP_LEVEL_MS_API,
                    get_called_class(),
                    $user);

                $logger->logSimpleOperation(
                    $parameterElements::LOG_OP_RESPONSE_PARAMETERS_OF_CATEGORY_FROM_DATABASE,
                    MicroserviceOperationLogs::APP_LEVEL_MS_API,
                    get_called_class(),
                    $user);

                return $this->apiJsonResponse($getParameters);

            } else {

                $response = [];
                /** @var AllegroParameter $parameter */
                foreach ($parameters as $parameter) {

//                    $dictionaries = $parameter->getParametersDictionary();
//                    $paramDictionary = [];
//                    foreach ($dictionaries as $dictionary) {
//                        array_push($paramDictionary, ['id' => $dictionary->getAllegroId(),
//                            'value' => $dictionary->getAllegroValue()]);
//                    }

                    $ResponseOneParameter = [
                        "id" => $parameter->getAllegroId(),
                        "name" => $parameter->getAllegroName(),
                        "type" => $parameter->getType(),
                        "required" => $parameter->getRequired(),
                        "unit" => $parameter->getUnit(),
                        "options" => [
                            "variantsAllowed" => $parameter->getVariantAllowed(),
                            "variantsEqual" => $parameter->getVariantEqual()],
                        "dictionary" => $parameter->getDictionary(),
                        "restrictions" => $parameter->getRestrictions()
                    ];
                    array_push($response, $ResponseOneParameter);
                }

                $logger->logSimpleOperation(
                    $parameterElements::LOG_OP_RESPONSE_PARAMETERS_OF_CATEGORY_FROM_DATABASE,
                    MicroserviceOperationLogs::APP_LEVEL_MS_API,
                    get_called_class(),
                    $user);

                return $this->apiJsonResponse($response);

            }
        }

        return $this->apiJsonResponse(["device_communicate" => 'Allegro category not found'], Response::HTTP_NOT_FOUND);
    }
}
