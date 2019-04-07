<?php

namespace App\Controller\Client\Microservice;

use App\Entity\AllegroCategories;
use App\Services\Microservice\CategoriesOperations;
use function PHPSTORM_META\elementType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryController
 * @package App\Controller\Client\Microservice
 */
class CategoryController extends MainMSController
{
    /**
     * @return Response
     */
    public function getAll(CategoriesOperations $categoriesOperations): Response
    {

        $categories = $this->getDoctrine()->getRepository(AllegroCategories::class)->findAll();
        if (!$categories) {
            return $this->apiJsonResponse(['device_communicate' => 'Categories not found, please contact with support!', Response::HTTP_LOCKED]);
        }

        /** @var AllegroCategories $category */
        $data = [];
        foreach ($categories as $category) {
            $categoryChildren = [];
            $children = $this->getDoctrine()->getRepository(AllegroCategories::class)->findBy(['allegroParentCategory' => $category->getId()]);

            foreach ($children as $child) {
                array_push($categoryChildren,
                    ['allegro_id' => $child->getAllegroId(),
                        'allegro_name' => $child->getAllegroName()]);
            }

            $parent = $categoriesOperations->getPathOfCategory($category);

            $categoryData = [
                'name' => $category->getAllegroName(),
                'allegro_id' => $category->getAllegroId(),
                'children' => $categoryChildren,
                'path' => $parent
            ];

            array_push($data, $categoryData);
        }


        return $this->apiJsonResponse(['categories' => $data]);
    }

    /**
     * @param CategoriesOperations $categoriesOperations
     * @return Response
     */
    public function getRouteForAllLeafCategories(CategoriesOperations $categoriesOperations): Response
    {
        set_time_limit(0);

        $categories = $this->getDoctrine()->getRepository(AllegroCategories::class)->findAll();
        if (!$categories) {
            return $this->apiJsonResponse(['device_communicate' => 'Categories not found, please contact with support!', Response::HTTP_LOCKED]);
        }

//        $data = [];
        /** @var AllegroCategories $category */
        foreach ($categories as $category) {

            $parent = $categoriesOperations->getPathOfCategory($category);
            if($category->getLeaf()) {
                $categoryData[$category->getAllegroId()] = $parent;

//                array_push($data, $categoryData);
//                $data= array_merge($data, $categoryData);
            }
        }

//        $json_data = json_encode($categoryData);
//        file_put_contents(__DIR__ . '/../../../../public/categories.json', $json_data, true);
        return $this->apiJsonResponse($categoryData);
    }


    /**
     * @param AllegroCategories $category
     * @param CategoriesOperations $categoriesOperations
     * @return Response
     */
    public function getCategoryById(AllegroCategories $category, CategoriesOperations $categoriesOperations): Response
    {

        if (!$category) {
            return $this->apiJsonResponse(['device_communicate' => 'Category not found, please contact with support!', Response::HTTP_LOCKED]);
        }

        $categoryChildren = [];
        $children = $this->getDoctrine()->getRepository(AllegroCategories::class)->findBy(['allegroParentCategory' => $category->getId()]);

        foreach ($children as $child) {
            array_push($categoryChildren, [
                'allegro_id' => $child->getAllegroId(),
                'allegro_name' => $child->getAllegroName()
            ]);
        }

        /** @var AllegroCategories $parentCategory */
        $parentCategory = $category->getAllegroParentCategory();
        $parent = null;
        if ($parentCategory) {
            /** @var AllegroCategories $oldCategory */
            $oldCategory = $this->getDoctrine()->getRepository(AllegroCategories::class)->findOneBy(['allegroId' => $parentCategory->getAllegroId()]);
//            $parent = '' . $oldCategory->getAllegroParentCategory()->getAllegroParentCategory()->getAllegroName() . ' >> ' . $oldCategory->getAllegroParentCategory()->getAllegroName() . ' >> ' . $parentCategory->getAllegroName() . '';
        };

        $parent = $categoriesOperations->getPathOfCategory($category);
        $data = [
//            $category->getAllegroId() => $parent,
            'name' => $category->getAllegroName(),
            'allegro_id' => $category->getAllegroId(),
            'children' => $categoryChildren,
            'path' => $parent
        ];


        $json_data = json_encode($data);
        file_put_contents(__DIR__ . '/categories.json', $json_data);

        return $this->apiJsonResponse([$data]);
    }
}