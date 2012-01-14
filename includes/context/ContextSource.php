<?php
/**
 * Request-dependant objects containers.
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
 * @since 1.18
 *
 * @author Happy-melon
 * @file
 */

/**
 * The simplest way of implementing IContextSource is to hold a RequestContext as a
 * member variable and provide accessors to it.
 */
abstract class ContextSource implements IContextSource {

	/**
	 * @var IContextSource
	 */
	private $context;

	/**
	 * Get the RequestContext object
	 * @since 1.18
	 * @return RequestContext
	 */
	public function getContext() {
		if ( $this->context === null ) {
			$class = get_class( $this );
			wfDebug( __METHOD__  . " ($class): called and \$context is null. Using RequestContext::getMain() for sanity\n" );
			$this->context = RequestContext::getMain();
		}
		return $this->context;
	}

	/**
	 * Set the IContextSource object
	 *
	 * @since 1.18
	 * @param $context IContextSource
	 */
	public function setContext( IContextSource $context ) {
		$this->context = $context;
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
	 * @return Title
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
	 * @return OutputPage object
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
	 * @deprecated 1.19 Use getLanguage instead
	 * @return Language
	 */
	public function getLang() {
		wfDeprecated( __METHOD__, '1.19' );
		return $this->getLanguage();
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
	 * Get a Message object with context set
	 * Parameters are the same as wfMessage()
	 *
	 * @since 1.18
	 * @return Message object
	 */
	public function msg( /* $args */ ) {
		$args = func_get_args();
		return call_user_func_array( array( $this->getContext(), 'msg' ), $args );
	}
	
}

