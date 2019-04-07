<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroCategories;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Allegro\CategoryElements;
use App\Services\Microservice\OperationLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryController
 * @package App\Controller\Client\Allegro
 */
class CategoryController extends MainAllegroController
{
    /**
     * @param Request $request
     * @param string $categoryId
     * @param null $parentCategory
     * @param CategoryElements $category
     * @param OperationLogger $logger
     * @return Response
     */
    public function writeActualCategoriesRoute(Request $request, $categoryId = '', $parentCategory = null, CategoryElements $category, OperationLogger $logger)
    {
        set_time_limit(0);

        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }

        if ($user instanceof AllegroUserAccounts) {

            $categoryWrote = $this->getDoctrine()->getRepository(AllegroCategories::class)->findAll();
            if ($categoryWrote) {
                return $this->apiJsonResponse(['device_communicate' => 'Actual categories was wrote to DB. Please contact with administrator'], Response::HTTP_ALREADY_REPORTED); // TODO
            }

            $mainCategories = $category->getCategories($user, $categoryId);

            if ($mainCategories == null) {
                return $this->apiJsonResponse(['device_communicate' => 'Error of get categories from Allegro. Please contact with administrator'], Response::HTTP_INTERNAL_SERVER_ERROR); // TODO
            }

//        $category = $this->getDoctrine()->getRepository(AllegroCategories::class)->findAll();
//        if($category){
//            return $this->apiJsonResponse(['device_communicate' => 'Actual Allegro categories was wrote'], Response::HTTP_NOT_EXTENDED);
//        }

            foreach ($mainCategories as $mainCategory) {
                $childCategory = $category->saveOneCategory($mainCategory, $parentCategory);

                if ($mainCategory->leaf == false) {
                    $category->createCategories($mainCategory->id, $childCategory, $user);
                }
            }

            $logger->logSimpleOperation(
                CategoryElements::LOG_OP_DOWNLOAD_ACTUAL_CATEGORIES,
                MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
                get_called_class(),
                $user);
            return $this->apiJsonResponse(['device_communicate' => 'Actual categories was wrote to DB']);
        } else {
            return $user;
        }
    }

}
