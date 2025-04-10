<template>
	<!-- @todo Remove after it's no longer new. -->
	<cdx-message v-if="enableMultiblocks" allow-user-dismiss>
		{{ $i18n( 'block-multiblocks-new-feature' ) }}
	</cdx-message>
	<!-- @todo Remove some time after deprecation -->
	<cdx-message
		v-if="wasRedirected"
		allow-user-dismiss
	>
		{{ $i18n( 'block-unblock-redirected' ) }}
	</cdx-message>
	<cdx-field
		class="mw-block-fieldset"
		:is-fieldset="true"
		:disabled="store.formDisabled"
	>
		<div ref="messagesContainer" class="mw-block-messages">
			<cdx-message
				v-if="blockAdded"
				type="success"
				:allow-user-dismiss="true"
				class="mw-block-success"
			>
				<p><strong>{{ blockSavedMessage }}</strong></p>
				<!-- eslint-disable-next-line vue/no-v-html -->
				<p v-html="$i18n( 'block-success', store.targetUser ).parse()"></p>
			</cdx-message>
			<cdx-message
				v-if="blockRemoved"
				type="success"
				:allow-user-dismiss="true"
			>
				<p>{{ $i18n( 'block-removed' ) }}</p>
			</cdx-message>
			<cdx-message
				v-for="( formError, index ) in formErrors"
				:key="index"
				type="error"
				inline
			>
				<!-- eslint-disable-next-line vue/no-v-html -->
				<div v-html="formError"></div>
			</cdx-message>
		</div>
		<user-lookup
			v-model="store.targetUser"
		></user-lookup>

		<div v-if="showBlockLogs">
			<block-log
				:key="`${submitCount}-active`"
				:open="blockAdded || blockRemoved"
				:can-delete-log-entry="false"
				block-log-type="active"
				@edit-block="onEditBlock"
				@remove-block="onRemoveBlock"
			></block-log>
			<block-log
				v-if="mw.util.isIPAddress( store.targetUser, true )"
				:key="`${submitCount}-active-ranges`"
				:can-delete-log-entry="false"
				block-log-type="active-ranges"
			></block-log>
			<block-log
				:key="`${submitCount}-recent`"
				block-log-type="recent"
				:can-delete-log-entry="canDeleteLogEntry"
			></block-log>
			<block-log
				v-if="blockShowSuppressLog"
				:key="`${submitCount}-suppress`"
				block-log-type="suppress"
				:can-delete-log-entry="canDeleteLogEntry"
			></block-log>

			<div v-if="formVisible" class="mw-block__block-form">
				<h2>{{ formHeaderText }}</h2>
				<block-type-field></block-type-field>
				<expiry-field></expiry-field>
				<reason-field v-model="store.reason"></reason-field>
				<block-details-field></block-details-field>
				<additional-details-field></additional-details-field>
				<confirmation-dialog
					v-if="store.confirmationNeeded"
					v-model:open="confirmationOpen"
					:title="$i18n( 'ipb-confirm' ).text()"
					primary-action-type="destructive"
					:primary-action-label=" $i18n( 'block-confirm-yes' ).text()"
					:default-action-label=" $i18n( 'block-confirm-no' ).text()"
					@confirm="doBlock"
				>
					<template #default>
						<!-- eslint-disable-next-line vue/no-v-html -->
						<p v-html="store.confirmationMessage"></p>
					</template>
				</confirmation-dialog>
				<hr class="mw-block-hr">
				<cdx-button
					action="default"
					data-test="cancel-edit-button"
					weight="primary"
					@click="onFormCancel"
				>
					{{ $i18n( 'block-cancel' ) }}
				</cdx-button>
				<cdx-button
					action="progressive"
					weight="primary"
					class="mw-block-submit"
					@click="onFormSubmission"
				>
					{{ $i18n( 'block-submit' ).text() }}
				</cdx-button>
			</div>
			<div v-else-if="shouldShowAddBlockButton">
				<cdx-button
					type="button"
					action="progressive"
					weight="primary"
					class="mw-block__create-button"
					@click="onCreateBlock"
				>
					{{ $i18n( 'block-create' ).text() }}
				</cdx-button>
			</div>
		</div>
	</cdx-field>
	<confirmation-dialog
		v-model:open="removalConfirmationOpen"
		:title="$i18n( 'block-removal-title' ).text()"
		primary-action-type="progressive"
		:primary-action-label=" $i18n( 'block-removal-confirm-yes' ).text()"
		:default-action-label=" $i18n( 'block-removal-confirm-no' ).text()"
		@confirm="doRemoveBlock"
	>
		<template #default>
			<cdx-field>
				<template #label>
					{{ $i18n( 'block-reason' ).text() }}
				</template>
				<cdx-text-input
					v-model="store.removalReason"
					name="wpRemovalReason"
					:placeholder="$i18n( 'block-removal-reason-placeholder' ).text()"
				></cdx-text-input>
			</cdx-field>
			<cdx-field>
				<cdx-checkbox
					v-model="store.watchUser"
				>
					{{ $i18n( 'ipbwatchuser' ) }}
				</cdx-checkbox>
			</cdx-field>
		</template>
	</confirmation-dialog>
</template>

<script>
const { computed, defineComponent, nextTick, onMounted, ref, watch } = require( 'vue' );
const { storeToRefs } = require( 'pinia' );
const { CdxButton, CdxTextInput, CdxCheckbox, CdxField, CdxMessage } = require( '@wikimedia/codex' );
const useBlockStore = require( './stores/block.js' );
const UserLookup = require( './components/UserLookup.vue' );
const BlockLog = require( './components/BlockLog.vue' );
const BlockTypeField = require( './components/BlockTypeField.vue' );
const ExpiryField = require( './components/ExpiryField.vue' );
const ReasonField = require( './components/ReasonField.vue' );
const BlockDetailsField = require( './components/BlockDetailsField.vue' );
const AdditionalDetailsField = require( './components/AdditionalDetailsField.vue' );
const ConfirmationDialog = require( './components/ConfirmationDialog.vue' );

/**
 * Top-level component for the Special:Block Vue application.
 */
module.exports = exports = defineComponent( {
	name: 'SpecialBlock',
	components: {
		UserLookup,
		BlockLog,
		BlockTypeField,
		ExpiryField,
		ReasonField,
		BlockDetailsField,
		AdditionalDetailsField,
		ConfirmationDialog,
		CdxButton,
		CdxTextInput,
		CdxCheckbox,
		CdxField,
		CdxMessage
	},
	setup() {
		const store = useBlockStore();
		const blockShowSuppressLog = mw.config.get( 'blockShowSuppressLog' ) || false;
		const canDeleteLogEntry = mw.config.get( 'blockCanDeleteLogEntry' ) || false;
		const { alreadyBlocked, formErrors, formSubmitted, formVisible, blockAdded, blockRemoved, enableMultiblocks } = storeToRefs( store );
		const messagesContainer = ref();
		const blockSavedMessage = ref( '' );
		// Value to use for BlockLog component keys, so they reload after saving.
		const submitCount = ref( 0 );
		const formHeaderText = computed( () => {
			if ( ( !store.enableMultiblocks && store.alreadyBlocked ) ||
				( store.enableMultiblocks && store.blockId )
			) {
				return mw.message( 'block-update' ).text();
			}
			return mw.message( 'block-create' ).text();
		} );

		const confirmationOpen = ref( false );
		const showBlockLogs = computed( () => ( store.targetUser && store.targetExists ) || store.blockId );
		const removalConfirmationOpen = ref( false );

		// TODO: Remove some time after deprecation
		// T382539: Check if we've been redirected from Special:Unblock
		const wasRedirected = mw.util.getParamValue( 'redirected' );

		let initialLoad = true;

		onMounted( () => {
			// Prevent the window from being closed as long as we have the form open
			mw.confirmCloseWindow( {
				test: function () {
					return formVisible.value;
				}
			} );

			// If we're editing or removing via an id URL parameter, check that the block exists.
			if ( store.blockId ) {
				loadFromId( store.blockId ).then( ( data ) => {
					if ( data && data.blocks.length ) {
						if ( mw.util.getParamValue( 'remove' ) === '1' ) {
							// Fire the remove click handler manually.
							onRemoveBlock( store.blockId );
						} else {
							// Load the block form content.
							const block = data.blocks[ 0 ];
							store.loadFromData( block, true );
							formVisible.value = true;
							scrollToForm();
						}
					} else {
						// If the block ID is invalid, show an error message.
						formErrors.value = [ mw.msg( 'block-invalid-id' ) ];
					}
				} );
			}
		} );

		/**
		 * Show the form for a new block.
		 */
		function onCreateBlock() {
			// On initial load, we want the preset values from the URL to be set.
			if ( initialLoad ) {
				initialLoad = false;
			} else {
				// Subsequent loads should reset the form to the established defaults.
				store.resetForm();
			}
			formVisible.value = true;
			scrollToForm();
		}

		/**
		 * Show the form for an existing block.
		 *
		 * @param {Object} blockData
		 */
		function onEditBlock( blockData ) {
			store.loadFromData( blockData, false );
			formVisible.value = true;
			scrollToForm();
			initialLoad = false;
		}

		/**
		 * Click handler for the 'remove' links, to open the removal confirmation dialog.
		 *
		 * @param {number} currentBlockId
		 */
		function onRemoveBlock( currentBlockId ) {
			formVisible.value = false;
			store.blockId = currentBlockId;
			store.removalReason = mw.config.get( 'blockRemovalReasonPreset' ) || '';
			store.watchUser = false;
			removalConfirmationOpen.value = true;
		}

		/**
		 * Handler for the primary action of the removal confirmation dialog.
		 */
		function doRemoveBlock() {
			store.doRemoveBlock()
				.then( () => {
					removalConfirmationOpen.value = false;
					submitCount.value++;
					blockRemoved.value = true;
					formErrors.value = [];
				} )
				.fail( ( _, errorObj ) => {
					formErrors.value = [ errorObj.error.info ];
				} )
				.always( () => {
					blockAdded.value = false;
					formSubmitted.value = false;
				} );
		}

		/**
		 * Animate scrolling to the form.
		 */
		function scrollToForm() {
			nextTick( () => {
				document.querySelector( '.mw-block__block-form' ).scrollIntoView( { behavior: 'smooth' } );
			} );
		}

		/**
		 * Handle form submission. If the form is invalid, show the browser's
		 * validation messages along with any custom validation messages, and
		 * scroll to the first error message to ensure the user sees it.
		 *
		 * If the form is valid, send the block request to the server,
		 * add the success message and scroll to it.
		 *
		 * @param {Event} event
		 */
		function onFormSubmission( event ) {
			event.preventDefault();
			formSubmitted.value = true;
			blockAdded.value = false;

			// checkValidity() executes browser form validation, which triggers automatic
			// validation states on applicable components (e.g. fields with `required` attr).
			if ( event.target.form.checkValidity() && store.expiry ) {
				if ( store.confirmationNeeded ) {
					confirmationOpen.value = true;
					return;
				}

				if ( store.blockId ) {
					blockSavedMessage.value = mw.message( 'block-updated-message' ).text();
				} else {
					blockSavedMessage.value = mw.message( 'block-added-message' ).text();
				}

				doBlock();
			} else {
				// nextTick() needed to ensure error messages are rendered before scrolling.
				nextTick( () => {
					// Currently, only the expiry field has custom validations.
					// Scrolling to `cdx-message--error` is merely future-proofing to
					// ensure the user sees the error message, wherever it may be.
					// Actual validation logic should live in the respective component.
					const firstError = document.querySelector( '.cdx-message--error' );
					if ( firstError ) {
						// Guard against there not being any parent fieldset.
						const firstErrorFieldset = firstError.closest( 'fieldset' );
						( firstErrorFieldset || firstError ).scrollIntoView( { behavior: 'smooth' } );
					}
					formSubmitted.value = false;
				} );
			}
		}

		/**
		 * Handle form cancel button.
		 *
		 * @param {Event} event
		 */
		function onFormCancel( event ) {
			event.preventDefault();
			store.resetForm();
			formVisible.value = false;
			nextTick( () => {
				messagesContainer.value.scrollIntoView( { behavior: 'smooth' } );
			} );
		}

		/**
		 * Load data for a given block.
		 *
		 * @param {string} id The block ID to load.
		 * @return {Promise<Object>} A promise that resolves to the block query response.
		 */
		function loadFromId( id ) {
			const params = {
				action: 'query',
				list: 'blocks',
				bkids: id,
				formatversion: 2,
				format: 'json',
				bkprop: 'id|user|by|timestamp|expiry|reason|range|flags|restrictions'
			};
			const api = new mw.Api();
			return api.get( params ).then( ( response ) => response.query );
		}

		/**
		 * Execute the block request, set the success state and form errors,
		 * and scroll to the messages container.
		 */
		function doBlock() {
			store.doBlock()
				.done( ( result ) => {
					// Set the target user to the user that was blocked.
					// This is primarily for the log entries when blocking a range.
					if ( result.block && result.block.user ) {
						store.targetUser = result.block.user;
					}
					blockAdded.value = true;
					formErrors.value = [];
					// Bump the submitCount (to re-render the logs) after scrolling
					// because the log tables may change the length of the page.
					submitCount.value++;
					// Hide the form if the block was successful.
					formVisible.value = false;
					// Reset the form so no block data leaks into the next block (T384822).
					store.resetForm( false, false );
				} )
				.fail( ( _, errorObj ) => {
					formErrors.value = errorObj.errors.map( ( e ) => e.html );
					blockAdded.value = false;
				} )
				.always( () => {
					formSubmitted.value = false;
					blockRemoved.value = false;
					messagesContainer.value.scrollIntoView( { behavior: 'smooth' } );
				} );
		}

		// We need to reset the form so no block data is set.
		watch( removalConfirmationOpen, ( newValue ) => {
			if ( !newValue ) {
				store.resetForm();
			}
		} );

		// Submit the form if form is visible and 'Enter' is pressed
		watch( formVisible, ( newValue ) => {
			if ( newValue ) {
				nextTick( () => {
					const blockForm = document.querySelector( '.mw-block__block-form' );
					blockForm.addEventListener( 'keypress', ( event ) => {
						if ( event.key === 'Enter' ) {
							onFormSubmission( event );
						}
					} );
				} );
			}
		} );

		// Show the 'Add block' button if:
		// * the target user exists AND EITHER
		//   * multiblocks is enabled, OR
		//   * multiblocks is disabled AND the user is not already blocked
		const shouldShowAddBlockButton = computed(
			() => store.targetExists && (
				store.enableMultiblocks || !alreadyBlocked.value
			)
		);

		return {
			store,
			messagesContainer,
			formErrors,
			blockAdded,
			blockRemoved,
			shouldShowAddBlockButton,
			submitCount,
			blockSavedMessage,
			formHeaderText,
			enableMultiblocks,
			blockShowSuppressLog,
			canDeleteLogEntry,
			confirmationOpen,
			removalConfirmationOpen,
			onCreateBlock,
			onEditBlock,
			onFormCancel,
			onFormSubmission,
			doBlock,
			onRemoveBlock,
			doRemoveBlock,
			showBlockLogs,
			formVisible,
			wasRedirected,
			mw
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

// HACK: The accordions within the form need to be full width.
.mw-htmlform-codex {
	max-width: unset;
}

// HACK: Set the max-width of the fields back to what they should be.
.cdx-field:not( .mw-block-fieldset ),
.mw-block-messages {
	max-width: @size-4000;
}

// HACK: CdxMessage doesn't support v-html, so we need an inner div,
// and apply the expected styling to the contents therein.
.mw-block-messages .cdx-message__content > div {
	> :first-child {
		margin-top: 0;
	}

	> :last-child {
		margin-bottom: 0;
	}
}

.mw-block-hideuser .cdx-checkbox__label .cdx-label__label__text {
	font-weight: @font-weight-bold;
}

.mw-block-hr {
	margin-top: @spacing-200;
}

.mw-block-confirm {
	font-weight: @font-weight-normal;
}

.cdx-button.mw-block-submit,
.cdx-button.mw-block__create-button {
	margin-top: @spacing-100;
}

// Lower opacity and remove pointer events from accordions while the disabled state is active.
// This to prevent users from going to click on something and then having it immediately
// disappear, or worse, change to a different link that they click by mistake.
.mw-block-fieldset[ disabled ] .cdx-accordion {
	opacity: @opacity-low;
	pointer-events: none;
}

.mw-block-fieldset {
	min-width: unset;

	// Unset font-size until T377902 is resolved.
	font-size: unset;

	legend {
		// Match font-size of accordion labels. T383921.
		font-size: @font-size-medium;
	}

	.cdx-field:first-child {
		// Override Codex's lack of top margin for the first fieldset,
		// because here it appears directly below an accordion border.
		margin-top: @spacing-100;
	}

	legend .cdx-label__label,
	legend .cdx-label__description {
		margin-bottom: @spacing-50;
	}

	// We need :is-fieldset="true" on the outer <fieldset> for disabled state to propagate
	// to children. :is-fieldset="true" forces a <legend> which we don't want.
	& > legend:first-of-type {
		display: none;
	}
}
</style>
