( function ( mw ) {
	/**
	 * View model for the namespace selection and display
	 *
	 * @mixins OO.EventEmitter
	 * @mixins OO.EmitterList
	 *
	 * @constructor
	 * @param {Object} [config] Configuration object
	 * @cfg {boolean} [inverted] The selection is inverted
	 */
	mw.rcfilters.dm.NamespacesViewModel = function MwRcfiltersDmNamespacesViewModel( config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		this.inverted = !!config.inverted;
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.dm.NamespacesViewModel );
	OO.mixinClass( mw.rcfilters.dm.NamespacesViewModel, OO.EventEmitter );
	OO.mixinClass( mw.rcfilters.dm.NamespacesViewModel, OO.EmitterList );

	/* Events */

	/**
	 * @event initialize
	 *
	 * The model has been initialized
	 */

	/**
	 * @event update
	 *
	 * The model has been updated
	 */

	/* Methods */

	/**
	 * Initialize the model
	 *
	 * @param {[type]} namespaceStructure Namespace structure, coming from
	 * wgFormattedNamespaces
	 * @fires initialize
	 */
	mw.rcfilters.dm.NamespacesViewModel.prototype.initialize = function ( namespaceStructure ) {
		var items = [];

		this.clearItems();
		$.each( namespaceStructure, function ( namespaceID, label ) {
			if ( namespaceID === 0 ) {
				// Main namespace, we use our own label
				label = mw.msg( 'blanknamespace' );
			}

			items.push( new mw.rcfilters.dm.NamespaceItem(
				namespaceID,
				label
			) );
		} );

		this.addItems( items );

		this.emit( 'initialize' );
	};
	/**
	 * Check whether the selection is inverted
	 *
	 * @return {boolean} Selection is inverted
	 */
	mw.rcfilters.dm.NamespacesViewModel.prototype.isInverted = function () {
		return this.inverted;
	};

	/**
	 * Toggle the inverted state of the model
	 *
	 * @param {boolean} isInverted Selection is inverted
	 * @fires update
	 */
	mw.rcfilters.dm.NamespacesViewModel.prototype.toggleInverted = function ( isInverted ) {
		isInverted = isInverted === undefined ? !this.inverted : !!isInverted;

		if ( this.inverted !== isInverted ) {
			this.inverted = isInverted;

			this.getItems().forEach( function ( item ) {
				item.toggleInverted( isInverted );
			} );

			this.emit( 'update' );
		}
	};

	mw.rcfilters.dm.NamespacesViewModel.prototype.findMatches = function () {};
}( mediaWiki ) );
