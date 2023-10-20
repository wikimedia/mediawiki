<?php

namespace Mediawiki\OutputTransform;

use Language;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Languages\LanguageFactory;
use Mediawiki\OutputTransform\Stages\AddRedirectHeader;
use Mediawiki\OutputTransform\Stages\AddWrapperDivClass;
use Mediawiki\OutputTransform\Stages\DeduplicateStyles;
use Mediawiki\OutputTransform\Stages\ExpandToAbsoluteUrls;
use Mediawiki\OutputTransform\Stages\ExtractBody;
use Mediawiki\OutputTransform\Stages\HandleSectionLinks;
use Mediawiki\OutputTransform\Stages\HandleTOCMarkers;
use Mediawiki\OutputTransform\Stages\HydrateHeaderPlaceholders;
use Mediawiki\OutputTransform\Stages\PostCacheTransformHookRunner;
use Mediawiki\OutputTransform\Stages\RenderDebugInfo;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\Title\TitleFactory;
use Psr\Log\LoggerInterface;

/**
 * This class contains the default output transformation pipeline factory for wikitext. It is a postprocessor for
 * ParserOutput objects either directly resulting from a parse or fetched from ParserCache.
 * @unstable
 */
class DefaultOutputPipelineFactory {

	private HookContainer $hookContainer;
	private LoggerInterface $logger;
	private TidyDriverBase $tidy;
	private LanguageFactory $langFactory;
	private Language $contentLang;
	private TitleFactory $titleFactory;

	public function __construct(
		HookContainer $hookContainer,
		TidyDriverBase $tidy,
		LanguageFactory $langFactory,
		Language $contentLang,
		LoggerInterface $logger,
		TitleFactory $titleFactory
	) {
		$this->hookContainer = $hookContainer;
		$this->logger = $logger;
		$this->langFactory = $langFactory;
		$this->contentLang = $contentLang;
		$this->tidy = $tidy;
		$this->titleFactory = $titleFactory;
	}

	/**
	 * Creates a pipeline of transformations to transform the content of the ParserOutput object from "parsed HTML"
	 * to "output HTML" and returns it.
	 * @internal
	 * @return OutputTransformPipeline
	 */
	public function buildPipeline(): OutputTransformPipeline {
		return ( new OutputTransformPipeline() )
			->addStage( new ExtractBody() )
			->addStage( new AddRedirectHeader() )
			->addStage( new RenderDebugInfo( $this->hookContainer ) )
			->addStage( new PostCacheTransformHookRunner( $this->hookContainer ) )
			->addStage( new AddWrapperDivClass( $this->langFactory, $this->contentLang ) )
			->addStage( new HandleSectionLinks( $this->logger, $this->titleFactory ) )
			->addStage( new HandleTOCMarkers( $this->tidy ) )
			->addStage( new DeduplicateStyles() )
			->addStage( new ExpandToAbsoluteUrls() )
			->addStage( new HydrateHeaderPlaceholders() );
	}
}
