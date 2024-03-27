<template>
	<cdx-field>
		<cdx-lookup
			v-model:selected="wrappedModel"
			:menu-items="menuItems"
			:start-icon="cdxIconSearch"
			:placeholder="$i18n( 'block-user-placeholder' )"
			@input="onInput"
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

// @vue/component
module.exports = defineComponent( {
	name: 'UserLookup',
	components: { CdxLookup, CdxField },
	props: {
		// eslint-disable-next-line vue/no-unused-properties
		modelValue: { type: [ Number, String, null ], required: true }
	},
	emits: [
		'update:modelValue'
	],
	setup( props, { emit } ) {
		const menuItems = ref( [] );
		const currentSearchTerm = ref( '' );
		const wrappedModel = useModelWrapper(
			toRef( props, 'modelValue' ),
			emit
		);

		/**
		 * Get search results.
		 *
		 * @param {string} searchTerm
		 * @param {number} offset Optional result offset
		 *
		 * @return {Promise}
		 */
		function fetchResults( searchTerm ) {
			const api = new mw.Api();

			const params = {
				origin: '*',
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
			// Internally track the current search term.
			currentSearchTerm.value = value;

			// Do nothing if we have no input.
			if ( !value ) {
				menuItems.value = [];
				return;
			}

			fetchResults( value )
				.then( ( data ) => {
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
					const results = data.allusers.map( ( result ) => {
						return {
							label: result.name,
							value: result.userid
						};
					} );

					// Update menuItems.
					menuItems.value = results;
				} )
				.catch( () => {
					// On error, set results to empty.
					menuItems.value = [];
				} );
		}

		return {
			menuItems,
			onInput,
			cdxIconSearch,
			wrappedModel
		};
	}
} );
</script>
