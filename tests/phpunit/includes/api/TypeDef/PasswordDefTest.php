<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use DerivativeContext;
use MediaWikiLangTestCase;
use MockApi;
use RequestContext;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef\PasswordDef
 */
class PasswordDefTest extends MediaWikiLangTestCase {

	public function testNormalizeSettings() {
		$typeDef = new PasswordDef;
		$this->assertSame(
			[ ApiBase::PARAM_SENSITIVE => true ],
			$typeDef->normalizeSettings( [] )
		);
	}

	public function testGetHelpInfo() {
		$typeDef = new PasswordDef;

		$settings = [
			ApiBase::PARAM_TYPE => 'password',
		];
		$this->assertSame( [
			// api-help-type-password is empty by default
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setLanguage( 'qqx' );
		$this->assertSame( [
			'(api-help-param-type-password: 1)',
		], $typeDef->getHelpInfo( $context, 'foobar', $settings, new MockApi ) );
	}

}
