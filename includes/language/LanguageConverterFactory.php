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

use BanConverter;
use CrhConverter;
use EnConverter;
use GanConverter;
use ILanguageConverter;
use IuConverter;
use KkConverter;
use KuConverter;
use Language;
use MediaWiki\StubObject\StubUserLang;
use ShConverter;
use ShiConverter;
use SrConverter;
use TgConverter;
use TlyConverter;
use TrivialLanguageConverter;
use UzConverter;
use Wikimedia\ObjectFactory\ObjectFactory;
use WuuConverter;
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
	private $converterList = [
		'ban' => [
			'class' => BanConverter::class,
		],
		'crh' => [
			'class' => CrhConverter::class,
		],
		'gan' => [
			'class' => GanConverter::class,
		],
		'iu' => [
			'class' => IuConverter::class,
		],
		'kk' => [
			'class' => KkConverter::class,
		],
		'ku' => [
			'class' => KuConverter::class,
		],
		'shi' => [
			'class' => ShiConverter::class,
		],
		'sh' => [
			'class' => ShConverter::class,
		],
		'sr' => [
			'class' => SrConverter::class,
		],
		'tg' => [
			'class' => TgConverter::class,
		],
		'tly' => [
			'class' => TlyConverter::class,
		],
		'uz' => [
			'class' => UzConverter::class,
		],
		'wuu' => [
			'class' => WuuConverter::class,
		],
		'zh' => [
			'class' => ZhConverter::class,
		],
	];

	private const DEFAULT_CONVERTER = [
		'class' => TrivialLanguageConverter::class,
		'services' => [
			'TitleFormatter',
		]
	];

	private const EN_CONVERTER = [
		'class' => EnConverter::class,
	];

	/** @var ObjectFactory */
	private $objectFactory;

	/**
	 * @var bool Whether to disable language variant conversion.
	 */
	private $isConversionDisabled;

	/**
	 * @var bool Whether to disable language variant conversion for links.
	 */
	private $isTitleConversionDisabled;

	/**
	 * @var callable callback of "() : Language"
	 */
	private $defaultLanguage;

	/**
	 * @param ObjectFactory $objectFactory
	 * @param bool $usePigLatinVariant should pig variant of English be used
	 * @param bool $isConversionDisabled Whether to disable language variant conversion
	 * @param bool $isTitleConversionDisabled Whether to disable language variant conversion for links
	 * @param callable $defaultLanguage callback of "() : Language", should return
	 *  default language. Used in getLanguageConverter when $language is null.
	 *
	 * @internal Should be called from MediaWikiServices only.
	 */
	public function __construct(
		ObjectFactory $objectFactory,
		$usePigLatinVariant, $isConversionDisabled, $isTitleConversionDisabled,
		callable $defaultLanguage
	) {
		$this->objectFactory = $objectFactory;
		if ( $usePigLatinVariant ) {
			$this->converterList['en'] = self::EN_CONVERTER;
		}
		$this->isConversionDisabled = $isConversionDisabled;
		$this->isTitleConversionDisabled = $isTitleConversionDisabled;
		$this->defaultLanguage = $defaultLanguage;
	}

	/**
	 * Returns Converter instance for a given language object
	 *
	 * @param Language|StubUserLang $lang
	 * @return ILanguageConverter
	 */
	private function instantiateConverter( $lang ): ILanguageConverter {
		$code = mb_strtolower( $lang->getCode() );
		$spec = $this->converterList[$code] ?? self::DEFAULT_CONVERTER;
		// ObjectFactory::createObject accepts an array, not just a callable (phan bug)
		// @phan-suppress-next-line PhanTypeInvalidCallableArrayKey, PhanTypeInvalidCallableArraySize
		return $this->objectFactory->createObject(
			$spec,
			[
				'assertClass' => ILanguageConverter::class,
				'extraArgs' => [ $lang ],
			]
		);
	}

	/**
	 * Provide a LanguageConverter for given language
	 *
	 * @param Language|StubUserLang|null $language for which a LanguageConverter should be provided.
	 * If it is null, then the LanguageConverter provided for current content language as returned
	 * by the callback provided to the constructor.
	 *
	 * @return ILanguageConverter
	 */
	public function getLanguageConverter( $language = null ): ILanguageConverter {
		$lang = $language ?? ( $this->defaultLanguage )();
		if ( isset( $this->cache[$lang->getCode()] ) ) {
			return $this->cache[$lang->getCode()];
		}
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable False positive
		$converter = $this->instantiateConverter( $lang );
		$this->cache[$lang->getCode()] = $converter;
		return $converter;
	}

	/**
	 * Whether to disable language variant conversion.
	 *
	 * @return bool
	 */
	public function isConversionDisabled() {
		return $this->isConversionDisabled;
	}

	/**
	 * Whether to disable language variant conversion for titles.
	 *
	 * @return bool
	 * @deprecated since 1.36 Should use ::isLinkConversionDisabled() instead
	 */
	public function isTitleConversionDisabled() {
		wfDeprecated( __METHOD__, '1.36' );
		return $this->isTitleConversionDisabled;
	}

	/**
	 * Whether to disable language variant conversion for links.
	 *
	 * @return bool
	 */
	public function isLinkConversionDisabled() {
		return $this->isConversionDisabled || $this->isTitleConversionDisabled;
	}
}
