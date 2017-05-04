( function ( mw ) {
	/**
	 * Filter item model
	 *
	 * @extends mw.rcfilters.dm.ItemModel
	 *
	 * @constructor
	 * @param {number} param Namespace ID
	 * @param {string} label Namespace label
	 * @param {Object} config Configuration object
	 * @cfg {boolean} [inverted] The selection is inverted
	 */
	mw.rcfilters.dm.NamespaceItem = function MwRcfiltersDmNamespaceItem( param, label, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.dm.NamespaceItem.parent.call( this, param, $.extend( {
			namePrefix: 'namespace_',
			label: label
		}, config ) );
		// Mixin constructor
		OO.EventEmitter.call( this );

		this.inverted = !!config.inverted;
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.dm.NamespaceItem, mw.rcfilters.dm.ItemModel );

	/* Events */

	/**
	 * @event update
	 *
	 * The model has been updated
	 */

	/* Methods */

	/**
	 * Get namespace ID
	 *
	 * @return {number} Namespace ID
	 */
	mw.rcfilters.dm.NamespaceItem.prototype.getNamespaceID = function () {
		return +this.getParamName();
	}

	/**
	 * Toggle the inverted state of the model
	 *
	 * @param {boolean} isInverted Selection is inverted
	 * @fires update
	 */
	mw.rcfilters.dm.NamespaceItem.prototype.toggleInverted = function ( isInverted ) {
		isInverted = isInverted === undefined ? !this.inverted : !!isInverted;

		if ( this.inverted !== isInverted ) {
			this.inverted = isInverted;

			this.emit( 'update' );
		}
	};
}( mediaWiki ) );
