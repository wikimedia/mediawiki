<template>
	<div>
		<user-lookup v-model="targetUser"></user-lookup>
		<target-active-blocks></target-active-blocks>
		<target-block-log></target-block-log>
		<block-type-field
			v-model="blockPartialOptionsSelected"
			:partial-block-options="blockPartialOptions"
		></block-type-field>
		<expiration-field></expiration-field>
		<reason-field></reason-field>
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
		<cdx-button
			action="progressive"
			weight="primary"
			@click="handleSubmit">
			{{ $i18n( 'block-save' ).text() }}
		</cdx-button>
	</div>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const { CdxButton } = require( '@wikimedia/codex' );
const UserLookup = require( './components/UserLookup.vue' );
const TargetActiveBlocks = require( './components/TargetActiveBlocks.vue' );
const TargetBlockLog = require( './components/TargetBlockLog.vue' );
const BlockTypeField = require( './components/BlockTypeField.vue' );
const ExpirationField = require( './components/ExpirationField.vue' );
const ReasonField = require( './components/ReasonField.vue' );
const BlockDetailsField = require( './components/BlockDetailsOptions.vue' );

// @vue/component
module.exports = defineComponent( {
	name: 'SpecialBlock',
	components: {
		UserLookup,
		TargetActiveBlocks,
		TargetBlockLog,
		BlockTypeField,
		ExpirationField,
		ReasonField,
		BlockDetailsField,
		CdxButton
	},
	setup() {
		const targetUser = ref( '' );

		const blockPartialOptions = mw.config.get( 'partialBlockActionOptions' ) ?
			Object.keys( mw.config.get( 'partialBlockActionOptions' ) ).map(
				// Messages that can be used here:
				// * ipb-action-upload
				// * ipb-action-move
				// * ipb-action-create
				( key ) => Object( { label: mw.message( key ).text(), value: key } ) ) :
			[];
		const blockPartialOptionsSelected = ref( [ 'ipb-action-create' ] );
		const blockAllowsUTEdit = mw.config.get( 'blockAllowsUTEdit' ) || false;
		const blockEmailBan = mw.config.get( 'blockAllowsEmailBan' ) || false;
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

		const additionalDetailsSelected = ref( [] );
		const additionalDetailsOptions = [
			{
				label: mw.message( 'ipbenableautoblock' ),
				value: 'wpAutoBlock',
				disabled: false
			},
			{
				label: mw.message( 'ipbwatchuser' ),
				value: 'wpWatch',
				disabled: false
			},
			{
				label: mw.message( 'ipb-hardblock' ),
				value: 'wpHardBlock',
				disabled: false
			}
		];

		function handleSubmit( event ) {
			event.preventDefault();

			// TODO: Implement validation
			block();
		}

		/*
		 * Send block.
		 *
		 * @return {jQuery.Promise}
		 */
		function block() {

			const params = {
				action: 'block',
				format: 'json',
				user: targetUser.value,
				expiry: '2025-02-25T07:27:50Z',
				reason: 'API Test'
			};

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
		return {
			targetUser,
			handleSubmit,
			blockDetailsOptions,
			blockDetailsSelected,
			additionalDetailsOptions,
			additionalDetailsSelected,
			blockPartialOptions,
			blockPartialOptionsSelected
		};
	}
} );
</script>
