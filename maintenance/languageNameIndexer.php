<?php
/**
 * Script to create language names index.
 *
 * Copyright (C) 2012 Alolita Sharma, Amir Aharoni, Arun Ganesh, Brandon Harris,
 * Niklas Laxström, Pau Giner, Santhosh Thottingal, Siebrand Mazeland and other
 * contributors.
 *
 * UniversalLanguageSelector is dual licensed GPLv2 or later and MIT. You don't
 * have to do anything special to choose one license or the other and you don't
 * have to notify anyone which license you are using. You are free to use
 * UniversalLanguageSelector in commercial projects as long as the copyright
 * header is left intact. See files GPL-LICENSE for details.
 *
 * @ingroup Maintenance
 * @license GPL-2.0-or-later
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Extension\CLDR\LanguageNames;
use MediaWiki\Json\FormatJson;
use MediaWiki\Language\LanguageNameSearch;
use MediaWiki\Language\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Registration\ExtensionRegistry;

class LanguageNameIndexer extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to create language names index.' );

		$extensionRegistry = ExtensionRegistry::getInstance();
		if ( !$extensionRegistry->isLoaded( 'cldr' ) ) {
			$this->requireExtension( 'cldr' );
		}
		if ( !$extensionRegistry->isLoaded( 'UniversalLanguageSelector' ) ) {
			$this->requireExtension( 'UniversalLanguageSelector' );
		}
	}

	public function execute() {
		// Avoid local configuration leaking to this script
		if ( $this->getConfig()->get( MainConfigNames::ExtraLanguageNames ) !== [] ) {
			$this->fatalError( 'You have entries in $wgExtraLanguageNames. Needs to be empty for this script.' );
		}

		$languageNames = [];
		// Add languages from language-data
		$ulsLanguages = $this->getLanguageData()[ 'languages' ];
		foreach ( $ulsLanguages as $languageCode => $languageEntry ) {
			// Redirect have only one item
			if ( isset( $languageEntry[ 2 ] ) ) {
				$languageNames[ 'autonyms' ][ $languageCode ] = $languageEntry[ 2 ];
			}
		}

		// Languages and their names in different languages from Names.php and the cldr extension
		// This comes after $ulsLanguages so that for example the als/gsw mixup is using the code
		// used in the Wikimedia world.
		$mwLanguages = $this->getServiceContainer()->getLanguageNameUtils()
			->getLanguageNames( LanguageNameUtils::AUTONYMS, LanguageNameUtils::ALL );
		foreach ( array_keys( $mwLanguages ) as $languageCode ) {
			// This method is in the CLDR extension
			// @phan-suppress-next-line PhanUndeclaredClassMethod
			$languageNames[ $languageCode ] = LanguageNames::getNames( $languageCode, 0, 2 );
		}

		$buckets = [];
		foreach ( $languageNames as $translations ) {
			foreach ( $translations as $targetLanguage => $translation ) {
				$translation = mb_strtolower( $translation );
				$translation = trim( $translation );

				// Clean up "gjermanishte zvicerane (dialekti i alpeve)" to "gjermanishte zvicerane".
				// The original name is still shown, but avoid us creating entries such as
				// "(dialekti" or "alpeve)".
				$basicForm = preg_replace( '/\(.+\)$/', '', $translation );
				$words = preg_split( '/[\s]+/u', $basicForm, -1, PREG_SPLIT_NO_EMPTY );

				foreach ( $words as $index => $word ) {
					$bucket = LanguageNameSearch::getIndex( $word );

					$type = 'prefix';
					$display = $translation;
					if ( $index > 0 ) {
						// Avoid creating infix entries for short strings like punctuation, articles, prepositions...
						if ( mb_strlen( $word ) < 3 ) {
							continue;
						}

						$type = 'infix';
						$display = "$word — $translation";
					}
					$buckets[$bucket][$type][$display] = $targetLanguage;
				}
			}
		}

		// Some languages don't have a conveniently searchable name in CLDR.
		// For example, the name of Western Punjabi doesn't start with
		// the string "punjabi" in any language, so it cannot be found
		// by people who search in English.
		// To resolve this, some languages are added here locally.
		$specialLanguages = [
			// Abron / Brong / Bono (T369464)
			'abr' => [ 'bono', 'brong' ],
			// Acholi (T376060)
			'ach' => [ 'leb acoli' ],
			// Hadhrami Arabic (T397355)
			'ayh' => [ 'حضرمية' ],
			// Catalan, sometimes searched as "Valencià"
			'ca' => [ 'valencia' ],
			// Compatibility with the old name and other Chinese varieties
			'cdo' => [ 'chinese min dong' ],
			// Alternate names for Anufo in linguistic literature
			'cko' => [ 'chakosi', 'chokosi', 'tchokossi' ],
			// Dolgan (T395396)
			'dlg' => [ 'һака' ],
			// Older name, see T375891
			'dtp' => [ 'bundu-liwan, dusun' ],
			// Spanish, the transliteration of the autonym is often used for searching
			'es' => [ 'castellano' ],
			// Armenian, the transliteration of the autonym is often used for searching
			'hy' => [ 'hayeren' ],
			// Japanese, the transliteration of the autonym is often used for searching
			'ja' => [ 'nihongo', 'にほんご' ],
			// Javanese (T393746)
			'jv-java' => [ 'jawa hanacaraka' ],
			// Georgian, the transliteration of the autonym is often used for searching
			'ka' => [ 'kartuli', 'qartuli' ],
			// Lango (Uganda; T376054).
			// The second alias help avoid ambiguity with
			// other languages named "Lango" and also
			// with "Langi"
			'laj' => [ 'leb lango', 'lango, leb' ],
			// Chiluvale (T368856)
			'lue' => [ 'luvale, chi-' ],
			// Shan (T377856)
			'shn' => [ 'ၽႃႇသႃႇတႆး', 'လိၵ်ႈတႆး' ],
			// Tigrinya: variant names in Hebrew,
			// to ensure they can be found in different spellings
			'ti' => [
				'טגריניה',
				'טגרינית',
				'טיגריניה',
				'טיגרינית',
				'תגריניה',
				'תגרינית',
				'תיגריניה',
			],
			// Tigre: variant names in Hebrew,
			// to ensure they can be found in different spellings
			'tig' => [
				'טגרה',
				'טגרית',
				'טיגרה',
				'תגרה',
				'תגרית',
				'תיגרה',
				'תיגרית',
			],
			// Mon, renamed in core MediaWiki's Names.php (T352776)
			'mnw' => [ 'ဘာသာ မန်' ],
			// Palembang, also known as "Musi".
			// Writing this as two words ensures that it has a unique key,
			// so that Moore (mos), which is known as "musi" in one of the languages,
			// can also be found
			'mui' => [ 'musi palembang' ],
			// Western Punjabi, doesn't start with the word "Punjabi" in any language
			'pnb' => [ 'punjabi western' ],
			// Tai Nuea (T367377)
			'tdd' => [ 'ᥖᥭᥰᥖᥬᥳᥑᥨᥒᥰ' ],
			// Waale (T368046) - support alternate spellings of the name
			'wlx' => [ 'waali', 'waalii' ],
			// Simplified and Traditional Chinese, because zh-hans and zh-hant
			// are not mapped to any English name
			'zh-hans' => [ 'chinese simplified' ],
			'zh-hant' => [ 'chinese traditional' ],
			// Compatibility with the old name and other Chinese varieties
			'zh-min-nan' => [ 'chinese min nan' ],
		];

		foreach ( $specialLanguages as $targetLanguage => $translations ) {
			foreach ( $translations as $translation ) {
				$bucket = LanguageNameSearch::getIndex( $translation );
				$buckets[$bucket]['prefix'][$translation] = $targetLanguage;
			}
		}

		$lengths = [];
		// Sorting the bucket contents gives two benefits:
		// - more consistent output across environments
		// - shortest matches appear first, especially exact matches
		// Sort buckets by index
		ksort( $buckets );
		foreach ( $buckets as &$bucketTypes ) {
			$lengths[] = array_sum( array_map( 'count', $bucketTypes ) );
			// Ensure 'prefix' is before 'infix';
			krsort( $bucketTypes );
			// Ensure each bucket has entries sorted
			foreach ( $bucketTypes as &$bucket ) {
				ksort( $bucket );
			}
		}

		$count = count( $buckets );
		$min = min( $lengths );
		$max = max( $lengths );
		$median = $lengths[ceil( $count / 2 )];
		$avg = array_sum( $lengths ) / $count;
		$this->output( "Bucket stats:\n - $count buckets\n - smallest has $min entries\n" );
		$this->output( " - largest has $max entries\n - median size is $median entries\n" );
		$this->output( " - average size is $avg entries\n" );

		$this->generateFile( $buckets );
	}

	/**
	 * @return array
	 */
	private function getLanguageData() {
		$file = __DIR__ . '/../extensions/UniversalLanguageSelector/lib/jquery.uls/src/jquery.uls.data.js';
		$contents = file_get_contents( $file );
		if ( !preg_match( '/.*\$\.uls\.data\s*=\s*(.*?)\s*}\s*\(\s*jQuery\s*\)/s', $contents, $matches ) ) {
			throw new LogicException( 'Syntax error in jquery.uls.data.js?' );
		}
		$json = $matches[ 1 ];
		$data = json_decode( $json, true );
		if ( !$data ) {
			throw new LogicException( 'json_decode failed. Syntax error in jquery.uls.data.js?' );
		}
		return $data;
	}

	/**
	 * @param array $buckets
	 */
	private function generateFile( array $buckets ) {
		// Add metadata to indicate this is a generated file
		$data = [
			'_comment' => 'This file is generated by a script!',
			'_generator' => 'maintenance/languageNameIndexer.php',
			'buckets' => $buckets
		];
		$json = FormatJson::encode( $data, "\t", FormatJson::ALL_OK );
		file_put_contents( __DIR__ . '/../languages/data/LanguageNameSearchData.json', $json . "\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = LanguageNameIndexer::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
