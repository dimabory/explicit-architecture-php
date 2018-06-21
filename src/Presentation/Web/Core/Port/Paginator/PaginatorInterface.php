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

namespace Acme\App\Presentation\Web\Core\Port\Paginator;

interface PaginatorInterface
{
    public const DEFAULT_MAX_ITEMS_PER_PAGE = 5;
    public const DEFAULT_PAGE = 1;

    public function setCurrentPage(int $page): void;
}
