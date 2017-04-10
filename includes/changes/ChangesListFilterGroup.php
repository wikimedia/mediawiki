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

// TODO: Might want to make a super-class or trait to share behavior (especially re
// conflicts) between ChangesListFilter and ChangesListFilterGroup.
// What to call it.  FilterStructure?  That would also let me make
// setUnidirectionalConflict protected.

/**
 * Represents a filter group (used on ChangesListSpecialPage and descendants)
 *
 * @since 1.29
 */
abstract class ChangesListFilterGroup {
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
	 * i18n key for header of What's This?
	 *
	 * @var string|null $whatsThisHeader
	 */
	protected $whatsThisHeader;

	/**
	 * i18n key for body of What's This?
	 *
	 * @var string|null $whatsThisBody
	 */
	protected $whatsThisBody;

	/**
	 * URL of What's This? link
	 *
	 * @var string|null $whatsThisUrl
	 */
	protected $whatsThisUrl;

	/**
	 * i18n key for What's This? link
	 *
	 * @var string|null $whatsThisLinkText
	 */
	protected $whatsThisLinkText;

	/**
	 * Type, from a TYPE constant of a subclass
	 *
	 * @var string $type
	 */
	protected $type;

	/**
	 * Priority integer.  Higher values means higher up in the
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
	 * Whether this group is full coverage.  This means that checking every item in the
	 * group means no changes list (e.g. RecentChanges) entries are filtered out.
	 *
	 * @var bool $isFullCoverage
	 */
	protected $isFullCoverage;

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

	const DEFAULT_PRIORITY = -100;

	const RESERVED_NAME_CHAR = '_';

	/**
	 * Create a new filter group with the specified configuration
	 *
	 * @param array $groupDefinition Configuration of group
	 * * $groupDefinition['name'] string Group name; use camelCase with no punctuation
	 * * $groupDefinition['title'] string i18n key for title (optional, can be omitted
	 * *  only if none of the filters in the group display in the structured UI)
	 * * $groupDefinition['type'] string A type constant from a subclass of this one
	 * * $groupDefinition['priority'] int Priority integer.  Higher value means higher
	 * *  up in the group list (optional, defaults to -100).
	 * * $groupDefinition['filters'] array Numeric array of filter definitions, each of which
	 * *  is an associative array to be passed to the filter constructor.  However,
	 * *  'priority' is optional for the filters.  Any filter that has priority unset
	 * *  will be put to the bottom, in the order given.
	 * * $groupDefinition['isFullCoverage'] bool Whether the group is full coverage;
	 * *  if true, this means that checking every item in the group means no
	 * *  changes list entries are filtered out.
	 */
	public function __construct( array $groupDefinition ) {
		if ( strpos( $groupDefinition['name'], self::RESERVED_NAME_CHAR ) !== false ) {
			throw new MWException( 'Group names may not contain \'' .
				self::RESERVED_NAME_CHAR .
				'\'.  Use the naming convention: \'camelCase\''
			);
		}

		$this->name = $groupDefinition['name'];

		if ( isset( $groupDefinition['title'] ) ) {
			$this->title = $groupDefinition['title'];
		}

		if ( isset ( $groupDefinition['whatsThisHeader'] ) ) {
			$this->whatsThisHeader = $groupDefinition['whatsThisHeader'];
			$this->whatsThisBody = $groupDefinition['whatsThisBody'];
			$this->whatsThisUrl = $groupDefinition['whatsThisUrl'];
			$this->whatsThisLinkText = $groupDefinition['whatsThisLinkText'];
		}

		$this->type = $groupDefinition['type'];
		if ( isset( $groupDefinition['priority'] ) ) {
			$this->priority = $groupDefinition['priority'];
		} else {
			$this->priority = self::DEFAULT_PRIORITY;
		}

		$this->isFullCoverage = $groupDefinition['isFullCoverage'];

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
			$filterDefinition['group'] = $this;

			$filter = $this->createFilter( $filterDefinition );
			$this->registerFilter( $filter );
		}
	}

	/**
	 * Creates a filter of the appropriate type for this group, from the definition
	 *
	 * @param array $filterDefinition Filter definition
	 * @return ChangesListFilter Filter
	 */
	abstract protected function createFilter( array $filterDefinition );

	/**
	 * Marks that the given ChangesListFilterGroup or ChangesListFilter conflicts with this object.
	 *
	 * WARNING: This means there is a conflict when both things are *shown*
	 * (not filtered out), even for the hide-based filters.  So e.g. conflicting with
	 * 'hideanons' means there is a conflict if only anonymous users are *shown*.
	 *
	 * @param ChangesListFilterGroup|ChangesListFilter $other Other
	 *  ChangesListFilterGroup or ChangesListFilter
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
	 * Marks that the given ChangesListFilterGroup or ChangesListFilter conflicts with
	 * this object.
	 *
	 * Internal use ONLY.
	 *
	 * @param ChangesListFilterGroup|ChangesListFilter $other Other
	 *  ChangesListFilterGroup or ChangesListFilter
	 * @param string $globalDescription i18n key for top-level conflict message
	 * @param string $contextDescription i18n key for conflict message in this
	 *  direction (when in UI context of $this object)
	 */
	public function setUnidirectionalConflict( $other, $globalDescription,
		$contextDescription ) {

		if ( $other instanceof ChangesListFilterGroup ) {
			$this->conflictingGroups[] = [
				'group' => $other->getName(),
				'groupObject' => $other,
				'globalDescription' => $globalDescription,
				'contextDescription' => $contextDescription,
			];
		} elseif ( $other instanceof ChangesListFilter ) {
			$this->conflictingFilters[] = [
				'group' => $other->getGroup()->getName(),
				'filter' => $other->getName(),
				'filterObject' => $other,
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
	 * @return string Type (TYPE constant from a subclass)
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
	 * @return ChangesListFilter|null Specified filter, or null if it is not registered
	 */
	public function getFilter( $name ) {
		return isset( $this->filters[$name] ) ? $this->filters[$name] : null;
	}

	/**
	 * Check whether the URL parameter is for the group, or for individual filters.
	 * Defaults can also be defined on the group if and only if this is true.
	 *
	 * @return bool True if and only if the URL parameter is per-group
	 */
	abstract public function isPerGroupRequestParameter();

	/**
	 * Gets the JS data in the format required by the front-end of the structured UI
	 *
	 * @return array|null Associative array, or null if there are no filters that
	 *  display in the structured UI.  messageKeys is a special top-level value, with
	 *  the value being an array of the message keys to send to the client.
	 */
	public function getJsData() {
		$output = [
			'name' => $this->name,
			'type' => $this->type,
			'fullCoverage' => $this->isFullCoverage,
			'filters' => [],
			'priority' => $this->priority,
			'conflicts' => [],
			'messageKeys' => [ $this->title ]
		];

		if ( isset ( $this->whatsThisHeader ) ) {
			$output['whatsThisHeader'] = $this->whatsThisHeader;
			$output['whatsThisBody'] = $this->whatsThisBody;
			$output['whatsThisUrl'] = $this->whatsThisUrl;
			$output['whatsThisLinkText'] = $this->whatsThisLinkText;

			array_push(
				$output['messageKeys'],
				$output['whatsThisHeader'],
				$output['whatsThisBody'],
				$output['whatsThisLinkText']
			);
		}

		usort( $this->filters, function ( $a, $b ) {
			return $b->getPriority() - $a->getPriority();
		} );

		foreach ( $this->filters as $filterName => $filter ) {
			if ( $filter->displaysOnStructuredUi() ) {
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
			unset( $conflictInfo['filterObject'] );
			unset( $conflictInfo['groupObject'] );
			array_push(
				$output['messageKeys'],
				$conflictInfo['globalDescription'],
				$conflictInfo['contextDescription']
			);
		}

		return $output;
	}

	/**
	 * Get groups conflicting with this filter group
	 *
	 * @return ChangesListFilterGroup[]
	 */
	public function getConflictingGroups() {
		return array_map(
			function ( $conflictDesc ) {
				return $conflictDesc[ 'groupObject' ];
			},
			$this->conflictingGroups
		);
	}

	/**
	 * Get filters conflicting with this filter group
	 *
	 * @return ChangesListFilter[]
	 */
	public function getConflictingFilters() {
		return array_map(
			function ( $conflictDesc ) {
				return $conflictDesc[ 'filterObject' ];
			},
			$this->conflictingFilters
		);
	}

	/**
	 * Check if any filter in this group is selected
	 *
	 * @param FormOptions $opts
	 * @return bool
	 */
	public function anySelected( FormOptions $opts ) {
		return !!count( array_filter(
			$this->getFilters(),
			function ( ChangesListFilter $filter ) use ( $opts ) {
				return $filter->isSelected( $opts );
			}
		) );
	}
}
