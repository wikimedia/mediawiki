<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers ApiBlockInfoTrait
 */
class ApiBlockInfoTraitTest extends MediaWikiIntegrationTestCase {
	protected function setUp(): void {
		parent::setUp();

		$db = $this->createNoOpMock( IDatabase::class, [ 'getInfinity' ] );
		$db->method( 'getInfinity' )->willReturn( 'infinity' );
		$lbFactory = $this->createMock( LBFactory::class );
		$lbFactory->method( 'getReplicaDatabase' )->willReturn( $db );
		$this->setService( 'DBLoadBalancerFactory', $lbFactory );
	}

	/**
	 * @dataProvider provideGetBlockDetails
	 */
	public function testGetBlockDetails( $block, $expectedInfo ) {
		$language = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$mock = $this->getMockForTrait( ApiBlockInfoTrait::class );
		$mock->method( 'getLanguage' )->willReturn( $language );
		$info = TestingAccessWrapper::newFromObject( $mock )->getBlockDetails( $block );
		$subset = array_merge( [
			'blockid' => null,
			'blockedby' => '',
			'blockedbyid' => 0,
			'blockreason' => '',
			'blockexpiry' => 'infinite',
			'blockemail' => false,
			'blockowntalk' => true,
		], $expectedInfo );
		$this->assertArraySubmapSame( $subset, $info, "Matching block details" );
	}

	public static function provideGetBlockDetails() {
		return [
			'Sitewide block' => [
				new DatabaseBlock(),
				[ 'blockpartial' => false ],
			],
			'Partial block' => [
				new DatabaseBlock( [ 'sitewide' => false ] ),
				[ 'blockpartial' => true ],
			],
			'Email block' => [
				new DatabaseBlock( [ 'blockEmail' => true ] ),
				[ 'blockemail' => true ]
			],
			'System block' => [
				new SystemBlock( [ 'systemBlock' => 'proxy' ] ),
				[ 'systemblocktype' => 'proxy' ]
			],
		];
	}
}
