<template>
	<cdx-button type="button" @click="openAssignDialog">
		{{ $i18n( 'watchlistlabels-editwatchlist-dialog-button' ) }}
	</cdx-button>
	<cdx-button type="button" @click="openUnassignDialog">
		{{ $i18n( 'watchlistlabels-editwatchlist-dialog-button-unassign' ) }}
	</cdx-button>
	<cdx-button
		ref="removeButton"
		type="button"
		:title="$i18n( 'tooltip-watchlistedit-normal-submit' )"
		:accesskey="$i18n( 'accesskey-watchlistedit-normal-submit' )"
		@click="openRemoveDialog"
	>
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
				<span v-i18n-html="dialogBody"></span>
			</p>
			<div v-if="showLabels">
				<cdx-checkbox
					v-for="[ labelId, labelName ] in labels"
					:key="labelId"
					:input-value="labelId"
					name="watchlistlabels[]"
				>
					<bdi>{{ labelName }}</bdi>
				</cdx-checkbox>
			</div>
		</div>
		<template #footer>
			<div class="cdx-dialog__footer__actions">
				<!-- Manually create the action buttons in order to add type and other attributes
				so that they work as part of the <form>. -->
				<cdx-button
					v-if="primaryActionEnabled"
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
					{{ primaryActionEnabled ? $i18n( 'cancel' ) : $i18n( 'ok' ) }}
				</cdx-button>
			</div>
		</template>
	</cdx-dialog>
</template>

<script>
const { defineComponent, ref, computed, onMounted } = require( 'vue' );
const { CdxButton, CdxDialog, CdxCheckbox } = require( '@wikimedia/codex' );

module.exports = defineComponent( {
	name: 'EditWatchlistDialog',
	components: {
		CdxButton,
		CdxDialog,
		CdxCheckbox
	},
	setup() {
		const removeButton = ref( null );
		const dialogAction = ref( 'assign' );
		const isOpen = ref( false );
		const showLabels = ref( false );
		const selectedPages = ref( [] );
		const selectedPagesListHtml = ref( '' );
		const allLabels = new Map( Object.values( mw.config.get( 'watchlistLabels' ) ).map( ( l ) => [ l.id, l.name ] ) );
		const labels = ref( new Map( allLabels ) );

		onMounted( () => {
			// Update tooltip to include access key hint
			if ( removeButton.value && removeButton.value.$el ) {
				$( removeButton.value.$el ).updateTooltipAccessKeys();
			}
		} );

		const primaryActionEnabled = computed(
			() => selectedPages.value.length && ( showLabels.value || dialogAction.value === 'remove' )
		);
		const dialogTitle = computed( () => {
			if ( dialogAction.value === 'assign' ) {
				return primaryActionEnabled.value ?
					mw.msg( 'watchlistlabels-editwatchlist-dialog-button' ) :
					mw.msg( 'watchlistlabels-editwatchlist-dialog-assign-error' );
			} else if ( dialogAction.value === 'unassign' ) {
				return primaryActionEnabled.value ?
					mw.msg( 'watchlistlabels-editwatchlist-dialog-button-unassign' ) :
					mw.msg( 'watchlistlabels-editwatchlist-dialog-unassign-error' );
			} else {
				return selectedPages.value.length === 0 ?
					mw.msg( 'watchlistedit-table-remove-selected-error' ) :
					mw.msg( 'watchlistedit-table-remove-selected', selectedPages.value.length );
			}
		} );
		const dialogBody = computed( () => {
			if ( dialogAction.value === 'assign' ) {
				showLabels.value = false;
				if ( allLabels.size === 0 ) {
					const specialWatchlistLabelsLink = mw.config.get( 'SpecialWatchlistLabelsTitle' );
					return mw.message( 'watchlistlabels-editwatchlist-dialog-intro-nolabels', specialWatchlistLabelsLink );
				} else if ( selectedPages.value.length === 0 ) {
					return mw.message( 'watchlistlabels-editwatchlist-dialog-intro-noitems' );
				}
				showLabels.value = true;
				return mw.message(
					'watchlistlabels-editwatchlist-dialog-intro',
					document.createRange().createContextualFragment( selectedPagesListHtml.value ),
					labels.value.length,
					selectedPages.value.length
				);
			} else if ( dialogAction.value === 'unassign' ) {
				showLabels.value = false;
				if ( selectedPages.value.length > 0 && labels.value.size === 0 ) {
					return mw.message(
						'watchlistlabels-editwatchlist-dialog-intro-unassign-noitemlabels',
						selectedPages.value.length
					);
				} else if ( selectedPages.value.length === 0 ) {
					return mw.message( 'watchlistlabels-editwatchlist-dialog-intro-unassign-noitems' );
				}
				showLabels.value = true;
				return mw.message(
					'watchlistlabels-editwatchlist-dialog-intro-unassign',
					document.createRange().createContextualFragment( selectedPagesListHtml.value ),
					labels.value.length,
					selectedPages.value.length
				);
			} else {
				showLabels.value = false;
				return selectedPages.value.length > 0 ?
					mw.message(
						'watchlistedit-unwatch-confirmation',
						document.createRange().createContextualFragment( selectedPagesListHtml.value ),
						selectedPages.value.length
					) :
					mw.message( 'watchlistedit-unwatch-confirmation-empty' );
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

		const updateSelectedPagesListHtml = () => {
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
			selectedPagesListHtml.value = mw.language.listToText(
				selectedPages.value.map( ( t ) => mw.html.element( 'strong', {}, t ) )
			);
		};
		checkboxes.forEach( ( checkbox ) => {
			checkbox.addEventListener( 'change', updateSelectedPagesListHtml );
		} );
		const selectAllCheckbox = document.getElementById( 'select-all-checkbox' );
		selectAllCheckbox.addEventListener( 'selectall', updateSelectedPagesListHtml );
		updateSelectedPagesListHtml();

		const openAssignDialog = () => {
			isOpen.value = true;
			dialogAction.value = 'assign';
			labels.value = new Map( allLabels );
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
			removeButton,
			isOpen,
			dialogTitle,
			dialogBody,
			dialogAction,
			selectedPages,
			labels,
			showLabels,
			openRemoveDialog,
			openAssignDialog,
			openUnassignDialog,
			primaryAction,
			primaryActionEnabled
		};
	}
} );
</script>
