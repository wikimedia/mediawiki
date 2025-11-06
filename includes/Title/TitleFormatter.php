<?php
/**
 * A title formatter service for MediaWiki.
 *
 * @license GPL-2.0-or-later
 * @file
 * @author Daniel Kinzler
 */

namespace MediaWiki\Title;

use InvalidArgumentException;
use MediaWiki\Cache\GenderCache;
use MediaWiki\Language\Language;
use MediaWiki\Page\PageReference;
use Wikimedia\Parsoid\Core\LinkTarget;

/**
 * A title formatter service for MediaWiki.
 *
 * This is designed to encapsulate knowledge about conventions for the title
 * forms to be used in the database, in urls, in wikitext, etc.
 *
 * @note Normalization and validation is applied while parsing, not when formatting.
 *   It's possible to construct a TitleValue with an invalid title, and use TitleFormatter
 *   to generate an (invalid) title string from it. TitleValues should be constructed only
 *   via parseTitle() or from a (semi)trusted source, such as the database.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 * @since 1.23
 */
class TitleFormatter {
	protected Language $language;
	protected GenderCache $genderCache;
	protected NamespaceInfo $nsInfo;

	/**
	 * @param Language $language The language object to use for localizing namespace names,
	 *    capitalization, etc.
	 * @param GenderCache $genderCache The gender cache for generating gendered namespace names
	 * @param NamespaceInfo $nsInfo
	 */
	public function __construct(
		Language $language,
		GenderCache $genderCache,
		NamespaceInfo $nsInfo
	) {
		$this->language = $language;
		$this->genderCache = $genderCache;
		$this->nsInfo = $nsInfo;
	}

	/**
	 * Returns the title formatted for display.
	 * By default, this includes the namespace but not the fragment.
	 *
	 * @note Normalization is applied if $title is not in TitleValue::TITLE_FORM.
	 *
	 * @param int|false $namespace The namespace ID (or false, if the namespace should be ignored)
	 * @param string $text The page title. Should be valid. Only minimal normalization is applied.
	 *        Underscores will be replaced.
	 * @param string $fragment The fragment name (may be empty).
	 * @param string $interwiki The interwiki prefix (may be empty).
	 *
	 * @throws InvalidArgumentException If the namespace is invalid
	 * @return string
	 */
	public function formatTitle( $namespace, $text, $fragment = '', $interwiki = '' ) {
		$out = '';
		if ( $interwiki !== '' ) {
			$out = $interwiki . ':';
		}

		if ( $namespace != 0 ) {
			try {
				$nsName = $this->getNamespaceName( $namespace, $text );
			} catch ( InvalidArgumentException ) {
				// See T165149. Awkward, but better than erroneously linking to the main namespace.
				$nsName = $this->language->getNsText( NS_SPECIAL ) . ":Badtitle/NS{$namespace}";
			}

			$out .= $nsName . ':';
		}
		$out .= $text;

		if ( $fragment !== '' ) {
			$out .= '#' . $fragment;
		}

		$out = str_replace( '_', ' ', $out );

		return $out;
	}

	/**
	 * Returns the title text formatted for display, without namespace or fragment.
	 *
	 * @param LinkTarget|PageReference $title The title to format
	 *
	 * @return string
	 */
	public function getText( $title ) {
		if ( $title instanceof LinkTarget ) {
			return $title->getText();
		} elseif ( $title instanceof PageReference ) {
			return strtr( $title->getDBKey(), '_', ' ' );
		} else {
			throw new InvalidArgumentException( '$title has invalid type: ' . get_class( $title ) );
		}
	}

	/**
	 * Returns the title formatted for display, including the namespace name.
	 *
	 * @param LinkTarget|PageReference $title The title to format
	 *
	 * @return string
	 * @suppress PhanUndeclaredProperty
	 */
	public function getPrefixedText( $title ) {
		if ( $title instanceof LinkTarget ) {
			if ( !isset( $title->prefixedText ) ) {
				$title->prefixedText = $this->formatTitle(
					$title->getNamespace(),
					$title->getText(),
					'',
					$title->getInterwiki()
				);
			}
			return $title->prefixedText;
		} elseif ( $title instanceof PageReference ) {
			$title->assertWiki( PageReference::LOCAL );
			return $this->formatTitle(
				$title->getNamespace(),
				$this->getText( $title )
			);
		} else {
			throw new InvalidArgumentException( '$title has invalid type: ' . get_class( $title ) );
		}
	}

	/**
	 * Return the title in prefixed database key form, with interwiki
	 * and namespace.
	 *
	 * @since 1.27
	 *
	 * @param LinkTarget|PageReference $target
	 * @return string
	 */
	public function getPrefixedDBkey( $target ) {
		if ( $target instanceof LinkTarget ) {
			return strtr( $this->formatTitle(
				$target->getNamespace(),
				$target->getDBkey(),
				'',
				$target->getInterwiki()
			), ' ', '_' );
		} elseif ( $target instanceof PageReference ) {
			$target->assertWiki( PageReference::LOCAL );
			return strtr( $this->formatTitle(
				$target->getNamespace(),
				$target->getDBkey()
			), ' ', '_' );
		} else {
			throw new InvalidArgumentException( '$title has invalid type: ' . get_class( $target ) );
		}
	}

	/**
	 * Returns the title formatted for display, with namespace and fragment.
	 *
	 * @param LinkTarget|PageReference $title The title to format
	 *
	 * @return string
	 */
	public function getFullText( $title ) {
		if ( $title instanceof LinkTarget ) {
			return $this->formatTitle(
				$title->getNamespace(),
				$title->getText(),
				$title->getFragment(),
				$title->getInterwiki()
			);
		} elseif ( $title instanceof PageReference ) {
			$title->assertWiki( PageReference::LOCAL );
			return $this->formatTitle(
				$title->getNamespace(),
				$this->getText( $title )
			);
		} else {
			throw new InvalidArgumentException( '$title has invalid type: ' . get_class( $title ) );
		}
	}

	/**
	 * Returns the name of the namespace for the given title.
	 *
	 * @note This must take into account gender sensitive namespace names.
	 *
	 * @param int $namespace
	 * @param string $text
	 *
	 * @throws InvalidArgumentException If the namespace is invalid
	 * @return string Namespace name with underscores (not spaces), e.g. 'User_talk'
	 */
	public function getNamespaceName( $namespace, $text ) {
		if ( $this->language->needsGenderDistinction() &&
			$this->nsInfo->hasGenderDistinction( $namespace )
		) {
			// NOTE: we are assuming here that the title text is a user name!
			$gender = $this->genderCache->getGenderOf( $text, __METHOD__ );
			$name = $this->language->getGenderNsText( $namespace, $gender );
		} else {
			$name = $this->language->getNsText( $namespace );
		}

		if ( $name === false ) {
			throw new InvalidArgumentException( 'Unknown namespace ID: ' . $namespace );
		}

		return $name;
	}

}

/** @deprecated class alias since 1.41 */
class_alias( TitleFormatter::class, 'TitleFormatter' );
