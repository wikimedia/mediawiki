<?php
/**
 * A ContentIndexer provides search index values for Content objects.
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
 * A ContentIndexer provides search index values for Content objects.
 *
 * @since 1.26
 *
 * @ingroup Content
 */
interface ContentIndexer {

	/**
	 * Field name for the raw markup of a page.
	 * Should be present for all text-based content models.
	 * Must use the IndexFieldDefinition::TEXT_INDEX type.
	 */
	const SOURCE_FIELD = 'source';

	/**
	 * Returns the values for the index field identified by $field.
	 * The values are determined in some way by analyzing $content.
	 * If the field is not known or has no value, an empty array is returned.
	 *
	 * The type, format and interpretation of the array elements are defined
	 * by the corresponding IndexFieldDefinition returned by
	 * getIndexFieldDefinitions(). Refer to the documentation of the
	 * IndexFieldDefinition::XXX_INDEX constants for details.
	 *
	 * @param Content $content
	 * @param string $field
	 *
	 * @return array A list of index values corresponding to $field as applied to $content.
	 */
	public function getIndexValues( Content $content, $field );

	/**
	 * @return IndexFieldDefinition[]
	 */
	public function getIndexFieldDefinitions();

}
