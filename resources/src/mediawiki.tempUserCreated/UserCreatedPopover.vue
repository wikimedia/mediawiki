<template>
	<cdx-popover
		v-model:open="isPopoverOpen"
		v-bind="popoverProps"
		class="mw-popover"
		:class="cssClasses"
		use-bottom-sheet
		stacked-actions
		placement="bottom"
		:use-close-button="true"
		@primary="onPrimary"
	>
		<template #default>
			<div class="mw-popover-content">
				<p> {{ listHead }} </p>
				<ul>
					<li
						v-for="( benefit, index ) in benefits"
						:key="`benefit-${index}`"
					>
						{{ benefit }}
					</li>
				</ul>
			</div>
		</template>
	</cdx-popover>
</template>

<script>
const { defineComponent, ref, onMounted, computed } = require( 'vue' );
const { CdxPopover } = require( '@wikimedia/codex' );

module.exports = defineComponent( {
	name: 'UserCreatedPopover',
	components: {
		CdxPopover
	},
	props: {
		title: {
			type: String,
			default: () => null
		},
		classes: {
			type: Array,
			default: () => ( [] )
		},
		content: {
			type: Array,
			default: () => ( [] )
		},
		hideBackdrop: {
			type: Boolean,
			default: false
		},
		primaryActionLabel: {
			type: String,
			default: () => null
		},
		primaryActionUrl: {
			type: String,
			default: () => null
		}
	},
	setup( props ) {
		const { title, content, primaryActionLabel, primaryActionUrl, hideBackdrop } = props;
		const [ listHead, ...benefits ] = content;
		const primaryAction = {
			label: primaryActionLabel,
			actionType: 'progressive'
		};
		const cssClasses = computed( () => props.classes.reduce( ( prev, curr ) => {
			if ( curr ) {
				prev[ curr ] = true;
			}
			return prev;
		}, {} ) );
		const popoverProps = { title, primaryAction, hideBackdrop };
		const isPopoverOpen = ref( false );

		onMounted( () => {
			isPopoverOpen.value = true;
		} );

		return {
			benefits,
			cssClasses,
			listHead,
			isPopoverOpen,
			popoverProps,
			onPrimary() {
				window.location.assign( primaryActionUrl );
			}
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-popover {
	.cdx-popover__header__title {
		font-family: @font-family-base;
		font-size: @font-size-large;
		font-weight: @font-weight-bold;
		line-height: @line-height-large;
	}

	.mw-popover-content > p {
		margin: 0;
		line-height: @line-height-medium;
	}

	.mw-popover-content > p + ul {
		margin-top: 0;
	}

	.mw-popover-content > ul {
		padding-left: @spacing-200;
		line-height: @line-height-medium;
	}

	.mw-popover-content > ul > li {
		margin-bottom: 0;
	}
}
</style>
