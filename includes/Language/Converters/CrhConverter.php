<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\Languages\Data\CrhExceptions;
use MediaWiki\StubObject\StubUserLang;
use Wikimedia\ReplacementArray;

/**
 * Crimean Tatar (Qırımtatarca) converter routines.
 *
 * Adapted from https://crh.wikipedia.org/wiki/Qullan%C4%B1c%C4%B1:Don_Alessandro/Translit
 *
 * @ingroup Languages
 */
class CrhConverter extends LanguageConverterSpecific {
	// Defines working character ranges

	// Cyrillic
	# Crimean Tatar Cyrillic uppercase
	public const C_UC = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
	# Crimean Tatar Cyrillic lowercase
	public const C_LC = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
	# Crimean Tatar Cyrillic + CÑ uppercase consonants
	public const C_CONS_UC = 'БВГДЖЗЙКЛМНПРСТФХЦЧШЩCÑ';
	# Crimean Tatar Cyrillic + CÑ lowercase consonants
	public const C_CONS_LC = 'бвгджзйклмнпрстфхцчшщcñ';
	# Crimean Tatar Cyrillic M-type consonants
	public const C_M_CONS = 'бгкмшcБГКМШC';

	// Crimean Tatar Cyrillic + CÑ consonants
	public const C_CONS = 'бвгджзйклмнпрстфхцчшщcñБВГДЖЗЙКЛМНПРСТФХЦЧШЩCÑ';

	// Latin
	# Crimean Tatar Latin uppercase
	public const L_UC = 'AÂBCÇDEFGĞHIİJKLMNÑOÖPQRSŞTUÜVYZ';
	# Crimean Tatar Latin lowercase
	public const L_LC = 'aâbcçdefgğhıijklmnñoöpqrsştuüvyz';
	# Crimean Tatar Latin N-type upper case consonants
	public const L_N_CONS_UC = 'ÇNRSTZ';
	# Crimean Tatar Latin N-type lower case consonants
	public const L_N_CONS_LC = 'çnrstz';
	# Crimean Tatar Latin N-type consonants
	public const L_N_CONS = 'çnrstzÇNRSTZ';
	# Crimean Tatar Latin M-type consonants
	public const L_M_CONS = 'bcgkmpşBCGKMPŞ';
	# Crimean Tatar Latin uppercase consonants
	public const L_CONS_UC = 'BCÇDFGĞHJKLMNÑPQRSŞTVZ';
	# Crimean Tatar Latin lowercase consonants
	public const L_CONS_LC = 'bcçdfgğhjklmnñpqrsştvz';
	# Crimean Tatar Latin consonants
	public const L_CONS = 'bcçdfgğhjklmnñpqrsştvzBCÇDFGĞHJKLMNÑPQRSŞTVZ';
	# Crimean Tatar Latin uppercase vowels
	public const L_VOW_UC = 'AÂEIİOÖUÜ';
	# Crimean Tatar Latin vowels
	public const L_VOW = 'aâeıioöuüAÂEIİOÖUÜ';
	# Crimean Tatar Latin uppercase front vowels
	public const L_F_UC = 'EİÖÜ';
	# Crimean Tatar Latin front vowels
	public const L_F = 'eiöüEİÖÜ';

	public function getMainCode(): string {
		return 'crh';
	}

	public function getLanguageVariants(): array {
		return [ 'crh', 'crh-cyrl', 'crh-latn' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'crh' => 'crh-latn',
			'crh-cyrl' => 'crh-latn',
			'crh-latn' => 'crh-cyrl',
		];
	}

	/**
	 * @param Language|StubUserLang $langobj
	 */
	public function __construct( $langobj ) {
		parent::__construct( $langobj );

		// No point delaying this since they're in code.
		// Waiting until loadDefaultTables() means they never get loaded
		// when the tables themselves are loaded from the cache.
		$this->loadExceptions();
	}

	public const CYRILLIC_TO_LATIN = [
		## these are independent of location in the word, but have
		## to go first so other transforms don't bleed them
		'гъ' => 'ğ', 'Гъ' => 'Ğ', 'ГЪ' => 'Ğ',
		'къ' => 'q', 'Къ' => 'Q', 'КЪ' => 'Q',
		'нъ' => 'ñ', 'Нъ' => 'Ñ', 'НЪ' => 'Ñ',
		'дж' => 'c', 'Дж' => 'C', 'ДЖ' => 'C',

		'А' => 'A', 'а' => 'a', 'Б' => 'B', 'б' => 'b',
		'В' => 'V', 'в' => 'v', 'Г' => 'G', 'г' => 'g',
		'Д' => 'D', 'д' => 'd', 'Ж' => 'J', 'ж' => 'j',
		'З' => 'Z', 'з' => 'z', 'И' => 'İ', 'и' => 'i',
		'Й' => 'Y', 'й' => 'y', 'К' => 'K', 'к' => 'k',
		'Л' => 'L', 'л' => 'l', 'М' => 'M', 'м' => 'm',
		'Н' => 'N', 'н' => 'n', 'П' => 'P', 'п' => 'p',
		'Р' => 'R', 'р' => 'r', 'С' => 'S', 'с' => 's',
		'Т' => 'T', 'т' => 't', 'Ф' => 'F', 'ф' => 'f',
		'Х' => 'H', 'х' => 'h', 'Ч' => 'Ç', 'ч' => 'ç',
		'Ш' => 'Ş', 'ш' => 'ş', 'Ы' => 'I', 'ы' => 'ı',
		'Э' => 'E', 'э' => 'e', 'Е' => 'E', 'е' => 'e',
		'Я' => 'Â', 'я' => 'â', 'У' => 'U', 'у' => 'u',
		'О' => 'O', 'о' => 'o',

		'Ё' => 'Yo', 'ё' => 'yo', 'Ю' => 'Yu', 'ю' => 'yu',
		'Ц' => 'Ts', 'ц' => 'ts', 'Щ' => 'Şç', 'щ' => 'şç',
		'Ь' => '', 'ь' => '', 'Ъ' => '', 'ъ' => '',
	];

	public const LATIN_TO_CYRILLIC = [
		'Â' => 'Я', 'â' => 'я', 'B' => 'Б', 'b' => 'б',
		'Ç' => 'Ч', 'ç' => 'ч', 'D' => 'Д', 'd' => 'д',
		'F' => 'Ф', 'f' => 'ф', 'G' => 'Г', 'g' => 'г',
		'H' => 'Х', 'h' => 'х', 'I' => 'Ы', 'ı' => 'ы',
		'İ' => 'И', 'i' => 'и', 'J' => 'Ж', 'j' => 'ж',
		'K' => 'К', 'k' => 'к', 'L' => 'Л', 'l' => 'л',
		'M' => 'М', 'm' => 'м', 'N' => 'Н', 'n' => 'н',
		'O' => 'О', 'o' => 'о', 'P' => 'П', 'p' => 'п',
		'R' => 'Р', 'r' => 'р', 'S' => 'С', 's' => 'с',
		'Ş' => 'Ш', 'ş' => 'ш', 'T' => 'Т', 't' => 'т',
		'V' => 'В', 'v' => 'в', 'Z' => 'З', 'z' => 'з',

		'ya' => 'я', 'Ya' => 'Я', 'YA' => 'Я',
		'ye' => 'е', 'YE' => 'Е', 'Ye' => 'Е',

		// hack, hack, hack
		'A' => 'А', 'a' => 'а', 'E' => 'Е', 'e' => 'е',
		'Ö' => 'Ё', 'ö' => 'ё', 'U' => 'У', 'u' => 'у',
		'Ü' => 'Ю', 'ü' => 'ю', 'Y' => 'Й', 'y' => 'й',
		'C' => 'Дж', 'c' => 'дж', 'Ğ' => 'Гъ', 'ğ' => 'гъ',
		'Ñ' => 'Нъ', 'ñ' => 'нъ', 'Q' => 'Къ', 'q' => 'къ',
	];

	/** @var string[] */
	private array $mCyrl2LatnExceptions = [];
	/** @var string[] */
	private array $mLatn2CyrlExceptions = [];

	/** @var string[] */
	private array $mCyrl2LatnPatterns = [];
	/** @var string[] */
	private array $mLatn2CyrlPatterns = [];

	/** @var string[] */
	private array $mCyrlCleanUpRegexes = [];

	private bool $mExceptionsLoaded = false;

	/**
	 * @inheritDoc
	 */
	protected function loadDefaultTables(): array {
		return [
			'crh-latn' => new ReplacementArray( self::CYRILLIC_TO_LATIN ),
			'crh-cyrl' => new ReplacementArray( self::LATIN_TO_CYRILLIC ),
			'crh' => new ReplacementArray()
		];
	}

	private function loadExceptions() {
		if ( $this->mExceptionsLoaded ) {
			return;
		}

		$this->mExceptionsLoaded = true;
		$crhExceptions = new CrhExceptions();
		[ $this->mCyrl2LatnExceptions, $this->mLatn2CyrlExceptions,
			$this->mCyrl2LatnPatterns, $this->mLatn2CyrlPatterns, $this->mCyrlCleanUpRegexes ] =
			$crhExceptions->loadExceptions( self::L_LC . self::C_LC, self::L_UC . self::C_UC );
	}

	/**
	 * It translates text into variant, specials:
	 * - omitting roman numbers
	 *
	 * @param string $text
	 * @param string $toVariant
	 * @return string
	 */
	public function translate( $text, $toVariant ) {
		switch ( $toVariant ) {
			case 'crh-cyrl':
			case 'crh-latn':
				break;
			default:
				return $text;
		}

		$this->loadTables();

		if ( !isset( $this->mTables[$toVariant] ) ) {
			throw new LogicException( "Broken variant table: " . implode( ',', array_keys( $this->mTables ) ) );
		}

		switch ( $toVariant ) {
			case 'crh-cyrl':
				/* Check for roman numbers like VII, XIX...
				 * Only need to split on Roman numerals when converting to Cyrillic
				 * Lookahead assertion ensures $roman doesn't match the empty string, and
				 * non-period after the first "Roman" character allows initials to be converted
				 */
				$roman = '(?=[MDCLXVI]([^.]|$))M{0,4}(C[DM]|D?C{0,3})(X[LC]|L?X{0,3})(I[VX]|V?I{0,3})';

				$breaks = '([^\w\x80-\xff])';

				// allow for multiple Roman numerals in a row; rare but it happens
				$romanRegex = '/^' . $roman . '$|^(' . $roman . $breaks . ')+|(' . $breaks . $roman . ')+$|' .
					$breaks . '(' . $roman . $breaks . ')+/';

				$matches = preg_split( $romanRegex, $text, -1, PREG_SPLIT_OFFSET_CAPTURE );
				$mstart = 0;
				$ret = '';
				foreach ( $matches as $m ) {
					// copy over Roman numerals
					$ret .= substr( $text, $mstart, (int)$m[1] - $mstart );

					// process everything else
					if ( $m[0] !== '' ) {
						$ret .= $this->regsConverter( $m[0], $toVariant );
					}

					$mstart = (int)$m[1] + strlen( $m[0] );
				}

				return $ret;

			default:
				// Just process the whole string in one go
				return $this->regsConverter( $text, $toVariant );
		}
	}

	private function regsConverter( string $text, string $toVariant ): string {
		if ( $text == '' ) {
			return $text;
		}

		switch ( $toVariant ) {
			case 'crh-latn':
				$text = strtr( $text, $this->mCyrl2LatnExceptions );
				foreach ( $this->mCyrl2LatnPatterns as $pat => $rep ) {
					$text = preg_replace( $pat, $rep, $text );
				}
				$text = parent::translate( $text, $toVariant );
				return strtr( $text, [ '«' => '"', '»' => '"', ] );

			case 'crh-cyrl':
				$text = strtr( $text, $this->mLatn2CyrlExceptions );
				foreach ( $this->mLatn2CyrlPatterns as $pat => $rep ) {
					$text = preg_replace( $pat, $rep, $text );
				}
				$text = parent::translate( $text, $toVariant );
				$text = strtr( $text, [ '“' => '«', '”' => '»', ] );
				foreach ( $this->mCyrlCleanUpRegexes as $pat => $rep ) {
					$text = preg_replace( $pat, $rep, $text );
				}
				return $text;

			default:
				return $text;
		}
	}
}
