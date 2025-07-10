<template>
	<cdx-dialog
		v-if="mobileExperience"
		:open="true"
		title=""
		class="skin-dialog-search"
	>
		<template #header>
			<div>
				<cdx-button
					:aria-label="$i18n( 'search-close' ).text()"
					weight="quiet"
					@click="$emit( 'exit' )">
					<cdx-icon :icon="exitIcon"></cdx-icon>
				</cdx-button>
				<slot></slot>
			</div>
		</template>
	</cdx-dialog>
	<div v-else>
		<slot></slot>
	</div>
</template>

<script>
const { cdxIconArrowPrevious } = require( './icons.json' );
const { defineComponent } = require( 'vue' );
const { CdxButton, CdxDialog, CdxIcon } = require( 'mediawiki.codex.typeaheadSearch' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'TypeaheadSearchWrapper',
	components: {
		CdxButton,
		CdxIcon,
		CdxDialog
	},
	props: {
		exitIcon: {
			type: Object,
			default: cdxIconArrowPrevious
		},
		mobileExperience: {
			type: Boolean
		}
	},
	emits: [
		'exit'
	],
	mounted() {
		// Adjust the bottom position of the typeahead search menu on mobile devices
		// to account for the virtual keyboard covering the bottom part of the viewport
		if ( this.mobileExperience && window.visualViewport ) {
			window.visualViewport.addEventListener( 'resize', () => {
				const menu = document.querySelector( '.cdx-typeahead-search__menu' );
				if ( menu ) {
					const bottom = window.innerHeight - ( visualViewport.offsetTop + visualViewport.height );
					menu.style.bottom = `${ Math.round( bottom ) }px`;
				}
			} );
		}
	}
} );

</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

@header-height: 55px;

@media screen {
	// Override the default Codex Dialog component to look like an overlay
	/* stylelint-disable-next-line selector-class-pattern */
	.cdx-dialog.skin-dialog-search {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		box-sizing: border-box;
		width: 100%;
		max-width: none;
		max-height: none;
		background: transparent;

		@media ( max-width: @max-width-breakpoint-mobile ) {
			background-color: @background-color-base;
		}

		.cdx-dialog__header {
			padding: 0;
			background-color: @background-color-interactive;
		}

		.cdx-dialog__header > div {
			display: flex;
			box-sizing: border-box;
			padding: 0 8px;
			align-items: center;
			height: @header-height;

			.cdx-typeahead-search {
				flex-grow: 1;
			}
		}

		@media ( max-width: @max-width-breakpoint-mobile ) {
			.cdx-menu {
				top: @header-height;
				left: 0;
				right: 0;
				position: fixed;
				bottom: 0;
				margin-top: -1px;
				overflow-y: auto;
				// Always show the menu on mobile even when the input is unfocused
				display: block !important; /* stylelint-disable-line declaration-no-important */
			}

			.cdx-menu-item {
				// Prevent Minerva list item styles from applying
				margin: 0;
			}

			.cdx-menu--has-footer {
				.cdx-menu__listbox {
					// Remove padding from Codex needed for absolutely positioned footer
					margin-bottom: 0 !important; /* stylelint-disable-line declaration-no-important */
				}

				.cdx-menu-item:last-of-type {
					position: relative;
				}
			}
		}
	}
}
</style>
