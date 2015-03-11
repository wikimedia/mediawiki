<?php
/**
 * A IndexFieldDefinition provides information about an index field.
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
 * A IndexFieldDefinition provides information about an index field.
 *
 * @see ContentIndexer
 *
 * @since 1.26
 *
 * @ingroup Content
 */
class IndexFieldDefinition {

	/**
	 * Index type for identifier-like strings which should be accessible via a prefix index.
	 */
	const STRING_INDEX = 'string';

	/**
	 * Index type for text-like strings which should be accessible via a fulltext index.
	 */
	const TEXT_INDEX = 'text';

	/**
	 * Index type for HTML markup strings which should be accessible via a fulltext index.
	 */
	const HTML_INDEX = 'html';

	/**
	 * Index type for per-language identifier-like strings which should be accessible
	 * via a prefix index. Values are represented as associative arrays mapping
	 * language coded to strings.
	 */
	const MULTILINGUAL_STRING_INDEX = 'string-multi';

	/**
	 * Index type for per-language text-like strings which should be accessible
	 * via a fulltext index. Values are represented as associative arrays mapping
	 * language coded to strings.
	 */
	const MULTILINGUAL_TEXT_INDEX = 'text-multi';

	/**
	 * Index type for per-language HTML markup strings which should be accessible
	 * via a fulltext index. Values are represented as associative arrays mapping
	 * language coded to strings.
	 */
	const MULTILINGUAL_HTML_INDEX = 'html-multi';

	/**
	 * Index type for numeric values which should be accessible via a range index.
	 * The return value of ContextIndexer::getIndexField() will be interpreted as a float.
	 */
	const NUMBER_INDEX = 'number';

	/**
	 * Index type for date/time values which should be accessible via a range index.
	 * The return value of ContextIndexer::getIndexField() will be interpreted as a
	 * data as understood by wfTimestamp().
	 */
	const TIMESTAMP_INDEX = 'timestamp';

	/**
	 * Index type for geo-coordinates which should be accessible via a geo-spacial index.
	 * The return value of ContextIndexer::getIndexField() will be interpreted as a geo-coordinates.
	 */
	const GEO_INDEX = 'geo';

	/**
	 * Use for type-ahead suggestions ("prefix search").
	 * Implies DEFAULT_USAGE.
	 */
	const TYPEAHEAD_USAGE = 'typeahead';

	/**
	 * Use for default search (Special:Search)
	 */
	const DEFAULT_USAGE = 'default';

	/**
	 * Use optionally (checkbox on Special:Search, special syntax in search box)
	 */
	const OPTIONAL_USAGE = 'default';

	/**
	 * Use internally only, do not expose to users.
	 * @note No guarantees are given about keeping values secret.
	 */
	const INTERNAL_USAGE = 'internal';

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string Use the XXX_INDEX constants
	 */
	private $indexType;

	/**
	 * @var float
	 */
	private $boost;

	/**
	 * @var string use the XXX_USAGE constants.
	 */
	private $usageLevel;

	/**
	 * @param string $name
	 * @param string $indexType use the XXX_INDEX constants
	 * @param string $usageLevel use the XXX_USAGE constants; this indicates a preference,
	 *        search engines are free to ignore the provided usage level.
	 * @param float $boost score boost
	 */
	function __construct( $name, $indexType, $usageLevel = self::DEFAULT_USAGE, $boost = 1.0 ) {
		if ( !is_string( $name ) ) {
			throw new InvalidArgumentException( '$name must be a string' );
		}

		if ( !is_string( $indexType ) ) {
			throw new InvalidArgumentException( '$indexType must be a string' );
		}

		if ( !is_int( $boost ) && !is_float( $boost ) ) {
			throw new InvalidArgumentException( '$boost must be an int or float' );
		}

		if ( !is_string( $usageLevel ) ) {
			throw new InvalidArgumentException( '$usageLevel must be a string' );
		}

		$this->name = $name;
		$this->indexType = $indexType;
		$this->boost = $boost;
		$this->usageLevel = $usageLevel;
	}

	/**
	 * Returns the desired index type for the field.
	 *
	 * @return string on of the XXX_INDEX constants.
	 */
	public function getIndexType() {
		return $this->indexType;
	}

	/**
	 * Returns the programmatic name of the field, for use with ContentIndexer::getIndexField().
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns the intended level of usage of the field.
	 *
	 * @return string one of the XXX_USAGE constants.
	 */
	public function getUsageLevel() {
		return $this->usageLevel;
	}

	/**
	 * Returns the desired rank boost of the field. The default boost is 1.0 (that is, no boost).
	 * @return float
	 */
	public function getBoost() {
		return $this->boost;
	}

}
