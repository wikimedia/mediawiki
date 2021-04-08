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
use Psr\Log\LoggerInterface;

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

	/** @var RevisionRecord|null */
	protected $revision = null;

	/** @var RevisionRenderer */
	protected $renderer = null;

	/** @var ParserOutput|bool */
	protected $parserOutput = false;

	/** @var bool */
	protected $isDirty = false;

	/** @var bool */
	protected $isFast = false;

	/** @var Status|bool */
	protected $error = false;

	/** @var LoggerSpi */
	private $loggerSpi;

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
	 * Get the ParserOutput from this object, or false in case of failure
	 *
	 * @return ParserOutput|bool
	 */
	public function getParserOutput() {
		return $this->parserOutput;
	}

	/**
	 * Get whether the ParserOutput is a dirty one (i.e. expired)
	 *
	 * @return bool
	 */
	public function getIsDirty() {
		return $this->isDirty;
	}

	/**
	 * Get whether the ParserOutput was retrieved in fast stale mode
	 *
	 * @return bool
	 */
	public function getIsFastStale() {
		return $this->isFast;
	}

	/**
	 * Get a Status object in case of error or false otherwise
	 *
	 * @return Status|bool
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * @return bool
	 */
	public function doWork() {
		$renderedRevision = $this->renderer->getRenderedRevision(
			$this->revision,
			$this->parserOptions,
			null,
			[ 'audience' => RevisionRecord::RAW ]
		);

		if ( !$renderedRevision ) {
			// audience check failed
			return false;
		}

		// Reduce effects of race conditions for slow parses (T48014)
		$cacheTime = wfTimestampNow();

		$time = -microtime( true );
		$this->parserOutput = $renderedRevision->getRevisionParserOutput();
		$time += microtime( true );

		// Timing hack
		if ( $time > 3 ) {
			// TODO: Use Parser's logger (once it has one)
			$logger = $this->getLogger( 'slow-parse' );
			$logger->info( 'Parsing {title} was slow, took {time} seconds', [
				'time' => number_format( $time, 2 ),
				'title' => $this->revision->getPageAsLinkTarget()->getDBkey(),
				'ns' => $this->revision->getPageAsLinkTarget()->getNamespace(),
				'trigger' => 'view',
			] );
		}

		if ( $this->cacheable && $this->parserOutput->isCacheable() ) {
			$this->saveInCache( $this->parserOutput, $cacheTime );
		}

		$this->afterWork( $this->parserOutput );

		return true;
	}

	/**
	 * Place the output in the cache from which getCachedWork() will retrieve it.
	 * Will be called before saveInCache().
	 *
	 * @param ParserOutput $output
	 * @param string $cacheTime
	 */
	protected function saveInCache( ParserOutput $output, string $cacheTime ) {
		// noop
	}

	/**
	 * Subclasses may implement this to perform some action after the work of rendering is done.
	 * Will be called after saveInCache().
	 *
	 * @param ParserOutput $output
	 */
	protected function afterWork( ParserOutput $output ) {
		// noop
	}

	/**
	 * @param Status $status
	 * @return bool
	 */
	public function error( $status ) {
		$this->error = $status;
		return false;
	}

	/**
	 * @param string $name
	 *
	 * @return LoggerInterface
	 */
	protected function getLogger( $name = 'PoolWorkArticleView' ): LoggerInterface {
		return $this->loggerSpi->getLogger( $name );
	}
}
