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
	 * Callable used to check whether this filter is allowed to take effect
	 *
	 * @var callable $isAllowedCallable
	 */
	protected $isAllowedCallable;

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
	 * List of filters that are a subset of the current filter
	 *
	 * @var array $subsetFilters Array of associative arrays with subset information
	 */
	protected $subsetFilters = [];

	/**
	 * Priority integer.  Higher value means higher up in the group's filter list.
	 *
	 * @var string $priority
	 */
	protected $priority;

	/**
	 * Create a new filter with the specified configuration.
	 *
	 * It infers which UI (it can be either or both) to display the filter on based on
	 * which messages are provided.
	 *
	 * If 'label' is provided, it will be displayed on the structured UI.  Thus,
	 * 'label', 'description', and sub-class parameters are optional depending on which
	 * UI it's for.
	 *
	 * @param array $filterDefinition ChangesListFilter definition
	 *
	 * $filterDefinition['name'] string Name of filter
	 * $filterDefinition['cssClassSuffix'] string CSS class suffix, used to mark
	 *  that a particular row belongs to this filter (when a row is included by the
	 *  filter) (optional)
	 * $filterDefinition['isRowApplicableCallable'] Callable taking two parameters, the
	 *  IContextSource, and the RecentChange object for the row, and returning true if
	 *  the row is attributed to this filter.  The above CSS class will then be
	 *  automatically added (optional, required if cssClassSuffix is used).
	 * $filterDefinition['group'] ChangesListFilterGroup Group.  Filter group this
	 *  belongs to.
	 * $filterDefinition['label'] string i18n key of label for structured UI.
	 * $filterDefinition['description'] string i18n key of description for structured
	 *  UI.
	 * $filterDefinition['isAllowedCallable'] callable Callable taking two parameters,
	 *  the class name of the special page and an IContextSource, and returning true
	 *  if and only if the current user is permitted to use this filter on the current
	 *  wiki.  If it returns false, it will both hide the UI (in all UIs) and prevent
	 *  the DB query modification from taking effect. (optional, defaults to allowed)
	 * $filterDefinition['priority'] int Priority integer.  Higher value means higher
	 *  up in the group's filter list.
	 */
	public function __construct( array $filterDefinition ) {
		if ( isset( $filterDefinition['group'] ) ) {
			$this->group = $filterDefinition['group'];
		} else {
			throw new MWException( 'You must use \'group\' to specify the ' .
				'ChangesListFilterGroup this filter belongs to' );
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

		if ( isset( $filterDefinition['isAllowedCallable'] ) ) {
			$this->isAllowedCallable = $filterDefinition['isAllowedCallable'];
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
				'globalDescription' => $globalDescription,
				'contextDescription' => $contextDescription,
			];
		} elseif ( $other instanceof ChangesListFilter ) {
			$this->conflictingFilters[] = [
				'group' => $other->getGroup()->getName(),
				'filter' => $other->getName(),
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
	 * @param ChangesListFilter The filter the current instance is a superset of
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
	 * @param ChangesListSpecialPage $specialPage Current special page
	 * @return bool Whether to display
	 */
	abstract public function displaysOnUnstructuredUi( ChangesListSpecialPage $specialPage );

	/**
	 * Checks whether the filter should display on the structured UI
	 * This refers to the exact filter.  See also isFeatureAvailableOnStructuredUi.
	 *
	 * @param ChangesListSpecialPage $specialPage Current special page
	 * @return bool Whether to display
	 */
	public function displaysOnStructuredUi( ChangesListSpecialPage $specialPage ) {
		return $this->label !== null && $this->isAllowed( $specialPage );
	}

	/**
	 * Checks whether an equivalent feature for this filter is available on the
	 * structured UI.
	 *
	 * This can either be the exact filter, or a new filter that replaces it.
	 */
	public function isFeatureAvailableOnStructuredUi( ChangesListSpecialPage $specialPage ) {
		return $this->displaysOnStructuredUi( $specialPage );
	}

	/**
	 * @return int Priority.  Higher value means higher up in the group list
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * Checks whether the filter is allowed for the current context
	 *
	 * @param ChangesListSpecialPage $specialPage Current special page
	 * @return bool Whether it is allowed
	 */
	public function isAllowed( ChangesListSpecialPage $specialPage ) {
		if ( $this->isAllowedCallable === null ) {
			return true;
		} else {
			return call_user_func(
				$this->isAllowedCallable,
				get_class( $specialPage ),
				$specialPage->getContext()
			);
		}
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
	 * @param Non-associative array of CSS class names; appended to if needed
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
