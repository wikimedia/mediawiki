<template>
	<!-- This dialog can't be closed, either by the user or programatically, because we are not using v-model:open -->
	<!-- It can only be "closed" by unmounting the app -->
	<cdx-dialog
		class="mw-auth-message-dialog"
		ref="dialog"
		:open="true"
		:fixed-height="bodyHeight"
	>
		<template #header>
			<!-- Custom header slot in order to completely remove the nonfunctional icon-only close button -->
			<!-- The title is like in a standard Codex dialog -->
			<div class="cdx-dialog__header__title-group">
				<h2 class="cdx-dialog__header__title">
					{{ $i18n( 'userlogin-authpopup-loggingin-title' ) }}
				</h2>
			</div>
		</template>

		<div>
			{{ $i18n( 'userlogin-authpopup-loggingin-body-popup' ) }}
		</div>

		<cdx-progress-indicator
			aria-hidden="true"
		></cdx-progress-indicator>

		<template #footer>
			<!-- Custom footer slot in order to add a normal link next to the action buttons -->
			<!-- The action buttons are like in a standard Codex dialog -->
			<div class="cdx-dialog__footer__actions">
				<cdx-button
					weight="primary"
					action="progressive"
					@click="$emit( 'retry' )"
				>
					{{ $i18n( 'userlogin-authpopup-retry' ) }}
				</cdx-button>

				<cdx-button
					weight="primary"
					@click="$emit( 'cancel' )"
				>
					{{ $i18n( 'userlogin-authpopup-cancel' ) }}
				</cdx-button>

				<a
					v-if="fallbackUrl"
					:href="fallbackUrl"
					target="_blank"
					@click="$emit( 'fallback' )"
				>
					{{ $i18n( 'userlogin-authpopup-fallback' ) }}
				</a>
			</div>
		</template>
	</cdx-dialog>
</template>

<script>
const { defineComponent, ref, onMounted, onUnmounted } = require( 'vue' );
const { CdxDialog, CdxProgressIndicator, CdxButton } = require( './codex.js' );

module.exports = defineComponent( {
	name: 'AuthMessageDialog',
	components: {
		CdxDialog,
		CdxProgressIndicator,
		CdxButton
	},
	props: {
		fallbackUrl: {
			type: String,
			default: ''
		}
	},
	emits: [ 'retry', 'cancel', 'fallback' ],
	setup() {
		// The 'dialog' ref is used outside of the component to compute the dialog's size
		const dialog = ref( null );

		const bodyHeight = ref( 0 );
		const computeHeight = () => {
			// These numbers are chosen so that the dialog isn't too tall/narrow on large screens.
			// Leaving 200px on the top and bottom of the user's screen feels about right.
			bodyHeight.value = Math.max( document.body.clientHeight - 400, 500 ) + 40;
		};
		computeHeight();
		onMounted( () => window.addEventListener( 'resize', computeHeight ) );
		onUnmounted( () => window.removeEventListener( 'resize', computeHeight ) );

		return {
			dialog,
			bodyHeight
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-auth-message-dialog {
	.cdx-dialog__footer__actions {
		// Align the link with the action buttons
		a {
			align-self: center;
			.cdx-mixin-link();
		}
	}

	// Center the progress indicator in remaining space
	.cdx-dialog__body {
		display: flex;
		height: 100%;
		flex-direction: column;
	}

	.cdx-progress-indicator {
		flex-grow: 1;
		align-self: center;
	}
}
</style>
