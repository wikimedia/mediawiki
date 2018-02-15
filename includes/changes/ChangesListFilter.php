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
 * @author Matthew Flaschen
 */

/**
 * Represents a filter (used on ChangesListSpecialPage and descendants)
 *
 * @since 1.29
 */
abstract class ChangesListFilter {
	/**
	 * Filter name
	 *
	 * @var string $name
	 */
	protected $name;

	/**
	 * CSS class suffix used for attribution, e.g. 'bot'.
	 *
	 * In this example, if bot actions are included in the result set, this CSS class
	 * will then be included in all bot-flagged actions.
	 *
	 * @var string|null $cssClassSuffix
	 */
	protected $cssClassSuffix;

	/**
	 * Callable that returns true if and only if a row is attributed to this filter
	 *
	 * @var callable $isRowApplicableCallable
	 */
	protected $isRowApplicableCallable;

	/**
	 * Group.  ChangesListFilterGroup this belongs to
	 *
	 * @var ChangesListFilterGroup $group
	 */
	protected $group;

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

	/**
	 * Array of associative arrays with conflict information.  See
	 * setUnidirectionalConflict
	 *
	 * @var array $conflictingGroups
	 */
	protected $conflictingGroups = [];

	/**
	 * Array of associative arrays with conflict information.  See
	 * setUnidirectionalConflict
	 *
	 * @var array $conflictingFilters
	 */
	protected $conflictingFilters = [];

	/**
	 * Array of associative arrays with subset information
	 *
	 * @var array $subsetFilters
	 */
	protected $subsetFilters = [];

	/**
	 * Priority integer.  Higher value means higher up in the group's filter list.
	 *
	 * @var string $priority
	 */
	protected $priority;

	/**
	 *
	 * @var string $defaultHighlightColor
	 */
	protected $defaultHighlightColor;

	const RESERVED_NAME_CHAR = '_';

	/**
	 * Creates a new filter with the specified configuration, and registers it to the
	 * specified group.
	 *
	 * It infers which UI (it can be either or both) to display the filter on based on
	 * which messages are provided.
	 *
	 * If 'label' is provided, it will be displayed on the structured UI.  Thus,
	 * 'label', 'description', and sub-class parameters are optional depending on which
	 * UI it's for.
	 *
	 * @param array $filterDefinition ChangesListFilter definition
	 * * $filterDefinition['name'] string Name of filter; use lowercase with no
	 *     punctuation
	 * * $filterDefinition['cssClassSuffix'] string CSS class suffix, used to mark
	 *     that a particular row belongs to this filter (when a row is included by the
	 *     filter) (optional)
	 * * $filterDefinition['isRowApplicableCallable'] Callable taking two parameters, the
	 *     IContextSource, and the RecentChange object for the row, and returning true if
	 *     the row is attributed to this filter.  The above CSS class will then be
	 *     automatically added (optional, required if cssClassSuffix is used).
	 * * $filterDefinition['group'] ChangesListFilterGroup Group.  Filter group this
	 *     belongs to.
	 * * $filterDefinition['label'] string i18n key of label for structured UI.
	 * * $filterDefinition['description'] string i18n key of description for structured
	 *     UI.
	 * * $filterDefinition['priority'] int Priority integer.  Higher value means higher
	 *     up in the group's filter list.
	 */
	public function __construct( array $filterDefinition ) {
		if ( isset( $filterDefinition['group'] ) ) {
			$this->group = $filterDefinition['group'];
		} else {
			throw new MWException( 'You must use \'group\' to specify the ' .
				'ChangesListFilterGroup this filter belongs to' );
		}

		if ( strpos( $filterDefinition['name'], self::RESERVED_NAME_CHAR ) !== false ) {
			throw new MWException( 'Filter names may not contain \'' .
				self::RESERVED_NAME_CHAR .
				'\'.  Use the naming convention: \'lowercase\''
			);
		}

		if ( $this->group->getFilter( $filterDefinition['name'] ) ) {
			throw new MWException( 'Two filters in a group cannot have the ' .
				"same name: '{$filterDefinition['name']}'" );
		}

		$this->name = $filterDefinition['name'];

		if ( isset( $filterDefinition['cssClassSuffix'] ) ) {
			$this->cssClassSuffix = $filterDefinition['cssClassSuffix'];
			$this->isRowApplicableCallable = $filterDefinition['isRowApplicableCallable'];
		}

		if ( isset( $filterDefinition['label'] ) ) {
			$this->label = $filterDefinition['label'];
			$this->description = $filterDefinition['description'];
		}

		$this->priority = $filterDefinition['priority'];

		$this->group->registerFilter( $this );
	}

	/**
	 * Marks that the given ChangesListFilterGroup or ChangesListFilter conflicts with this object.
	 *
	 * WARNING: This means there is a conflict when both things are *shown*
	 * (not filtered out), even for the hide-based filters.  So e.g. conflicting with
	 * 'hideanons' means there is a conflict if only anonymous users are *shown*.
	 *
	 * @param ChangesListFilterGroup|ChangesListFilter $other
	 * @param string $globalKey i18n key for top-level conflict message
	 * @param string $forwardKey i18n key for conflict message in this
	 *  direction (when in UI context of $this object)
	 * @param string $backwardKey i18n key for conflict message in reverse
	 *  direction (when in UI context of $other object)
	 */
	public function conflictsWith( $other, $globalKey, $forwardKey, $backwardKey ) {
		if ( $globalKey === null || $forwardKey === null || $backwardKey === null ) {
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
	 * @param ChangesListFilterGroup|ChangesListFilter $other
	 * @param string $globalDescription i18n key for top-level conflict message
	 * @param string $contextDescription i18n key for conflict message in this
	 *  direction (when in UI context of $this object)
	 */
	public function setUnidirectionalConflict( $other, $globalDescription, $contextDescription ) {
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
	 * Marks that the current instance is (also) a superset of the filter passed in.
	 * This can be called more than once.
	 *
	 * This means that anything in the results for the other filter is also in the
	 * results for this one.
	 *
	 * @param ChangesListFilter $other The filter the current instance is a superset of
	 */
	public function setAsSupersetOf( ChangesListFilter $other ) {
		if ( $other->getGroup() !== $this->getGroup() ) {
			throw new MWException( 'Supersets can only be defined for filters in the same group' );
		}

		$this->subsetFilters[] = [
			// It's always the same group, but this makes the representation
			// more consistent with conflicts.
			'group' => $other->getGroup()->getName(),
			'filter' => $other->getName(),
		];
	}

	/**
	 * @return string Name, e.g. hideanons
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return ChangesListFilterGroup Group this belongs to
	 */
	public function getGroup() {
		return $this->group;
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
	 * Checks whether the filter should display on the unstructured UI
	 *
	 * @return bool Whether to display
	 */
	abstract public function displaysOnUnstructuredUi();

	/**
	 * Checks whether the filter should display on the structured UI
	 * This refers to the exact filter.  See also isFeatureAvailableOnStructuredUi.
	 *
	 * @return bool Whether to display
	 */
	public function displaysOnStructuredUi() {
		return $this->label !== null;
	}

	/**
	 * Checks whether an equivalent feature for this filter is available on the
	 * structured UI.
	 *
	 * This can either be the exact filter, or a new filter that replaces it.
	 * @return bool
	 */
	public function isFeatureAvailableOnStructuredUi() {
		return $this->displaysOnStructuredUi();
	}

	/**
	 * @return int Priority.  Higher value means higher up in the group list
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * Gets the CSS class
	 *
	 * @return string|null CSS class, or null if not defined
	 */
	protected function getCssClass() {
		if ( $this->cssClassSuffix !== null ) {
			return ChangesList::CSS_CLASS_PREFIX . $this->cssClassSuffix;
		} else {
			return null;
		}
	}

	/**
	 * Add CSS class if needed
	 *
	 * @param IContextSource $ctx Context source
	 * @param RecentChange $rc Recent changes object
	 * @param array &$classes Non-associative array of CSS class names; appended to if needed
	 */
	public function applyCssClassIfNeeded( IContextSource $ctx, RecentChange $rc, array &$classes ) {
		if ( $this->isRowApplicableCallable === null ) {
			return;
		}

		if ( call_user_func( $this->isRowApplicableCallable, $ctx, $rc ) ) {
			$classes[] = $this->getCssClass();
		}
	}

	/**
	 * Gets the JS data required by the front-end of the structured UI
	 *
	 * @return array Associative array Data required by the front-end.  messageKeys is
	 *  a special top-level value, with the value being an array of the message keys to
	 *  send to the client.
	 */
	public function getJsData() {
		$output = [
			'name' => $this->getName(),
			'label' => $this->getLabel(),
			'description' => $this->getDescription(),
			'cssClass' => $this->getCssClass(),
			'priority' => $this->priority,
			'subset' => $this->subsetFilters,
			'conflicts' => [],
			'defaultHighlightColor' => $this->defaultHighlightColor
		];

		$output['messageKeys'] = [
			$this->getLabel(),
			$this->getDescription(),
		];

		$conflicts = array_merge(
			$this->conflictingGroups,
			$this->conflictingFilters
		);

		foreach ( $conflicts as $conflictInfo ) {
			unset( $conflictInfo['filterObject'] );
			unset( $conflictInfo['groupObject'] );
			$output['conflicts'][] = $conflictInfo;
			array_push(
				$output['messageKeys'],
				$conflictInfo['globalDescription'],
				$conflictInfo['contextDescription']
			);
		}

		return $output;
	}

	/**
	 * Checks whether this filter is selected in the provided options
	 *
	 * @param FormOptions $opts
	 * @return bool
	 */
	abstract public function isSelected( FormOptions $opts );

	/**
	 * Get groups conflicting with this filter
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
	 * Get filters conflicting with this filter
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
	 * Check if the conflict with a group is currently "active"
	 *
	 * @param ChangesListFilterGroup $group
	 * @param FormOptions $opts
	 * @return bool
	 */
	public function activelyInConflictWithGroup( ChangesListFilterGroup $group, FormOptions $opts ) {
		if ( $group->anySelected( $opts ) && $this->isSelected( $opts ) ) {
			/** @var ChangesListFilter $siblingFilter */
			foreach ( $this->getSiblings() as $siblingFilter ) {
				if ( $siblingFilter->isSelected( $opts ) && !$siblingFilter->hasConflictWithGroup( $group ) ) {
					return false;
				}
			}
			return true;
		}
		return false;
	}

	private function hasConflictWithGroup( ChangesListFilterGroup $group ) {
		return in_array( $group, $this->getConflictingGroups() );
	}

	/**
	 * Check if the conflict with a filter is currently "active"
	 *
	 * @param ChangesListFilter $filter
	 * @param FormOptions $opts
	 * @return bool
	 */
	public function activelyInConflictWithFilter( ChangesListFilter $filter, FormOptions $opts ) {
		if ( $this->isSelected( $opts ) && $filter->isSelected( $opts ) ) {
			/** @var ChangesListFilter $siblingFilter */
			foreach ( $this->getSiblings() as $siblingFilter ) {
				if (
					$siblingFilter->isSelected( $opts ) &&
					!$siblingFilter->hasConflictWithFilter( $filter )
				) {
					return false;
				}
			}
			return true;
		}
		return false;
	}

	private function hasConflictWithFilter( ChangesListFilter $filter ) {
		return in_array( $filter, $this->getConflictingFilters() );
	}

	/**
	 * Get filters in the same group
	 *
	 * @return ChangesListFilter[]
	 */
	protected function getSiblings() {
		return array_filter(
			$this->getGroup()->getFilters(),
			function ( $filter ) {
				return $filter !== $this;
			}
		);
	}

	/**
	 * @param string $defaultHighlightColor
	 */
	public function setDefaultHighlightColor( $defaultHighlightColor ) {
		$this->defaultHighlightColor = $defaultHighlightColor;
	}
}
