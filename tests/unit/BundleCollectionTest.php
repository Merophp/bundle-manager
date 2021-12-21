<?php

use Merophp\BundleManager\Bundle;
use Merophp\BundleManager\Collection\BundleCollection;
use Merophp\BundleManager\Factory\BundleBootstrapperFactory;
use Merophp\BundleManager\Provider\BundleProvider;
use PHPUnit\Framework\TestCase;
use Merophp\BundleManager\BundleManager;
use Merophp\BundleManager\BundleBootstrapperInterface;

/**
 * @covers \Merophp\BundleManager\Collection\BundleCollection
 */
final class BundleCollectionTest extends TestCase
{
    /**
     * @var BundleCollection
     */
    protected static BundleCollection $bundleCollectionInstance;

    /**
     * @var Bundle
     */
    protected static Bundle $testBundleA;

    /**
     * @var Bundle
     */
    protected static Bundle $testBundleB;

    public static function setUpBeforeClass():void
    {
        self::$testBundleA = new Bundle('MyOrganization\\MyBundlename');
        self::$testBundleB = new Bundle('MyOrganization\\MyBundlename2', ['myconfigKey'=>'myConfigValue']);

        self::$bundleCollectionInstance = new BundleCollection();
    }

    public function testAddMultiple(){
        self::$bundleCollectionInstance->addMultiple([
            self::$testBundleA,
            self::$testBundleB,
        ]);

        $this->assertTrue(self::$bundleCollectionInstance->has(self::$testBundleA));
        $this->assertTrue(self::$bundleCollectionInstance->has(self::$testBundleB));

        $this->expectException(Exception::class);
        self::$bundleCollectionInstance->addMultiple([
            self::$testBundleA
        ]);
    }

    public function testGetByState(){
        self::$testBundleA->setState(2);
        $this->assertIsIterable(self::$bundleCollectionInstance->getByState(1));
        foreach(self::$bundleCollectionInstance->getByState(1) as $bundle){
            $this->assertEquals(1, $bundle->getState());
        }
    }

    public function testGetAll(){
        $this->assertIsIterable(self::$bundleCollectionInstance->getAll());
    }

    public function testGetByIdentifier(){
        $this->assertEquals(self::$testBundleA, self::$bundleCollectionInstance->getByIdentifier(self::$testBundleA->getIdentifier()));
    }

    public function testRemove(){
        self::$bundleCollectionInstance->remove(self::$testBundleB);
        $this->assertFalse(self::$bundleCollectionInstance->has(self::$testBundleB));
    }
}
