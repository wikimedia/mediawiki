<?php

namespace MediaWiki\Rest\Handler;

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Page\PageLookup;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Revision\RevisionLookup;
use TitleFormatter;

/**
 * @since 1.40 Factory for helper objects designed for sharing logic between REST handlers that deal with page content.
 */
class PageRestHelperFactory {

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = PageContentHelper::CONSTRUCTOR_OPTIONS;

	private ServiceOptions $options;
	private RevisionLookup $revisionLookup;
	private TitleFormatter $titleFormatter;
	private PageLookup $pageLookup;
	private ParsoidOutputStash $parsoidOutputStash;
	private StatsdDataFactoryInterface $stats;
	private ParsoidOutputAccess $parsoidOutputAccess;
	private HtmlTransformFactory $htmlTransformFactory;

	/**
	 * @param ServiceOptions $options
	 * @param RevisionLookup $revisionLookup
	 * @param TitleFormatter $titleFormatter
	 * @param PageLookup $pageLookup
	 * @param ParsoidOutputStash $parsoidOutputStash
	 * @param StatsdDataFactoryInterface $statsDataFactory
	 * @param ParsoidOutputAccess $parsoidOutputAccess
	 * @param HtmlTransformFactory $htmlTransformFactory
	 */
	public function __construct(
		ServiceOptions $options,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		PageLookup $pageLookup,
		ParsoidOutputStash $parsoidOutputStash,
		StatsdDataFactoryInterface $statsDataFactory,
		ParsoidOutputAccess $parsoidOutputAccess,
		HtmlTransformFactory $htmlTransformFactory
	) {
		$this->options = $options;
		$this->revisionLookup = $revisionLookup;
		$this->titleFormatter = $titleFormatter;
		$this->pageLookup = $pageLookup;
		$this->parsoidOutputStash = $parsoidOutputStash;
		$this->stats = $statsDataFactory;
		$this->parsoidOutputAccess = $parsoidOutputAccess;
		$this->htmlTransformFactory = $htmlTransformFactory;
	}

	public function newRevisionContentHelper(): RevisionContentHelper {
		return new RevisionContentHelper(
			$this->options,
			$this->revisionLookup,
			$this->titleFormatter,
			$this->pageLookup
		);
	}

	public function newPageContentHelper(): PageContentHelper {
		return new PageContentHelper(
			$this->options,
			$this->revisionLookup,
			$this->titleFormatter,
			$this->pageLookup
		);
	}

	public function newHtmlOutputRendererHelper(): HtmlOutputRendererHelper {
		return new HtmlOutputRendererHelper(
			$this->parsoidOutputStash,
			$this->stats,
			$this->parsoidOutputAccess,
			$this->htmlTransformFactory
		);
	}

	public function newHtmlInputTransformHelper( $envOptions = [] ): HtmlInputTransformHelper {
		return new HtmlInputTransformHelper(
			$this->stats,
			$this->htmlTransformFactory,
			$this->parsoidOutputStash,
			$this->parsoidOutputAccess,
			$envOptions
		);
	}

}
