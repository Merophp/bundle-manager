# Introduction

Bundle manager for the merophp framework.

## Installation

Via composer:

<code>
composer require merophp/bundle-manager
</code>

## Basic Usage

<pre><code>require_once 'vendor/autoload.php';

use Merophp\BundleManager\BundleManager;

use Merophp\BundleManager\Collection\BundleCollection;
use Merophp\BundleManager\Provider\BundleProvider;
use Merophp\BundleManager\Bundle;

$collection = new BundleCollection();
$collection->addMultiple([
    new Bundle('MyOrganization\\MyBundlename'),
    new Bundle('MyOrganization\\MyBundlename2', ['myconfigKey'=>'myConfigValue']),
]);

$provider = new BundleProvider($collection);
$bundleManager = new BundleManager($provider);

$bundleManager->startRegisteredBundles();
</code></pre>

## Bundle Bootstrapping 

A bundle must have a bootstrapper class which implements <i>Merophp\BundleManager\BundleBootstrapper\BundleBootstrapperInterface</i>.
The bootstrapper class name is a compound of the bundle identifier as the namespace prefix and <i>Bootstrapping/Bootstrapper</i>. 
For the example above the fully qualified bootstrapper class name is <i>MyOrganization\MyBundlename\Bootstrapping\Bootstrapper</i>:

<pre><code>
namespace MyOrganization\MyBundlename\Bootstrapping;

use Merophp\BundleManager\BundleBootstrapperInterface;

class Bootstrapper implements BundleBootstrapperInterface
{
    public function setConfiguration(array $configuration = [])
    {}

    public function setup()
    {}

    public function tearDown()
    {}
}
</code></pre>

A bundle bootstrapper has the two lifecycle methods 'setup' and 'tearDown', which are called by the bundle manager.
'setup' will be called, when the bundle gets started and 'tearDown' will be called at the very end of the PHP execution.
