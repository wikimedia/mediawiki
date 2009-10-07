/* a simple language tester replacements

 */

loadGM({
	//test msg with english words to see whats going on
	'test_plural_msg' : '{{PLURAL:$1|one|few|many}}',
	//sample real world msgs: 
	'undelete_short' : 'Восстановить $1 {{PLURAL:$1|правку|правки|правок}}'
});
$mw.lang.loadRS({
	'PLURAL' :
		{
		"one":[{"mod":10,"is":1},{"mod":100,"not":11}],
		"few":[{"mod":10,"is":"2-4"},{"mod":100,"not":"12-14"}],
		"many":[{"mod":10,"is":0},
				"or",
				{"mod":10,"is":"5-9"},
				"or",
				{"mod":100,"is":"11-14"}
				]
		}
});
/*
one	1, 21, 31, 41, 51, 61...	
few	2-4, 22-24, 32-34...
many	0, 5-20, 25-30, 35-40...
other	1.31, 2.31, 5.31...
*/
