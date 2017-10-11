<?php

use Wikimedia\Rdbms\LoadBalancer;

class UserOptionsProvider implements IDBAccessObject {
	private $defaultOptions = [];
	/** @var LoadBalancer */
	private $loadBalancer;

	public function __construct( LoadBalancer $loadBalancer ) {
		$this->loadBalancer = $loadBalancer;
	}

	public function getUserOptions( User $user, $flags ) {
		$data = $this->getOptionsInternal( $user, $flags );
		/** @var UserOptions $options */
		$options = null;
		$options = new UserOptions( $data, function() use ( $user, &$options ) {
			$this->saveOptions( $user, $options );
		} );

		return $options;
	}

	public function getDefaultOptions() {
		global $wgNamespacesToBeSearchedDefault, $wgDefaultUserOptions, $wgContLang, $wgDefaultSkin;

		if ( $this->defaultOptions ) {
			return $this->defaultOptions;
		}

		$options = $wgDefaultUserOptions;
		$options['language'] = $wgContLang->getCode();
		foreach ( LanguageConverter::$languagesWithVariants as $langCode ) {
			$options[$langCode == $wgContLang->getCode() ? 'variant' : "variant-$langCode"] = $langCode;
		}

		// NOTE: don't use SearchEngineConfig::getSearchableNamespaces here,
		// since extensions may change the set of searchable namespaces depending
		// on user groups/permissions.
		foreach ( $wgNamespacesToBeSearchedDefault as $nsnum => $val ) {
			$options['searchNs' . $nsnum] = (bool)$val;
		}
		$options['skin'] = Skin::normalizeKey( $wgDefaultSkin );

		Hooks::run( 'UserGetDefaultOptions', [ &$options ] );

		$this->defaultOptions = new ReadOnlyUserOptions( $options );

		return $this->defaultOptions;
	}

	private function saveOptions( User $user, UserOptions $options ) {

	}

	protected function getOptionsInternal( User $user, $flags ) {
		global $wgContLang;

		$options = $this->getDefaultOptions()->getAll();

		if ( !$user->getId() ) {
			// For unlogged-in users, load language/variant options from request.
			// There's no need to do it for logged-in users: they can set preferences,
			// and handling of page content is done by $pageLang->getPreferredVariant() and such,
			// so don't override user's choice (especially when the user chooses site default).
			$variant = $wgContLang->getDefaultVariant();
			$options['variant'] = $variant;
			$options['language'] = $variant;
			return $options;
		}

		// Load from database
		$dbType = ( $flags & self::READ_LATEST )
			? DB_MASTER
			: DB_REPLICA;

		$dbr = $this->loadBalancer->getConnection( $dbType );

		$res = $dbr->select(
			'user_properties',
			[ 'up_property', 'up_value' ],
			[ 'up_user' => $user->getId() ],
			__METHOD__
		);

		$data = [];
		foreach ( $res as $row ) {
			// Convert '0' to 0. PHP's boolean conversion considers them both
			// false, but e.g. JavaScript considers the former as true.
			// @todo: T54542 Somehow determine the desired type (string/int/bool)
			//  and convert all values here.
			if ( $row->up_value === '0' ) {
				$row->up_value = 0;
			}
			$data[$row->up_property] = $row->up_value;
		}

		// Convert the email blacklist from a new line delimited string
		// to an array of ids.
		if ( isset( $data['email-blacklist'] ) && $data['email-blacklist'] ) {
			$data['email-blacklist'] = array_map( 'intval', explode( "\n", $data['email-blacklist'] ) );
		}

		foreach ( $data as $property => $value ) {
			$options[$property] = $value;
		}

		Hooks::run( 'UserLoadOptions', [ $user, &$options ] );

		return $options;
	}
}
