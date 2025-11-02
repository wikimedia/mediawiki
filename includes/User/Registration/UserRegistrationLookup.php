<?php

namespace MediaWiki\User\Registration;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\User\UserIdentity;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * @since 1.41
 */
class UserRegistrationLookup {

	/**
	 * @internal for use in ServiceWiring
	 * @var string[] Config names to require
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::UserRegistrationProviders,
	];

	/** @var array ObjectFactory specs indexed by provider name */
	private array $providersSpecs;

	/** @var IUserRegistrationProvider[] Constructed registration providers indexed by name */
	private array $providers = [];

	/** @var array<string,string|null|false> An in-memory cache for the user registration dates */
	private array $registrationCache = [];
	private const CACHE_MAX_SIZE = 100;

	public function __construct(
		ServiceOptions $options,
		private readonly ObjectFactory $objectFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->providersSpecs = $options->get( MainConfigNames::UserRegistrationProviders );
	}

	/**
	 * Is a registration provider registered?
	 *
	 * @see MainConfigSchema::UserRegistrationLookupProviders
	 * @param string $type
	 * @return bool
	 */
	public function isRegistered( string $type ): bool {
		return array_key_exists( $type, $this->providersSpecs );
	}

	/**
	 * Construct a registration provider, if needed
	 *
	 * @param string $type
	 * @return IUserRegistrationProvider
	 */
	private function getProvider( string $type ): IUserRegistrationProvider {
		if ( !$this->isRegistered( $type ) ) {
			throw new InvalidArgumentException( 'Registration provider ' . $type . ' is not registered' );
		}
		if ( !array_key_exists( $type, $this->providers ) ) {
			$this->providers[$type] = $this->objectFactory->createObject(
				$this->providersSpecs[$type],
				[ 'assertClass' => IUserRegistrationProvider::class ]
			);
		}
		return $this->providers[$type];
	}

	/**
	 * @param UserIdentity $user User for which registration should be fetched.
	 * @param string $type Name of a registered registration provider
	 * @return string|null|false Registration timestamp (TS_MW), null if not available or false if it
	 * cannot be fetched (anonymous users, for example).
	 */
	public function getRegistration(
		UserIdentity $user,
		string $type = LocalUserRegistrationProvider::TYPE
	) {
		$cacheKey = $this->getCacheKey( $user, $type );
		if ( array_key_exists( $cacheKey, $this->registrationCache ) ) {
			return $this->registrationCache[$cacheKey];
		}

		$registration = $this->getProvider( $type )->fetchRegistration( $user );
		if ( $user->isRegistered() ) {
			$this->setCachedRegistration( $user, $registration, $type );
		}
		return $registration;
	}

	/**
	 * Sets the cached registration timestamp for a given user. Can only be used to set
	 * the date for registered users.
	 * If the size of the cache exceeds CACHE_MAX_SIZE, the oldest entry is evicted.
	 */
	public function setCachedRegistration(
		UserIdentity $user,
		string|null|false $timestamp,
		string $type = LocalUserRegistrationProvider::TYPE
	): void {
		if ( !$user->isRegistered() ) {
			throw new InvalidArgumentException( __METHOD__ . ' expects the user to be registered' );
		}

		while ( count( $this->registrationCache ) >= self::CACHE_MAX_SIZE ) {
			$evictKey = array_key_first( $this->registrationCache );
			unset( $this->registrationCache[$evictKey] );
		}

		$cacheKey = $this->getCacheKey( $user, $type );
		/* @phan-suppress-next-line PhanTypeMismatchProperty Phan can't recognize that the types here are the same */
		$this->registrationCache[$cacheKey] = $timestamp;
	}

	/**
	 * Find the first registration timestamp for a given user
	 *
	 * Note this invokes _all_ registered providers.
	 *
	 * @param UserIdentity $user
	 * @return string|null Earliest registration timestamp (TS_MW), null if not available.
	 */
	public function getFirstRegistration( UserIdentity $user ): ?string {
		$firstRegistrationTimestamp = null;
		foreach ( $this->providersSpecs as $providerKey => $_ ) {
			$registrationTimestamp = $this->getRegistration( $user, $providerKey );
			if ( $registrationTimestamp === null || $registrationTimestamp === false ) {
				// Provider was unable to return a registration timestamp for $providerKey, skip
				// them.
				continue;
			}
			if ( $firstRegistrationTimestamp === null ||
				$registrationTimestamp < $firstRegistrationTimestamp
			) {
				$firstRegistrationTimestamp = $registrationTimestamp;
			}
		}

		return $firstRegistrationTimestamp;
	}

	/**
	 * Get the first registration timestamp for a batch of users.
	 * This invokes all registered providers and doesn't use caching.
	 *
	 * @param iterable<UserIdentity> $users
	 * @return string[]|null[] Map of registration timestamps in MediaWiki format keyed by user ID.
	 * The timestamp may be `null` for users without a stored registration timestamp and for anonymous users.
	 */
	public function getFirstRegistrationBatch( iterable $users ): array {
		$earliestTimestampsById = [];

		foreach ( $users as $user ) {
			$earliestTimestampsById[$user->getId()] = null;
		}

		foreach ( $this->providersSpecs as $providerKey => $_ ) {
			$timestampsById = $this->getProvider( $providerKey )->fetchRegistrationBatch( $users );

			foreach ( $timestampsById as $userId => $timestamp ) {
				$curValue = $earliestTimestampsById[$userId];

				if ( $timestamp !== null && ( $curValue === null || $timestamp < $curValue ) ) {
					$earliestTimestampsById[$userId] = $timestamp;
				}
			}
		}

		return $earliestTimestampsById;
	}

	private function getCacheKey( UserIdentity $user, string $providerKey ): string {
		$wikiId = $user->getWikiId();
		$userId = $user->getId( $wikiId );
		$isRemoteWiki = ( $wikiId !== UserIdentity::LOCAL ) && !WikiMap::isCurrentWikiId( $wikiId );

		$cacheKey = $providerKey . ':' . $userId;
		if ( $isRemoteWiki ) {
			$cacheKey .= '@' . $wikiId;
		}
		return $cacheKey;
	}
}
