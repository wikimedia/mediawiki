<?php

namespace Wikimedia\Tests\Stats;

use PHPUnit\Framework\TestCase;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Stats\UnitTestingHelper;

/**
 * @covers \Wikimedia\Stats\UnitTestingHelper
 */
class UnitTestingHelperTest extends TestCase {
	private UnitTestingHelper $statsHelper;
	private UnitTestingHelper $statsHelperWithComponent;

	public function setUp(): void {
		parent::setUp();
		// Prepopulate No Component StatsFactory
		$this->statsHelper = StatsFactory::newUnitTestingHelper();
		$statsFactory = $this->statsHelper->getStatsFactory();
		$statsFactory->getCounter( 'test' )->setLabel( 'a', 'a' )->setLabel( 'b', 'b' )->increment();
		$statsFactory->getCounter( 'test' )->setLabel( 'a', 'a' )->setLabel( 'b', 'c' )->incrementBy( 2 );
		$statsFactory->getCounter( 'test' )->setLabel( 'a', 'b' )->setLabel( 'b', 'b' )->increment();
		$statsFactory->getCounter( 'test' )->setLabel( 'a', 'b' )->setLabel( 'b', 'b' )->increment();
		$statsFactory->getCounter( 'test' )->setLabel( 'a', 'c' )->setLabel( 'b', 'c' )->increment();
		$statsFactory->getCounter( 'test' )->setLabel( 'a', 'c' )->setLabel( 'b', 'c' )->increment();
		$statsFactory->getCounter( 'test' )->setLabel( 'a', 'cdef' )->setLabel( 'b', 'c' )->increment();
		// Prepopulate FooComponent StatsFactory
		$this->statsHelperWithComponent = StatsFactory::newUnitTestingHelper()->withComponent( 'FooComponent' );
		$statsFactoryWithComponent = $this->statsHelperWithComponent->getStatsFactory();
		$statsFactoryWithComponent->getCounter( 'test' )->setLabel( 'a', 'a' )->setLabel( 'b', 'b' )->increment();
		$statsFactoryWithComponent->getCounter( 'test' )->setLabel( 'a', 'a' )->setLabel( 'b', 'c' )->incrementBy( 2 );
		$statsFactoryWithComponent->getCounter( 'test' )->setLabel( 'a', 'b' )->setLabel( 'b', 'b' )->increment();
		$statsFactoryWithComponent->getCounter( 'test' )->setLabel( 'a', 'b' )->setLabel( 'b', 'b' )->increment();
		$statsFactoryWithComponent->getCounter( 'test' )->setLabel( 'a', 'c' )->setLabel( 'b', 'c' )->increment();
		$statsFactoryWithComponent->getCounter( 'test' )->setLabel( 'a', 'c' )->setLabel( 'b', 'c' )->increment();
		$statsFactoryWithComponent->getCounter( 'test' )->setLabel( 'a', 'cdef' )->setLabel( 'b', 'c' )->increment();
	}

	public function testCount() {
		$actual = $this->statsHelper->count( 'test{a="a"}' );
		$this->assertEquals( 2, $actual );
		$actual = $this->statsHelperWithComponent->count( 'test{a="a"}' );
		$this->assertEquals( 2, $actual );
	}

	public function testCountNotEqual() {
		$actual = $this->statsHelper->count( 'test{a!="a"}' );
		$this->assertEquals( 5, $actual );
		$actual = $this->statsHelperWithComponent->count( 'test{a!="a"}' );
		$this->assertEquals( 5, $actual );
	}

	public function testCountPositiveRegex() {
		$actual = $this->statsHelper->count( 'test{a=~"c.*"}' );
		$this->assertEquals( 3, $actual );
		$actual = $this->statsHelperWithComponent->count( 'test{a=~"c.*"}' );
		$this->assertEquals( 3, $actual );
	}

	public function testCountNegativeRegex() {
		$actual = $this->statsHelper->count( 'test{a!~"c.*"}' );
		$this->assertEquals( 4, $actual );
		$actual = $this->statsHelperWithComponent->count( 'test{a!~"c.*"}' );
		$this->assertEquals( 4, $actual );
	}

	public function testLast() {
		$actual = $this->statsHelper->last( 'test{a="a"}' );
		$this->assertEquals( 2, $actual );
		$actual = $this->statsHelperWithComponent->last( 'test{a="a"}' );
		$this->assertEquals( 2, $actual );
	}

	public function testSum() {
		$actual = $this->statsHelper->sum( 'test{a="a"}' );
		$this->assertEquals( 3, $actual );
		$actual = $this->statsHelperWithComponent->sum( 'test{a="a"}' );
		$this->assertEquals( 3, $actual );
		// avoid counting the same metric twice
		$actual = $this->statsHelperWithComponent->sum( 'test{a="a", a="a"}' );
		$this->assertEquals( 3, $actual );
	}

	public function testMax() {
		$actual = $this->statsHelper->max( 'test{a="a"}' );
		$this->assertEquals( 2, $actual );
		$actual = $this->statsHelperWithComponent->max( 'test{a="a"}' );
		$this->assertEquals( 2, $actual );
	}

	public function testMedian() {
		$actual = $this->statsHelper->median( 'test{a="a"}' );
		$this->assertEquals( 1.5, $actual );
		$actual = $this->statsHelperWithComponent->median( 'test{a="a"}' );
		$this->assertEquals( 1.5, $actual );
	}

	public function testMin() {
		$actual = $this->statsHelper->min( 'test{a="a"}' );
		$this->assertSame( 1.0, $actual );
		$actual = $this->statsHelperWithComponent->min( 'test{a="a"}' );
		$this->assertSame( 1.0, $actual );
	}

	public function testNoFilter() {
		$actual = $this->statsHelper->count( 'test' );
		$this->assertEquals( 7, $actual );
		$actual = $this->statsHelperWithComponent->count( 'test' );
		$this->assertEquals( 7, $actual );
	}

	public function testMissingName() {
		$this->expectExceptionMessage( 'Selector cannot be empty.' );
		$this->statsHelper->count( '{a="a"}' );
		$this->statsHelperWithComponent->count( '{a="a"}' );
	}

	public function testMalformedSelector() {
		$this->expectExceptionMessage( 'Filter components cannot be empty.' );
		$this->statsHelper->count( 'test{a=}' );
		$this->statsHelperWithComponent->count( 'test{a=' );
	}
}
