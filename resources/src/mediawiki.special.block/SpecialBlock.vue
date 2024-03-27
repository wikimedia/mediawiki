<template>
	<div>
		<user-lookup v-model="targetUser"></user-lookup>
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

// @vue/component
module.exports = defineComponent( {
	name: 'SpecialBlock',
	components: {
		UserLookup,
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
