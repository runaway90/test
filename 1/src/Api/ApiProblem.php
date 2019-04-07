<?php

namespace App\Api;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiProblem
{
    const TOKEN_REQUIRED = "token_required";
    const INVALID_API_TOKEN = "invalid_api_token";
    const TYPE_VALIDATION_ERROR = "validation_error";
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';
    const PAGINATION_PAGE_NOT_EXIST = 'page_not_exist';
    const PAGINATION_NO_ITEMS = 'no_items_to_search_for';


    private static $titles = [
        self::TOKEN_REQUIRED => "This API Require Token to continue",
        self::INVALID_API_TOKEN => "Invalid API Token",
        self::TYPE_VALIDATION_ERROR => 'Form validation error',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid JSON format sent',
        self::PAGINATION_PAGE_NOT_EXIST => 'Passed page number is different form available pages',
        self::PAGINATION_NO_ITEMS => "Cannot find any item for this search request"
    ];

    /**
     * @var string
     */
    private $statusCode;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $title;
    /**
     * @var array
     */
    private $extraData = array();

    public function __construct($statusCode, $type = null, $title = null)
    {
        $this->statusCode = $statusCode;
        if (!$type) {;
            $type = isset(Response::$statusTexts[$statusCode])
                ? strtolower(str_replace(' ','_',Response::$statusTexts[$statusCode]))
                : 'about:blank';
            if(!$title){
                $title = isset(Response::$statusTexts[$statusCode])
                    ? Response::$statusTexts[$statusCode]
                    : 'Unknown status code.';
            }
        } else {
            if (!isset(self::$titles[$type]) && !$title) {
                throw new \InvalidArgumentException('No title for type ' . $type);
            }
            $title = self::$titles[$type];
        }
        $this->type = $type;
        $this->title = $title;
    }

    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
    }

    public function toArray()
    {
        return array_merge(
            array(
            'status' => $this->statusCode,
            'type' => $this->type,
            'title' => $this->title,
            ),
            $this->extraData
        );
    }

    public function getStatusCode(): ?string
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    public function toJsonResponse(){
        $params = array_merge(
            array(
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title,
            ),
            $this->extraData
        );
        return new JsonResponse($params, $this->statusCode);
    }
}