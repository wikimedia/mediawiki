( function ( mw ) {
	/**
	 * List of changes
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersViewModel View model
	 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListViewModel View model
	 * @param {mw.rcfilters.Controller} controller
	 * @param {jQuery} $changesListRoot Root element of the changes list to attach to
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget = function MwRcfiltersUiChangesListWrapperWidget(
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
		mw.rcfilters.ui.ChangesListWrapperWidget.parent.call( this, config );

		this.filtersViewModel = filtersViewModel;
		this.changesListViewModel = changesListViewModel;
		this.controller = controller;

		// Events
		this.filtersViewModel.connect( this, {
			itemUpdate: 'onItemUpdate',
			highlightChange: 'onHighlightChange'
		} );
		this.changesListViewModel.connect( this, {
			invalidate: 'onModelInvalidate',
			update: 'onModelUpdate',
			newChangesExist: 'onNewChangesExist'
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-changesListWrapperWidget' )
			// We handle our own display/hide of the empty results message
			.removeClass( 'mw-changeslist-empty' );

		// Set up highlight containers
		this.setupHighlightContainers( this.$element );

		this.setupNewChangesButtonContainer( this.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.ChangesListWrapperWidget, OO.ui.Widget );

	/**
	 * Respond to the highlight feature being toggled on and off
	 *
	 * @param {boolean} highlightEnabled
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onHighlightChange = function ( highlightEnabled ) {
		if ( highlightEnabled ) {
			this.applyHighlight();
		} else {
			this.clearHighlight();
		}
	};

	/**
	 * Respond to a filter item model update
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onItemUpdate = function () {
		if ( this.filtersViewModel.isHighlightEnabled() ) {
			this.clearHighlight();
			this.applyHighlight();
		}
	};

	/**
	 * Respond to changes list model invalidate
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onModelInvalidate = function () {
		$( '.rcfilters-spinner' ).removeClass( 'mw-rcfilters-ui-ready' );
		this.$element.removeClass( 'mw-rcfilters-ui-ready' );
	};

	/**
	 * Respond to changes list model update
	 *
	 * @param {jQuery|string} $changesListContent The content of the updated changes list
	 * @param {jQuery} $fieldset The content of the updated fieldset
	 * @param {boolean} isInitialDOM Whether $changesListContent is the existing (already attached) DOM
	 * @param {boolean} from Timestamp of the new changes
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onModelUpdate = function (
		$changesListContent, $fieldset, isInitialDOM, from
	) {
		var conflictItem,
			$message = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-results' ),
			isEmpty = $changesListContent === 'NO_RESULTS',
			$lastSeen,
			$indicator;

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
							.text( mw.message( 'recentchanges-noresult' ).text() )
					);
			}

			this.$element.append( $message );
		} else {
			if ( !isInitialDOM ) {
				this.$element.empty().append( $changesListContent );

				if ( from ) {
					$indicator = $( '<div>' )
						.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-previousChangesIndicator' )
						.text( mw.message( 'rcfilters-previous-changes-label' ).text() );

					$indicator.on( 'click', function () {
						$indicator.detach();
					} );

					$lastSeen = null;
					this.$element.find( 'li[data-mw-ts]' ).each( function () {
						var $li = $( this ),
							ts = $li.data( 'mw-ts' );

						if ( ts >= from ) {
							$li.addClass( 'mw-rcfilters-newChange' );
						} else if ( $lastSeen === null ) {
							$lastSeen = $li;
							return false;
						}
					} );

					if ( $lastSeen ) {
						$lastSeen.before( $indicator );
					}

					$( '.mw-rcfilters-newChange, .mw-rcfilters-ui-changesListWrapperWidget-previousChangesIndicator' )
						.hide()
						.fadeIn( 1000 );
				}
			}

			// Set up highlight containers
			this.setupHighlightContainers( this.$element );

			// Apply highlight
			this.applyHighlight();

			if ( !isInitialDOM ) {
				// Make sure enhanced RC re-initializes correctly
				mw.hook( 'wikipage.content' ).fire( this.$element );
			}
		}

		$( '.rcfilters-spinner' ).addClass( 'mw-rcfilters-ui-ready' );
		this.$element.addClass( 'mw-rcfilters-ui-ready' );
	};

	/**
	 * Respond to changes list model newChangesExist
	 *
	 * @param {boolean} newChangesExist Whether new changes exist
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onNewChangesExist = function ( newChangesExist ) {
		this.showNewChangesLink.toggle( newChangesExist );
	};

	/**
	 * Respond to the user clicking the 'show new changes' button
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onShowNewChangesClick = function () {
		this.controller.showNewChanges();
	};

	/**
	 * Setup the container for the 'new changes' button.
	 *
	 * @param {jQuery} $content
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.setupNewChangesButtonContainer = function ( $content ) {
		this.showNewChangesLink = new OO.ui.ButtonWidget( {
			framed: false,
			label: mw.message( 'rcfilters-show-new-changes' ).text(),
			flags: [ 'progressive' ]
		} );
		this.showNewChangesLink.connect( this, { click: 'onShowNewChangesClick' } );
		this.showNewChangesLink.toggle( false );

		$content.before(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-newChanges' )
				.append( this.showNewChangesLink.$element )
		);
	};

	/**
	 * Set up the highlight containers with all color circle indicators.
	 *
	 * @param {jQuery|string} $content The content of the updated changes list
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.setupHighlightContainers = function ( $content ) {
		var uri = new mw.Uri(),
			highlightClass = 'mw-rcfilters-ui-changesListWrapperWidget-highlights',
			$highlights = $( '<div>' )
				.addClass( highlightClass )
				.append(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-color-none' )
						.prop( 'data-color', 'none' )
				);

		$( '.' + highlightClass ).detach();

		mw.rcfilters.HighlightColors.forEach( function ( color ) {
			$highlights.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-color-' + color )
					.prop( 'data-color', color )
			);
		} );

		if (
			( uri.query.enhanced !== undefined && Number( uri.query.enhanced ) ) ||
			( uri.query.enhanced === undefined && Number( mw.user.options.get( 'usenewrc' ) ) )
		) {
			// Enhanced RC
			$content.find( 'td.mw-enhanced-rc' )
				.parent()
				.prepend(
					$( '<td>' )
						.append( $highlights.clone() )
				);
		} else {
			// Regular RC
			$content.find( 'ul.special li' )
				.prepend( $highlights.clone() );
		}
	};

	/**
	 * Apply color classes based on filters highlight configuration
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.applyHighlight = function () {
		if ( !this.filtersViewModel.isHighlightEnabled() ) {
			return;
		}

		this.filtersViewModel.getHighlightedItems().forEach( function ( filterItem ) {
			// Add highlight class to all highlighted list items
			this.$element.find( '.' + filterItem.getCssClass() )
				.addClass( 'mw-rcfilters-highlight-color-' + filterItem.getHighlightColor() );
		}.bind( this ) );

		// Turn on highlights
		this.$element.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlighted' );
	};

	/**
	 * Remove all color classes
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.clearHighlight = function () {
		// Remove highlight classes
		mw.rcfilters.HighlightColors.forEach( function ( color ) {
			this.$element.find( '.mw-rcfilters-highlight-color-' + color ).removeClass( 'mw-rcfilters-highlight-color-' + color );
		}.bind( this ) );

		// Turn off highlights
		this.$element.removeClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlighted' );
	};
}( mediaWiki ) );
