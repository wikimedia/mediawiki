<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User\CentralId;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityLookup;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Factory class for CentralIdLookup. Creates instances based on their definitions in
 * the CentralIdLookupProviders extension.json field.
 *
 * @since 1.37
 * @ingroup User
 * @see CentralIdLookup
 * @see MainConfigSchema::CentralIdLookupProviders
 */
class CentralIdLookupFactory {

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CentralIdLookupProviders,
		MainConfigNames::CentralIdLookupProvider,
	];

	/** @var array ObjectFactory specs indexed by provider name */
	private $providers;

	private string $defaultProvider;

	/** @var CentralIdLookup[] */
	private $instanceCache = [];

	public function __construct(
		ServiceOptions $options,
		private readonly ObjectFactory $objectFactory,
		private readonly UserIdentityLookup $userIdentityLookup,
		private readonly UserFactory $userFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->providers = $options->get( MainConfigNames::CentralIdLookupProviders );
		$this->defaultProvider = $options->get( MainConfigNames::CentralIdLookupProvider );
	}

	/**
	 * Get the IDs of the registered central ID lookup providers.
	 *
	 * @return string[]
	 * @see MainConfigSchema::CentralIdLookupProviders
	 */
	public function getProviderIds(): array {
		return array_keys( $this->providers );
	}

	/**
	 * Get the ID of the default central ID provider.
	 * @see MainConfigSchema::CentralIdLookupProvider
	 */
	public function getDefaultProviderId(): string {
		return $this->defaultProvider;
	}

	/**
	 * Get an instance of a CentralIdLookup.
	 *
	 * @param string|null $providerId Provider ID from $wgCentralIdLookupProviders or null
	 *   to use the provider configured in $wgCentralIdLookupProvider
	 * @return CentralIdLookup
	 * @throws InvalidArgumentException if $providerId is not properly configured
	 */
	public function getLookup( ?string $providerId = null ): CentralIdLookup {
		$providerId ??= $this->defaultProvider;

		if ( !array_key_exists( $providerId, $this->instanceCache ) ) {
			$providerSpec = $this->providers[$providerId] ?? null;
			if ( !$providerSpec ) {
				throw new InvalidArgumentException( "Invalid central ID provider $providerId" );
			}
			$provider = $this->objectFactory->createObject(
				$providerSpec,
				[ 'assertClass' => CentralIdLookup::class ]
			);
			$provider->init( $providerId, $this->userIdentityLookup, $this->userFactory );
			$this->instanceCache[$providerId] = $provider;
		}
		return $this->instanceCache[$providerId];
	}

	/**
	 * Returns a CentralIdLookup that is guaranteed to be non-local.
	 * If no such guarantee can be made, returns null.
	 *
	 * If this function returns a non-null CentralIdLookup, that lookup is expected to provide IDs
	 * that are shared with some set of other wikis. However, you should still be cautious
	 * when using those IDs, as they will not necessarily work with *all* other wikis, and it can be
	 * hard to tell if another wiki is in the same set as this one or not.
	 *
	 * @param string|null $providerID Provider ID from $wgCentralIdLookupProviders or null
	 *   to use the provider configured in $wgCentralIdLookupProvider
	 * @return ?CentralIdLookup
	 * @throws InvalidArgumentException if $providerId is not properly configured
	 */
	public function getNonLocalLookup( ?string $providerID = null ): ?CentralIdLookup {
		$centralIdLookup = $this->getLookup( $providerID );
		if ( $centralIdLookup instanceof LocalIdLookup ) {
			/*
			 * A LocalIdLookup (which is the default) may actually be non-local,
			 * if shared user tables are used.
			 * However, we cannot know that here, so play it safe and refuse to return it.
			 * See also T163277 and T170996.
			 */
			return null;
		}
		return $centralIdLookup;
	}
}
