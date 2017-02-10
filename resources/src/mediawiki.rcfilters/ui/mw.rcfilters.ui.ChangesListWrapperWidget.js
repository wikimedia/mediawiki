( function ( mw ) {
	/**
	 * List of changes
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.ChangesListViewModel} filtersViewModel View model
	 * @param {mw.rcfilters.dm.FiltersViewModel} changesListViewModel View model
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
	 * @param {jQuery|string} changesListContent The content of the updated changes list
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onModelUpdate = function ( changesListContent ) {
		var isEmpty = changesListContent === 'NO_RESULTS';
		this.$element.toggleClass( 'mw-changeslist', !isEmpty );
		this.$element.toggleClass( 'mw-changeslist-empty', isEmpty );
		if ( isEmpty ) {
			this.changesListContent = null;
			this.$element.empty().append(
				document.createTextNode( mw.message( 'recentchanges-noresult' ).text() )
			);
		} else {
			this.changesListContent = changesListContent;
			this.$element.empty().append( this.changesListContent );
			this.applyHighlight();
		}
		this.popPending();
	};

	/**
	 * Apply color classes based on filters highlight configuration
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.applyHighlight = function () {
		var classesMap = {},
			highlightedItems = this.filtersViewModel.getHighlightedItems(),
			$liList = this.$element.find( 'ul.special li' );

		// Map the highlighted identifiers to their colors
		highlightedItems.forEach( function ( filterItem ) {
			classesMap[ filterItem.getIdentifier() ] = filterItem.getHighlightColor();
		} );

		// Add the highlight container to all li's
		// with an empty circle
		$liList.each( function () {
			var $li = $( this );
				$highlights = $li.find( '.mw-rcfilters-ui-changesListWrapperWidget-highlights' );

			if ( !$highlights.length ) {
				// Prepend highlights
				$li.prepend(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights' )
						.append(
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-color-none' )
						)
				);
			}

			// Check if the item needs to be highlighted
			Object.keys( classesMap ).forEach( function ( className ) {
				if ( $li.hasClass( className ) ) {
					// Add highlight
					$li.addClass( classesMap[ className ] );

					// Add circle
					$highlights.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-color-none' )
					);
				}
			} );

			// If no highlights were added, add the 'none' circle
			if ( $highlights.children().length === 0 ) {
				$highlights.append(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-color-none' )
				);
			}
		} );

		// Add highlights to relevant items
		highlightedItems.forEach( function ( filterItem ) {
			var $li = this.$element.find( '.' + filterItem.getIdentifier() ),
				$highlights = $li.find( '.mw-rcfilters-ui-changesListWrapperWidget-highlights' );

			if ( $li.length ) {
				// Add highlight class
				$li.addClass( '.' + filterItem.getHighlightColor() );

				// Remove 'none' from highlight circles
				$highlights.find( '.mw-rcfilters-ui-changesListWrapperWidget-highlights-color-none' ).detach();

				// Add color to highlight circles
				$highlights.append(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-color-' + filterItem.getHighlightColor() )
				);
			}

			// Adjust highlight positioning
			$highlights.css( 'margin-left', -( $highlights.outerWidth() ) );
		}.bind( this ) );

		this.$element.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlighted' );
	};

	/**
	 * Remove all color classes
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.clearHighlight = function () {
		this.filtersViewModel.getHighlightedItems().forEach( function ( filterItem ) {
			var $el = this.$element.find( '.' + filterItem.getIdentifier() ),
				$highlights = $el.find( '.mw-rcfilters-ui-changesListWrapperWidget-highlights' );

			$el.removeClass( filterItem.getHighlightColor() );

			// Replace the highlight circles with the 'none' circle
			$highlights
				.empty()
				.append(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-color-none' )
				)
				.css( 'margin-left', -( $highlights.width() ) );
		}.bind( this ) );

		// mw.rcfilters.HighlightColors.forEach( function ( className ) {
		// 	this.$element.find( '.' + className )
		// 		.removeClass( className );

		// 	// Replace the highlight circles with the 'none' circle
		// 	this.$element
		// 		.find( '.mw-rcfilters-ui-changesListWrapperWidget-highlights' )
		// 			.empty()
		// 			.append(
		// 				$( '<div>' )
		// 					.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-color-none' )
		// 			);

		// 	$highlights.css( 'margin-left', -( $highlights.width() ) );
		// }.bind( this ) );

		this.$element.removeClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlighted' );
	};
}( mediaWiki ) );
