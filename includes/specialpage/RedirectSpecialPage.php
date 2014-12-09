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
	protected $mAllowedRedirectParams = array();

	// Query parameters added by redirects
	protected $mAddedRedirectParams = array();

	public function execute( $par ) {
		$redirect = $this->getRedirect( $par );
		$query = $this->getRedirectQuery();
		// Redirect to a page title with possible query parameters
		if ( $redirect instanceof Title ) {
			$url = $redirect->getFullURL( $query );
			$this->getOutput()->redirect( $url );

			return $redirect;
		} elseif ( $redirect === true ) {
			// Redirect to index.php with query parameters
			$url = wfAppendQuery( wfScript( 'index' ), $query );
			$this->getOutput()->redirect( $url );

			return $redirect;
		} else {
			$class = get_class( $this );
			throw new MWException( "RedirectSpecialPage $class doesn't redirect!" );
		}
	}

	/**
	 * If the special page is a redirect, then get the Title object it redirects to.
	 * False otherwise.
	 *
	 * @param string $par Subpage string
	 * @return Title|bool
	 */
	abstract public function getRedirect( $par );

	/**
	 * Return part of the request string for a special redirect page
	 * This allows passing, e.g. action=history to Special:Mypage, etc.
	 *
	 * @return string
	 */
	public function getRedirectQuery() {
		$params = array();
		$request = $this->getRequest();

		foreach ( $this->mAllowedRedirectParams as $arg ) {
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
}

/**
 * @ingroup SpecialPage
 */
abstract class SpecialRedirectToSpecial extends RedirectSpecialPage {
	/** @var string Name of redirect target */
	protected $redirName;

	/** @var string Name of subpage of redirect target */
	protected $redirSubpage;

	function __construct(
		$name, $redirName, $redirSubpage = false,
		$allowedRedirectParams = array(), $addedRedirectParams = array()
	) {
		parent::__construct( $name );
		$this->redirName = $redirName;
		$this->redirSubpage = $redirSubpage;
		$this->mAllowedRedirectParams = $allowedRedirectParams;
		$this->mAddedRedirectParams = $addedRedirectParams;
	}

	public function getRedirect( $subpage ) {
		if ( $this->redirSubpage === false ) {
			return SpecialPage::getTitleFor( $this->redirName, $subpage );
		} else {
			return SpecialPage::getTitleFor( $this->redirName, $this->redirSubpage );
		}
	}
}

/**
 * Superclass for any RedirectSpecialPage which redirects the user
 * to a particular article (as opposed to user contributions, logs, etc.).
 *
 * For security reasons these special pages are restricted to pass on
 * the following subset of GET parameters to the target page while
 * removing all others:
 *
 * - useskin, uselang, printable: to alter the appearance of the resulting page
 *
 * - redirect: allows viewing one's user page or talk page even if it is a
 * redirect.
 *
 * - rdfrom: allows redirecting to one's user page or talk page from an
 * external wiki with the "Redirect from..." notice.
 *
 * - limit, offset: Useful for linking to history of one's own user page or
 * user talk page. For example, this would be a link to "the last edit to your
 * user talk page in the year 2010":
 * http://en.wikipedia.org/wiki/Special:MyPage?offset=20110000000000&limit=1&action=history
 *
 * - feed: would allow linking to the current user's RSS feed for their user
 * talk page:
 * http://en.wikipedia.org/w/index.php?title=Special:MyTalk&action=history&feed=rss
 *
 * - preloadtitle: Can be used to provide a default section title for a
 * preloaded new comment on one's own talk page.
 *
 * - summary : Can be used to provide a default edit summary for a preloaded
 * edit to one's own user page or talk page.
 *
 * - preview: Allows showing/hiding preview on first edit regardless of user
 * preference, useful for preloaded edits where you know preview wouldn't be
 * useful.
 *
 * - redlink: Affects the message the user sees if their talk page/user talk
 * page does not currently exist. Avoids confusion for newbies with no user
 * pages over why they got a "permission error" following this link:
 * http://en.wikipedia.org/w/index.php?title=Special:MyPage&redlink=1
 *
 * - debug: determines whether the debug parameter is passed to load.php,
 * which disables reformatting and allows scripts to be debugged. Useful
 * when debugging scripts that manipulate one's own user page or talk page.
 *
 * @par Hook extension:
 * Extensions can add to the redirect parameters list by using the hook
 * RedirectSpecialArticleRedirectParams
 *
 * This hook allows extensions which add GET parameters like FlaggedRevs to
 * retain those parameters when redirecting using special pages.
 *
 * @par Hook extension example:
 * @code
 *    $wgHooks['RedirectSpecialArticleRedirectParams'][] =
 *        'MyExtensionHooks::onRedirectSpecialArticleRedirectParams';
 *    public static function onRedirectSpecialArticleRedirectParams( &$redirectParams ) {
 *        $redirectParams[] = 'stable';
 *        return true;
 *    }
 * @endcode
 *
 * @ingroup SpecialPage
 */
abstract class RedirectSpecialArticle extends RedirectSpecialPage {
	function __construct( $name ) {
		parent::__construct( $name );
		$redirectParams = array(
			'action',
			'redirect', 'rdfrom',
			# Options for preloaded edits
			'preload', 'preloadparams', 'editintro', 'preloadtitle', 'summary', 'nosummary',
			# Options for overriding user settings
			'preview', 'minor', 'watchthis',
			# Options for history/diffs
			'section', 'oldid', 'diff', 'dir',
			'limit', 'offset', 'feed',
			# Misc options
			'redlink', 'debug',
			# Options for action=raw; missing ctype can break JS or CSS in some browsers
			'ctype', 'maxage', 'smaxage',
		);

		Hooks::run( "RedirectSpecialArticleRedirectParams", array( &$redirectParams ) );
		$this->mAllowedRedirectParams = $redirectParams;
	}
}
