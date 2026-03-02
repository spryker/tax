<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Communication\Controller;

use SprykerTest\Zed\Tax\PageObject\TaxSetListPage;
use SprykerTest\Zed\Tax\TaxCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Communication
 * @group Controller
 * @group TaxSetEditCest
 * Add your own group annotations below this line
 */
class TaxSetEditCest
{
    public function breadcrumbIsVisible(TaxCommunicationTester $i): void
    {
        $i->listDataTable(TaxSetListPage::DATA_TABLE_URL);
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Administration / Tax Sets / Edit Tax Set');
    }

    public function dataIsProvided(TaxCommunicationTester $i): void
    {
        $i->listDataTable(TaxSetListPage::DATA_TABLE_URL);
        $i->clickDataTableEditButton();

        $name = $i->grabValueFrom('[name="tax_set[name]"]');
        $i->assertNotEmpty($name);
    }
}
