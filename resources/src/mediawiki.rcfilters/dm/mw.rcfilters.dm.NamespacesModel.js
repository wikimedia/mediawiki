( function ( mw ) {
	/**
	 * Model holding namespace items
	 *
	 * @mixins OO.EventEmitter
	 * @mixins OO.EmitterList
	 *
	 * @constructor
	 */
	mw.rcfilters.dm.NamespacesModel = function MwRcfiltersDmNamespacesModel() {
		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		// Events
		this.aggregate( { update: 'namespaceItemUpdate' } );
		this.connect( this, { namespaceItemUpdate: [ 'emit', 'update' ] } );
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.dm.NamespacesModel );
	OO.mixinClass( mw.rcfilters.dm.NamespacesModel, OO.EventEmitter );
	OO.mixinClass( mw.rcfilters.dm.NamespacesModel, OO.EmitterList );

	mw.rcfilters.dm.NamespacesModel.prototype.getSeparator = function () {
		return '|';
	};
	mw.rcfilters.dm.NamespacesModel.prototype.getItemFromData = function ( data ) {
		return this.getItems().filter( function ( item ) {
			return item.getName() === data;
		} )[ 0 ];
	};
	mw.rcfilters.dm.NamespacesModel.prototype.getByID = function ( id ) {
		return this.getItems().filter( function ( item ) {
			return item.getNamespaceID() === id;
		} )[ 0 ];
	};
}( mediaWiki ) );
