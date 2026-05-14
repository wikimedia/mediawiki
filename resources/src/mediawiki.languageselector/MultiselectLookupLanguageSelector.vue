<template>
	<cdx-field :status="status" :messages="statusMessages">
		<cdx-multiselect-lookup
			:id="inputId"
			v-model:input-value="inputValue"
			:input-chips="selection"
			:selected="selectedValues"
			:menu-items="menuItems"
			:menu-config="menuConfig"
			:placeholder="placeholder"
			@input="search"
			@update:input-value="onUpdateInputValue"
			@update:selected="onUpdateSelected"
			@update:input-chips="onUpdateInputChips"
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
		</cdx-multiselect-lookup>
	</cdx-field>
</template>

<script>
const { defineComponent, ref, toRefs, watch, computed } = require( 'vue' );
const { CdxField, CdxMultiselectLookup } = require( './codex.js' );
const { useLanguageSelector, computeMenuItems } = require( 'mediawiki.languageselector.core' );

module.exports = exports = defineComponent( {
	name: 'MultiselectLookupLanguageSelector',
	components: {
		CdxField,
		CdxMultiselectLookup
	},
	props: {
		// eslint-disable-next-line vue/no-unused-properties
		selectableLanguages: {
			type: Object,
			default: () => null
		},
		searchApiUrl: {
			type: String,
			required: true
		},
		debounceDelayMs: {
			type: Number,
			default: 300
		},
		// eslint-disable-next-line vue/no-unused-properties
		selected: {
			type: Array,
			default: () => []
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
	emits: [
		'update:selected'
	],
	setup( props, { emit } ) {
		const { selectableLanguages, selected } = toRefs( props );

		const {
			languages,
			searchQuery,
			searchResults,
			search,
			selection,
			selectedValues,
			isSelectionUpdated,
			clearSearchQuery
		} = useLanguageSelector( selectableLanguages, selected, props.searchApiUrl, props.debounceDelayMs, true );

		const inputValue = ref( '' );
		const menuItems = ref( computeMenuItems( languages.value ) );

		const status = ref( 'default' );
		const statusMessages = computed( () => ( {
			warning: mw.msg( 'languageselector-invalid-input', inputValue.value.slice( 0, 30 ) ) // Limit returned input to 30 bytes
		} ) );

		const onUpdateInputValue = ( val ) => {
			if ( val === '' ) {
				menuItems.value = computeMenuItems( languages.value );
				return;
			}

			search( val );
		};

		const onUpdateSelected = ( values ) => {
			inputValue.value = '';
			if ( isSelectionUpdated( values ) ) {
				emit( 'update:selected', values );
			}

			clearSearchQuery();
		};

		const onUpdateInputChips = ( chips ) => {
			inputValue.value = '';
			const chipValues = chips.map( ( c ) => c.value );
			if ( isSelectionUpdated( chipValues ) ) {
				emit( 'update:selected', chipValues );
			}

			clearSearchQuery();
		};

		const onBlur = () => {
			status.value = 'default';
			if ( inputValue.value.length > 0 ) {
				if ( menuItems.value.length ) {
					// Select the first item from the menu
					const selectedLanguages = selected.value;
					const assumedSelection = menuItems.value[ 0 ].value;
					if ( !selectedLanguages.includes( assumedSelection ) ) {
						selectedLanguages.push( assumedSelection );
					}
					onUpdateSelected( selectedLanguages );
					status.value = 'default';
				} else {
					status.value = 'warning';
				}
			}
		};

		watch( searchResults, () => {
			if ( inputValue.value === '' ) {
				menuItems.value = computeMenuItems( languages.value );
			} else {
				menuItems.value = computeMenuItems( languages.value, searchResults.value );
			}
		} );

		return {
			searchQuery,
			inputValue,
			status,
			statusMessages,
			search,
			selection,
			selectedValues,
			menuItems,
			onBlur,
			onUpdateInputValue,
			onUpdateSelected,
			onUpdateInputChips
		};
	}
} );
</script>
