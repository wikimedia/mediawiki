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
			$class = get_class( $this );
			wfDebug( __METHOD__ . " ($class): called and \$context is null. " .
				"Using RequestContext::getMain() for sanity\n" );
			$this->context = RequestContext::getMain();
		}

		return $this->context;
	}

	/**
	 * Set the IContextSource object
	 *
	 * @since 1.18
	 * @param IContextSource $context
	 */
	public function setContext( IContextSource $context ) {
		$this->context = $context;
	}

	/**
	 * Get the Config object
	 *
	 * @since 1.23
	 * @return Config
	 */
	public function getConfig() {
		return $this->getContext()->getConfig();
	}

	/**
	 * Get the WebRequest object
	 *
	 * @since 1.18
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->getContext()->getRequest();
	}

	/**
	 * Get the Title object
	 *
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
	 * Get the OutputPage object
	 *
	 * @since 1.18
	 * @return OutputPage
	 */
	public function getOutput() {
		return $this->getContext()->getOutput();
	}

	/**
	 * Get the User object
	 *
	 * @since 1.18
	 * @return User
	 */
	public function getUser() {
		return $this->getContext()->getUser();
	}

	/**
	 * Get the Language object
	 *
	 * @since 1.19
	 * @return Language
	 */
	public function getLanguage() {
		return $this->getContext()->getLanguage();
	}

	/**
	 * Get the Skin object
	 *
	 * @since 1.18
	 * @return Skin
	 */
	public function getSkin() {
		return $this->getContext()->getSkin();
	}

	/**
	 * Get the Stats object
	 *
	 * @since 1.25
	 * @return BufferingStatsdDataFactory
	 */
	public function getStats() {
		return $this->getContext()->getStats();
	}


	/**
	 * Get a Message object with context set
	 * Parameters are the same as wfMessage()
	 *
	 * @since 1.18
	 * @param mixed ...
	 * @return Message
	 */
	public function msg( /* $args */ ) {
		$args = func_get_args();

		return call_user_func_array( array( $this->getContext(), 'msg' ), $args );
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
