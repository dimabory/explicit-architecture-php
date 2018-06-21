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

namespace Acme\App\Test\TestCase\Core\Component\Blog\Application\Repository\DQL;

use Acme\App\Core\Component\Blog\Application\Repository\DQL\CommentRepository;
use Acme\App\Core\Component\Blog\Domain\Entity\Comment;
use Acme\App\Core\Component\Blog\Domain\Entity\CommentId;
use Acme\App\Core\Port\Persistence\DQL\DQLQueryBuilderInterface;
use Acme\App\Core\Port\Persistence\QueryServiceRouterInterface;
use Acme\App\Infrastructure\Persistence\Doctrine\DQLPersistenceService;
use Acme\App\Test\Framework\AbstractIntegrationTest;

final class CommentRepositoryIntegrationTest extends AbstractIntegrationTest
{
    /**
     * @var CommentRepository
     */
    private $repository;

    /**
     * @var DQLPersistenceService
     */
    private $persistenceService;

    /**
     * @var DQLQueryBuilderInterface
     */
    private $dqlQueryBuilder;

    /**
     * @var QueryServiceRouterInterface
     */
    private $queryService;

    public function setUp(): void
    {
        $this->repository = self::getService(CommentRepository::class);
        $this->persistenceService = self::getService(DQLPersistenceService::class);
        $this->dqlQueryBuilder = self::getService(DQLQueryBuilderInterface::class);
        $this->queryService = self::getService(QueryServiceRouterInterface::class);
    }

    /**
     * @test
     */
    public function find(): void
    {
        $aComment = $this->findAComment();
        $this->clearDatabaseCache();
        $comment = $this->repository->find($aComment->getId());

        self::assertEquals($aComment, $comment);
    }

    /**
     * @test
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function upsert_updates_entity(): void
    {
        $comment = $this->findAComment();
        $commentId = $comment->getId();
        $newContent = 'some new content';
        $comment->setContent($newContent);
        $this->persistenceService->startTransaction();
        $this->repository->upsert($comment);
        $this->persistenceService->finishTransaction();
        $this->clearDatabaseCache();

        $comment = $this->findById($commentId);

        self::assertSame($newContent, $comment->getContent());
    }

    /**
     * @test
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function upsert_creates_entity(): void
    {
        $auxiliaryComment = $this->findAComment();

        $comment = new Comment();
        $commentId = $comment->getId();
        $comment->setAuthor($auxiliaryComment->getAuthor());
        $comment->setContent($content = 'some new content');
        $comment->setPost($auxiliaryComment->getPost());

        $this->persistenceService->startTransaction();
        $this->repository->upsert($comment);
        $this->persistenceService->finishTransaction();
        $this->clearDatabaseCache();

        $comment = $this->findById($commentId);

        self::assertSame($content, $comment->getContent());
        self::assertTrue($auxiliaryComment->getAuthor()->getId()->equals($comment->getAuthor()->getId()));
        self::assertTrue($auxiliaryComment->getPost()->getId()->equals($comment->getPost()->getId()));
    }

    private function findById(CommentId $id): Comment
    {
        $dqlQuery = $this->dqlQueryBuilder->create(Comment::class)
            ->where('Comment.id = :id')
            ->setParameter('id', $id)
            ->build();

        return $this->queryService->query($dqlQuery)->getSingleResult();
    }

    private function findAComment(): Comment
    {
        $dqlQuery = $this->dqlQueryBuilder->create(Comment::class)->setMaxResults(1)->build();

        return $this->queryService->query($dqlQuery)->getSingleResult();
    }
}
