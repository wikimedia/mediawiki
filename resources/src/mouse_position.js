
(function (mw, $){
		$( 'wpCreateaccount').click(function(event) {
			var x = event.clientX;
			var y = event.clientY;
			mw.track('mouse_postion.js', Coordinates);
		});
}(mediawiki, jQuery));

