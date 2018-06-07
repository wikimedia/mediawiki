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
 * @author Happy-melon
 * @file
 */
use MediaWiki\MediaWikiServices;

/**
 * The simplest way of implementing IContextSource is to hold a RequestContext as a
 * member variable and provide accessors to it.
 *
 * @since 1.18
 */
abstract class ContextSource implements IContextSource {
	/**
	 * @var IContextSource
	 */
	private $context;

	/**
	 * Get the base IContextSource object
	 * @since 1.18
	 * @return IContextSource
	 */
	public function getContext() {
		if ( $this->context === null ) {
			$class = static::class;
			wfDebug( __METHOD__ . " ($class): called and \$context is null. " .
				"Using RequestContext::getMain() for sanity\n" );
			$this->context = RequestContext::getMain();
		}

		return $this->context;
	}

	/**
	 * @since 1.18
	 * @param IContextSource $context
	 */
	public function setContext( IContextSource $context ) {
		$this->context = $context;
	}

	/**
	 * @since 1.23
	 * @return Config
	 */
	public function getConfig() {
		return $this->getContext()->getConfig();
	}

	/**
	 * @since 1.18
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->getContext()->getRequest();
	}

	/**
	 * @since 1.18
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
	 * @return WikiPage
	 */
	public function getWikiPage() {
		return $this->getContext()->getWikiPage();
	}

	/**
	 * @since 1.18
	 * @return OutputPage
	 */
	public function getOutput() {
		return $this->getContext()->getOutput();
	}

	/**
	 * @since 1.18
	 * @return User
	 */
	public function getUser() {
		return $this->getContext()->getUser();
	}

	/**
	 * @since 1.19
	 * @return Language
	 */
	public function getLanguage() {
		return $this->getContext()->getLanguage();
	}

	/**
	 * @since 1.18
	 * @return Skin
	 */
	public function getSkin() {
		return $this->getContext()->getSkin();
	}

	/**
	 * @since 1.27
	 * @return Timing
	 */
	public function getTiming() {
		return $this->getContext()->getTiming();
	}

	/**
	 * @deprecated since 1.27 use a StatsdDataFactory from MediaWikiServices (preferably injected)
	 *
	 * @since 1.25
	 * @return IBufferingStatsdDataFactory
	 */
	public function getStats() {
		return MediaWikiServices::getInstance()->getStatsdDataFactory();
	}

	/**
	 * Get a Message object with context set
	 * Parameters are the same as wfMessage()
	 *
	 * @since 1.18
	 * @param string|string[]|MessageSpecifier $key Message key, or array of keys,
	 *   or a MessageSpecifier.
	 * @param mixed $args,...
	 * @return Message
	 */
	public function msg( $key /* $args */ ) {
		$args = func_get_args();

		return $this->getContext()->msg( ...$args );
	}

	/**
	 * Export the resolved user IP, HTTP headers, user ID, and session ID.
	 * The result will be reasonably sized to allow for serialization.
	 *
	 * @return array
	 * @since 1.21
	 */
	public function exportSession() {
		return $this->getContext()->exportSession();
	}
}
