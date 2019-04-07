<?php
namespace App\Services\Traits;

use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    /**
     * @var \Throwable
     */
    private $exception;

    /**
     * @return LoggerInterface
     */
    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function logError(\Throwable $exception, ?array $params = array()): void
    {
        $logName = $this->getLogName($exception);
        $this->getLogger()->error($logName, [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'params' => $params
        ]);
    }

    /**
     * @param \Throwable $exception
     * @return string
     * @author Michał Szargut <michal.szargut@contelizer.pl>
     */
    protected function getLogName(\Throwable $exception): string
    {
        $calledFrom = current($exception->getTrace());
        $class = explode('\\', $calledFrom['class']);
        $class = end($class);
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $class, $matches);
        $class = current($matches);
        $class = implode('_', $class);
        $function = $calledFrom['function'];
        $name = strtoupper($class . '::' . $function);
        return "[$name]";
    }
}