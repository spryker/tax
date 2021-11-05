<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Business\Model;

use Codeception\Test\Unit;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException;
use Spryker\Zed\Tax\Business\TaxFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Business
 * @group Model
 * @group ReaderTest
 * Add your own group annotations below this line
 */
class ReaderTest extends Unit
{
    /**
     * @var string
     */
    public const DUMMY_TAX_SET_NAME = 'SalesTax';

    /**
     * @var string
     */
    public const DUMMY_TAX_RATE1_NAME = 'Local';

    /**
     * @var int
     */
    public const DUMMY_TAX_RATE1_PERCENTAGE = 25;

    /**
     * @var string
     */
    public const DUMMY_TAX_RATE2_NAME = 'Regional';

    /**
     * @var int
     */
    public const DUMMY_TAX_RATE2_PERCENTAGE = 10;

    /**
     * @var int
     */
    public const NON_EXISTENT_ID = 999999999;

    /**
     * @var \Spryker\Zed\Tax\Business\TaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->taxFacade = new TaxFacade();
    }

    /**
     * @return void
     */
    public function testGetTaxRates(): void
    {
        $this->loadFixtures();
        $taxRateCollectionTransfer = $this->taxFacade->getTaxRates();
        $this->assertTrue(count($taxRateCollectionTransfer->getTaxRates()) > 0);
    }

    /**
     * @return void
     */
    public function testGetTaxRate(): void
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->getTaxRate($persistedTaxSet->getSpyTaxRates()[0]->getIdTaxRate());
        $this->assertSame(static::DUMMY_TAX_RATE1_NAME, $result->getName());
        $this->assertSame(sprintf('%.2f', static::DUMMY_TAX_RATE1_PERCENTAGE), $result->getRate());
    }

    /**
     * @return void
     */
    public function testTaxRateExists(): void
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->taxRateExists($persistedTaxSet->getSpyTaxRates()[0]->getIdTaxRate());
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testGetTaxSets(): void
    {
        $this->loadFixtures();
        $taxSetCollectionTransfer = $this->taxFacade->getTaxSets();
        $this->assertNotEmpty($taxSetCollectionTransfer->getTaxSets());
    }

    /**
     * @return void
     */
    public function testGetTaxSet(): void
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->getTaxSet($persistedTaxSet->getIdTaxSet());
        $this->assertSame(static::DUMMY_TAX_SET_NAME, $result->getName());
    }

    /**
     * @return void
     */
    public function testTaxSetExists(): void
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->taxSetExists($persistedTaxSet->getIdTaxSet());
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testExceptionRaisedIfAttemptingToFetchNonExistentTaxRate(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->taxFacade->getTaxSet(static::NON_EXISTENT_ID);
    }

    /**
     * @return void
     */
    public function testExceptionRaisedIfAttemptingToFetchNonExistentTaxSet(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->taxFacade->getTaxRate(static::NON_EXISTENT_ID);
    }

    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSet
     */
    private function loadFixtures(): SpyTaxSet
    {
        $taxRateEntity = new SpyTaxRate();
        $taxRateEntity->setName(static::DUMMY_TAX_RATE1_NAME);
        $taxRateEntity->setRate(static::DUMMY_TAX_RATE1_PERCENTAGE);
        $taxRateEntity->save();

        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->setName(static::DUMMY_TAX_SET_NAME);
        $taxSetEntity->addSpyTaxRate($taxRateEntity);
        $taxSetEntity->save();

        return $taxSetEntity;
    }
}
