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

namespace Acme\App\Test\TestCase\Infrastructure\Notification\Strategy\Email;

use Acme\App\Core\Port\Notification\Client\Email\Email;
use Acme\App\Test\TestCase\Infrastructure\Notification\Strategy\DummyNotification;

class DummyEmailGenerator
{
    public function generate(DummyNotification $notification): Email
    {
        // not needed for tests
    }
}
