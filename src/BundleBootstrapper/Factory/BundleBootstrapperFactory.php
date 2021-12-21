<?php
namespace Merophp\BundleManager\BundleBootstrapper\Factory;

use Exception;

use Merophp\BundleManager\Bundle;
use Merophp\BundleManager\BundleBootstrapper\BundleBootstrapperInterface;

class BundleBootstrapperFactory{

    /**
     * @param Bundle $bundle
     * @return BundleBootstrapperInterface
     * @throws Exception
     */
    public function get(Bundle $bundle): BundleBootstrapperInterface
    {
		$bootstrapperClassName = '\\'.$bundle->getIdentifier().'\\Bootstrapping\\Bootstrapper';

		if(!class_exists($bootstrapperClassName))
		    throw new Exception('Bootstrapper for bundle ['.$bundle->getIdentifier().'] is not loadable!');

        if(!in_array(BundleBootstrapperInterface::class, class_implements($bootstrapperClassName)))
            throw new Exception('Bootstrapper for module ['.$bundle->getIdentifier().'] must implement '.BundleBootstrapperInterface::class.'!');

		$bootstrapper = $this->instantiateBundleBootstrapper($bootstrapperClassName);
		$bootstrapper->setConfiguration($bundle->getConfiguration());
		return $bootstrapper;
	}

	protected function instantiateBundleBootstrapper(string $bootstrapperClassName)
    {
        return new $bootstrapperClassName;
    }
}
