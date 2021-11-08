<?php

declare(strict_types=1);

namespace App\Tests\Domain\Product;

use App\Domain\Product\Event\ProductCreated;
use App\Domain\Product\Event\ProductNameChanged;
use App\Domain\Product\Event\ProductPriceChanged;
use App\Domain\Product\Product;
use App\Domain\Product\ValueObject\Info;
use App\Domain\Product\ValueObject\Money;
use App\Domain\Product\ValueObject\Status;
use App\Domain\SharedKernel\Exception\DomainConsistencyException;
use Money\Currency as LibCurrency;
use Money\Money as LibMoney;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductTest extends KernelTestCase
{
    public function testCreateCorrectDomainModel()
    {
        $product = $this->createProductDomain();
        $product->create();

        $this->assertEquals('test name', $product->getInfo()->getName());
        $this->assertEquals('test description', $product->getInfo()->getDescription());
        $this->assertEquals(99.99, $product->getMoney()->getAmount());
        $this->assertEquals('PLN', $product->getMoney()->getCurrencyCode());
        $this->assertTrue($product->getStatus()->isActive());

        $this->assertContainsOnlyInstancesOf(ProductCreated::class, $product->getDomainEvents());
    }

    public function testChangeProductNameWithCorrectValue()
    {
        $product = $this->createProductDomain();
        $product->changeName('changed name');

        $this->assertEquals('changed name', $product->getInfo()->getName());
        $this->assertContainsOnlyInstancesOf(ProductNameChanged::class, $product->getDomainEvents());
    }

    public function testChangeProductPriceWithCorrectValue()
    {
        $product = $this->createProductDomain();
        $product->changePrice(new LibMoney(10000, new LibCurrency('PLN')));

        $this->assertEquals(100.0, $product->getMoney()->getAmount());
        $this->assertEquals('PLN', $product->getMoney()->getCurrencyCode());
        $this->assertContainsOnlyInstancesOf(ProductPriceChanged::class, $product->getDomainEvents());
    }

    public function testChangeProductPriceWithWrongCurrencyCodeShouldThrowException()
    {
        $this->expectException(DomainConsistencyException::class);
        $this->expectExceptionMessage('Product currency must be one of following: PLN');

        $product = $this->createProductDomain();

        $product->changePrice(new LibMoney(10000, new LibCurrency('USD')));
    }

    public function testRemoveProductWithCorrectState()
    {
        $product = $this->createProductDomain();
        $product->remove();

        $this->assertFalse($product->getStatus()->isActive());
    }

    public function testRemoveProductWithWrongStateShouldThrowException()
    {
        $this->expectException(DomainConsistencyException::class);
        $this->expectExceptionMessage('Removed product cannot be removed');

        $product = $this->createProductDomain();
        $product->remove();

        $product->remove(); // second remove should throw exception
    }

    public function testCreateProductAndChangeNameAndPriceShouldCreateFewDomainEvents()
    {
        $product = $this->createProductDomain();

        $product->create();
        $product->changeName('changed name');
        $product->changePrice(new LibMoney(10000, new LibCurrency('PLN')));

        $this->assertEquals('changed name', $product->getInfo()->getName());
        $this->assertEquals(100.0, $product->getMoney()->getAmount());
        $this->assertEquals('PLN', $product->getMoney()->getCurrencyCode());

        $events = $product->getDomainEvents();

        $this->assertCount(3, $events);

        $this->assertInstanceOf(ProductCreated::class, $events[0]);
        $this->assertInstanceOf(ProductNameChanged::class, $events[1]);
        $this->assertInstanceOf(ProductPriceChanged::class, $events[2]);
    }

    private function createProductDomain(): Product
    {
        return new Product(
            'test uuid',
            new Info('test name', 'test description'),
            new Money(99.99, 'PLN'),
            new Status(true)
        );
    }
}