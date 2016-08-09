<?php

namespace MediaWiki\Search\Field;

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
	 * @return SearchIndexField
	 */
	public function getCategoryField() {
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
	public function getExternalLinkField() {
		return $this->engine->makeSearchFieldMapping(
			'external_link',
			SearchIndexField::INDEX_TYPE_KEYWORD
		);
	}

	/**
	 * @return SearchIndexField
	 */
	public function getOutgoingLinkField() {
		return $this->engine->makeSearchFieldMapping(
			'outgoing_link',
			SearchIndexField::INDEX_TYPE_KEYWORD
		);
	}

	/**
	 * @return SearchIndexField
	 */
	public function getTemplateField() {
		$field = $this->engine->makeSearchFieldMapping(
			'template',
			SearchIndexField::INDEX_TYPE_KEYWORD
		);

		$field->setFlag( SearchIndexField::FLAG_CASEFOLD );

		return $field;
	}

}
