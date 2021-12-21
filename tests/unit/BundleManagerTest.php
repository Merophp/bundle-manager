<?php

use Merophp\BundleManager\Bundle;
use Merophp\BundleManager\Collection\BundleCollection;
use Merophp\BundleManager\BundleBootstrapper\Factory\BundleBootstrapperFactory;
use Merophp\BundleManager\Provider\BundleProvider;
use PHPUnit\Framework\TestCase;
use Merophp\BundleManager\BundleManager;
use Merophp\BundleManager\BundleBootstrapper\BundleBootstrapperInterface;

/**
 * @covers \Merophp\BundleManager\BundleManager
 */
final class BundleManagerTest extends TestCase
{
    /**
     * @var BundleManager
     */
    protected static BundleManager $bundleManagerInstance;

    /**
     * @var Bundle
     */
    protected static Bundle $testBundleA;

    /**
     * @var Bundle
     */
    protected static Bundle $testBundleB;

    public static function setUpBeforeClass():void{

        self::$testBundleA = new Bundle('MyOrganization\\MyBundlename');
        self::$testBundleB = new Bundle('MyOrganization\\MyBundlename2', ['myconfigKey'=>'myConfigValue']);

        $bundleBootstrapperMock = Mockery::mock(BundleBootstrapperInterface::class);
        $bundleBootstrapperMock->shouldReceive('setup')->andReturns();
        $bundleBootstrapperMock->shouldReceive('tearDown')->andReturns();
        $bundleBootstrapperMock->shouldReceive('setConfiguration')->andReturns();

        $bundleBootstrapperFactoryMock = Mockery::mock(BundleBootstrapperFactory::class);
        $bundleBootstrapperFactoryMock->shouldReceive('get')->with(self::$testBundleA)->andReturns($bundleBootstrapperMock);
        $bundleBootstrapperFactoryMock->shouldReceive('get')->with(self::$testBundleB)->andReturns($bundleBootstrapperMock);

        $collection = new BundleCollection();
        $collection->addMultiple([
            self::$testBundleA,
            self::$testBundleB,
        ]);

        $provider = new BundleProvider($collection);

        self::$bundleManagerInstance = new BundleManager($provider);
        self::$bundleManagerInstance->setBundleBootstrapperFactory($bundleBootstrapperFactoryMock);
    }

    public function testStartRegisteredBundles(){
        $this->assertEquals(Bundle::STATE_REGISTERED, self::$testBundleA->getState());
        self::$bundleManagerInstance->startRegisteredBundles();
        $this->assertEquals(Bundle::STATE_STARTED, self::$testBundleA->getState());
        $this->assertIsObject(self::$testBundleA->getBootstrapper());
    }

    public function testTearDownStartedBundles(){
        self::$bundleManagerInstance->tearDownStartedBundles();
        $this->assertEquals(Bundle::STATE_TEARED_DOWN, self::$testBundleA->getState());
    }
}
