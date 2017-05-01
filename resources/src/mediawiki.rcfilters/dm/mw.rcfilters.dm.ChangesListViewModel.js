( function ( mw ) {
	/**
	 * View model for the changes list
	 *
	 * @mixins OO.EventEmitter
	 *
	 * @constructor
	 */
	mw.rcfilters.dm.ChangesListViewModel = function MwRcfiltersDmChangesListViewModel() {
		// Mixin constructor
		OO.EventEmitter.call( this );

		this.valid = true;
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.dm.ChangesListViewModel );
	OO.mixinClass( mw.rcfilters.dm.ChangesListViewModel, OO.EventEmitter );

	/* Events */

	/**
	 * @event invalidate
	 *
	 * The list of changes is now invalid (out of date)
	 */

	/**
	 * @event update
	 * @param {jQuery|string} changesListContent
	 *
	 * The list of change is now up to date
	 */

	/* Methods */

	/**
	 * Invalidate the list of changes
	 *
	 * @fires invalidate
	 */
	mw.rcfilters.dm.ChangesListViewModel.prototype.invalidate = function () {
		if ( this.valid ) {
			this.valid = false;
			this.emit( 'invalidate' );
		}
	};

	/**
	 * Update the model with an updated list of changes
	 *
	 * @param {jQuery|string} changesListContent
	 * @param {jQuery} $fieldset
	 */
	mw.rcfilters.dm.ChangesListViewModel.prototype.update = function ( changesListContent, $fieldset ) {
		this.valid = true;
		this.emit( 'update', changesListContent, $fieldset );
	};

}( mediaWiki ) );
