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
		config = config || {};

		// Parent
		mw.rcfilters.ui.ChangesListWrapperWidget.parent.call( this, $.extend( {}, config, {
			$element: $changesListRoot
		} ) );
		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, config );

		this.filtersViewModel = filtersViewModel;
		this.changesListViewModel = filtersViewModel;

		// Events
		this.filtersViewModel.connect( this, {
			itemUpdate: 'onItemUpdate',
			highlightChange: 'onHighlightChange'
		} );
		this.changesListViewModel.connect( this, {
			invalidate: 'onModelInvalidate',
			update: 'onModelUpdate'
		} );

		this.$element.addClass( 'mw-rcfilters-ui-ChangesListWrapperWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.ChangesListWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.ChangesListWrapperWidget, OO.ui.mixin.PendingElement );

	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onHighlightChange = function ( highlightEnabled ) {
		if ( highlightEnabled ) {
			this.applyHighlight();
		} else {
			this.clearHighlight();
		}
	};

	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onItemUpdate = function () {
		if ( this.filtersViewModel.isHighlightEnabled() ) {
			this.applyHighlight();
		}
	};

	/**
	 * Respond to model invalidate
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.onModelInvalidate = function () {
		this.pushPending();
	};

	/**
	 * Respond to model update
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
	 * FIXME: this is a naive implementation, it is potentially very slow
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.applyHighlight = function () {
		this.clearHighlight();

		this.filtersViewModel.getItems().forEach( function ( filterItem ) {
			var color = filterItem.getColor(),
				className = filterItem.getClass();
			if ( className && color ) {
				this.$element.find( '.' + className ).addClass( color );
			}
		}.bind( this ) );
	};

	mw.rcfilters.ui.ChangesListWrapperWidget.prototype.clearHighlight = function () {
		[ 'blue', 'green', 'yellow', 'orange', 'red' ].forEach( function ( className ) {
			this.$element.find( '.' + className ).removeClass( className );
		}.bind( this ) );
	};
}( mediaWiki ) );
