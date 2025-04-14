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
const { CdxDialog, ModalAction, PrimaryModalAction, useModelWrapper } = require( '@wikimedia/codex' );

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
		},
		primaryActionType: {
			type: String,
			required: true
		},
		primaryActionLabel: {
			type: String,
			required: true
		},
		defaultActionLabel: {
			type: String,
			required: true
		}
	},
	emits: [ 'update:open', 'confirm' ],
	setup( props, { emit } ) {
		const wrappedOpen = useModelWrapper( toRef( props, 'open' ), emit, 'update:open' );

		/** @type {PrimaryModalAction} */
		const primaryAction = {
			label: props.primaryActionLabel,
			actionType: props.primaryActionType
		};

		/** @type {ModalAction} */
		const defaultAction = {
			label: props.defaultActionLabel,
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
