<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridge;

/**
 * @method \Spryker\Zed\Tax\TaxConfig getConfig()
 */
class TaxDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const STORE_CONFIG = 'store config';
    /**
     * @var string
     */
    public const FACADE_COUNTRY = 'facade country';
    /**
     * @var string
     */
    public const SERVICE_DATE_FORMATTER = 'date formatter';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container->set(static::STORE_CONFIG, function (Container $container) {
            return Store::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_COUNTRY, function (Container $container) {
            return new TaxToCountryBridge($container->getLocator()->country()->facade());
        });

        $container->set(static::SERVICE_DATE_FORMATTER, function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        });

        return $container;
    }
}
