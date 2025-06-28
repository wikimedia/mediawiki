<?php

namespace MediaWiki\Diff\TextDiffer;

use MediaWiki\Html\Html;
use TextSlotDiffRenderer;

/**
 * @since 1.41
 */
class Wikidiff2TextDiffer extends BaseTextDiffer {
	/** @var string */
	private $version;
	/** @var bool */
	private $haveMoveSupport;
	/** @var bool */
	private $haveMultiFormatSupport;
	/** @var bool */
	private $haveCutoffParameter;
	/** @var bool */
	private $useMultiFormat;
	/** @var array */
	private $defaultOptions;
	/** @var array[] */
	private $formatOptions;
	/** @var string */
	private $optionsHash;

	private const OPT_NAMES = [
		'numContextLines',
		'changeThreshold',
		'movedLineThreshold',
		'maxMovedLines',
		'maxWordLevelDiffComplexity',
		'maxSplitSize',
		'initialSplitThreshold',
		'finalSplitThreshold',
	];

	/**
	 * Fake wikidiff2 extension version for PHPUnit testing
	 * @var string|null
	 */
	public static $fakeVersionForTesting = null;

	/**
	 * Determine whether the extension is installed (or mocked for testing)
	 *
	 * @return bool
	 */
	public static function isInstalled() {
		return self::$fakeVersionForTesting !== null
			|| function_exists( 'wikidiff2_do_diff' );
	}

	/**
	 * @param array $options
	 */
	public function __construct( $options ) {
		$this->version = self::$fakeVersionForTesting ?? phpversion( 'wikidiff2' );
		$this->haveMoveSupport = version_compare( $this->version, '1.5.0', '>=' );
		$this->haveMultiFormatSupport = version_compare( $this->version, '1.14.0', '>=' );
		$this->haveCutoffParameter = $this->haveMoveSupport
			&& version_compare( $this->version, '1.8.0', '<' );

		$this->useMultiFormat = $this->haveMultiFormatSupport && !empty( $options['useMultiFormat'] );
		$validOpts = array_fill_keys( self::OPT_NAMES, true );
		$this->defaultOptions = array_intersect_key( $options, $validOpts );
		$this->formatOptions = [];
		foreach ( $options['formatOptions'] ?? [] as $format => $formatOptions ) {
			$this->formatOptions[$format] = array_intersect_key( $formatOptions, $validOpts );
		}
	}

	public function getName(): string {
		return 'wikidiff2';
	}

	/** @inheritDoc */
	public function getFormatContext( string $format ) {
		return $format === 'inline' ? self::CONTEXT_PLAIN : self::CONTEXT_ROW;
	}

	public function getCacheKeys( array $formats ): array {
		return [
			'20-wikidiff2-version' => $this->version,
			'21-wikidiff2-options' => $this->getOptionsHash(),
		];
	}

	/**
	 * Get a hash of the cache-varying constructor options
	 *
	 * @return string
	 */
	private function getOptionsHash() {
		if ( $this->optionsHash === null ) {
			$json = json_encode(
				[
					$this->useMultiFormat,
					$this->defaultOptions,
					$this->formatOptions,
				],
				JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
			);
			$this->optionsHash = substr( md5( $json ), 0, 8 );
		}
		return $this->optionsHash;
	}

	public function doRenderBatch( string $oldText, string $newText, array $formats ): array {
		if ( $this->useMultiFormat ) {
			if ( !$this->formatOptions ) {
				/** @var array $result */
				$result = wikidiff2_multi_format_diff(
					$oldText,
					$newText,
					[ 'formats' => $formats ] + $this->defaultOptions
				);
			} else {
				$result = [];
				foreach ( $formats as $format ) {
					$result[$format] = wikidiff2_multi_format_diff(
						$oldText,
						$newText,
						[ 'formats' => $formats ]
						+ ( $this->formatOptions[$format] ?? [] )
						+ $this->defaultOptions
					);
				}
			}
		} else {
			$result = [];
			foreach ( $formats as $format ) {
				switch ( $format ) {
					case 'table':
						$result['table'] = $this->doTableFormat( $oldText, $newText );
						break;

					case 'inline':
						$result['inline'] = $this->doInlineFormat( $oldText, $newText );
						break;
				}
			}
		}
		return $result;
	}

	/**
	 * Do a table format diff
	 *
	 * @param string $old
	 * @param string $new
	 * @return string
	 */
	private function doTableFormat( $old, $new ) {
		if ( $this->haveCutoffParameter ) {
			return wikidiff2_do_diff(
				$old,
				$new,
				2,
				0
			);
		} else {
			// Don't pass the 4th parameter introduced in version 1.5.0 and removed in version 1.8.0
			return wikidiff2_do_diff(
				$old,
				$new,
				2
			);
		}
	}

	/**
	 * Do an inline format diff
	 *
	 * @param string $oldText
	 * @param string $newText
	 * @return string
	 */
	private function doInlineFormat( $oldText, $newText ) {
		return wikidiff2_inline_diff( $oldText, $newText, 2 );
	}

	public function getFormats(): array {
		return [ 'table', 'inline' ];
	}

	public function getTablePrefixes( string $format ): array {
		$localizer = $this->getLocalizer();
		$ins = Html::element( 'span',
			[ 'class' => 'mw-diff-inline-legend-ins' ],
			$localizer->msg( 'diff-inline-tooltip-ins' )->plain()
		);
		$del = Html::element( 'span',
			[ 'class' => 'mw-diff-inline-legend-del' ],
			$localizer->msg( 'diff-inline-tooltip-del' )->plain()
		);
		$hideDiffClass = $format === 'inline' ? '' : 'oo-ui-element-hidden';
		$legend = Html::rawElement( 'div',
			[ 'class' => 'mw-diff-inline-legend ' . $hideDiffClass ], "$del $ins"
		);
		return [ TextSlotDiffRenderer::INLINE_LEGEND_KEY => $legend ];
	}

	public function localize( string $format, string $diff, array $options = [] ): string {
		$diff = $this->localizeLineNumbers( $diff,
			$options['reducedLineNumbers'] ?? false
		);
		if ( $this->haveMoveSupport ) {
			$diff = $this->addLocalizedTitleTooltips( $format, $diff );
		}
		return $diff;
	}

	/**
	 * Add title attributes for tooltips on various diff elements
	 *
	 * @param string $format
	 * @param string $text
	 * @return string
	 */
	private function addLocalizedTitleTooltips( $format, $text ) {
		// Moved paragraph indicators.
		$localizer = $this->getLocalizer();
		$replacements = [
			'class="mw-diff-movedpara-right"' =>
				'class="mw-diff-movedpara-right" title="' .
				$localizer->msg( 'diff-paragraph-moved-toold' )->escaped() . '"',
			'class="mw-diff-movedpara-left"' =>
				'class="mw-diff-movedpara-left" title="' .
				$localizer->msg( 'diff-paragraph-moved-tonew' )->escaped() . '"',
		];
		// For inline diffs, add tooltips to `<ins>` and `<del>`.
		if ( $format == 'inline' ) {
			$replacements['<ins>'] = Html::openElement( 'ins',
				[ 'title' => $localizer->msg( 'diff-inline-tooltip-ins' )->plain() ] );
			$replacements['<del>'] = Html::openElement( 'del',
				[ 'title' => $localizer->msg( 'diff-inline-tooltip-del' )->plain() ] );
		}
		return strtr( $text, $replacements );
	}

	public function getPreferredFormatBatch( string $format ): array {
		if ( $this->formatOptions ) {
			return [ $format ];
		} else {
			return [ 'table', 'inline' ];
		}
	}
}
