<?php

namespace MediaWiki\ShadowPage;

use MediaWiki\Page\PageReference;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Parsoid\Core\LinkTarget;

/**
 * A service which loads shadow content, which is content that is displayed on
 * a nonexistent page with a valid can-exist title. For example, pages in the
 * MediaWiki namespace show the default message text.
 *
 * A container and factory for ShadowPageProvider instances.
 *
 * @since 1.47
 */
class ShadowPageLoader {
	public const CORE_SPECS = [
		[
			'namespace' => NS_MEDIAWIKI,
			'class' => MessageProvider::class,
			'services' => [
				'MessageCache',
				'ContentLanguage',
				'SlotRoleRegistry',
				'ContentHandlerFactory',
			]
		]
	];

	private const OBJECT_FACTORY_KEYS = [
		'class', 'factory', 'args', 'services', 'optional_services'
	];

	private const CORE = 0;
	private const EXTENSION = 1;

	/** @var ShadowPageProvider[][] */
	private array $providers = [];

	/** @var ShadowPageProvider[][] */
	private array $providersForNamespace = [];

	private ?PageReference $cachedTitle = null;
	private ?ShadowPage $cachedShadow = null;
	private ?MessageProvider $messageProvider = null;

	/**
	 * @param ObjectFactory $objectFactory
	 * @param array $coreSpecs Should be self::CORE_SPECS except while testing
	 * @param array $extensionSpecs Array of associative arrays. The keys
	 *   "class", "factory", "args", "services" and "optional_services"
	 *   are passed down to ObjectFactory. The following additional keys
	 *   are recognised:
	 *     - namespace: If present, only titles with this namespace will
	 *       be given to the provider.
	 */
	public function __construct(
		private ObjectFactory $objectFactory,
		private array $coreSpecs,
		private array $extensionSpecs
	) {
	}

	/**
	 * Try to get a ShadowPage for the given title.
	 */
	public function get( PageReference $title ): ?ShadowPage {
		if ( $this->cachedTitle && $title->isSamePageAs( $this->cachedTitle ) ) {
			return $this->cachedShadow;
		}
		$this->cachedTitle = $title;
		$this->cachedShadow = $this->maybeCreateShadowPage( $title );
		return $this->cachedShadow;
	}

	/**
	 * Check if a link should be shown as existing, due to the existence of
	 * shadow content at that location.
	 */
	public function existsForLink( LinkTarget $link ): bool {
		foreach ( $this->getProvidersForNamespace( $link->getNamespace() ) as $provider ) {
			if ( $provider->existsForLink( $link ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the MessageProvider instance
	 */
	public function getMessageProvider(): MessageProvider {
		if ( $this->messageProvider === null ) {
			foreach ( $this->getProvidersForNamespace( NS_MEDIAWIKI ) as $provider ) {
				if ( $provider instanceof MessageProvider ) {
					$this->messageProvider = $provider;
					break;
				}
			}
		}
		return $this->messageProvider;
	}

	/**
	 * Uncached implementation of get()
	 */
	private function maybeCreateShadowPage( PageReference $title ): ?ShadowPage {
		foreach ( $this->getProvidersForNamespace( $title->getNamespace() ) as $provider ) {
			$shadowPage = $provider->get( $title );
			if ( $shadowPage ) {
				return $shadowPage;
			}
		}
		return null;
	}

	/**
	 * @param int $namespace
	 * @return ShadowPageProvider[]
	 */
	private function getProvidersForNamespace( int $namespace ): array {
		if ( !isset( $this->providersForNamespace[$namespace] ) ) {
			$providers = [];
			foreach ( $this->coreSpecs as $index => $spec ) {
				if ( !isset( $spec['namespace'] ) || $spec['namespace'] === $namespace ) {
					$providers[] = $this->getProvider( self::CORE, $index, $spec );
				}
			}
			foreach ( $this->extensionSpecs as $index => $spec ) {
				if ( !isset( $spec['namespace'] ) || $spec['namespace'] === $namespace ) {
					$providers[] = $this->getProvider( self::EXTENSION, $index, $spec );
				}
			}
			$this->providersForNamespace[$namespace] = $providers;
		}
		return $this->providersForNamespace[$namespace];
	}

	/**
	 * Create and cache a provider instance.
	 *
	 * @param int $origin Either self::CORE or self::EXTENSION
	 * @param int $index The cache index
	 * @param array $spec The specification for provider creation
	 * @return ShadowPageProvider
	 */
	private function getProvider( int $origin, int $index, array $spec ): ShadowPageProvider {
		if ( !isset( $this->providers[$origin][$index] ) ) {
			$objSpec = array_intersect_key( $spec,
				array_fill_keys( self::OBJECT_FACTORY_KEYS, true ) );
			$provider = $this->objectFactory->createObject( $objSpec );
			if ( $provider instanceof BaseShadowPageProvider ) {
				$provider->initBaseDeps( new ParseHelper() );
			}
			$this->providers[$origin][$index] = $provider;
		}
		return $this->providers[$origin][$index];
	}
}
