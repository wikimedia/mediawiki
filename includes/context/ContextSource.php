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

namespace MediaWiki\Context;

use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\Message\Message;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\WikiPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\WebRequest;
use MediaWiki\Session\CsrfTokenSet;
use MediaWiki\Skin\Skin;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\NonSerializable\NonSerializableTrait;
use Wikimedia\Timing\Timing;

/**
 * The simplest way of implementing IContextSource is to hold a RequestContext as a
 * member variable and provide accessors to it.
 *
 * @stable to extend
 * @since 1.18
 * @author Happy-melon
 */
abstract class ContextSource implements IContextSource {
	use NonSerializableTrait;

	/**
	 * @var IContextSource
	 */
	private $context;

	/**
	 * Get the base IContextSource object
	 * @since 1.18
	 * @stable to override
	 * @return IContextSource
	 */
	public function getContext() {
		if ( $this->context === null ) {
			$class = static::class;
			wfDebug( __METHOD__ . " ($class): called and \$context is null. " .
				"Using RequestContext::getMain()" );
			$this->context = RequestContext::getMain();
		}

		return $this->context;
	}

	/**
	 * @since 1.18
	 * @stable to override
	 * @param IContextSource $context
	 */
	public function setContext( IContextSource $context ) {
		$this->context = $context;
	}

	/**
	 * @since 1.23
	 * @stable to override
	 * @return Config
	 */
	public function getConfig() {
		return $this->getContext()->getConfig();
	}

	/**
	 * @since 1.18
	 * @stable to override
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->getContext()->getRequest();
	}

	/**
	 * @since 1.18
	 * @stable to override
	 * @return Title|null
	 */
	public function getTitle() {
		return $this->getContext()->getTitle();
	}

	/**
	 * Check whether a WikiPage object can be get with getWikiPage().
	 * Callers should expect that an exception is thrown from getWikiPage()
	 * if this method returns false.
	 *
	 * @since 1.19
	 * @stable to override
	 * @return bool
	 */
	public function canUseWikiPage() {
		return $this->getContext()->canUseWikiPage();
	}

	/**
	 * Get the WikiPage object.
	 * May throw an exception if there's no Title object set or the Title object
	 * belongs to a special namespace that doesn't have WikiPage, so use first
	 * canUseWikiPage() to check whether this method can be called safely.
	 *
	 * @since 1.19
	 * @stable to override
	 * @return WikiPage
	 */
	public function getWikiPage() {
		return $this->getContext()->getWikiPage();
	}

	/**
	 * Get the action name for the current web request.
	 *
	 * @since 1.38
	 * @stable to override
	 * @return string
	 */
	public function getActionName(): string {
		return $this->getContext()->getActionName();
	}

	/**
	 * @since 1.18
	 * @stable to override
	 * @return OutputPage
	 */
	public function getOutput() {
		return $this->getContext()->getOutput();
	}

	/**
	 * @stable to override
	 * @since 1.18
	 * @stable to override
	 * @return User
	 */
	public function getUser() {
		return $this->getContext()->getUser();
	}

	/**
	 * @since 1.36
	 * @return Authority
	 */
	public function getAuthority(): Authority {
		return $this->getContext()->getAuthority();
	}

	/**
	 * @since 1.19
	 * @stable to override
	 * @return Language
	 */
	public function getLanguage() {
		return $this->getContext()->getLanguage();
	}

	/**
	 * @since 1.42
	 * @stable to override
	 * @note When overriding, keep consistent with getLanguage()!
	 * @return Bcp47Code
	 */
	public function getLanguageCode(): Bcp47Code {
		return $this->getLanguage();
	}

	/**
	 * @since 1.18
	 * @stable to override
	 * @return Skin
	 */
	public function getSkin() {
		return $this->getContext()->getSkin();
	}

	/**
	 * @since 1.27
	 * @stable to override
	 * @return Timing
	 */
	public function getTiming() {
		return $this->getContext()->getTiming();
	}

	/**
	 * Get a Message object with context set
	 * Parameters are the same as wfMessage()
	 *
	 * @since 1.18
	 * @stable to override
	 * @param string|string[]|MessageSpecifier $key Message key, or array of keys,
	 *   or a MessageSpecifier.
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return Message
	 */
	public function msg( $key, ...$params ) {
		return $this->getContext()->msg( $key, ...$params );
	}

	/**
	 * Export the resolved user IP, HTTP headers, user ID, and session ID.
	 * The result will be reasonably sized to allow for serialization.
	 *
	 * @since 1.21
	 * @stable to override
	 * @return array
	 */
	public function exportSession() {
		return $this->getContext()->exportSession();
	}

	/**
	 * Get a repository to obtain and match CSRF tokens.
	 *
	 * @return CsrfTokenSet
	 * @since 1.37
	 */
	public function getCsrfTokenSet(): CsrfTokenSet {
		return $this->getContext()->getCsrfTokenSet();
	}
}

/** @deprecated class alias since 1.42 */
class_alias( ContextSource::class, 'ContextSource' );
