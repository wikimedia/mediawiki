<template>
	<div>
		<user-lookup v-model="targetUser"></user-lookup>
		<target-active-blocks></target-active-blocks>
		<target-block-log></target-block-log>
		<block-type-field></block-type-field>
		<expiration-field></expiration-field>
		<reason-field></reason-field>
		<cdx-button
			action="progressive"
			weight="primary"
			@click="submit"
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
		CdxButton
	},
	setup() {
		const targetUser = ref( '' );

		return {
			targetUser
		};
	},
	methods: {
		submit() {}
	}
} );
</script>
