<?php

use Wikimedia\TestingAccessWrapper;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;

/**
 * @covers ApiBlockInfoTrait
 */
class ApiBlockInfoTraitTest extends \MediaWikiUnitTestCase {

	protected function setUp() {
		parent::setUp();

		$lbMock = $this->createMock( LoadBalancer::class );
		$lbMock->expects( $this->any() )
			->method( 'getConnection' )
			->willReturn( $this->createMock( Database::class ) );

		$loadBalancerMockFactory = function () use ( $lbMock ): LoadBalancer {
			return $lbMock;
		};

		$this->overrideMwServices( [ 'DBLoadBalancer' => $loadBalancerMockFactory ] );
	}

	/**
	 * @dataProvider provideGetBlockDetails
	 */
	public function testGetBlockDetails( $blockFactory, $expectedInfo ) {
		$mock = $this->getMockForTrait( ApiBlockInfoTrait::class );
		$info = TestingAccessWrapper::newFromObject( $mock )->getBlockDetails( $blockFactory() );
		$subset = array_merge( [
			'blockid' => null,
			'blockedby' => '',
			'blockedbyid' => 0,
			'blockreason' => '',
			'blockexpiry' => 'infinite',
		], $expectedInfo );
		$this->assertArraySubset( $subset, $info );
	}

	public static function provideGetBlockDetails() {
		return [
			'Sitewide block' => [
				// Defer instantiation to avoid connecting to DB
				function () {
					return new DatabaseBlock();
				},
				[ 'blockpartial' => false ],
			],
			'Partial block' => [
				function () {
					return new DatabaseBlock( [ 'sitewide' => false ] );
				},
				[ 'blockpartial' => true ],
			],
			'System block' => [
				function () {
					return new SystemBlock( [ 'systemBlock' => 'proxy' ] );
				},
				[ 'systemblocktype' => 'proxy' ]
			],
		];
	}
}
