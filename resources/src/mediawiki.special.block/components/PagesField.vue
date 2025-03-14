<template>
	<cdx-field>
		<template #label>
			{{ $i18n( 'ipb-pages-label' ).text() }}
		</template>
		<cdx-multiselect-lookup
			v-model:input-chips="chips"
			v-model:selected="selection"
			v-model:input-value="inputValue"
			class="mw-block-pages"
			:menu-items="menuItems"
			:menu-config="menuConfig"
			:aria-label="$i18n( 'ipb-pages-label' ).text()"
			:placeholder="placeholder"
			@input="onInput"
			@update:input-chips="onUpdateChips"
		></cdx-multiselect-lookup>
	</cdx-field>
</template>

<script>

const { computed, watch, defineComponent, ref } = require( 'vue' );
const { storeToRefs } = require( 'pinia' );
const { CdxField, ChipInputItem, CdxMultiselectLookup } = require( '@wikimedia/codex' );
const useBlockStore = require( '../stores/block.js' );
const api = new mw.Api();
const useMultiblocks = mw.config.get( 'blockEnableMultiblocks' );
const MAX_CHIPS = useMultiblocks ? 50 : 10;

/**
 * Field component for selecting pages to block.
 *
 * @todo Abstract for general use in MediaWiki (T375220)
 */
module.exports = exports = defineComponent( {
	name: 'PagesField',
	components: {
		CdxField,
		CdxMultiselectLookup
	},
	setup() {
		const { pages } = storeToRefs( useBlockStore() );
		const selection = ref( pages.value );
		if ( pages.value.length > MAX_CHIPS ) {
			pages.value = pages.value.slice( 0, MAX_CHIPS );
		}
		const chips = ref( [] );
		watch( pages, ( newValue ) => {
			chips.value = pages.value.map( ( page ) => ( { value: page, label: page } ) );
			selection.value = newValue;
		}, { immediate: true } );

		const placeholder = computed( () => {
			if ( chips.value.length === MAX_CHIPS ) {
				return '';
			}
			return mw.msg( 'block-pages-placeholder' );
		} );
		const inputValue = ref( '' );
		const menuItems = ref( [] );
		const menuConfig = { visibleItemLimit: 10 };

		/**
		 * Get search results.
		 *
		 * @param {string} searchTerm
		 *
		 * @return {Promise}
		 */
		function fetchResults( searchTerm ) {
			return api.get( {
				action: 'query',
				list: 'prefixsearch',
				pssearch: searchTerm,
				pslimit: 10,
				format: 'json'
			} ).then( ( response ) => response.query );
		}

		/**
		 * Handle lookup input.
		 *
		 * @param {string} value
		 */
		const onInput = mw.util.debounce( ( value ) => {
			if ( chips.value.length >= MAX_CHIPS ) {
				menuItems.value = [];
				return;
			}

			// Clear menu items if the input was cleared.
			if ( !value ) {
				menuItems.value = [];
				return;
			}

			fetchResults( value ).then( ( data ) => {
				// Make sure this data is still relevant first.
				if ( inputValue.value !== value ) {
					return;
				}

				// Reset the menu items if there are no results.
				if ( !data || data.prefixsearch.length === 0 ) {
					menuItems.value = [];
					return;
				}

				// Build and update the array of menu items.
				menuItems.value = data.prefixsearch.map( ( result ) => ( {
					label: result.title,
					value: result.title
				} ) );
			} ).catch( () => {
				// On error, set results to empty.
				menuItems.value = [];
			} );
		}, 300 );

		/**
		 * Update the store with the new chip values.
		 *
		 * @param {ChipInputItem[]} newChips
		 */
		function onUpdateChips( newChips ) {
			// NOTE: This is to avoid recursive updates since pages is bound to MultiselectLookup with v-model
			const uniqueChipValues = newChips.map( ( item ) => item.value );
			const uniqueNewValues = uniqueChipValues.filter( ( chipValue ) => !pages.value.includes( chipValue ) );
			if ( uniqueNewValues.length !== 0 || pages.value.length > uniqueChipValues.length ) {
				pages.value = newChips.map( ( chip ) => chip.value );
			}
		}

		return {
			chips,
			placeholder,
			selection,
			inputValue,
			menuItems,
			menuConfig,
			onInput,
			onUpdateChips
		};
	}
} );
</script>
