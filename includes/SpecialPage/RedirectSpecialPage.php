<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\SpecialPage;

use LogicException;
use MediaWiki\Title\Title;

/**
 * Shortcut to construct a special page alias.
 *
 * @stable to extend
 *
 * @ingroup SpecialPage
 */
abstract class RedirectSpecialPage extends UnlistedSpecialPage {
	/** @var array Query parameters that can be passed through redirects */
	protected $mAllowedRedirectParams = [];

	/** @var array Query parameters added by redirects */
	protected $mAddedRedirectParams = [];

	/**
	 * @stable to override
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
	 * @stable to override
	 * @param string|null $subpage
	 * @return array|false
	 */
	public function getRedirectQuery( $subpage ) {
		$params = [];
		$request = $this->getRequest();

		foreach ( array_merge(
			$this->mAllowedRedirectParams,
			// parameters which can be passed to all pages
			[ 'uselang', 'useskin', 'useformat', 'variant', 'debug', 'safemode' ]
		) as $arg ) {
			if ( $request->getVal( $arg ) !== null ) {
				$params[$arg] = $request->getVal( $arg );
			} elseif ( $request->getArray( $arg ) !== null ) {
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
	 * @stable to override
	 * @since 1.27
	 * @return bool
	 */
	public function personallyIdentifiableTarget() {
		return false;
	}

	/**
	 * @stable to override
	 */
	protected function showNoRedirectPage() {
		$class = static::class;
		throw new LogicException( "RedirectSpecialPage $class doesn't redirect!" );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( RedirectSpecialPage::class, 'RedirectSpecialPage' );
