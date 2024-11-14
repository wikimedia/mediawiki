<template>
	<cdx-dialog
		v-model:open="wrappedOpen"
		class="mw-block-confirm"
		:use-close-button="true"
		:primary-action="primaryAction"
		:default-action="defaultAction"
		:title="title"
		@primary="onConfirm"
		@default="wrappedOpen = false"
	>
		<slot></slot>
	</cdx-dialog>
</template>

<script>
const { defineComponent, toRef } = require( 'vue' );
const { CdxDialog, DialogAction, PrimaryDialogAction, useModelWrapper } = require( '@wikimedia/codex' );

/**
 * Confirmation dialog component for use by Special:Block.
 *
 * @todo Abstract for general use in MediaWiki (T375220)
 */
module.exports = exports = defineComponent( {
	name: 'ConfirmationDialog',
	components: {
		CdxDialog
	},
	props: {
		// eslint-disable-next-line vue/no-unused-properties
		open: {
			type: Boolean,
			default: false
		},
		title: {
			type: String,
			required: true
		}
	},
	emits: [ 'update:open', 'confirm' ],
	setup( props, { emit } ) {
		const wrappedOpen = useModelWrapper( toRef( props, 'open' ), emit, 'update:open' );

		/** @type {PrimaryDialogAction} */
		const primaryAction = {
			label: mw.msg( 'block-confirm-yes' ),
			actionType: 'destructive'
		};

		/** @type {DialogAction} */
		const defaultAction = {
			label: mw.msg( 'block-confirm-no' ),
			actionType: 'default'
		};

		function onConfirm() {
			wrappedOpen.value = false;
			emit( 'confirm' );
		}

		return {
			wrappedOpen,
			primaryAction,
			defaultAction,
			onConfirm
		};
	}
} );
</script>
