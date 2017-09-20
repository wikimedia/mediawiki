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
		this.highlightClasses = null;
		this.filtersModelInitialized = false;

		// Events
		this.filtersViewModel.connect( this, {
			itemUpdate: 'onItemUpdate',
			highlightChange: 'onHighlightChange',
			initialize: 'onFiltersModelInitialize'
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

		this.setupNewChangesButtonContainer( this.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.ChangesListWrapperWidget, OO.ui.Widget );

	/**
	 * Respond to filters model initialize event
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onFiltersModelInitialize = function () {
		this.filtersModelInitialized = true;
		// Set up highlight containers. We need to wait for the filters model
		// to be initialized, so we can make sure we have all the css class definitions
		// we get from the server with our filters
		this.setupHighlightContainers( this.$element );
	};

	/**
	 * Get all available highlight classes
	 *
	 * @return {string[]} An array of available highlight class names
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.getHighlightClasses = function () {
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
		if ( this.filtersModelInitialized && this.filtersViewModel.isHighlightEnabled() ) {
			this.clearHighlight();
			this.applyHighlight();
		}
	};

	/**
	 * Respond to changes list model invalidate
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onModelInvalidate = function () {
		$( 'body' ).addClass( 'mw-rcfilters-ui-loading' );
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
							.text( mw.message( 'recentchanges-noresult' ).text() )
					);
			}

			this.$element.append( $message );
		} else {
			if ( !isInitialDOM ) {
				this.$element.empty().append( $changesListContent );

				if ( from ) {
					this.emphasizeNewChanges( from );
				}
			}

			// Set up highlight containers
			this.setupHighlightContainers( this.$element );

			// Apply highlight
			this.applyHighlight();

		}

		loaderPromise.done( function () {
			if ( !isInitialDOM && !isEmpty ) {
				// Make sure enhanced RC re-initializes correctly
				mw.hook( 'wikipage.content' ).fire( widget.$element );
			}

			$( 'body' ).removeClass( 'mw-rcfilters-ui-loading' );
		} );
	};

	/**
	 * Emphasize the elements (or groups) newer than the 'from' parameter
	 * @param {string} from Anything newer than this is considered 'new'
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.emphasizeNewChanges = function ( from ) {
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

		$newChanges
			.hide()
			.fadeIn( 1000 );
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
		var $enhancedTopPageCell, $enhancedNestedPagesCell,
			widget = this,
			highlightClass = 'mw-rcfilters-ui-changesListWrapperWidget-highlights',
			$highlights = $( '<div>' )
				.addClass( highlightClass )
				.append(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-circle' )
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
					.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlights-circle' )
					.prop( 'data-color', color )
			);
		} );

		if ( this.inEnhancedMode() ) {
			$enhancedTopPageCell = $content.find( 'table.mw-enhanced-rc.mw-collapsible' );
			$enhancedNestedPagesCell = $content.find( 'td.mw-enhanced-rc-nested' );

			// Enhanced RC highlight containers
			$content.find( 'table.mw-enhanced-rc tr:first-child' )
				.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-enhanced-toplevel' )
				.prepend(
					$( '<td>' )
						.append( $highlights.clone() )
				);

			// We are adding and changing cells in a table that, despite having nested rows,
			// is actually all one big table. To do that right, we want to remove the 'placeholder'
			// cell from the top row, because we're actually adding that placeholder in the children
			// with the highlights.
			$content.find( 'table.mw-enhanced-rc tr:first-child td.mw-changeslist-line-prefix' )
				.detach();
			$content.find( 'table.mw-enhanced-rc tr:first-child td.mw-enhanced-rc' )
				.prop( 'colspan', '2' );

			$enhancedNestedPagesCell
				.before(
					$( '<td>' )
						.append( $highlights.clone().addClass( 'mw-enhanced-rc-nested' ) )
				);

			// We need to target the nested rows differently than the top rows so that the
			// LESS rules applies correctly. In top rows, the rule should highlight all but
			// the first 2 cells td:not( :nth-child( -n+2 ) and the nested rows, the rule
			// should highlight all but the first 3 cells td:not( :nth-child( -n+3 )
			$enhancedNestedPagesCell
				.closest( 'tr' )
				.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-enhanced-nested' );

			// Go over pages that have sub results
			// HACK: We really only can collect those by targetting the collapsible class
			$enhancedTopPageCell.each( function () {
				var collectedClasses,
					$table = $( this );

				// Go over <tr>s and pick up all recognized classes
				collectedClasses = widget.getHighlightClasses().filter( function ( className ) {
					return $table.find( 'tr' ).hasClass( className );
				} );

				$table.find( 'tr:first-child' )
					.addClass( collectedClasses.join( ' ' ) );
			} );

			$content.addClass( 'mw-rcfilters-ui-changesListWrapperWidget-enhancedView' );
		} else {
			// Regular RC
			$content.find( 'ul.special li' )
				.prepend( $highlights.clone() );
		}
	};

	/**
	 * In enhanced mode, we need to check whether the grouped results all have the
	 * same active highlights in order to see whether the "parent" of the group should
	 * be grey or highlighted normally.
	 *
	 * This is called every time highlights are applied.
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.updateEnhancedParentHighlight = function () {
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
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.inEnhancedMode = function () {
		var uri = new mw.Uri();
		return ( uri.query.enhanced !== undefined && Number( uri.query.enhanced ) ) ||
			( uri.query.enhanced === undefined && Number( mw.user.options.get( 'usenewrc' ) ) );
	};

	/**
	 * Apply color classes based on filters highlight configuration
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.applyHighlight = function () {
		if ( !this.filtersViewModel.isHighlightEnabled() ) {
			return;
		}

		this.filtersViewModel.getHighlightedItems().forEach( function ( filterItem ) {
			var $elements = this.$element.find( '.' + filterItem.getCssClass() );

			// Add highlight class to all highlighted list items
			$elements
				.addClass( 'mw-rcfilters-highlight-color-' + filterItem.getHighlightColor() );

			$elements.each( function () {
				var filterString = $( this ).attr( 'data-highlightedFilters' ) || '',
					filters = filterString ? filterString.split( '|' ) : [];

				if ( filters.indexOf( filterItem.getLabel() ) === -1 ) {
					filters.push( filterItem.getLabel() );
				}

				$( this )
					.attr( 'data-highlightedFilters', filters.join( '|' ) );
			} );
		}.bind( this ) );
		// Apply a title for relevant filters
		this.$element.find( '[data-highlightedFilters]' ).each( function () {
			var filterString = $( this ).attr( 'data-highlightedFilters' ) || '',
				filters = filterString ? filterString.split( '|' ) : [];

			if ( filterString ) {
				$( this ).attr( 'title', mw.msg( 'rcfilters-highlighted-filters-list', filters.join( ', ' ) ) );
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
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.clearHighlight = function () {
		// Remove highlight classes
		mw.rcfilters.HighlightColors.forEach( function ( color ) {
			this.$element.find( '.mw-rcfilters-highlight-color-' + color ).removeClass( 'mw-rcfilters-highlight-color-' + color );
		}.bind( this ) );

		this.$element.find( '[data-highlightedFilters]' )
			.removeAttr( 'title' )
			.removeAttr( 'data-highlightedFilters' );

		// Remove grey from enhanced rows
		this.$element.find( '.mw-rcfilters-ui-changesListWrapperWidget-enhanced-grey' )
			.removeClass( 'mw-rcfilters-ui-changesListWrapperWidget-enhanced-grey' );

		// Turn off highlights
		this.$element.removeClass( 'mw-rcfilters-ui-changesListWrapperWidget-highlighted' );
	};
}( mediaWiki ) );
