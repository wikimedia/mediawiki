<template>
	<cdx-field :status="status" :messages="statusMessages">
		<cdx-lookup
			:id="inputId"
			v-model:input-value="inputValue"
			:selected="selection.value"
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
			selection
		} = useLanguageSelector( selectableLanguages, selected, props.searchApiUrl, props.debounceDelayMs );

		const inputValue = ref( selection.value && selection.value.label || '' );
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
			if ( inputValue.value.length > 0 && selection.value.value === null ) {
				if ( menuItems.value.length ) {
					// Select the first item from the menu
					onUpdateSelected( menuItems.value[ 0 ].value );
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
			inputValue,
			status,
			statusMessages,
			searchQuery,
			selection,
			menuItems,
			onBlur,
			onUpdateInputValue,
			onUpdateSelected
		};
	}
} );
</script>
