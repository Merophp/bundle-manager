<?php
namespace Merophp\BundleManager;

use Merophp\BundleManager\BundleBootstrapper\BundleBootstrapperInterface;

class Bundle
{
    const STATE_REGISTERED = 1;
    const STATE_STARTED = 2;
    const STATE_TEARED_DOWN = 3;

    /**
     * identifier
     */
    protected string $identifier = '';

    /**
     * @var int
     */
    public int $state = 1;

    /**
     * configuration
     */
    protected array $configuration = [];

    /**
     * @var ?BundleBootstrapperInterface
     */
    protected ?BundleBootstrapperInterface $bootstrapper = null;

    /**
     * @param string $identifier
     * @param array $configuration
     */
    public function __construct(string $identifier, array $configuration = [])
    {
        $this->identifier = $identifier;
        $this->configuration = $configuration;
    }

    /**
     * set identifier
     */
    public function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * get identifier
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param int $state
     */
    public function setState(int $state): void
    {
        $this->state = $state;
    }

    /**
     *
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     *
     */
    public function setBootstrapper(BundleBootstrapperInterface $bootstrapper)
    {
        $this->bootstrapper = $bootstrapper;
    }

    /**
     *
     */
    public function getBootstrapper(): ?BundleBootstrapperInterface
    {
        return $this->bootstrapper;
    }

    /**
     * set configuration
     */
    public function setConfiguration($configuration = []): void
    {
        $this->configuration = $configuration;
    }

    /**
     * get type
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
