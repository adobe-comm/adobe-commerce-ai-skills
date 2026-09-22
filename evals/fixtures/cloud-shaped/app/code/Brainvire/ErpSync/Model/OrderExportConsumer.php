<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model;

class OrderExportConsumer
{
    public function process(string $message): void
    {
        // Fixture stub: POST order payload to ERP sandbox URL from env config
    }
}
