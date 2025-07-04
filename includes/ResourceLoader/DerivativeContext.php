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
 * @author Kunal Mehta
 */

namespace MediaWiki\ResourceLoader;

use MediaWiki\MediaWikiServices;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserRigorOptions;

/**
 * A mutable version of Context.
 *
 * Allows changing specific properties of a context object,
 * without changing the main one. Inspired by MediaWiki's DerivativeContext.
 *
 * @ingroup ResourceLoader
 * @since 1.24
 */
class DerivativeContext extends Context {
	private const INHERIT_VALUE = -1;

	/**
	 * @var Context
	 */
	private $context;

	/** @var int|string[] */
	protected $modules = self::INHERIT_VALUE;
	/** @var int|string */
	protected $language = self::INHERIT_VALUE;
	/** @var int|string|null */
	protected $direction = self::INHERIT_VALUE;
	/** @var int|string */
	protected $skin = self::INHERIT_VALUE;
	/** @var int|string|null */
	protected $user = self::INHERIT_VALUE;
	/** @var int|UserIdentity|null|false */
	protected $userIdentity = self::INHERIT_VALUE;
	/** @var int|User|null */
	protected $userObj = self::INHERIT_VALUE;
	/** @var int */
	protected $debug = self::INHERIT_VALUE;
	/** @var int|string|null */
	protected $only = self::INHERIT_VALUE;
	/** @var int|string|null */
	protected $version = self::INHERIT_VALUE;
	/** @var int|bool */
	protected $raw = self::INHERIT_VALUE;
	/** @var int|bool */
	protected $sourcemap = self::INHERIT_VALUE;
	/** @var int|callable|null */
	protected $contentOverrideCallback = self::INHERIT_VALUE;

	public function __construct( Context $context ) {
		$this->context = $context;
	}

	public function getModules(): array {
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

	public function getLanguage(): string {
		if ( $this->language === self::INHERIT_VALUE ) {
			return $this->context->getLanguage();
		}
		return $this->language;
	}

	public function setLanguage( string $language ) {
		$this->language = $language;
		// Invalidate direction since it is based on language
		$this->direction = null;
		$this->hash = null;
	}

	public function getDirection(): string {
		if ( $this->direction === self::INHERIT_VALUE ) {
			return $this->context->getDirection();
		}
		if ( $this->direction === null ) {
			$this->direction = MediaWikiServices::getInstance()->getLanguageFactory()
				->getLanguage( $this->getLanguage() )->getDir();
		}
		return $this->direction;
	}

	public function setDirection( string $direction ) {
		$this->direction = $direction;
		$this->hash = null;
	}

	public function getSkin(): string {
		if ( $this->skin === self::INHERIT_VALUE ) {
			return $this->context->getSkin();
		}
		return $this->skin;
	}

	public function setSkin( string $skin ) {
		$this->skin = $skin;
		$this->hash = null;
	}

	public function getUser(): ?string {
		if ( $this->user === self::INHERIT_VALUE ) {
			return $this->context->getUser();
		}
		return $this->user;
	}

	public function getUserIdentity(): ?UserIdentity {
		if ( $this->userIdentity === self::INHERIT_VALUE ) {
			return $this->context->getUserIdentity();
		}
		if ( $this->userIdentity === false ) {
			$username = $this->getUser();
			if ( $username === null ) {
				// Anonymous user
				$this->userIdentity = null;
			} else {
				// Use provided username if valid
				$this->userIdentity = MediaWikiServices::getInstance()
					->getUserFactory()
					->newFromName( $username, UserRigorOptions::RIGOR_VALID );
			}
		}
		return $this->userIdentity;
	}

	public function getUserObj(): User {
		if ( $this->userObj === self::INHERIT_VALUE ) {
			return $this->context->getUserObj();
		}
		if ( $this->userObj === null ) {
			$username = $this->getUser();
			$userFactory = MediaWikiServices::getInstance()->getUserFactory();
			if ( $username ) {
				$this->userObj = $userFactory->newFromName( $username, UserRigorOptions::RIGOR_VALID );
			}
			$this->userObj ??= $userFactory->newAnonymous();
		}
		return $this->userObj;
	}

	public function setUser( ?string $user ) {
		$this->user = $user;
		$this->hash = null;
		// Clear getUserObj cache
		$this->userObj = null;
		$this->userIdentity = false;
	}

	public function getDebug(): int {
		if ( $this->debug === self::INHERIT_VALUE ) {
			return $this->context->getDebug();
		}
		return $this->debug;
	}

	public function setDebug( int $debug ) {
		$this->debug = $debug;
		$this->hash = null;
	}

	public function getOnly(): ?string {
		if ( $this->only === self::INHERIT_VALUE ) {
			return $this->context->getOnly();
		}
		return $this->only;
	}

	public function setOnly( ?string $only ) {
		$this->only = $only;
		$this->hash = null;
	}

	public function getVersion(): ?string {
		if ( $this->version === self::INHERIT_VALUE ) {
			return $this->context->getVersion();
		}
		return $this->version;
	}

	public function setVersion( ?string $version ) {
		$this->version = $version;
		$this->hash = null;
	}

	public function getRaw(): bool {
		if ( $this->raw === self::INHERIT_VALUE ) {
			return $this->context->getRaw();
		}
		return $this->raw;
	}

	public function setRaw( bool $raw ) {
		$this->raw = $raw;
	}

	public function isSourceMap(): bool {
		if ( $this->sourcemap === self::INHERIT_VALUE ) {
			return $this->context->isSourceMap();
		}
		return $this->sourcemap;
	}

	public function setIsSourceMap( bool $sourcemap ) {
		$this->sourcemap = $sourcemap;
	}

	public function getRequest(): WebRequest {
		return $this->context->getRequest();
	}

	public function getResourceLoader(): ResourceLoader {
		return $this->context->getResourceLoader();
	}

	/** @inheritDoc */
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
