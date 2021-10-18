<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Attribute;

/*
 * This is package for Symfony framework.
 *
 * (c) Mykhailo Shtanko <fractalzombie@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsDeprecated
{
    public const DEFAULT_DEPRECATION_TEMPLATE = 'The "%service_id%" service is deprecated. You should stop using it, as it will be removed in the future.';

    /**
     * @param string $message the deprecation template must contain the "%service_id%" placeholder
     */
    public function __construct(
        private string $package,
        private string $version,
        private string $message = self::DEFAULT_DEPRECATION_TEMPLATE,
    ) {
    }

    public function getPackage(): string
    {
        return $this->package;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
