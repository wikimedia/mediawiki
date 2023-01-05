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

namespace MediaWiki\Export;

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Revision\RevisionStore;
use TitleParser;
use WikiExporter;
use Wikimedia\Rdbms\IDatabase;

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
	 * @param IDatabase $db
	 * @param int|array $history
	 * @param int $text
	 * @param null|array $limitNamespaces
	 *
	 * @return WikiExporter
	 */
	public function getWikiExporter(
		IDatabase $db,
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
