<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\SpecialPage\RedirectSpecialArticle;
use MediaWiki\Title\Title;

/**
 * Redirect to the appropriate translated version of a page if it exists.
 *
 * Usage: [[Special:MyLanguage/Page name|link text]]
 *
 * @since 1.24
 * @ingroup SpecialPage
 * @author Niklas Laxström
 * @author Siebrand Mazeland
 * @copyright Copyright © 2010-2013 Niklas Laxström, Siebrand Mazeland
 */
class SpecialMyLanguage extends RedirectSpecialArticle {

	private LanguageNameUtils $languageNameUtils;
	private RedirectLookup $redirectLookup;

	public function __construct(
		LanguageNameUtils $languageNameUtils,
		RedirectLookup $redirectLookup
	) {
		parent::__construct( 'MyLanguage' );
		$this->languageNameUtils = $languageNameUtils;
		$this->redirectLookup = $redirectLookup;
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
	 * Find a title.
	 *
	 * This may return the base page, e.g. if the UI and
	 * content language are the same.
	 *
	 * Examples, assuming the UI language is fi and the content language
	 * is en:
	 * - input Page: returns Page/fi if it exists, otherwise Page
	 * - input Page/de: returns Page/fi if it exists, otherwise Page/de
	 * if it exists, otherwise Page
	 *
	 * @param string|null $subpage
	 * @return Title|null
	 */
	public function findTitle( $subpage ) {
		return $this->findTitleInternal( $subpage, false );
	}

	/**
	 * Find a title for transclusion. This avoids returning the base
	 * page if a suitable alternative exists.
	 *
	 * Examples, assuming the UI language is fi and the content language
	 * is en:
	 * - input Page: returns Page/fi if it exists, otherwise Page/en if
	 * it exists, otherwise Page
	 * - input Page/de: returns Page/fi if it exists, otherwise Page/de
	 * if it exists, otherwise Page/en if it exists, otherwise Page
	 *
	 * @param string|null $subpage
	 * @return Title|null
	 */
	public function findTitleForTransclusion( $subpage ) {
		return $this->findTitleInternal( $subpage, true );
	}

	/**
	 * Find a title, depending on the content language and the user's
	 * interface language.
	 *
	 * @param string|null $subpage
	 * @param bool $forTransclusion
	 * @return Title|null
	 */
	private function findTitleInternal( $subpage, $forTransclusion ) {
		// base = title without the language code suffix
		// provided = the title as it was given
		$base = $provided = null;
		if ( $subpage !== null ) {
			$provided = Title::newFromText( $subpage );
			$base = $provided;

			if ( $provided && str_contains( $subpage, '/' ) ) {
				$pos = strrpos( $subpage, '/' );
				$basepage = substr( $subpage, 0, $pos );
				$code = substr( $subpage, $pos + 1 );
				if ( $code !== '' && $this->languageNameUtils->isKnownLanguageTag( $code ) ) {
					$base = Title::newFromText( $basepage );
				}
			}
		}

		if ( !$base || !$base->canExist() ) {
			// No subpage provided or base page does not exist
			return null;
		}

		$fragment = '';
		if ( $base->isRedirect() ) {
			$target = $this->redirectLookup->getRedirectTarget( $base );
			if ( $target !== null ) {
				$base = Title::newFromLinkTarget( $target );
				// Preserve the fragment from the redirect target
				$fragment = $base->getFragment();
			}
		}

		$uiLang = $this->getLanguage();
		$baseLang = $base->getPageLanguage();

		// T309329: Always use subpages for transclusion
		if ( !$forTransclusion && $baseLang->equals( $uiLang ) ) {
			// Short circuit when the current UI language is the
			// page's content language to avoid unnecessary page lookups.
			return $base;
		}

		// Check for a subpage in the current UI language
		$proposed = $base->getSubpage( $uiLang->getCode() );
		if ( $proposed && $proposed->exists() ) {
			if ( $fragment !== '' ) {
				$proposed->setFragment( $fragment );
			}
			return $proposed;
		}

		// Explicit language code given and the page exists
		if ( $provided !== $base && $provided->exists() ) {
			// Not based on the redirect target, don't need the fragment
			return $provided;
		}

		// Check for fallback languages specified by the UI language
		$possibilities = $uiLang->getFallbackLanguages();
		foreach ( $possibilities as $lang ) {
			// $base already include fragments
			// T309329: Always use subpages for transclusion
			// T333187: Do not ignore base language page if matched
			if ( !$forTransclusion && $lang === $baseLang->getCode() ) {
				return $base;
			}
			// Look for subpages if is for transclusion or didn't match base page language
			$proposed = $base->getSubpage( $lang );
			if ( $proposed && $proposed->exists() ) {
				if ( $fragment !== '' ) {
					$proposed->setFragment( $fragment );
				}
				return $proposed;
			}
		}

		// When all else has failed, return the base page
		return $base;
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

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialMyLanguage::class, 'SpecialMyLanguage' );
