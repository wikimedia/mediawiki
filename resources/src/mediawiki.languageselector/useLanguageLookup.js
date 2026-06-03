const { ref, computed, watch, unref } = require( 'vue' );

/**
 * Convert language data to Codex menu items format
 *
 * @param {Object} languages
 * @param {string[]|undefined} languageCodes Language codes to include; if undefined, include all languages
 * @return {{label: string, value: string}[]}
 */
function computeMenuItems( languages, languageCodes ) {
	if ( languageCodes ) {
		return languageCodes.map( ( code ) => ( {
			label: languages[ code ] || code,
			value: code
		} ) );
	}

	return Object.entries( languages ).map( ( [ code, name ] ) => ( {
		label: name,
		value: code
	} ) );
}

/**
 * Composable for language lookup components (single and multiselect)
 *
 * @param {Object} options
 * @param {Object} options.selection Ref<Object>
 * @param {Object} options.selectedValues Ref<string|string[]>
 * @param {Object} options.languages Ref<Object>
 * @param {Object} options.searchQuery Ref<string>
 * @param {Object} options.searchResults Ref<string[]>
 * @param {Function} options.search
 * @param {Function} options.clearSearchQuery
 * @param {Function} options.isSelectionUpdated
 * @param {Function} options.emit
 * @param {boolean} options.isMultiple
 * @return {Object}
 */
function useLanguageLookup( {
	selection,
	selectedValues,
	languages,
	searchQuery,
	searchResults,
	search,
	clearSearchQuery,
	isSelectionUpdated,
	emit,
	isMultiple
} ) {
	const inputValue = ref( '' );
	const status = ref( 'default' );

	if ( !isMultiple ) {
		watch( selection, ( newSelection ) => {
			inputValue.value = newSelection.label || '';
		}, { immediate: true } );
	}

	const statusMessages = computed( () => {
		if ( status.value !== 'warning' ) {
			return {};
		}

		return {
			// Limit returned input to 30 bytes
			warning: mw.msg( 'languageselector-invalid-input', inputValue.value.slice( 0, 30 ) )
		};
	} );

	const menuItems = ref( [] );
	const allMenuItems = computed( () => computeMenuItems( languages.value ) );

	watch( [ searchResults, allMenuItems ], () => {
		if ( searchQuery.value ) {
			menuItems.value = computeMenuItems( languages.value, searchResults.value );
		} else {
			menuItems.value = allMenuItems.value;
		}
	}, { immediate: true } );

	watch( searchQuery, ( newQuery ) => {
		if ( !newQuery ) {
			menuItems.value = allMenuItems.value;
		}
	} );

	const onUpdateInputValue = ( val ) => {
		if ( val === '' ) {
			clearSearchQuery();
			return;
		}

		// Don't re-search when the input still reflects the current single
		// selection's label (e.g. right after the user picks an item, Codex
		// sets the input text to the selected label). In multiple mode the
		// input is cleared on selection, so this never applies.
		if ( !isMultiple && val === selection.value.label ) {
			return;
		}

		search( val );
	};

	const onUpdateSelected = ( val ) => {
		if ( isMultiple ) {
			inputValue.value = '';
		}
		if ( isSelectionUpdated( val ) ) {
			emit( 'update:selected', val );
		}
		clearSearchQuery();
	};

	// In multiple mode, removing a chip (the X button) only emits
	// update:input-chips, not update:selected. Translate the remaining chips
	// back into selected values so the removal propagates to the parent.
	const onUpdateInputChips = ( chips ) => {
		onUpdateSelected( chips.map( ( chip ) => chip.value ) );
	};

	const onBlur = () => {
		status.value = 'default';
		const hasInput = inputValue.value.length > 0;
		const shouldAttemptSelection = isMultiple ? hasInput : ( hasInput && !selectedValues.value );

		if ( shouldAttemptSelection ) {
			if ( menuItems.value.length ) {
				let nextSelection;
				if ( isMultiple ) {
					nextSelection = [ ...unref( selectedValues ) ];
					const assumedSelection = menuItems.value[ 0 ].value;
					if ( !nextSelection.includes( assumedSelection ) ) {
						nextSelection.push( assumedSelection );
					}
				} else {
					nextSelection = menuItems.value[ 0 ].value;
				}
				onUpdateSelected( nextSelection );
			} else {
				status.value = 'warning';
			}
		}
	};

	return {
		inputValue,
		status,
		statusMessages,
		menuItems,
		onUpdateInputValue,
		onUpdateSelected,
		onUpdateInputChips,
		onBlur
	};
}

module.exports = useLanguageLookup;
