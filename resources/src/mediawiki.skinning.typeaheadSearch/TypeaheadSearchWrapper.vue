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
