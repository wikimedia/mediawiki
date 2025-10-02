<?php
/**
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Skin;

use MediaWiki\Output\OutputPage;
use MediaWiki\Parser\ParserOutputFlags;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
class SkinComponentTableOfContents implements SkinComponent {
	/** @var OutputPage */
	private $output;

	public function __construct( OutputPage $output ) {
		$this->output = $output;
	}

	/**
	 * Nests child sections within their parent sections.
	 *
	 * @param array $sections
	 * @param int $toclevel
	 * @return array
	 */
	private function getSectionsDataInternal( array $sections, int $toclevel = 1 ): array {
		$data = [];
		foreach ( $sections as $i => $section ) {
			// Child section belongs to a higher parent.
			if ( $section->tocLevel < $toclevel ) {
				return $data;
			}

			// Set all the parent sections at the current top level.
			if ( $section->tocLevel === $toclevel ) {
				$childSections = $this->getSectionsDataInternal(
					array_slice( $sections, $i + 1 ),
					$toclevel + 1
				);
				$data[] = $section->toLegacy() + [
					'array-sections' => $childSections,
					'is-top-level-section' => $toclevel === 1,
					'is-parent-section' => $childSections !== []
				];
			}
		}
		return $data;
	}

	/**
	 * Get table of contents template data
	 *
	 * Enriches section data by nesting child elements within parent elements
	 * such that the table of contents can be rendered in Mustache.
	 *
	 * For an example of how to render the data, see TableOfContents.mustache in
	 * the Vector skin.
	 */
	private function getTOCDataInternal(): array {
		$tocData = $this->output->getTOCData();
		// Return data only if TOC present T298796.
		if ( $tocData === null ) {
			return [];
		}
		// Respect __NOTOC__
		if ( $this->output->getOutputFlag( ParserOutputFlags::NO_TOC ) ) {
			return [];
		}

		$outputSections = $tocData->getSections();

		return count( $outputSections ) > 0 ? [
			'number-section-count' => count( $outputSections ),
			'array-sections' => $this->getSectionsDataInternal( $outputSections, 1 ),
		] : [];
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return $this->getTOCDataInternal();
	}
}
