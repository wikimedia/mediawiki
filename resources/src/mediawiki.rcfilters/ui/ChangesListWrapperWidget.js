( function () {
	/**
	 * List of changes
	 *
	 * @class mw.rcfilters.ui.ChangesListWrapperWidget
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersViewModel View model
	 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListViewModel View model
	 * @param {mw.rcfilters.Controller} controller
	 * @param {jQuery} $changesListRoot Root element of the changes list to attach to
	 * @param {Object} [config] Configuration object
	 */
	var ChangesListWrapperWidget = function MwRcfiltersUiChangesListWrapperWidget(
		filtersViewModel,
		changesListViewModel,
		controller,
		$changesListRoot,
		config
	) {
		config = $.extend( {}, config, {
			$element: $changesListRoot
		} );

		// Parent
		ChangesListWrapperWidget.parent.call( this, config );

		this.filtersViewModel = filtersViewModel;
		this.changesListViewModel = changesListViewModel;
		this.controller = controller;
		this.highlightClasses = null;

		// Events
		this.filtersViewModel.connect( this, {
			itemUpdate: 'onItemUpdate',
			highlightChange: 'onHighlightChange'
		} );
		this.changesListViewModel.connect( this, {
			invalidate: 'onModelInvalidate',
			update: 'onModelUpdate'
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-changesListWrapperWidget' )
			// We handle our own display/hide of the empty results message
			// We keep the timeout class here and remove it later, since at this
			// stage it is still needed to identify that the timeout occurred.
			.removeClass( 'mw-changeslist-empty' );
	};

	/* Initialization */

	OO.inheritClass( ChangesListWrapperWidget, OO.ui.Widget );

	/**
	 * Get all available highlight classes
	 *
	 * @return {string[]} An array of available highlight class names
	 */
	ChangesListWrapperWidget.prototype.getHighlightClasses = function () {
		if ( !this.highlightClasses || !this.highlightClasses.length ) {
			this.highlightClasses = this.filtersViewModel.getItemsSupportingHighlights()
				.map( function ( filterItem ) {
					return filterItem.getCssClass();
				} );
		}

		return this.highlightClasses;
	};

	/**
	 * Respond to the highlight feature being toggled on and off
	 *
	 * @param {boolean} highlightEnabled
	 */
	ChangesListWrapperWidget.prototype.onHighlightChange = function ( highlightEnabled ) {
		if ( highlightEnabled ) {
			this.applyHighlight();
		} else {
			this.clearHighlight();
		}
	};

	/**
	 * Respond to a filter item model update
	 */
	ChangesListWrapperWidget.prototype.onItemUpdate = function () {
		if ( this.controller.isInitialized() && this.filtersViewModel.isHighlightEnabled() ) {
			// this.controller.isInitialized() is still false during page load,
			// we don't want to clear/apply highlights at this stage.
			this.clearHighlight();
			this.applyHighlight();
		}
	};

	/**
	 * Respond to changes list model invalidate
	 */
	ChangesListWrapperWidget.prototype.onModelInvalidate = function () {
		$( 'body' ).addClass( 'mw-rcfilters-ui-loading' );
	};

	/**
	 * Respond to changes list model update
	 *
	 * @param {jQuery|string} $changesListContent The content of the updated changes list
	 * @param {jQuery} $fieldset The content of the updated fieldset
	 * @param {string} noResultsDetails Type of no result error
	 * @param {boolean} isInitialDOM Whether $changesListContent is the existing (already attached) DOM
	 * @param {boolean} from Timestamp of the new changes
	 */
	ChangesListWrapperWidget.prototype.onModelUpdate = function (
		$changesListContent, $fieldset, noResultsDetails, isInitialDOM, from
	) {
		var conflictItem,
			$message = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-results' ),
			isEmpty = $changesListContent === 'NO_RESULTS',
			// For enhanced mode, we have to load these modules, which are
			// not loaded for the 'regular' mode in the backend
			loaderPromise = mw.user.options.get( 'usenewrc' ) ?
				mw.loader.using( [ 'mediawiki.special.changeslist.enhanced', 'mediawiki.icon' ] ) :
				$.Deferred().resolve(),
			widget = this;

		this.$element.toggleClass( 'mw-changeslist', !isEmpty );
		if ( isEmpty ) {
			this.$element.empty();

			if ( this.filtersViewModel.hasConflict() ) {
				conflictItem = this.filtersViewModel.getFirstConflictedItem();

				$message
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-results-conflict' )
							.text( mw.message( 'rcfilters-noresults-conflict' ).text() ),
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-results-message' )
							.text( mw.message( conflictItem.getCurrentConflictResultMessage() ).text() )
					);
			} else {
				$message
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-results-noresult' )
							.text( mw.msg( this.getMsgKeyForNoResults( noResultsDetails ) ) )
					);

				// remove all classes matching mw-changeslist-*
				this.$element.removeClass( function ( elementIndex, allClasses ) {
					return allClasses
						.split( ' ' )
						.filter( function ( className ) {
							return className.indexOf( 'mw-changeslist-' ) === 0;
						} )
						.join( ' ' );
				} );
			}

			this.$element.append( $message );
		} else {
			if ( !isInitialDOM ) {
				this.$element.empty().append( $changesListContent );

				if ( from ) {
					this.emphasizeNewChanges( from );
				}
			}

			// Apply highlight
			this.applyHighlight();

		}

		this.$element.prepend( $( '<div>' ).addClass( 'mw-changeslist-overlay' ) );

		loaderPromise.done( function () {
			if ( !isInitialDOM && !isEmpty ) {
				// Make sure enhanced RC re-initializes correctly
				mw.hook( 'wikipage.content' ).fire( widget.$element );
			}

			$( 'body' ).removeClass( 'mw-rcfilters-ui-loading' );
		} );
	};

	/** Toggles overlay class on changes list
	 *
	 * @param {boolean} isVisible True if overlay should be visible
	 */
	ChangesListWrapperWidget.prototype.toggleOverlay = function ( isVisible ) {
		this.$element.toggleClass( 'mw-rcfilters-ui-changesListWrapperWidget--overlaid', isVisible );
	};

	/**
	 * Map a reason for having no results to its message key
	 *
	 * @param {string} reason One of the NO_RESULTS_* "constant" that represent
	 *   a reason for having no results
	 * @return {string} Key for the message that explains why there is no results in this case
	 */
	ChangesListWrapperWidget.prototype.getMsgKeyForNoResults = function ( reason ) {
		var reasonMsgKeyMap = {
			NO_RESULTS_NORMAL: 'recentchanges-noresult',
			NO_RESULTS_TIMEOUT: 'recentchanges-timeout',
			NO_RESULTS_NETWORK_ERROR: 'recentchanges-network',
			NO_RESULTS_NO_TARGET_PAGE: 'recentchanges-notargetpage',
			NO_RESULTS_INVALID_TARGET_PAGE: 'allpagesbadtitle'
		};
		return reasonMsgKeyMap[ reason ];
	};

	/**
	 * Emphasize the elements (or groups) newer than the 'from' parameter
	 * @param {string} from Anything newer than this is considered 'new'
	 */
	ChangesListWrapperWidget.prototype.emphasizeNewChanges = function ( from ) {
		var $firstNew,
			$indicator,
			$newChanges = $( [] ),
			selector = this.inEnhancedMode() ?
				'table.mw-enhanced-rc[data-mw-ts]' :
				'li[data-mw-ts]',
			set = this.$element.find( selector ),
			length = set.length;

		set.each( function ( index ) {
			var $this = $( this ),
				ts = $this.data( 'mw-ts' );

			if ( ts >= from ) {
				$newChanges = $newChanges.add( $this );
				$firstNew = $this;

				// guards against putting the marker after the last element
				if ( index === ( length - 1 ) ) {
					$firstNew = null;
				}
			}
		} );

		if ( $firstNew ) {
			$indicator = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-previousChangesIndicator' );

			$firstNew.after( $indicator );
		}

		// FIXME: Use CSS transition
		// eslint-disable-next-line no-jquery/no-fade
		$newChanges
			.hide()
			.fadeIn( 1000 );
	};

	/**
	 * In enhanced mode, we need to check whether the grouped results all have the
	 * same active highlights in order to see whether the "parent" of the group should
	 * be grey or highlighted normally.
	 *
	 * This is called every time highlights are applied.
	 */
	ChangesListWrapperWidget.prototype.updateEnhancedParentHighlight = function () {
		var activeHighlightClasses,
			$enhancedTopPageCell = this.$element.find( 'table.mw-enhanced-rc.mw-collapsible' );

		activeHighlightClasses = this.filtersViewModel.getCurrentlyUsedHighlightColors().map( function ( color ) {
			return 'mw-rcfilters-highlight-color-' + color;
		} );

		// Go over top pages and their children, and figure out if all sub-pages have the
		// same highlights between themselves. If they do, the parent should be highlighted
		// with all colors. If classes are different, the parent should receive a grey
		// background
		$enhancedTopPageCell.each( function () {
			var firstChildClasses, $rowsWithDifferentHighlights,
				$table = $( this );

			// Collect the relevant classes from the first nested child
			firstChildClasses = activeHighlightClasses.filter( function ( className ) {
				return $table.find( 'tr:nth-child(2)' ).hasClass( className );
			} );
			// Filter the non-head rows and see if they all have the same classes
			// to the first row
			$rowsWithDifferentHighlights = $table.find( 'tr:not(:first-child)' ).filter( function () {
				var classesInThisRow,
					$this = $( this );

				classesInThisRow = activeHighlightClasses.filter( function ( className ) {
					return $this.hasClass( className );
				} );

				return !OO.compare( firstChildClasses, classesInThisRow );
			} );

			// If classes are different, tag the row for using grey color
			$table.find( 'tr:first-child' )
				.toggleClass( 'mw-rcfilters-ui-changesListWrapperWidget-enhanced-grey', $rowsWithDifferentHighlights.length > 0 );
		} );
	};

	/**
	 * @return {boolean} Whether the changes are grouped by page
	 */
	ChangesListWrapperWidget.prototype.inEnhancedMode = function () {
		var uri = new mw.Uri();
		return ( uri.query.enhanced !== undefined && Number( uri.query.enhanced ) ) ||
			( uri.query.enhanced === undefined && Number( mw.user.options.get( 'usenewrc' ) ) );
	};

	/**
	 * Apply color classes based on filters highlight configuration
	 */
	ChangesListWrapperWidget.prototype.applyHighlight = function () {
		if ( !this.filtersViewModel.isHighlightEnabled() ) {
			return;
		}

		this.filtersViewModel.getHighlightedItems().forEach( function ( filterItem ) {
			var $elements = this.$element.find( '.' + filterItem.getCssClass() );

			// Add highlight class to all highlighted list items
			$elements
				.addClass(
					'mw-rcfilters-highlighted ' +
					'mw-rcfilters-highlight-color-' + filterItem.getHighlightColor()
				);

			// Track the filters for each item in .data( 'highlightedFilters' )
			$elements.each( function () {
				var filters = $( this ).data( 'highlightedFilters' );
				if ( !filters ) {
					filters = [];
					$( this ).data( 'highlightedFilters', filters );
				}
				if ( filters.indexOf( filterItem.getLabel() ) === -1 ) {
					filters.push( filterItem.getLabel() );
				}
			} );
		}.bind( this ) );
		// Apply a title to each highlighted item, with a list of filters
		this.$element.find( '.mw-rcfilters-highlighted' ).each( function () {
			var filters = $( this ).data( 'highlightedFilters' );

			if ( filters && filters.length ) {
				$( this ).attr( 'title', mw.msg(
					'rcfilters-highlighted-filters-list',
					filters.join( mw.msg( 'comma-separator' ) )
				) );
			}

		} );
		if ( this.inEnhancedMode() ) {
			this.updateEnhancedParentHighlight();
		}

		// Turn on highlights
		this.$element.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlighted' );
	};

	/**
	 * Remove all color classes
	 */
	ChangesListWrapperWidget.prototype.clearHighlight = function () {
		// Remove highlight classes
		mw.rcfilters.HighlightColors.forEach( function ( color ) {
			this.$element
				.find( '.mw-rcfilters-highlight-color-' + color )
				.removeClass( 'mw-rcfilters-highlight-color-' + color );
		}.bind( this ) );

		this.$element.find( '.mw-rcfilters-highlighted' )
			.removeAttr( 'title' )
			.removeData( 'highlightedFilters' )
			.removeClass( 'mw-rcfilters-highlighted' );

		// Remove grey from enhanced rows
		this.$element.find( '.mw-rcfilters-ui-changesListWrapperWidget-enhanced-grey' )
			.removeClass( 'mw-rcfilters-ui-changesListWrapperWidget-enhanced-grey' );

		// Turn off highlights
		this.$element.removeClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlighted' );
	};

	module.exports = ChangesListWrapperWidget;
}() );
