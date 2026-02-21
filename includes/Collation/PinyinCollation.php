<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Collation;

use InvalidArgumentException;
use Locale;
use MediaWiki\Language\LanguageFactory;

/**
 * Tailored collation for Chinese Pinyin sorting. Groups Pinyin initials
 * and Latin characters into buckets.
 * TODO: Overrides for compounds of polyphonic characters (T401456)
 *
 * @since 1.46
 */
class PinyinCollation extends IcuCollation {

	public function __construct( LanguageFactory $languageFactory, string $locale ) {
		$keywords = Locale::getKeywords( $locale ) ?? [];
		if ( isset( $keywords['collation'] ) ) {
			throw new InvalidArgumentException(
				"Collation method should not be specified for PinyinCollation: $locale"
			);
		}

		$locale = str_replace( [ '-u-', '@' ], [ '-u-co-pinyin-', '@collation=pinyin;' ], $locale, $count );
		if ( $count === 0 ) {
			$locale .= '@collation=pinyin';
		}

		parent::__construct( $languageFactory, $locale );
	}

	/**
	 * ICU orders characters by script group (Latin/Hani) first, but we want to
	 * avoid having first-letter sections separately for Pinyin and English words
	 * on different pages of a large category.
	 *
	 * @inheritDoc
	 */
	public function getSortKey( $string ) {
		$ranges = [];
		foreach ( [ 'A', 'Z' ] as $char ) {
			$ranges[0][$char] = $this->getPrimarySortKey( "\u{FDD0}$char" );
			$ranges[1][$char] = $this->getPrimarySortKey( $char );
		}
		// Hani script group comes first by default, but the order can be flipped
		// with `colReorder=latn-hani` parameter.
		if ( strcmp( $ranges[0]['A'], $ranges[1]['A'] ) > 0 ) {
			$ranges = array_reverse( $ranges );
		}

		$sortLetter = $this->getRawSortLetter( $string );
		$sortLetterKey = $this->getPrimarySortKey( $sortLetter );
		if ( strcmp( $sortLetterKey, $ranges[0]['A'] ) < 0 ) {
			// Special character groups that are always sorted first
			$prefix = chr( 0x00 ) . chr( 0x00 );
		} elseif ( strcmp( $sortLetterKey, $ranges[0]['Z'] ) <= 0 ) {
			// substr strips off the "\u{FDD0}" prefix if any
			$prefix = chr( 0x01 ) . $this->getPrimarySortKey( substr( $sortLetter, -1, 1 ) )[0];
		} elseif ( strcmp( $sortLetterKey, $ranges[1]['A'] ) < 0 ) {
			// In case there are something between the Hani and Latin ranges
			$prefix = chr( 0x02 ) . chr( 0x00 );
		} elseif ( strcmp( $sortLetterKey, $ranges[1]['Z'] ) <= 0 ) {
			// Use the same bucket weight for Latin characters and Pinyin initials
			$prefix = chr( 0x01 ) . $this->getPrimarySortKey( substr( $sortLetter, -1, 1 ) )[0];
		} else {
			// All the other script groups
			$prefix = chr( 0x02 ) . chr( 0x01 );
		}

		return $prefix . $this->mainCollator->getSortKey( $string );
	}
}
