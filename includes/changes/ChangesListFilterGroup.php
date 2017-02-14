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

// TODO: Might want to make a super-class to share behavior between ChangesListFilter and ChangesListFilterGroup.
// What to call it.  FilterStructure?  That would also let me make
// setUnidirectionalConflict protected.
/**
 * Represents a filter group (used on ChangesListSpecialPage and descendants)
 *
 * @since 1.29
 */
class ChangesListFilterGroup {
	/**
	 * If the group is active, any unchecked filters will
	 * translate to hide parameters in the URL.  E.g. if 'Human (not bot)' is checked,
	 * but 'Bot' is unchecked, hidebots=1 will be sent.
	 */
	const SEND_UNSELECTED_IF_ANY = 'send_unselected_if_any';

	/**
	 * The names of the group's selected filters are sent as string
	 * values separated by a delimiter (the only delimiter currently supported is ',').
	 *
	 * For example, param=opt1,opt2.  If all options are
	 * selected they are replaced by the term "all".  The parameter name is the
	 * group name.
	 */
	const STRING_OPTIONS = 'string_options';

	/**
	 * Delimiter for STRING_OPTIONS groups
	 */
	const SEPARATOR = ';';

	/**
	 * For STRING_OPTIONS groups, signifies that all options in the group are selected.
	 */
	const ALL = 'all';

	/**
	 * For STRING_OPTIONS groups, signifies that no options in the group are selected,
	 * meaning the group has no effect.
	 *
	 * For full-coverage groups, this is the same as ALL if all filters are allowed.
	 * For others, it is not.
	 */
	const NONE = '';

	/**
	 * Name (internal identifier)
	 *
	 * @var string $name
	 */
	protected $name;

	/**
	 * i18n key for title
	 *
	 * @var string $title
	 */
	protected $title;

	/**
	 * Type, either SEND_UNSELECTED_IF_ANY or STRING_OPTIONS
	 *
	 * @var string $type
	 */
	protected $type;

	/**
	 * Priority integer.  Higher means higher in the
	 * group list.
	 *
	 * @var string $priority
	 */
	protected $priority;

	/**
	 * Associative array of filters, as ChangesListFilter objects, with filter name as key
	 *
	 * @var array $filters
	 */
	protected $filters;

	/**
	 * Default, used only if this is part of a
	 *  ChangesListFilterGroup::STRING_OPTIONS group; otherwise, null and unused.
	 *
	 * @var string $defaultValue
	 */
	protected $defaultValue;

	/**
	 * Whether this group is full coverage.  This means that checking every item in the
	 * group means no changes list (e.g. RecentChanges) entries are filtered out.
	 *
	 * @var bool $isFullCoverage
	 */
	protected $isFullCoverage;

	/**
	 * Callable used to do the actual query modification; see constructor
	 *
	 * @var callable $queryCallable
	 */
	protected $queryCallable;

	/**
	 * List of conflicting groups
	 *
	 * @var array $conflictingGroups Array of associative arrays with conflict
	 *   information.  See setUnidirectionalConflict
	 */
	protected $conflictingGroups = [];

	/**
	 * List of conflicting filters
	 *
	 * @var array $conflictingFilters Array of associative arrays with conflict
	 *   information.  See setUnidirectionalConflict
	 */
	protected $conflictingFilters = [];

	/**
	 * Create a new filter group with the specified configuration
	 *
	 * @param array $groupDefinition Configuration of group
	 * * $groupDefinition['name'] string Group name
	 * * $groupDefinition['title'] string i18n key for title (optional, can be omitted
	 * *  only if none of the filters in the group display in the structured UI)
	 * * $groupDefinition['type'] string One of two types, ChangesListFilterGroup::SEND_UNSELECTED_IF_ANY
	 * *  or ChangesListFilterGroup::STRING_OPTIONS
	 * * $groupDefinition['priority'] int Priority integer.  Higher means higher in the
	 * *  group list.
	 * * $groupDefinition['filters'] array Numeric array of filter definitions, each of which
	 * *  is an associative array to be passed to the ChangesListFilter constructor.  However,
	 * *  'priority' is optional for the filters.  Any filter that has priority unset
	 * *  will be put to the bottom, in the order given.
	 * * $groupDefinition['default'] string Default for group.  This is required if and only
	 * *  if 'type' is STRING_OPTIONS (optional)
	 * * $groupDefinition['isFullCoverage'] bool Whether the group is full coverage;
	 * *  if true, this means that checking every item in the group means no
	 * *  changes list entries are filtered out.  This is required if and only if the
	 * *  type is STRING_OPTIONS
	 *
	 * $filterDefinition['queryCallable'] callable Callable accepting parameters:
	 *  string $specialPageClassName Class name of current special page
	 *  IContextSource $context Context, for e.g. user
	 *  IDatabase $dbr Database, for addQuotes, makeList, and similar
	 *  array &$tables Array of tables; see IDatabase::select $table
	 *  array &$fields Array of fields; see IDatabase::select $vars
	 *  array &$conds Array of conditions; see IDatabase::select $conds
	 *  array &$query_options Array of query options; see IDatabase::select $options
	 *  array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 *  array $selectedValues The allowed and requested values, lower-cased and sorted
	 */
	public function __construct( array $groupDefinition ) {
		$this->name = $groupDefinition['name'];
		if ( isset( $groupDefinition['title'] ) ) {
			$this->title = $groupDefinition['title'];
		}
		$this->type = $groupDefinition['type'];
		$this->priority = $groupDefinition['priority'];

		if ( isset( $groupDefinition['default'] ) ) {
			$this->setDefault( $groupDefinition['default'] );
		}

		if ( isset( $groupDefinition['isFullCoverage'] ) ) {
			if ( $this->isPerGroupRequestParameter() ) {
				$this->isFullCoverage = $groupDefinition['isFullCoverage'];
			} else {
				// Logic error
				throw new MWException( 'You can only specify isFullCoverage on a STRING_OPTIONS group' );
			}
		} else {
			// Always true for SEND_UNSELECTED_IF_ANY
			$this->isFullCoverage = true;
		}

		if ( isset( $groupDefinition['queryCallable'] ) ) {
			if ( $this->isPerGroupRequestParameter() ) {
				$this->queryCallable = $groupDefinition['queryCallable'];
			} else {
				// Logic error
				throw new MWException( 'You can only specify a query on a STRING_OPTIONS group.  Otherwise, specify it on the ChangesListFilter' );
			}
		}

		$this->filters = [];
		$lowestSpecifiedPriority = -1;
		foreach ( $groupDefinition['filters'] as $filterDefinition ) {
			if ( isset( $filterDefinition['priority'] ) ) {
				$lowestSpecifiedPriority = min( $lowestSpecifiedPriority, $filterDefinition['priority'] );
			}
		}

		// Convenience feature: If you specify a group (and its filters) all in
		// one place, you don't have to specify priority.  You can just put them
		// in order.  However, if you later add one (e.g. an extension adds a filter
		// to a core-defined group), you need to specify it.
		$autoFillPriority = $lowestSpecifiedPriority - 1;
		foreach ( $groupDefinition['filters'] as $filterDefinition ) {
			if ( !isset( $filterDefinition['priority'] ) ) {
				$filterDefinition['priority'] = $autoFillPriority;
				$autoFillPriority--;
			}

			$this->registerFilter( new ChangesListFilter( $filterDefinition ) );
		}
	}

	/**
	 * Registers a filter in this group
	 *
	 * @param string $groupName Group name
	 * @param ChangesListFilter $filter ChangesListFilter
	 */
	public function registerFilter( ChangesListFilter $filter ) {
		$this->filters[$filter->getName()] = $filter;
	}

	/**
	 * Marks that the given ChangesListFilterGroup or ChangesListFilter conflicts with this object.
	 *
	 * WARNING: This means there is a conflict when they are both things *shown*
	 * (not filtered out), even for the hide-based filters.  So e.g. conflicting with
	 * 'hideanons' means there is a conflict if only anonymous users are *shown*.
	 *
	 * @param ChangesListFilterGroup|ChangesListFilter $other Other ChangesListFilterGroup or ChangesListFilter
	 * @param string $globalKey i18n key for top-level conflict message
	 * @param string $forwardKey i18n key for conflict message in this
	 *  direction (when in UI context of $this object)
	 * @param string $backwardKey i18n key for conflict message in reverse
	 *  direction (when in UI context of $other object)
	 */
	public function conflictsWith( $other, $globalKey, $forwardKey,
		$backwardKey ) {

		if ( $globalKey === null || $forwardKey === null ||
			$backwardKey === null ) {

			throw new MWException( 'All messages must be specified' );
		}

		$this->setUnidirectionalConflict(
			$other,
			$globalKey,
			$forwardKey
		);

		$other->setUnidirectionalConflict(
			$this,
			$globalKey,
			$backwardKey
		);
	}

	/**
	 * Marks that the given ChangesListFilterGroup or ChangesListFilter conflicts with this object.
	 * Internal use ONLY.
	 *
	 * @param ChangesListFilterGroup|ChangesListFilter $other Other ChangesListFilterGroup or ChangesListFilter
	 * @param string $globalDescription i18n key for top-level conflict message
	 * @param string $contextDescription i18n key for conflict message in this
	 *  direction (when in UI context of $this object)
	 */
	public function setUnidirectionalConflict( $other, $globalDescription,
		$contextDescription ) {

		if ( $other instanceof ChangesListFilterGroup ) {
			$this->conflictingGroups[] = [
				'group' => $other->getName(),
				'globalDescription' => $globalDescription,
				'contextDescription' => $contextDescription,
			];
		} else if ( $other instanceof ChangesListFilter ) {
			$this->conflictingFilters[] = [
				'filter' => $other->getName(),
				'globalDescription' => $globalDescription,
				'contextDescription' => $contextDescription,
			];
		} else {
			throw new MWException( 'You can only pass in a ChangesListFilterGroup or a ChangesListFilter' );
		}
	}

	/**
	 * @return string Internal name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string i18n key for title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return string Type, either SEND_UNSELECTED_IF_ANY or STRING_OPTIONS
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return int Priority.  Higher means higher in the group list
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * @return array Associative array of ChangesListFilter objects, with filter name as key
	 */
	public function getFilters() {
		return $this->filters;
	}

	/**
	 * Get filter by name
	 *
	 * @param string $name Filter name
	 * @return ChangesListFilter Specified filter
	 */
	public function getFilter( $name ) {
		return $this->filters[$name];
	}

	/**
	 * Check whether the URL parameter is for the group, or for individual filters.
	 * Defaults can also be defined on the group if and only if this is true.
	 *
	 * @return bool True if and only if the URL parameter is per-group
	 */
	public function isPerGroupRequestParameter() {
		return $this->type === self::STRING_OPTIONS;
	}

	/**
	 * Gets default of filter group
	 *
	 * This can only be called if the URL parameter is per-group.
	 *
	 * @return string $defaultValue
	 */
	public function getDefault() {
		if ( $this->isPerGroupRequestParameter() ) {
			return $this->defaultValue;
		} else {
			// Logic error
			throw new MWException( 'You can only get a default on a STRING_OPTIONS group' );
		}
	}

	/**
	 * Sets default of filter group.
	 *
	 * This can only be called if the URL parameter is per-group.
	 *
	 * @param string $defaultValue
	 */
	public function setDefault( $defaultValue ) {
		if ( $this->isPerGroupRequestParameter() ) {
			$this->defaultValue = $defaultValue;
		} else {
			// Logic error
			throw new MWException( 'You can only specify a default on a string_options group' );
		}
	}

	/**
	 * Modifies the query to include the filter group.  This is only called for
	 * isPerGroupRequestParameter() true groups.  Otherwise, it is called on the ChangesListFilter.
	 *
	 * It is also only called if the filter group is in effect.  For STRING_OPTIONS
	 * groups, this means that the value is non-empty (taking into account the default).
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
	public function modifyQuery( IDatabase $dbr, ChangesListSpecialPage $specialPage, &$tables, &$fields, &$conds,
		&$query_options, &$join_conds, $value ) {

		$allowedFilterNames = [];
		foreach ( $this->filters as $filter ) {
			if ( $filter->isAllowed( $specialPage ) ) {
				$allowedFilterNames[] = $filter->getName();
			}
		}

		if ( $value === self::ALL ) {
			$selectedValues = $allowedFilterNames;
		} else {
			$selectedValues = explode( self::SEPARATOR, strtolower( $value ) );

			// remove values that are not recognized
			$selectedValues = array_intersect(
				$selectedValues,
				$allowedFilterNames
			);
		}

		// If there are now no values, because all are disallowed or invalid,
		// this is a no-op.  This instance method shouldn't be called at all if the
		// original URL parameter is '', but that case is handled here too.
		//
		// If everything is unchecked, the group always has no effect, regardless
		// of full-coverage.
		if ( count( $selectedValues ) === 0 ) {
			return;
		}

		sort( $selectedValues );

		call_user_func_array(
			$this->queryCallable,
			[
				get_class( $specialPage ), $specialPage->getContext(), &$dbr, $tables, &$fields, &$conds, &$query_options, &$join_conds, $selectedValues
			]
		);
	}

	/**
	 * Gets the JS data in the format required by the front-end of the structured UI
	 *
	 * @param ChangesListSpecialPage $specialPage
	 * @return array|null Associative array, or null if there are no filters that
	 *  display in the structured UI.  messageKeys is a special top-level value, with
	 *  the value being an array of the message keys to send to the client.
	 */
	public function getJsData( ChangesListSpecialPage $specialPage ) {
		$output = [
			'name' => $this->name,
			'type' => $this->type,
			'fullCoverage' => $this->isFullCoverage,
			'filters' => [],
			'priority' => $this->priority,
			'conflicts' => [],
			'messageKeys' => [ $this->title ]
		];

		if ( $this->type === self::STRING_OPTIONS ) {
			$output['separator'] = self::SEPARATOR;
			$output['default'] = $this->getDefault();
		}

		usort( $this->filters, function ( $a, $b ) {
			return $b->getPriority() - $a->getPriority();
		} );

		foreach ( $this->filters as $filterName => $filter ) {
			if ( $filter->displaysOnStructuredUi( $specialPage ) ) {
				$filterData = $filter->getJsData();
				$output['messageKeys'] = array_merge(
					$output['messageKeys'],
					$filterData['messageKeys']
				);
				unset( $filterData['messageKeys'] );
				$output['filters'][] = $filterData;
			}
		}

		if ( count( $output['filters'] ) === 0 ) {
			return null;
		}

		$output['title'] = $this->title;

		$conflicts = array_merge(
			$this->conflictingGroups,
			$this->conflictingFilters
		);

		foreach ( $conflicts as $conflictInfo ) {
			$output['conflicts'][] = $conflictInfo;
			array_push(
				$output['messageKeys'],
				$conflictInfo['globalDescription'],
				$conflictInfo['contextDescription']
			);
		}

		return $output;
	}
}
