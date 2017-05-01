<?php
/**
 * Represents a filter group (used on ChangesListSpecialPage and descendants)
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
 * @license GPL 2+
 * @author Matthew Flaschen
 */

use Wikimedia\Rdbms\IDatabase;

/**
 * Represents a filter group with multiple string options. They are passed to the server as
 * a single form parameter separated by a delimiter.  The parameter name is the
 * group name.  E.g. groupname=opt1;opt2 .
 *
 * If all options are selected they are replaced by the term "all".
 *
 * There is also a single DB query modification for the whole group.
 *
 * @since 1.29
 */

class ChangesListStringOptionsFilterGroup extends ChangesListFilterGroup {
	/**
	 * Type marker, used by JavaScript
	 */
	const TYPE = 'string_options';

	/**
	 * Delimiter
	 */
	const SEPARATOR = ';';

	/**
	 * Signifies that all options in the group are selected.
	 */
	const ALL = 'all';

	/**
	 * Signifies that no options in the group are selected, meaning the group has no effect.
	 *
	 * For full-coverage groups, this is the same as ALL if all filters are allowed.
	 * For others, it is not.
	 */
	const NONE = '';

	/**
	 * Group name; used as form parameter.
	 *
	 * @var string $name
	 */

	/**
	 * Defaul parameter value
	 *
	 * @var string $defaultValue
	 */
	protected $defaultValue;

	/**
	 * Callable used to do the actual query modification; see constructor
	 *
	 * @var callable $queryCallable
	 */
	protected $queryCallable;

	/**
	 * Create a new filter group with the specified configuration
	 *
	 * @param array $groupDefinition Configuration of group
	 * * $groupDefinition['name'] string Group name
	 * * $groupDefinition['title'] string i18n key for title (optional, can be omitted
	 * *  only if none of the filters in the group display in the structured UI)
	 * * $groupDefinition['priority'] int Priority integer.  Higher means higher in the
	 * *  group list.
	 * * $groupDefinition['filters'] array Numeric array of filter definitions, each of which
	 * *  is an associative array to be passed to the filter constructor.  However,
	 * *  'priority' is optional for the filters.  Any filter that has priority unset
	 * *  will be put to the bottom, in the order given.
	 * * $groupDefinition['default'] string Default for group.
	 * * $groupDefinition['isFullCoverage'] bool Whether the group is full coverage;
	 * *  if true, this means that checking every item in the group means no
	 * *  changes list entries are filtered out.
	 * * $groupDefinition['queryCallable'] callable Callable accepting parameters:
	 * *  string $specialPageClassName Class name of current special page
	 * *  IContextSource $context Context, for e.g. user
	 * *  IDatabase $dbr Database, for addQuotes, makeList, and similar
	 * *  array &$tables Array of tables; see IDatabase::select $table
	 * *  array &$fields Array of fields; see IDatabase::select $vars
	 * *  array &$conds Array of conditions; see IDatabase::select $conds
	 * *  array &$query_options Array of query options; see IDatabase::select $options
	 * *  array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 * *  array $selectedValues The allowed and requested values, lower-cased and sorted
	 */
	public function __construct( array $groupDefinition ) {
		if ( !isset( $groupDefinition['isFullCoverage'] ) ) {
			throw new MWException( 'You must specify isFullCoverage' );
		}

		$groupDefinition['type'] = self::TYPE;

		parent::__construct( $groupDefinition );

		$this->queryCallable = $groupDefinition['queryCallable'];

		if ( isset( $groupDefinition['default'] ) ) {
			$this->setDefault( $groupDefinition['default'] );
		} else {
			throw new MWException( 'You must specify a default' );
		}
	}

	/**
	 * @inheritdoc
	 */
	public function isPerGroupRequestParameter() {
		return true;
	}

	/**
	 * Sets default of filter group.
	 *
	 * @param string $defaultValue
	 */
	public function setDefault( $defaultValue ) {
		$this->defaultValue = $defaultValue;
	}

	/**
	 * Gets default of filter group
	 *
	 * @return string $defaultValue
	 */
	public function getDefault() {
		return $this->defaultValue;
	}

	/**
	 * @inheritdoc
	 */
	protected function createFilter( array $filterDefinition ) {
		return new ChangesListStringOptionsFilter( $filterDefinition );
	}

	/**
	 * Registers a filter in this group
	 *
	 * @param ChangesListStringOptionsFilter $filter ChangesListStringOptionsFilter
	 */
	public function registerFilter( ChangesListStringOptionsFilter $filter ) {
		$this->filters[$filter->getName()] = $filter;
	}

	/**
	 * Modifies the query to include the filter group.
	 *
	 * The modification is only done if the filter group is in effect.  This means that
	 * one or more valid and allowed filters were selected.
	 *
	 * @param IDatabase $dbr Database, for addQuotes, makeList, and similar
	 * @param ChangesListSpecialPage $specialPage Current special page
	 * @param array &$tables Array of tables; see IDatabase::select $table
	 * @param array &$fields Array of fields; see IDatabase::select $vars
	 * @param array &$conds Array of conditions; see IDatabase::select $conds
	 * @param array &$query_options Array of query options; see IDatabase::select $options
	 * @param array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 * @param string $value URL parameter value
	 */
	public function modifyQuery( IDatabase $dbr, ChangesListSpecialPage $specialPage,
		&$tables, &$fields, &$conds, &$query_options, &$join_conds, $value ) {

		$allowedFilterNames = [];
		foreach ( $this->filters as $filter ) {
			$allowedFilterNames[] = $filter->getName();
		}

		if ( $value === self::ALL ) {
			$selectedValues = $allowedFilterNames;
		} else {
			$selectedValues = explode( self::SEPARATOR, strtolower( $value ) );

			// remove values that are not recognized or not currently allowed
			$selectedValues = array_intersect(
				$selectedValues,
				$allowedFilterNames
			);
		}

		// If there are now no values, because all are disallowed or invalid (also,
		// the user may not have selected any), this is a no-op.

		// If everything is unchecked, the group always has no effect, regardless
		// of full-coverage.
		if ( count( $selectedValues ) === 0 ) {
			return;
		}

		sort( $selectedValues );

		call_user_func_array(
			$this->queryCallable,
			[
				get_class( $specialPage ),
				$specialPage->getContext(),
				$dbr,
				&$tables,
				&$fields,
				&$conds,
				&$query_options,
				&$join_conds,
				$selectedValues
			]
		);
	}

	/**
	 * @inheritdoc
	 */
	public function getJsData() {
		$output = parent::getJsData();

		$output['separator'] = self::SEPARATOR;
		$output['default'] = $this->getDefault();

		return $output;
	}
}
