<?php
declare(strict_types=1);

namespace Brainvire\CompanySharedCatalog\Console\Command;

use Brainvire\CompanySharedCatalog\Model\CompanyProductPricingAssigner;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddSharedCatalogProductCommand extends Command
{
    private const ARG_COMPANY_ID = 'company-id';
    private const ARG_SKU = 'sku';
    private const OPT_PRICE = 'price';

    public function __construct(
        private readonly CompanyProductPricingAssigner $assigner,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('brainvire:shared-catalog:add-product');
        $this->setDescription('Add a product (and optional custom price) to one company\'s shared catalog.');
        $this->addArgument(self::ARG_COMPANY_ID, InputArgument::REQUIRED, 'Company entity ID');
        $this->addArgument(self::ARG_SKU, InputArgument::REQUIRED, 'Product SKU');
        $this->addOption(
            self::OPT_PRICE,
            null,
            InputOption::VALUE_OPTIONAL,
            'Custom shared-catalog price for this company'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $companyId = (int) $input->getArgument(self::ARG_COMPANY_ID);
        $sku = (string) $input->getArgument(self::ARG_SKU);
        $priceRaw = $input->getOption(self::OPT_PRICE);
        $customPrice = $priceRaw !== null && $priceRaw !== '' ? (float) $priceRaw : null;

        try {
            $this->assigner->assignProduct($companyId, $sku, $customPrice);
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
