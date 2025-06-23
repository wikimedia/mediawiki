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

use MediaWiki\Content\Content;
use MediaWiki\Content\TextContent;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Diff\TextDiffer\ManifoldTextDiffer;
use MediaWiki\Diff\TextDiffer\TextDiffer;
use MediaWiki\Exception\FatalError;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\PoolCounter\PoolCounterWorkViaCallback;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use OOUI\ToggleSwitchWidget;
use Wikimedia\Stats\IBufferingStatsdDataFactory;
use Wikimedia\Stats\StatsFactory;

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

	public const INLINE_LEGEND_KEY = '10_mw-diff-inline-legend';

	public const INLINE_SWITCHER_KEY = '60_mw-diff-inline-switch';

	/** @var StatsFactory|null */
	private $statsFactory;

	/** @var HookRunner|null */
	private $hookRunner;

	/** @var string|null */
	private $format;

	/** @var string */
	private $contentModel;

	/** @var TextDiffer|null */
	private $textDiffer;

	/** @var bool */
	private $inlineToggleEnabled = false;

	/** @inheritDoc */
	public function getExtraCacheKeys() {
		return $this->textDiffer->getCacheKeys( [ $this->format ] );
	}

	/**
	 * Convenience helper to use getTextDiff without an instance.
	 * @param string $oldText
	 * @param string $newText
	 * @param array $options
	 * @return string
	 */
	public static function diff( $oldText, $newText, $options = [] ) {
		/** @var TextSlotDiffRenderer $slotDiffRenderer */
		$slotDiffRenderer = MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( CONTENT_MODEL_TEXT )
			->getSlotDiffRenderer( RequestContext::getMain(), $options );
		'@phan-var TextSlotDiffRenderer $slotDiffRenderer';
		return $slotDiffRenderer->getTextDiff( $oldText, $newText );
	}

	/**
	 * This has no effect since MW 1.43.
	 *
	 * @internal Use ContentHandler::createTextSlotDiffRenderer instead
	 * @param IBufferingStatsdDataFactory $statsdDataFactory
	 */
	public function setStatsdDataFactory( IBufferingStatsdDataFactory $statsdDataFactory ) {
		wfDeprecated( __METHOD__, '1.43' );
	}

	/**
	 * @internal Use ContentHandler::createTextSlotDiffRenderer instead
	 * @param StatsFactory $statsFactory
	 */
	public function setStatsFactory( StatsFactory $statsFactory ) {
		$this->statsFactory = $statsFactory;
	}

	/**
	 * This has no effect since MW 1.41. The language is now injected via setTextDiffer().
	 *
	 * @param Language $language
	 * @deprecated since 1.41
	 */
	public function setLanguage( Language $language ) {
		wfDeprecated( __METHOD__, '1.41' );
	}

	/**
	 * @internal Use ContentHandler::createTextSlotDiffRenderer instead
	 * @since 1.41
	 * @param HookContainer $hookContainer
	 */
	public function setHookContainer( HookContainer $hookContainer ): void {
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * @param string $contentModel
	 * @since 1.41
	 */
	public function setContentModel( string $contentModel ) {
		$this->contentModel = $contentModel;
	}

	/**
	 * Set which diff engine to use.
	 *
	 * @param string $type One of the ENGINE_* constants.
	 * @param null $executable Must be null since 1.41. Previously a path to execute.
	 */
	public function setEngine( $type, $executable = null ) {
		if ( $executable !== null ) {
			throw new \InvalidArgumentException(
				'The $executable parameter is no longer supported and must be null'
			);
		}
		switch ( $type ) {
			case self::ENGINE_PHP:
				$engine = 'php';
				$format = 'table';
				break;

			case self::ENGINE_WIKIDIFF2:
				$engine = 'wikidiff2';
				$format = 'table';
				break;

			case self::ENGINE_EXTERNAL:
				$engine = 'external';
				$format = 'external';
				break;

			case self::ENGINE_WIKIDIFF2_INLINE:
				$engine = 'wikidiff2';
				$format = 'inline';
				break;

			default:
				throw new \InvalidArgumentException( '$type ' .
					'must be one of the TextSlotDiffRenderer::ENGINE_* constants' );
		}
		if ( $this->textDiffer instanceof ManifoldTextDiffer ) {
			$this->textDiffer->setEngine( $engine );
		}
		$this->setFormat( $format );
	}

	/**
	 * Set the TextDiffer format
	 *
	 * @since 1.41
	 * @param string $format
	 */
	public function setFormat( $format ) {
		$this->format = $format;
	}

	public function setTextDiffer( TextDiffer $textDiffer ) {
		$this->textDiffer = $textDiffer;
	}

	/**
	 * Get the current TextDiffer, or throw an exception if setTextDiffer() has
	 * not been called.
	 */
	private function getTextDiffer(): TextDiffer {
		return $this->textDiffer;
	}

	/**
	 * Set a flag indicating whether the inline toggle switch is shown.
	 *
	 * @since 1.41
	 * @param bool $enabled
	 */
	public function setInlineToggleEnabled( $enabled = true ) {
		$this->inlineToggleEnabled = $enabled;
	}

	/**
	 * Get the content model ID that this renderer acts on
	 *
	 * @since 1.41
	 * @return string
	 */
	public function getContentModel(): string {
		return $this->contentModel;
	}

	/** @inheritDoc */
	public function getDiff( ?Content $oldContent = null, ?Content $newContent = null ) {
		$this->normalizeContents( $oldContent, $newContent, TextContent::class );

		$oldText = $oldContent->serialize();
		$newText = $newContent->serialize();

		return $this->getTextDiff( $oldText, $newText );
	}

	/** @inheritDoc */
	public function localizeDiff( $diff, $options = [] ) {
		return $this->textDiffer->localize( $this->format, $diff, $options );
	}

	/**
	 * @inheritDoc
	 */
	public function getTablePrefix( IContextSource $context, Title $newTitle ): array {
		$parts = $this->getTextDiffer()->getTablePrefixes( $this->format );

		$showDiffToggleSwitch = $this->inlineToggleEnabled && $this->getTextDiffer()->hasFormat( 'inline' );
		// If we support the inline type, add a toggle switch
		if ( $showDiffToggleSwitch ) {
			$values = $context->getRequest()->getQueryValues();
			$isInlineDiffType = $this->format === 'inline';
			$values[ 'diff-type' ] = $isInlineDiffType ? 'table' : 'inline';
			unset( $values[ 'title' ] );
			$parts[self::INLINE_SWITCHER_KEY] = Html::rawElement( 'div',
				[ 'class' => 'mw-diffPage-inlineToggle-container' ],
				new OOUI\FieldLayout(
					new ToggleSwitchWidget( [
						'id' => 'mw-diffPage-inline-toggle-switch',
						'href' => $newTitle->getLocalURL( $values ),
						'value' => $isInlineDiffType,
						'title' => $context->msg( 'diff-inline-switch-desc' )->plain()
					] ),
					[
						'id' => 'mw-diffPage-inline-toggle-switch-layout',
						'label' => $context->msg( 'diff-inline-format-label' )->plain(),
						'infusable' => true,
						'title' => $context->msg( 'diff-inline-switch-desc' )->plain()
					]
				),
			);
		}
		// Add an empty placeholder for the legend is added when it's not in
		// use and other items have been added.
		$parts += [ self::INLINE_LEGEND_KEY => null, self::INLINE_SWITCHER_KEY => null ];

		// Allow extensions to add other parts to this area (or modify the legend).
		$this->hookRunner->onTextSlotDiffRendererTablePrefix( $this, $context, $parts );
		if ( count( $parts ) > 1 && $parts[self::INLINE_LEGEND_KEY] === null ) {
			$parts[self::INLINE_LEGEND_KEY] = Html::element( 'div' );
		}
		return $parts;
	}

	/**
	 * Diff the text representations of two content objects (or just two pieces of text in general).
	 * @param string $oldText
	 * @param string $newText
	 * @return string HTML. One or more <tr> tags, or an empty string if the inputs are identical.
	 */
	public function getTextDiff( string $oldText, string $newText ) {
		$diff = function () use ( $oldText, $newText ) {
			$time = microtime( true );

			$result = $this->getTextDiffInternal( $oldText, $newText );

			$time = intval( ( microtime( true ) - $time ) * 1000 );

			if ( $this->statsFactory ) {
				$this->statsFactory->getTiming( 'diff_text_seconds' )
					->copyToStatsdAt( 'diff_time' )
					->observe( $time );
			}

			return $result;
		};

		/**
		 * @param Status $status
		 * @throws FatalError
		 * @return never
		 */
		$error = static function ( $status ): never {
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
	 */
	protected function getTextDiffInternal( $oldText, $newText ) {
		$oldText = str_replace( "\r\n", "\n", $oldText );
		$newText = str_replace( "\r\n", "\n", $newText );

		if ( $oldText === $newText ) {
			return '';
		}

		$textDiffer = $this->getTextDiffer();
		$diffText = $textDiffer->render( $oldText, $newText, $this->format );
		return $textDiffer->addRowWrapper( $this->format, $diffText );
	}

}
