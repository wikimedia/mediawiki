<template>
	<cdx-popover
		v-model:open="showPopover"
		:anchor="anchor"
		placement="bottom-start"
		:render-in-place="true"
		:use-close-button="false"
		:primary-action="primaryAction"
		:default-action="defaultAction"
		class="mw-special-watchlistlabels-onboarding"
		@primary="goToNext"
		@default="goToPrev"
	>
		<div class="mw-special-watchlistlabels-onboarding-wrapper">
			<div class="mw-special-watchlistlabels-onboarding-image" :class="imageClass">
			</div>
			<div class="mw-special-watchlistlabels-onboarding-main">
				<div class="mw-special-watchlistlabels-onboarding-header">
					<!-- Build a manual header, because the 'header' slot doesn't allow us to put the image above it. -->
					<div class="mw-special-watchlistlabels-onboarding-title">
						{{ title }}
					</div>
					<div class="mw-special-watchlistlabels-onboarding-progress">
						{{ $i18n( 'watchlistlabels-onboarding-progress', popoverCurrentNum, popoverTotalNum ).text() }}
					</div>
				</div>
				<div>
					{{ body }}
				</div>
			</div>
		</div>
	</cdx-popover>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const { CdxPopover } = require( '@wikimedia/codex' );

module.exports = defineComponent( {
	name: 'LabelOnboarding',
	components: {
		CdxPopover
	},
	setup() {
		const showPopover = ref( true );
		const imageClass = ref( '' );
		const anchor = ref( '' );
		const title = ref( '' );
		const body = ref( '' );
		const popoverTotalNum = ref( 0 );
		const popoverCurrentNum = ref( 0 );
		const defaultAction = ref( {} );
		const primaryAction = ref( {} );

		const specialWatchlistLabelsUrl = mw.config.get( 'SpecialWatchlistLabelsUrl' );
		const specialEditWatchlistUrl = mw.config.get( 'SpecialEditWatchlistUrl' );
		// Popover names are unique between the five.
		const popovers = [];
		if ( document.getElementById( 'p-associated-pages' ) ) {
			// Skins that support associated-pages have three popovers.
			popovers.push( {
				name: 'manage',
				selector: 'a[href$="' + specialWatchlistLabelsUrl + '"]',
				image: 'manage'
			} );
			popovers.push( {
				name: 'edit',
				selector: 'a[href$="' + specialEditWatchlistUrl + '"]',
				image: 'assign'
			} );
		} else {
			// Skins that do not have two popovers.
			popovers.push( {
				name: 'editmanage',
				selector: 'a[href$="' + specialEditWatchlistUrl + '"]',
				image: 'edit'
			} );
		}
		// The final popover is common to all skins.
		popovers.push( {
			name: 'filter',
			selector: '.mw-rcfilters-ui-watchlistlabels',
			image: 'filter'
		} );
		popoverTotalNum.value = popovers.length;

		let currentPopover = 0;

		const configurePopover = ( idx ) => {
			if ( popovers[ idx ] === undefined ) {
				return false;
			}
			anchor.value = document.querySelector( popovers[ idx ].selector );
			imageClass.value = 'mw-special-watchlistlabels-onboarding-image-' + popovers[ idx ].name;
			// Labels that can be used here:
			// * watchlistlabels-onboarding-manage-title
			// * watchlistlabels-onboarding-edit-title
			// * watchlistlabels-onboarding-editmanage-title
			// * watchlistlabels-onboarding-filter-title
			title.value = mw.msg( 'watchlistlabels-onboarding-' + popovers[ idx ].name + '-title' );
			// Labels that can be used here:
			// * watchlistlabels-onboarding-manage-body
			// * watchlistlabels-onboarding-edit-body
			// * watchlistlabels-onboarding-editmanage-body
			// * watchlistlabels-onboarding-filter-body
			body.value = mw.msg( 'watchlistlabels-onboarding-' + popovers[ idx ].name + '-body' );
			if ( idx <= 0 ) {
				defaultAction.value = null;
			} else {
				defaultAction.value = { label: mw.msg( 'watchlistlabels-onboarding-prev' ) };
			}
			if ( popovers[ idx + 1 ] === undefined ) {
				primaryAction.value = { label: mw.msg( 'watchlistlabels-onboarding-final' ), actionType: 'progressive' };
			} else {
				primaryAction.value = { label: mw.msg( 'watchlistlabels-onboarding-next' ) };
			}
			popoverCurrentNum.value = idx + 1;
			currentPopover = idx;
			return true;
		};

		// Initial state is with the first popover open.
		configurePopover( 0 );

		const goToNext = function () {
			const switched = configurePopover( currentPopover + 1 );
			if ( !switched ) {
				// If we didn't switch to a new popover, this must be the last one.
				new mw.Api().saveOption( 'watchlistlabelonboarding', '0' ).then( () => {
					mw.user.options.set( 'watchlistlabelonboarding', '0' );
					showPopover.value = false;
				} );
			}
		};

		const goToPrev = function () {
			configurePopover( currentPopover - 1 );
		};

		return {
			anchor,
			showPopover,
			primaryAction,
			defaultAction,
			goToNext,
			goToPrev,
			imageClass,
			title,
			body,
			popoverTotalNum,
			popoverCurrentNum
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-special-watchlistlabels-onboarding-wrapper {
	display: flex;
	gap: @spacing-50;
}

.mw-special-watchlistlabels-onboarding-header {
	display: flex;
	justify-content: space-between;
}

.mw-special-watchlistlabels-onboarding-title {
	font-weight: @font-weight-bold;
}

.mw-special-watchlistlabels-onboarding-progress {
	font-size: @font-size-small;
}

.mw-special-watchlistlabels-onboarding-image {
	width: 128px;
	height: 128px;
	background-repeat: no-repeat;
	background-size: contain;
	flex: 0 0 128px;
}

.mw-special-watchlistlabels-onboarding-image-manage {
	/* @embed */
	background-image: url( ./images/label-onboarding-manage.svg );
}

.mw-special-watchlistlabels-onboarding-image-edit,
.mw-special-watchlistlabels-onboarding-image-editmanage {
	/* @embed */
	background-image: url( ./images/label-onboarding-assign.svg );
}

.mw-special-watchlistlabels-onboarding-image-filter {
	/* @embed */
	background-image: url( ./images/label-onboarding-filter.svg );
}
</style>
