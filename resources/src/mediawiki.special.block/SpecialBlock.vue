<template>
	<!-- @todo Remove after it's no longer new. -->
	<cdx-message v-if="blockEnableMultiblocks" allow-user-dismiss>
		{{ $i18n( 'block-multiblocks-new-feature' ) }}
	</cdx-message>
	<cdx-field
		class="mw-block-fieldset"
		:is-fieldset="true"
		:disabled="store.formDisabled"
	>
		<div ref="messagesContainer" class="mw-block-messages">
			<cdx-message
				v-if="success"
				type="success"
				:allow-user-dismiss="true"
				class="mw-block-success"
			>
				<p><strong>{{ $i18n( 'blockipsuccesssub' ) }}</strong></p>
				<!-- eslint-disable-next-line vue/no-v-html -->
				<p v-html="$i18n( 'block-success', store.targetUser ).parse()"></p>
			</cdx-message>
			<cdx-message
				v-for="( formError, index ) in formErrors"
				:key="index"
				type="error"
				class="mw-block-error"
				inline
			>
				<!-- eslint-disable-next-line vue/no-v-html -->
				<div v-html="formError"></div>
			</cdx-message>
		</div>
		<user-lookup
			v-model="store.targetUser"
		></user-lookup>

		<div v-if="store.targetUser">
			<block-log
				:key="`${submitCount}-active`"
				:open="success"
				:can-delete-log-entry="false"
				block-log-type="active"
				@create-block="onCreateBlock"
				@edit-block="onEditBlock"
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
				<block-type-field></block-type-field>
				<expiry-field></expiry-field>
				<reason-field
					v-model:selected="store.reason"
					v-model:other="store.reasonOther"
				></reason-field>
				<block-details-field></block-details-field>
				<additional-details-field></additional-details-field>
				<confirmation-dialog
					v-if="store.confirmationNeeded"
					v-model:open="confirmationOpen"
					:title="$i18n( 'ipb-confirm' ).text()"
					@confirm="doBlock"
				>
					<template #default>
						<!-- eslint-disable-next-line vue/no-v-html -->
						<p v-html="store.confirmationMessage"></p>
					</template>
				</confirmation-dialog>
				<hr class="mw-block-hr">
				<cdx-button
					action="destructive"
					weight="primary"
					class="mw-block-submit"
					@click="onFormSubmission"
				>
					{{ submitButtonMessage }}
				</cdx-button>
			</div>
		</div>
	</cdx-field>
</template>

<script>
const { computed, defineComponent, nextTick, ref } = require( 'vue' );
const { storeToRefs } = require( 'pinia' );
const { CdxButton, CdxField, CdxMessage } = require( '@wikimedia/codex' );
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
		CdxField,
		CdxMessage
	},
	setup() {
		const store = useBlockStore();
		const blockEnableMultiblocks = mw.config.get( 'blockEnableMultiblocks' ) || false;
		const blockShowSuppressLog = mw.config.get( 'blockShowSuppressLog' ) || false;
		const canDeleteLogEntry = mw.config.get( 'canDeleteLogEntry' ) || false;
		const { formErrors, formSubmitted, formVisible, success } = storeToRefs( store );
		const messagesContainer = ref();
		// Value to use for BlockLog component keys, so they reload after saving.
		const submitCount = ref( 0 );
		// eslint-disable-next-line arrow-body-style
		const submitButtonMessage = computed( () => {
			return mw.message( store.alreadyBlocked ? 'block-update' : 'ipbsubmit' ).text();
		} );
		const confirmationOpen = ref( false );
		let initialLoad = true;

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
			store.loadFromData( blockData );
			formVisible.value = true;
			scrollToForm();
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
			success.value = false;

			// checkValidity() executes browser form validation, which triggers automatic
			// validation states on applicable components (e.g. fields with `required` attr).
			if ( event.target.form.checkValidity() && store.expiry ) {
				if ( store.confirmationNeeded ) {
					confirmationOpen.value = true;
					return;
				}
				doBlock();
				formVisible.value = false;
			} else {
				// nextTick() needed to ensure error messages are rendered before scrolling.
				nextTick( () => {
					// Currently, only the expiry field has custom validations.
					// Scrolling to `cdx-message--error` is merely future-proofing to
					// ensure the user sees the error message, wherever it may be.
					// Actual validation logic should live in the respective component.
					document.querySelector( '.cdx-message--error' )
						.scrollIntoView( { behavior: 'smooth' } );
					formSubmitted.value = false;
				} );
			}
		}

		/**
		 * Execute the block request, set the success state and form errors,
		 * and scroll to the messages container.
		 */
		function doBlock() {
			store.doBlock()
				.done( () => {
					success.value = true;
					formErrors.value = [];
					// Bump the submitCount (to re-render the logs) after scrolling
					// because the log tables may change the length of the page.
					submitCount.value++;
				} )
				.fail( ( _, errorObj ) => {
					formErrors.value = [ errorObj.error.info ];
					success.value = false;
				} )
				.always( () => {
					formSubmitted.value = false;
					messagesContainer.value.scrollIntoView( { behavior: 'smooth' } );
				} );
		}

		return {
			store,
			messagesContainer,
			formErrors,
			success,
			submitCount,
			submitButtonMessage,
			blockEnableMultiblocks,
			blockShowSuppressLog,
			canDeleteLogEntry,
			confirmationOpen,
			formVisible,
			onCreateBlock,
			onEditBlock,
			onFormSubmission,
			doBlock
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
.mw-block-messages .cdx-message__content > div > :first-child {
	margin-top: 0;
}

.mw-block-hideuser .cdx-checkbox__label .cdx-label__label__text {
	font-weight: @font-weight-bold;
}

.mw-block-hr {
	margin-top: @spacing-200;
}

.mw-block-submit.cdx-button {
	margin-top: @spacing-100;
}

.mw-block-error {
	margin-left: @spacing-75;
}

.mw-block-confirm {
	font-weight: @font-weight-normal;
}

// Hide the log and convenience links showing at the bottom of page.
.mw-ipb-conveniencelinks,
.mw-warning-with-logexcerpt {
	display: none;
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
}
</style>
