<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Dependency\Facade;

interface TaxToLocaleFacadeInterface
{
    /**
     * @return string
     */
    public function getCurrentLocaleName(): string;
}
