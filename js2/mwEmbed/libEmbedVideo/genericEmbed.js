/* the most simple implementation used for unknown application/ogg plugin */
var genericEmbed = {
	 supports: {
		'play_head':false,
		'pause':false,
		'stop':true,
		'fullscreen':false,
		'time_display':false,
		'volume_control':false
	},
	instanceOf:'genericEmbed',
	getEmbedHTML:function() {
		return '<object type="application/ogg" ' +
				  'width="' + this.width + '" height="' + this.height + '" ' +
				  'data="' + this.getURI( this.seek_time_sec ) + '"></object>';
	}
};