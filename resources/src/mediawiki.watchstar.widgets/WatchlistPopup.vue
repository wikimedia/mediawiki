<template>
	<cdx-popover
		id="mw-watchstar-WatchlistPopup"
		v-model:open="isOpen"
		class="mw-watchstar-WatchlistPopup"
		:anchor="link"
		use-close-button
		:title="$i18n( 'watchstar-popup-title' ).text()"
	>
		<template #default>
			<cdx-message :type="messageType" inline class="mw-watchstar-WatchstarPopup-message">
				<div v-i18n-html="message" v-if="!errorMessage"></div>
				<div v-html="errorMessage"></div>
			</cdx-message>

			<cdx-field
				v-if="expiryEnabled && action === 'unwatch'"
			>
				<template #default>
					<cdx-select
						v-model:selected="expiry"
						:menu-items="expiries"
						@update:selected="onExpiryUpdateSelected"
					></cdx-select>
				</template>
				<template #label>
					{{ $i18n( 'addedwatchexpiry-options-label' ).text() }}
				</template>
				<template #help-text>
					<!-- @todo Localize the special page name. -->
					<div v-i18n-html:watchstar-popup-expiry-help="[ 'Special:Preferences#mw-htmlform-pageswatchlist' ]"></div>
				</template>
			</cdx-field>

			<cdx-field
				v-if="labelsEnabled && action === 'unwatch'"
			>
				<template #default>
					<cdx-multiselect-lookup
						v-model:input-chips="labelsInputChips"
						v-model:selected="labelsSelected"
						:menu-items="labelsVisible"
						:disabled="!labelsAreLoaded"
						@input="onLabelsInput"
						@update:input-chips="onLabelsUpdateInputChips"
						@update:selected="onLabelsUpdateSelected"
					>
						<template #no-results>
							{{ $i18n( 'watchstar-popup-labels-no-results' ).text() }}
						</template>
					</cdx-multiselect-lookup>
				</template>
				<template #label>
					{{ $i18n( 'watchstar-popup-labels' ).text() }}
				</template>
				<template #help-text>
					<!-- @todo Localize the special page name. -->
					<div v-i18n-html:watchstar-popup-labels-help="[ 'Special:WatchlistLabels' ]"></div>
				</template>
			</cdx-field>
		</template>

		<template #footer>
			<cdx-button
				v-if="action === 'unwatch'"
				action="destructive"
				@click="onUnwatch"
			>
				{{ $i18n( 'unwatch' ) }}
			</cdx-button>
			<cdx-button
				v-if="action === 'watch'"
				action="progressive"
				@click="doWatch"
			>
				{{ $i18n( 'watch' ) }}
			</cdx-button>
		</template>
	</cdx-popover>
</template>

<script>
const { defineComponent, ref, computed, watch, onMounted } = require( 'vue' );
const { CdxPopover, CdxMessage, CdxField, CdxSelect, CdxMultiselectLookup, CdxButton } = require( '@wikimedia/codex' );
const api = new mw.Api();

module.exports = defineComponent( {
	name: 'WatchlistPopup',
	components: {
		CdxPopover,
		CdxMessage,
		CdxField,
		CdxSelect,
		CdxMultiselectLookup,
		CdxButton
	},
	props: {
		initialAction: { type: String, default: 'watch' },
		expiryEnabled: { type: Boolean, default: false },
		labelsEnabled: { type: Boolean, default: false },
		dataExpiryOptions: { type: Object, default: () => {} },
		watchResponse: { type: Array, default: () => [] },
		title: { type: mw.Title, default: null },
		preferredExpiry: { type: String, default: '' },
		link: { type: Object, default: () => {} }
	},
	setup( props ) {
		/** @member {boolean} isOpen Whether the popup is currently open. */
		const isOpen = ref( true );

		/** @member {string} action The action currently able to be taken from the popup or the link (i.e. if the item is watched, this is 'unwatch'). */
		const action = ref( props.initialAction );

		/** @member {string} expiry The current expiry value. */
		const expiry = ref( props.preferredExpiry );

		/** @member {Object[]} expiries All expiry values shown, including a custom one if the item is currently expiring. */
		const expiries = ref( [] );
		for ( const key in props.dataExpiryOptions ) {
			expiries.value.push( { value: props.dataExpiryOptions[ key ], label: key } );
		}

		/** @member {number} daysLeft Integer number of days remaining (rounded up). */
		const daysLeft = computed( () => {
			const expiryDate = new Date( expiry.value );
			const currentDate = new Date();
			return Math.ceil( ( expiryDate - currentDate ) / ( 1000 * 60 * 60 * 24 ) );
		} );

		/** @member {Object} watchResponse The response of the most recent api.watch() call. */
		const watchResponse = ref( props.watchResponse );

		/** @member {string} messageType One of 'notice', 'warning', 'error', or 'success'. */
		const messageType = ref( 'notice' );

		/** @member {string} message The name of the system message shown at the top of the popup. */
		const message = computed( () => {
			let messageName = 'watchstar-popup-already-watched';
			messageType.value = 'notice';
			let expiryMessageVal = expiry.value;
			if ( watchResponse.value.watched || watchResponse.value.unwatched ) {
				const isWatched = watchResponse.value.watched === true;
				messageName = isWatched ? 'addedwatchtext' : 'removedwatchtext';
				if ( isWatched ) {
					if ( !props.expiryEnabled || ( !expiry.value || mw.util.isInfinity( expiry.value ) ) ) {
						// The message should include `infinite` watch period,
						// and is also shown if expiry is disabled (until T270058 is resolved).
						messageName = 'addedwatchindefinitelytext';
					} else {
						if ( daysLeft.value ) {
							expiryMessageVal = daysLeft.value;
							messageName = 'addedwatchexpirydays';
						} else {
							messageName = 'addedwatchexpirytext';
						}
					}
				}
				if ( props.title.isTalkPage() ) {
					messageName += '-talk';
				}
				messageType.value = 'success';
			}
			// Messages that can be used here:
			// * addedwatchtext
			// * addedwatchtext-talk
			// * addedwatchexpirydays-talk
			// * addedwatchexpirydays
			// * addedwatchexpirydays-talk
			// * addedwatchexpirytext
			// * addedwatchexpirytext-talk
			// * addedwatchindefinitelytext
			// * addedwatchindefinitelytext-talk
			// * removedwatchtext
			// * removedwatchtext-talk
			// * watchstar-popup-already-watched
			return mw.message( messageName, props.title.getPrefixedText(), expiryMessageVal );
		} );

		const errorMessage = ref();

		/** @member {Object[]} labelsVisible Labels currently visible in the dropdown list (i.e. all minus selected). */
		const labelsVisible = ref( [] );

		/** @member {Object[]} labelsAll All of the user's labels. */
		const labelsAll = ref( [] );

		/** @member {Object[]} labelsInputChips Labels that are currently shown as input chips. */
		const labelsInputChips = ref( [] );

		/** @member {number[]} labelsSelected The labels IDs that are currently selected. */
		const labelsSelected = ref( [] );

		/** @member {boolean} labelsAreLoaded False until the initial label-getting API request has finished. */
		const labelsAreLoaded = ref( false );

		/**
		 * Allows user to tab into the expiry dropdown from the watch link.
		 * Valid only for the initial keystroke after the popup appears, so as to
		 * avoid listening to every keystroke for the entire session.
		 */
		function addTabKeyListener() {
			$( window ).one( 'keydown.watchlistExpiry', ( e ) => {
				if ( ( e.keyCode || e.which ) !== OO.ui.Keys.TAB ) {
					return;
				}

				// Here we look for focus on the watch link, going by the accessKey.
				// This is because there is no CSS class or ID on the link itself,
				// and skins could manipulate the position of the link. The accessKey
				// however is always present on the link.
				if ( document.activeElement.accessKey === mw.msg( 'accesskey-ca-watch' ) ) {
					e.preventDefault();
					// @todo Fix this.
					// this.expirySelect.value.focus();

					// Add another tab key listener so they can tab back to the watch link.
					addTabKeyListener();
				} else if ( $( e.target ).parents( '.mw-watchexpiry' ).length ) {
					// Move focus to the watch link if they're tabbing from the dropdown.
					e.preventDefault();
					props.link.focus();
				}
			} );
		}

		const showError = ( code, data ) => {
			errorMessage.value = api.getErrorMessage( data ).html();
			messageType.value = 'error';
		};

		const doWatch = () => {
			window.dispatchEvent( new CustomEvent( 'WatchlistPopup.loading' ) );
			api.watch( props.title.getPrefixedText(), expiry.value, labelsSelected.value )
				.done( ( newWatchResponse ) => {
					watchResponse.value = newWatchResponse;
					action.value = 'unwatch';
					window.dispatchEvent( new CustomEvent( 'WatchlistPopup.watch', { detail: { watchResponse } } ) );
					errorMessage.value = null;
				} )
				.fail( showError );
		};

		const onUnwatch = () => {
			window.dispatchEvent( new CustomEvent( 'WatchlistPopup.loading' ) );
			api.unwatch( props.title.getPrefixedText() )
				.done( ( newWatchResponse ) => {
					watchResponse.value = newWatchResponse;
					labelsSelected.value = [];
					labelsInputChips.value = [];
					expiry.value = props.preferredExpiry;
					action.value = 'watch';
					window.dispatchEvent( new CustomEvent( 'WatchlistPopup.unwatch', { detail: { watchResponse } } ) );
					errorMessage.value = null;
				} )
				.fail( showError );
		};

		const onExpiryUpdateSelected = () => {
			doWatch();
		};

		const onLabelsInput = ( value ) => {
			labelsVisible.value = labelsAll.value.filter(
				( l ) => l.label.toLowerCase().includes( value.toLowerCase() )
			);
		};

		const onLabelsUpdateSelected = () => {
			doWatch();
		};

		const onLabelsUpdateInputChips = () => {
		};

		onMounted( () => {
			api.get( {
				action: 'query',
				meta: 'userinfo',
				uiprop: 'watchlistlabels',

				prop: 'info',
				titles: props.title.getPrefixedText(),
				inprop: 'watched|watchlistlabels',

				format: 'json',
				formatversion: '2'

			} ).then( ( data ) => {
				labelsAreLoaded.value = true;
				// Existing value.
				if ( data.query && data.query.pages ) {
					// Selected expiry
					if ( data.query.pages[ 0 ].watched === true && data.query.pages[ 0 ].watchlistexpiry === undefined ) {
						expiry.value = 'infinite';
					} else {
						expiry.value = data.query.pages[ 0 ].watchlistexpiry || props.preferredExpiry;
					}
					// Currently selected labels.
					for ( const l of data.query.pages[ 0 ].watchlistlabels || [] ) {
						labelsInputChips.value.push( { value: l.id, label: l.name } );
						labelsSelected.value.push( l.id );
					}
				}
				// All of the user's labels, divided into an 'all' list and a 'visible' list
				// (the latter can be further filtered by the input to the multiselect).
				if ( data.query && data.query.userinfo && data.query.userinfo.watchlistlabels ) {
					for ( const label of data.query.userinfo.watchlistlabels ) {
						labelsAll.value.push( { value: label.id, label: label.name } );
					}
				}
				labelsVisible.value = labelsAll.value.filter(
					// Only include labels that have not yet been added as chips.
					( l ) => !labelsInputChips.value.some( ( x ) => x.value === l.value )
				);
			} );
		} );

		// If re-opening the popup and page is not watched, watch it.
		watch( isOpen, ( newVal ) => {
			if ( newVal && action.value === 'watch' ) {
				doWatch();
			} else if ( !newVal ) {
				watchResponse.value = {};
			}
		} );

		watch( expiry, ( newExpiry ) => {
			if ( newExpiry && expiries.value.filter( ( x ) => x.value === newExpiry ).length === 0 ) {
				const daysLeftMsg = daysLeft.value > 0 ?
					mw.msg( 'watchlist-expiry-days-left', daysLeft.value ) :
					mw.msg( 'watchlist-expiry-hours-left' );
				expiries.value.push( { value: newExpiry, label: daysLeftMsg } );
			}
		} );

		if ( props.expiryEnabled || props.labelsEnabled ) {
			addTabKeyListener();
		}

		// Watch on inintial open, if not yet watched.
		if ( props.initialAction === 'watch' ) {
			doWatch();
		}

		return {
			isOpen,
			action,
			message,
			messageType,
			errorMessage,
			expiries,
			expiry,
			onExpiryUpdateSelected,
			labelsVisible,
			labelsSelected,
			labelsInputChips,
			labelsAreLoaded,
			doWatch,
			onLabelsInput,
			onLabelsUpdateSelected,
			onLabelsUpdateInputChips,
			onUnwatch
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-watchstar-WatchlistPopup {
	min-width: @min-width-medium;

	.cdx-label__label__text {
		font-weight: normal;
	}
}
</style>
