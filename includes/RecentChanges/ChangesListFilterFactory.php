<?php

namespace MediaWiki\RecentChanges;

use InvalidArgumentException;
use UnexpectedValueException;

class ChangesListFilterFactory {
	private ?string $showHidePrefix;

	public function __construct( private array $config ) {
		$this->showHidePrefix = $config['showHidePrefix'] ?? null;
	}

	/**
	 * Register filters from an array of group definitions
	 *
	 *  Groups are displayed to the user in the structured UI.  However, if necessary,
	 *  all of the filters in a group can be configured to only display on the
	 *  unstructured UI, in which case you don't need a group title.
	 *
	 *  The order of both groups and filters is significant; first is top-most priority,
	 *  descending from there.
	 *
	 * @param ChangesListFilterGroupContainer $container
	 * @param array $definitions An array of associative arrays each consisting
	 *   of parameters passed to the group class, and also the following
	 *   parameters recognised by the factory:
	 *     - class: The name of the group class
	 *     - requireConfig: An associative array mapping a required config name
	 *       to its value. If a configuration item with that name and value was
	 *       not passed to the factory constructor, the group will not be
	 *       registered.
	 *     - filters: The following filter properties are recognised, in
	 *       addition to those used by the filter class constructor:
	 *         - requireConfig: As for the group
	 *         - subsets: string[] A list of filter names in the same group
	 *           which are subsets of this filter.
	 *         - conflictsWith: An associative array mapping the group name to
	 *           an associative array mapping the conflicting filter name to an
	 *           associative array of conflict options which may include
	 *           "globalKey", "forwardKey" and "backwardKey", which are passed
	 *           to ChangesListFilter::conflictsWith().
	 *         - conflictOptions: Defaults for "globalKey", "forwardKey" and
	 *           "backwardKey" in conflictsWith.
	 *         - showHideSuffix: This is prefixed with the factory config option
	 *           "showHidePrefix" and then passed to the filter as "showHide".
	 * * @phan-param array<int,array{class:class-string<ChangesListFilterGroup>,filters:array}> $definitions
	 */
	public function registerFiltersFromDefinitions(
		ChangesListFilterGroupContainer $container,
		array $definitions
	) {
		foreach ( $definitions as $groupDefinition ) {
			if ( !$this->isConfigSatisfied( $groupDefinition ) ) {
				continue;
			}
			$this->transformGroupDefinition( $container, $groupDefinition );
			$group = $this->createGroup( $groupDefinition );
			$this->registerSupersets( $group, $groupDefinition );
			$container->registerGroup( $group );
			$this->registerConflicts( $container, $group, $groupDefinition );
			$this->handlePendingConflicts( $container, $group );
		}
	}

	/**
	 * Check whether any "requireConfig" values in the filter or group definition
	 * are satisfied by the currently defined config. If this returns false, the
	 * filter or group will not be registered.
	 *
	 * @param array $def Group or filter definition
	 * @return bool
	 */
	private function isConfigSatisfied( array $def ) {
		foreach ( $def['requireConfig'] ?? [] as $name => $value ) {
			if ( !array_key_exists( $name, $this->config ) || $this->config[$name] !== $value ) {
				return false;
			}
		}
		return true;
	}

	private function transformGroupDefinition(
		ChangesListFilterGroupContainer $container,
		array &$groupDefinition
	) {
		$groupDefinition['priority'] =
			$container->fillPriority( $groupDefinition['priority'] ?? null );

		$filterDefs = [];
		foreach ( $groupDefinition['filters'] as $def ) {
			if ( !$this->isConfigSatisfied( $def ) ) {
				continue;
			}
			$def = $this->transformFilterDefinition( $def );
			$filterDefs[] = $def;
		}
		$groupDefinition['filters'] = $filterDefs;
	}

	/**
	 * Transforms filter definition to prepare it for constructor.
	 *
	 * See overrides of this method as well.
	 *
	 * @param array $filterDefinition Original filter definition
	 *
	 * @return array Transformed definition
	 */
	private function transformFilterDefinition( array $filterDefinition ) {
		if ( $this->showHidePrefix !== null && isset( $filterDefinition['showHideSuffix'] ) ) {
			$filterDefinition['showHide'] = $this->showHidePrefix . $filterDefinition['showHideSuffix'];
		}

		return $filterDefinition;
	}

	private function createGroup( array $groupDefinition ): ChangesListFilterGroup {
		$className = $groupDefinition['class'];
		unset( $groupDefinition['class'] );

		$group = new $className( $groupDefinition );
		if ( !( $group instanceof ChangesListFilterGroup ) ) {
			throw new UnexpectedValueException(
				"$className was expected to be an instance of ChangesListFilterGroup" );
		}
		return $group;
	}

	/**
	 * If any filters in this new group have subsets, register the subset.
	 * Subsets must be in the same group.
	 *
	 * @param ChangesListFilterGroup $group
	 * @param array $groupDefinition
	 */
	private function registerSupersets( ChangesListFilterGroup $group, array $groupDefinition ) {
		foreach ( $groupDefinition['filters'] as $def ) {
			foreach ( $def['subsets'] ?? [] as $subsetName ) {
				$filter = $group->getFilter( $def['name'] );
				$subset = $group->getFilter( $subsetName );
				if ( $filter && $subset ) {
					$filter->setAsSupersetOf( $subset );
				}
			}
		}
	}

	/**
	 * Find filters with "conflictsWith" in their group definition. If the
	 * filter it refers to exists, register a conflict. Otherwise, register
	 * a pending conflict.
	 *
	 * @param ChangesListFilterGroupContainer $container
	 * @param ChangesListFilterGroup $group
	 * @param array $groupDefinition
	 */
	private function registerConflicts(
		ChangesListFilterGroupContainer $container,
		ChangesListFilterGroup $group,
		array $groupDefinition
	) {
		foreach ( $groupDefinition['filters'] as $def ) {
			$filter = $group->getFilter( $def['name'] );
			foreach ( $def['conflictsWith'] ?? [] as $conflictingGroupName => $conflictingFilters ) {
				foreach ( $conflictingFilters as $conflictingFilterName => $opts ) {
					'@phan-var array $opts';
					$opts += $def['conflictOptions'] ?? [];
					$missing = array_diff(
						[ 'globalKey', 'forwardKey', 'backwardKey' ],
						array_keys( $opts )
					);
					if ( $missing ) {
						throw new InvalidArgumentException(
							"The conflict option(s) " . implode( ', ', $missing ) .
							" must be present in either conflictsWith or conflictOptions in " .
							"the definition of filter {$group->getName()}/{$def['name']}"
						);
					}
					$conflictingGroup = $container->getGroup( $conflictingGroupName );
					$conflictingFilter = $conflictingGroup?->getFilter( $conflictingFilterName );
					if ( $conflictingFilter ) {
						$filter->conflictsWith(
							$conflictingFilter,
							$opts['globalKey'],
							$opts['forwardKey'],
							$opts['backwardKey']
						);
					} else {
						$container->addPendingConflict(
							$filter,
							$conflictingGroupName,
							$conflictingFilterName,
							$opts
						);
					}
				}
			}
		}
	}

	/**
	 * Check whether there were any pending conflicts registered with this
	 * new group as the target. If so, remove them from the pending list and
	 * register the conflict.
	 *
	 * @param ChangesListFilterGroupContainer $container
	 * @param ChangesListFilterGroup $group
	 */
	private function handlePendingConflicts(
		ChangesListFilterGroupContainer $container,
		ChangesListFilterGroup $group
	) {
		foreach ( $group->getFilters() as $filter ) {
			$conflicts = $container->popPendingConflicts( $group, $filter );
			foreach ( $conflicts as [ $sourceFilter, $opts ] ) {
				$sourceFilter->conflictsWith(
					$filter,
					$opts['globalKey'],
					$opts['forwardKey'],
					$opts['backwardKey']
				);
			}
		}
	}
}
