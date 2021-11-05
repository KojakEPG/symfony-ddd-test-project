<?php

declare(strict_types=1);

namespace App\Infrastructure\SharedKernel\Migration\Fixtures;

use App\Application\UseCase\Command\Product\Create\CreateCommand;
use App\Infrastructure\SharedKernel\MessageBus\Command\CommandBus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ProductsFixtures implements FixtureInterface
{
    private CommandBus $commandBus;

    private array $productsData = [
        [
            'name' => 'The Godfather',
            'description' => 'The Godfather - product description',
            'amount' => 59.99,
            'currency' => 'PLN',
        ],
        [
            'name' => 'Steve Jobs',
            'description' => 'Steve Jobs - product description',
            'amount' => 49.95,
            'currency' => 'PLN',
        ],
        [
            'name' => 'The Return of Sherlock Holmes',
            'description' => 'The Return of Sherlock Holmes - product description',
            'amount' => 39.99,
            'currency' => 'PLN',
        ],
        [
            'name' => 'The Little Prince',
            'description' => 'The Little Prince - product description',
            'amount' => 29.99,
            'currency' => 'PLN',
        ],
        [
            'name' => 'I Hate Myselfie!',
            'description' => 'I Hate Myselfie! - product description',
            'amount' => 19.99,
            'currency' => 'PLN',
        ],
        [
            'name' => 'The Trial',
            'description' => 'The Trial - product description',
            'amount' => 9.99,
            'currency' => 'PLN',
        ],
    ];

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->productsData as $product) {
            $createProductCommand = new CreateCommand(
                (string)(Uuid::uuid4()),
                (string)$product['name'],
                (string)$product['description'],
                (float)$product['amount'],
                (string)$product['currency'],
            );

            $this->commandBus->handle($createProductCommand);
        }
    }
}