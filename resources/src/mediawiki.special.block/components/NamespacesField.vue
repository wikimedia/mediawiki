<template>
	<cdx-field>
		<template #label>
			{{ label }}
		</template>
		<cdx-multiselect-lookup
			v-model:input-chips="chips"
			v-model:selected="selectedNamespaces"
			:class="lookupClass"
			:menu-items="menuItems"
			:menu-config="menuConfig"
			:aria-label="label"
			:placeholder="placeholder"
			@input="onInput"
			@update:input-chips="onUpdateChips"
		></cdx-multiselect-lookup>
	</cdx-field>
</template>

<script>
const { computed, defineComponent, ref } = require( 'vue' );
const { CdxField, ChipInputItem, CdxMultiselectLookup } = require( '@wikimedia/codex' );

/**
 * Namespace lookup field component for use by Special:Block.
 *
 * @todo Abstract for general use in MediaWiki (T375220)
 */
module.exports = exports = defineComponent( {
	name: 'NamespacesField',
	components: {
		CdxField,
		CdxMultiselectLookup
	},
	props: {
		modelValue: {
			type: Array,
			required: true
		},
		lookupClass: {
			type: String,
			required: true
		},
		labelMessage: {
			type: String,
			required: true
		},
		placeholderMessage: {
			type: String,
			required: true
		}
	},
	emits: [ 'update:modelValue' ],
	setup( props, { emit } ) {
		const label = computed( () => mw.message( props.labelMessage ).text() );
		const placeholder = computed( () => mw.message( props.placeholderMessage ).text() );
		const selectedNamespaces = computed( {
			get: () => props.modelValue,
			set: ( value ) => emit( 'update:modelValue', value )
		} );
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
			props.modelValue.map( ( nsId ) => ( { value: nsId, label: mwNamespaces[ nsId ] } ) )
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
			// Avoid recursive updates since selectedNamespaces is bound to MultiselectLookup with v-model
			const uniqueChipValues = newChips.map( ( item ) => item.value );
			const uniqueNewValues = uniqueChipValues.filter(
				( chipValue ) => !props.modelValue.includes( chipValue )
			);
			if ( uniqueNewValues.length !== 0 || props.modelValue.length > uniqueChipValues.length ) {
				emit( 'update:modelValue', uniqueChipValues );
			}
		}

		return {
			chips,
			label,
			selectedNamespaces,
			menuItems,
			menuConfig,
			onInput,
			onUpdateChips,
			placeholder
		};
	}
} );
</script>
