<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetCollectionTransfer;
use Generated\Shared\Transfer\TaxSetCriteriaTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;

interface TaxRepositoryInterface
{
    public function isTaxSetNameUnique(string $name): bool;

    public function isTaxSetNameAndIdUnique(string $name, int $idTaxSet): bool;

    public function findTaxRate(int $idTaxRate): ?TaxRateTransfer;

    public function findTaxSet(int $idTaxSet): ?TaxSetTransfer;

    public function getTaxSetCollection(TaxSetCriteriaTransfer $taxSetCriteriaTransfer): TaxSetCollectionTransfer;
}
