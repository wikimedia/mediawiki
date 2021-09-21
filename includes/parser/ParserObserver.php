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
use MediaWiki\Page\PageReference;
use ParserOptions;
use ParserOutput;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Title;

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

	/**
	 * @var array
	 */
	private $previousParseStackTraces;

	/**
	 * @param LoggerInterface $logger
	 */
	public function __construct( LoggerInterface $logger ) {
		$this->logger = $logger;
		$this->previousParseStackTraces = [];
	}

	/**
	 * @param PageReference $page
	 * @param int|null $revId
	 * @param ParserOptions $options
	 * @param ParserOutput $output
	 */
	public function notifyParse(
		PageReference $page, ?int $revId, ParserOptions $options, ParserOutput $output
	) {
		$pageKey = CacheKeyHelper::getKeyForPage( $page );

		$optionsHash = $options->optionsHash(
			$output->getUsedOptions(),
			Title::castFromPageReference( $page )
		);

		$index = $this->getParseId( $pageKey, $revId, $optionsHash );

		$stackTrace = ( new RuntimeException() )->getTraceAsString();
		if ( array_key_exists( $index, $this->previousParseStackTraces ) ) {

			// NOTE: there may be legitimate changes to re-parse the same WikiText content,
			// e.g. if predicted revision ID for the REVISIONID magic word mismatched.
			// But that should be rare.
			$this->logger->debug(
				__METHOD__ . ': Possibly redundant parse!',
				[
					'page' => $pageKey,
					'rev' => $revId,
					'options-hash' => $optionsHash,
					'trace' => $stackTrace,
					'previous-trace' => $this->previousParseStackTraces[$index],
				]
			);
		}
		$this->previousParseStackTraces[$index] = $stackTrace;
	}

	/**
	 * @param string $titleStr
	 * @param int|null $revId
	 * @param string $optionsHash
	 * @return string
	 */
	private function getParseId( string $titleStr, ?int $revId, string $optionsHash ): string {
		// $revId may be null when previewing a new page
		$revIdStr = $revId ?? "";

		return "$titleStr.$revIdStr.$optionsHash";
	}

}
