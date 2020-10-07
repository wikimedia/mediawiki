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
 * https://en.wikipedia.org/wiki/Special:MyPage?offset=20110000000000&limit=1&action=history
 *
 * - feed: would allow linking to the current user's RSS feed for their user
 * talk page:
 * https://en.wikipedia.org/w/index.php?title=Special:MyTalk&action=history&feed=rss
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
 * https://en.wikipedia.org/w/index.php?title=Special:MyPage&redlink=1
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
 * @stable to extend
 *
 * @ingroup SpecialPage
 */
abstract class RedirectSpecialArticle extends RedirectSpecialPage {

	/**
	 * @stable to call
	 *
	 * @param string $name
	 */
	public function __construct( $name ) {
		parent::__construct( $name );
		$redirectParams = [
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
			'redlink',
			# Options for action=raw; missing ctype can break JS or CSS in some browsers
			'ctype', 'maxage', 'smaxage',
		];

		$this->getHookRunner()->onRedirectSpecialArticleRedirectParams( $redirectParams );
		$this->mAllowedRedirectParams = $redirectParams;
	}

	/**
	 * @inheritDoc
	 */
	public function getRedirectQuery( $subpage ) {
		$query = parent::getRedirectQuery( $subpage );
		$title = $this->getRedirect( $subpage );
		// Avoid double redirect for action=edit&redlink=1 for existing pages
		// (compare to the check in EditPage::edit)
		if (
			$query && isset( $query['action'] ) && isset( $query['redlink'] ) &&
			( $query['action'] === 'edit' || $query['action'] === 'submit' ) &&
			(bool)$query['redlink'] &&
			$title instanceof Title &&
			$title->exists()
		) {
			return false;
		}
		return $query;
	}

}
