<?php

namespace MediaWiki\Tests\Integration\Context;

use DerivativeContext;
use IContextSource;
use Language;
use MediaWiki\Actions\ActionFactory;
use MediaWiki\Config\HashConfig;
use MediaWiki\Output\OutputPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use RequestContext;
use WikiPage;

/**
 * @covers DerivativeContext
 */
class DerivativeContextTest extends MediaWikiIntegrationTestCase {

	public function provideGetterSetter() {
		$initialContext = new RequestContext();
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
			'initialValue' => $this->createNoOpMock( OutputPage::class ),
			'newValue' => $this->createNoOpMock( OutputPage::class ),
			'getter' => 'getOutput',
			'setter' => 'setOutput'
		];
		yield 'get/set User' => [
			'initialContext' => $initialContext,
			'initialValue' => $this->createNoOpMock( User::class ),
			'newValue' => $this->createNoOpMock( User::class ),
			'getter' => 'getUser',
			'setter' => 'setUser'
		];
		yield 'get/set Authority' => [
			'initialContext' => $initialContext,
			'initialValue' => $this->createNoOpMock( Authority::class ),
			'newValue' => $this->createNoOpMock( Authority::class ),
			'getter' => 'getAuthority',
			'setter' => 'setAuthority'
		];
		yield 'get/set Language' => [
			'initialContext' => $initialContext,
			'initialValue' => $this->createNoOpMock( Language::class ),
			'newValue' => $this->createNoOpMock( Language::class ),
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
		$initialTitle = $this->createMock( Title::class );
		$initialTitle->expects( $this->any() )->method( 'equals' );
		yield 'get/set Title' => [
			'initialContext' => $initialContext,
			'initialValue' => $initialTitle,
			'newValue' => $this->createNoOpMock( Title::class ),
			'getter' => 'getTitle',
			'setter' => 'setTitle',
		];
		$initialWikiPage = $this->createMock( WikiPage::class );
		$initialWikiPage->expects( $this->any() )->method( 'getTitle' )->willReturn( $initialTitle );
		$newWikiPage = $this->createMock( WikiPage::class );
		$newWikiPage->expects( $this->any() )->method( 'getTitle' );
		yield 'get/set WikiPage' => [
			'initialContext' => $initialContext,
			'initialValue' => $initialWikiPage,
			'newValue' => $newWikiPage,
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
