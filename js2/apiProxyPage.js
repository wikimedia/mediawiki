/*
* mwProxy js2 page system.
*
* Invokes the apiProxy system 
*/

/*
 * Since this is proxy server set a pre-append debug flag to know which debug msgs are coming from where
 */
 
$mw.conf['debug_pre'] = 'Proxy';
 
if( !mwApiProxyConfig )
	var mwApiProxyConfig = {};

// The default mwApiProxyConfig config 
// (presently hard coded but should read from user and site config)  
var mwApiProxyDefaultConfig = {
		'master_whitelist' 	: [  'en.wikipedia.org', 'localhost', '127.1.1.100' ],
		'master_blacklist'	: []					
};

// User white_list should also be checked and configured at runtime.
js2AddOnloadHook( function() {				
	//build our configuration from the default and mwApiProxyConfig vars
	mwApiProxyConfig = $j.extend(true, mwApiProxyDefaultConfig,  mwApiProxyConfig);	
	$j.apiProxy( 'server', mwApiProxyConfig );	
});
