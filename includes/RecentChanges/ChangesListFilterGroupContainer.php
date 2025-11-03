<?php

namespace MediaWiki\RecentChanges;

use ArrayIterator;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\FormOptions;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery;
use MediaWiki\SpecialPage\ChangesListSpecialPage;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * A container holding changes list filter groups. Helps ChangesListSpecialPage
 * to iterate over all groups. Provides strongly typed accessors.
 *
 * @internal
 */
class ChangesListFilterGroupContainer implements \IteratorAggregate {
	/**
	 * Filter groups, and their contained filters
	 * This is an associative array (with group name as key) of ChangesListFilterGroup objects.
	 *
	 * @var ChangesListFilterGroup[]
	 */
	private $filterGroups = [];

	/**
	 * Pending conflicts indexed by group name and filter name
	 *
	 * @var array<string,array<string,array<int,array{0:ChangesListFilter,1:array}>>>
	 */
	private $pendingConflicts = [];

	/**
	 * The priority of the last inserted group
	 *
	 * @var int
	 */
	private $autoPriority = 0;

	/**
	 * Iterate over defined filter groups.
	 *
	 * This is mostly for b/c with the FetchChangesList hook. When core needs
	 * to iterate over filter groups, there are usually specific wrapper
	 * functions.
	 *
	 * @return ArrayIterator<ChangesListFilterGroup>
	 */
	public function getIterator(): ArrayIterator {
		return new ArrayIterator( $this->filterGroups );
	}

	/**
	 * Get the filter groups as an associative array. This can be removed when
	 * ChangesListSpecialPage::getFilterGroups() is removed.
	 * @return ChangesListFilterGroup[]
	 */
	public function toArray(): array {
		return $this->filterGroups;
	}

	/**
	 * Gets a specified ChangesListFilterGroup by name
	 *
	 * @param string $name Name of group
	 * @return ChangesListFilterGroup|null Group, or null if not registered
	 */
	public function getGroup( $name ) {
		return $this->filterGroups[$name] ?? null;
	}

	/**
	 * Check if a group with a specific name is registered
	 *
	 * @param string $name
	 * @return bool
	 */
	public function hasGroup( string $name ): bool {
		return isset( $this->filterGroups[$name] );
	}

	/**
	 * Register a structured changes list filter group
	 */
	public function registerGroup( ChangesListFilterGroup $group ) {
		$groupName = $group->getName();

		$this->filterGroups[$groupName] = $group;
	}

	/**
	 * Register a conflict where the source exists but the destination doesn't
	 * exist yet.
	 *
	 * @param ChangesListFilter $sourceFilter
	 * @param string $conflictingGroupName
	 * @param string $conflictingFilterName
	 * @param array $opts
	 */
	public function addPendingConflict(
		$sourceFilter,
		$conflictingGroupName,
		$conflictingFilterName,
		$opts
	) {
		$this->pendingConflicts[$conflictingGroupName][$conflictingFilterName][]
			= [ $sourceFilter, $opts ];
	}

	/**
	 * Get any pending conflicts for the specified filter which is in the
	 * specified group, and remove the conflicts from the container.
	 *
	 * @param ChangesListFilterGroup $group
	 * @param ChangesListFilter $filter
	 * @return iterable<array{0:ChangesListFilter,1:array}>
	 */
	public function popPendingConflicts(
		ChangesListFilterGroup $group,
		ChangesListFilter $filter
	) {
		$groupName = $group->getName();
		$filterName = $filter->getName();
		$conflicts = $this->pendingConflicts[$groupName][$filterName] ?? [];
		if ( $conflicts ) {
			unset( $this->pendingConflicts[$groupName][$filterName] );
		}
		return $conflicts;
	}

	/**
	 * Apply a set of default overrides to the registered filters. Ignore any
	 * filters that don't exist.
	 *
	 * @param array<string,array<string,bool>|string> $defaults The key is the group name.
	 *   For string options groups, the value is a string. For boolean groups,
	 *   the value is an array mapping the filter name to the default value.
	 */
	public function setDefaults( array $defaults ) {
		foreach ( $defaults as $groupName => $groupDefault ) {
			$this->getGroup( $groupName )?->setDefault( $groupDefault );
		}
	}

	/**
	 * If a priority is passed, update the current auto-priority and return the
	 * passed value. If the priority is null, return the next auto-priority value.
	 *
	 * @param ?int $priority
	 * @return int
	 */
	public function fillPriority( ?int $priority ) {
		if ( $priority === null ) {
			return --$this->autoPriority;
		} else {
			$this->autoPriority = $priority;
			return $priority;
		}
	}

	/**
	 * Check if filters are in conflict and guaranteed to return no results.
	 *
	 * @return bool
	 */
	public function areFiltersInConflict( FormOptions $opts ) {
		foreach ( $this->filterGroups as $group ) {
			if ( $group->getConflictingGroups() ) {
				wfLogWarning(
					$group->getName() .
					" specifies conflicts with other groups but these are not supported yet."
				);
			}

			foreach ( $group->getConflictingFilters() as $conflictingFilter ) {
				if ( $conflictingFilter->activelyInConflictWithGroup( $group, $opts ) ) {
					return true;
				}
			}

			foreach ( $group->getFilters() as $filter ) {
				foreach ( $filter->getConflictingFilters() as $conflictingFilter ) {
					if (
						$conflictingFilter->activelyInConflictWithFilter( $filter, $opts ) &&
						$filter->activelyInConflictWithFilter( $conflictingFilter, $opts )
					) {
						return true;
					}
				}

			}

		}
		return false;
	}

	/**
	 * Add all the options represented by registered filter groups to $opts
	 *
	 * @param FormOptions $opts
	 * @param bool $allowDefaults
	 * @param bool $isStructuredFiltersEnabled
	 */
	public function addOptions( FormOptions $opts, $allowDefaults, $isStructuredFiltersEnabled ) {
		foreach ( $this->filterGroups as $filterGroup ) {
			$filterGroup->addOptions( $opts, $allowDefaults, $isStructuredFiltersEnabled );
		}
	}

	/**
	 * Modifies the query according to the current filter groups.
	 *
	 * The modification is only done if the filter group is in effect.  This means that
	 * one or more valid and allowed filters were selected.
	 *
	 * @param IReadableDatabase $dbr Database, for addQuotes, makeList, and similar
	 * @param ChangesListSpecialPage $specialPage Current special page
	 * @param array &$tables Array of tables; see IDatabase::select $table
	 * @param array &$fields Array of fields; see IDatabase::select $vars
	 * @param array &$conds Array of conditions; see IDatabase::select $conds
	 * @param array &$query_options Array of query options; see IDatabase::select $options
	 * @param array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 * @param FormOptions $opts Wrapper for the current request options and their defaults
	 * @param bool $isStructuredFiltersEnabled True if the Structured UI is currently enabled
	 */
	public function modifyLegacyQuery( IReadableDatabase $dbr, ChangesListSpecialPage $specialPage,
		&$tables, &$fields, &$conds, &$query_options, &$join_conds,
		FormOptions $opts, $isStructuredFiltersEnabled
	) {
		foreach ( $this->filterGroups as $filterGroup ) {
			$filterGroup->modifyQuery( $dbr, $specialPage, $tables, $fields, $conds,
				$query_options, $join_conds, $opts, $isStructuredFiltersEnabled );
		}
	}

	/**
	 * Modifies the query according to the current filter groups
	 *
	 * The modification is only done if the filter group is in effect.  This means that
	 * one or more valid and allowed filters were selected.
	 *
	 * @param ChangesListQuery $query
	 * @param FormOptions $opts
	 * @param bool $isStructuredFiltersEnabled
	 */
	public function modifyChangesListQuery(
		ChangesListQuery $query,
		FormOptions $opts,
		$isStructuredFiltersEnabled
	) {
		if ( $this->areFiltersInConflict( $opts ) ) {
			$query->forceEmptySet();
			return;
		}
		foreach ( $this->filterGroups as $filterGroup ) {
			$filterGroup->modifyChangesListQuery( $query, $opts, $isStructuredFiltersEnabled );
		}
	}

	/**
	 * Fix invalid options by resetting pairs that should never appear together.
	 *
	 * @param FormOptions $opts
	 * @return bool True if any option was reset
	 */
	public function fixContradictoryOptions( FormOptions $opts ) {
		$fixed = false;
		foreach ( $this->filterGroups as $filterGroup ) {
			if ( $filterGroup instanceof ChangesListBooleanFilterGroup ) {
				$filters = $filterGroup->getFilters();

				if ( count( $filters ) === 1 ) {
					// legacy boolean filters should not be considered
					continue;
				}

				$allInGroupEnabled = array_reduce(
					$filters,
					static function ( bool $carry, ChangesListBooleanFilter $filter ) use ( $opts ) {
						return $carry && $opts[ $filter->getName() ];
					},
					/* initialValue */ count( $filters ) > 0
				);

				if ( $allInGroupEnabled ) {
					foreach ( $filters as $filter ) {
						$opts[ $filter->getName() ] = false;
					}

					$fixed = true;
				}
			}
		}
		return $fixed;
	}

	/**
	 * Get the boolean filters which are displayed on the unstructured UI, with
	 * the filter name in the key.
	 *
	 * @return array<string,ChangesListBooleanFilter>
	 */
	public function getLegacyShowHideFilters() {
		$filters = [];
		foreach ( $this->filterGroups as $group ) {
			if ( $group instanceof ChangesListBooleanFilterGroup ) {
				foreach ( $group->getFilters() as $key => $filter ) {
					if ( $filter->displaysOnUnstructuredUi() ) {
						$filters[ $key ] = $filter;
					}
				}
			}
		}
		return $filters;
	}

	/**
	 * Gets structured filter information needed by JS
	 *
	 * Currently, this intentionally only includes filters that display
	 * in the structured UI.  This can be changed easily, though, if we want
	 * to include data on filters that use the unstructured UI.  messageKeys is a
	 * special top-level value, with the value being an array of the message keys to
	 * send to the client.
	 *
	 * @return array Associative array
	 *   - array $return['groups'] Group data
	 *   - array $return['messageKeys'] Array of message keys
	 */
	public function getJsData() {
		$output = [
			'groups' => [],
			'messageKeys' => [],
		];

		usort( $this->filterGroups, static function ( ChangesListFilterGroup $a, ChangesListFilterGroup $b ) {
			return $b->getPriority() <=> $a->getPriority();
		} );

		foreach ( $this->filterGroups as $group ) {
			$groupOutput = $group->getJsData();
			if ( $groupOutput !== null ) {
				$output['messageKeys'] = array_merge(
					$output['messageKeys'],
					$groupOutput['messageKeys']
				);

				unset( $groupOutput['messageKeys'] );
				$output['groups'][] = $groupOutput;
			}
		}

		return $output;
	}

	/**
	 * Get the parameters which can be set via the subpage
	 *
	 * @return array<string,string> A map of the parameter name to its type,
	 *   which can be either "bool" or "string".
	 */
	public function getSubpageParams() {
		// URL parameters can be per-group, like 'userExpLevel',
		// or per-filter, like 'hideminor'.
		$params = [];
		foreach ( $this->filterGroups as $filterGroup ) {
			if ( $filterGroup instanceof ChangesListStringOptionsFilterGroup ) {
				$params[$filterGroup->getName()] = 'string';
			} elseif ( $filterGroup instanceof ChangesListBooleanFilterGroup ) {
				foreach ( $filterGroup->getFilters() as $filter ) {
					$params[$filter->getName()] = 'bool';
				}
			}
		}
		return $params;
	}

	/**
	 * Add any necessary CSS classes
	 *
	 * @param IContextSource $ctx Context source
	 * @param RecentChange $rc Recent changes object
	 * @param array &$classes Non-associative array of CSS class names; appended to if needed
	 */
	public function applyCssClassIfNeeded( IContextSource $ctx, RecentChange $rc, array &$classes ) {
		foreach ( $this->filterGroups as $groupName => $filterGroup ) {
			foreach ( $filterGroup->getFilters() as $filterName => $filter ) {
				// New system
				if ( $rc->isHighlighted( "$groupName/$filterName" ) ) {
					$class = $filter->getCssClass();
					if ( $class !== null ) {
						$classes[] = $class;
					}
				}
				// Old system
				$filter->applyCssClassIfNeeded( $ctx, $rc, $classes );
			}
		}
	}

}
