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
	 * @cfg {string} [namePrefix='namespace_'] Prefix for all item names
	 */
	mw.rcfilters.dm.NamespacesViewModel = function MwRcfiltersDmNamespacesViewModel( config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		this.inverted = !!config.inverted;
		this.namePrefix = config.namePrefix || '';
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
		var items = [],
			model = this;

		this.clearItems();
		$.each( namespaceStructure, function ( namespaceID, label ) {
			if ( namespaceID === '0' ) {
				// Main namespace, we use our own label
				label = mw.msg( 'blanknamespace' );
			}

			items.push( new mw.rcfilters.dm.NamespaceItem(
				namespaceID,
				label,
				{ namePrefix: model.namePrefix }
			) );
		} );

		this.addItems( items );

		this.emit( 'initialize' );
	};

	/**
	 * Get item by its name
	 *
	 * @param {string} name Item name
	 * @return {mw.rcfiltrs.dm.NamespaceItem} Item
	 */
	mw.rcfilters.dm.NamespacesViewModel.prototype.getItemByName = function ( name ) {
		return this.getItems().filter( function ( item ) {
			return item.getName() === name;
		} )[ 0 ];
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
