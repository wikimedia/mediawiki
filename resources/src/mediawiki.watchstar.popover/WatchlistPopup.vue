<template>
	<cdx-popover
		id="mw-watchstar-WatchlistPopup"
		v-model:open="isOpen"
		class="mw-watchstar-WatchlistPopup"
		:anchor="anchor"
		:placement="placement"
		:use-bottom-sheet="useBottomSheet"
		@mouseenter="onPopoverMouseEnter"
		@mouseleave="onPopoverMouseLeave"
		@focusin="onPopoverFocusIn"
		@focusout="onPopoverFocusOut"
	>
		<template #header>
			<cdx-icon
				v-if="headerIcon"
				:icon="headerIcon"
				class="mw-watchstar-WatchlistPopup__header-icon"
				:class="'mw-watchstar-WatchlistPopup__header-icon--' + messageType"
			></cdx-icon>
			<div
				class="cdx-popover__header__title mw-watchstar-WatchlistPopup__header-title mw-watchstar-WatchstarPopup-message"
				aria-live="polite"
			>
				<span v-if="!errorMessage" v-i18n-html="message"></span>
				<!-- eslint-disable-next-line vue/no-v-html -- Error message HTML comes from the API. -->
				<span v-else v-html="errorMessage"></span>
			</div>
			<div class="cdx-popover__header__button-wrapper">
				<cdx-button
					ref="closeButton"
					class="cdx-popover__header__close-button"
					weight="quiet"
					type="button"
					:aria-label="$i18n( 'cdx-popover-close-button-label' ).text()"
					@click="isOpen = false"
				>
					<cdx-icon :icon="cdxIconClose"></cdx-icon>
				</cdx-button>
			</div>
		</template>
		<template #default>
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
						:placeholder="labelsPlaceholder"
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
				<template v-if="userHasNoLabels" #help-text>
					<!-- @todo Localize the special page name. -->
					<div v-i18n-html:watchstar-popup-labels-help="[ 'Special:WatchlistLabels' ]"></div>
				</template>
			</cdx-field>
		</template>
		<template v-if="action === 'watch'" #footer>
			<cdx-button
				ref="undoButton"
				action="progressive"
				@click="onUndo"
			>
				<cdx-icon :icon="cdxIconUndo"></cdx-icon>
				{{ $i18n( 'watchstar-popup-undo' ).text() }}
			</cdx-button>
		</template>
	</cdx-popover>
</template>

<script>
const { defineComponent, ref, computed, watch, onMounted, onBeforeUnmount } = require( 'vue' );
const { CdxPopover, CdxField, CdxSelect, CdxMultiselectLookup, CdxButton, CdxIcon } = require( './codex.js' );
const { cdxIconUndo, cdxIconSuccess, cdxIconError, cdxIconInfoFilled, cdxIconClose } = require( './icons.json' );
const api = new mw.Api();
const AUTO_CLOSE_DELAY_MS = 5000;
const DEFAULT_POPOVER_PLACEMENT = 'bottom';

/**
 * Determine whether a watch link is currently rendered in Vector 2022's
 * page tools dropdown (used on narrower viewports).
 *
 * @param {Element|null} link
 * @return {boolean}
 */
function isVectorToolsDropdownWatchLink( link ) {
	if ( !( link instanceof Element ) || !document.body.classList.contains( 'skin-vector-2022' ) ) {
		return false;
	}

	const isInVectorDropdown = !!link.closest( '.vector-page-toolbar-container .vector-dropdown, .vector-page-toolbar-container .vector-dropdown-content' );
	const isInToolsPortlet = !!link.closest( '#p-tb, #vector-page-tools-dropdown' );

	return isInVectorDropdown || isInToolsPortlet;
}

/**
 * Use side placement for the tools-dropdown watch link to avoid clipping on
 * narrow layouts; keep bottom placement for all other contexts.
 *
 * @param {Element|null} link
 * @return {string}
 */
function getPlacementForLink( link ) {
	if ( !isVectorToolsDropdownWatchLink( link ) ) {
		return DEFAULT_POPOVER_PLACEMENT;
	}

	return document.documentElement.dir === 'rtl' ? 'right-start' : 'left-start';
}

module.exports = defineComponent( {
	name: 'WatchlistPopup',
	components: {
		CdxPopover,
		CdxField,
		CdxSelect,
		CdxMultiselectLookup,
		CdxButton,
		CdxIcon
	},
	props: {
		initialAction: { type: String, default: 'watch' },
		expiryEnabled: { type: Boolean, default: false },
		labelsEnabled: { type: Boolean, default: false },
		dataExpiryOptions: { type: Object, default: () => {} },
		watchResponse: { type: Array, default: () => [] },
		title: { type: mw.Title, default: null },
		preferredExpiry: { type: String, default: '' },
		link: { type: Object, default: () => {} },
		useBottomSheet: { type: Boolean, default: false }
	},
	setup( props ) {
		/** @member {boolean} isOpen Whether the popup is currently open. */
		const isOpen = ref( false );

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

		const errorMessage = ref();

		/** @member {string} messageType One of 'notice', 'error', or 'success'. */
		const messageType = computed( () => {
			if ( errorMessage.value ) {
				return 'error';
			}
			if ( watchResponse.value.watched || watchResponse.value.unwatched ) {
				return 'success';
			}
			return 'notice';
		} );

		/** @member {string} message The name of the system message shown at the top of the popup. */
		const message = computed( () => {
			let messageName = 'watchstar-popup-already-watched';
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

		/** @member {Object} headerIcon Codex icon shown alongside the header message, matching the message type. */
		const headerIcon = computed( () => {
			switch ( messageType.value ) {
				case 'success':
					return cdxIconSuccess;
				case 'error':
					return cdxIconError;
				default:
					return cdxIconInfoFilled;
			}
		} );

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

		/** @member {boolean} userHasNoLabels True when the user has no watchlist labels defined yet. */
		const userHasNoLabels = computed( () => labelsAreLoaded.value && labelsAll.value.length === 0 );

		/** @member {string} labelsPlaceholder Placeholder for the labels input, depending on whether the user has any labels. */
		const labelsPlaceholder = computed( () => mw.msg(
			userHasNoLabels.value ? 'watchstar-popup-labels-none' : 'watchstar-popup-labels-add'
		) );

		// State captured just before an unwatch, so the "Undo" action can restore the
		// page's previous expiry and labels rather than re-adding it indefinitely (T426040).
		const previousExpiry = ref( props.preferredExpiry );
		const previousLabelsSelected = ref( [] );
		const previousLabelsInputChips = ref( [] );

		/** Recompute the labels available in the dropdown, excluding those already added as chips. */
		const refreshLabelsVisible = () => {
			labelsVisible.value = labelsAll.value.filter(
				( l ) => !labelsInputChips.value.some( ( x ) => x.value === l.value )
			);
		};

		const showError = ( code, data ) => {
			errorMessage.value = api.getErrorMessage( data ).html();
		};

		const doWatch = () => {
			window.dispatchEvent( new CustomEvent( 'WatchlistPopup.loading' ) );
			return api.watch( props.title.getPrefixedText(), expiry.value, labelsSelected.value )
				.done( ( newWatchResponse ) => {
					watchResponse.value = newWatchResponse;
					action.value = 'unwatch';
					window.dispatchEvent( new CustomEvent( 'WatchlistPopup.watch', {
						detail: { watchResponse: newWatchResponse }
					} ) );
					errorMessage.value = null;
				} )
				.fail( showError );
		};

		const onUnwatch = ( closePopup = true ) => {
			window.dispatchEvent( new CustomEvent( 'WatchlistPopup.loading' ) );
			return api.unwatch( props.title.getPrefixedText() )
				.done( ( newWatchResponse ) => {
					watchResponse.value = newWatchResponse;
					// Remember the previous expiry and labels so "Undo" can restore them.
					previousExpiry.value = expiry.value;
					previousLabelsSelected.value = labelsSelected.value.slice();
					previousLabelsInputChips.value = labelsInputChips.value.slice();
					labelsSelected.value = [];
					labelsInputChips.value = [];
					expiry.value = props.preferredExpiry;
					action.value = 'watch';
					if ( closePopup ) {
						isOpen.value = false;
					}
					window.dispatchEvent( new CustomEvent( 'WatchlistPopup.unwatch', {
						detail: { watchResponse: newWatchResponse }
					} ) );
					errorMessage.value = null;
				} )
				.fail( showError );
		};

		/**
		 * Re-watch the page, restoring the expiry and labels it had before it was unwatched.
		 *
		 * @return {jQuery.Promise}
		 */
		const onUndo = () => {
			expiry.value = previousExpiry.value;
			labelsSelected.value = previousLabelsSelected.value.slice();
			labelsInputChips.value = previousLabelsInputChips.value.slice();
			refreshLabelsVisible();
			return doWatch();
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

		/** @member {HTMLAnchorElement} anchor The watch link that the popup is currently anchored to. */
		const anchor = ref( props.link );
		const placement = ref( getPlacementForLink( props.link ) );

		/** @member {Object} closeButton Template ref for the header close button. */
		const closeButton = ref();

		/** @member {Object} undoButton Template ref for the footer "Undo" button, only present when action is 'watch'. */
		const undoButton = ref();

		let autoCloseTimer = null;
		let isPopoverHovered = false;
		let isPopoverFocused = false;

		const clearAutoCloseTimer = () => {
			clearTimeout( autoCloseTimer );
			autoCloseTimer = null;
		};

		const maybeStartAutoCloseTimer = () => {
			clearAutoCloseTimer();
			if (
				!isOpen.value ||
				isPopoverHovered ||
				isPopoverFocused ||
				action.value === 'watch'
			) {
				return;
			}
			autoCloseTimer = setTimeout( () => {
				isOpen.value = false;
			}, AUTO_CLOSE_DELAY_MS );
		};

		const onPopoverMouseEnter = () => {
			isPopoverHovered = true;
			clearAutoCloseTimer();
		};

		const onPopoverMouseLeave = () => {
			isPopoverHovered = false;
			maybeStartAutoCloseTimer();
		};

		const onPopoverFocusIn = () => {
			isPopoverFocused = true;
			clearAutoCloseTimer();
		};

		const onPopoverFocusOut = ( e ) => {
			isPopoverFocused = !!( e.relatedTarget && e.currentTarget.contains( e.relatedTarget ) );
			if ( !isPopoverFocused ) {
				maybeStartAutoCloseTimer();
			}
		};

		/**
		 * Allows user to tab into the expiry dropdown from the watch link.
		 *
		 * @param {KeyboardEvent} e
		 */
		const tabKeyListener = ( e ) => {
			if ( e.key !== 'Tab' && ( e.keyCode || e.which ) !== 9 ) {
				return;
			}

			if ( document.activeElement === props.link && e.shiftKey ) {
				// We're on the watchstar and reverse-tabbing, so go to the end of the popover.
				e.preventDefault();
				// eslint-disable-next-line no-jquery/no-event-shorthand -- $el is a DOM node, not a jQuery object.
				( undoButton.value || closeButton.value ).$el.focus();
			} else if ( document.activeElement === props.link ) {
				// We're on the watchstar, tab to the close button (as the first focusable element).
				e.preventDefault();
				// eslint-disable-next-line no-jquery/no-event-shorthand -- $el is a DOM node, not a jQuery object.
				closeButton.value.$el.focus();
			} else if (
				// We're in the footer and tabbing.
				( !e.shiftKey && $( e.target ).parents( '.cdx-popover__footer' ).length ) ||
				// Or we're in the header and reverse-tabbing.
				( e.shiftKey && $( e.target ).parents( '.cdx-popover__header' ).length )
			) {
				// Move focus back to the watch link.
				e.preventDefault();
				props.link.focus();
			}
		};

		// Promise for the initial watch-state request (set in onMounted), so that
		// openPopup can wait for the current expiry and labels to load before
		// unwatching, allowing "Undo" to restore them (T426040).
		let watchStateLoaded = null;

		// The popup only becomes visible once the watch or unwatch request has finished,
		// so guard against a second click landing in that window and firing another
		// request, which would overwrite the state "Undo" restores.
		let isRequestPending = false;

		const openPopup = function ( newAnchor ) {
			if ( isRequestPending ) {
				return;
			}
			isRequestPending = true;
			anchor.value = newAnchor;
			placement.value = getPlacementForLink( newAnchor );
			const doAction = () => {
				const request = action.value === 'watch' ?
					doWatch() :
					onUnwatch( false );
				request.always( () => {
					isRequestPending = false;
					isOpen.value = true;
				} );
			};
			if ( action.value === 'unwatch' && watchStateLoaded ) {
				watchStateLoaded.always( doAction );
			} else {
				doAction();
			}
		};

		onMounted( () => {
			watchStateLoaded = api.get( {
				action: 'query',
				meta: 'userinfo',
				uiprop: 'watchlistlabels',

				prop: 'info',
				titles: props.title.getPrefixedText(),
				inprop: 'watched|watchlistexpiry|watchlistlabels',

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
				// Only include labels that have not yet been added as chips.
				refreshLabelsVisible();
			} );
		} );

		onBeforeUnmount( () => {
			clearAutoCloseTimer();
			$( window ).off( 'keydown.watchlistExpiry' );
		} );

		// Manage popup keyboard trapping and reset transient state on close.
		watch( isOpen, ( newVal ) => {
			if ( newVal ) {
				maybeStartAutoCloseTimer();
				$( window ).on( 'keydown.watchlistExpiry', tabKeyListener );
			} else {
				clearAutoCloseTimer();
				isPopoverHovered = false;
				isPopoverFocused = false;
				watchResponse.value = {};
				$( window ).off( 'keydown.watchlistExpiry' );
			}
		} );

		watch( action, maybeStartAutoCloseTimer );

		watch( expiry, ( newExpiry ) => {
			if ( newExpiry && expiries.value.filter( ( x ) => x.value === newExpiry ).length === 0 ) {
				const daysLeftMsg = daysLeft.value > 0 ?
					mw.msg( 'watchlist-expiry-days-left', daysLeft.value ) :
					mw.msg( 'watchlist-expiry-hours-left' );
				expiries.value.push( { value: newExpiry, label: daysLeftMsg } );
			}
		} );

		return {
			isOpen,
			action,
			message,
			messageType,
			headerIcon,
			errorMessage,
			expiries,
			expiry,
			onExpiryUpdateSelected,
			labelsVisible,
			labelsSelected,
			labelsInputChips,
			labelsAreLoaded,
			userHasNoLabels,
			labelsPlaceholder,
			cdxIconUndo,
			cdxIconClose,
			onUndo,
			onLabelsInput,
			onLabelsUpdateSelected,
			onLabelsUpdateInputChips,
			onPopoverMouseEnter,
			onPopoverMouseLeave,
			onPopoverFocusIn,
			onPopoverFocusOut,
			// eslint-disable-next-line vue/no-unused-properties
			openPopup,
			anchor,
			placement,
			closeButton,
			undoButton
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-watchstar-WatchlistPopup {
	min-width: @min-width-breakpoint-mobile;

	.cdx-label__label__text {
		font-weight: normal;
	}

	// CdxField always renders the help-text wrapper; collapse it when we omit
	// the help text (i.e. when the user already has labels) to avoid a stray gap.
	.cdx-field__help-text:empty {
		display: none;
	}

	&__header-icon {
		flex-shrink: 0;

		&--success {
			color: @color-icon-success;
		}

		&--error {
			color: @color-icon-error;
		}

		&--notice {
			color: @color-icon-notice;
		}
	}

	// The header message is a sentence, not a short label, so allow it to
	// wrap and sit at a comfortable reading weight rather than a bold title.
	&__header-title.cdx-popover__header__title {
		font-weight: normal;
	}
}
</style>
