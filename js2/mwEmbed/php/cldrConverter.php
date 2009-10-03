<?php
/*
 * cldr format tries to build a structured representation of
 * http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html
 *
 * it has structure similar to
 * http://www.unicode.org/repos/cldr/tags/release-1-7-1/common/supplemental/plurals.xml
 *
 * It works by mapping array indexes to forms passed in via the PLURAL call
 * the plural types are linear mapped to PLURAL argument index.
 *
 * Rules should come in the following order:
 *  zero, one, two, few, many, other (other is a special default case that applies to all)
 *
 * cldr also allows export of rule sets in json to associated javascript
 */

class cldrConverter {
	static $masterCLDR = array(
		// if locale is known to have no plurals, there are no rules
		array(
			'locales'=> array('az','fa','hu','ja','ko','my to','tr','vi','yo','zh',
								'bo','dz','id','jv ka','km','kn','ms','th')
		),
		array(
			'locales'=> array('ar'),
			'rules' => array(
				'zero' 	=> 0,
				'one'	=> 1,
				'two'	=> 2,
				//n mod 100 in 3..10
				'few'	=> array( 'mod' => 100, 'is'=>'3-10' ),
				//n mod 100 in 11..99
				'many'	=> array( 'mod' => 100, 'is'=>'11-99')
			)
		),
		array( 'locales' => array( 	'da','de','el','en','eo','es','et','fi','fo','gl',
									'he','iw','it','nb','nl','nn','no','pt_PT','sv',
									'af','bg','bn','ca','eu','fur','fy','gu','ha',
									'is','ku','lb','ml','mr','nah','ne','om','or',
									'pa','pap','ps','so','sq','sw','ta','te','tk',
									'ur','zu','mn','gsw'),
				'rules'	=> array(
					'one' => 1
				)
		),
		array( 	'locales' => array('pt','am','bh','fil','tl','guw','hi','ln','mg','nso','ti','wa'),
				'rules'=> array(
					'one'=> '0-1'
				)
		),
		array( 	'locales' => array('fr'),
				'rules'=>array(
					//n within 0..2 and n is not 2
					'one' => array( 'is'=>'0-2', 'not' => 2)
				)
		),
		array( 	'locales' => array('lv'),
				'rules' => array(
					'zero' => 0,
					//n mod 10 is 1 and n mod 100 is not 11
					'one'=>array(
							array( 'mod' => 10, 'is' => 1 ),
							//AND
							array( 'mod' => 100, 'not' => 11)
						)
				)
		),
		array(	'locales' => array('ga','se','sma','smi','smj','smn','sms'),
				'rules' => array(
					'one' => 1,
					'two' => 2
				)
		),
		array(	'locales' => array('ro','mo'),
				'rules' => array(
					'one' => 1,
					//n is 0 OR n is not 1 AND n mod 100 in 1..19
					'few' => array(
								array( 'is' => 0),
								'or',
								array(
									array( 'not' => 1),
									array( 'mod' => 100, 'is'=>'1-19')
								)
							)
				)
		),
		array(	'locales' => array( 'lt' ),
				'rules'	=> array(
					//n mod 10 is 1 and n mod 100 not in 11..19
					'one' => array(
								array( 'mod'=>10, 'is'=> 1 ),
								array( 'mod'=> 100, 'not'=> '11-19')
							),
					//n mod 10 in 2..9 and n mod 100 not in 11..19
					'few' => array(
								array( 'mod'=> 10, 'is'=> '2-9' ),
								array( 'mod'=> 100, 'not' => '11-19')
							),
				)
		),
		array( 	'locales' => array( 'hr','ru','sr','uk','be','bs','sh' ),
				'rules' => array(
					//n mod 10 is 1 and n mod 100 is not 11
					'one' => array(
						array( 'mod' => 10, 'is' => 1),
						array( 'mod' => 100, 'not' => 11)
					),
					//n mod 10 in 2..4 and n mod 100 not in 12..14
					'few' => array(
						array( 'mod' => 10, 'is' => '2-4'),
						array( 'mod' => 100, 'not' => '12-14')
					),
					//n mod 10 is 0 or n mod 10 in 5..9 or n mod 100 in 11..14
					'many' => array(
						array( 'mod'=> 10, 'is' => 0),
						'or',
						array( 'mod'=> 10, 'is' => '5-9'),
						'or',
						array( 'mod'=> 100, 'is' => '11-14')
					),
				)
		),
		array( 	'locales' => array('cs','sk'),
				'rules' => array(
					'one' => 1,
					'few'=> '2-4'
				)
		),
		array( 	'locales' => array('pl'),
				'rules '=> array(
					'one' => 1,
					'few' => array(
						//n mod 10 in 2..4
						array( 'mod' => 10, 'is' => '2-4'),
						//and n mod 100 not in 12..14
						array( 'mod' => 100, 'not'=> '12-14'),
						//and n mod 100 not in 22..24
						array( 'mod' => 100, 'in' => '22-24')
					)
				)
		),
		array(	'locales' => array('sl'),
				'rules' => array(
					'one' => array( 'mod'=>100, 'is' => 1 ),
					'two' => array( 'mod'=>100, 'is' => 2 ),
					'few' => array( 'mod'=>100, 'is' => '3-4')
				)
		),
		array( 	'locales' => array('mt'),
				'rules' => array(
					'one' => 1,
					//n is 0 or n mod 100 in 2..10
					'few' => array(
						array( 'is' => 0 ),
						'or',
						array( 'mod' => 100, 'is' => '2-10')
					),
					//n mod 100 in 11..19
					'many' => array( 'mod'=>100, 'is' => '11-19')
				)
		),
		array(	'locales' => array( 'mk' ),
				'rules' => array(
					'one' => array('mod' => 10, 'is' => '1')
				)
		),
		array(	'locales' => array( 'cy' ),
				'rules'	=> array(
					'one' => 1,
					'two' => 2,
					//n is 8 or n is 11
					'many' => array(
						array( 'is' => 8 ),
						array( 'is' => 11 )
					)
				)
		)
	);
	//takes the cldr representation and returns the proper form
	function cldrConvertPlural($count, $forms){
		if ( !count($forms) ) { return ''; }
		//get the rule set
		$ruleSet = $this->getCldrRuleSet();
		//get the number of forms (ruleSet Count + 1 for 'other' )
		$fomsCount = count( $ruleSet ) + 1;

		//if count is 1 no plurals for this language:
		if( count( $forms ) == 1)
			return $forms[0];

		$forms = $this->preConvertPlural( $forms, $fomsCount );

	}

	function getCldrRuleSet(){
		$code = $this->getCode();
		foreach($this->masterCLDR as $ruleSet){
			if( in_array($code, $ruleSet['locales']) ){
				return $ruleSet['rules'];
			}
		}
		//could not find the language code
		return false;
	}
}