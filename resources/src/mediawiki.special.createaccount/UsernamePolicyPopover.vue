<template>
	<cdx-popover
		v-model:open="isPopoverOpen"
		:anchor="anchor"
		class="mw-createaccount-username-policy-popover"
		:title="popoverMessages.title"
		:use-close-button="true"
		:use-bottom-sheet="true"
		:show-backdrop="true"
		placement="bottom"
	>
		<!-- eslint-disable vue/no-v-html -->
		<div
			ref="bulletsElement"
			class="mw-createaccount-username-policy-popover-bullets"
			v-html="popoverMessages.bulletsHtml"
		></div>
		<!-- eslint-enable vue/no-v-html -->
	</cdx-popover>
</template>

<script>
const { defineComponent, ref, onMounted, onUnmounted, onUpdated } = require( 'vue' );
const { CdxPopover } = require( '@wikimedia/codex' );
const EXPERIMENT_NAME = 'we-1-8-account-creation-form-v2';

module.exports = defineComponent( {
	name: 'UsernamePolicyPopover',
	components: {
		CdxPopover
	},
	props: {
		/**
		 * The in-form control that opens the popover (e.g. "Choose carefully").
		 */
		triggerElement: {
			type: Object,
			required: true
		}
	},
	setup( props ) {
		const isPopoverOpen = ref( false );
		const anchor = ref( props.triggerElement );
		const bulletsElement = ref( null );
		const pack = mw.config.get( 'wgCreateAccountUsernamePolicyPopoverMsgs' );
		const popoverMessages = pack && typeof pack === 'object' ? pack : {};

		function applyPolicyLinkAttrs() {
			if ( !bulletsElement.value ) {
				return;
			}
			Array.prototype.forEach.call(
				bulletsElement.value.querySelectorAll( 'a' ),
				( link ) => {
					link.target = '_blank';
					link.rel = 'noopener noreferrer';
				}
			);
		}

		function onTriggerClick( ev ) {
			ev.preventDefault();
			isPopoverOpen.value = true;
		}

		onMounted( () => {
			isPopoverOpen.value = true;
			props.triggerElement.addEventListener( 'click', onTriggerClick, true );
			applyPolicyLinkAttrs();

			mw.loader.using( [ 'ext.testKitchen', 'ext.wikimediaEvents.testKitchen' ] ).then( () => {
				const { ClickThroughRateInstrument, UrlEnrolledExperiment } = require( 'ext.wikimediaEvents.testKitchen' );
				const experiment = UrlEnrolledExperiment.getExperimentFromQuery( EXPERIMENT_NAME );
				ClickThroughRateInstrument.start( '.mw-createaccount-username-policy-popover-list > li:first-of-type > a:first-of-type',
					'username policy popover informational link - offensive', experiment );
				ClickThroughRateInstrument.start( '.mw-createaccount-username-policy-popover-list > li:first-of-type > a:nth-of-type(2)',
					'username policy popover informational link - misleading', experiment );
				ClickThroughRateInstrument.start( '.mw-createaccount-username-policy-popover-list > li:first-of-type > a:nth-of-type(3)',
					'username policy popover informational link - promotional', experiment );

				ClickThroughRateInstrument.start( '.mw-createaccount-username-policy-popover-list > li:nth-of-type(2) > a:first-of-type',
					'username policy popover informational link - not an organization', experiment );

				ClickThroughRateInstrument.start( '.mw-createaccount-username-policy-popover-list > li:nth-of-type(3) > a:first-of-type',
					'username policy popover informational link - real name', experiment );
			} );
		} );

		onUpdated( () => {
			applyPolicyLinkAttrs();
		} );

		onUnmounted( () => {
			props.triggerElement.removeEventListener( 'click', onTriggerClick, true );
		} );

		return {
			anchor,
			bulletsElement,
			isPopoverOpen,
			popoverMessages
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-createaccount-username-policy-popover-list {
	margin: 0 0 @spacing-75 0;
	padding-inline-start: @spacing-150;
}
</style>
