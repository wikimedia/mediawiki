<?php

use MediaWiki\User\Options\DefaultOptionsLookup;
use MediaWiki\User\Options\UserOptionsLookup;

/**
 * @covers \MediaWiki\User\Options\DefaultOptionsLookup
 */
class DefaultOptionsLookupTest extends UserOptionsLookupTestBase {
	protected function getLookup(
		string $langCode = 'qqq',
		array $defaultOptionsOverrides = []
	): UserOptionsLookup {
		return $this->getDefaultManager( $langCode, $defaultOptionsOverrides );
	}

	/**
	 * @covers \MediaWiki\User\Options\DefaultOptionsLookup::getOption
	 */
	public function testGetOptionsExcludeDefaults() {
		$this->assertSame( [], $this->getLookup()
			->getOptions( $this->getAnon(), DefaultOptionsLookup::EXCLUDE_DEFAULTS ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\DefaultOptionsLookup::getDefaultOptions
	 */
	public function testGetDefaultOptionsHook() {
		$this->setTemporaryHook( 'UserGetDefaultOptions', static function ( &$options ) {
			$options['from_hook'] = 'value_from_hook';
		} );
		$this->assertSame( 'value_from_hook', $this->getLookup()->getDefaultOption( 'from_hook' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\DefaultOptionsLookup::getDefaultOptions
	 */
	public function testSearchNS() {
		$lookup = $this->getLookup();
		$this->assertSame( 1, $lookup->getDefaultOption( 'searchNs0' ) );
		$this->assertSame( 0, $lookup->getDefaultOption( 'searchNs8' ) );
		$this->assertSame( 0, $lookup->getDefaultOption( 'searchNs9' ) );
		// Special namespace is not searchable and does not have a default
		$this->assertNull( $lookup->getDefaultOption( 'searchNs-1' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\DefaultOptionsLookup::getDefaultOptions
	 */
	public function testLangVariantOptions() {
		$managerZh = $this->getLookup( 'zh' );
		$this->assertSame( 'zh', $managerZh->getDefaultOption( 'language' ) );
		$this->assertSame( 'gan', $managerZh->getDefaultOption( 'variant-gan' ) );
		$this->assertSame( 'zh', $managerZh->getDefaultOption( 'variant' ) );
	}
}
