<template>
	<cdx-field>
		<template #label>
			{{ $i18n( 'ipb-namespaces-label' ).text() }}
		</template>
		<cdx-multiselect-lookup
			v-model:input-chips="chips"
			v-model:selected="namespaces"
			class="mw-block-namespaces"
			:menu-items="menuItems"
			:menu-config="menuConfig"
			:aria-label="$i18n( 'ipb-namespaces-label' ).text()"
			:placeholder="$i18n( 'block-namespaces-placeholder' ).text()"
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

/**
 * Namespaces field component for use by Special:Block.
 *
 * @todo Abstract for general use in MediaWiki (T375220)
 */
module.exports = exports = defineComponent( {
	name: 'NamespacesField',
	components: {
		CdxField,
		CdxMultiselectLookup
	},
	setup() {
		const { namespaces } = storeToRefs( useBlockStore() );
		const mwNamespaces = mw.config.get( 'wgFormattedNamespaces' );
		mwNamespaces[ '0' ] = mw.msg( 'blanknamespace' );
		const initialMenuItems = Object.keys( mwNamespaces )
			// Exclude virtual namespaces
			.filter( ( id ) => Number( id ) >= 0 )
			.map( ( id ) => ( {
				value: Number( id ),
				label: mwNamespaces[ id ]
			} ) );
		const chips = ref(
			namespaces.value.map( ( nsId ) => ( { value: nsId, label: mwNamespaces[ nsId ] } ) )
		);
		const menuItems = ref( initialMenuItems );
		const menuConfig = { visibleItemLimit: 10 };

		/**
		 * On input, filter the menu items.
		 *
		 * @param {string} value
		 */
		function onInput( value ) {
			if ( value ) {
				// eslint-disable-next-line arrow-body-style
				menuItems.value = initialMenuItems.filter( ( item ) => {
					return item.label.toLowerCase().includes( value.toLowerCase() );
				} );
			} else {
				menuItems.value = initialMenuItems;
			}
		}

		/**
		 * Update the store with the new chip values.
		 *
		 * @param {ChipInputItem[]} newChips
		 */
		function onUpdateChips( newChips ) {
			// NOTE: This is to avoid recursive updates since namespaces is bound to MultiselectLookup with v-model
			const uniqueChipValues = newChips.map( ( item ) => item.value );
			const uniqueNewValues = uniqueChipValues.filter( ( chipValue ) => !namespaces.value.includes( chipValue ) );
			if ( uniqueNewValues.length !== 0 || namespaces.value.length > uniqueChipValues.length ) {
				namespaces.value = newChips.map( ( chip ) => chip.value );
			}
		}

		return {
			chips,
			namespaces,
			menuItems,
			menuConfig,
			onInput,
			onUpdateChips
		};
	}
} );
</script>
