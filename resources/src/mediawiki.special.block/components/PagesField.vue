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
			:placeholder="$i18n( 'block-pages-placeholder' ).text()"
			@input="onInput"
			@update:input-chips="onUpdateChips"
		></cdx-multiselect-lookup>
	</cdx-field>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const { storeToRefs } = require( 'pinia' );
const { CdxField, ChipInputItem, CdxMultiselectLookup } = require( '@wikimedia/codex' );
const useBlockStore = require( '../stores/block.js' );
const api = new mw.Api();

module.exports = exports = defineComponent( {
	name: 'PagesField',
	components: {
		CdxField,
		CdxMultiselectLookup
	},
	setup() {
		const { pages } = storeToRefs( useBlockStore() );
		const chips = ref(
			pages.value.map( ( page ) => ( { value: page, label: page } ) )
		);
		const selection = ref( [] );
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
			pages.value = newChips.map( ( chip ) => chip.value );
		}

		return {
			chips,
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
