<?php

namespace MediaWiki\User\Options;

use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * A UserOptionsLookup that's just an array. Useful for testing and creating staging environments.
 * Note that unlike UserOptionsManager, no attempt is made to canonicalize user names.
 * @since 1.36
 */
class StaticUserOptionsLookup extends UserOptionsLookup {

	/** @var array[] */
	private $userMap;

	/** @var mixed[] */
	private $defaults;

	/**
	 * @param array[] $userMap User options, username => [ option name => value ]
	 * @param mixed[] $defaults Defaults for each option, option name => value
	 */
	public function __construct( array $userMap, array $defaults = [] ) {
		$this->userMap = $userMap;
		$this->defaults = $defaults;
	}

	/** @inheritDoc */
	public function getDefaultOptions( ?UserIdentity $userIdentity = null ): array {
		return $this->defaults;
	}

	/** @inheritDoc */
	public function getOption(
		UserIdentity $user,
		string $oname,
		$defaultOverride = null,
		bool $ignoreHidden = false,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	) {
		$userOptions = $this->getOptions( $user );
		if ( array_key_exists( $oname, $userOptions ) ) {
			return $userOptions[$oname];
		} else {
			return $defaultOverride;
		}
	}

	/** @inheritDoc */
	public function getOptions(
		UserIdentity $user,
		int $flags = 0,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): array {
		$userOptions = [];
		if ( $user->isRegistered() ) {
			$userOptions = $this->userMap[$user->getName()] ?? [];
		}
		if ( !( $flags & self::EXCLUDE_DEFAULTS ) ) {
			$userOptions += $this->defaults;
		}
		return $userOptions;
	}

	/** @inheritDoc */
	public function getOptionBatchForUserNames( array $users, string $key ) {
		$options = [];
		foreach ( $users as $name ) {
			$options[$name] = $this->userMap[$name][$key] ?? $this->defaults[$key] ?? '';
		}
		return $options;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( StaticUserOptionsLookup::class, 'MediaWiki\User\StaticUserOptionsLookup' );
