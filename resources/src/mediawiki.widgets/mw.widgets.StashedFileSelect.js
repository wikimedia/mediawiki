/*!
 * MediaWiki Widgets - StashedFileSelect class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw, OO ) {

	/**
	 * @class mw.widgets.StashedFileSelectWidget
	 * @extends OO.ui.Widget
	 * Accepts a stashed file and displays the information.
	 * @constructor
	 * @param {Object} config Configuration options
	 * @cfg {string} filekey The filekey of the stashed file.
	 * @cfg {Object} apiconfig API configuration to use for thumbnails.
	 * @cfg {string} [placeholder] Placeholder text for empty field.
	 * @cfg {number} [thumbnailSizeLimit=20] How many MB a file should be before we don't fetch a thumbnail.
	 */
	mw.widgets.StashedFileSelectWidget = function MWWStashedFileSelectWidget( config ) {
		config = $.extend( {
			apiconfig: {},
			placeholder: mw.message( 'mediawiki-widgets-stashedFileSelectWidget-placeholder' ),
			thumbnailSizeLimit: 20
		}, config );

		// Parent constructor
		mw.widgets.StashedFileSelect.parent.call( this, config );

		// Mixin constructors
		OO.ui.mixin.IconElement.call( this, config );
		OO.ui.mixin.LabelElement.call( this, config );

		// Properties
		this.$info = $( '<span>' );
		this.filekey = config.filekey;
		this.thumbnailSizeLimit = config.thumbnailSizeLimit;
		this.placeholder = config.placeholder;
		this.$label.addClass( 'mediawiki-widgets-stashedFileSelectWidget-label' );
		this.$info
			.addClass( 'mediawiki-widgets-stashedFileSelectWidget-info' )
			.append( this.$icon, this.$label );

		this.$thumbnail = $( '<div>' ).addClass( 'mediawiki-widgets-stashedFileSelectWidget-thumbnail' );

		this.$element
			.addClass( 'mediawiki-widgets-stashedFileSelectWidget' )
			.append( this.$info );

		this.updateUI();
	};

	OO.inheritClass( mw.widgets.StashedFileSelectWidget, OO.ui.Widget );
	OO.mixinClass( mw.widgets.StashedFileSelectWidget, OO.ui.mixin.IconElement );
	OO.mixinClass( mw.widgets.StashedFileSelectWidget, OO.ui.mixin.LabelElement );

	/**
	 * Get the current filekey.
	 * @return {string|null}
	 */
	mw.widgets.StashedFileSelectWidget.prototype.getValue = function () {
		return this.filekey;
	};

	/**
	 * Set the filekey.
	 * @param {string|null} filekey
	 */
	mw.widgets.StashedFileSelectWidget.prototype.setValue = function ( filekey ) {
		if ( filekey !== this.filekey ) {
			this.filekey = filekey;
			this.updateUI();
			this.emit( 'change', this.filekey );
		}
	};

	mw.widgets.StashedFileSelectWidget.prototype.updateUI = function () {
		if ( this.filekey ) {
			this.$element.removeClass( 'mediawiki-widgets-stashedFileSelectWidget-empty' );
			$label = $( [] );
			$label = $label.add(
				$( '<span>' )
					.addClass( 'mediawiki-widgets-stashedFileSelectWidget-filekey' )
					.text( this.filekey )
			);

			this.setLabel( $label );

			this.pushPending();
			this.loadAndGetImageUrl().done( function ( url ) {
				this.$thumbnail.css( 'background-image', 'url( ' + url + ' )' );
			}.bind( this ) ).fail( function () {
				this.$thumbnail.append(
					new OO.ui.IconWidget( {
						icon: 'attachment',
						classes: [ 'mediawiki-widgets-stashedFileSelectWidget-noThumbnail-icon' ]
					} ).$element
				);
			}.bind( this ) ).always( function () {
				this.popPending();
			}.bind( this ) );
		} else {
			this.$element.addClass( 'mediawiki-widgets-stashedFileSelectWidget-empty' );
			this.setLabel( this.placeholder );
		}
	};

	mw.widgets.StashedFileSelectWidget.prototype.loadAndGetImageUrl = function () {
		var filekey = this.filekey,
			maxsize = this.thumbnailSizeLimit * 1024 * 1024,
			api = new mw.Api( apiconfig );

		if ( filekey ) {
			return api.get( {
				action: 'query',
				prop: 'stashimageinfo',
				siifilekey: filekey,
				siiprop: [ 'size', 'url' ],
				siiurlwidth: 220
			} ).then( function ( data ) {
				var sii = data.query.stashimageinfo[0];

				if ( sii.size > maxsize ) {
					return $.Deferred().reject( 'File too large, not fetching thumbnail' );
				}

				return sii.thumburl;
			} );
		}
	};
	mw.widgets.StashedFileSelectWidget.prototype.
}( jQuery, mediaWiki, OO );
