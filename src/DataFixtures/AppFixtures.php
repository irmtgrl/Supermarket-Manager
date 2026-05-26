<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\InventoryItem;
use App\Entity\Supplier;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\CustomerOrder;
use App\Entity\CustomerOrderItem;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        //================================================
        //  CATEGORIES
        //================================================

        $categoryNames = [
            "Dairy",
            "Bakery",
            "Frozen Foods",
            "Drinks",
            "Snacks",
            "Vegetables",
            "Fruits",
            "Meat"
        ];

        $categories = [];

        foreach($categoryNames as $categoryName)
        {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);

            $categories[] = $category;
        }

        //================================================
        //  SUPPLIERS
        //================================================
        
        $suppliers = [];

        for($i = 0; $i < 5; $i++) 
        {
            $supplier = new Supplier();

            $supplier->setName($faker->company());
            $supplier->setEmail($faker->companyEmail());
            $supplier->setPhone($faker->phoneNumber());

            $manager->persist($supplier);

            $suppliers[] = $supplier;
        }

        //================================================
        //  PRODUCTS
        //================================================

        $products = [];

        for($i = 0; $i < 5; $i++)
        {
            $product = new Product();

            $product->setName(
                ucfirst($faker->unique()->word())
            );
            $product->setBarcode(
                $faker->ean13()
            );
            $product->setDescription(
                $faker->sentence(12)
            );
            $product->setPrice(
                $faker->randomFloat(2, 1, 100)
            );
            $product->setCategory(
                $faker->randomElement($categories)
            );

            $manager->persist($product);

            $products[] = $product;
        }

        //================================================
        //  PURCHASES
        //================================================

        for($i = 0; $i < 20; $i++)
        {
            $purchase = new Purchase();

            $purchase->setSupplier(
                $faker->randomElement($suppliers)
            );
            $purchase->setPurchasedAt(
                \DateTimeImmutable::createFromMutable(
                    $faker->dateTimeBetween("-3 months", "now")
                )
            );

            $manager->persist($purchase);

            for($j = 0; $j < rand(2, 6); $j++)
            {
                $purchaseItem = new PurchaseItem();
                
                $purchaseItem->setPurchase($purchase);
                $purchaseItem->setProduct(
                    $faker->randomElement($products)
                );
                $purchaseItem->setQuantity(
                    rand(10, 50)
                );
                $purchaseItem->setUnitPrice(
                    $faker->randomFloat(2, 1, 50)
                );

                $manager->persist($purchaseItem);
            }
        }

        //================================================
        //  CUSTOMER ORDERS
        //================================================

        for($i = 0; $i < 30; $i++)
        {
            $order = new CustomerOrder();

            $order->setTotalPrice(
                $faker->randomFloat(2, 10, 300)
            );
            $order->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $faker->dateTimeBetween('-1 month', 'now')
                )
            );

            $manager->persist($order);

            for ($j = 0; $j < rand(1, 5); $j++) {

                $orderItem = new CustomerOrderItem();

                $orderItem->setCustomerOrder($order);
                $orderItem->setProduct(
                    $faker->randomElement($products)
                );
                $orderItem->setQuantity(
                    rand(1, 5)
                );
                $orderItem->setPrice(
                    $faker->randomFloat(2, 1, 100)
                );

                $manager->persist($orderItem);
            }
        }

        $manager->flush();
    }
}
