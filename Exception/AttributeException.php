<?php

declare(strict_types=1);

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 *
 * Copyright (c) 2024 Mykhailo Shtanko fractalzombie@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE.MD
 * file that was distributed with this source code.
 */

namespace FRZB\Component\DependencyInjection\Exception;

use FRZB\Component\DependencyInjection\Attribute\AsAlias;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class AttributeException extends \LogicException
{
    private function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, (int) $previous?->getCode(), $previous);
    }

    public static function unexpected(AsAlias|AsService $attribute, ?\Throwable $previous = null): self
    {
        $message = $previous
            ? sprintf('"%s" attribute has unexpected exception: %s', $attribute::class, $previous?->getMessage())
            : sprintf('"%s" attribute has unexpected exception', $attribute::class);

        return new self($message, $previous);
    }

    public static function invalidImplementation(AsAlias|AsService $attribute, \ReflectionClass $aliasClass, ?\Throwable $previous = null): self
    {
        $message = sprintf('Class "%s" must implement or be subclass of "%s"', $attribute->service, $aliasClass->getName());

        return new self($message, $previous);
    }

    public static function noDefinitionInContainer(AsAlias|AsService $attribute, ?\Throwable $previous = null): self
    {
        $message = sprintf('There is no definition "%s" in container', $attribute->service);

        return new self($message, $previous);
    }

    public static function mustBeOfType(string $type, object $attribute, ?\Throwable $previous = null): self
    {
        $message = sprintf('Tags must be of type "%s" given "%s"', $type, $attribute::class);

        return new self($message, $previous);
    }
}
