<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Controller;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class WebController
 * @package Infrastructure\Symfony\Controller
 */
class WebController extends AbstractController
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var MessageBusInterface
     */
    protected $commandBus;

    /**
     * @param PaginatorInterface  $paginator
     * @param MessageBusInterface $commandBus
     */
    public function __construct(
        PaginatorInterface $paginator,
        MessageBusInterface $commandBus
    ) {
        $this->paginator = $paginator;
        $this->commandBus = $commandBus;
    }

    /**
     * @param       $target
     * @param int   $page
     * @param int   $limit
     * @param array $options
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function paginate($target, $page = 1, $limit = 10, array $options = []): PaginationInterface
    {
        return $this->paginator->paginate($target, $page, $limit, $options);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function uuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @param $command
     */
    public function dispatch($command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $exception) {
            throw  $exception->getPrevious();
        }
    }
}
