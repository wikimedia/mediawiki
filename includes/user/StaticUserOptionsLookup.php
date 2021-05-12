<?php

namespace MediaWiki\User;

/**
 * A UserOptionsLookup that's just an array. Useful for testing and creating staging environments.
 * Note that unlike UserOptionsManager, no attempt is made to canonincalize user names.
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
	public function getDefaultOptions(): array {
		return $this->defaults;
	}

	/** @inheritDoc */
	public function getOption(
		UserIdentity $user,
		string $oname,
		$defaultOverride = null,
		bool $ignoreHidden = false,
		int $queryFlags = self::READ_NORMAL
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
		int $queryFlags = self::READ_NORMAL
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
}
