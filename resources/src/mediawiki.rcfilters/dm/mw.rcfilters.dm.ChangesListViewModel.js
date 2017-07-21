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
		this.newChangesExist = false;
		this.nextFrom = null;
		this.liveUpdate = false;
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
	 * @param {jQuery|string} $changesListContent List of changes
	 * @param {jQuery} $fieldset Server-generated form
	 * @param {boolean} isInitialDOM Whether the previous dom variables are from the initial page load
	 * @param {boolean} These are new changes fetched via Live Update
	 *
	 * The list of changes has been updated
	 */

	/**
	 * @event newChangesExist
	 * @param {boolean} newChangesExist
	 *
	 * The existence of changes newer than those currently displayed has changed.
	 */

	/**
	 * @event liveUpdateChange
	 * @param {boolean} enable
	 *
	 * The state of the 'live update' feature has changed.
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
			this.setNewChangesExist( false );
			this.emit( 'invalidate' );
		}
	};

	/**
	 * Update the model with an updated list of changes
	 *
	 * @param {jQuery|string} changesListContent
	 * @param {jQuery} $fieldset
	 * @param {boolean} [isInitialDOM] Using the initial (already attached) DOM elements
	 * @param {boolean} [fromLiveUpdate] These are new changes fetched via Live Update
	 * @fires update
	 */
	mw.rcfilters.dm.ChangesListViewModel.prototype.update = function ( changesListContent, $fieldset, isInitialDOM, fromLiveUpdate ) {
		var from = this.nextFrom;
		this.valid = true;
		this.extractNextFrom( $fieldset );
		this.emit( 'update', changesListContent, $fieldset, isInitialDOM, fromLiveUpdate ? from : null );
	};

	/**
	 * Specify whether new changes exist
	 *
	 * @param {boolean} newChangesExist
	 * @fires newChangesExist
	 */
	mw.rcfilters.dm.ChangesListViewModel.prototype.setNewChangesExist = function ( newChangesExist ) {
		if ( newChangesExist !== this.newChangesExist ) {
			this.newChangesExist = newChangesExist;
			this.emit( 'newChangesExist', newChangesExist );
		}
	};

	/**
	 * @return {boolean} Whether new changes exist
	 */
	mw.rcfilters.dm.ChangesListViewModel.prototype.getNewChangesExist = function () {
		return this.newChangesExist;
	};

	/**
	 * Extract the value of the 'from' parameter from a link in the field set
	 *
	 * @param {jQuery} $fieldset
	 */
	mw.rcfilters.dm.ChangesListViewModel.prototype.extractNextFrom = function ( $fieldset ) {
		this.nextFrom = $fieldset.find( '.rclistfrom > a' ).data( 'params' ).from;
	};

	/**
	 * @return {string} The 'from' parameter that can be used to query new changes
	 */
	mw.rcfilters.dm.ChangesListViewModel.prototype.getNextFrom = function () {
		return this.nextFrom;
	};

	/**
	 * Toggle the 'live update' feature on/off
	 *
	 * @param {boolean} enable
	 */
	mw.rcfilters.dm.ChangesListViewModel.prototype.toggleLiveUpdate = function ( enable ) {
		enable = enable === undefined ? !this.liveUpdate : enable;
		if ( enable !== this.liveUpdate ) {
			this.liveUpdate = enable;
			this.emit( 'liveUpdateChange', this.liveUpdate );
		}
	};

	/**
	 * @return {boolean} The 'live update' feature is enabled
	 */
	mw.rcfilters.dm.ChangesListViewModel.prototype.getLiveUpdate = function () {
		return this.liveUpdate;
	};

}( mediaWiki ) );
