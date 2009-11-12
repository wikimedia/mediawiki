/* a simple language tester replacements
 */

loadGM({	
	"undelete_short"  : "Undelete {{PLURAL:$1|one edit|$1 edits}}",
	"category-subcat-count" : "{{PLURAL:$2|This category has only the following subcategory.|This category has the following {{PLURAL:$1|subcategory|$1 subcategories}}, out of $2 total.}}",
	"mwe-upload-multi" : "Upload {{PLURAL:$1|file|files}}"	
});

mw.lang.loadRS({
	'PLURAL' : { "one" : 1 }	
});

//define a class by the name of this file:  
mw.testLang = {};