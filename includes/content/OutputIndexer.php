<?php
/**
 * A OutputIndexer provides search index values for ParserOutput objects.
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
 * @ingroup ^
 *
 * @author Daniel Kinzler
 */

/**
 * A OutputIndexer provides search index values for ParserOutput objects.
 *
 * @since 1.26
 *
 * @ingroup Content
 */
interface OutputIndexer {

	/**
	 * Field name for the rendered HTML output of a page.
	 * Must use the ParserOutputIndexFieldDefinition::HTML_INDEX or MULTILANG_HTML_INDEX type.
	 */
	const RENDERED_FIELD = 'rendered';

	/**
	 * Field name for outgoing page links.
	 * Must use the ParserOutputIndexFieldDefinition::STRING_INDEX or MULTILANG_STRING_INDEX type.
	 */
	const PAGELINK_FIELD = 'pagelink';

	/**
	 * Returns the values for the index field identified by $field.
	 * The values are determined in some way by analyzing $output.
	 * If the field is not known or has no value, an empty array is returned.
	 *
	 * The type, format and interpretation of the array elements are defined
	 * by the corresponding ParserOutputIndexFieldDefinition returned by
	 * getIndexFieldDefinitions(). Refer to the documentation of the
	 * ParserOutputIndexFieldDefinition::XXX_INDEX constants for details.
	 *
	 * @param ParserOutput $output
	 * @param string $field
	 *
	 * @return array A list of index values corresponding to $field as applied to $output.
	 */
	public function getIndexValues( ParserOutput $output, $field );

	/**
	 * @return IndexFieldDefinition[]
	 */
	public function getIndexFieldDefinitions();

}
