<?php

/**
 * @covers ResourceLoaderUserOptionsModule
 */
class ResourceLoaderUserOptionsModuleTest extends MediaWikiIntegrationTestCase {

	public function testGetScript() {
		$module = new ResourceLoaderUserOptionsModule();
		$hooks = $this->createHookContainer();
		$module->setHookContainer( $hooks );
		$options = new MediaWiki\User\StaticUserOptionsLookup(
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
			'send overriden value'
		);
		$this->assertStringContainsString(
			'"userjs-extra":"1"',
			$script,
			'send custom preference keys'
		);
	}

	private function makeContext( ?string $name = null ) {
		$user = $this->createStub( User::class );
		if ( $name ) {
			$user->method( 'isRegistered' )->willReturn( true );
			$user->method( 'getName' )->willReturn( $name );
		}
		$ctx = $this->createStub( ResourceLoaderContext::class );
		$ctx->method( 'encodeJson' )->willReturnCallback( 'json_encode' );
		$ctx->method( 'getUserObj' )->willReturn( $user );
		return $ctx;
	}
}
