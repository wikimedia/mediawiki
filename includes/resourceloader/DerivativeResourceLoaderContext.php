<?php
/**
 * Derivative context for resource loader modules.
 *
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
 * @author Kunal Mehta
 */

/**
 * Allows changing specific properties of a context object,
 * without changing the main one. Inspired by DerivativeContext.
 *
 * @since 1.24
 */
class DerivativeResourceLoaderContext extends ResourceLoaderContext {

	/**
	 * @var ResourceLoaderContext
	 */
	private $context;
	protected $modules;
	protected $language;
	protected $direction;
	protected $skin;
	protected $user;
	protected $debug;
	protected $only;
	protected $version;
	protected $hash;
	protected $raw;

	public function __construct( ResourceLoaderContext $context ) {
		$this->context = $context;
	}

	public function getModules() {
		if ( !is_null( $this->modules ) ) {
			return $this->modules;
		} else {
			return $this->context->getModules();
		}
	}

	/**
	 * @param string[] $modules
	 */
	public function setModules( array $modules ) {
		$this->modules = $modules;
	}

	public function getLanguage() {
		if ( !is_null( $this->language ) ) {
			return $this->language;
		} else {
			return $this->context->getLanguage();
		}
	}

	/**
	 * @param string $language
	 */
	public function setLanguage( $language ) {
		$this->language = $language;
		$this->direction = null; // Invalidate direction since it might be based on language
		$this->hash = null;
	}

	public function getDirection() {
		if ( !is_null( $this->direction ) ) {
			return $this->direction;
		} else {
			return $this->context->getDirection();
		}
	}

	/**
	 * @param string $direction
	 */
	public function setDirection( $direction ) {
		$this->direction = $direction;
		$this->hash = null;
	}

	public function getSkin() {
		if ( !is_null( $this->skin ) ) {
			return $this->skin;
		} else {
			return $this->context->getSkin();
		}
	}

	/**
	 * @param string $skin
	 */
	public function setSkin( $skin ) {
		$this->skin = $skin;
		$this->hash = null;
	}

	public function getUser() {
		if ( !is_null( $this->user ) ) {
			return $this->user;
		} else {
			return $this->context->getUser();
		}
	}

	/**
	 * @param string $user
	 */
	public function setUser( $user ) {
		$this->user = $user;
		$this->hash = null;
		$this->userObj = null;
	}

	public function getDebug() {
		if ( !is_null( $this->debug ) ) {
			return $this->debug;
		} else {
			return $this->context->getDebug();
		}
	}

	/**
	 * @param bool $debug
	 */
	public function setDebug( $debug ) {
		$this->debug = $debug;
		$this->hash = null;
	}

	public function getOnly() {
		if ( !is_null( $this->only ) ) {
			return $this->only;
		} else {
			return $this->context->getOnly();
		}
	}

	/**
	 * @param string $only
	 */
	public function setOnly( $only ) {
		$this->only = $only;
		$this->hash = null;
	}

	public function getVersion() {
		if ( !is_null( $this->version ) ) {
			return $this->version;
		} else {
			return $this->context->getVersion();
		}
	}

	/**
	 * @param string $version
	 */
	public function setVersion( $version ) {
		$this->version = $version;
		$this->hash = null;
	}

	public function getRaw() {
		if ( !is_null( $this->raw ) ) {
			return $this->raw;
		} else {
			return $this->context->getRaw();
		}
	}

	/**
	 * @param bool $raw
	 */
	public function setRaw( $raw ) {
		$this->raw = $raw;
	}

	public function getRequest() {
		return $this->context->getRequest();
	}

	public function getResourceLoader() {
		return $this->context->getResourceLoader();
	}

}
