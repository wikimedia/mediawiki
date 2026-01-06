<template>
	<cdx-button
		type="button"
		:disabled="selectedPagesList.length === 0"
		@click="isOpen = true; dialogAction = 'assign';"
	>
		{{ $i18n( 'watchlistlabels-editwatchlist-dialog-button' ) }}
	</cdx-button>
	<cdx-button
		type="button"
		:disabled="selectedPagesList.length === 0"
		@click="isOpen = true; dialogAction = 'unassign';"
	>
		{{ $i18n( 'watchlistlabels-editwatchlist-dialog-button-unassign' ) }}
	</cdx-button>

	<!-- Render in place so that the checkboxe inputs are within the EditWatchlist form. -->
	<cdx-dialog
		v-model:open="isOpen"
		render-in-place
		:title="dialogAction === 'unassign' ? $i18n( 'watchlistlabels-editwatchlist-dialog-button-unassign' ) : $i18n( 'watchlistlabels-editwatchlist-dialog-button' )"
		:use-close-button="false"
	>
		<div role="group" aria-labelledby="watchlistlabels-dialog-checkboxes">
			<p id="watchlistlabels-dialog-checkboxes">
				<!-- Security note: the raw HTML here is a list of valid page titles wrapped in <strong> elements. -->
				<!-- eslint-disable vue/no-v-html -->
				<span v-if="dialogAction === 'unassign'" v-html="$i18n( 'watchlistlabels-editwatchlist-dialog-intro-unassign', selectedPagesList, labels.length, selectedPagesList.length )">
				</span>
				<span v-else v-html="$i18n( 'watchlistlabels-editwatchlist-dialog-intro', selectedPagesList, labels.length, selectedPagesList.length )">
				</span>
			</p>
			<cdx-checkbox
				v-for="label in labels"
				:key="label.id"
				:input-value="label.id"
				name="watchlistlabels[]"
			>
				{{ label.name }}
			</cdx-checkbox>
		</div>
		<template #footer>
			<div class="cdx-dialog__footer__actions">
				<!-- Manually create the action buttons in order to add type and other attributes
				so that they work as part of the <form>. -->
				<cdx-button
					class="cdx-dialog__footer__primary-action"
					weight="primary"
					:action="dialogAction === 'unassign' ? 'destructive' : 'progressive'"
					name="watchlistlabels-action"
					:value="dialogAction === 'unassign' ? 'unassign' : 'assign'"
					type="submit"
				>
					{{ dialogAction === 'unassign' ?
						$i18n( 'watchlistlabels-editwatchlist-dialog-unassign' ) :
						$i18n( 'watchlistlabels-editwatchlist-dialog-assign' )
					}}
				</cdx-button>
				<cdx-button
					class="cdx-dialog__footer__default-action"
					type="button"
					@click="isOpen = false"
				>
					{{ $i18n( 'cancel' ) }}
				</cdx-button>
			</div>
		</template>
	</cdx-dialog>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const { CdxButton, CdxDialog, CdxCheckbox } = require( '@wikimedia/codex' );

module.exports = defineComponent( {
	name: 'LabelDialog',
	components: {
		CdxButton,
		CdxDialog,
		CdxCheckbox
	},
	setup() {
		const dialogAction = ref( 'assign' );
		const isOpen = ref( false );
		const selectedPagesList = ref( '' );

		const checkboxes = document.querySelectorAll( '.watchlist-item-checkbox' );

		const updateSelectedPagesList = () => {
			let selectedPages = Array.from( checkboxes )
				.filter( ( e ) => e.checked )
				.map( ( e ) => e.value );
			const namedLimit = 5;
			if ( selectedPages.length > namedLimit ) {
				const remaining = selectedPages.length - namedLimit;
				selectedPages = selectedPages.splice( 0, namedLimit );
				selectedPages.push( mw.msg(
					'watchlistlabels-editwatchlist-dialog-intro-more',
					remaining,
					mw.language.convertNumber( remaining )
				) );
			}
			selectedPagesList.value = mw.language.listToText(
				selectedPages.map( ( t ) => '<strong>' + t + '</strong>' )
			);
		};
		checkboxes.forEach( ( checkbox ) => {
			checkbox.addEventListener( 'change', updateSelectedPagesList );
		} );
		// @todo Fix for select-all.
		const selectAllCheckbox = document.getElementById( 'select-all-checkbox' );
		selectAllCheckbox.addEventListener( 'change', updateSelectedPagesList );

		return {
			dialogAction,
			isOpen,
			selectedPagesList,
			labels: mw.config.get( 'watchlistLabels' )
		};
	}
} );
</script>
