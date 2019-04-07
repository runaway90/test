<?php
namespace App\Services;

use App\Services\Traits\EMTrait;
use App\Services\Traits\LoggerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class BaseService
{
    use LoggerTrait;
    use EMTrait;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * BaseService constructor.
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $em
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $em
    )
    {
        $this->logger = $logger;
        $this->em = $em;
    }
}