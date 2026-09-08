<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax;

use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class TaxConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return float
     */
    public function getDefaultTaxRate()
    {
        return $this->get(TaxConstants::DEFAULT_TAX_RATE, 0);
    }

    /**
     * Specification:
     * - Enables the optional `uuid` column on the `spy_tax_set` table.
     * - When enabled, the `TaxSetUuid` schema folder is merged during `propel:install`.
     * - Consumers relying on tax set UUID (e.g. backend API existence checks) require this to be enabled.
     *
     * @api
     */
    public function isTaxSetUuidEnabled(): bool
    {
        return false;
    }
}
