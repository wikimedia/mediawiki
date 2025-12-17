<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Config\Config;
use MediaWiki\MediaWikiServices;

/**
 * Depend on a MediaWiki configuration variable from the global config.
 *
 * @ingroup Language
 */
class MainConfigDependency extends CacheDependency {
	/** @var string */
	private $name;
	/** @var mixed */
	private $value;

	public function __construct( string $name ) {
		$this->name = $name;
		$this->value = $this->getConfig()->get( $this->name );
	}

	private function getConfig(): Config {
		return MediaWikiServices::getInstance()->getMainConfig();
	}

	/** @inheritDoc */
	public function isExpired( $callback = null ) {
		if ( !$this->getConfig()->has( $this->name ) ) {
			if ( is_callable( $callback ) ) {
				$callback ( "{$this->name} does not exist in configuration" );
			}
			return true;
		}

		if ( $this->getConfig()->get( $this->name ) != $this->value ) {
			// @ silences "var_export does not handle circular references";
			// phpcs:disable Generic.PHP.NoSilencedErrors.Discouraged
			$old = @var_export( $this->value, true );
			$new = @var_export( $this->getConfig()->get( $this->name ), true );
			// phpcs:enable Generic.PHP.NoSilencedErrors.Discouraged
			if ( is_callable( $callback ) ) {
				$callback( "Configuration value {$this->name} changed from {$old} to {$new}" );
			}
			return true;
		}

		return false;
	}
}
