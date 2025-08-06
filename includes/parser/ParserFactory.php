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
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

use MediaWiki\Category\TrackingCategories;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Utils\UrlUtils;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * @since 1.32
 */
class ParserFactory {
	/** @var ServiceOptions */
	private $svcOptions;

	/** @var MagicWordFactory */
	private $magicWordFactory;

	/** @var Language */
	private $contLang;

	/** @var UrlUtils */
	private $urlUtils;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var LinkRendererFactory */
	private $linkRendererFactory;

	/** @var NamespaceInfo */
	private $nsInfo;

	/** @var LoggerInterface */
	private $logger;

	/** @var BadFileLookup */
	private $badFileLookup;

	/** @var LanguageConverterFactory */
	private $languageConverterFactory;

	/** @var LanguageNameUtils */
	private $languageNameUtils;

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	/** @var UserFactory */
	private $userFactory;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var HttpRequestFactory */
	private $httpRequestFactory;

	/** @var TrackingCategories */
	private $trackingCategories;

	/** @var SignatureValidatorFactory */
	private $signatureValidatorFactory;

	/** @var UserNameUtils */
	private $userNameUtils;

	/**
	 * Track calls to Parser constructor to aid in deprecation of direct
	 * Parser invocation.  This is temporary: it will be removed once the
	 * deprecation notice period is over and the underlying method calls
	 * are refactored.
	 * @internal
	 * @var int
	 */
	public static $inParserFactory = 0;

	/** @var HookContainer */
	private $hookContainer;

	/** @var TidyDriverBase */
	private $tidy;

	/** @var WANObjectCache */
	private $wanCache;

	/** @var Parser|null */
	private $mainInstance;

	/**
	 * @param ServiceOptions $svcOptions
	 * @param MagicWordFactory $magicWordFactory
	 * @param Language $contLang Content language
	 * @param UrlUtils $urlUtils
	 * @param SpecialPageFactory $spFactory
	 * @param LinkRendererFactory $linkRendererFactory
	 * @param NamespaceInfo $nsInfo
	 * @param LoggerInterface $logger
	 * @param BadFileLookup $badFileLookup
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param LanguageNameUtils $languageNameUtils
	 * @param HookContainer $hookContainer
	 * @param TidyDriverBase $tidy
	 * @param WANObjectCache $wanCache
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param UserFactory $userFactory
	 * @param TitleFormatter $titleFormatter
	 * @param HttpRequestFactory $httpRequestFactory
	 * @param TrackingCategories $trackingCategories
	 * @param SignatureValidatorFactory $signatureValidatorFactory
	 * @param UserNameUtils $userNameUtils
	 * @since 1.32
	 * @internal
	 */
	public function __construct(
		ServiceOptions $svcOptions,
		MagicWordFactory $magicWordFactory,
		Language $contLang,
		UrlUtils $urlUtils,
		SpecialPageFactory $spFactory,
		LinkRendererFactory $linkRendererFactory,
		NamespaceInfo $nsInfo,
		LoggerInterface $logger,
		BadFileLookup $badFileLookup,
		LanguageConverterFactory $languageConverterFactory,
		LanguageNameUtils $languageNameUtils,
		HookContainer $hookContainer,
		TidyDriverBase $tidy,
		WANObjectCache $wanCache,
		UserOptionsLookup $userOptionsLookup,
		UserFactory $userFactory,
		TitleFormatter $titleFormatter,
		HttpRequestFactory $httpRequestFactory,
		TrackingCategories $trackingCategories,
		SignatureValidatorFactory $signatureValidatorFactory,
		UserNameUtils $userNameUtils
	) {
		$svcOptions->assertRequiredOptions( Parser::CONSTRUCTOR_OPTIONS );

		wfDebug( __CLASS__ . ": using default preprocessor" );

		$this->svcOptions = $svcOptions;
		$this->magicWordFactory = $magicWordFactory;
		$this->contLang = $contLang;
		$this->urlUtils = $urlUtils;
		$this->specialPageFactory = $spFactory;
		$this->linkRendererFactory = $linkRendererFactory;
		$this->nsInfo = $nsInfo;
		$this->logger = $logger;
		$this->badFileLookup = $badFileLookup;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->languageNameUtils = $languageNameUtils;
		$this->hookContainer = $hookContainer;
		$this->tidy = $tidy;
		$this->wanCache = $wanCache;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->userFactory = $userFactory;
		$this->titleFormatter = $titleFormatter;
		$this->httpRequestFactory = $httpRequestFactory;
		$this->trackingCategories = $trackingCategories;
		$this->signatureValidatorFactory = $signatureValidatorFactory;
		$this->userNameUtils = $userNameUtils;
	}

	/**
	 * Creates a new parser
	 *
	 * @note Use this function to get a new Parser instance to store
	 * in a local class property.  Where possible use lazy creation and
	 * create the Parser only when needed, not directly in service wiring.
	 *
	 * @return Parser
	 * @since 1.32
	 */
	public function create(): Parser {
		self::$inParserFactory++;
		try {
			return new Parser(
				$this->svcOptions,
				$this->magicWordFactory,
				$this->contLang,
				$this->urlUtils,
				$this->specialPageFactory,
				$this->linkRendererFactory,
				$this->nsInfo,
				$this->logger,
				$this->badFileLookup,
				$this->languageConverterFactory,
				$this->languageNameUtils,
				$this->hookContainer,
				$this->tidy,
				$this->wanCache,
				$this->userOptionsLookup,
				$this->userFactory,
				$this->titleFormatter,
				$this->httpRequestFactory,
				$this->trackingCategories,
				$this->signatureValidatorFactory,
				$this->userNameUtils
			);
		} finally {
			self::$inParserFactory--;
		}
	}

	/**
	 * Get the main shared instance. This is unsafe when the caller is not in
	 * a top-level context, because re-entering the parser will throw an
	 * exception.
	 *
	 * @note This function is used to get metadata from the parser. Avoid
	 * using this function to parse wikitext.  (Generally avoid using this
	 * function at all in new code.)
	 *
	 * @since 1.39
	 * @return Parser
	 */
	public function getMainInstance() {
		if ( $this->mainInstance === null ) {
			$this->mainInstance = $this->create();
		}
		return $this->mainInstance;
	}

	/**
	 * Get the main shared instance, or if it is locked, get a new instance
	 *
	 * @note This function was used to parse wikitext. The instance it
	 * returned should be used only in local scope.  Do not hold a
	 * reference to this parser in class properties.  In general,
	 * avoid using this method and use ::create() instead.
	 *
	 * @since 1.39
	 * @return Parser
	 */
	public function getInstance() {
		$instance = $this->getMainInstance();
		if ( $instance->isLocked() ) {
			$instance = $this->create();
		}
		return $instance;
	}

}

/** @deprecated class alias since 1.43 */
class_alias( ParserFactory::class, 'ParserFactory' );
