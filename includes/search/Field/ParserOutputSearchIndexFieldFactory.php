<?php

namespace MediaWiki\Search\Field;

use InvalidArgumentException;
use SearchEngine;
use SearchIndexField;

/**
 * Creates SearchIndexFields for ParserOutput data (e.g. external links).
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
 * @since 1.28
 */
class ParserOutputSearchIndexFieldFactory {

	/**
	 * @var SearchEngine
	 */
	private $engine;

	/**
	 * @param SearchEngine $engine
	 */
	public function __construct( SearchEngine $engine ) {
		$this->engine = $engine;
	}

	/**
	 * @param string[] $fieldNames
	 *
	 * @throws InvalidArgumentException
	 * @return SearchIndexField
	 */
	public function newFields( array $fieldNames ) {
		$fields = [];

		foreach ( $fieldNames as $fieldName ) {
			$fields[$fieldName] = $this->newField( $fieldName );
		}

		return $fields;
	}

	/**
	 * @throws InvalidArgumentException
	 * @return SearchIndexField
	 */
	public function newField( $fieldName ) {
		switch ( $fieldName ) {
			case 'category':
				return $this->newCategoryField();
			case 'external_link':
				return $this->newKeywordSearchIndexField( $fieldName );
			case 'outgoing_link':
				return $this->newKeywordSearchIndexField( $fieldName );
			case 'template':
				return $this->newTemplateField( $fieldName );
			default:
				throw new InvalidArgumentException( 'Unknown field name: ' . $fieldName );
		}
	}

	/**
	 * @return SearchIndexField
	 */
	private function newCategoryField() {
		$field = $this->engine->makeSearchFieldMapping(
			'category',
			SearchIndexField::INDEX_TYPE_TEXT
		);

		$field->setFlag( SearchIndexField::FLAG_CASEFOLD );

		return $field;
	}

	/**
	 * @return SearchIndexField
	 */
	private function newTemplateField() {
		$field = $this->newKeywordSearchIndexField( 'template' );
		$field->setFlag( SearchIndexField::FLAG_CASEFOLD );

		return $field;
	}

	/**
	 * @return SearchIndexField
	 */
	private function newKeywordSearchIndexField( $fieldName ) {
		return $this->engine->makeSearchFieldMapping(
			$fieldName,
			SearchIndexField::INDEX_TYPE_KEYWORD
		);
	}

}
