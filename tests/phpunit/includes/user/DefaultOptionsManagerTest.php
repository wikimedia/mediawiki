<?php

use MediaWiki\User\DefaultOptionsManager;
use MediaWiki\User\UserOptionsLookup;

/**
 * @covers MediaWiki\User\DefaultOptionsManager
 */
class DefaultOptionsManagerTest extends UserOptionsLookupTest {
	protected function getLookup(
		string $langCode = 'qqq',
		array $defaultOptionsOverrides = []
	) : UserOptionsLookup {
		return $this->getDefaultManager( $langCode, $defaultOptionsOverrides );
	}

	/**
	 * @covers MediaWiki\User\DefaultOptionsManager::getOption
	 */
	public function testGetOptionsExcludeDefaults() {
		$this->assertSame( [], $this->getLookup()
			->getOptions( $this->getAnon(), DefaultOptionsManager::EXCLUDE_DEFAULTS ) );
	}

	/**
	 * @covers MediaWiki\User\DefaultOptionsManager::getDefaultOptions
	 */
	public function testGetDefaultOptionsHook() {
		$this->setTemporaryHook( 'UserGetDefaultOptions', function ( &$options ) {
			$options['from_hook'] = 'value_from_hook';
		} );
		$this->assertSame( 'value_from_hook', $this->getLookup()->getDefaultOption( 'from_hook' ) );
	}

	/**
	 * @covers MediaWiki\User\DefaultOptionsManager::getDefaultOptions
	 */
	public function testSearchNS() {
		$this->assertTrue( $this->getLookup()->getDefaultOption( 'searchNs0' ) );
		$this->assertNull( $this->getLookup()->getDefaultOption( 'searchNs5' ) );
	}

	/**
	 * @covers MediaWiki\User\DefaultOptionsManager::getDefaultOptions
	 */
	public function testLangVariantOptions() {
		$managerZh = $this->getLookup( 'zh' );
		$this->assertSame( 'zh', $managerZh->getDefaultOption( 'language' ) );
		$this->assertSame( 'gan', $managerZh->getDefaultOption( 'variant-gan' ) );
		$this->assertSame( 'zh', $managerZh->getDefaultOption( 'variant' ) );
	}
}
