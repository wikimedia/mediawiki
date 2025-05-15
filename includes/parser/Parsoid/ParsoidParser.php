<?php

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Content\TextContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Parser\Parsoid\Config\DataAccess;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Assert\Assert;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Parsoid\Parsoid;

/**
 * Parser implementation which uses Parsoid.
 *
 * Currently incomplete; see T236809 for the long-term plan.
 *
 * @since 1.41
 * @unstable since 1.41; see T236809 for plan.
 */
class ParsoidParser /* eventually this will extend \Parser */ {
	/**
	 * @unstable
	 * This should not be used widely right now since this may go away.
	 * This is being added to support DiscussionTools with Parsoid HTML
	 * and after initial exploration, this may be implemented differently.
	 */
	public const PARSOID_TITLE_KEY = "parsoid:title-dbkey";
	private Parsoid $parsoid;
	private PageConfigFactory $pageConfigFactory;
	private LanguageConverterFactory $languageConverterFactory;
	private DataAccess $dataAccess;

	/**
	 * @param Parsoid $parsoid
	 * @param PageConfigFactory $pageConfigFactory
	 * @param LanguageConverterFactory $languageConverterFactory
	 */
	public function __construct(
		Parsoid $parsoid,
		PageConfigFactory $pageConfigFactory,
		LanguageConverterFactory $languageConverterFactory,
		DataAccess $dataAccess
	) {
		$this->parsoid = $parsoid;
		$this->pageConfigFactory = $pageConfigFactory;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->dataAccess = $dataAccess;
	}

	/**
	 * Internal helper to avoid code deuplication across two methods
	 *
	 * @param PageConfig $pageConfig
	 * @param ParserOptions $options
	 * @return ParserOutput
	 */
	private function genParserOutput(
		PageConfig $pageConfig, ParserOptions $options, ?ParserOutput $previousOutput
	): ParserOutput {
		$parserOutput = new ParserOutput();

		// Parsoid itself does not vary output by parser options right now.
		// But, ensure that any option use by extensions, parser functions,
		// recursive parses, or (in the unlikely future scenario) Parsoid itself
		// are recorded as used.
		$options->registerWatcher( [ $parserOutput, 'recordOption' ] );

		// The enable/disable logic here matches that in Parser::internalParseHalfParsed(),
		// although __NOCONTENTCONVERT__ is handled internal to Parsoid.
		//
		// T349137: It might be preferable to handle __NOCONTENTCONVERT__ here rather than
		// by inspecting the DOM inside Parsoid. That will come in a separate patch.
		$htmlVariantLanguage = null;
		if ( !( $options->getDisableContentConversion() || $options->getInterfaceMessage() ) ) {
			// NOTES (some of these are TODOs for read views integration)
			// 1. This html variant conversion is a pre-cache transform. HtmlOutputRendererHelper
			//    has another variant conversion that is a post-cache transform based on the
			//    'Accept-Language' header. If that header is set, there is really no reason to
			//    do this conversion here. So, eventually, we are likely to either not pass in
			//    the htmlVariantLanguage option below OR disable language conversion from the
			//    wt2html path in Parsoid and this and the Accept-Language variant conversion
			//    both would have to be handled as post-cache transforms.
			//
			// 2. Parser.php calls convert() which computes a preferred variant from the
			//    target language. But, we cannot do that unconditionally here because REST API
			//    requests specify the exact variant via the 'Content-Language' header.
			//
			//    For Parsoid page views, either the callers will have to compute the
			//    preferred variant and set it in ParserOptions OR the REST API will have
			//    to set some other flag indicating that the preferred variant should not
			//    be computed. For now, I am adding a temporary hack, but this should be
			//    replaced with something more sensible (T267067).
			//
			// 3. Additionally, Parsoid's callers will have to set targetLanguage in ParserOptions
			//    to mimic the logic in Parser.php (missing right now).
			$langCode = $pageConfig->getPageLanguageBcp47();
			if ( $options->getRenderReason() === 'page-view' ) { // TEMPORARY HACK
				$langFactory = MediaWikiServices::getInstance()->getLanguageFactory();
				$lang = $langFactory->getLanguage( $langCode );
				$langConv = $this->languageConverterFactory->getLanguageConverter( $lang );
				$htmlVariantLanguage = $langFactory->getLanguage( $langConv->getPreferredVariant() );
			} else {
				$htmlVariantLanguage = $langCode;
			}
		}
		$oldPageConfig = null;
		$oldPageBundle = null;

		// T371713: Temporary statistics collection code to determine
		// feasibility of Parsoid selective update
		$sampleRate = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::ParsoidSelectiveUpdateSampleRate
		);
		$doSample = ( $sampleRate && mt_rand( 1, $sampleRate ) === 1 );
		if ( $doSample && $previousOutput !== null && $previousOutput->getCacheRevisionId() ) {
			// Allow fetching the old wikitext corresponding to the
			// $previousOutput
			$oldPageConfig = $this->pageConfigFactory->create(
				Title::newFromLinkTarget( $pageConfig->getLinkTarget() ),
				$options->getUserIdentity(),
				$previousOutput->getCacheRevisionId(),
				null,
				$previousOutput->getLanguage(),
			);
			$oldPageBundle =
				PageBundleParserOutputConverter::pageBundleFromParserOutput(
					$previousOutput
				);
		}

		$defaultOptions = [
			'pageBundle' => true,
			'wrapSections' => true,
			'logLinterData' => true,
			'body_only' => false,
			'htmlVariantLanguage' => $htmlVariantLanguage,
			'offsetType' => 'byte',
			'outputContentVersion' => Parsoid::defaultHTMLVersion(),
			'previousOutput' => $oldPageBundle,
			'previousInput' => $oldPageConfig,
			// The following are passed for metrics & labelling
			'sampleStats' => $doSample,
			'renderReason' => $options->getRenderReason(),
			'userAgent' => RequestContext::getMain()->getRequest()->getHeader( 'User-Agent' ),
		];

		$parserOutput->resetParseStartTime();

		// This can throw ClientError or ResourceLimitExceededException.
		// Callers are responsible for figuring out how to handle them.
		$pageBundle = $this->parsoid->wikitext2html(
			$pageConfig,
			$defaultOptions,
			$headers,
			$parserOutput );

		$parserOutput = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle, $parserOutput );

		// Record the page title in dbkey form so that post-cache transforms
		// have access to the title.
		$parserOutput->setExtensionData(
			self::PARSOID_TITLE_KEY,
			Title::newFromLinkTarget( $pageConfig->getLinkTarget() )->getPrefixedDBkey()
		);

		// Register a watcher again because the $parserOutput arg
		// and $parserOutput return value above are different objects!
		$options->registerWatcher( [ $parserOutput, 'recordOption' ] );

		$parserOutput->setFromParserOptions( $options );

		$parserOutput->recordTimeProfile();
		$this->dataAccess->makeLimitReport( $pageConfig, $options, $parserOutput );

		// T371713: Collect statistics on parsing time -vs- presence of
		// $previousOutput
		$stats = MediaWikiServices::getInstance()->getStatsFactory();
		$labels = [
			'type' => $previousOutput === null ? 'full' : 'selective',
			'wiki' => WikiMap::getCurrentWikiId(),
			'reason' => $options->getRenderReason() ?: 'unknown',
			'has_async_content' =>
				$parserOutput->getOutputFlag( ParserOutputFlags::HAS_ASYNC_CONTENT )
				? 'true' : 'false',
			'async_not_ready' =>
				$parserOutput->getOutputFlag( ParserOutputFlags::ASYNC_NOT_READY )
				? 'true' : 'false',
		];
		$stats
			->getCounter( 'Parsoid_parse_cpu_seconds' )
			->setLabels( $labels )
			->incrementBy( $parserOutput->getTimeProfile( 'cpu' ) );
		$stats
			->getCounter( 'Parsoid_parse_total' )
			->setLabels( $labels )
			->increment();

		// Add Parsoid skinning module
		$parserOutput->addModuleStyles( [ 'mediawiki.skinning.content.parsoid' ] );

		// Record Parsoid version in extension data; this allows
		// us to use the onRejectParserCacheValue hook to selectively
		// expire "bad" generated content in the event of a rollback.
		$parserOutput->setExtensionData(
			'core:parsoid-version', Parsoid::version()
		);
		$parserOutput->setExtensionData(
			'core:html-version', Parsoid::defaultHTMLVersion()
		);

		return $parserOutput;
	}

	/**
	 * Convert wikitext to HTML
	 * Do not call this function recursively.
	 *
	 * @param string|TextContent $text Text we want to parse
	 * @param-taint $text escapes_htmlnoent
	 * @param PageReference $page
	 * @param ParserOptions $options
	 * @param bool $linestart
	 * @param bool $clearState
	 * @param int|null $revId ID of the revision being rendered. This is used to render
	 *  REVISION* magic words. 0 means that any current revision will be used. Null means
	 *  that {{REVISIONID}}/{{REVISIONUSER}} will be empty and {{REVISIONTIMESTAMP}} will
	 *  use the current timestamp.
	 * @param ?ParserOutput $previousOutput The (optional) result of a
	 *  previous parse of this page, which can be used for selective update.
	 * @return ParserOutput
	 * @return-taint escaped
	 * @unstable since 1.41
	 */
	public function parse(
		$text, PageReference $page, ParserOptions $options,
		bool $linestart = true, bool $clearState = true, ?int $revId = null,
		?ParserOutput $previousOutput = null
	): ParserOutput {
		Assert::invariant( $linestart, '$linestart=false is not yet supported' );
		Assert::invariant( $clearState, '$clearState=false is not yet supported' );
		$title = Title::newFromPageReference( $page );
		$lang = $options->getTargetLanguage();
		if ( $lang === null && $options->getInterfaceMessage() ) {
			$lang = $options->getUserLangObj();
		}
		$pageConfig = $revId === null || $revId === 0 ? null : $this->pageConfigFactory->create(
			$title,
			$options->getUserIdentity(),
			$revId,
			null, // unused
			$lang // defaults to title page language if null
		);
		$content = null;
		if ( $text instanceof TextContent ) {
			$content = $text;
			$text = $content->getText();
		}
		if ( !( $pageConfig && $pageConfig->getPageMainContent() === $text ) ) {
			// This is a bit awkward! But we really need to parse $text, which
			// may or may not correspond to the $revId provided!
			// T332928 suggests one solution: splitting the "have revid"
			// callers from the "bare text, no associated revision" callers.
			$revisionRecord = new MutableRevisionRecord( $title );
			if ( $revId !== null ) {
				$revisionRecord->setId( $revId );
			}
			$revisionRecord->setSlot(
				SlotRecord::newUnsaved(
					SlotRecord::MAIN,
					$content ?? new WikitextContent( $text )
				)
			);
			$pageConfig = $this->pageConfigFactory->create(
				$title,
				$options->getUserIdentity(),
				$revisionRecord,
				null, // unused
				$lang // defaults to title page language if null
			);
		}

		return $this->genParserOutput( $pageConfig, $options, $previousOutput );
	}

	/**
	 * @internal
	 *
	 * Convert custom wikitext (stored in main slot of the $fakeRev arg) to HTML.
	 * Callers are expected NOT to stuff the result into ParserCache.
	 *
	 * @param RevisionRecord $fakeRev Revision to parse
	 * @param PageReference $page
	 * @param ParserOptions $options
	 * @return ParserOutput
	 * @unstable since 1.41
	 */
	public function parseFakeRevision(
		RevisionRecord $fakeRev, PageReference $page, ParserOptions $options
	): ParserOutput {
		wfDeprecated( __METHOD__, '1.43' );
		$title = Title::newFromPageReference( $page );
		$lang = $options->getTargetLanguage();
		if ( $lang === null && $options->getInterfaceMessage() ) {
			$lang = $options->getUserLangObj();
		}
		$pageConfig = $this->pageConfigFactory->create(
			$title,
			$options->getUserIdentity(),
			$fakeRev,
			null, // unused
			$lang // defaults to title page language if null
		);

		return $this->genParserOutput( $pageConfig, $options, null );
	}
}
