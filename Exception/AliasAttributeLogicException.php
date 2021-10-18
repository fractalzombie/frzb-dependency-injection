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

namespace FRZB\Component\DependencyInjection\Exception;

use FRZB\Component\DependencyInjection\Attribute\AsAlias;

final class AliasAttributeLogicException extends \LogicException
{
    private AsAlias $attribute;

    private function __construct(AsAlias $attribute, string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, (int) $previous?->getCode(), $previous);
        $this->attribute = $attribute;
    }

    public static function unexpected(AsAlias $attribute, ?\Throwable $previous = null): static
    {
        $message = $previous
            ? sprintf('"%s" attribute has unexpected exception: %s', AsAlias::class, $previous?->getMessage())
            : sprintf('"%s" attribute has unexpected exception', AsAlias::class);

        return new static($attribute, $message, $previous);
    }

    public static function invalidImplementation(AsAlias $attribute, \ReflectionClass $aliasClass, ?\Throwable $previous = null): static
    {
        $message = sprintf(
            'Class "%s" must implement or be subclass of "%s"',
            $attribute->getService(),
            $aliasClass->getName(),
        );

        return new static($attribute, $message, $previous);
    }

    public static function noDefinitionInContainer(AsAlias $attribute, ?\Throwable $previous = null): static
    {
        $message = sprintf('There is no definition "%s" in container', $attribute->getService());

        return new static($attribute, $message, $previous);
    }

    public function getAttribute(): AsAlias
    {
        return $this->attribute;
    }
}
