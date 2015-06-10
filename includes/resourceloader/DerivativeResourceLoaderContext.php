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
	protected $modules = -1;
	protected $language = -1;
	protected $direction = -1;
	protected $skin = -1;
	protected $user = -1;
	protected $debug = -1;
	protected $only = -1;
	protected $version = -1;
	protected $hash;
	protected $raw = -1;

	public function __construct( ResourceLoaderContext $context ) {
		$this->context = $context;
	}

	public function getModules() {
		if ( $this->modules !== -1 ) {
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
		if ( $this->language !== -1 ) {
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
		$this->direction = -1; // Invalidate direction since it might be based on language
		$this->hash = null;
	}

	public function getDirection() {
		if ( $this->direction !== -1 ) {
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
		if ( $this->skin !== -1 ) {
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
		if ( $this->user !== -1 ) {
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
		if ( $this->debug !== -1 ) {
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
		if ( $this->only !== -1 ) {
			return $this->only;
		} else {
			return $this->context->getOnly();
		}
	}

	/**
	 * @param string|null $only
	 */
	public function setOnly( $only ) {
		$this->only = $only;
		$this->hash = null;
	}

	public function getVersion() {
		if ( $this->version !== -1 ) {
			return $this->version;
		} else {
			return $this->context->getVersion();
		}
	}

	/**
	 * @param string|null $version
	 */
	public function setVersion( $version ) {
		$this->version = $version;
		$this->hash = null;
	}

	public function getRaw() {
		if ( $this->raw !== -1 ) {
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
