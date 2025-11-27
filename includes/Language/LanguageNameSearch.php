<?php

namespace MediaWiki\Language;

use MediaWiki\MediaWikiServices;

/**
 * Cross-Language Language name search
 *
 * FIXME: This class can be marked as readonly once AutoLoaderStructureTest can
 * parse "readonly" annotations.
 *
 * Copyright (C) 2012 Alolita Sharma, Amir Aharoni, Arun Ganesh, Brandon Harris,
 * Niklas Laxström, Pau Giner, Santhosh Thottingal, Siebrand Mazeland and other
 * contributors.
 *
 * @license GPL-2.0-or-later
 */
class LanguageNameSearch {
	private LanguageNameUtils $languageNameUtils;

	public function __construct( LanguageNameUtils $languageNameUtils ) {
		$this->languageNameUtils = $languageNameUtils;
	}

	/**
	 * Find languages with fuzzy matching.
	 * The order of results is following:
	 * 1: exact language code match
	 * 2: exact language name match in any language
	 * 3: prefix language name match in any language
	 * 4: infix language name match in any language
	 *
	 * The returned language name for autocompletion is the first one that
	 * matches in this list:
	 * 1: exact match in [user, autonym, any other language]
	 * 2: prefix match in [user, autonym, any other language]
	 * 3: inline match in [user, autonym, any other language]
	 *
	 * @param string $searchKey
	 * @param int $typos
	 * @param string|null $userLanguage Language tag.
	 * @return array
	 */
	public static function search( string $searchKey, int $typos = 0, ?string $userLanguage = null ): array {
		$services = MediaWikiServices::getInstance();
		$instance = $services->getLanguageNameSearch();
		return $instance->doSearch( $searchKey, $typos, $userLanguage );
	}

	/**
	 * Find languages with fuzzy matching.
	 * The order of results is following:
	 * 1: exact language code match
	 * 2: exact language name match in any language
	 * 3: prefix language name match in any language
	 * 4: infix language name match in any language
	 *
	 * The returned language name for autocompletion is the first one that
	 * matches in this list:
	 * 1: exact match in [user, autonym, any other language]
	 * 2: prefix match in [user, autonym, any other language]
	 * 3: inline match in [user, autonym, any other language]
	 *
	 * @param string $searchKey
	 * @param int $typos
	 * @param string|null $userLanguage Language tag.
	 * @return array
	 */
	public function doSearch( string $searchKey, int $typos = 0, ?string $userLanguage = null ): array {
		$results = [];
		$searchKey = mb_strtolower( $searchKey );

		if ( mb_strlen( $searchKey ) > 100 ) {
			// Searching with long search keys for language names is not useful. So, return early.
			return [];
		}

		$languageNameUtils = $this->languageNameUtils;

		// Always prefer exact language code match
		if ( $languageNameUtils->isKnownLanguageTag( $searchKey ) ) {
			$name = mb_strtolower( $languageNameUtils->getLanguageName( $searchKey, $userLanguage ) );
			// Check if language code is a prefix of the name
			if ( str_starts_with( $name, $searchKey ) ) {
				$results[$searchKey] = $name;
			} else {
				$results[$searchKey] = "$searchKey – $name";
			}
		}

		$index = self::getIndex( $searchKey );
		static $buckets = null;
		if ( $buckets === null ) {
			$data = json_decode(
				file_get_contents( __DIR__ . '/../../languages/data/LanguageNameSearchData.json' ),
				true
			);
			$buckets = $data['buckets'] ?? [];
		}
		$bucketsForIndex = $buckets[$index] ?? [];

		// types are 'prefix', 'infix' (in this order!)
		foreach ( $bucketsForIndex as $bucket ) {
			foreach ( $bucket as $name => $code ) {
				// We can skip checking languages we already have in the list
				if ( isset( $results[ $code ] ) ) {
					continue;
				}

				// Apply fuzzy search
				if ( !$this->matchNames( $name, $searchKey, $typos ) ) {
					continue;
				}

				// Once we find a match, figure out the best name to display to the user
				// If $userLanguage is not provided (null), it is the same as autonym
				$candidates = [
					mb_strtolower( $languageNameUtils->getLanguageName( $code, $userLanguage ) ),
					mb_strtolower( $languageNameUtils->getLanguageName( $code, LanguageNameUtils::AUTONYMS ) ),
					$name
				];

				foreach ( $candidates as $candidate ) {
					if ( $searchKey === $candidate ) {
						$results[$code] = $candidate;
						continue 2;
					}
				}

				foreach ( $candidates as $candidate ) {
					if ( $this->matchNames( $candidate, $searchKey, $typos ) ) {
						$results[$code] = $candidate;
						continue 2;
					}
				}
			}
		}

		return $results;
	}

	private function matchNames( string $name, string $searchKey, int $typos ): bool {
		return strrpos( $name, $searchKey, -strlen( $name ) ) !== false
			|| ( $typos > 0 && $this->levenshteinDistance( $name, $searchKey ) <= $typos );
	}

	public static function getIndex( string $name ): int {
		$codepoint = self::getCodepoint( $name );

		if ( $codepoint < 4000 ) {
			// For latin etc. we need smaller buckets for speed
			return $codepoint;
		}

		// Try to group names of same script together
		return $codepoint - ( $codepoint % 1000 );
	}

	/**
	 * Get the code point of first letter of string
	 *
	 * @param string $str
	 * @return int Code point of first letter of string
	 */
	private static function getCodepoint( string $str ): int {
		$values = [];
		$lookingFor = 1;
		$strLen = strlen( $str );
		$number = 0;

		for ( $i = 0; $i < $strLen; $i++ ) {
			$thisValue = ord( $str[$i] );
			if ( $thisValue < 128 ) {
				$number = $thisValue;

				break;
			}

			// Codepoints larger than 127 are represented by multi-byte sequences
			if ( $values === [] ) {
				// 224 is the lowest non-overlong-encoded codepoint.
				$lookingFor = ( $thisValue < 224 ) ? 2 : 3;
			}

			$values[] = $thisValue;
			if ( count( $values ) === $lookingFor ) {
				// Refer http://en.wikipedia.org/wiki/UTF-8#Description
				if ( $lookingFor === 3 ) {
					$number = ( $values[0] % 16 ) * 4096;
					$number += ( $values[1] % 64 ) * 64;
					$number += $values[2] % 64;
				} else {
					$number = ( $values[0] % 32 ) * 64;
					$number += $values[1] % 64;
				}

				break;
			}
		}

		return $number;
	}

	/**
	 * Calculate the Levenshtein distance between two strings
	 */
	private function levenshteinDistance( string $str1, string $str2 ): int {
		if ( $str1 === $str2 ) {
			return 0;
		}
		$length1 = mb_strlen( $str1, 'UTF-8' );
		$length2 = mb_strlen( $str2, 'UTF-8' );
		if ( $length1 === 0 ) {
			return $length2;
		}
		if ( $length1 < $length2 ) {
			return $this->levenshteinDistance( $str2, $str1 );
		}
		$prevRow = range( 0, $length2 );
		for ( $i = 0; $i < $length1; $i++ ) {
			$currentRow = [];
			$currentRow[0] = $i + 1;
			$c1 = mb_substr( $str1, $i, 1, 'UTF-8' );
			for ( $j = 0; $j < $length2; $j++ ) {
				$c2 = mb_substr( $str2, $j, 1, 'UTF-8' );
				$insertions = $prevRow[$j + 1] + 1;
				$deletions = $currentRow[$j] + 1;
				$substitutions = $prevRow[$j] + ( ( $c1 !== $c2 ) ? 1 : 0 );
				$currentRow[] = min( $insertions, $deletions, $substitutions );
			}
			$prevRow = $currentRow;
		}

		return $prevRow[$length2];
	}
}
