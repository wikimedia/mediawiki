<template>
	<cdx-field :is-fieldset="true">
		<cdx-lookup
			v-model:selected="selection"
			v-model:input-value="currentSearchTerm"
			name="wpTarget"
			:menu-items="menuItems"
			:placeholder="$i18n( 'block-user-placeholder' ).text()"
			:start-icon="cdxIconSearch"
			@input="onInput"
			@change="onChange"
			@update:selected="currentSearchTerm = selection"
		>
		</cdx-lookup>
		<template #label>
			{{ $i18n( 'block-user-label' ).text() }}
		</template>
		<template #description>
			{{ $i18n( 'block-user-description' ).text() }}
		</template>
	</cdx-field>
</template>

<script>
const { defineComponent, toRef, ref } = require( 'vue' );
const { CdxLookup, CdxField, useModelWrapper } = require( '@wikimedia/codex' );
const { cdxIconSearch } = require( '../icons.json' );
const api = new mw.Api();

module.exports = exports = defineComponent( {
	name: 'UserLookup',
	components: { CdxLookup, CdxField },
	props: {
		modelValue: { type: [ String, null ], required: true }
	},
	emits: [
		'update:modelValue'
	],
	setup( props, { emit } ) {
		// Set a flag to keep track of pending API requests, so we can abort if
		// the target string changes
		let pending = false;

		const menuItems = ref( [] );
		const currentSearchTerm = ref( props.modelValue || '' );
		const selection = useModelWrapper(
			toRef( props, 'modelValue' ),
			emit
		);

		/**
		 * Get search results.
		 *
		 * @param {string} searchTerm
		 * @return {Promise}
		 */
		function fetchResults( searchTerm ) {
			const params = {
				action: 'query',
				format: 'json',
				formatversion: 2,
				list: 'allusers',
				aulimit: '10',
				auprefix: searchTerm
			};

			return api.get( params )
				.then( ( response ) => response.query );
		}

		/**
		 * Handle lookup input.
		 *
		 * @param {string} value
		 */
		function onInput( value ) {
			// Abort any existing request if one is still pending
			if ( pending ) {
				pending = false;
				api.abort();
			}

			// Internally track the current search term.
			currentSearchTerm.value = value;

			// Do nothing if we have no input.
			if ( !value ) {
				menuItems.value = [];
				return;
			}

			fetchResults( value )
				.then( ( data ) => {
					pending = false;

					// Make sure this data is still relevant first.
					if ( currentSearchTerm.value !== value ) {
						return;
					}

					// Reset the menu items if there are no results.
					if ( !data.allusers || data.allusers.length === 0 ) {
						menuItems.value = [];
						return;
					}

					// Build an array of menu items.
					menuItems.value = data.allusers.map( ( result ) => ( {
						label: result.name,
						value: result.name
					} ) );
				} )
				.catch( () => {
					// On error, set results to empty.
					menuItems.value = [];
				} );
		}

		/**
		 * Handle lookup change.
		 *
		 * @param {Event} event
		 */
		function onChange( event ) {
			emit( 'update:modelValue', event.target.value );
		}

		return {
			menuItems,
			onChange,
			onInput,
			cdxIconSearch,
			currentSearchTerm,
			selection
		};
	}
} );
</script>
