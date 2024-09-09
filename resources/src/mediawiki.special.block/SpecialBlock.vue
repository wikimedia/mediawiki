<template>
	<cdx-message
		v-if="targetUser && alreadyBlocked"
		type="error"
		inline
	>
		{{ $i18n( 'ipb-needreblock', targetUser ).text() }}
	</cdx-message>
	<user-lookup
		v-model="targetUser"
		@input="alreadyBlocked = false"
	></user-lookup>
	<target-active-blocks
		v-if="blockEnableMultiblocks"
		:target-user="targetUser"
	></target-active-blocks>
	<target-block-log
		:target-user="targetUser"
	></target-block-log>
	<block-type-field
		v-model="blockPartialOptionsSelected"
		:partial-block-options="blockPartialOptions"
	></block-type-field>
	<expiry-field v-model="expiry"></expiry-field>
	<reason-field
		v-model:selected="reasonSelected"
		v-model:other="reasonOther"
	></reason-field>
	<block-details-field
		v-model="blockDetailsSelected"
		:checkboxes="blockDetailsOptions"
		:label="$i18n( 'block-details' ).text()"
		:description="$i18n( 'block-details-description' ).text()"
	></block-details-field>
	<block-details-field
		v-model="additionalDetailsSelected"
		:checkboxes="additionalDetailsOptions"
		:label="$i18n( 'block-options' ).text()"
		:description="$i18n( 'block-options-description' ).text()"
	></block-details-field>
	<hr class="mw-block-hr">
	<cdx-button
		action="destructive"
		weight="primary"
		@click="handleSubmit"
		class="mw-block-submit"
	>
		{{ submitBtnMsg }}
	</cdx-button>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const { CdxButton, CdxMessage } = require( '@wikimedia/codex' );
const UserLookup = require( './components/UserLookup.vue' );
const TargetActiveBlocks = require( './components/TargetActiveBlocks.vue' );
const TargetBlockLog = require( './components/TargetBlockLog.vue' );
const BlockTypeField = require( './components/BlockTypeField.vue' );
const ExpiryField = require( './components/ExpiryField.vue' );
const ReasonField = require( './components/ReasonField.vue' );
const BlockDetailsField = require( './components/BlockDetailsOptions.vue' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'SpecialBlock',
	components: {
		UserLookup,
		TargetActiveBlocks,
		TargetBlockLog,
		BlockTypeField,
		ExpiryField,
		ReasonField,
		BlockDetailsField,
		CdxButton,
		CdxMessage
	},
	setup() {
		const targetUser = ref( mw.config.get( 'blockTargetUser' ) );
		const expiry = ref( {} );
		const blockEnableMultiblocks = mw.config.get( 'blockEnableMultiblocks' ) || false;
		const blockPartialOptions = mw.config.get( 'partialBlockActionOptions' ) ?
			Object.keys( mw.config.get( 'partialBlockActionOptions' ) ).map(
				// Messages that can be used here:
				// * ipb-action-upload
				// * ipb-action-move
				// * ipb-action-create
				( key ) => Object( { label: mw.message( key ).text(), value: key } ) ) :
			[];
		const blockPartialOptionsSelected = ref( [ 'ipb-action-create' ] );
		const reasonSelected = ref( 'other' );
		const reasonOther = ref( '' );
		const blockAllowsUTEdit = mw.config.get( 'blockAllowsUTEdit' ) || false;
		const blockEmailBan = mw.config.get( 'blockAllowsEmailBan' ) || false;
		const blockAutoblockExpiry = mw.config.get( 'blockAutoblockExpiry' );
		const blockHideUser = mw.config.get( 'blockHideUser' ) || false;
		const blockDetailsSelected = ref( [] );
		const blockDetailsOptions = [
			{
				label: mw.message( 'ipbcreateaccount' ),
				value: 'wpCreateAccount'
			}
		];

		if ( blockEmailBan ) {
			blockDetailsOptions.push( {
				label: mw.message( 'ipbemailban' ),
				value: 'wpDisableEmail'
			} );
		}

		if ( blockAllowsUTEdit ) {
			blockDetailsOptions.push( {
				label: mw.message( 'ipb-disableusertalk' ),
				value: 'wpDisableUTEdit'
			} );
		}

		const additionalDetailsSelected = ref( [ 'wpAutoBlock' ] );
		const additionalDetailsOptions = [ {
			label: mw.message( 'ipbenableautoblock', blockAutoblockExpiry ),
			value: 'wpAutoBlock',
			disabled: false
		} ];

		if ( blockHideUser ) {
			additionalDetailsOptions.push( {
				label: mw.message( 'ipbhidename' ),
				value: 'wpHideName'
			} );
		}

		additionalDetailsOptions.push( {
			label: mw.message( 'ipbwatchuser' ),
			value: 'wpWatch',
			disabled: false
		} );

		additionalDetailsOptions.push( {
			label: mw.message( 'ipb-hardblock' ),
			value: 'wpHardBlock',
			disabled: false
		} );

		function handleSubmit( event ) {
			event.preventDefault();

			// TODO: Implement validation

			block();
		}

		/**
		 * Send block.
		 *
		 * @return {jQuery.Promise}
		 */
		function block() {
			const params = {
				action: 'block',
				format: 'json',
				user: targetUser.value,
				expiry: expiry.value.value
			};

			// Reason selected concatenated with 'Other' field
			if ( reasonSelected.value === 'other' ) {
				params.reason = reasonOther.value;
			} else {
				params.reason = reasonSelected.value + (
					reasonOther.value ? mw.msg( 'colon-separator' ) + reasonOther.value : ''
				);
			}

			if ( blockPartialOptions ) {
				const actionRestrictions = [];
				params.partial = 1;
				if ( blockPartialOptionsSelected.value.indexOf( 'ipb-action-upload' ) !== -1 ) {
					actionRestrictions.push( 'upload' );
				}
				if ( blockPartialOptionsSelected.value.indexOf( 'ipb-action-move' ) !== -1 ) {
					actionRestrictions.push( 'move' );
				}
				if ( blockPartialOptionsSelected.value.indexOf( 'ipb-action-create' ) !== -1 ) {
					actionRestrictions.push( 'create' );
				}
				params.actionRestrictions = actionRestrictions.join( '|' );
			}

			if ( blockDetailsSelected.value.indexOf( 'wpCreateAccount' ) !== -1 ) {
				params.nocreate = 1;
			}

			if ( blockDetailsSelected.value.indexOf( 'wpDisableEmail' ) !== -1 ) {
				params.noemail = 1;
			}

			if ( blockDetailsSelected.value.indexOf( 'wpDisableUTEdit' ) !== -1 ) {
				params.allowusertalk = 1;
			}

			if ( additionalDetailsSelected.value.indexOf( 'wpAutoBlock' ) !== -1 ) {
				params.autoblock = 1;
			}

			if ( additionalDetailsSelected.value.indexOf( 'wpHideName' ) !== -1 ) {
				params.hidename = 1;
			}

			if ( additionalDetailsSelected.value.indexOf( 'wpWatch' ) !== -1 ) {
				params.watchuser = 1;
			}

			if ( additionalDetailsSelected.value.indexOf( 'wpHardBlock' ) !== -1 ) {
				params.nocreate = 1;
			}

			const api = new mw.Api();

			return api.postWithToken( 'csrf', params )
				.done( () => {

				} );
		}

		const alreadyBlocked = mw.config.get( 'blockAlreadyBlocked' );

		return {
			targetUser,
			expiry,
			alreadyBlocked,
			submitBtnMsg: mw.message( alreadyBlocked ? 'ipb-change-block' : 'ipbsubmit' ).text(),
			handleSubmit,
			blockDetailsOptions,
			blockDetailsSelected,
			reasonOther,
			reasonSelected,
			additionalDetailsOptions,
			additionalDetailsSelected,
			blockPartialOptions,
			blockPartialOptionsSelected,
			blockEnableMultiblocks
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
.cdx-field {
	max-width: @size-4000;
}

.mw-block-hr {
	margin-top: @spacing-200;
}

.mw-block-submit.cdx-button {
	margin-top: @spacing-100;
}
</style>
