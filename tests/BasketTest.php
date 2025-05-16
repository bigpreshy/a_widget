<?php

namespace AcmeWidgetCo\Tests;

use AcmeWidgetCo\Basket;
use PHPUnit\Framework\TestCase;
use AcmeWidgetCo\Exceptions\NotFoundException;

class BasketTest extends TestCase
{
    private Basket $basket;

    public function setUp(): void
    {
        $this->basket = resolve(Basket::class);
    }

    public function testAddingTwoProductsCalculatesCorrectTotal(): void
    {
        $this->basket->add('B01');
        $this->basket->add('G01');
        $this->assertEquals(3785, $this->basket->total());
    }

    public function testAddingTwoSameProductsCalculatesCorrectTotal(): void
    {
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->assertEquals(5437, $this->basket->total());
    }

    public function testAddingMultipleProductsCalculatesCorrectTotal(): void
    {
        $this->basket->add('R01');
        $this->basket->add('G01');
        $this->assertEquals(6085, $this->basket->total());
    }

    public function testAddingSeveralProductsCalculatesCorrectTotal(): void
    {
        $this->basket->add('B01');

        $this->basket->add('B01');
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->assertEquals(9827, $this->basket->total());
    }

    public function testAddingNonExistentProductThrowsException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->basket->add('X99');
    }

    public function testEmptyBasketReturnsZeroTotal(): void
    {
        $this->assertEquals(0, $this->basket->total());
    }

    public function testBasketTotalUnder50GetsHighestDeliveryCharge(): void
    {
        $this->basket->add('B01'); // 7.95
        // Item total = 7.95. Delivery (<50) = 4.95. Final = 12.90
        $this->assertEquals(1290, $this->basket->total());
    }

    public function testBasketTotalJustUnder50GetsHighestDeliveryCharge(): void
    {
        $this->basket->add('G01'); // 24.95
        $this->basket->add('G01'); // 24.95
        // Item total = 49.90. Delivery (<50) = 4.95. Final = 54.85
        $this->assertEquals(5485, $this->basket->total());
    }

    public function testBasketTotalBetween50And90GetsMediumDeliveryChargeWithOffer(): void
    {
        $this->basket->add('R01'); // 32.95
        $this->basket->add('R01'); // 32.95, 2nd half price (discount 16.48)
        $this->basket->add('R01'); // 32.95
        // Subtotal = 32.95*3 = 98.85. Discount = 16.48. Item total = 82.37.
        // Delivery (50-90) = 2.95. Final = 85.32
        $this->assertEquals(8532, $this->basket->total());
    }

    public function testBasketTotalOver90GetsNoDeliveryChargeWithOffer(): void
    {
        $this->basket->add('R01'); // 32.95
        $this->basket->add('R01'); // 32.95, 2nd half price (discount 16.48)
        $this->basket->add('R01'); // 32.95
        $this->basket->add('B01'); // 7.95
        // Subtotal before discount = (32.95*3) + 7.95 = 98.85 + 7.95 = 106.80
        // Discount = 16.48. Item total = 106.80 - 16.48 = 90.32.
        // Delivery (>=90) = 0.00. Final = 90.32
        $this->assertEquals(9032, $this->basket->total());
    }

    public function testMultiplePairsOfSpecialOfferProductGetMultipleDiscounts(): void
    {
        $this->basket->add('R01'); // 32.95
        $this->basket->add('R01'); // 32.95, 2nd half price (discount 16.48)
        $this->basket->add('R01'); // 32.95
        $this->basket->add('R01'); // 32.95, 4th half price (discount 16.48)
        // Subtotal = 32.95*4 = 131.80. Discounts = 16.48 * 2 = 32.96.
        // Item total = 131.80 - 32.96 = 98.84.
        // Delivery (>=90) = 0.00. Final = 98.84
        $this->assertEquals(9884, $this->basket->total());
    }

    public function testOfferWithMixedItemsAndMidTierDelivery(): void
    {
        // Two Red Widgets (R01):
        // First R01: 3295 cents
        // Second R01 (half price due to offer): floor(3295 / 2) = 1647 cents
        // Subtotal for R01s with offer: 3295 + 1647 = 4942 cents.
        // One Blue Widget (B01): 795 cents.
        // Total Subtotal (after offer, before delivery): 4942 + 795 = 5737 cents.
        // Delivery Charge (subtotal 5737 is >= 5000 and < 9000): 295 cents.
        // Expected Final Total: 5737 + 295 = 6032 cents.

        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->basket->add('B01');
        $this->assertEquals(6032, $this->basket->total());
    }
}
