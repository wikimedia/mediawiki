<template>
	<div>
		<user-lookup v-model="targetUser"></user-lookup>
		<target-active-blocks></target-active-blocks>
		<target-block-log></target-block-log>
		<block-type-field></block-type-field>
		<expiration-field></expiration-field>
		<reason-field></reason-field>
		<block-details-field
			v-model="blockDetailsSelected"
			:checkboxes="blockDetailsOptions"
			:label="$i18n( 'block-details' ).text()"
			:description="$i18n( 'block-details-description' ).text()"
		></block-details-field>
		<cdx-button
			action="progressive"
			weight="primary"
			@click="saveBlock"
		>
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
		const form = document.querySelector( '.mw-htmlform' );
		const targetUser = ref( '' );
		const blockAllosUTEdit = mw.config.get( 'blockAllosUTEdit' ) || false;
		const blockEmailBan = mw.config.get( 'blockAllosEmailBan' ) || false;
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

		if ( blockAllosUTEdit ) {
			blockDetailsOptions.push( {
				label: mw.message( 'ipb-disableusertalk' ),
				value: 'wpDisableUTEdit'
			} );
		}

		function saveBlock() {
			form.submit();
		}
		return {
			targetUser,
			saveBlock,
			blockDetailsSelected,
			blockDetailsOptions
		};
	}
} );
</script>
