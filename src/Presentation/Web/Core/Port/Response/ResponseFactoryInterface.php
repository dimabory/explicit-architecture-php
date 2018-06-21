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

namespace Acme\App\Presentation\Web\Core\Port\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ResponseFactoryInterface
{
    public function respond($content = '', int $status = 200, array $headers = []): ResponseInterface;

    public function respondJson($data = null, int $status = 200, array $headers = []): ResponseInterface;

    public function forward(
        ServerRequestInterface $currentRequest,
        string $controller,
        array $attributes = null,
        array $queryParameters = null,
        array $postParameters = null
    ): ResponseInterface;

    public function redirectToUrl(string $url, int $status = 302): ResponseInterface;

    public function redirectToRoute(string $route, array $parameters = [], int $status = 302): ResponseInterface;
}
