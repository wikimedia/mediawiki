<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

/*
	There are two levels of conversion for Serbian: the script level
	(Cyrillics <-> Latin), and the variant level (ekavian
	<->iyekavian). The two are orthogonal. So we really only need two
	dictionaries: one for Cyrillics and Latin, and one for ekavian and
	iyekavian.
*/
require_once( "LanguageConverter.php" );
require_once( "LanguageSr_ec.php" );
require_once( "LanguageSr_el.php" );
require_once( "LanguageSr_jc.php" );
require_once( "LanguageSr_jl.php" );

class SrConverter extends LanguageConverter {
	var $mToLatin = array(
		'а' => 'a', 'б' => 'b',  'в' => 'v', 'г' => 'g',  'д' => 'd',
		'ђ' => 'đ', 'е' => 'e',  'ж' => 'ž', 'з' => 'z',  'и' => 'i',
		'ј' => 'j', 'к' => 'k',  'л' => 'l', 'љ' => 'lj', 'м' => 'm',
		'н' => 'n', 'њ' => 'nj', 'о' => 'o', 'п' => 'p',  'р' => 'r',
		'с' => 's', 'т' => 't',  'ћ' => 'ć', 'у' => 'u',  'ф' => 'f',
		'х' => 'h', 'ц' => 'c',  'ч' => 'č', 'џ' => 'dž', 'ш' => 'š',

		'А' => 'A', 'Б' => 'B',  'В' => 'V', 'Г' => 'G',  'Д' => 'D',
		'Ђ' => 'Đ', 'Е' => 'E',  'Ж' => 'Ž', 'З' => 'Z',  'И' => 'I',
		'Ј' => 'J', 'К' => 'K',  'Л' => 'L', 'Љ' => 'Lj', 'М' => 'M',
		'Н' => 'N', 'Њ' => 'Nj', 'О' => 'O', 'П' => 'P',  'Р' => 'R',
		'С' => 'S', 'Т' => 'T',  'Ћ' => 'Ć', 'У' => 'U',  'Ф' => 'F',
		'Х' => 'H', 'Ц' => 'C',  'Ч' => 'Č', 'Џ' => 'Dž', 'Ш' => 'Š',
	);

	var $mToCyrillics = array(
		'a' => 'а', 'b'  => 'б', 'c' => 'ц', 'č' => 'ч', 'ć'  => 'ћ',
		'd' => 'д', 'dž' => 'џ', 'đ' => 'ђ', 'e' => 'е', 'f'  => 'ф',
		'g' => 'г', 'h'  => 'х', 'i' => 'и', 'j' => 'ј', 'k'  => 'к',
		'l' => 'л', 'lj' => 'љ', 'm' => 'м', 'n' => 'н', 'nj' => 'њ',
		'o' => 'о', 'p'  => 'п', 'r' => 'р', 's' => 'с', 'š'  => 'ш',
		't' => 'т', 'u'  => 'у', 'v' => 'в', 'z' => 'з', 'ž'  => 'ж',

		'A' => 'А', 'B'  => 'Б', 'C' => 'Ц', 'Č' => 'Ч', 'Ć'  => 'Ћ',
		'D' => 'Д', 'Dž' => 'Џ', 'Đ' => 'Ђ', 'E' => 'Е', 'F'  => 'Ф',
		'G' => 'Г', 'H'  => 'Х', 'I' => 'И', 'J' => 'Ј', 'K'  => 'К',
		'L' => 'Л', 'LJ' => 'Љ', 'M' => 'М', 'N' => 'Н', 'NJ' => 'Њ',
		'O' => 'О', 'P'  => 'П', 'R' => 'Р', 'S' => 'С', 'Š'  => 'Ш',
		'T' => 'Т', 'U'  => 'У', 'V' => 'В', 'Z' => 'З', 'Ž'  => 'Ж',

		'DŽ' => 'Џ', 'd!ž' => 'дж', 'D!ž'=> 'Дж', 'D!Ž'=> 'ДЖ',
		'Lj' => 'Љ', 'l!j' => 'лј', 'L!j'=> 'Лј', 'L!J'=> 'ЛЈ',
		'Nj' => 'Њ', 'n!j' => 'нј', 'N!j'=> 'Нј', 'N!J'=> 'НЈ'
	);

	function loadDefaultTables() {
		$this->mTables = array();
		$this->mTables['sr-ec'] = $this->mToCyrillics;
		$this->mTables['sr-jc'] = $this->mToCyrillics;
		$this->mTables['sr-el'] = $this->mToLatin;
		$this->mTables['sr-jl'] = $this->mToLatin;
		$this->mTables['sr'] = array();
	}

	/* rules should be defined as -{ekavian | iyekavian-} -or-
		-{code:text | code:text | ...}-
	*/
	function parseManualRule($rule, $flags) {

		$choices = explode($this->mMarkup['varsep'], $rule);
		$carray = array();
		if(sizeof($choices) == 1) {
			if(in_array('W', $flags)) {
				$carray['sr'] = $this->autoConvert($choices[0], 'sr-ec');
				$carray['sr-ec'] = $this->autoConvert($choices[0], 'sr-ec');
				$carray['sr-jc'] = $this->autoConvert($choices[0], 'sr-jc');
				$carray['sr-el'] = $this->autoConvert($choices[0], 'sr-el');
				$carray['sr-jl'] = $this->autoConvert($choices[0], 'sr-jl');
			}
			foreach($this->mVariants as $v) {
				$carray[$v] = $choices[0];
			}
			return $carray;
		}

		/* detect which format is used, also trim the choices*/
		$n=0;
		foreach($choices as $c=>$t) {
			if(strpos($t, $this->mMarkup['codesep']) !== false) { $n++; }
			$choices[$c] = trim($t);
		}
		/* the -{code:text | ...}- format */
		if($n == sizeof($choices)) {
			foreach($choices as $c) {
				list($code, $text) = explode($this->mMarkup['codesep'], $c);
				$carray[trim($code)] = trim($text);
			}
			return $carray;
		}

		/* the two choice format -{choice1; choice2}-*/
		if(sizeof($choices == 2) && $n==0) {
			if(in_array('S', $flags)) {
				// conversion between Cyrillics and Latin
				$carray['sr'] = $carray['sr-ec'] =$carray['sr-jc'] = $choices[0];
				$carray['sr-el'] =$carray['sr-jl'] = $choices[1];
			}
			else {
				$carray['sr'] = $this->autoConvert($choices[0], 'sr-ec');
				$carray['sr-ec'] = $this->autoConvert($choices[0], 'sr-ec');
				$carray['sr-jc'] = $this->autoConvert($choices[1], 'sr-jc');
				$carray['sr-el'] = $this->autoConvert($choices[0], 'sr-el');
				$carray['sr-jl'] = $this->autoConvert($choices[1], 'sr-jl');
			}
			return $carray;
		}
		return $carray;
	}

}

class LanguageSr extends LanguageSr_ec {
	function LanguageSr() {
		global $wgHooks;
		$variants = array('sr', 'sr-ec', 'sr-jc', 'sr-el', 'sr-jl');
		$variantfallbacks = array(
			'sr'    => 'sr-ec',
			'sr-ec' => 'sr-jc',
			'sr-jc' => 'sr-ec',
			'sr-el' => 'sr-jl',
			'sr-jl' => 'sr-el'
		);
		$marker = array();//don't mess with these, leave them as they are
		$flags = array(
			'S' => 'S', 'писмо' => 'S', 'pismo' => 'S',
			'W' => 'W', 'реч'   => 'W', 'reč'   => 'W', 'ријеч' => 'W', 'riječ' => 'W'
		);
		$this->mConverter = new SrConverter($this, 'sr', $variants, $variantfallbacks, $marker, $flags);
		$wgHooks['ArticleSaveComplete'][] = $this->mConverter;
	}
	function getVariantname( $code ) {
		return wfMsg( "variantname-$code" );
	}
}
?>
