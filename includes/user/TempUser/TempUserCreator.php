<?php

namespace MediaWiki\User\TempUser;

use ExtensionRegistry;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\Throttler;
use MediaWiki\Session\Session;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserRigorOptions;
use UnexpectedValueException;
use WebRequest;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Service for temporary user creation. For convenience this also proxies the
 * TempUserConfig methods.
 *
 * This is separate from TempUserConfig to avoid dependency loops. Special pages
 * and actions are free to use this class, but services should take it as a
 * constructor parameter only if necessary.
 *
 * @since 1.39
 */
class TempUserCreator implements TempUserConfig {
	/** @var RealTempUserConfig */
	private $config;

	/** @var UserFactory */
	private $userFactory;

	/** @var AuthManager */
	private $authManager;

	/** @var Throttler|null */
	private $throttler;

	/** @var array */
	private $serialProviderConfig;

	/** @var array */
	private $serialMappingConfig;

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var SerialProvider|null */
	private $serialProvider;

	/** @var SerialMapping|null */
	private $serialMapping;

	/** ObjectFactory specs for the core serial providers */
	private const SERIAL_PROVIDERS = [
		'local' => [
			'class' => LocalSerialProvider::class,
			'services' => [ 'DBLoadBalancer' ],
		]
	];

	/** ObjectFactory specs for the core serial maps */
	private const SERIAL_MAPPINGS = [
		'plain-numeric' => [
			'class' => PlainNumericSerialMapping::class,
		],
		'localized-numeric' => [
			'class' => LocalizedNumericSerialMapping::class,
			'services' => [ 'LanguageFactory' ],
		],
		'filtered-radix' => [
			'class' => FilteredRadixSerialMapping::class,
		],
		'scramble' => [
			'class' => ScrambleMapping::class,
		]
	];

	public function __construct(
		RealTempUserConfig $config,
		ObjectFactory $objectFactory,
		UserFactory $userFactory,
		AuthManager $authManager,
		?Throttler $throttler
	) {
		$this->config = $config;
		$this->objectFactory = $objectFactory;
		$this->userFactory = $userFactory;
		$this->authManager = $authManager;
		$this->throttler = $throttler;
		$this->serialProviderConfig = $config->getSerialProviderConfig();
		$this->serialMappingConfig = $config->getSerialMappingConfig();
	}

	/**
	 * Acquire a serial number, create the corresponding user and log in.
	 *
	 * @param string|null $name Previously acquired name
	 * @param WebRequest|null $request Request details, used for throttling
	 * @return CreateStatus
	 */
	public function create( $name = null, WebRequest $request = null ): CreateStatus {
		$status = new CreateStatus;
		if ( $request && $this->throttler ) {
			// TODO: This is duplicated from ThrottlePreAuthenticationProvider
			// and should be factored out, see T261744
			$result = $this->throttler->increase(
				null, $request->getIP(), 'TempUserCreator' );
			if ( $result ) {
				$message = wfMessage( 'acct_creation_throttle_hit' )->params( $result['count'] )
					->durationParams( $result['wait'] );
				$status->fatal( $message );
				return $status;
			}
		}

		if ( $name === null ) {
			$name = $this->acquireName();
		}

		$user = $this->userFactory->newFromName( $name, UserRigorOptions::RIGOR_USABLE );
		if ( !$user ) {
			$status->fatal( 'internalerror_info',
				'Unable to create user with automatically generated name' );
			return $status;
		}

		$status = $this->authManager->autoCreateUser(
			$user,
			AuthManager::AUTOCREATE_SOURCE_TEMP,
			true /* login */,
			false /* log */
		);
		$createStatus = new CreateStatus;
		$createStatus->merge( $status );
		// Make userexists warning be fatal
		if ( $createStatus->hasMessage( 'userexists' ) ) {
			$createStatus->fatal( 'userexists' );
		}
		if ( $createStatus->isOK() ) {
			$createStatus->value = $user;
		}
		return $createStatus;
	}

	public function isEnabled() {
		return $this->config->isEnabled();
	}

	public function isAutoCreateAction( string $action ) {
		return $this->config->isAutoCreateAction( $action );
	}

	public function isReservedName( string $name ) {
		return $this->config->isReservedName( $name );
	}

	public function getPlaceholderName(): string {
		return $this->config->getPlaceholderName();
	}

	/**
	 * Acquire a new username and return it. Permanently reserve the ID in
	 * the database.
	 *
	 * @return string
	 */
	private function acquireName(): string {
		$index = $this->getSerialProvider()->acquireIndex();
		$serialId = $this->getSerialMapping()->getSerialIdForIndex( $index );
		return $this->config->getGeneratorPattern()->generate( $serialId );
	}

	/**
	 * Get the serial provider
	 * @return SerialProvider
	 */
	private function getSerialProvider(): SerialProvider {
		if ( !$this->serialProvider ) {
			$this->serialProvider = $this->createSerialProvider();
		}
		return $this->serialProvider;
	}

	/**
	 * Create the serial provider
	 * @return SerialProvider
	 */
	private function createSerialProvider(): SerialProvider {
		$type = $this->serialProviderConfig['type'];
		if ( isset( self::SERIAL_PROVIDERS[$type] ) ) {
			$spec = self::SERIAL_PROVIDERS[$type];
		} else {
			$extensionProviders = ExtensionRegistry::getInstance()
				->getAttribute( 'TempUserSerialProviders' );
			if ( isset( $extensionProviders[$type] ) ) {
				$spec = $extensionProviders[$type];
			} else {
				throw new UnexpectedValueException( __CLASS__ . ": unknown serial provider \"$type\"" );
			}
		}

		/** @noinspection PhpIncompatibleReturnTypeInspection */
		// @phan-suppress-next-line PhanTypeInvalidCallableArrayKey
		return $this->objectFactory->createObject(
			$spec,
			[
				'assertClass' => SerialProvider::class,
				'extraArgs' => [ $this->serialProviderConfig ]
			]
		);
	}

	/**
	 * Get the serial mapping
	 * @return SerialMapping
	 */
	private function getSerialMapping(): SerialMapping {
		if ( !$this->serialMapping ) {
			$this->serialMapping = $this->createSerialMapping();
		}
		return $this->serialMapping;
	}

	/**
	 * Create the serial map
	 * @return SerialMapping
	 */
	private function createSerialMapping(): SerialMapping {
		$type = $this->serialMappingConfig['type'];
		if ( isset( self::SERIAL_MAPPINGS[$type] ) ) {
			$spec = self::SERIAL_MAPPINGS[$type];
		} else {
			$extensionMappings = ExtensionRegistry::getInstance()
				->getAttribute( 'TempUserSerialMappings' );
			if ( isset( $extensionMappings[$type] ) ) {
				$spec = $extensionMappings[$type];
			} else {
				throw new UnexpectedValueException( __CLASS__ . ": unknown serial mapping \"$type\"" );
			}
		}
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		// @phan-suppress-next-line PhanTypeInvalidCallableArrayKey
		return $this->objectFactory->createObject(
			$spec,
			[
				'assertClass' => SerialMapping::class,
				'extraArgs' => [ $this->serialMappingConfig ]
			]
		);
	}

	/**
	 * Permanently acquire a username, stash it in a session, and return it.
	 * Do not create the user.
	 *
	 * If this method was called before with the same session ID, return the
	 * previously stashed username instead of acquiring a new one.
	 *
	 * @param Session $session
	 * @return string The username
	 */
	public function acquireAndStashName( Session $session ) {
		$name = $session->get( 'TempUser:name' );
		if ( $name !== null ) {
			return $name;
		}
		$name = $this->acquireName();
		$session->set( 'TempUser:name', $name );
		$session->save();
		return $name;
	}
}
