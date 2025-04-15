<template>
	<cdx-field
		:key="refreshKey"
		:is-fieldset="true"
		:status="status"
		:messages="messages"
	>
		<!-- eslint-disable vue/no-unused-refs -->
		<cdx-lookup
			id="mw-bi-target"
			v-model:selected="selection"
			v-model:input-value="currentSearchTerm"
			class="mw-block-target"
			name="wpTarget"
			required
			:clearable="true"
			:menu-items="menuItems"
			:placeholder="$i18n( 'block-target-placeholder' ).text()"
			:start-icon="cdxIconSearch"
			@input="onInput"
			@change="onChange"
			@clear="onClear"
			@update:selected="onSelect"
		>
		</cdx-lookup>
		<!-- eslint-enable vue/no-unused-refs -->
		<template #label>
			{{ $i18n( 'block-target' ).text() }}
		</template>
		<component
			:is="customComponent"
			v-for="customComponent in customComponents"
			:key="customComponent.name"
			:target-user="targetExists ? targetUser : null"
		></component>
	</cdx-field>
</template>

<script>
const {
	defineComponent,
	onMounted,
	ref,
	shallowRef,
	watch,
	DefineSetupFnComponent,
	Ref
} = require( 'vue' );
const { CdxLookup, CdxField } = require( '@wikimedia/codex' );
const { storeToRefs } = require( 'pinia' );
const { cdxIconSearch } = require( '../icons.json' );
const useBlockStore = require( '../stores/block.js' );
const util = require( '../util.js' );
const api = new mw.Api();

/**
 * User lookup component for Special:Block.
 *
 * @todo Abstract for general use in MediaWiki (T375220)
 */
module.exports = exports = defineComponent( {
	name: 'UserLookup',
	components: { CdxLookup, CdxField },
	props: {
		modelValue: { type: [ String, null ], required: true }
	},
	emits: [
		'update:modelValue'
	],
	setup( props ) {
		const store = useBlockStore();
		const { targetExists, targetUser } = storeToRefs( store );
		/**
		 * Custom components to be added to the bottom of the field.
		 *
		 * @type {Ref<DefineSetupFnComponent>}
		 */
		const customComponents = shallowRef( [] );
		/**
		 * A key to force the component to re-render.
		 *
		 * @type {Ref<number>}
		 */
		const refreshKey = ref( 0 );
		/**
		 * Codex Lookup component requires a v-modeled `selected` prop.
		 * Until a selection is made, the value may be set to null.
		 * We instead want to only update the targetUser for non-null values
		 * (made either via selection, or the 'change' event).
		 *
		 * @type {Ref<string>}
		 */
		const selection = ref( props.modelValue || '' );
		/**
		 * This is the source of truth for what should be the target user,
		 * but it should only change on 'change' or 'select' events,
		 * otherwise we'd fire off API queries for the block log unnecessarily.
		 *
		 * @type {Ref<string>}
		 */
		const currentSearchTerm = ref( props.modelValue || '' );
		/**
		 * Menu items for the Lookup component.
		 *
		 * @type {Ref<Object[]>}
		 */
		const menuItems = ref( [] );
		/**
		 * Error status of the field.
		 *
		 * @type {Ref<string>}
		 */
		const status = ref( 'default' );
		/**
		 * Error messages for the field.
		 *
		 * @type {Ref<Object>}
		 */
		const messages = ref( {} );

		let htmlInput;

		// Set a flag to keep track of pending API requests, so we can abort if
		// the target string changes
		let pending = false;

		onMounted( () => {
			// Get the input element.
			htmlInput = document.getElementById( 'mw-bi-target' );

			// Focus the input on mount if there's no initial value.
			if ( !targetUser.value ) {
				htmlInput.focus();
			}

			// Ensure error messages are displayed for missing users.
			if ( !!targetUser.value && !store.targetExists ) {
				validate();
			}

			// If loaded from bfcache, re-render the component to ensure the correct state.
			window.addEventListener( 'pageshow', ( event ) => {
				if ( event.persisted ) {
					refreshKey.value += 1;
				}
			} );

			/**
			 * Hook for custom components to be added to the UserLookup component.
			 *
			 * @event codex.userlookup
			 * @param {Ref<DefineSetupFnComponent[]>} customComponents
			 * @private
			 * @internal
			 */
			mw.hook( 'codex.userlookup' ).fire( customComponents );
		} );

		watch( targetUser, ( newValue ) => {
			if ( newValue ) {
				currentSearchTerm.value = newValue;
			}
		} );

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
		 * @return {Promise}
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
				return Promise.resolve();
			}

			return fetchResults( value )
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
		 * Validate the target user and set the status and messages.
		 *
		 * @return {boolean} Whether the target user is valid.
		 */
		function validate() {
			const inResults = menuItems.value.some( ( item ) => item.value === currentSearchTerm.value );
			let error = null;

			if ( !htmlInput.checkValidity() ) {
				// Validation constraints on the HTMLInputElement failed.
				error = htmlInput.validationMessage;
			} else if ( !inResults && !mw.util.isIPAddress( currentSearchTerm.value, true ) ) {
				// Not a valid username or IP.
				error = mw.message( 'nosuchusershort', currentSearchTerm.value ).text();
				targetExists.value = false;

				// If there is a previously set targetUser then we need to clear all store data
				// since we now have an invalid target in the UserLookup text field.
				if ( targetUser.value ) {
					store.resetForm( true );
				}
			} else {
				targetExists.value = true;
			}

			if ( error ) {
				status.value = 'error';
				messages.value = { error };
			} else {
				status.value = 'default';
				messages.value = {};
			}

			return !error;
		}

		/**
		 * Handle lookup change.
		 */
		function onChange() {
			// Use the currentSearchTerm value instead of the event target value,
			// since the event can be fired before the watcher updates the value.
			setTarget( currentSearchTerm.value );
		}

		/**
		 * When the clear button is clicked.
		 */
		function onClear() {
			store.resetForm( true );
			htmlInput.focus();
			status.value = 'default';
			messages.value = {};
		}

		/**
		 * Handle lookup selection.
		 */
		function onSelect() {
			if ( selection.value !== null ) {
				currentSearchTerm.value = selection.value;
				setTarget( selection.value );
			}
		}

		/**
		 * Set the target user and trigger validation.
		 *
		 * @param {string} value
		 */
		function setTarget( value ) {
			validate();

			if ( mw.util.isIPAddress( value, true ) ) {
				// Sanitize IP & IP ranges
				targetUser.value = util.sanitizeRange( value );
			} else {
				targetUser.value = value;
			}

			onInput( value );
		}

		return {
			targetExists,
			targetUser,
			menuItems,
			onChange,
			onInput,
			onClear,
			onSelect,
			cdxIconSearch,
			currentSearchTerm,
			selection,
			status,
			messages,
			customComponents,
			refreshKey
		};
	}
} );
</script>

<style lang="less">
.mw-block-conveniencelinks {
	a {
		font-size: 90%;
	}
}
</style>
