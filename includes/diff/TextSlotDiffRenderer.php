<?php
/**
 * Renders a slot diff by doing a text diff on the native representation.
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
 * @file
 * @ingroup DifferenceEngine
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;
use Wikimedia\Assert\Assert;

/**
 * Renders a slot diff by doing a text diff on the native representation.
 *
 * If you want to use this without content objects (to call getTextDiff() on some
 * non-content-related texts), obtain an instance with
 *     ContentHandler::getForModelID( CONTENT_MODEL_TEXT )
 *         ->getSlotDiffRenderer( RequestContext::getMain() )
 *
 * @ingroup DifferenceEngine
 */
class TextSlotDiffRenderer extends SlotDiffRenderer {

	/** Use the PHP diff implementation (DiffEngine). */
	public const ENGINE_PHP = 'php';

	/** Use the wikidiff2 PHP module. */
	public const ENGINE_WIKIDIFF2 = 'wikidiff2';

	/** Use the wikidiff2 PHP module. */
	public const ENGINE_WIKIDIFF2_INLINE = 'wikidiff2inline';

	/** Use an external executable. */
	public const ENGINE_EXTERNAL = 'external';

	/** @var IBufferingStatsdDataFactory|null */
	private $statsdDataFactory;

	/** @var Language|null The language this content is in. */
	private $language;

	/** @var string One of the ENGINE_* constants. */
	private $engine = self::ENGINE_PHP;

	/** @var string|null Path to an executable to be used as the diff engine. */
	private $externalEngine;

	/** @inheritDoc */
	public function getExtraCacheKeys() {
		// Tell DifferenceEngine this is a different variant from the standard wikidiff2 variant
		return $this->engine === self::ENGINE_WIKIDIFF2_INLINE ? [
			phpversion( 'wikidiff2' ), 'inline'
		] : [];
	}

	/**
	 * Convenience helper to use getTextDiff without an instance.
	 * @param string $oldText
	 * @param string $newText
	 * @return string
	 */
	public static function diff( $oldText, $newText ) {
		/** @var TextSlotDiffRenderer $slotDiffRenderer */
		$slotDiffRenderer = MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( CONTENT_MODEL_TEXT )
			->getSlotDiffRenderer( RequestContext::getMain() );
		'@phan-var TextSlotDiffRenderer $slotDiffRenderer';
		return $slotDiffRenderer->getTextDiff( $oldText, $newText );
	}

	/**
	 * @param IBufferingStatsdDataFactory $statsdDataFactory
	 */
	public function setStatsdDataFactory( IBufferingStatsdDataFactory $statsdDataFactory ) {
		$this->statsdDataFactory = $statsdDataFactory;
	}

	/**
	 * @param Language $language
	 */
	public function setLanguage( Language $language ) {
		$this->language = $language;
	}

	/**
	 * Set which diff engine to use.
	 * @param string $type One of the ENGINE_* constants.
	 * @param string|null $executable Path to an external executable, only when type is ENGINE_EXTERNAL.
	 */
	public function setEngine( $type, $executable = null ) {
		$engines = [ self::ENGINE_PHP, self::ENGINE_WIKIDIFF2, self::ENGINE_EXTERNAL,
			self::ENGINE_WIKIDIFF2_INLINE ];
		Assert::parameter( in_array( $type, $engines, true ), '$type',
			'must be one of the TextSlotDiffRenderer::ENGINE_* constants' );
		if ( $type === self::ENGINE_EXTERNAL ) {
			Assert::parameter( is_string( $executable ) && is_executable( $executable ), '$executable',
				'must be a path to a valid executable' );
		} else {
			Assert::parameter( $executable === null, '$executable',
				'must not be set unless $type is ENGINE_EXTERNAL' );
		}
		$this->engine = $type;
		$this->externalEngine = $executable;
	}

	/** @inheritDoc */
	public function getDiff( Content $oldContent = null, Content $newContent = null ) {
		$this->normalizeContents( $oldContent, $newContent, TextContent::class );

		$oldText = $oldContent->serialize();
		$newText = $newContent->serialize();

		return $this->getTextDiff( $oldText, $newText );
	}

	/**
	 * Diff the text representations of two content objects (or just two pieces of text in general).
	 * @param string $oldText
	 * @param string $newText
	 * @return string HTML, one or more <tr> tags.
	 */
	public function getTextDiff( string $oldText, string $newText ) {
		$diff = function () use ( $oldText, $newText ) {
			$time = microtime( true );

			$result = $this->getTextDiffInternal( $oldText, $newText );

			$time = intval( ( microtime( true ) - $time ) * 1000 );
			if ( $this->statsdDataFactory ) {
				$this->statsdDataFactory->timing( 'diff_time', $time );
			}

			// TODO reimplement this using T142313
			/*
			// Log requests slower than 99th percentile
			if ( $time > 100 && $this->mOldPage && $this->mNewPage ) {
				wfDebugLog( 'diff',
					"$time ms diff: {$this->mOldid} -> {$this->mNewid} {$this->mNewPage}" );
			}
			*/

			return $result;
		};

		/**
		 * @param Status $status
		 * @throws FatalError
		 * @return never
		 */
		$error = static function ( $status ) {
			throw new FatalError( $status->getWikiText() );
		};

		// Use PoolCounter if the diff looks like it can be expensive
		if ( strlen( $oldText ) + strlen( $newText ) > 20000 ) {
			$work = new PoolCounterWorkViaCallback( 'diff',
				md5( $oldText ) . md5( $newText ),
				[ 'doWork' => $diff, 'error' => $error ]
			);
			return $work->execute();
		}

		return $diff();
	}

	/**
	 * Diff the text representations of two content objects (or just two pieces of text in general).
	 * This does the actual diffing, getTextDiff() wraps it with logging and resource limiting.
	 * @param string $oldText
	 * @param string $newText
	 * @return string
	 * @throws Exception
	 * @suppress PhanParamTooManyInternal,UnusedSuppression Spurious Phan warning with wikidiff <1.5.0 or >=1.8.0
	 */
	protected function getTextDiffInternal( $oldText, $newText ) {
		// TODO move most of this into three parallel implementations of a text diff generator
		// class, choose which one to use via dependency injection

		$oldText = str_replace( "\r\n", "\n", $oldText );
		$newText = str_replace( "\r\n", "\n", $newText );

		// Better external diff engine, the 2 may some day be dropped
		// This one does the escaping and segmenting itself
		if ( $this->engine === self::ENGINE_WIKIDIFF2 ) {
			$wikidiff2Version = phpversion( 'wikidiff2' );
			if (
				$wikidiff2Version !== false &&
				version_compare( $wikidiff2Version, '1.5.0', '>=' ) &&
				version_compare( $wikidiff2Version, '1.8.0', '<' )
			) {
				$text = wikidiff2_do_diff(
					$oldText,
					$newText,
					2,
					0
				);
			} else {
				// Don't pass the 4th parameter introduced in version 1.5.0 and removed in version 1.8.0
				$text = wikidiff2_do_diff(
					$oldText,
					$newText,
					2
				);
			}

			return $text;
		} elseif ( $this->engine === self::ENGINE_EXTERNAL ) {
			# Diff via the shell
			$tmpDir = wfTempDir();
			$tempName1 = tempnam( $tmpDir, 'diff_' );
			$tempName2 = tempnam( $tmpDir, 'diff_' );

			$tempFile1 = fopen( $tempName1, "w" );
			if ( !$tempFile1 ) {
				throw new Exception( "Could not create temporary file $tempName1 for external diffing" );
			}
			$tempFile2 = fopen( $tempName2, "w" );
			if ( !$tempFile2 ) {
				throw new Exception( "Could not create temporary file $tempName2 for external diffing" );
			}
			fwrite( $tempFile1, $oldText );
			fwrite( $tempFile2, $newText );
			fclose( $tempFile1 );
			fclose( $tempFile2 );
			$cmd = [ $this->externalEngine, $tempName1, $tempName2 ];
			$result = Shell::command( $cmd )
				->execute();
			$exitCode = $result->getExitCode();
			if ( $exitCode !== 0 ) {
				throw new Exception( "External diff command returned code {$exitCode}. Stderr: "
					. wfEscapeWikiText( $result->getStderr() )
				);
			}
			$difftext = $result->getStdout();
			unlink( $tempName1 );
			unlink( $tempName2 );

			return $difftext;
		} elseif ( $this->engine === self::ENGINE_PHP ) {
			if ( $this->language ) {
				$oldText = $this->language->segmentForDiff( $oldText );
				$newText = $this->language->segmentForDiff( $newText );
			}
			$ota = explode( "\n", $oldText );
			$nta = explode( "\n", $newText );
			$diffs = new Diff( $ota, $nta );
			$formatter = new TableDiffFormatter();
			$difftext = $formatter->format( $diffs );
			if ( $this->language ) {
				$difftext = $this->language->unsegmentForDiff( $difftext );
			}

			return $difftext;
		} elseif ( $this->engine === self::ENGINE_WIKIDIFF2_INLINE ) {
			// Note wikidiff2_inline_diff returns an element sans table.
			// Due to the way other diffs work (return a table with before and after), we need to wrap
			// the output in a row that spans the 4 columns that are expected, so that our diff appears in
			// the correct place!
			return '<tr><td colspan="4">' . wikidiff2_inline_diff( $oldText, $newText, 2 ) . '</td></tr>';
		}
		throw new LogicException( 'Invalid engine: ' . $this->engine );
	}

}
