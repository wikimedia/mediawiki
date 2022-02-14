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
 */

namespace MediaWiki\Skin;

use OutputPage;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
class SkinComponentTableOfContents implements SkinComponent {
	/** @var OutputPage */
	private $output;

	/**
	 * @param OutputPage $output
	 */
	public function __construct( OutputPage $output ) {
		$this->output = $output;
	}

	/**
	 * Enriches section data by nesting child elements within parent elements
	 * such that the table of contents can be rendered in Mustache.
	 *
	 * Example Mustache template code:
	 * <ul>
	 * {{#array-sections}}
	 *   <li>{{number}} {{line}}</li>
	 *   {{#array-subsections}}
	 *     {{>TableOfContents__subsection}}
	 *   {{/array-subsections}}
	 * {{/array-sections}}
	 * </ul>
	 *
	 * @return array
	 */
	private function getSectionsData(): array {
		return $this->getSectionsDataInternal( $this->output->getSections(), 1 );
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
			// Set all the parent sections at the current top level.
			if ( $section['toclevel'] === $toclevel ) {
				$childSections = $this->getSectionsDataInternal(
					array_slice( $sections, $i + 1 ),
					$toclevel + 1
				);
				$data[] = $section + [
					'array-sections' => $childSections,
					'is-top-level-section' => $toclevel === 1,
					'is-parent-section' => !empty( $childSections )
				];
			}
			// Child section belongs to a higher parent.
			if ( $section['toclevel'] < $toclevel ) {
				return $data;
			}
		}
		return $data;
	}

	/**
	 * Get table of contents template data
	 *
	 * @return array
	 */
	private function getTOCData(): array {
		// Return data only if TOC present T298796.
		if ( !$this->output->isTOCEnabled() ) {
			return [];
		}
		return [
			'array-sections' => $this->getSectionsData(),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return $this->getTOCData();
	}
}
