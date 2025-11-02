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
	public function isExpired() {
		if ( !$this->getConfig()->has( $this->name ) ) {
			return true;
		}

		return $this->getConfig()->get( $this->name ) != $this->value;
	}
}
