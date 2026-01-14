<template>
	<cdx-button
		type="button"
		:disabled="selectedPagesList.length === 0"
		@click="openAssignDialog"
	>
		{{ $i18n( 'watchlistlabels-editwatchlist-dialog-button' ) }}
	</cdx-button>
	<cdx-button
		type="button"
		:disabled="selectedPagesList.length === 0"
		@click="openUnassignDialog"
	>
		{{ $i18n( 'watchlistlabels-editwatchlist-dialog-button-unassign' ) }}
	</cdx-button>
	<cdx-button type="button" @click="openRemoveDialog">
		{{ $i18n( 'watchlistedit-table-remove-selected', selectedPages.length ) }}
	</cdx-button>

	<!-- Render in place so that the checkboxe inputs are within the EditWatchlist form. -->
	<cdx-dialog
		v-model:open="isOpen"
		render-in-place
		:title="dialogTitle"
		:use-close-button="false"
	>
		<div role="group" aria-labelledby="watchlistlabels-dialog-checkboxes">
			<p id="watchlistlabels-dialog-checkboxes">
				<!-- Security note: the raw HTML here is a list of valid page titles wrapped in <strong> elements. -->
				<!-- eslint-disable vue/no-v-html -->
				<span v-html="dialogBody"></span>
			</p>
			<div v-if="dialogAction !== 'remove'">
				<cdx-checkbox
					v-for="[ labelId, labelName ] in labels"
					:key="labelId"
					:input-value="labelId"
					name="watchlistlabels[]"
				>
					{{ labelName }}
				</cdx-checkbox>
			</div>
		</div>
		<template #footer>
			<div class="cdx-dialog__footer__actions">
				<!-- Manually create the action buttons in order to add type and other attributes
				so that they work as part of the <form>. -->
				<cdx-button
					v-if="selectedPages.length"
					class="cdx-dialog__footer__primary-action"
					weight="primary"
					:action="dialogAction === 'assign' ? 'progressive' : 'destructive'"
					:name="dialogAction === 'remove' ? '' : 'watchlistlabels-action'"
					:value="dialogAction === 'unassign' ? 'unassign' : 'assign'"
					type="submit"
				>
					{{ primaryAction }}
				</cdx-button>
				<cdx-button
					class="cdx-dialog__footer__default-action"
					type="button"
					@click="isOpen = false"
				>
					{{ selectedPages.length > 0 ? $i18n( 'cancel' ) : $i18n( 'ok' ) }}
				</cdx-button>
			</div>
		</template>
	</cdx-dialog>
</template>

<script>
const { defineComponent, ref, computed } = require( 'vue' );
const { CdxButton, CdxDialog, CdxCheckbox } = require( '@wikimedia/codex' );

module.exports = defineComponent( {
	name: 'EditWatchlistDialog',
	components: {
		CdxButton,
		CdxDialog,
		CdxCheckbox
	},
	setup() {
		const dialogAction = ref( 'assign' );
		const isOpen = ref( false );
		const selectedPages = ref( [] );
		const selectedPagesList = ref( '' );
		const allLabels = new Map( Object.values( mw.config.get( 'watchlistLabels' ) ).map( ( l ) => [ l.id, l.name ] ) );
		const labels = ref( new Map( allLabels ) );

		const dialogTitle = computed( () => {
			if ( dialogAction.value === 'assign' ) {
				return mw.msg( 'watchlistlabels-editwatchlist-dialog-button' );
			} else if ( dialogAction.value === 'unassign' ) {
				return mw.msg( 'watchlistlabels-editwatchlist-dialog-button-unassign' );
			} else {
				return mw.msg( 'watchlistedit-table-remove-selected', selectedPages.value.length );
			}
		} );
		const dialogBody = computed( () => {
			if ( dialogAction.value === 'assign' ) {
				return mw.msg( 'watchlistlabels-editwatchlist-dialog-intro', selectedPagesList.value, labels.value.length, labels.value.length );
			} else if ( dialogAction.value === 'unassign' ) {
				return mw.msg( 'watchlistlabels-editwatchlist-dialog-intro-unassign', selectedPagesList.value, labels.value.length, labels.value.length );
			} else {
				return selectedPages.value.length > 0 ?
					mw.msg( 'watchlistedit-unwatch-confirmation', selectedPagesList.value, selectedPages.value.length ) :
					mw.msg( 'watchlistedit-unwatch-confirmation-empty' );
			}
		} );
		const primaryAction = computed( () => {
			if ( dialogAction.value === 'assign' ) {
				return mw.msg( 'watchlistlabels-editwatchlist-dialog-assign' );
			} else if ( dialogAction.value === 'unassign' ) {
				return mw.msg( 'watchlistlabels-editwatchlist-dialog-unassign' );
			} else {
				return mw.msg( 'watchlistedit-unwatch-confirmation-accept' );
			}
		} );

		const checkboxes = document.querySelectorAll( '.watchlist-item-checkbox' );

		const updateSelectedPagesList = () => {
			selectedPages.value = Array.from( checkboxes )
				.filter( ( e ) => e.checked )
				.map( ( e ) => e.value );
			const namedLimit = 5;
			if ( selectedPages.value.length > namedLimit ) {
				const remaining = selectedPages.value.length - namedLimit;
				selectedPages.value = selectedPages.value.splice( 0, namedLimit );
				selectedPages.value.push( mw.msg(
					'watchlistlabels-editwatchlist-dialog-intro-more',
					remaining,
					mw.language.convertNumber( remaining )
				) );
			}
			selectedPagesList.value = mw.language.listToText(
				selectedPages.value.map( ( t ) => '<strong>' + t + '</strong>' )
			);
		};
		checkboxes.forEach( ( checkbox ) => {
			checkbox.addEventListener( 'change', updateSelectedPagesList );
		} );
		const selectAllCheckbox = document.getElementById( 'select-all-checkbox' );
		selectAllCheckbox.addEventListener( 'selectall', updateSelectedPagesList );

		const openAssignDialog = () => {
			isOpen.value = true;
			dialogAction.value = 'assign';
			labels.value = allLabels;
		};

		const openUnassignDialog = () => {
			isOpen.value = true;
			dialogAction.value = 'unassign';
			labels.value.clear();
			Array.from( checkboxes )
				.filter( ( e ) => e.checked )
				.forEach( ( e ) => {
					Array.from( e.closest( 'tr' ).querySelectorAll( '[data-wllabel]' ) ).forEach( ( labelEl ) => {
						labels.value.set( labelEl.dataset.wllabel, labelEl.textContent );
					} );
				} );
		};

		const openRemoveDialog = () => {
			isOpen.value = true;
			dialogAction.value = 'remove';
		};

		return {
			isOpen,
			dialogTitle,
			dialogBody,
			dialogAction,
			selectedPages,
			selectedPagesList,
			labels,
			openRemoveDialog,
			openAssignDialog,
			openUnassignDialog,
			primaryAction
		};
	}
} );
</script>
