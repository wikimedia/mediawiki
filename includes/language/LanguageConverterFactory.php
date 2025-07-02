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

namespace MediaWiki\Language;

use BanConverter;
use CrhConverter;
use EnConverter;
use GanConverter;
use IuConverter;
use KuConverter;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\StubObject\StubUserLang;
use MniConverter;
use ShConverter;
use ShiConverter;
use SrConverter;
use TgConverter;
use TlyConverter;
use TrivialLanguageConverter;
use UzConverter;
use Wikimedia\ObjectFactory\ObjectFactory;
use WuuConverter;
use ZghConverter;
use ZhConverter;

/**
 * An interface for creating language converters.
 *
 * @since 1.35
 * @ingroup Language
 */
class LanguageConverterFactory {

	/** @var ILanguageConverter[] */
	private $cache = [];
	/**
	 * @var array
	 * @phpcs-require-sorted-array
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
		'ku' => [
			'class' => KuConverter::class,
		],
		'mni' => [
			'class' => MniConverter::class,
		],
		'sh' => [
			'class' => ShConverter::class,
		],
		'shi' => [
			'class' => ShiConverter::class,
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
		'zgh' => [
			'class' => ZghConverter::class,
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

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::UsePigLatinVariant,
		MainConfigNames::DisableLangConversion,
		MainConfigNames::DisableTitleConversion,
	];

	private ServiceOptions $options;
	private ObjectFactory $objectFactory;

	/**
	 * @var callable callback of "() : Language"
	 */
	private $defaultLanguage;

	/**
	 * @param ServiceOptions $options
	 * @param ObjectFactory $objectFactory
	 * @param callable $defaultLanguage callback of "() : Language", should return
	 *  default language. Used in getLanguageConverter when $language is null.
	 *
	 * @internal Should be called from MediaWikiServices only.
	 */
	public function __construct(
		ServiceOptions $options,
		ObjectFactory $objectFactory,
		callable $defaultLanguage
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->objectFactory = $objectFactory;
		if ( $options->get( MainConfigNames::UsePigLatinVariant ) ) {
			$this->converterList['en'] = self::EN_CONVERTER;
		}
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
		return $this->options->get( MainConfigNames::DisableLangConversion );
	}

	/**
	 * Whether to disable language variant conversion for links.
	 *
	 * @return bool
	 */
	public function isLinkConversionDisabled() {
		return $this->options->get( MainConfigNames::DisableLangConversion ) ||
			// Note that this configuration option is misnamed.
			$this->options->get( MainConfigNames::DisableTitleConversion );
	}
}

/** @deprecated class alias since 1.45 */
class_alias( LanguageConverterFactory::class, 'MediaWiki\\Languages\\LanguageConverterFactory' );
