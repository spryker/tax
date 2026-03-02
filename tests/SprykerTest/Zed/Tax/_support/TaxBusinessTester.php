<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 */
class TaxBusinessTester extends Actor
{
    use _generated\TaxBusinessTesterActions;

    /**
     * @var array
     */
    public const COUNTRIES = ['DE', 'AT'];

    /**
     * @var int
     */
    public const DEFAULT_QUANTITY = 1;

    /**
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    public function sumTaxAmount(CalculableObjectTransfer $calculableObjectTransfer): int
    {
        $items = $calculableObjectTransfer->getItems()->getArrayCopy();

        return array_reduce($items, function (?int $total, ItemTransfer $itemTransfer) {
            $total += $itemTransfer->getSumTaxAmount();

            return $total;
        });
    }

    protected function createItemTransfer(float $taxRate, int $price, int $sumPrice): ItemTransfer
    {
        $itemTransfer = (new ItemTransfer())
            ->setTaxRate($taxRate)
            ->setUnitNetPrice($price)
            ->setSumNetPrice($sumPrice)
            ->setUnitPrice($price)
            ->setSumPrice($sumPrice)
            ->setOriginUnitNetPrice($price);

        return $itemTransfer;
    }

    public function createItemTransferCollection(float $taxRate, int $price, int $sumPrice, int $quantity = 1): array
    {
        $items = [];

        while ($quantity--) {
            $items[] = $this->createItemTransfer($taxRate, $price, $sumPrice);
        }

        return $items;
    }

    public function createCalculableObjectTransfer(array $itemTransferCollection): CalculableObjectTransfer
    {
        $calculableObjectTransferMock = (new CalculableObjectTransfer())
            ->setPriceMode(static::PRICE_MODE_NET)
            ->setItems(new ArrayObject($itemTransferCollection));

        return $calculableObjectTransferMock;
    }
}
