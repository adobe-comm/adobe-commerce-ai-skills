<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model\Api;

use Magento\Framework\Exception\TemporaryStateException;

/**
 * ERP responded with a transient error (timeout, 5xx, network) — safe to retry.
 */
class ExportRetryableException extends TemporaryStateException
{
}
