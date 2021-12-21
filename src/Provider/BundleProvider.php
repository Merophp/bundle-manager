<?php
namespace Merophp\BundleManager\Provider;

use Merophp\BundleManager\Bundle;
use Merophp\BundleManager\Collection\BundleCollection;

class BundleProvider implements BundleProviderInterface
{
    /**
     * @var BundleCollection
     */
    protected BundleCollection $bundleCollection;

    /**
     * @param BundleCollection $bundleCollection
     */
    public function __construct(BundleCollection $bundleCollection)
    {
        $this->bundleCollection = $bundleCollection;
    }

    /**
     * @param int $state
     * @return iterable
     */
    public function getBundlesByState(int $state): iterable
    {
        yield from $this->bundleCollection->getByState($state);
    }

    /**
     * @param string $identifier
     * @return Bundle|null
     */
    public function getBundleByIdentifier(string $identifier): ?Bundle
    {
        return $this->bundleCollection->getByIdentifier($identifier);
    }
}
