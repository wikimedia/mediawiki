<?php
/**
 * Represents a filter (used on ChangesListSpecialPage and descendants)
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

/**
 * Represents a filter (used on ChangesListSpecialPage and descendants)
 *
 * @since 1.29
 */
class Filter {
	/**
	 * Name.  Used as URL parameters if group is send_unselected_if_any.
	 *
	 * @var string $name
	 */
	protected $name;

	/**
	 * i18n key of label for structured UI
	 *
	 * @var string $label
	 */
	protected $label;

	/**
	 * i18n key of description for structured UI
	 *
	 * @var string $description
	 */
	protected $description;

	// This can sometimes be different on Special:RecentChanges
	// and Special:Watchlist, due to the double-legacy hooks
	// (SpecialRecentChangesFilters and SpecialWatchlistFilters)
	//
	// but there will be separate sets of FilterGroup and Filter instances
	// for those pages (it should work even if they're both loaded
	// at once, but that can't happen).
	/**
	 * Main unstructured UI i18n key
	 *
	 * @var string $showHide
	 */
	protected $showHide;

	/**
	 * Default, used only if this is part of a
	 *  FilterGroup::SEND_UNSELECTED_IF_ANY group
	 *
	 * @var bool $defaultValue
	 */
	protected $defaultValue;

	/**
	 * Callable used to check whether this filter is allowed to take effect
	 *
	 * @var callable $isAllowedCallable
	 */
	protected $isAllowedCallable;

	/**
	 * List of conflicting groups
	 *
	 * @var array $conflictingGroups Array of FilterGroup objects that conflict with
	 *  this
	 */
	protected $conflictingGroups = [];

	/**
	 * List of conflicting filters
	 *
	 * @var array $conflictingFilters Array of Filter objects that conflict with
	 *  this
	 */
	protected $conflictingFilters = [];

	/**
	 * Priority integer.  Higher means higher in the
	 * group's filter list.
	 *
	 * @var string $priority
	 */
	protected $priority;

	/**
	 * Callable used to do the actual query modification; see constructor
	 *
	 * @var callable $queryCallable
	 */
	protected $queryCallable;

	/**
	 * Create a new filter with the specified configuration.
	 *
	 * It infers which UI (it can be either or both) to display the filter on based on
	 * which messages are provided.
	 *
	 * If 'label 'is provided, it will be displayed on the structured UI.  If
	 * 'showHide' is provided, it will be displayed on the unstructured UI.  Thus,
	 * 'label', 'description', and 'showHide' are optional depending on which UI
	 * it's for.
	 *
	 * @param array $filterDefinition Filter definition
	 *
	 * $filterDefinition['name'] string Name.  Used as URL parameters if
	 *  send_unselected_if_any.
	 * $filterDefinition['label'] string i18n key of label for structured UI.
	 * $filterDefinition['description'] string i18n key of description for structured
	 *  UI.
	 * $filterDefinition['showHide'] Main i18n key used for unstructured UI.
	 * $filterDefinition['default'] bool Default, used only if this is part of a
	 *  FilterGroup::SEND_UNSELECTED_IF_ANY group
	 * $filterDefinition['isAllowedCallable'] callable Callable taking a single
	 *  parameter, an IContextSource, and returning true if and only if
	 *  the current user is permitted to use this filter on the current wiki.
	 *  If it returns false, it will both hide the UI (in all UIs) and prevent
	 *  the DB query modification from taking effect. (optional, defaults to allowed)
	 * $filterDefinition['priority'] int Priority integer.  Higher means higher in the
	 *  group's filter list.
	 * $filterDefinition['queryCallable'] callable Callable accepting parameters; see
	 *  modifyQuery (optional, only expected for filters in SEND_UNSELECTED_IF_ANY
	 *  groups.
	 */
	public function __construct( array $filterDefinition ) {
		$this->name = $filterDefinition['name'];
		if ( isset( $filterDefinition['label'] ) ) {
			$this->label = $filterDefinition['label'];
			$this->description = $filterDefinition['description'];
		}

		if ( isset( $filterDefinition['showHide'] ) ) {
			$this->showHide = $filterDefinition['showHide'];
		}

		if ( isset( $filterDefinition['default'] ) ) {
			$this->defaultValue = $filterDefinition['default'];
		}

		if ( isset( $filterDefinition['isAllowedCallable'] ) ) {
			$this->isAllowedCallable = $filterDefinition['isAllowedCallable'];
		}

		$this->priority = $filterDefinition['priority'];

		if ( isset( $filterDefinition['queryCallable'] ) ) {
			$this->queryCallable = $filterDefinition['queryCallable'];
		}
	}

	// TODO: Add i18n messages
	/**
	 * Marks that the given FilterGroup or Filter conflicts with this Filter
	 *
	 * WARNING: This means there is a conflict when they are both things *shown*
	 * (not filtered out), even for the hide-based filters.  So e.g. conflicting with
	 * 'hideanons' means there is a conflict if only anonymous users are *shown*.
	 *
	 * @param FilterGroup|Filter $other Other Filter or a FilterGroup
	 */
	public function conflictsWith( $other ) {
		if ( $other instanceof FilterGroup ) {
			$this->conflictingGroups[] = $other;
		} else if ( $other instanceof Filter ) {
			$this->conflictingFilters[] = $other;
		} else {
			throw new MWException( 'You can only pass in a FilterGroup or a Filter' );
		}
	}

	/**
	 * @return string Name, e.g. hideanons
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string i18n key of label for structured UI
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return string i18n key of description for structured UI
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return string Main i18n key for unstructured UI
	 */
	public function getShowHide() {
		return $this->showHide;
	}

	/**
	 * Checks whether the filter should display on the unstructured UI
	 *
	 * @return bool Whether to display
	 */
	public function displaysOnUnstructuredUi( IContextSource $contextSource ) {
		return $this->showHide &&
			$this->isAllowed( $contextSource );
	}

	/**
	 * Checks whether the filter should display on the unstructured UI
	 *
	 * @return bool Whether to display
	 */
	public function displaysOnStructuredUi( IContextSource $contextSource ) {
		return $this->label !== null && $this->isAllowed( $contextSource );
	}

	/**
	 * @return bool Default value
	 */
	public function getDefault() {
		return $this->defaultValue;
	}

	/**
	 * Sets default
	 *
	 * @param bool Default value
	 */
	public function setDefault( $defaultValue ) {
		$this->defaultValue = $defaultValue;
	}

	/**
	 * @return int Priority.  Higher means higher in the group list
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * Checks whether the filter is allowed for the current context
	 *
	 * @param IContextSource $context The context to check
	 * @return bool Whether it is allowed
	 */
	public function isAllowed( IContextSource $context ) {
		if ( $this->isAllowedCallable === null ) {
			return true;
		} else {
			return call_user_func( $this->isAllowedCallable, $context );
		}
	}

	/**
	 * Modifies the query to include the filter.  This is only called if the filter is
	 * in effect (taking into account the default).  If isPerGroupRequestParameter is
	 * true on the group, modifyQuery will be called on the group instead.
	 *
	 * @param IDatabase $dbr Database, for addQuotes, makeList, and similar
	 * @param IContextSource $context Context, for e.g. user
	 * @param array &$tables Array of tables; see IDatabase::select $table
	 * @param array &$fields Array of fields; see IDatabase::select $vars
	 * @param array &$conds Array of conditions; see IDatabase::select $conds
	 * @param array &$query_options Array of query options; see IDatabase::select $options
	 * @param array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 */
	public function modifyQuery( IDatabase $dbr, IContextSource $context, &$tables, &$fields, &$conds,
		&$query_options, &$join_conds ) {

		return call_user_func_array(
			$this->queryCallable,
			[
				$dbr, $context, &$tables, &$fields, &$conds, &$query_options, &$join_conds
			]
		);
	}

	/**
	 * Gets the JS data in the format required by the front-end of the structured UI
	 *
	 * @param IContextSource $context
	 * @return array Associative array
	 */
	public function getJsData( IContextSource $context ) {
		return [
			'name' => $this->getName(),
			'label' => $context->msg( $this->getLabel() )->text(),
			'description' => $context->msg( $this->getDescription() )->text(),
			'conflicts' => [],
		];

		foreach ( $this->conflictingGroups as $group ) {
			$output['conflicts'][] = [
				'group' => $group->getName(),
			];
		}

		foreach ( $this->conflictingFilters as $filter ) {
			$output['conflicts'][] = [
				'filter' => $filter->getName(),
			];
		}
	}
}
