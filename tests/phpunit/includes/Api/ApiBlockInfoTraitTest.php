<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiBlockInfoTrait;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Api\ApiBlockInfoTrait
 * @covers \MediaWiki\Api\ApiBlockInfoHelper
 */
class ApiBlockInfoTraitTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->setService( 'DBLoadBalancerFactory', $this->getDummyDBLoadBalancerFactory() );
	}

	/**
	 * @dataProvider provideGetBlockDetails
	 */
	public function testGetBlockDetails( $block, $expectedInfo ) {
		$language = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$mock = $this->getMockForTrait( ApiBlockInfoTrait::class );
		$mock->method( 'getLanguage' )->willReturn( $language );
		$mock->method( 'getUser' )->willReturn( new UserIdentityValue( 0, 'Test' ) );
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
			'Composite block' => [
				CompositeBlock::createFromBlocks(
					new DatabaseBlock( [ 'blockEmail' => false ] ),
					new DatabaseBlock( [ 'blockEmail' => true ] )
				),
				[
					'blockemail' => true,
					'blockreason' => 'There are multiple blocks against your account and/or IP address',
					'blockcomponents' => [
						[ 'blockemail' => false ],
						[ 'blockemail' => true ],
					],
				],
			],
		];
	}
}
