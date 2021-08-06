<?php

namespace MediaWiki\Tests\Unit\Context;

use DerivativeContext;
use FauxRequest;
use HashConfig;
use IContextSource;
use Language;
use MediaWikiUnitTestCase;
use OutputPage;
use RequestContext;
use User;

/**
 * @coversDefaultClass DerivativeContext
 * @package MediaWiki\Tests\Unit\Context
 */
class DerivativeContextTest extends MediaWikiUnitTestCase {

	public function provideGetterSetter() {
		$initialContext = new RequestContext();
		yield 'get/set Context' => [
			'initialContext' => $initialContext,
			'initialValue' => $initialContext,
			'newValue' => new RequestContext(),
			'getter' => 'getContext',
			'setter' => 'setContext'
		];
		$initialConfig = new HashConfig();
		$initialContext->setConfig( $initialConfig );
		yield 'get/set Config' => [
			'initialContext' => $initialContext,
			'initialValue' => $initialConfig,
			'newValue' => new HashConfig(),
			'getter' => 'getConfig',
			'setter' => 'setConfig'
		];
		$initialOutput = $this->createNoOpMock( OutputPage::class );
		$initialContext->setOutput( $initialOutput );
		yield 'get/set OutputPage' => [
			'initialContext' => $initialContext,
			'initialValue' => $initialOutput,
			'newValue' => $this->createNoOpMock( OutputPage::class ),
			'getter' => 'getOutput',
			'setter' => 'setOutput'
		];
		$initialUser = $this->createNoOpMock( User::class );
		$initialContext->setUser( $initialUser );
		yield 'get/set User' => [
			'initialContext' => $initialContext,
			'initialValue' => $initialUser,
			'newValue' => $this->createNoOpMock( User::class ),
			'getter' => 'getUser',
			'setter' => 'setUser'
		];
		$initialLanguage = $this->createNoOpMock( Language::class );
		$initialContext->setLanguage( $initialLanguage );
		yield 'get/set Language' => [
			'initialContext' => $initialContext,
			'initialValue' => $initialLanguage,
			'newValue' => $this->createNoOpMock( Language::class ),
			'getter' => 'getLanguage',
			'setter' => 'setLanguage'
		];
		$initialRequest = new FauxRequest();
		$initialContext->setRequest( $initialRequest );
		yield 'get/set WebRequest' => [
			'initialContext' => $initialContext,
			'initialValue' => $initialRequest,
			'newValue' => new FauxRequest(),
			'getter' => 'getRequest',
			'setter' => 'setRequest'
		];
	}

	/**
	 * @covers ::__construct
	 * @covers ::getContext
	 * @covers ::setContext
	 * @covers ::getConfig
	 * @covers ::setConfig
	 * @covers ::getOutput
	 * @covers ::setOutput
	 * @covers ::getUser
	 * @covers ::setUser
	 * @covers ::setLanguage
	 * @covers ::getLanguage
	 * @covers ::getRequest
	 * @covers ::setRequest
	 * @dataProvider provideGetterSetter
	 */
	public function testGetterSetter(
		IContextSource $initialContext,
		$initialValue,
		$newValue,
		string $getter,
		string $setter
	) {
		$derivativeContext = new DerivativeContext( $initialContext );
		$this->assertSame( $initialValue, $derivativeContext->$getter() );
		$derivativeContext->$setter( $newValue );
		$this->assertSame( $newValue, $derivativeContext->$getter() );
	}
}
