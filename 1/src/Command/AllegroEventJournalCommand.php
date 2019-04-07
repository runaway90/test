<?php

namespace App\Command;

use App\Entity\AllegroEvent;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Allegro\EventOperations;
use App\Services\Microservice\CoreRequest;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AllegroEventJournalCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var OperationLogger
     */
    private $logger;

    /**
     * @var EventOperations
     */
    private $eventOperations;
    /**
     * @var CoreRequest
     */
    private $requestToCore;

    /**
     * AllegroGetActualCategoriesCommand constructor.
     * @param EntityManagerInterface $em
     * @param EventOperations $eventOperations
     * @param CoreRequest $coreRequest
     * @param OperationLogger $logger
     */
    public function __construct(EntityManagerInterface $em, OperationLogger $logger, EventOperations $eventOperations, CoreRequest $coreRequest)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->eventOperations = $eventOperations;
        $this->requestToCore = $coreRequest;
        parent::__construct();
    }

    protected static $defaultName = 'allegro:event-journal';

    protected function configure()
    {
        $this
            ->setDescription('This command looking for new events');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        /** @var AllegroUserAccounts|null $user */
        $users = $this->em->getRepository(AllegroUserAccounts::class)->findAll();
        if (!$users) {
            $io->error('Users not found');
            die;
        }

        foreach ($users as $user){

            $eventAlreadyExist = $user->getAllegroEvents();
            if($eventAlreadyExist){
                $lastEvent = $this->em->getRepository(AllegroEvent::class)->findBy(['getUserAccount' => $user], ['createAt' => 'DESC']);
                $events = $this->eventOperations->getOrderEventsFromLastId($user, $lastEvent);
            }else{
                $events = $this->eventOperations->getOrderEvents($user);
            }

            foreach ($events as $event) {
                $checkEvent = $this->em->getRepository(AllegroEvent::class)->findBy(['allegroId' => $event->id]);
                if(!$checkEvent && $event->type == 'READY_FOR_PROCESSING'){
                    $this->requestToCore->sendRequestToCore('event', $event);

                    $this->eventOperations->writeEventToDB($event, $user);
                }
            }

            $this->logger->logSimpleOperation(
                EventOperations::LOG_OP_GET_ORDER_EVENTS,
                MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
                get_called_class(),
                $user);
        }


        $io->success('All event send to cores');
    }
}
