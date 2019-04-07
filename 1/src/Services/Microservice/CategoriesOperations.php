<?php

namespace App\Services\Microservice;

use App\Entity\AllegroCategories;

class CategoriesOperations
{
    const REWROTE_CATEGORIES_FILE = 'rewrote_categories_file';
    /**
     * @param AllegroCategories $category
     * @param string $path
     * @return string
     */

    public function getPathOfCategory($category, $path = '')
    {
        $name = $category->getAllegroName();

        /** @var AllegroCategories $parentCategory */
        $parentCategory = $category->getAllegroParentCategory();
        if ($parentCategory) {

            $path = $this->addParentToCategoryPath($parentCategory->getAllegroName(), $name,' >> ');
            $oldCategory = $parentCategory->getAllegroParentCategory();

            if ($oldCategory) {

                $old =$this->getPathOfCategory($oldCategory,'');
                $path = $this->addParentToCategoryPath($old, $path, ' >> ');

            }

        }else{
            $path = $this->addParentToCategoryPath($name, $path);
        }

        return $path;
    }

    public function addParentToCategoryPath($parent, $path = '', $symbol = '')
    {
        $path = $parent . $symbol . $path;

        return $path;
    }


}