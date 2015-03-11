<?php
/**
 * A ContentIndexer implementation based on Content::getTextForSearchIndex.
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
 * A ContentIndexer implementation based on Content::getTextForSearchIndex.
 *
 * @since 1.26
 *
 * @ingroup Content
 */
class BasicContentIndexer implements ContentIndexer {

	/**
	 * Provides an index value for $field = ContentIndexer::SOURCE_FIELD
	 * if $content is an instance of TextContent.
	 *
	 * @param Content $content
	 * @param string $field
	 *
	 * @return string[] An array containing $content's textual content,
	 *         or an empty array().
	 */
	public function getIndexValues( Content $content, $field ) {
		if ( !$content instanceof TextContent ) {
			return array();
		}

		if ( $field === ContentIndexer::SOURCE_FIELD ) {
			return array( $content->getTextForSearchIndex() );
		}

		return array();
	}

	/**
	 * @return IndexFieldDefinition[]
	 */
	public function getIndexFieldDefinitions() {
		return array(
			new IndexFieldDefinition( ContentIndexer::SOURCE_FIELD, IndexFieldDefinition::TEXT_INDEX )
		);
	}

}
