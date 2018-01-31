var oldTweenRun = jQuery.Tween.prototype.run;
var linearEasing = function( pct ) {
		return pct;
	};

jQuery.Tween.prototype.run = function( ) {
	if ( jQuery.easing[ this.easing ].length > 1 ) {
		migrateWarn(
			"'jQuery.easing." + this.easing.toString() + "' should use only one argument"
		);

		jQuery.easing[ this.easing ] = linearEasing;
	}

	oldTweenRun.apply( this, arguments );
};

jQuery.fx.interval = jQuery.fx.interval || 13;

// Support: IE9, Android <=4.4
// Avoid false positives on browsers that lack rAF
if ( window.requestAnimationFrame ) {
	migrateWarnProp( jQuery.fx, "interval", jQuery.fx.interval,
		"jQuery.fx.interval is deprecated" );
}
