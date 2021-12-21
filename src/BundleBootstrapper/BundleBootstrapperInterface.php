<?php
namespace Merophp\BundleManager\BundleBootstrapper;

/**
 * @author Robert Becker
 */
interface BundleBootstrapperInterface{
	public function setup();
	public function tearDown();
	public function setConfiguration(array $configuration = []);
}
