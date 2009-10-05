/* a simple language tester replacements

 */

loadGM({
	//test msg with english words to see whats going on
	'test_plural_msg' : '{{PLURAL:$1|one|other}}',
	//sample real world msgs: 
	'undelete_short'  : 'Undelete {{PLURAL:$1|one edit|$1 edits}}',
	'category-subcat-count' : "{{PLURAL:$2|This category has only the following subcategory.|This category has the following {{PLURAL:$1|subcategory|$1 subcategories}}, out of $2 total.}}"
});

$mw.lang.loadRS({
	'PLURAL' : { "one":1 }	
});