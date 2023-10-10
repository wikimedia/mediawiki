<?php

namespace MediaWiki\Diff\TextDiffer;

use MediaWiki\Html\Html;
use MediaWiki\Output\OutputPage;
use MessageLocalizer;

/**
 * The base class for specific implementations of TextDiffer, apart from
 * ManifoldTextDiffer.
 *
 * A place for protected utility functions.
 *
 * @since 1.41
 */
abstract class BaseTextDiffer implements TextDiffer {
	/** @var MessageLocalizer */
	private $localizer;

	/**
	 * @param MessageLocalizer $localizer
	 */
	public function setLocalizer(
		MessageLocalizer $localizer
	) {
		$this->localizer = $localizer;
	}

	/**
	 * Provide a MessageLocalizer, or throw if setLocalizer() has not been called.
	 *
	 * @return MessageLocalizer
	 */
	protected function getLocalizer(): MessageLocalizer {
		return $this->localizer;
	}

	public function hasFormat( string $format ): bool {
		return in_array( $format, $this->getFormats(), true );
	}

	public function addRowWrapper( string $format, string $diffText ): string {
		$context = $this->getFormatContext( $format );
		if ( $context === self::CONTEXT_PLAIN ) {
			return "<tr><td colspan=\"4\">$diffText</td></tr>";
		} elseif ( $context === self::CONTEXT_PRE ) {
			return '<tr><td colspan="4">' .
				Html::element( 'pre', [], $diffText ) .
				'</td></tr>';
		} else {
			return $diffText;
		}
	}

	/**
	 * Throw an exception if any of the formats in the array is not supported.
	 *
	 * @param string[] $formats
	 */
	protected function validateFormats( $formats ) {
		$badFormats = array_diff( $formats, $this->getFormats() );
		if ( $badFormats ) {
			throw new \InvalidArgumentException( 'The requested format is not supported by this engine' );
		}
	}

	public function render( string $oldText, string $newText, string $format ): string {
		$result = $this->renderBatch( $oldText, $newText, [ $format ] );
		return reset( $result );
	}

	public function renderBatch( string $oldText, string $newText, array $formats ): array {
		$this->validateFormats( $formats );
		if ( !count( $formats ) ) {
			return [];
		}
		return $this->doRenderBatch( $oldText, $newText, $formats );
	}

	/**
	 * Subclasses should override this to render diffs in the specified formats.
	 * The $formats array is guaranteed to not be empty and to contain only
	 * formats supported by the subclass.
	 *
	 * @param string $oldText
	 * @param string $newText
	 * @param array $formats
	 * @return array
	 */
	abstract protected function doRenderBatch( string $oldText, string $newText, array $formats ): array;

	public function addModules( OutputPage $out, string $format ): void {
	}

	public function getCacheKeys( array $formats ): array {
		return [];
	}

	public function localize( string $format, string $diff, array $options = [] ): string {
		return $diff;
	}

	public function getTablePrefixes( string $format ): array {
		return [];
	}

	public function getPreferredFormatBatch( string $format ): array {
		return [ $format ];
	}

	/**
	 * Replace a common convention for language-independent line numbers with
	 * the text in the language of the current localizer.
	 *
	 * @param string $text
	 * @param bool $reducedLineNumbers
	 *
	 * @return string
	 */
	protected function localizeLineNumbers(
		$text, $reducedLineNumbers
	) {
		// Inline diffs.
		$text = preg_replace_callback( '/<!-- LINES (\d+),(\d+) -->/',
			function ( array $matches ) use ( $reducedLineNumbers ) {
				if ( $matches[1] === '1' && $matches[2] === '1' && $reducedLineNumbers ) {
					return '';
				}
				$msg = $matches[1] === $matches[2]
					? 'lineno'
					: 'lineno-inline';
				return $this->getLocalizer()->msg( $msg )
					->numParams( $matches[1], $matches[2] )
					->escaped();
			}, $text );

		// Table diffs.
		return preg_replace_callback( '/<!--LINE (\d+)-->/',
			function ( array $matches ) use ( $reducedLineNumbers ) {
				if ( $matches[1] === '1' && $reducedLineNumbers ) {
					return '';
				}
				return $this->getLocalizer()->msg( 'lineno' )->numParams( $matches[1] )->escaped();
			}, $text );
	}

}
