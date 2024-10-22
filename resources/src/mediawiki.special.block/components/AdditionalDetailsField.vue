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
			input-value="wpHideName"
			class="mw-block-hideuser"
		>
			{{ $i18n( 'ipbhidename' ) }}
		</cdx-checkbox>
		<cdx-checkbox
			v-model="watch"
			input-value="wpWatch"
		>
			{{ $i18n( 'ipbwatchuser' ) }}
		</cdx-checkbox>
		<cdx-checkbox
			v-if="hardBlockVisible"
			v-model="hardBlock"
			input-value="wpHardBlock"
		>
			{{ $i18n( 'ipb-hardblock' ) }}
		</cdx-checkbox>
		<template #label>
			{{ $i18n( 'block-options' ).text() }}
			<span class="cdx-label__label__optional-flag">
				{{ $i18n( 'htmlform-optional-flag' ).text() }}
			</span>
		</template>
		<template #description>
			{{ $i18n( 'block-options-description' ).text() }}
		</template>
	</cdx-field>
</template>

<script>
const { defineComponent } = require( 'vue' );
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

// @vue/component
module.exports = exports = defineComponent( {
	name: 'AdditionalDetailsField',
	components: { CdxCheckbox, CdxField },
	setup() {
		const store = useBlockStore();
		const {
			autoBlock,
			autoBlockExpiry,
			autoBlockVisible,
			hideName,
			hideNameVisible,
			watch,
			hardBlock,
			hardBlockVisible
		} = storeToRefs( store );
		return {
			autoBlock,
			autoBlockExpiry,
			autoBlockVisible,
			hideName,
			hideNameVisible,
			watch,
			hardBlock,
			hardBlockVisible
		};
	}
} );
</script>
