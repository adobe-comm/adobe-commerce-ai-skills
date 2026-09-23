<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model\Api;

use Magento\Framework\Exception\LocalizedException;

/**
 * ERP rejected the payload (4xx) — retrying the same message will not help.
 */
class ExportPermanentFailureException extends LocalizedException
{
}
