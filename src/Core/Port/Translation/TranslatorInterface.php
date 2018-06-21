<?php

declare(strict_types=1);

/*
 * This file is part of the Explicit Architecture POC,
 * which is created on top of the Symfony Demo application.
 *
 * (c) Herberto Graça <herberto.graca@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\App\Core\Port\Translation;

use Acme\PhpExtension\Enum\AbstractEnum;

interface TranslatorInterface
{
    public function translate(string $key, array $parameters = []): string;

    public function translateEnum(AbstractEnum $enum, array $parameters = []): string;
}
