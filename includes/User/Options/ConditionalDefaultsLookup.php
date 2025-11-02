<?php

namespace MediaWiki\User\Options;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityUtils;
use Wikimedia\Timestamp\ConvertibleTimestamp;

class ConditionalDefaultsLookup {

	/**
	 * @internal Exposed for ServiceWiring only
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ConditionalUserOptions,
	];

	private HookRunner $hookRunner;
	private ServiceOptions $options;
	private UserRegistrationLookup $userRegistrationLookup;
	private UserIdentityUtils $userIdentityUtils;
	/**
	 * UserGroupManager must be provided as a callback function to avoid circular dependency
	 * @var callable
	 */
	private $userGroupManagerCallback;
	private ?array $extraConditions = null;

	public function __construct(
		HookRunner $hookRunner,
		ServiceOptions $options,
		UserRegistrationLookup $userRegistrationLookup,
		UserIdentityUtils $userIdentityUtils,
		callable $userGroupManagerCallback
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->hookRunner = $hookRunner;
		$this->options = $options;
		$this->userRegistrationLookup = $userRegistrationLookup;
		$this->userIdentityUtils = $userIdentityUtils;
		$this->userGroupManagerCallback = $userGroupManagerCallback;
	}

	/**
	 * Does the option support conditional defaults?
	 *
	 * @param string $option
	 * @return bool
	 */
	public function hasConditionalDefault( string $option ): bool {
		return array_key_exists(
			$option,
			$this->options->get( MainConfigNames::ConditionalUserOptions )
		);
	}

	/**
	 * Get all conditionally default user options
	 *
	 * @return string[]
	 */
	public function getConditionallyDefaultOptions(): array {
		return array_keys(
			$this->options->get( MainConfigNames::ConditionalUserOptions )
		);
	}

	/**
	 * Get the conditional default for user and option
	 *
	 * @param string $optionName
	 * @param UserIdentity $userIdentity
	 * @return mixed|null The default value if set, or null if it cannot be determined
	 * conditionally (default from DefaultOptionsLookup should be used in that case).
	 */
	public function getOptionDefaultForUser(
		string $optionName,
		UserIdentity $userIdentity
	) {
		$conditionalDefaults = $this->options
			->get( MainConfigNames::ConditionalUserOptions )[$optionName] ?? [];
		foreach ( $conditionalDefaults as $conditionalDefault ) {
			// At the zeroth index of the conditional case, the intended value is found; the rest
			// of the array are conditions, which are evaluated in checkConditionsForUser().
			$value = array_shift( $conditionalDefault );
			if ( $this->checkConditionsForUser( $userIdentity, $conditionalDefault ) ) {
				return $value;
			}
		}

		return null;
	}

	/**
	 * Are ALL conditions satisfied for the given user?
	 *
	 * @param UserIdentity $userIdentity
	 * @param array $conditions
	 * @return bool
	 */
	private function checkConditionsForUser( UserIdentity $userIdentity, array $conditions ): bool {
		foreach ( $conditions as $condition ) {
			if ( !$this->checkConditionForUser( $userIdentity, $condition ) ) {
				return false;
			}
		}
		return true;
	}

	private function getExtraConditions(): array {
		if ( !$this->extraConditions ) {
			$this->extraConditions = [];
			$this->hookRunner->onConditionalDefaultOptionsAddCondition( $this->extraConditions );
		}
		return $this->extraConditions;
	}

	/**
	 * Is ONE condition satisfied for the given user?
	 *
	 * @param UserIdentity $userIdentity
	 * @param array|int $cond Either [ CUDCOND_*, args ] or CUDCOND_*, depending on whether the
	 * condition has any arguments.
	 * @return bool
	 */
	private function checkConditionForUser(
		UserIdentity $userIdentity,
		$cond
	): bool {
		if ( !is_array( $cond ) ) {
			$cond = [ $cond ];
		}
		if ( $cond === [] ) {
			throw new InvalidArgumentException( 'Empty condition' );
		}
		$condName = array_shift( $cond );
		switch ( $condName ) {
			case CUDCOND_AFTER:
				$registration = $this->userRegistrationLookup->getRegistration( $userIdentity );
				if ( $registration === null || $registration === false ) {
					return false;
				}

				return $registration > ConvertibleTimestamp::convert( TS_MW, $cond[0] );
			case CUDCOND_ANON:
				return !$userIdentity->isRegistered();
			case CUDCOND_NAMED:
				return $this->userIdentityUtils->isNamed( $userIdentity );
			case CUDCOND_USERGROUP:
				$userGroupManagerCallback = $this->userGroupManagerCallback;
				/** @var UserGroupManager */
				$userGroupManager = $userGroupManagerCallback();
				return in_array( $cond[0], $userGroupManager->getUserEffectiveGroups( $userIdentity ) );
			default:
				$extraConditions = $this->getExtraConditions();
				if ( array_key_exists( $condName, $extraConditions ) ) {
					return $extraConditions[$condName]( $userIdentity, $cond );
				}
				throw new InvalidArgumentException( 'Unsupported condition ' . $condName );
		}
	}
}
