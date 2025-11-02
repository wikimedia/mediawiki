<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Export;

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Title\TitleParser;
use WikiExporter;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Factory service for WikiExporter instances.
 *
 * @author Zabe
 * @since 1.38
 */
class WikiExporterFactory {
	/** @var HookContainer */
	private $hookContainer;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var TitleParser */
	private $titleParser;

	/** @var CommentStore */
	private $commentStore;

	/**
	 * @param HookContainer $hookContainer
	 * @param RevisionStore $revisionStore
	 * @param TitleParser $titleParser
	 * @param CommentStore $commentStore
	 */
	public function __construct(
		HookContainer $hookContainer,
		RevisionStore $revisionStore,
		TitleParser $titleParser,
		CommentStore $commentStore
	) {
		$this->hookContainer = $hookContainer;
		$this->revisionStore = $revisionStore;
		$this->titleParser = $titleParser;
		$this->commentStore = $commentStore;
	}

	/**
	 * @param IReadableDatabase $db
	 * @param int|array $history
	 * @param int $text
	 * @param null|array $limitNamespaces
	 *
	 * @return WikiExporter
	 */
	public function getWikiExporter(
		IReadableDatabase $db,
		$history = WikiExporter::CURRENT,
		$text = WikiExporter::TEXT,
		$limitNamespaces = null
	): WikiExporter {
		return new WikiExporter(
			$db,
			$this->commentStore,
			$this->hookContainer,
			$this->revisionStore,
			$this->titleParser,
			$history,
			$text,
			$limitNamespaces
		);
	}
}
