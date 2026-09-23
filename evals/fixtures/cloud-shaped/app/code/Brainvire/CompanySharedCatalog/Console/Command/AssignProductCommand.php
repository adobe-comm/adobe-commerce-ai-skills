<?php
declare(strict_types=1);

namespace Brainvire\CompanySharedCatalog\Console\Command;

use Brainvire\CompanySharedCatalog\Model\SharedCatalogProductAssigner;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AssignProductCommand extends Command
{
    private const OPTION_COMPANY = 'company-id';
    private const OPTION_SKU = 'sku';
    private const OPTION_PRICE = 'price';

    public function __construct(
        private readonly SharedCatalogProductAssigner $sharedCatalogProductAssigner,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('brainvire:shared-catalog:assign-product');
        $this->setDescription('Assign a product SKU to the shared catalog for one B2B company.');
        $this->addOption(
            self::OPTION_COMPANY,
            null,
            InputOption::VALUE_REQUIRED,
            'Company entity ID'
        );
        $this->addOption(
            self::OPTION_SKU,
            null,
            InputOption::VALUE_REQUIRED,
            'Product SKU to add to the company shared catalog'
        );
        $this->addOption(
            self::OPTION_PRICE,
            null,
            InputOption::VALUE_OPTIONAL,
            'Custom shared-catalog price for the SKU'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $companyId = (int) $input->getOption(self::OPTION_COMPANY);
        $sku = (string) $input->getOption(self::OPTION_SKU);
        $priceOption = $input->getOption(self::OPTION_PRICE);
        $customPrice = $priceOption !== null && $priceOption !== '' ? (float) $priceOption : null;

        if ($companyId <= 0 || $sku === '') {
            $output->writeln('<error>--company-id and --sku are required.</error>');

            return Cli::RETURN_FAILURE;
        }

        try {
            $this->sharedCatalogProductAssigner->assign($companyId, $sku, $customPrice);
        } catch (\Exception $exception) {
            $output->writeln('<error>' . $exception->getMessage() . '</error>');

            return Cli::RETURN_FAILURE;
        }

        $output->writeln(
            sprintf(
                '<info>SKU %s assigned to shared catalog for company %d.</info>',
                $sku,
                $companyId
            )
        );

        return Cli::RETURN_SUCCESS;
    }
}
