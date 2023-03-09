<?php

declare(strict_types=1);

/*
 * This is package for Symfony framework.
 *
 * (c) Mykhailo Shtanko <fractalzombie@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Attribute\AsTagged;

/** @internal */
#[AsService(tags: [new AsTagged(AnotherServiceInterface::class)])]
class AnotherService implements AnotherServiceInterface
{
    public const SOMETHING_VALUE = 'something';

    private ServiceInterface $serviceWithArgument;

    public function __construct(ServiceInterface $serviceWithArgument)
    {
        $this->serviceWithArgument = $serviceWithArgument;
    }

    public function getServiceWithArgument(): ServiceInterface
    {
        return $this->serviceWithArgument;
    }

    public function getSomething(): string
    {
        return self::SOMETHING_VALUE;
    }
}
