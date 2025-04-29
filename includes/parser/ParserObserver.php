<?php

/**
 * Observer to detect parser behaviors such as duplicate parses
 *
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
 * @since 1.38
 *
 * @file
 * @ingroup Parser
 *
 * @author Cindy Cicalese
 */

namespace MediaWiki\Parser;

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Content\Content;
use MediaWiki\Page\PageReference;
use MediaWiki\Title\Title;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Wikimedia\MapCacheLRU\MapCacheLRU;

/**
 * For observing and detecting parser behaviors, such as duplicate parses
 * @internal
 * @package MediaWiki\Parser
 */
class ParserObserver {
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	private MapCacheLRU $previousParseStackTraces;

	public function __construct( LoggerInterface $logger ) {
		$this->logger = $logger;
		$this->previousParseStackTraces = new MapCacheLRU( 10 );
	}

	/**
	 * @param PageReference $page
	 * @param int|null $revId
	 * @param ParserOptions $options
	 * @param Content $content
	 * @param ParserOutput $output
	 */
	public function notifyParse(
		PageReference $page, ?int $revId, ParserOptions $options, Content $content, ParserOutput $output
	) {
		$pageKey = CacheKeyHelper::getKeyForPage( $page );

		$optionsHash = $options->optionsHash(
			$output->getUsedOptions(),
			Title::newFromPageReference( $page )
		);

		$contentStr = $content->isValid() ? $content->serialize() : null;
		// $contentStr may be null if the content could not be serialized
		$contentSha1 = $contentStr ? sha1( $contentStr ) : 'INVALID';

		$index = $this->getParseId( $pageKey, $revId, $optionsHash, $contentSha1 );

		$stackTrace = ( new RuntimeException() )->getTraceAsString();
		if ( $this->previousParseStackTraces->has( $index ) ) {

			// NOTE: there may be legitimate changes to re-parse the same WikiText content,
			// e.g. if predicted revision ID for the REVISIONID magic word mismatched.
			// But that should be rare.
			$this->logger->debug(
				__METHOD__ . ': Possibly redundant parse!',
				[
					'page' => $pageKey,
					'rev' => $revId,
					'options-hash' => $optionsHash,
					'contentSha1' => $contentSha1,
					'trace' => $stackTrace,
					'previous-trace' => $this->previousParseStackTraces->get( $index ),
				]
			);
		}
		$this->previousParseStackTraces->set( $index, $stackTrace );
	}

	/**
	 * @param string $titleStr
	 * @param int|null $revId
	 * @param string $optionsHash
	 * @param string $contentSha1
	 * @return string
	 */
	private function getParseId( string $titleStr, ?int $revId, string $optionsHash, string $contentSha1 ): string {
		// $revId may be null when previewing a new page
		$revIdStr = $revId ?? "";

		return "$titleStr.$revIdStr.$optionsHash.$contentSha1";
	}

}
