<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Dependency\Facade;

use Generated\Shared\Transfer\CountryCollectionTransfer;

class TaxToCountryBridge implements TaxToCountryBridgeInterface
{
    /**
     * @var \Spryker\Zed\Country\Business\CountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\Country\Business\CountryFacadeInterface $countryFacade
     */
    public function __construct($countryFacade)
    {
        $this->countryFacade = $countryFacade;
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getAvailableCountries(): CountryCollectionTransfer
    {
        return $this->countryFacade->getAvailableCountries();
    }
}
