mediaWiki.template.registerCompiler( 'regexp', {
	compile: function ( src ) {
		return {
			render: function () {
				return new RegExp(
					src
						// Remove whitespace
						.replace( /\s+/g, '' )
						// Remove named capturing groups
						.replace( /\?<\w+?>/g, '' )
				);
			}
		};
	}
} );
