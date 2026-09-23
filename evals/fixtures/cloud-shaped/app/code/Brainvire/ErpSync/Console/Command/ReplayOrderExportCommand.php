<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Console\Command;

use Brainvire\ErpSync\Model\Queue\OrderExportQueuePublisher;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\NoSuchEntityException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReplayOrderExportCommand extends Command
{
    private const ARG_ORDER_ID = 'order-id';

    public function __construct(
        private readonly OrderExportQueuePublisher $orderExportQueuePublisher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('brainvire:erp:replay');
        $this->setDescription('Re-enqueue a single order for asynchronous ERP export.');
        $this->addArgument(
            self::ARG_ORDER_ID,
            InputArgument::REQUIRED,
            'Magento sales order entity ID'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orderId = (int) $input->getArgument(self::ARG_ORDER_ID);

        try {
            $this->orderExportQueuePublisher->enqueueByOrderId($orderId);
        } catch (NoSuchEntityException $exception) {
            $output->writeln('<error>' . $exception->getMessage() . '</error>');
            return Cli::RETURN_FAILURE;
        }

        $output->writeln(
            sprintf(
                '<info>Order %d enqueued for ERP export (topic %s).</info>',
                $orderId,
                OrderExportQueuePublisher::TOPIC_NAME
            )
        );

        return Cli::RETURN_SUCCESS;
    }
}
