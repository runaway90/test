<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroCategories;
use App\Entity\AllegroParameter;
use App\Entity\AllegroParameterDictionary;
use App\Entity\AllegroTokens;
use App\Entity\AllegroUserAccounts;
use App\Services\Microservice\OperationLogger;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\All;

class ParameterElements extends MainAppService
{
    const LOG_OP_SAVE_PARAMETERS_OF_CATEGORY = 'save_category_parameters';
    const LOG_OP_RESPONSE_PARAMETERS_OF_CATEGORY_FROM_DATABASE = 'response_category_parameters';

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;
    /**
     * @var OperationLogger $logger
     */
    protected $logger;

    /**
     * CategoryElements constructor.
     * @param EntityManagerInterface $entityManager
     * @param OperationLogger $logger
     */
    public function __construct(EntityManagerInterface $entityManager, OperationLogger $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param AllegroUserAccounts $user
     * @param string $category
     * @return object
     */
    public function getParameter(AllegroUserAccounts $user, $category = '')
    {
        /** @var AllegroTokens $activeToken */
        $activeToken = $user->getAccessAllegroToken()->getAccessToken();

        $client = new RequestController();
        $response = $client->createClientRequest()->get('https://api.allegro.pl.allegrosandbox.pl/sale/categories/' . $category . '/parameters', [
            'headers' => $client->getSimpleAuth($activeToken)
        ]);
// TODO try catch
        $contents = json_decode($response->getBody()->getContents());

        return $contents->parameters;
    }

    /**
     * @param object $parameter
     * @param AllegroCategories|null $category
     * @return AllegroParameter
     */
    public function saveOneParameter($parameter, AllegroCategories $category = null)
    {
        $writeParameter = (new AllegroParameter())
            ->setCreateAt(Carbon::now())
            ->setAllegroId($parameter->id)
            ->setAllegroName($parameter->name)
            ->setRequired($parameter->required)
            ->setType($parameter->type)
            ->setUnit($parameter->unit)
            ->setVariantAllowed($parameter->options->variantsAllowed)
            ->setVariantEqual($parameter->options->variantsEqual)
            ->setRestrictions($parameter->restrictions)
            ->setAllegroCategory($category);
        if(isset($parameter->dictionary)){
            $writeParameter->setDictionary($parameter->dictionary);
        }
//
//        if (isset($parameter->dictionary)) {
//            $parameters = $parameter->dictionary;
//            foreach ($parameters as $parameterDictionary) {
//                $dictionary = $this->saveParameterDictionary($parameterDictionary);
//                $writeParameter->addParametersDictionary($dictionary);
//            }
//        }

        $this->entityManager->persist($writeParameter);
        $this->entityManager->flush();

        return $writeParameter;
    }

//    public function saveParameterDictionary($parameterDictionary): AllegroParameterDictionary
//    {
//        $newDictionary = (new AllegroParameterDictionary())
//            ->setAllegroId($parameterDictionary->id)
//            ->setAllegroValue($parameterDictionary->value);
//
//        $this->entityManager->persist($newDictionary);
//        $this->entityManager->flush();
//
//        return $newDictionary;
//    }

    public function checkAndUpdateParameters()
    {
        $change = false;
        $parameters = $this->entityManager->getRepository(AllegroParameter::class)->findAll();
        /** @var AllegroUserAccounts $user */
        $user = $this->entityManager->getRepository(AllegroUserAccounts::class)->findOneBy(['name' => 'Contelizer']);
        if (!$user) {
            dd('You not register microservice in Allegro.');
        }
        /** @var AllegroParameter $parameter */
        foreach ($parameters as $parameter) {
            $getParametersFromCategory = $this->getParameter($user, $parameter->getAllegroCategory()->getAllegroId());
            foreach ($getParametersFromCategory as $getParam) {
                $newParameter = $this->entityManager->getRepository(AllegroParameter::class)->findOneBy(['allegroId' => $getParam->id]);

                /** @var AllegroParameter $newParameter */
                if (!$newParameter) {
                    $this->saveOneParameter($getParam, $parameter->getAllegroCategory()->getAllegroId());
                    $change = true;
                } elseif ($newParameter->getAllegroId() == $parameter->getAllegroId()) {
                    if ($parameter->getRequired() !== $getParam->required) {
                        $parameter->setRequired($getParam->required);
                        $change = true;
                    }

                    if ($parameter->getVariantEqual() !== $getParam->options->variantsEqual) {
                        $parameter->setVariantEqual($getParam->options->variantsEqual);
                        $change = true;
                    }

                    if ($parameter->getVariantAllowed() !== $getParam->options->variantsAllowed) {
                        $parameter->setVariantAllowed($getParam->options->variantsAllowed);
                        $change = true;
                    }

                    if ($parameter->getUnit() !== $getParam->unit) {
                        $parameter->setUnit($getParam->unit);
                        $change = true;
                    }

                    if ($parameter->getParametersDictionary() !== $newParameter->getParametersDictionary()) {
                        /** @var AllegroParameterDictionary $newDictionary */

                        $newDictionary = $this->entityManager->getRepository(AllegroParameterDictionary::class)->findOneBy(['allegroParameter' => $parameter->getAllegroId()]);
                        if ($newDictionary) {
                            $this->entityManager->remove($newDictionary);
                            $this->entityManager->flush();
                            foreach ($newParameter->getParametersDictionary() as $dictionary) {
                                $this->saveParameterDictionary($dictionary);
                            }
                        }
                    }
                    $this->entityManager->persist($parameter);
                    $this->entityManager->flush();
                }
            }
        }

        return $change;

    }
}