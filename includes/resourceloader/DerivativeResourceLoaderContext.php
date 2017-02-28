<?php
/**
 * Derivative context for ResourceLoader modules.
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
	const INHERIT_VALUE = -1;

	/**
	 * @var ResourceLoaderContext
	 */
	private $context;

	protected $modules = self::INHERIT_VALUE;
	protected $language = self::INHERIT_VALUE;
	protected $direction = self::INHERIT_VALUE;
	protected $skin = self::INHERIT_VALUE;
	protected $user = self::INHERIT_VALUE;
	protected $debug = self::INHERIT_VALUE;
	protected $only = self::INHERIT_VALUE;
	protected $version = self::INHERIT_VALUE;
	protected $raw = self::INHERIT_VALUE;
	protected $contentOverrideCallback = self::INHERIT_VALUE;

	public function __construct( ResourceLoaderContext $context ) {
		$this->context = $context;
	}

	public function getModules() {
		if ( $this->modules === self::INHERIT_VALUE ) {
			return $this->context->getModules();
		}
		return $this->modules;
	}

	/**
	 * @param string[] $modules
	 */
	public function setModules( array $modules ) {
		$this->modules = $modules;
	}

	public function getLanguage() {
		if ( $this->language === self::INHERIT_VALUE ) {
			return $this->context->getLanguage();
		}
		return $this->language;
	}

	/**
	 * @param string $language
	 */
	public function setLanguage( $language ) {
		$this->language = $language;
		// Invalidate direction since it is based on language
		$this->direction = null;
		$this->hash = null;
	}

	public function getDirection() {
		if ( $this->direction === self::INHERIT_VALUE ) {
			return $this->context->getDirection();
		}
		if ( $this->direction === null ) {
			$this->direction = Language::factory( $this->getLanguage() )->getDir();
		}
		return $this->direction;
	}

	/**
	 * @param string $direction
	 */
	public function setDirection( $direction ) {
		$this->direction = $direction;
		$this->hash = null;
	}

	public function getSkin() {
		if ( $this->skin === self::INHERIT_VALUE ) {
			return $this->context->getSkin();
		}
		return $this->skin;
	}

	/**
	 * @param string $skin
	 */
	public function setSkin( $skin ) {
		$this->skin = $skin;
		$this->hash = null;
	}

	public function getUser() {
		if ( $this->user === self::INHERIT_VALUE ) {
			return $this->context->getUser();
		}
		return $this->user;
	}

	/**
	 * @param string|null $user
	 */
	public function setUser( $user ) {
		$this->user = $user;
		$this->hash = null;
		$this->userObj = null;
	}

	public function getDebug() {
		if ( $this->debug === self::INHERIT_VALUE ) {
			return $this->context->getDebug();
		}
		return $this->debug;
	}

	/**
	 * @param bool $debug
	 */
	public function setDebug( $debug ) {
		$this->debug = $debug;
		$this->hash = null;
	}

	public function getOnly() {
		if ( $this->only === self::INHERIT_VALUE ) {
			return $this->context->getOnly();
		}
		return $this->only;
	}

	/**
	 * @param string|null $only
	 */
	public function setOnly( $only ) {
		$this->only = $only;
		$this->hash = null;
	}

	public function getVersion() {
		if ( $this->version === self::INHERIT_VALUE ) {
			return $this->context->getVersion();
		}
		return $this->version;
	}

	/**
	 * @param string|null $version
	 */
	public function setVersion( $version ) {
		$this->version = $version;
		$this->hash = null;
	}

	public function getRaw() {
		if ( $this->raw === self::INHERIT_VALUE ) {
			return $this->context->getRaw();
		}
		return $this->raw;
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

	public function getContentOverrideCallback() {
		if ( $this->contentOverrideCallback === self::INHERIT_VALUE ) {
			return $this->context->getContentOverrideCallback();
		}
		return $this->contentOverrideCallback;
	}

	/**
	 * @see self::getContentOverrideCallback
	 * @since 1.32
	 * @param callable|null|int $callback As per self::getContentOverrideCallback,
	 *  or self::INHERIT_VALUE
	 */
	public function setContentOverrideCallback( $callback ) {
		$this->contentOverrideCallback = $callback;
	}

}
