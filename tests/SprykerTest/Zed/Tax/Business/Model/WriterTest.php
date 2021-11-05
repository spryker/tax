<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\TaxSetBuilder;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxRateQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\Tax\Business\Model\Exception\DuplicateResourceException;
use Spryker\Zed\Tax\Business\Model\Exception\MissingTaxRateException;
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
 * @group WriterTest
 * Add your own group annotations below this line
 */
class WriterTest extends Unit
{
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
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    private function createTaxRateTransfer(): TaxRateTransfer
    {
        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setName(static::DUMMY_TAX_RATE1_NAME);
        $taxRateTransfer->setRate(static::DUMMY_TAX_RATE1_PERCENTAGE);

        return $taxRateTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    private function createTaxSetTransfer(): TaxSetTransfer
    {
        return (new TaxSetBuilder())->build();
    }

    /**
     * @return void
     */
    public function testCreateTaxRate(): void
    {
        $taxRateTransfer = $this->createTaxRateTransfer();

        $this->taxFacade->createTaxRate($taxRateTransfer);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($taxRateTransfer->getIdTaxRate())->findOne();

        $this->assertNotEmpty($taxRateQuery);
        $this->assertSame(sprintf('%.2f', static::DUMMY_TAX_RATE1_PERCENTAGE), $taxRateQuery->getRate());
        $this->assertSame(static::DUMMY_TAX_RATE1_NAME, $taxRateQuery->getName());
    }

    /**
     * @return void
     */
    public function testCreateTaxSetWithNewTaxRate(): void
    {
        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxRateTransfer = $this->createTaxRateTransfer();

        $taxSetTransfer->addTaxRate($taxRateTransfer);

        $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetTransfer->getIdTaxSet())->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertSame($taxSetTransfer->getName(), $taxSetQuery->getName());
        $this->assertNotEmpty($taxSetQuery->getSpyTaxRates());
    }

    /**
     * @return void
     */
    public function testCreateTaxSetWithExistingTaxRate(): void
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $this->taxFacade->createTaxRate($taxRateTransfer);

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetTransfer->getIdTaxSet())->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertNotEmpty($taxSetQuery->getSpyTaxRates());
    }

    /**
     * @return void
     */
    public function testUpdateTaxRate(): void
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $id = $this->taxFacade->createTaxRate($taxRateTransfer)->getIdTaxRate();

        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setIdTaxRate($id);
        $taxRateTransfer->setName(static::DUMMY_TAX_RATE2_NAME);
        $taxRateTransfer->setRate(static::DUMMY_TAX_RATE2_PERCENTAGE);

        $this->taxFacade->updateTaxRate($taxRateTransfer);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($id)->findOne();

        $this->assertNotEmpty($taxRateQuery);
        $this->assertSame(sprintf('%.2f', static::DUMMY_TAX_RATE2_PERCENTAGE), $taxRateQuery->getRate());
        $this->assertSame(static::DUMMY_TAX_RATE2_NAME, $taxRateQuery->getName());
    }

    /**
     * @return void
     */
    public function testUpdateTaxSet(): void
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $taxSetId = $this->taxFacade->createTaxSet($taxSetTransfer)->getIdTaxSet();

        $taxRate2Transfer = new TaxRateTransfer();
        $taxRate2Transfer->setName(static::DUMMY_TAX_RATE2_NAME);
        $taxRate2Transfer->setRate(static::DUMMY_TAX_RATE2_PERCENTAGE);

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->setIdTaxSet($taxSetId)->setName('Foobar');
        $taxSetTransfer->addTaxRate($taxRate2Transfer);

        $this->taxFacade->updateTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetId)->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertSame('Foobar', $taxSetQuery->getName());
        $this->assertCount(1, $taxSetQuery->getSpyTaxRates());
        $taxRateEntity = $taxSetQuery->getSpyTaxRates()[0];
        $this->assertSame(sprintf('%.2f', static::DUMMY_TAX_RATE2_PERCENTAGE), $taxRateEntity->getRate());
        $this->assertSame(static::DUMMY_TAX_RATE2_NAME, $taxRateEntity->getName());
    }

    /**
     * @return void
     */
    public function testAddTaxRateToTaxSet(): void
    {
        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($this->createTaxRateTransfer());
        $taxSetId = $this->taxFacade->createTaxSet($taxSetTransfer)->getIdTaxSet();

        $taxRate2Transfer = new TaxRateTransfer();
        $taxRate2Transfer->setName(static::DUMMY_TAX_RATE2_NAME);
        $taxRate2Transfer->setRate(static::DUMMY_TAX_RATE2_PERCENTAGE);

        $this->taxFacade->addTaxRateToTaxSet($taxSetId, $taxRate2Transfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetId)->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertCount(2, $taxSetQuery->getSpyTaxRates());
        $this->assertSame(sprintf('%.2f', static::DUMMY_TAX_RATE2_PERCENTAGE), $taxSetQuery->getSpyTaxRates()[1]->getRate());
    }

    /**
     * @return void
     */
    public function testRemoveTaxRateFromTaxSet(): void
    {
        $taxRate1Transfer = $this->createTaxRateTransfer();
        $rate1Id = $this->taxFacade->createTaxRate($taxRate1Transfer)->getIdTaxRate();

        $taxRate2Transfer = new TaxRateTransfer();
        $taxRate2Transfer->setName(static::DUMMY_TAX_RATE2_NAME);
        $taxRate2Transfer->setRate(static::DUMMY_TAX_RATE2_PERCENTAGE);
        $rate2Id = $this->taxFacade->createTaxRate($taxRate2Transfer)->getIdTaxRate();

        $taxSetTransfer = $this->createTaxSetTransfer();

        $taxSetTransfer->addTaxRate($taxRate1Transfer);
        $taxSetTransfer->addTaxRate($taxRate2Transfer);

        $taxSetId = $this->taxFacade->createTaxSet($taxSetTransfer)->getIdTaxSet();

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetId);
        $taxSetEntity = $taxSetQuery->findOne();
        $this->assertCount(2, $taxSetEntity->getSpyTaxRates());

        $this->taxFacade->removeTaxRateFromTaxSet($taxSetId, $rate2Id);

        $taxSetEntity = $taxSetQuery->findOne();
        $this->assertCount(1, $taxSetEntity->getSpyTaxRates());
        $this->assertSame($rate1Id, $taxSetEntity->getSpyTaxRates()[0]->getIdTaxRate());
    }

    /**
     * @return void
     */
    public function testExceptionRaisedIfAttemptingToUpdateNonExistentTaxRate(): void
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $taxRateTransfer->setIdTaxRate(static::NON_EXISTENT_ID);

        $this->expectException(ResourceNotFoundException::class);
        $this->taxFacade->updateTaxRate($taxRateTransfer);
    }

    /**
     * @return void
     */
    public function testExceptionRaisedIfAttemptingToRemoveTaxRateFromTaxSetWithSingleTaxRate(): void
    {
        $this->expectException(MissingTaxRateException::class);

        $taxRateTransfer = $this->createTaxRateTransfer();
        $rateId = $this->taxFacade->createTaxRate($taxRateTransfer)->getIdTaxRate();

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $taxSetId = $this->taxFacade->createTaxSet($taxSetTransfer)->getIdTaxSet();

        $this->taxFacade->removeTaxRateFromTaxSet($taxSetId, $rateId);
    }

    /**
     * @return void
     */
    public function testDeleteTaxRate(): void
    {
        $id = $this->taxFacade->createTaxRate($this->createTaxRateTransfer())->getIdTaxRate();

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($id);

        $taxRateEntity = $taxRateQuery->findOne();
        $this->assertNotEmpty($taxRateEntity);

        $this->taxFacade->deleteTaxRate($id);

        $taxRateEntity = $taxRateQuery->findOne();
        $this->assertEmpty($taxRateEntity);
    }

    /**
     * @return void
     */
    public function testDeleteTaxSetShouldDeleteSetButNotTheAssociatedRate(): void
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $rateId = $this->taxFacade->createTaxRate($taxRateTransfer)->getIdTaxRate();
        $taxRateTransfer->setIdTaxRate($rateId);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($rateId);
        $taxRateEntity = $taxRateQuery->findOne();
        $this->assertNotEmpty($taxRateEntity);

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $setId = $this->taxFacade->createTaxSet($taxSetTransfer)->getIdTaxSet();

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($setId);
        $taxSetEntity = $taxSetQuery->findOne();
        $this->assertNotEmpty($taxSetEntity);

        $this->taxFacade->deleteTaxSet($setId);

        $taxRateEntity = $taxRateQuery->findOne();
        $this->assertNotEmpty($taxRateEntity);

        $taxSetEntity = $taxSetQuery->findOne();
        $this->assertEmpty($taxSetEntity);
    }

    /**
     * @return void
     */
    public function testCreateTaxSetWithExistingTaxSetNameShouldRaiseException(): void
    {
        //Arrange
        $taxSetTransfer = (new TaxSetBuilder())->build();
        $taxSetName1 = $taxSetTransfer->getName();
        $this->taxFacade->createTaxSet($taxSetTransfer);
        $taxSetTransfer2 = (new TaxSetBuilder())->build();
        $taxSetTransfer2->setName($taxSetName1);

        //Assert
        $this->expectException(DuplicateResourceException::class);

        //Act
        $this->taxFacade->createTaxSet($taxSetTransfer2);
    }

    /**
     * @return void
     */
    public function testUpdateTaxSetWithExistingTaxSetNameShouldRaiseException(): void
    {
        //Arrange
        $taxSetTransfer = (new TaxSetBuilder())->build();
        $taxSetName1 = $taxSetTransfer->getName();
        $this->taxFacade->createTaxSet($taxSetTransfer);
        $taxSetTransfer2 = (new TaxSetBuilder())->build();
        $taxSetId2 = $this->taxFacade->createTaxSet($taxSetTransfer2)->getIdTaxSet();
        $taxSetTransfer3 = (new TaxSetBuilder())->build();
        $taxSetTransfer3->setIdTaxSet($taxSetId2)->setName($taxSetName1);

        //Assert
        $this->expectException(DuplicateResourceException::class);

        //Act
        $this->taxFacade->updateTaxSet($taxSetTransfer3);
    }
}
