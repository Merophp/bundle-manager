<?php
namespace Merophp\BundleManager;

use Merophp\BundleManager\Exception\{
    BundleAlreadyStartedException,
    BundleAlreadyTearedDownException,
    BundleNotEvenStartedException
};

use Merophp\BundleManager\BundleBootstrapper\Factory\BundleBootstrapperFactory;
use Merophp\BundleManager\Provider\BundleProviderInterface;

/**
 * @author Robert Becker
 */
class BundleManager{

	/**
	 * @var BundleProviderInterface
	 */
	protected BundleProviderInterface $bundleProvider;

	/**
	 * @var BundleBootstrapperFactory
	 */
	protected BundleBootstrapperFactory $bundleBootstrapperFactory;

    /**
     * @param BundleProviderInterface $bundleProvider
     */
	public function __construct(BundleProviderInterface $bundleProvider)
    {
        $this->bundleBootstrapperFactory = new BundleBootstrapperFactory();
        $this->bundleProvider = $bundleProvider;
        register_shutdown_function([$this, 'tearDownStartedBundles']);
	}

    /**
     * @param BundleBootstrapperFactory $bundleBootstrapperFactory
     */
    public function setBundleBootstrapperFactory(BundleBootstrapperFactory $bundleBootstrapperFactory)
    {
        $this->bundleBootstrapperFactory = $bundleBootstrapperFactory;
    }

	/**
	 * Start all registered bundles.
     * @param ?callable $onBundleStartedCallback Callback to call every time a bundle started
	 */
	 public function startRegisteredBundles(callable $onBundleStartedCallback = null): void
     {
		while($bundle = $this->getNextBundleToStart()){
			$this->startBundle($bundle);
			if($onBundleStartedCallback)
			    $onBundleStartedCallback($bundle);
		}
	}

    /**
     * @return Bundle|null
     */
	protected function getNextBundleToStart(): ?Bundle
    {
 		foreach($this->bundleProvider->getBundlesByState(Bundle::STATE_REGISTERED) as $bundle){
 			return $bundle;
 		}
 		return null;
 	}

    /**
     * @param Bundle $bundle
     * @throws BundleAlreadyStartedException
     */
    protected function startBundle(Bundle $bundle): void
    {
        if($bundle->getState() == Bundle::STATE_STARTED)
            throw new BundleAlreadyStartedException(sprintf('Bundle "%s" already started.', $bundle->getIdentifier()));

        $bundle->setBootstrapper($this->bundleBootstrapperFactory->get($bundle));
        $bundle->getBootstrapper()->setup();
        $bundle->setState(Bundle::STATE_STARTED);
	}

    /**
     * Tear down given bundle.
     *
     * @param Bundle $bundle
     * @throws BundleNotEvenStartedException
     * @throws BundleAlreadyTearedDownException
     */
    protected function tearDownBundle(Bundle $bundle): void
    {
        if($bundle->getState() === Bundle::STATE_TEARED_DOWN)
            throw new BundleAlreadyTearedDownException(sprintf('Bundle "%s" already teared down.', $bundle->getIdentifier()));

        if($bundle->getState() !== Bundle::STATE_STARTED)
            throw new BundleNotEvenStartedException(sprintf('Bundle "%s" not even started.', $bundle->getIdentifier()));


        $bundle->getBootstrapper()->tearDown();
        $bundle->setState(Bundle::STATE_TEARED_DOWN);
	}

	/**
	 * Tear down all started bundles.
     * @param ?callable $onBundleTearedDownCallback Callback to call every time a bundle teared down
	 */
	public function tearDownStartedBundles(callable $onBundleTearedDownCallback = null): void
    {
        foreach($this->bundleProvider->getBundlesByState(Bundle::STATE_STARTED) as $bundle){
            $this->tearDownBundle($bundle);
            if($onBundleTearedDownCallback)
                $onBundleTearedDownCallback($bundle);
        }
	}
}
