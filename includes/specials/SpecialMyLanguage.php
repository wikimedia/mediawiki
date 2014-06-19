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
	 * @param string $par Subpage string
	 * @return Title|bool
	 */
	public function getRedirect( $par ) {
		$title = $this->findTitle( $par );
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
	 * @param $par
	 * @return Title|null
	 */
	protected function findTitle( $par ) {
		global $wgLanguageCode;
		// base = title without language code suffix
		// provided = the title as it was given
		$base = $provided = Title::newFromText( $par );

		if ( $base && strpos( $par, '/' ) !== false ) {
			$basepage = $base->getBaseText();
			$code = $base->getSubpageText();
			if ( Language::isKnownLanguageTag( $code ) ) {
				$base = Title::newFromText( $basepage );
			}
		}

		if ( !$base ) {
			return null;
		}

		$uiCode = $this->getLanguage()->getCode();
		$proposed = $base->getSubpage( $uiCode );
		if ( $uiCode !== $wgLanguageCode && $proposed && $proposed->exists() ) {
			return $proposed;
		} elseif ( $provided && $provided->exists() ) {
			return $provided;
		} else {
			return $base;
		}
	}

	/**
	 * Make Special:MyLanguage links red if the target page doesn't exists.
	 *
	 * @param string|null $subPage
	 * @return bool
	 */
	public function subpageExists( $subPage ) {
		$target = Title::newFromText( $subPage );
		if ( !$target || !$target->exists() ) {
			return false;
		}
		return true;
	}
}
