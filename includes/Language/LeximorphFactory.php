<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Language;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use Psr\Log\LoggerInterface;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Leximorph\Manager;
use Wikimedia\Leximorph\Provider;
use Wikimedia\MapCacheLRU\MapCacheLRU;

/**
 * Create and cache Manager and Provider instances.
 *
 * This is the centralized entry point for Leximorph services in MediaWiki.
 *
 * This caches instances in-process, because each instance
 * has to load rule files, parse data, and prepare lookup maps.
 *
 * Example usage:
 *
 * @code
 *    $factory = MediaWikiServices::getInstance()->getLeximorphFactory();
 *    $bcp = new \Wikimedia\Bcp47Code\Bcp47CodeValue('en');
 *    $manager = $factory->getManager( $bcp );
 *    if ( $manager ) {
 *        $pluralHandler = $manager->getPlural();
 *    }
 *
 *    $provider = $factory->getProvider( $bcp );
 *    if ( $provider ) {
 *        $pluralRules = $provider->getPluralProvider();
 *    }
 * @endcode
 *
 * @since 1.45
 * @author Doğu Abaris (abaris@null.net)
 */
class LeximorphFactory {
	/** @internal Only public for service wiring use. */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::UseLeximorph,
	];

	/** @var int Maximum number of cached instances */
	private const CACHE_SIZE = 100;

	/** @var MapCacheLRU Cache for Manager instances */
	private MapCacheLRU $managerCache;

	/** @var MapCacheLRU Cache for Provider instances */
	private MapCacheLRU $providerCache;

	/** @var bool Whether UseLeximorph is enabled */
	private bool $useLeximorph;

	/**
	 * @since 1.45
	 * @param ServiceOptions $options Reads MainConfigNames::UseLeximorph
	 * @param LoggerInterface $logger Logger passed to created Manager/Provider instances.
	 */
	public function __construct(
		ServiceOptions $options,
		private readonly LoggerInterface $logger,
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->useLeximorph = (bool)$options->get( MainConfigNames::UseLeximorph );

		$this->managerCache = new MapCacheLRU( self::CACHE_SIZE );
		$this->providerCache = new MapCacheLRU( self::CACHE_SIZE );
	}

	/**
	 * Get a Manager for the given language code.
	 *
	 * @param Bcp47Code $code BCP‑47 language code
	 * @return ?Manager Manager, or null if Leximorph is disabled
	 */
	public function getManager( Bcp47Code $code ): ?Manager {
		if ( !$this->useLeximorph ) {
			return null;
		}
		$langCode = LanguageCode::bcp47ToInternal( $code );

		return $this->managerCache->getWithSetCallback(
			$langCode,
			fn () => new Manager( $langCode, $this->logger )
		);
	}

	/**
	 * Get a Provider for the given language code.
	 *
	 * @param Bcp47Code $code BCP‑47 language code
	 * @return ?Provider Provider, or null if Leximorph is disabled
	 */
	public function getProvider( Bcp47Code $code ): ?Provider {
		if ( !$this->useLeximorph ) {
			return null;
		}
		$langCode = LanguageCode::bcp47ToInternal( $code );

		return $this->providerCache->getWithSetCallback(
			$langCode,
			fn () => new Provider( $langCode, $this->logger )
		);
	}
}
