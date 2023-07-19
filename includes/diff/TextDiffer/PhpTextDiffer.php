<?php

namespace MediaWiki\Diff\TextDiffer;

use Language;
use Wikimedia\Diff\Diff;
use Wikimedia\Diff\TableDiffFormatter;

/**
 * @since 1.41
 */
class PhpTextDiffer extends BaseTextDiffer {
	/** @var Language|null */
	private $contentLanguage;

	public function __construct( ?Language $contentLanguage ) {
		$this->contentLanguage = $contentLanguage;
	}

	public function getName(): string {
		return 'php';
	}

	public function getFormats(): array {
		return [ 'table' ];
	}

	public function getFormatContext( string $format ) {
		return $format === 'table' ? self::CONTEXT_ROW : self::CONTEXT_PLAIN;
	}

	protected function doRenderBatch( string $oldText, string $newText, array $formats ): array {
		$language = $this->contentLanguage;
		if ( $language ) {
			$oldText = $language->segmentForDiff( $oldText );
			$newText = $language->segmentForDiff( $newText );
		}
		$oldLines = explode( "\n", $oldText );
		$newLines = explode( "\n", $newText );
		$diff = new Diff( $oldLines, $newLines );
		$result = [];
		foreach ( $formats as $format ) {
			// @phan-suppress-next-line PhanNoopSwitchCases -- rectified in followup commit
			switch ( $format ) {
				default: // 'table':
					$formatter = new TableDiffFormatter();
					$diffText = $formatter->format( $diff );
					break;
			}
			if ( $language ) {
				$diffText = $language->unsegmentForDiff( $diffText );
			}
			$result[$format] = $diffText;
		}

		return $result;
	}

	public function localize( string $format, string $diff, array $options = [] ): string {
		return $this->localizeLineNumbers( $diff, $options['reducedLineNumbers'] ?? false );
	}
}
