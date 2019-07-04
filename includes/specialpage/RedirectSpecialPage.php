<?php
/**
 * Shortcuts to construct a special page alias.
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
 * @ingroup SpecialPage
 */

/**
 * Shortcut to construct a special page alias.
 *
 * @ingroup SpecialPage
 */
abstract class RedirectSpecialPage extends UnlistedSpecialPage {
	// Query parameters that can be passed through redirects
	protected $mAllowedRedirectParams = [];

	// Query parameters added by redirects
	protected $mAddedRedirectParams = [];

	/**
	 * @param string|null $subpage
	 */
	public function execute( $subpage ) {
		$redirect = $this->getRedirect( $subpage );
		$query = $this->getRedirectQuery( $subpage );

		if ( $redirect instanceof Title ) {
			// Redirect to a page title with possible query parameters
			$url = $redirect->getFullUrlForRedirect( $query );
			$this->getOutput()->redirect( $url );
		} elseif ( $redirect === true ) {
			// Redirect to index.php with query parameters
			$url = wfAppendQuery( wfScript( 'index' ), $query );
			$this->getOutput()->redirect( $url );
		} else {
			$this->showNoRedirectPage();
		}
	}

	/**
	 * If the special page is a redirect, then get the Title object it redirects to.
	 * False otherwise.
	 *
	 * @param string|null $subpage
	 * @return Title|bool
	 */
	abstract public function getRedirect( $subpage );

	/**
	 * Return part of the request string for a special redirect page
	 * This allows passing, e.g. action=history to Special:Mypage, etc.
	 *
	 * @param string|null $subpage
	 * @return array|bool
	 */
	public function getRedirectQuery( $subpage ) {
		$params = [];
		$request = $this->getRequest();

		foreach ( array_merge( $this->mAllowedRedirectParams,
				[ 'uselang', 'useskin', 'debug', 'safemode' ] // parameters which can be passed to all pages
			) as $arg ) {
			if ( $request->getVal( $arg, null ) !== null ) {
				$params[$arg] = $request->getVal( $arg );
			} elseif ( $request->getArray( $arg, null ) !== null ) {
				$params[$arg] = $request->getArray( $arg );
			}
		}

		foreach ( $this->mAddedRedirectParams as $arg => $val ) {
			$params[$arg] = $val;
		}

		return count( $params )
			? $params
			: false;
	}

	/**
	 * Indicate if the target of this redirect can be used to identify
	 * a particular user of this wiki (e.g., if the redirect is to the
	 * user page of a User). See T109724.
	 *
	 * @since 1.27
	 * @return bool
	 */
	public function personallyIdentifiableTarget() {
		return false;
	}

	protected function showNoRedirectPage() {
		$class = static::class;
		throw new MWException( "RedirectSpecialPage $class doesn't redirect!" );
	}
}
