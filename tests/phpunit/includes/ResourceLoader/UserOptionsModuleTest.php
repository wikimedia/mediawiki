<?php

namespace MediaWiki\Tests\ResourceLoader;

use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\UserOptionsModule;
use MediaWikiIntegrationTestCase;
use User;

/**
 * @covers \MediaWiki\ResourceLoader\UserOptionsModule
 */
class UserOptionsModuleTest extends MediaWikiIntegrationTestCase {

	public function testGetScript() {
		$module = new UserOptionsModule();
		$hooks = $this->createHookContainer();
		$module->setHookContainer( $hooks );
		$options = new \MediaWiki\User\StaticUserOptionsLookup(
			[
				'Example1' => [],
				'Example2' => [ 'y' => '1', 'userjs-extra' => '1' ],
			],
			[
				'x' => '1',
				'y' => '0',
				'foobar' => 'Rhabarberbarbara',
				'ipsum' => 'consectetur adipiscing elit',
			]
		);
		$this->setService( 'UserOptionsLookup', $options );

		$script = $module->getScript( $this->makeContext() );
		$this->assertStringContainsString(
			'"csrfToken":',
			$script,
			'always send csrfToken'
		);
		$this->assertStringNotContainsString(
			'Rhabarberbarbara',
			$script,
			'no default settings sent'
		);
		$this->assertStringNotContainsString(
			'mw.user.options.set',
			$script,
			'no in-page blob for anon default settings'
		);

		$script = $module->getScript( $this->makeContext( 'Example1' ) );
		$this->assertStringNotContainsString(
			'mw.user.options.set',
			$script,
			'no in-page blob for logged-in default settings'
		);

		$script = $module->getScript( $this->makeContext( 'Example2' ) );
		$this->assertStringContainsString(
			'mw.user.options.set',
			$script,
			'send blob for non-default settings'
		);
		$this->assertStringContainsString(
			'"y":"1"',
			$script,
			'send overridden value'
		);
		$this->assertStringContainsString(
			'"userjs-extra":"1"',
			$script,
			'send custom preference keys'
		);
	}

	public function testResourceLoaderExcludeUserOptionsHook() {
		$module = new UserOptionsModule();
		$hooks = $this->createHookContainer( [
			'ResourceLoaderExcludeUserOptions' => static function (
				array &$keysToExclude,
				Context $context
			): void {
				$keysToExclude[] = 'exclude-explicit';
				$keysToExclude[] = 'exclude-default';
			}
		] );
		$module->setHookContainer( $hooks );
		$options = new \MediaWiki\User\StaticUserOptionsLookup(
			[
				'User' => [ 'include-explicit' => '1', 'exclude-explicit' => '1' ],
			],
			[
				'exclude-default' => '1',
			]
		);
		$this->setService( 'UserOptionsLookup', $options );

		$script = $module->getScript( $this->makeContext( 'User' ) );
		$this->assertStringContainsString(
			'include-explicit',
			$script,
			'normal behavior'
		);
		$this->assertStringNotContainsString(
			'exclude-explicit',
			$script,
			'$keysToExclude filters'
		);
		// defaults shouldn't show up here anyway but double-check
		$this->assertStringNotContainsString(
			'exclude-default',
			$script,
			'default excluded'
		);
	}

	private function makeContext( ?string $name = null ) {
		$user = $this->createStub( User::class );
		if ( $name ) {
			$user->method( 'isRegistered' )->willReturn( true );
			$user->method( 'getName' )->willReturn( $name );
		}
		$ctx = $this->createStub( Context::class );
		$ctx->method( 'encodeJson' )->willReturnCallback( 'json_encode' );
		$ctx->method( 'getUserObj' )->willReturn( $user );
		return $ctx;
	}
}
