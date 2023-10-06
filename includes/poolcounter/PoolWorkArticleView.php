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

use MediaWiki\Logger\Spi as LoggerSpi;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Status\Status;

/**
 * PoolCounter protected work wrapping RenderedRevision->getRevisionParserOutput.
 * Caching behavior may be defined by subclasses.
 *
 * @note No audience checks are applied.
 *
 * @internal
 */
class PoolWorkArticleView extends PoolCounterWork {
	/** @var ParserOptions */
	protected $parserOptions;
	/** @var RevisionRecord */
	protected $revision;
	/** @var RevisionRenderer */
	private $renderer;
	/** @var LoggerSpi */
	protected $loggerSpi;

	/**
	 * @param string $workKey
	 * @param RevisionRecord $revision Revision to render
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse
	 * @param RevisionRenderer $revisionRenderer
	 * @param LoggerSpi $loggerSpi
	 */
	public function __construct(
		string $workKey,
		RevisionRecord $revision,
		ParserOptions $parserOptions,
		RevisionRenderer $revisionRenderer,
		LoggerSpi $loggerSpi
	) {
		parent::__construct( 'ArticleView', $workKey );
		$this->revision = $revision;
		$this->parserOptions = $parserOptions;
		$this->renderer = $revisionRenderer;
		$this->loggerSpi = $loggerSpi;
	}

	/**
	 * @return Status
	 */
	public function doWork() {
		return $this->renderRevision();
	}

	/**
	 * @return Status with the value being a ParserOutput or null
	 */
	public function renderRevision(): Status {
		$renderedRevision = $this->renderer->getRenderedRevision(
			$this->revision,
			$this->parserOptions,
			null,
			[ 'audience' => RevisionRecord::RAW ]
		);

		$time = -microtime( true );
		$parserOutput = $renderedRevision->getRevisionParserOutput();
		$time += microtime( true );

		// Timing hack
		if ( $time > 3 ) {
			// TODO: Use Parser's logger (once it has one)
			$channel = $this->parserOptions->getUseParsoid() ? 'slow-parsoid' : 'slow-parse';
			$logger = $this->loggerSpi->getLogger( $channel );
			$logger->info( 'Parsing {title} was slow, took {time} seconds', [
				'time' => number_format( $time, 2 ),
				'title' => (string)$this->revision->getPageAsLinkTarget(),
				'trigger' => 'view',
			] );
		}

		return Status::newGood( $parserOutput );
	}

	/**
	 * @param Status $status
	 * @return Status
	 */
	public function error( $status ) {
		return $status;
	}

}
