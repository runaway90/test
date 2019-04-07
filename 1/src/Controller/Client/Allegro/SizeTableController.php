<?php

namespace App\Controller\Client\Allegro;

use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\SizeTableElements;
use Symfony\Component\HttpFoundation\Request;

class SizeTableController extends MainAllegroController
{

    public function getAllTables(Request $request, SizeTableElements $tableElements)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }


        if ($user instanceof AllegroUserAccounts) {
            $response = $tableElements->allSizeTables($user);
            if (!$response) {
                $responseArray['errors'] = "Table not found in Allegro, please check table id on Your account.";
            }else{
                $responseArray['message'] = $response;
                $responseArray['errors'] = null;
            }

            return $this->apiJsonResponse($responseArray);
        } else {
            return $user;
        }
    }

    public function getOneTable($table, Request $request, SizeTableElements $tableElements)
    {
        if (!$table) {
            return $this->apiJsonResponse(["errors" => "Not found this table. Please check table name."]);
        }
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }


        if ($user instanceof AllegroUserAccounts) {
            $oneTable = $tableElements->oneTable($user, $table);
            if (!$oneTable) {
                $responseArray['errors'] = "Table not found in Allegro, please check table id on Your account.";
            }else{
                $responseArray['message'] = $oneTable;
                $responseArray['errors'] = null;
            }
            return $this->apiJsonResponse($responseArray);
        } else {
            return $user;
        }
    }

}
