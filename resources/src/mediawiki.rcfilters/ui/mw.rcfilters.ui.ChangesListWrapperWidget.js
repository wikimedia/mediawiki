( function ( mw ) {
	/**
	 * List of changes
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.ChangesListViewModel} model View model
	 * @param {Object} config Configuration object
	 * @cfg {Object} $element Root element of the changes list to attach to
	 */
	mw.rcfilters.ui.ChangesListWrapperWidget = function MwRcfiltersUiChangesListWrapperWidget( model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.ChangesListWrapperWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, config );

		this.model = model;

		// Events
		this.model.connect( this, {
			invalidate: 'onModelInvalidate',
			update: 'onModelUpdate'
		} );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.ChangesListWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.ChangesListWrapperWidget, OO.ui.mixin.PendingElement );

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
		this.$element.empty().append(
			isEmpty ?
			document.createTextNode( mw.message( 'recentchanges-noresult' ).text() ) :
			changesListContent
		);
		this.popPending();
	};
}( mediaWiki ) );
