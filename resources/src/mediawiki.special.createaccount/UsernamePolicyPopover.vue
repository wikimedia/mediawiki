<template>
	<cdx-popover
		v-model:open="isPopoverOpen"
		:anchor="anchor"
		class="mw-createaccount-username-policy-popover"
		:title="$i18n( 'createacct-username-policy-popover-title' ).text()"
		:use-close-button="true"
		:use-bottom-sheet="true"
		:show-backdrop="true"
		placement="bottom"
	>
		<ul>
			<li v-i18n-html:createacct-username-policy-popover-bullet1></li>
			<li v-i18n-html:createacct-username-policy-popover-bullet2></li>
			<li v-i18n-html:createacct-username-policy-popover-bullet3></li>
		</ul>
		<a
			v-if="policyPageUrl"
			:href="policyPageUrl"
			target="_blank"
			rel="noopener noreferrer"
			class="mw-createaccount-username-policy-popover-link"
		>
			{{ $i18n( 'createacct-username-policy-link' ).text() }}
		</a>
	</cdx-popover>
</template>

<script>
const { defineComponent, ref, onMounted, onUnmounted } = require( 'vue' );
const { CdxPopover } = require( '@wikimedia/codex' );

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
		const policyPageUrl = mw.config.get( 'wgCreateAccountUsernamePolicyUrl' );

		function onTriggerClick( ev ) {
			ev.preventDefault();
			isPopoverOpen.value = true;
		}

		onMounted( () => {
			isPopoverOpen.value = true;
			props.triggerElement.addEventListener( 'click', onTriggerClick, true );
		} );

		onUnmounted( () => {
			props.triggerElement.removeEventListener( 'click', onTriggerClick, true );
		} );

		return {
			anchor,
			isPopoverOpen,
			policyPageUrl
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-createaccount-username-policy-popover-link {
	display: inline-block;
	margin-top: @spacing-100;
}
</style>
