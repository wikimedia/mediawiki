<?php

namespace MediaWiki\Tests\Mocks\Language;

use LCStoreDB;
use LocalisationCache;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use Psr\Log\NullLogger;

/**
 * Allows the construction of a mock {@link LocalisationCache} that has a limited pre-determined list of
 * message keys.
 */
trait MockLocalisationCacheTrait {
	use DummyServicesTrait;

	/**
	 * @param array $hooks Hook overrides
	 * @param array $options Service options (see {@link LocalisationCache::CONSTRUCTOR_OPTIONS})
	 * @return LocalisationCache
	 */
	protected function getMockLocalisationCache( array $hooks = [], array $options = [] ): LocalisationCache {
		$hookContainer = $this->createHookContainer( $hooks );

		// in case any of the LanguageNameUtils hooks are being used
		$langNameUtils = $this->getDummyLanguageNameUtils(
			[ 'hookContainer' => $hookContainer ]
		);

		$options += [
			'forceRecache' => false,
			'manualRecache' => false,
			'ExtensionMessagesFiles' => [],
			'MessagesDirs' => [],
			'TranslationAliasesDirs' => [],
		];

		$lc = $this->getMockBuilder( LocalisationCache::class )
			->setConstructorArgs( [
				new ServiceOptions( LocalisationCache::CONSTRUCTOR_OPTIONS, $options ),
				new LCStoreDB( [] ),
				new NullLogger,
				[],
				$langNameUtils,
				$hookContainer
			] )
			->onlyMethods( [ 'getMessagesDirs' ] )
			->getMock();
		$lc->method( 'getMessagesDirs' )
			->willReturn( [ MW_INSTALL_PATH . "/tests/phpunit/data/localisationcache" ] );

		return $lc;
	}
}
