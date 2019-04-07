<?php

namespace App\Controller\Client\Traits;

use App\Api\ApiProblem;
use App\Api\ApiProblemException;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

trait APIResponseTrait
{
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    protected function apiJsonResponse($params, $status = Response::HTTP_OK): JsonResponse
    {
        $response = new JsonResponse($params, $status);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    protected function processForm(Request &$request, FormInterface &$form)
    {
        $body = $request->getContent();
        $data = json_decode($body, true);
        if (!$data) {
            $apiProblem = new ApiProblem(
                400,
                ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT
            );
            throw new ApiProblemException($apiProblem);
        }

        $clearMissing = $request->getMethod() != "PATCH";
        $form->submit($data, $clearMissing);
    }

    protected function serializeObject($object, $config = array(), string $format = 'json')
    {
        try {
            $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        } catch (AnnotationException $e) {
            return null;
        }

        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $serializer = new Serializer(
            array(new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)),
            array('json' => new JsonEncoder(),
                'xml' => new XmlEncoder())
        );
        return $serializer->serialize($object, $format, $config);
    }

    protected function getErrorsFromForm(FormInterface &$form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

    protected function throwApiProblemValidationException(FormInterface &$form): void
    {
        $errors = $this->getErrorsFromForm($form);
        $apiProblem = new ApiProblem(400, ApiProblem::TYPE_VALIDATION_ERROR);
        $apiProblem->set('errors', $errors);

        throw new ApiProblemException($apiProblem);
    }

}
