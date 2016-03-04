/*!
 * MediaWiki Widgets - StashedFileWidget class.
 *
 * @copyright 2011-2016 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw, OO ) {

	/**
	 * Accepts a stashed file and displays the information for purposes of
	 * publishing the file at the behest of the user.
	 *
	 * Example use:
	 *     var widget = new mw.widgets.StashedFile( {
	 *       filekey: '12r9e4rugeec.ddtmmp.1.jpg',
	 *     } );
	 *
	 *     widget.getValue(); // '12r9e4rugeec.ddtmmp.1.jpg'
	 *     widget.setValue( '12r9epfbnskk.knfiy7.1.jpg' );
	 *     widget.getValue(); // '12r9epfbnskk.knfiy7.1.jpg'
	 *
	 * Note that this widget will not finish an upload for you. Use mw.Upload
	 * and mw.Upload#setFilekey, then mw.Upload#finishStashUpload to accomplish
	 * that.
	 *
	 * @class mw.widgets.StashedFileWidget
	 * @extends OO.ui.Widget
	 */

	/**
	 * @constructor
	 * @param {Object} config Configuration options
	 * @cfg {string} filekey The filekey of the stashed file.
	 * @cfg {Object} apiconfig API configuration to use for thumbnails.
	 */
	mw.widgets.StashedFileWidget = function MWWStashedFileWidget( config ) {
		config = $.extend( {
			apiconfig: {},
			placeholder: mw.message( 'mediawiki-widgets-stashedFileWidget-placeholder' )
		}, config );

		// Parent constructor
		mw.widgets.StashedFileWidget.parent.call( this, config );

		// Mixin constructors
		OO.ui.mixin.IconElement.call( this, config );
		OO.ui.mixin.LabelElement.call( this, config );
		OO.ui.mixin.PendingElement.call( this, config );

		// Properties
		this.$info = $( '<span>' );
		this.setValue( config.filekey );
		this.apiconfig = config.apiconfig;
		this.placeholder = config.placeholder;
		this.$label.addClass( 'mediawiki-widgets-stashedFileWidget-label' );
		this.$info
			.addClass( 'mediawiki-widgets-stashedFileWidget-info' )
			.append( this.$icon, this.$label );

		this.$thumbnail = $( '<div>' ).addClass( 'mediawiki-widgets-stashedFileWidget-thumbnail' );
		this.setPendingElement( this.$thumbnail );

		this.$thumbContain = $( '<div>' )
			.addClass( 'mediawiki-widgets-stashedFileWidget-thumbnail-container' )
			.append( this.$thumbnail, this.$info );

		this.$element
			.addClass( 'mediawiki-widgets-stashedFileWidget' )
			.append( this.$thumbContain );

		this.updateUI();
	};

	OO.inheritClass( mw.widgets.StashedFileWidget, OO.ui.Widget );
	OO.mixinClass( mw.widgets.StashedFileWidget, OO.ui.mixin.IconElement );
	OO.mixinClass( mw.widgets.StashedFileWidget, OO.ui.mixin.LabelElement );
	OO.mixinClass( mw.widgets.StashedFileWidget, OO.ui.mixin.PendingElement );

	/**
	 * Get the current filekey.
	 *
	 * @return {string|null}
	 */
	mw.widgets.StashedFileWidget.prototype.getValue = function () {
		return this.filekey;
	};

	/**
	 * Set the filekey.
	 *
	 * @param {string|null} filekey
	 */
	mw.widgets.StashedFileWidget.prototype.setValue = function ( filekey ) {
		if ( filekey !== this.filekey ) {
			this.filekey = filekey;
			this.updateUI();
			this.emit( 'change', this.filekey );
		}
	};

	mw.widgets.StashedFileWidget.prototype.updateUI = function () {
		var $label, $filetype;

		if ( this.filekey ) {
			this.$element.removeClass( 'mediawiki-widgets-stashedFileWidget-empty' );
			$label = $( [] );
			$filetype = $( '<span>' )
				.addClass( 'mediawiki-widgets-stashedFileWidget-fileType' );

			$label = $label.add(
				$( '<span>' )
					.addClass( 'mediawiki-widgets-stashedFileWidget-filekey' )
					.text( this.filekey )
			).add( $filetype );

			this.setLabel( $label );

			this.pushPending();
			this.loadAndGetImageUrl().done( function ( url, mime ) {
				this.$thumbnail.css( 'background-image', 'url( ' + url + ' )' );
				if ( mime ) {
					$filetype.text( mime );
					this.setLabel( $label );
				}
			}.bind( this ) ).fail( function () {
				this.$thumbnail.append(
					new OO.ui.IconWidget( {
						icon: 'attachment',
						classes: [ 'mediawiki-widgets-stashedFileWidget-noThumbnail-icon' ]
					} ).$element
				);
			}.bind( this ) ).always( function () {
				this.popPending();
			}.bind( this ) );
		} else {
			this.$element.addClass( 'mediawiki-widgets-stashedFileWidget-empty' );
			this.setLabel( this.placeholder );
		}
	};

	mw.widgets.StashedFileWidget.prototype.loadAndGetImageUrl = function () {
		var filekey = this.filekey,
			api = new mw.Api( this.apiconfig );

		if ( filekey ) {
			return api.get( {
				action: 'query',
				prop: 'stashimageinfo',
				siifilekey: filekey,
				siiprop: [ 'size', 'url', 'mime' ],
				siiurlwidth: 220
			} ).then( function ( data ) {
				var sii = data.query.stashimageinfo[ 0 ];

				return $.Deferred().resolve( sii.thumburl, sii.mime );
			} );
		}

		return $.Deferred().reject( 'No filekey' );
	};
}( jQuery, mediaWiki, OO ) );
