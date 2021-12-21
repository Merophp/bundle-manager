<?php

namespace Merophp\BundleManager\Provider;

use Merophp\BundleManager\Bundle;

interface BundleProviderInterface
{
    public function getBundlesByState(int $state): iterable;

    public function getBundleByIdentifier(string $identifier): ?Bundle;
}
