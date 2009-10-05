/* a simple language tester replacements

 */

loadGM({
	//test msg with english words to see whats going on
	'test_plural_msg' : '{{PLURAL:$1|one|other}}',
	//sample real world msgs: 
	'undelete_short'  : 'Undelete {{PLURAL:$1|one edit|$1 edits}}'
});

$mw.lang.loadRS({
	'PLURAL' : { "one":1 }	
});