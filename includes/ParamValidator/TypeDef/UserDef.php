<?php

namespace MediaWiki\ParamValidator\TypeDef;

use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\TitleParser;
use MediaWiki\User\ExternalUserNames;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserRigorOptions;
use Wikimedia\IPUtils;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\Callbacks;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef;

/**
 * Type definition for user types
 *
 * Failure codes:
 *  - 'baduser': The value was not a valid MediaWiki user. No data.
 *
 * @since 1.35
 */
class UserDef extends TypeDef {

	/**
	 * (string[]) Allowed types of user.
	 *
	 * One or more of the following values:
	 * - 'name': User names are allowed.
	 * - 'ip': IP ("anon") usernames are allowed.
	 * - 'temp': Temporary users are allowed.
	 * - 'cidr': IP ranges are allowed.
	 * - 'interwiki': Interwiki usernames are allowed.
	 * - 'id': Allow specifying user IDs, formatted like "#123".
	 *
	 * Default is `[ 'name', 'ip', 'temp', 'cidr', 'interwiki' ]`.
	 *
	 * Avoid combining 'id' with PARAM_ISMULTI, as it may result in excessive
	 * DB lookups. If you do combine them, consider setting low values for
	 * PARAM_ISMULTI_LIMIT1 and PARAM_ISMULTI_LIMIT2 to mitigate it.
	 */
	public const PARAM_ALLOWED_USER_TYPES = 'param-allowed-user-types';

	/**
	 * (bool) Whether to return a UserIdentity object.
	 *
	 * If false, the validated user name is returned as a string. Default is false.
	 *
	 * Avoid setting true with PARAM_ISMULTI, as it may result in excessive DB
	 * lookups. If you do combine them, consider setting low values for
	 * PARAM_ISMULTI_LIMIT1 and PARAM_ISMULTI_LIMIT2 to mitigate it.
	 */
	public const PARAM_RETURN_OBJECT = 'param-return-object';

	/** @var UserIdentityLookup */
	private $userIdentityLookup;

	/** @var TitleParser */
	private $titleParser;

	/** @var UserNameUtils */
	private $userNameUtils;

	/**
	 * @param Callbacks $callbacks
	 * @param UserIdentityLookup $userIdentityLookup
	 * @param TitleParser $titleParser
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		Callbacks $callbacks,
		UserIdentityLookup $userIdentityLookup,
		TitleParser $titleParser,
		UserNameUtils $userNameUtils
	) {
		parent::__construct( $callbacks );
		$this->userIdentityLookup = $userIdentityLookup;
		$this->titleParser = $titleParser;
		$this->userNameUtils = $userNameUtils;
	}

	public function validate( $name, $value, array $settings, array $options ) {
		$this->failIfNotString( $name, $value, $settings, $options );

		[ $type, $user ] = $this->processUser( $value );

		if ( !$user || !in_array( $type, $settings[self::PARAM_ALLOWED_USER_TYPES], true ) ) {
			// Message used: paramvalidator-baduser
			$this->failure( 'baduser', $name, $value, $settings, $options );
		}

		return empty( $settings[self::PARAM_RETURN_OBJECT] ) ? $user->getName() : $user;
	}

	public function normalizeSettings( array $settings ) {
		if ( isset( $settings[self::PARAM_ALLOWED_USER_TYPES] ) ) {
			$settings[self::PARAM_ALLOWED_USER_TYPES] = array_values( array_intersect(
				[ 'name', 'ip', 'temp', 'cidr', 'interwiki', 'id' ],
				$settings[self::PARAM_ALLOWED_USER_TYPES]
			) );
		}
		if ( empty( $settings[self::PARAM_ALLOWED_USER_TYPES] ) ) {
			$settings[self::PARAM_ALLOWED_USER_TYPES] = [ 'name', 'ip', 'temp', 'cidr', 'interwiki' ];
		}

		return parent::normalizeSettings( $settings );
	}

	public function checkSettings( string $name, $settings, array $options, array $ret ): array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		$ret['allowedKeys'] = array_merge( $ret['allowedKeys'], [
			self::PARAM_ALLOWED_USER_TYPES, self::PARAM_RETURN_OBJECT,
		] );

		if ( !is_bool( $settings[self::PARAM_RETURN_OBJECT] ?? false ) ) {
			$ret['issues'][self::PARAM_RETURN_OBJECT] = 'PARAM_RETURN_OBJECT must be boolean, got '
				. gettype( $settings[self::PARAM_RETURN_OBJECT] );
		}

		$hasId = false;
		if ( isset( $settings[self::PARAM_ALLOWED_USER_TYPES] ) ) {
			if ( !is_array( $settings[self::PARAM_ALLOWED_USER_TYPES] ) ) {
				$ret['issues'][self::PARAM_ALLOWED_USER_TYPES] = 'PARAM_ALLOWED_USER_TYPES must be an array, '
					. 'got ' . gettype( $settings[self::PARAM_ALLOWED_USER_TYPES] );
			} elseif ( $settings[self::PARAM_ALLOWED_USER_TYPES] === [] ) {
				$ret['issues'][self::PARAM_ALLOWED_USER_TYPES] = 'PARAM_ALLOWED_USER_TYPES cannot be empty';
			} else {
				$bad = array_diff(
					$settings[self::PARAM_ALLOWED_USER_TYPES],
					[ 'name', 'ip', 'temp', 'cidr', 'interwiki', 'id' ]
				);
				if ( $bad ) {
					$ret['issues'][self::PARAM_ALLOWED_USER_TYPES] =
						'PARAM_ALLOWED_USER_TYPES contains invalid values: ' . implode( ', ', $bad );
				}

				$hasId = in_array( 'id', $settings[self::PARAM_ALLOWED_USER_TYPES], true );
			}
		}

		if ( !empty( $settings[ParamValidator::PARAM_ISMULTI] ) &&
			( $hasId || !empty( $settings[self::PARAM_RETURN_OBJECT] ) ) &&
			(
				( $settings[ParamValidator::PARAM_ISMULTI_LIMIT1] ?? 100 ) > 10 ||
				( $settings[ParamValidator::PARAM_ISMULTI_LIMIT2] ?? 100 ) > 10
			)
		) {
			$ret['issues'][] = 'Multi-valued user-type parameters with PARAM_RETURN_OBJECT or allowing IDs '
				. 'should set low values (<= 10) for PARAM_ISMULTI_LIMIT1 and PARAM_ISMULTI_LIMIT2.'
				. ' (Note that "<= 10" is arbitrary. If something hits this, we can investigate a real limit '
				. 'once we have a real use case to look at.)';
		}

		return $ret;
	}

	/**
	 * Process $value to a UserIdentity, if possible
	 * @param string $value
	 * @return array [ string $type, UserIdentity|null $user ]
	 * @phan-return array{0:string,1:UserIdentity|null}
	 */
	private function processUser( string $value ): array {
		// A user ID?
		if ( preg_match( '/^#(\d+)$/D', $value, $m ) ) {
			// This used to use the IP address of the current request if the
			// id was 0, to match the behavior of User objects, but was switched
			// to "Unknown user" because the former behavior is likely unexpected.
			// If the id corresponds to a user in the database, use that user, otherwise
			// return a UserIdentityValue with id 0 (regardless of the input id) and
			// the name "Unknown user"
			$userId = (int)$m[1];
			if ( $userId !== 0 ) {
				// Check the database.
				$userIdentity = $this->userIdentityLookup->getUserIdentityByUserId( $userId );
				if ( $userIdentity ) {
					return [ 'id', $userIdentity ];
				}
			}
			// Fall back to "Unknown user"
			return [
				'id',
				new UserIdentityValue( 0, "Unknown user" )
			];
		}

		// An interwiki username?
		if ( ExternalUserNames::isExternal( $value ) ) {
			$name = $this->userNameUtils->getCanonical( $value, UserRigorOptions::RIGOR_NONE );
			// UserIdentityValue has the username which includes the > separating the external
			// wiki database and the actual name, but is created for the *local* wiki, like
			// for User objects (local is the default, but we specify it anyway to show
			// that its intentional even though the username is for a different wiki)
			// NOTE: We deliberately use the raw $value instead of the canonical $name
			// to avoid converting the first character of the interwiki prefix to uppercase
			$user = $name !== false ? new UserIdentityValue( 0, $value, UserIdentityValue::LOCAL ) : null;
			return [ 'interwiki', $user ];
		}

		// A valid user name?
		// Match behavior of UserFactory::newFromName with RIGOR_VALID and User::getId()
		// we know that if there is a canonical form from UserNameUtils then this can't
		// look like an IP, and since we checked for external user names above it isn't
		// that either, so if this is a valid user name then we check the database for
		// the id, and if there is no user with this name the id is 0
		$canonicalName = $this->userNameUtils->getCanonical( $value, UserRigorOptions::RIGOR_VALID );
		if ( $canonicalName !== false ) {
			// Determine if the username matches the temporary account format.
			$userType = $this->userNameUtils->isTemp( $value ) ? 'temp' : 'name';

			$userIdentity = $this->userIdentityLookup->getUserIdentityByName( $canonicalName );
			if ( $userIdentity ) {
				return [ $userType, $userIdentity ];
			}
			// Fall back to id 0, which can occur when the account does not exist.
			return [
				$userType,
				new UserIdentityValue( 0, $canonicalName )
			];
		}

		// (T232672) Reproduce the normalization applied in UserNameUtils::getCanonical() when
		// performing the checks below.
		if ( strpos( $value, '#' ) !== false ) {
			return [ '', null ];
		}

		try {
			$t = $this->titleParser->parseTitle( $value );
		} catch ( MalformedTitleException ) {
			$t = null;
		}
		if ( !$t || $t->getNamespace() !== NS_USER || $t->isExternal() ) { // likely
			try {
				$t = $this->titleParser->parseTitle( "User:$value" );
			} catch ( MalformedTitleException ) {
				$t = null;
			}
		}
		if ( !$t || $t->getNamespace() !== NS_USER || $t->isExternal() ) {
			// If it wasn't a valid User-namespace title, fail.
			return [ '', null ];
		}
		$value = $t->getText();

		// An IP?
		$b = IPUtils::RE_IP_BYTE;
		if ( IPUtils::isValid( $value ) ||
			// See comment for UserNameUtils::isIP. We don't just call that function
			// here because it also returns true for things like
			// 300.300.300.300 that are neither valid usernames nor valid IP
			// addresses.
			preg_match( "/^$b\.$b\.$b\.xxx$/D", $value )
		) {
			$name = IPUtils::sanitizeIP( $value );
			// We don't really need to use UserNameUtils::getCanonical() because for anonymous
			// users the only validation is that there is no `#` (which is already the case if its
			// a valid IP or matches the regex) and the only normalization is making the first
			// character uppercase (doesn't matter for numbers) and replacing underscores with
			// spaces (doesn't apply to IPs). But, better safe than sorry?
			$name = $this->userNameUtils->getCanonical( $name, UserRigorOptions::RIGOR_NONE );
			return [ 'ip', UserIdentityValue::newAnonymous( $name ) ];
		}

		// A range?
		if ( IPUtils::isValidRange( $value ) ) {
			$name = IPUtils::sanitizeIP( $value );
			// Per above, the UserNameUtils call isn't strictly needed, but doesn't hurt
			$name = $this->userNameUtils->getCanonical( $name, UserRigorOptions::RIGOR_NONE );
			return [ 'cidr', UserIdentityValue::newAnonymous( $name ) ];
		}

		// Fail.
		return [ '', null ];
	}

	public function getParamInfo( $name, array $settings, array $options ) {
		$info = parent::getParamInfo( $name, $settings, $options );

		$info['subtypes'] = $settings[self::PARAM_ALLOWED_USER_TYPES];

		return $info;
	}

	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getParamInfo( $name, $settings, $options );

		$isMulti = !empty( $settings[ParamValidator::PARAM_ISMULTI] );

		$subtypes = [];
		foreach ( $settings[self::PARAM_ALLOWED_USER_TYPES] as $st ) {
			// Messages: paramvalidator-help-type-user-subtype-name,
			// paramvalidator-help-type-user-subtype-ip, paramvalidator-help-type-user-subtype-cidr,
			// paramvalidator-help-type-user-subtype-interwiki, paramvalidator-help-type-user-subtype-id,
			// paramvalidator-help-type-user-subtype-temp
			$subtypes[] = MessageValue::new( "paramvalidator-help-type-user-subtype-$st" );
		}
		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-user' )
			->params( $isMulti ? 2 : 1 )
			->textListParams( $subtypes )
			->numParams( count( $subtypes ) );

		return $info;
	}

}
