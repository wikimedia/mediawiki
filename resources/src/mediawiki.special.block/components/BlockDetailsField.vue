<template>
	<cdx-field :is-fieldset="true">
		<cdx-checkbox
			v-model="createAccount"
			input-value="wpCreateAccount"
		>
			{{ $i18n( 'ipbcreateaccount' ) }}
		</cdx-checkbox>
		<cdx-checkbox
			v-if="disableEmailVisible"
			v-model="disableEmail"
			input-value="wpDisableEmail"
		>
			{{ $i18n( 'ipbemailban' ) }}
		</cdx-checkbox>
		<cdx-checkbox
			v-if="disableUTEditVisible"
			v-model="disableUTEdit"
			input-value="wpDisableUTEdit"
		>
			{{ $i18n( 'ipb-disableusertalk' ) }}
		</cdx-checkbox>
		<template #label>
			{{ $i18n( 'block-details' ).text() }}
			<span class="cdx-label__label__optional-flag">
				{{ $i18n( 'htmlform-optional-flag' ).text() }}
			</span>
		</template>
		<template #description>
			{{ $i18n( 'block-details-description' ).text() }}
		</template>
	</cdx-field>
</template>

<script>
const { defineComponent } = require( 'vue' );
const { storeToRefs } = require( 'pinia' );
const { CdxCheckbox, CdxField } = require( '@wikimedia/codex' );
const useBlockStore = require( '../stores/block.js' );

module.exports = exports = defineComponent( {
	name: 'BlockDetailsField',
	components: { CdxCheckbox, CdxField },
	setup() {
		const store = useBlockStore();
		const { createAccount, disableEmail, disableUTEdit, disableUTEditVisible } = storeToRefs( store );
		const disableEmailVisible = mw.config.get( 'blockDisableEmailVisible' ) || false;
		return {
			createAccount,
			disableEmail,
			disableEmailVisible,
			disableUTEdit,
			disableUTEditVisible
		};
	}
} );
</script>
