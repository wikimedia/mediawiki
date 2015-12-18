<?php
/**
 * Implements Special:MyLanguage
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
 * @author Niklas Laxström
 * @author Siebrand Mazeland
 * @copyright Copyright © 2010-2013 Niklas Laxström, Siebrand Mazeland
 */

/**
 * Unlisted special page just to redirect the user to the translated version of
 * a page, if it exists.
 *
 * Usage: [[Special:MyLanguage/Page name|link text]]
 *
 * @since 1.24
 * @ingroup SpecialPage
 */
class SpecialMyLanguage extends RedirectSpecialArticle {
	public function __construct() {
		parent::__construct( 'MyLanguage' );
	}

	/**
	 * If the special page is a redirect, then get the Title object it redirects to.
	 * False otherwise.
	 *
	 * @param string|null $subpage
	 * @return Title
	 */
	public function getRedirect( $subpage ) {
		$title = $this->findTitle( $subpage );
		// Go to the main page if given invalid title.
		if ( !$title ) {
			$title = Title::newMainPage();
		}
		return $title;
	}

	/**
	 * Assuming the user's interface language is fi. Given input Page, it
	 * returns Page/fi if it exists, otherwise Page. Given input Page/de,
	 * it returns Page/fi if it exists, otherwise Page/de if it exists,
	 * otherwise Page.
	 *
	 * @param string|null $subpage
	 * @return Title|null
	 */
	public function findTitle( $subpage ) {
		// base = title without language code suffix
		// provided = the title as it was given
		$base = $provided = null;
		if ( $subpage !== null ) {
			$provided = Title::newFromText( $subpage );
			$base = $provided;
		}

		if ( $provided && strpos( $subpage, '/' ) !== false ) {
			$pos = strrpos( $subpage, '/' );
			$basepage = substr( $subpage, 0, $pos );
			$code = substr( $subpage, $pos + 1 );
			if ( strlen( $code ) && Language::isKnownLanguageTag( $code ) ) {
				$base = Title::newFromText( $basepage );
			}
		}

		if ( !$base ) {
			return null;
		}

		if ( $base->isRedirect() ) {
			$page = new WikiPage( $base );
			$base = $page->getRedirectTarget();
		}

		$uiCode = $this->getLanguage()->getCode();
		$proposed = $base->getSubpage( $uiCode );
		if ( $proposed && $proposed->exists() && $uiCode !== $base->getPageLanguage()->getCode() ) {
			return $proposed;
		} elseif ( $provided && $provided->exists() ) {
			return $provided;
		} else {
			return $base;
		}
	}

	/**
	 * Target can identify a specific user's language preference.
	 *
	 * @see T109724
	 * @since 1.27
	 * @return bool
	 */
	public function personallyIdentifiableTarget() {
		return true;
	}
}
