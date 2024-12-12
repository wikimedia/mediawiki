<template>
	<cdx-field :is-fieldset="true">
		<cdx-checkbox
			v-if="autoBlockVisible"
			v-model="autoBlock"
			input-value="wpAutoBlock"
		>
			{{ $i18n( 'ipbenableautoblock', autoBlockExpiry ) }}
		</cdx-checkbox>
		<cdx-checkbox
			v-if="hideNameVisible"
			v-model="hideName"
			input-value="wpHideUser"
			class="mw-block-hideuser"
		>
			{{ $i18n( 'ipbhidename' ) }}
		</cdx-checkbox>
		<cdx-checkbox
			v-model="watchUser"
			input-value="wpWatch"
		>
			{{ $i18n( 'ipbwatchuser' ) }}
		</cdx-checkbox>
		<cdx-checkbox
			v-if="hardBlockVisible"
			v-model="hardBlock"
			input-value="wpHardBlock"
			class="mw-block-hardblock"
		>
			{{ $i18n( 'ipb-hardblock' ) }}
		</cdx-checkbox>
		<template #label>
			{{ $i18n( 'block-options' ).text() }}
			<span class="cdx-label__label__optional-flag">
				{{ $i18n( 'htmlform-optional-flag' ).text() }}
			</span>
		</template>
	</cdx-field>
</template>

<script>
const { computed, defineComponent } = require( 'vue' );
const { storeToRefs } = require( 'pinia' );
const { CdxCheckbox, CdxField } = require( '@wikimedia/codex' );
const useBlockStore = require( '../stores/block.js' );

/**
 * The 'additional details' (aka 'block options') section contains:
 *  - AutoBlock (if the target is not an IP)
 *  - HideUser/HideName (if the user has the hideuser right, and the target is not an IP)
 *  - Watch (if the user is logged in)
 *  - HardBlock (if the target is an IP)
 */

module.exports = exports = defineComponent( {
	name: 'AdditionalDetailsField',
	components: { CdxCheckbox, CdxField },
	setup() {
		const store = useBlockStore();
		const {
			autoBlock,
			hideName,
			hideNameVisible,
			watchUser,
			hardBlock
		} = storeToRefs( store );
		const autoBlockExpiry = mw.config.get( 'blockAutoblockExpiry' ) || '';
		const autoBlockVisible = computed(
			() => !mw.util.isIPAddress( store.targetUser, true )
		);
		const hardBlockVisible = computed(
			() => mw.util.isIPAddress( store.targetUser, true ) || false
		);

		return {
			autoBlock,
			autoBlockExpiry,
			autoBlockVisible,
			hideName,
			hideNameVisible,
			watchUser,
			hardBlock,
			hardBlockVisible
		};
	}
} );
</script>
