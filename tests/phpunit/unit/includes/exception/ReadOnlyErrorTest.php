<?php

/**
 * @covers ReadOnlyError
 * @author Addshore
 */
class ReadOnlyErrorTest extends \MediaWikiUnitTestCase {

	protected function setUp() {
		parent::setUp();

		$loadBalancerMockFactory = function (): LoadBalancer {
			return $this->createMock( LoadBalancer::class );
		};

		$this->overrideMwServices( [ 'DBLoadBalancer' => $loadBalancerMockFactory ] );
	}

	public function testConstruction() {
		$e = new ReadOnlyError();
		$this->assertEquals( 'readonly', $e->title );
		$this->assertEquals( 'readonlytext', $e->msg );
		$this->assertEquals( wfReadOnlyReason() ?: [], $e->params );
	}
}
