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

use MediaWiki\BadFileLookup;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserOptionsLookup;
use Psr\Log\LoggerInterface;

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

	/** @var string */
	private $urlProtocols;

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

	/**
	 * @param ServiceOptions $svcOptions
	 * @param MagicWordFactory $magicWordFactory
	 * @param Language $contLang Content language
	 * @param string $urlProtocols As returned from wfUrlProtocols()
	 * @param SpecialPageFactory $spFactory
	 * @param LinkRendererFactory $linkRendererFactory
	 * @param NamespaceInfo $nsInfo
	 * @param LoggerInterface $logger
	 * @param BadFileLookup $badFileLookup
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param HookContainer $hookContainer
	 * @param TidyDriverBase $tidy
	 * @param WANObjectCache $wanCache
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param UserFactory $userFactory
	 * @param TitleFormatter $titleFormatter
	 * @param HttpRequestFactory $httpRequestFactory
	 * @param TrackingCategories $trackingCategories
	 * @since 1.32
	 * @internal
	 */
	public function __construct(
		ServiceOptions $svcOptions,
		MagicWordFactory $magicWordFactory,
		Language $contLang,
		string $urlProtocols,
		SpecialPageFactory $spFactory,
		LinkRendererFactory $linkRendererFactory,
		NamespaceInfo $nsInfo,
		LoggerInterface $logger,
		BadFileLookup $badFileLookup,
		LanguageConverterFactory $languageConverterFactory,
		HookContainer $hookContainer,
		TidyDriverBase $tidy,
		WANObjectCache $wanCache,
		UserOptionsLookup $userOptionsLookup,
		UserFactory $userFactory,
		TitleFormatter $titleFormatter,
		HttpRequestFactory $httpRequestFactory,
		TrackingCategories $trackingCategories
	) {
		$svcOptions->assertRequiredOptions( Parser::CONSTRUCTOR_OPTIONS );

		wfDebug( __CLASS__ . ": using default preprocessor" );

		$this->svcOptions = $svcOptions;
		$this->magicWordFactory = $magicWordFactory;
		$this->contLang = $contLang;
		$this->urlProtocols = $urlProtocols;
		$this->specialPageFactory = $spFactory;
		$this->linkRendererFactory = $linkRendererFactory;
		$this->nsInfo = $nsInfo;
		$this->logger = $logger;
		$this->badFileLookup = $badFileLookup;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->hookContainer = $hookContainer;
		$this->tidy = $tidy;
		$this->wanCache = $wanCache;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->userFactory = $userFactory;
		$this->titleFormatter = $titleFormatter;
		$this->httpRequestFactory = $httpRequestFactory;
		$this->trackingCategories = $trackingCategories;
	}

	/**
	 * Creates a new parser
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
				$this,
				$this->urlProtocols,
				$this->specialPageFactory,
				$this->linkRendererFactory,
				$this->nsInfo,
				$this->logger,
				$this->badFileLookup,
				$this->languageConverterFactory,
				$this->hookContainer,
				$this->tidy,
				$this->wanCache,
				$this->userOptionsLookup,
				$this->userFactory,
				$this->titleFormatter,
				$this->httpRequestFactory,
				$this->trackingCategories
			);
		} finally {
			self::$inParserFactory--;
		}
	}
}
