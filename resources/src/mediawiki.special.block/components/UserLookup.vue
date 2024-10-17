<template>
	<cdx-field
		:is-fieldset="true"
		:status="status"
		:messages="messages"
	>
		<cdx-lookup
			v-model:selected="selection"
			v-model:input-value="currentSearchTerm"
			name="wpTarget"
			required
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
const { defineComponent, ref, watch } = require( 'vue' );
const { CdxLookup, CdxField } = require( '@wikimedia/codex' );
const { storeToRefs } = require( 'pinia' );
const { cdxIconSearch } = require( '../icons.json' );
const useBlockStore = require( '../stores/block.js' );
const api = new mw.Api();

module.exports = exports = defineComponent( {
	name: 'UserLookup',
	components: { CdxLookup, CdxField },
	props: {
		modelValue: { type: [ String, null ], required: true },
		/**
		 * Whether the form has been submitted yet. This is used to show
		 * validation messages only after the form has been submitted.
		 */
		formSubmitted: {
			type: Boolean,
			default: false
		}
	},
	emits: [
		'update:modelValue'
	],
	setup( props ) {
		const { targetUser } = storeToRefs( useBlockStore() );

		// Set a flag to keep track of pending API requests, so we can abort if
		// the target string changes
		let pending = false;

		// Codex Lookup component requires a v-modeled `selected` prop.
		// Until a selection is made, the value may be set to null.
		// We instead want to only update the targetUser for non-null values
		// (made either via selection, or the 'change' event).
		const selection = ref( props.modelValue || '' );
		// This handles changes via selection, while onChange() handles changes via input.
		watch( selection, ( newValue ) => {
			if ( newValue !== null ) {
				targetUser.value = newValue;
			}
		} );

		const currentSearchTerm = ref( props.modelValue || '' );
		const menuItems = ref( [] );
		const status = ref( 'default' );
		const messages = ref( {} );

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
		 * Validate the input element.
		 *
		 * @param {HTMLInputElement} el
		 */
		function validate( el ) {
			if ( el.checkValidity() ) {
				status.value = 'default';
				messages.value = {};
			} else {
				status.value = 'error';
				messages.value = { error: el.validationMessage };
			}
		}

		/**
		 * Handle lookup change.
		 *
		 * @param {Event} event
		 */
		function onChange( event ) {
			validate( event.target );
			targetUser.value = event.target.value;
		}

		// Validate the input when the form is submitted.
		// TODO: Remove once Codex supports native validations (T373872).
		watch( () => props.formSubmitted, () => {
			validate( document.querySelector( '[name="wpTarget"]' ) );
		} );

		return {
			menuItems,
			onChange,
			onInput,
			cdxIconSearch,
			currentSearchTerm,
			selection,
			status,
			messages
		};
	}
} );
</script>
