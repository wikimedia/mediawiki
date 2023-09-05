<?php

namespace MediaWiki\Diff\TextDiffer;

use MediaWiki\Output\OutputPage;

/**
 * An interface for parts of a diff page view which represent changes to text
 *
 * @since 1.41
 */
interface TextDiffer {
	/**
	 * The HTML returned is an ordinary block
	 */
	public const CONTEXT_PLAIN = 'plain';

	/**
	 * The HTML returned is zero or more table rows
	 */
	public const CONTEXT_ROW = 'row';

	/**
	 * The return value is plain text and should be wrapped in a <pre>
	 */
	public const CONTEXT_PRE = 'pre';

	/**
	 * Get a stable unique name to identify this differ in a cache key.
	 */
	public function getName(): string;

	/**
	 * Get the supported format names
	 *
	 * @return string[]
	 */
	public function getFormats(): array;

	/**
	 * Determine whether we support the specified format
	 *
	 * @param string $format
	 * @return bool
	 */
	public function hasFormat( string $format ): bool;

	/**
	 * Get the context for a given format. Returns one of the CONTEXT_* constants.
	 *
	 * @param string $format
	 * @return string
	 */
	public function getFormatContext( string $format );

	/**
	 * Make the context consistent by adding a colspan=4 wrapper around plain
	 * HTML output.
	 *
	 * @param string $format
	 * @param string $diffText
	 * @return string
	 */
	public function addRowWrapper( string $format, string $diffText ): string;

	/**
	 * Generate a diff comparing $oldText with $newText.
	 * The result must be passed through localize() before being sent to the user.
	 *
	 * @param string $oldText
	 * @param string $newText
	 * @param string $format
	 * @return string
	 */
	public function render( string $oldText, string $newText, string $format ): string;

	/**
	 * Render a diff in multiple formats.
	 * The results must be passed through localize() before being sent to the user.
	 *
	 * @param string $oldText
	 * @param string $newText
	 * @param string[] $formats
	 * @return array An array with the format in the key and the diff in the value.
	 */
	public function renderBatch( string $oldText, string $newText, array $formats ): array;

	/**
	 * Modify the OutputPage, adding any headers required by the specified format.
	 *
	 * @param OutputPage $out
	 * @param string $format
	 * @return void
	 */
	public function addModules( OutputPage $out, string $format ): void;

	/**
	 * Get additional cache keys required by the specified formats.
	 *
	 * The result should have unique string keys, so that cache keys can be
	 * deduplicated.
	 *
	 * @param string[] $formats
	 * @return array<string,string>
	 */
	public function getCacheKeys( array $formats ): array;

	/**
	 * Expand messages in the diff text using the current MessageLocalizer.
	 *
	 * Perform any other necessary post-cache transformations.
	 *
	 * @param string $format
	 * @param string $diff
	 * @param array $options An associative array of options, may contain:
	 *   - reducedLineNumbers: If true, remove "line 1" but allow other line numbers
	 * @return string
	 */
	public function localize( string $format, string $diff, array $options = [] ): string;

	/**
	 * Get table prefixes for the specified format. These are HTML fragments
	 * placed above all slot diffs. The key should be a string, used for sorting
	 * and deduplication.
	 *
	 * @param string $format
	 * @return array
	 */
	public function getTablePrefixes( string $format ): array;

	/**
	 * Given a format, get a list of formats which can be generated at the same
	 * time with minimal additional CPU cost.
	 *
	 * @param string $format
	 * @return string[]
	 */
	public function getPreferredFormatBatch( string $format ): array;
}
