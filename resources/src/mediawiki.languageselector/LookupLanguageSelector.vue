<template>
	<cdx-field :status="status" :messages="statusMessages">
		<cdx-lookup
			:id="inputId"
			v-model:input-value="inputValue"
			:selected="selectedValues"
			:menu-items="menuItems"
			:menu-config="menuConfig"
			:placeholder="placeholder"
			@update:input-value="onUpdateInputValue"
			@update:selected="onUpdateSelected"
			@blur="onBlur"
		>
			<template #menu-item="{ menuItem }">
				<slot
					name="menu-item"
					:menu-item="menuItem"
					:language-code="menuItem.value"
					:language-name="menuItem.label">
					{{ menuItem.label }}
				</slot>
			</template>
			<template #no-results>
				<slot name="no-results" :search-query="searchQuery">
					{{ $i18n( 'languageselector-no-results' ).text() }}
				</slot>
			</template>
		</cdx-lookup>
	</cdx-field>
</template>

<script>
const { defineComponent, ref, toRefs, watch, computed } = require( 'vue' );
const { CdxField, CdxLookup } = require( './codex.js' );
const { useLanguageSelector, computeMenuItems } = require( 'mediawiki.languageselector.core' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'LookupLanguageSelector',
	components: {
		CdxField,
		CdxLookup
	},
	props: {
		// eslint-disable-next-line vue/no-unused-properties
		selectableLanguages: {
			type: Object,
			default: () => null
		},
		debounceDelayMs: {
			type: Number,
			default: 300
		},
		// eslint-disable-next-line vue/no-unused-properties
		selected: {
			type: String,
			default: null
		},
		searchApiUrl: {
			type: String,
			required: true
		},
		menuConfig: {
			type: Object,
			default: () => ( {} )
		},
		inputId: {
			type: String,
			default: ''
		},
		placeholder: {
			type: String,
			default: ''
		}
	},
	emits: [ 'update:selected' ],
	setup( props, { emit } ) {
		const { selectableLanguages, selected } = toRefs( props );
		const {
			languages,
			searchQuery,
			searchResults,
			search,
			clearSearchQuery,
			isSelectionUpdated,
			selection,
			selectedValues
		} = useLanguageSelector( selectableLanguages, selected, props.searchApiUrl, props.debounceDelayMs );

		const inputValue = ref( '' );
		watch( selection, ( newSelection ) => {
			inputValue.value = newSelection.label || '';
		}, { immediate: true } );

		const menuItems = ref( [] );

		const status = ref( 'default' );
		const statusMessages = computed( () => {
			if ( status.value !== 'warning' ) {
				return {};
			}

			return {
				// Limit returned input to 30 bytes
				warning: mw.msg( 'languageselector-invalid-input', inputValue.value.slice( 0, 30 ) )
			};
		} );

		const onUpdateInputValue = ( val ) => {
			if ( val === '' ) {
				clearSearchQuery();
				return;
			}

			if ( val !== selection.value.label ) {
				search( val );
			}
		};

		const onUpdateSelected = ( val ) => {
			if ( isSelectionUpdated( val ) ) {
				emit( 'update:selected', val );
			}
			if ( val ) {
				clearSearchQuery();
			}
		};

		const onBlur = () => {
			status.value = 'default';
			if ( inputValue.value.length > 0 && !selectedValues.value ) {
				if ( menuItems.value.length ) {
					// Select the first item from the menu
					onUpdateSelected( menuItems.value[ 0 ].value );
				} else {
					status.value = 'warning';
				}
			}
		};

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

		return {
			inputValue,
			status,
			statusMessages,
			searchQuery,
			selectedValues,
			menuItems,
			onBlur,
			onUpdateInputValue,
			onUpdateSelected
		};
	}
} );
</script>
