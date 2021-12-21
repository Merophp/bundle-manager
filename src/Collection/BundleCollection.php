<?php
namespace Merophp\BundleManager\Collection;

use Exception;
use IteratorAggregate;
use ArrayIterator;
use Merophp\BundleManager\Bundle;

class BundleCollection implements IteratorAggregate
{
    protected array $bundles = [];

    /**
     * @param Bundle $bundle
     * @throws Exception
     */
    public function add(Bundle $bundle)
    {
        if($this->has($bundle))
            throw new Exception(sprintf('Bundle "%s" already exists in collection!', $bundle->getIdentifier()));

        $this->bundles[] = $bundle;
    }

    /**
     * @param Bundle[] $bundles
     * @throws Exception
     */
    public function addMultiple(array $bundles)
    {
        foreach($bundles as $bundle){
            $this->add($bundle);
        }
    }

    /**
     * @param Bundle $bundle
     */
    public function remove(Bundle $bundle)
    {
        unset($this->bundles[array_search($bundle, $this->bundles)]);
    }

    /**
     * @param Bundle $bundle
     * @return bool
     */
    public function has(Bundle $bundle): bool
    {
        return in_array($bundle, $this->bundles);
    }

    /**
     * @return iterable
     */
    public function getIterator(): iterable
    {
        return new ArrayIterator($this->bundles);
    }

    /**
     * @param int $state
     * @return iterable
     */
    public function getByState(int $state): iterable
    {
        foreach($this->bundles as $bundle){
            if($bundle->getState() === $state) yield $bundle;
        }
    }

    /**
     * @param string $identifier
     * @return Bundle|null
     */
    public function getByIdentifier(string $identifier): ?Bundle
    {
        foreach($this->bundles as $bundle){
            if($bundle->getIdentifier() == $identifier) return $bundle;
        }
        return null;
    }
}
