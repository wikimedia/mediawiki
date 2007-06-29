<?php
/**
  * @addtogroup Language
  */

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageSr_el extends Language {
	function convertPlural( $count, $wordform1, $wordform2, $wordform3, $w4, $w5) {
		$count = str_replace ('.', '', $count);
		if ($count > 10 && floor(($count % 100) / 10) == 1) {
			return $wordform3;
		} else {
			switch ($count % 10) {
				case 1: return $wordform1;
				case 2:
				case 3:
				case 4: return $wordform2;
				default: return $wordform3;
			}
		}
	}

}

