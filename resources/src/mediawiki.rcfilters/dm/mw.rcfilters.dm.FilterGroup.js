( function ( mw ) {
	/**
	 * View model for a filter group
	 *
	 * @mixins OO.EventEmitter
	 * @mixins OO.EmitterList
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {string} [type='send_unselected_if_any'] Group type
	 * @cfg {string} [title] Group title
	 * @cfg {string} [separator='|'] Value separator for 'string_options' groups
	 * @cfg {string} [exclusionType='default'] Group exclusion type
	 * @cfg {boolean} [active] Group is active
	 */
	mw.rcfilters.dm.FilterGroup = function MwRcfiltersDmFilterGroup( config ) {
		config = config || {};

		// Mixin constructor
		OO.EventEmitter.call( this );
		OO.EmitterList.call( this );

		this.type = config.type || 'send_unselected_if_any';
		this.title = config.title;
		this.separator = config.separator || '|';
		this.exclusionType = config.exclusionType || 'default';
		this.active = !!config.active;
	};

	/* Initialization */
	OO.initClass( mw.rcfilters.dm.FilterGroup );
	OO.mixinClass( mw.rcfilters.dm.FilterGroup, OO.EventEmitter );
	OO.mixinClass( mw.rcfilters.dm.FilterGroup, OO.EmitterList );

	/* Events */

	/**
	 * @event update
	 *
	 * Group state has been updated
	 */

	/* Methods */

	/**
	 * Check the active status of the group and set it accordingly.
	 *
	 * @fires update
	 */
	mw.rcfilters.dm.FilterGroup.prototype.checkActive = function () {
		var active,
			count = 0;

		// Recheck group activity
		this.getItems().forEach( function ( filterItem ) {
			count += Number( filterItem.isSelected() );
		} );

		active = (
			count > 0 &&
			count < this.getItemCount()
		);

		if ( this.active !== active ) {
			this.active = active;
			this.emit( 'update' );
		}
	};

	/**
	 * Get group active state
	 *
	 * @return {boolean} Active state
	 */
	mw.rcfilters.dm.FilterGroup.prototype.isActive = function () {
		return this.active;
	};

	/**
	 * Get group type
	 *
	 * @return {string} Group type
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getType = function () {
		return this.type;
	};

	/**
	 * Get group's title
	 *
	 * @return {string} Title
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getTitle = function () {
		return this.title;
	};

	/**
	 * Get group's values separator
	 *
	 * @return {string} Values separator
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getSeparator = function () {
		return this.separator;
	};

	/**
	 * Get group exclusion type
	 *
	 * @return {string} Exclusion type
	 */
	mw.rcfilters.dm.FilterGroup.prototype.getExclusionType = function () {
		return this.exclusionType;
	};
}( mediaWiki ) );
