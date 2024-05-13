<?php

namespace MediaWiki\Tests\Unit\DAO;

use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWikiUnitTestCase;
use Wikimedia\Assert\ParameterAssertionException;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\DAO\WikiAwareEntityTrait
 */
class WikiAwareEntityTraitTest extends MediaWikiUnitTestCase {

	/**
	 * @param string|false $wikiId
	 * @return WikiAwareEntity
	 */
	public function getEntityInstance( $wikiId ) {
		$entity = new class( $wikiId ) implements WikiAwareEntity {
			use WikiAwareEntityTrait;

			/** @var string|false */
			private $wikiId;

			/**
			 * @param string|false $wikiId
			 */
			public function __construct( $wikiId ) {
				$this->wikiId = $wikiId;
			}

			public function getWikiId() {
				return $this->wikiId;
			}
		};
		return $entity;
	}

	public static function provideMatchingWikis() {
		yield 'acme' => [
			'entityWiki' => 'acmewiki',
			'assertWiki' => 'acmewiki',
		];
		yield 'local' => [
			'entityWiki' => WikiAwareEntity::LOCAL,
			'assertWiki' => WikiAwareEntity::LOCAL,
		];
	}

	public static function provideMismatchingWikis() {
		yield 'acme-noacme' => [
			'entityWiki' => 'acmewiki',
			'assertWiki' => 'noacmewiki',
		];
		yield 'local-acme' => [
			'entityWiki' => WikiAwareEntity::LOCAL,
			'assertWiki' => 'acmewiki',
		];
		yield 'acme-local' => [
			'entityWiki' => 'amewiki',
			'assertWiki' => WikiAwareEntity::LOCAL,
		];
	}

	/**
	 * @dataProvider provideMatchingWikis
	 */
	public function testAssertWiki( $entityWiki, $assertWiki ) {
		$this->getEntityInstance( $entityWiki )->assertWiki( $assertWiki );
		$this->addToAssertionCount( 1 );
	}

	/**
	 * @dataProvider provideMatchingWikis
	 */
	public function testDeprecateInvalidCrossWiki( $entityWiki, $assertWiki ) {
		TestingAccessWrapper::newFromObject( $this->getEntityInstance( $entityWiki ) )
			->deprecateInvalidCrossWiki( $assertWiki, '1.99' );
		$this->addToAssertionCount( 1 );
	}

	/**
	 * @dataProvider provideMismatchingWikis
	 */
	public function testAssertWikiMismatch( $entityWiki, $assertWiki ) {
		$this->expectException( PreconditionException::class );
		$this->getEntityInstance( $entityWiki )->assertWiki( $assertWiki );
	}

	/**
	 * @dataProvider provideMismatchingWikis
	 */
	public function testDeprecateInvalidCrossWikiMismatch( $entityWiki, $assertWiki ) {
		$this->expectDeprecationAndContinue( '/Deprecated cross-wiki access/' );
		TestingAccessWrapper::newFromObject( $this->getEntityInstance( $entityWiki ) )
			->deprecateInvalidCrossWiki( $assertWiki, '1.99' );
	}

	public static function provideAssertWikiIdParamInvalid() {
		yield 'true' => [ true ];
		yield 'null' => [ null ];
		yield 'int' => [ 1 ];
	}

	/**
	 * @dataProvider provideAssertWikiIdParamInvalid
	 */
	public function testAssertWikiIdParamInvalid( $param ) {
		$entity = TestingAccessWrapper::newFromObject( $this->getEntityInstance( 'acme' ) );
		$this->expectException( ParameterAssertionException::class );
		$entity->assertWikiIdParam( $param );
	}
}
