<?php

namespace MediaWiki\Tests\Integration\Context;

use Closure;
use MediaWiki\Actions\ActionFactory;
use MediaWiki\Config\HashConfig;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Language\Language;
use MediaWiki\Output\OutputPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use WikiPage;

/**
 * @covers \MediaWiki\Context\DerivativeContext
 */
class DerivativeContextTest extends MediaWikiIntegrationTestCase {

	public static function provideGetterSetter(): iterable {
		$initialContext = new RequestContext();
		// phpcs:disable Squiz.Scope.StaticThisUsage.Found
		yield 'get/set Context' => [
			'initialContext' => $initialContext,
			'initialValue' => $initialContext,
			'newValue' => new RequestContext(),
			'getter' => 'getContext',
			'setter' => 'setContext'
		];
		yield 'get/set Config' => [
			'initialContext' => $initialContext,
			'initialValue' => new HashConfig(),
			'newValue' => new HashConfig(),
			'getter' => 'getConfig',
			'setter' => 'setConfig'
		];
		yield 'get/set OutputPage' => [
			'initialContext' => $initialContext,
			'initialValue' => fn () => $this->createNoOpMock( OutputPage::class ),
			'newValue' => fn () => $this->createNoOpMock( OutputPage::class ),
			'getter' => 'getOutput',
			'setter' => 'setOutput'
		];
		yield 'get/set User' => [
			'initialContext' => $initialContext,
			'initialValue' => fn () => $this->createNoOpMock( User::class ),
			'newValue' => fn () => $this->createNoOpMock( User::class ),
			'getter' => 'getUser',
			'setter' => 'setUser'
		];
		yield 'get/set Authority' => [
			'initialContext' => $initialContext,
			'initialValue' => fn () => $this->createNoOpMock( Authority::class ),
			'newValue' => fn () => $this->createNoOpMock( Authority::class ),
			'getter' => 'getAuthority',
			'setter' => 'setAuthority'
		];
		yield 'get/set Language' => [
			'initialContext' => $initialContext,
			'initialValue' => fn () => $this->createNoOpMock( Language::class ),
			'newValue' => fn () => $this->createNoOpMock( Language::class ),
			'getter' => 'getLanguage',
			'setter' => 'setLanguage'
		];
		yield 'get/set WebRequest' => [
			'initialContext' => $initialContext,
			'initialValue' => new FauxRequest(),
			'newValue' => new FauxRequest(),
			'getter' => 'getRequest',
			'setter' => 'setRequest'
		];
		yield 'get/set Title' => [
			'initialContext' => $initialContext,
			'initialValue' => fn () => $this->createNoOpMock( Title::class ),
			'newValue' => fn () => $this->createNoOpMock( Title::class ),
			'getter' => 'getTitle',
			'setter' => 'setTitle',
		];
		yield 'get/set WikiPage' => [
			'initialContext' => $initialContext,
			'initialValue' => fn () => $this->createMock( WikiPage::class ),
			'newValue' => fn () => $this->createMock( WikiPage::class ),
			'getter' => 'getWikiPage',
			'setter' => 'setWikiPage',
		];
		yield 'get/set ActionName' => [
			'initialContext' => $initialContext,
			'initialValue' => 'initActionName',
			'newValue' => 'newActionName',
			'getter' => 'getActionName',
			'setter' => 'setActionName',
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideGetterSetter
	 */
	public function testGetterSetter(
		IContextSource $initialContext,
		$initialValue,
		$newValue,
		string $getter,
		string $setter
	) {
		if ( $initialValue instanceof Closure ) {
			$initialValue = $initialValue->call( $this );
		}

		if ( $newValue instanceof Closure ) {
			$newValue = $newValue->call( $this );
		}

		if ( $setter !== 'setContext' ) {
			$initialContext->$setter( $initialValue );
		}

		$derivativeContext = new DerivativeContext( $initialContext );
		$this->assertSame( $initialValue, $derivativeContext->$getter(), 'Get inital value' );
		$derivativeContext->$setter( $newValue );
		$this->assertSame( $newValue, $derivativeContext->$getter(), 'Get new value' );
	}

	public function testOverideActionName() {
		$parent = new RequestContext();
		$parent->setActionName( 'view' );

		$factory = $this->createMock( ActionFactory::class );
		$factory
			->method( 'getActionName' )
			->willReturnOnConsecutiveCalls( 'foo', 'bar', 'baz' );
		$this->setService( 'ActionFactory', $factory );

		$derivative = new DerivativeContext( $parent );
		$this->assertSame( 'view', $derivative->getActionName(), 'default to parent cache' );

		$derivative->setTitle( $this->createMock( Title::class ) );
		$this->assertSame( 'foo', $derivative->getActionName(), 'recompute after change' );
		$this->assertSame( 'foo', $derivative->getActionName(), 'local cache' );

		$derivative->setWikiPage( $this->createMock( WikiPage::class ) );
		$this->assertSame( 'bar', $derivative->getActionName(), 'recompute after change' );
		$this->assertSame( 'bar', $derivative->getActionName(), 'local cache' );

		$derivative->setActionName( 'custom' );
		$this->assertSame( 'custom', $derivative->getActionName(), 'override' );
	}
}
