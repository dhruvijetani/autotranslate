<?php

declare(strict_types=1);

namespace RemoteDevs\RdActivitylog\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class LogTest extends UnitTestCase
{
    /**
     * @var \RemoteDevs\RdActivitylog\Domain\Model\Log|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \RemoteDevs\RdActivitylog\Domain\Model\Log::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getPageUidReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getPageUid()
        );
    }

    /**
     * @test
     */
    public function setPageUidForIntSetsPageUid(): void
    {
        $this->subject->setPageUid(12);

        self::assertEquals(12, $this->subject->_get('pageUid'));
    }

    /**
     * @test
     */
    public function getBeUserReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getBeUser()
        );
    }

    /**
     * @test
     */
    public function setBeUserForIntSetsBeUser(): void
    {
        $this->subject->setBeUser(12);

        self::assertEquals(12, $this->subject->_get('beUser'));
    }

    /**
     * @test
     */
    public function getActionTypeReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getActionType()
        );
    }

    /**
     * @test
     */
    public function setActionTypeForStringSetsActionType(): void
    {
        $this->subject->setActionType('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('actionType'));
    }
}
