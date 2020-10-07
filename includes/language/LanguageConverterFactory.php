<?php
/**
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
 */

namespace MediaWiki\Languages;

use CrhConverter;
use EnConverter;
use GanConverter;
use ILanguageConverter;
use IuConverter;
use KkConverter;
use KuConverter;
use Language;
use ShiConverter;
use SrConverter;
use TgConverter;
use TrivialLanguageConverter;
use UzConverter;
use ZhConverter;

/**
 * An interface for creating language converters.
 *
 * @since 1.35
 * @ingroup Language
 */
class LanguageConverterFactory {

	private $cache = [];
	/**
	 * @var array
	 */
	private $converterClasses = [
		'crh' => CrhConverter::class,
		'gan' => GanConverter::class,
		'iu' => IuConverter::class,
		'kk' => KkConverter::class,
		'ku' => KuConverter::class,
		'shi' => ShiConverter::class,
		'sr' => SrConverter::class,
		'tg' => TgConverter::class,
		'uz' => UzConverter::class,
		'zh' => ZhConverter::class,
	];

	private $defaultConverterClass = TrivialLanguageConverter::class;

	/**
	 * @var callable callback of () : Language
	 */
	private $defaultLanguage;

	/**
	 * @param bool $usePigLatinVariant should pig variant of English be used
	 * @param callable $defaultLanguage - callback of () : Language, should return
	 * default language. Used in getLanguageConverter when $language is null.
	 *
	 * @internal Should be called from MediaWikiServices only.
	 */
	public function __construct( $usePigLatinVariant, callable $defaultLanguage ) {
		if ( $usePigLatinVariant ) {
			$this->converterClasses['en'] = EnConverter::class;
		}
		$this->defaultLanguage = $defaultLanguage;
	}

	/**
	 * Returns Converter's class name for given language code
	 *
	 * @param string $code code for which class name should be provided
	 * @return string
	 */
	private function classFromCode( string $code ) : string {
		$code = mb_strtolower( $code );
		return $this->converterClasses[$code] ?? $this->defaultConverterClass;
	}

	/**
	 * Provide a LanguageConverter for given language
	 *
	 * @param Language|null $language for which a LanguageConverter should be provided.
	 * If null then LanguageConverter provided for current content language as returned
	 * by the callback provided to the constructor..
	 *
	 * @return ILanguageConverter
	 */
	public function getLanguageConverter( $language = null ) : ILanguageConverter {
		$lang = $language ?? ( $this->defaultLanguage )();
		if ( isset( $this->cache[$lang->getCode()] ) ) {
			return $this->cache[$lang->getCode()];
		}
		$class = $this->classFromCode( $lang->getCode() );

		$converter = new $class( $lang );
		$this->cache[$lang->getCode()] = $converter;
		return $converter;
	}
}
