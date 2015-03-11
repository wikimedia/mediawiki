<?php
/**
 * A basic OutputIndexer implementation based on the well known fields in ParserOutput.
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
 * @ingroup Content
 *
 * @author Daniel Kinzler
 */

/**
 * A basic OutputIndexer implementation based on the well known fields in ParserOutput.
 *
 * @since 1.26
 *
 * @ingroup ParserOutput
 */
class BasicOutputIndexer implements OutputIndexer {

	/**
	 * Provides an index values for the fields OutputIndexer::RENDERED_FIELD
	 * and OutputIndexer::PAGELINKS_FIELD.
	 *
	 * @param ParserOutput $output
	 * @param string $field
	 *
	 * @return string[] An array containing values for the requested field,
	 *         or an empty array().
	 */
	public function getIndexValues( ParserOutput $output, $field ) {
		if ( $field === OutputIndexer::RENDERED_FIELD ) {
			return array( $output->getText() );
		}

		if ( $field === OutputIndexer::PAGELINK_FIELD ) {
			return $this->getPagelinks( $output );
		}

		return array();
	}

	/**
	 * @param ParserOutput $output
	 *
	 * @return string[]
	 */
	private function getPagelinks( ParserOutput $output ) {
		$pagelinks = array();

		foreach ( $output->getLinks() as $ns => $titles ) {
			foreach ( $titles as $title ) {
				//TODO: inject TitleFormater instead of using global state!
				//$tv = new TitleValue( $ns, $title );
				//$pagelinks[] = $this->titleFormatter->getFullText( $tv );
				$title = Title::makeTitle( $ns, $title );
				$pagelinks[] = $title->getFullText();
			}
		}

		return $pagelinks;
	}

	/**
	 * @return IndexFieldDefinition[]
	 */
	public function getIndexFieldDefinitions() {
		return array(
			new IndexFieldDefinition( OutputIndexer::RENDERED_FIELD, IndexFieldDefinition::TEXT_INDEX ),
			new IndexFieldDefinition( OutputIndexer::PAGELINK_FIELD, IndexFieldDefinition::STRING_INDEX, IndexFieldDefinition::OPTIONAL_USAGE, 1.5 ),
		);
	}

}
