<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Pyz\Zed\Sales\Business;

use Codeception\TestCase\Test;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\Sales\Business\SalesFacade;

class SalesFacadeTest extends Test
{
    protected  $userDiscounts = true;

    /**
     * @return void
     */
    public function testSalesOrderAggregatorWithDiscountsStackShouldProvideDataFromPersistence()
    {
        $salesFacade = $this->createSalesFacade();

        $salesOrderEntity = $this->createTestOrder();

        $orderTransfer = $salesFacade->getOrderTotalsByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        $itemTransfer1 = $orderTransfer->getItems()[0];
        $itemTransfer2 = $orderTransfer->getItems()[1];

        $this->assertEquals(500, $itemTransfer1->getUnitGrossPrice());
        $this->assertEquals(800, $itemTransfer2->getUnitGrossPrice());

        $this->assertEquals(1000, $itemTransfer1->getSumGrossPrice());
        $this->assertEquals(800, $itemTransfer2->getSumGrossPrice());

        $this->assertEquals(400, $itemTransfer1->getUnitGrossPriceWithDiscounts());
        $this->assertEquals(700, $itemTransfer2->getUnitGrossPriceWithDiscounts());

        $this->assertEquals(800, $itemTransfer1->getSumGrossPriceWithDiscounts());
        $this->assertEquals(700, $itemTransfer2->getSumGrossPriceWithDiscounts());

        $this->assertEquals(67, $itemTransfer1->getUnitTaxAmountWithProductOptionAndDiscountAmounts());
        $this->assertEquals(120, $itemTransfer2->getUnitTaxAmountWithProductOptionAndDiscountAmounts());

        $this->assertEquals(840, $itemTransfer1->getSumGrossPriceWithProductOptionAndDiscountAmounts());
        $this->assertEquals(750, $itemTransfer2->getSumGrossPriceWithProductOptionAndDiscountAmounts());

        $this->assertEquals(80, $itemTransfer1->getUnitTaxAmount());
        $this->assertEquals(128, $itemTransfer2->getUnitTaxAmount());

        $this->assertEquals(160, $itemTransfer1->getSumTaxAmount());
        $this->assertEquals(128, $itemTransfer2->getSumTaxAmount());

        $this->assertEquals(840, $itemTransfer1->getRefundableAmount());
        $this->assertEquals(750, $itemTransfer2->getRefundableAmount());

        $expenseTransfer = $orderTransfer->getExpenses()[0];
        $this->assertEquals(90, $expenseTransfer->getUnitGrossPriceWithDiscounts());
        $this->assertEquals(90, $expenseTransfer->getSumGrossPriceWithDiscounts());

        $this->assertEquals(16, $expenseTransfer->getUnitTaxAmount());
        $this->assertEquals(16, $expenseTransfer->getSumTaxAmount());

        $this->assertEquals(14, $expenseTransfer->getUnitTaxAmountWithDiscounts());
        $this->assertEquals(14, $expenseTransfer->getSumTaxAmountWithDiscounts());

        $this->assertEquals(90, $expenseTransfer->getRefundableAmount());

        $calculatedDiscountTransfer = $orderTransfer->getCalculatedDiscounts()['discount1'];

        $this->assertEquals('discount1', $calculatedDiscountTransfer->getDisplayName());
        $this->assertEquals(230 + 110, $calculatedDiscountTransfer->getSumGrossAmount());

        $totalsTransfer = $orderTransfer->getTotals();
        $this->assertEquals(1920, $totalsTransfer->getSubtotal());
        $this->assertEquals(100, $totalsTransfer->getExpenseTotal());
        $this->assertEquals(340, $totalsTransfer->getDiscountTotal());
        $this->assertEquals(1680, $totalsTransfer->getGrandTotal());
        $this->assertEquals(268, $totalsTransfer->getTaxTotal()->getAmount());
        $this->assertEquals(19, $totalsTransfer->getTaxTotal()->getTaxRate());
    }

    /**
     * @return void
     */
    public function testSalesOrderAggregatorNODiscountsStackShouldProvideDataFromPersistence()
    {
        $salesFacade = $this->createSalesFacade();

        $this->userDiscounts = false;
        $salesOrderEntity = $this->createTestOrder();

        $orderTransfer = $salesFacade->getOrderTotalsByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        $itemTransfer1 = $orderTransfer->getItems()[0];
        $itemTransfer2 = $orderTransfer->getItems()[1];

        $this->assertEquals(500, $itemTransfer1->getUnitGrossPrice());
        $this->assertEquals(800, $itemTransfer2->getUnitGrossPrice());

        $this->assertEquals(1000, $itemTransfer1->getSumGrossPrice());
        $this->assertEquals(800, $itemTransfer2->getSumGrossPrice());

        $this->assertEquals(500, $itemTransfer1->getUnitGrossPriceWithDiscounts());
        $this->assertEquals(800, $itemTransfer2->getUnitGrossPriceWithDiscounts());

        $this->assertEquals(1000, $itemTransfer1->getSumGrossPriceWithDiscounts());
        $this->assertEquals(800, $itemTransfer2->getSumGrossPriceWithDiscounts());

        $this->assertEquals(1060, $itemTransfer1->getSumGrossPriceWithProductOptionAndDiscountAmounts());
        $this->assertEquals(860, $itemTransfer2->getSumGrossPriceWithProductOptionAndDiscountAmounts());

        $this->assertEquals(80, $itemTransfer1->getUnitTaxAmount());
        $this->assertEquals(128, $itemTransfer2->getUnitTaxAmount());

        $this->assertEquals(160, $itemTransfer1->getSumTaxAmount());
        $this->assertEquals(128, $itemTransfer2->getSumTaxAmount());

        $this->assertEquals(85, $itemTransfer1->getUnitTaxAmountWithProductOptionAndDiscountAmounts());
        $this->assertEquals(137, $itemTransfer2->getUnitTaxAmountWithProductOptionAndDiscountAmounts());

        $this->assertEquals(169, $itemTransfer1->getSumTaxAmountWithProductOptionAndDiscountAmounts());
        $this->assertEquals(137, $itemTransfer2->getSumTaxAmountWithProductOptionAndDiscountAmounts());

        $this->assertEquals(1060, $itemTransfer1->getRefundableAmount());
        $this->assertEquals(860, $itemTransfer2->getRefundableAmount());

        $expenseTransfer = $orderTransfer->getExpenses()[0];
        $this->assertEquals(100, $expenseTransfer->getUnitGrossPriceWithDiscounts());
        $this->assertEquals(100, $expenseTransfer->getSumGrossPriceWithDiscounts());

        $this->assertEquals(16, $expenseTransfer->getUnitTaxAmount());
        $this->assertEquals(16, $expenseTransfer->getSumTaxAmount());

        $this->assertEquals(16, $expenseTransfer->getUnitTaxAmountWithDiscounts());
        $this->assertEquals(16, $expenseTransfer->getSumTaxAmountWithDiscounts());

        $this->assertEquals(100, $expenseTransfer->getRefundableAmount());

        $totalsTransfer = $orderTransfer->getTotals();
        $this->assertEquals(1920, $totalsTransfer->getSubtotal());
        $this->assertEquals(100, $totalsTransfer->getExpenseTotal());
        $this->assertEquals(0, $totalsTransfer->getDiscountTotal());
        $this->assertEquals(2020, $totalsTransfer->getGrandTotal());
        $this->assertEquals(323, $totalsTransfer->getTaxTotal()->getAmount());
        $this->assertEquals(19, $totalsTransfer->getTaxTotal()->getTaxRate());
    }


    /**
     * @return SpySalesOrder
     */
    protected function createTestOrder()
    {
        //Data like shipment or state machine is not important in this test so take any first row.
        $salesOrderAddress = new SpySalesOrderAddress();
        $salesOrderAddress->setAddress1(1);
        $salesOrderAddress->setAddress2(2);
        $salesOrderAddress->setSalutation('Mr');
        $salesOrderAddress->setCellPhone('123456789');
        $salesOrderAddress->setCity('City');
        $salesOrderAddress->setCreatedAt(new \DateTime());
        $salesOrderAddress->setUpdatedAt(new \DateTime());
        $salesOrderAddress->setComment('comment');
        $salesOrderAddress->setDescription('describtion');
        $salesOrderAddress->setCompany('company');
        $salesOrderAddress->setFirstName('First name');
        $salesOrderAddress->setLastName('Last Name');
        $salesOrderAddress->setFkCountry(1);
        $salesOrderAddress->setEmail('email');
        $salesOrderAddress->setZipCode(10405);
        $salesOrderAddress->save();

        $shipmentMethod = SpyShipmentMethodQuery::create()->findOne();

        $omsState = new SpyOmsOrderItemState();
        $omsState->setName('test');
        $omsState->save();

        $salesOrder = new SpySalesOrder();
        $salesOrder->setBillingAddress($salesOrderAddress);
        $salesOrder->setShippingAddress(clone $salesOrderAddress);
        $salesOrder->setShipmentMethod($shipmentMethod);
        $salesOrder->setOrderReference('123');
        $salesOrder->save();

        $salesOrderItem1 = $this->createOrderItem(
            $omsState,
            $salesOrder,
            2,
            500,
            19,
            100,
            'discount1',
            [
                [
                    'gross_price' => 10,
                    'tax_rate' => 19,
                    'discounts' => [
                        [
                            'amount' => 5,
                            'name' => 'discount1'
                        ]
                    ]
                ],
                [
                    'gross_price' => 20,
                    'tax_rate' => 19,
                    'discounts' => [
                        [
                            'amount' => 5,
                            'name' => 'discount1'
                        ]
                    ]
                ]
            ]
        );
        $salesOrderItem2 = $this->createOrderItem(
            $omsState,
            $salesOrder,
            1,
            800,
            19,
            100,
            'discount1',
            [
                [
                    'gross_price' => 20,
                    'tax_rate' => 19,
                    'discounts' => [
                        [
                            'amount' => 5,
                            'name' => 'discount1'
                        ]
                    ]
                ],
                [
                    'gross_price' => 40,
                    'tax_rate' => 19,
                    'discounts' => [
                        [
                            'amount' => 5,
                            'name' => 'discount1'
                        ]
                    ]

                ]
            ]
        );

        $salesExpense = new SpySalesExpense();
        $salesExpense->setName('shiping test');
        $salesExpense->setTaxRate(19);
        $salesExpense->setGrossPrice(100);
        $salesExpense->setFkSalesOrder($salesOrder->getIdSalesOrder());
        $salesExpense->save();

        $this->createSalesDiscount(
            10,
            'discount1',
            $salesOrder->getIdSalesOrder(),
            null,
            $salesExpense->getIdSalesExpense()
        );

        return $salesOrder;
    }
    
    
    /**
     * @return SalesFacade
     */
    protected function createSalesFacade()
    {
        return new SalesFacade();
    }

    /**
     * @param SpyOmsOrderItemState $omsState
     * @param SpySalesOrder $salesOrder
     * @param $quantity
     * @param $grossPrice
     * @param $taxRate
     * @param $discountAmount
     * @param $discountName
     * @param array $options
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return SpySalesOrderItem
     */
    protected function createOrderItem(
        SpyOmsOrderItemState $omsState,
        SpySalesOrder $salesOrder,
        $quantity,
        $grossPrice,
        $taxRate,
        $discountAmount,
        $discountName,
        array $options = []
    ) {
        $salesOrderItem = new SpySalesOrderItem();
        $salesOrderItem->setGrossPrice($grossPrice);
        $salesOrderItem->setQuantity($quantity);
        $salesOrderItem->setSku('123');
        $salesOrderItem->setName('test1');
        $salesOrderItem->setTaxRate($taxRate);
        $salesOrderItem->setFkOmsOrderItemState($omsState->getIdOmsOrderItemState());
        $salesOrderItem->setFkSalesOrder($salesOrder->getIdSalesOrder());
        $salesOrderItem->save();

        $this->createSalesDiscount(
            $discountAmount,
            $discountName,
            $salesOrder->getIdSalesOrder(),
            $salesOrderItem->getIdSalesOrderItem()
        );

        foreach ($options as $option) {
            $salesOrderItemOption = new SpySalesOrderItemOption();
            $salesOrderItemOption->setFkSalesOrderItem($salesOrderItem->getIdSalesOrderItem());
            $salesOrderItemOption->setGrossPrice($option['gross_price']);
            $salesOrderItemOption->setTaxRate($option['tax_rate']);
            $salesOrderItemOption->setLabelOptionType('label1');
            $salesOrderItemOption->setLabelOptionValue('value1');
            $salesOrderItemOption->save();
            if (isset($option['discounts'])) {
                foreach ($option['discounts'] as $discount) {
                    $this->createSalesDiscount(
                        $discount['amount'],
                        $discount['name'],
                        $salesOrder->getIdSalesOrder(),
                        $salesOrderItem->getIdSalesOrderItem(),
                        null,
                        $salesOrderItemOption->getIdSalesOrderItemOption()
                    );
                }
            }
        }

        return $salesOrderItem;
    }

    /**
     * @param int $amount
     * @param int $name
     */
    protected function createSalesDiscount(
        $amount,
        $name,
        $idOrder,
        $idOrderItem = null,
        $idExpense = null,
        $idOrderItemOption = null
    ) {
        if ($this->userDiscounts === false) {
            return;
        }

        $spySalesDiscount = new SpySalesDiscount();
        $spySalesDiscount->setName('name');
        $spySalesDiscount->setFkSalesOrder($idOrder);
        $spySalesDiscount->setFkSalesOrderItem($idOrderItem);
        $spySalesDiscount->setFkSalesExpense($idExpense);
        $spySalesDiscount->setFkSalesOrderItemOption($idOrderItemOption);
        $spySalesDiscount->setDisplayName($name);
        $spySalesDiscount->setAmount($amount);
        $spySalesDiscount->save();
    }
}
