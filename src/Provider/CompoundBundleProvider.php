<?php
namespace Merophp\BundleManager\Provider;

use Merophp\BundleManager\Bundle;
use Merophp\BundleManager\Collection\BundleCollection;

class CompoundBundleProvider implements BundleProviderInterface
{
    /**
     * @var BundleProviderInterface[]
     */
    protected array $bundleProviders = [];

    /**
     * @param BundleProviderInterface $bundleProvider
     */
    public function attach(BundleProviderInterface $bundleProvider)
    {
        $this->bundleProviders[] = $bundleProvider;
    }

    /**
     * @param int $state
     * @return iterable
     */
    public function getBundlesByState(int $state): iterable
    {
        foreach($this->bundleProviders as $provider){
            yield from $provider->getBundlesByState($state);
        }
    }

    /**
     * @param string $identifier
     * @return Bundle|null
     */
    public function getBundleByIdentifier(string $identifier): ?Bundle
    {
        foreach($this->bundleProviders as $provider){
            if($provider->getBundleByIdentifier($identifier))
                return $provider->getBundleByIdentifier($identifier);
        }
    }
}
