( function ( mw ) {
	/**
	 * List of changes
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersViewModel View model
	 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListViewModel View model
	 * @param {jQuery} $changesListRoot Root element of the changes list to attach to
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget = function MwRcfiltersUiChangesListWrapperWidget(
		filtersViewModel,
		changesListViewModel,
		$changesListRoot,
		config
	) {
		config = $.extend( {}, config, {
			$element: $changesListRoot
		} );

		// Parent
		mw.rcfilters.ui.ChangesListWrapperWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, config );

		this.filtersViewModel = filtersViewModel;
		this.changesListViewModel = changesListViewModel;

		// Events
		this.filtersViewModel.connect( this, {
			itemUpdate: 'onItemUpdate',
			highlightChange: 'onHighlightChange'
		} );
		this.changesListViewModel.connect( this, {
			invalidate: 'onModelInvalidate',
			update: 'onModelUpdate'
		} );

		this.$element.addClass( 'mw-rcfilters-ui-changesListWrapperWidget' );

		// Set up highlight containers
		this.setupHighlightContainers( this.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.ChangesListWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.ChangesListWrapperWidget, OO.ui.mixin.PendingElement );

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
		this.pushPending();
	};

	/**
	 * Respond to changes list model update
	 *
	 * @param {jQuery|string} $changesListContent The content of the updated changes list
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onModelUpdate = function ( $changesListContent ) {
		var conflictItem,
			$message = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-results' ),
			isEmpty = $changesListContent === 'NO_RESULTS';

		this.$element.toggleClass( 'mw-changeslist', !isEmpty );
		this.$element.toggleClass( 'mw-changeslist-empty', isEmpty );
		if ( isEmpty ) {
			this.$changesListContent = null;
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
			this.$changesListContent = $changesListContent;
			this.$element.empty().append( this.$changesListContent );
			// Set up highlight containers
			this.setupHighlightContainers( this.$element );

			// Apply highlight
			this.applyHighlight();

			// Make sure enhanced RC re-initializes correctly
			mw.hook( 'wikipage.content' ).fire( this.$element );
		}
		this.popPending();
	};

	/**
	 * Set up the highlight containers with all color circle indicators.
	 *
	 * @param {jQuery|string} $content The content of the updated changes list
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.setupHighlightContainers = function ( $content ) {
		var $highlights = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights' )
				.append(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-color-none' )
						.prop( 'data-color', 'none' )
				);

		if ( $( '.mw-rcfilters-ui-changesListWrapperWidget-highlights' ).length ) {
			// Already set up
			return;
		}

		mw.rcfilters.HighlightColors.forEach( function ( color ) {
			$highlights.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-color-' + color )
					.prop( 'data-color', color )
			);
		} );

		if ( Number( mw.user.options.get( 'usenewrc' ) ) ) {
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
