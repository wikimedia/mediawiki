<?php

namespace MediaWiki\Tests\Rest\BasicAccess;

use MediaWiki\Rest\BasicAccess\BasicAuthorizerInterface;
use MediaWiki\Rest\BasicAccess\CompoundAuthorizer;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;

/**
 * @covers \MediaWiki\Rest\BasicAccess\CompoundAuthorizer
 */
class CompoundAuthorizerTest extends \MediaWikiUnitTestCase {
	public function provideAuthorize() {
		yield 'No authorizers' => [
			[], null, null
		];
		yield 'All success' => [
			[ new StaticBasicAuthorizer( null ), new StaticBasicAuthorizer( null ) ],
			null, null
		];
		yield 'First failed returned' => [
			[ new StaticBasicAuthorizer( 'first' ), new StaticBasicAuthorizer( 'second' ) ],
			null, 'first'
		];
		yield 'Added failed' => [
			[], new StaticBasicAuthorizer( 'added' ), 'added'
		];
	}

	/**
	 * @dataProvider provideAuthorize
	 * @param array $authorizers
	 * @param BasicAuthorizerInterface|null $addedAuthorizer
	 * @param string|null $result
	 */
	public function testAuthorize(
		array $authorizers,
		?BasicAuthorizerInterface $addedAuthorizer,
		?string $result
	) {
		$authorizer = new CompoundAuthorizer( $authorizers );
		if ( $addedAuthorizer ) {
			$authorizer->addAuthorizer( $addedAuthorizer );
		}
		$this->assertSame( $result, $authorizer->authorize(
			$this->createNoOpMock( RequestInterface::class ),
			$this->createNoOpMock( Handler::class )
		) );
	}
}
