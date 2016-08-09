<?php

namespace MediaWiki\Search\Field;

use SearchEngine;
use UnexpectedValueException;

/**
 * Factory for creating ParserOutputDataSearchField objects.
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
class ParserOutputDataSearchFieldFactory {

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
	 * @return ParserOutputDataSearchField[]
	 */
	public function newFields( array $fieldNames ) {
		$fields = [];

		foreach ( $fieldNames as $fieldName ) {
			$fields[$fieldName] = $this->newField( $fieldName );
		}

		return $fields;
	}

	/**
	 * @param string $fieldName
	 *
	 * @throws UnexpectedValueException;
	 * @return ParserOutputDataSearchField
	 */
	public function newField( $fieldName ) {
		switch ( $fieldName ) {
			case 'category':
				return $this->newCategoryField( $fieldName );
			case 'external_link':
				return $this->newExternalLinkField( $fieldName );
			case 'outgoing_link':
				return $this->newOutgoingLinkField( $fieldName );
			case 'template':
				return $this->newTemplateField( $fieldName );
			default:
				throw new UnexpectedValueException( 'Unknown field: ' . $fieldName );
		}
	}

	/**
	 * @return CategoryField
	 */
	private function newCategoryField( $fieldName ) {
		return new CategoryField( $this->engine, $fieldName );
	}

	/**
	 * @return ExternalLinkField
	 */
	private function newExternalLinkField( $fieldName ) {
		return new ExternalLinkField( $this->engine, $fieldName );
	}

	/**
	 * @return OutgoingLinkField
	 */
	private function newOutgoingLinkField( $fieldName ) {
		return new OutgoingLinkField( $this->engine, $fieldName );
	}

	/**
	 * @return TemplateField
	 */
	private function newTemplateField( $fieldName ) {
		return new TemplateField( $this->engine, $fieldName );
	}

}
