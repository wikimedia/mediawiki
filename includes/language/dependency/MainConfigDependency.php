<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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

	public function isExpired() {
		if ( !$this->getConfig()->has( $this->name ) ) {
			return true;
		}

		return $this->getConfig()->get( $this->name ) != $this->value;
	}
}
