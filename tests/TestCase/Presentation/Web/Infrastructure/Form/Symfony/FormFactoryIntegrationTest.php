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

namespace Acme\App\Test\TestCase\Presentation\Web\Infrastructure\Form\Symfony;

use Acme\App\Presentation\Web\Core\Port\Form\FormFactoryInterface;
use Acme\App\Presentation\Web\Infrastructure\Form\Symfony\Form\CommentForm;
use Acme\App\Presentation\Web\Infrastructure\Form\Symfony\Form\CreatePostForm;
use Acme\App\Presentation\Web\Infrastructure\Form\Symfony\Form\EditPostForm;
use Acme\App\Presentation\Web\Infrastructure\Form\Symfony\FormFactory;
use Acme\App\Test\Framework\AbstractIntegrationTest;
use Acme\PhpExtension\Helper\ClassHelper;

final class FormFactoryIntegrationTest extends AbstractIntegrationTest
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    protected function setUp(): void
    {
        $this->formFactory = self::getService(FormFactoryInterface::class);
    }

    /**
     * @test
     */
    public function createEditPostForm(): void
    {
        self::assertSame(
            ClassHelper::toSnakeCase(ClassHelper::extractCanonicalClassName(EditPostForm::class)),
            $this->formFactory->createEditPostForm()->getFormName()
        );
    }

    /**
     * @test
     */
    public function createCreatePostForm(): void
    {
        self::assertSame(
            ClassHelper::toSnakeCase(ClassHelper::extractCanonicalClassName(CreatePostForm::class)),
            $this->formFactory->createCreatePostForm()->getFormName()
        );
    }

    /**
     * @test
     */
    public function createCommentForm(): void
    {
        self::assertSame(
            ClassHelper::toSnakeCase(ClassHelper::extractCanonicalClassName(CommentForm::class)),
            $this->formFactory->createCommentForm()->getFormName()
        );
    }
}
