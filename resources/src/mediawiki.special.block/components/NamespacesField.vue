<template>
	<cdx-field>
		<template #label>
			{{ $i18n( 'ipb-namespaces-label' ).text() }}
		</template>
		<cdx-multiselect-lookup
			v-model:input-chips="chips"
			v-model:selected="selection"
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

module.exports = exports = defineComponent( {
	name: 'NamespacesField',
	components: {
		CdxField,
		CdxMultiselectLookup
	},
	setup() {
		const { namespaces } = storeToRefs( useBlockStore() );
		const selection = ref( [] );
		const mwNamespaces = mw.config.get( 'wgFormattedNamespaces' );
		mwNamespaces[ '0' ] = mw.msg( 'blanknamespace' );
		const initialMenuItems = Object.keys( mwNamespaces ).map( ( id ) => ( {
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
					return item.label.toLowerCase().indexOf( value.toLowerCase() ) !== -1;
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
			namespaces.value = newChips.map( ( chip ) => chip.value );
		}

		return {
			chips,
			selection,
			menuItems,
			menuConfig,
			onInput,
			onUpdateChips
		};
	}
} );
</script>
