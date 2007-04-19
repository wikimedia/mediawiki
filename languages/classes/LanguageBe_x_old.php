<?php
/** Belarusian alternative (Беларуская мова)
  *
  * @addtogroup Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  * @bug 1638, 2135
  * @link http://be.wikipedia.org/wiki/Talk:LanguageBe.php
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
  * @license http://www.gnu.org/copyleft/fdl.html GNU Free Documentation License
  */

class LanguageBe_x_old extends Language {
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

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
   * Cases: родны, вінавальны, месны
   */
	function convertGrammar( $word, $case ) {
		switch ( $case ) {
			case 'родны': # genitive
				if ( $word == 'Вікіпэдыя' ) {
					$word = 'Вікіпэдыі';
				} elseif ( $word == 'ВікіСлоўнік' ) {
					$word = 'ВікіСлоўніка';
				} elseif ( $word == 'ВікіКнігі' ) {
					$word = 'ВікіКніг';
				} elseif ( $word == 'ВікіКрыніца' ) {
					$word = 'ВікіКрыніцы';
				} elseif ( $word == 'ВікіНавіны' ) {
					$word = 'ВікіНавін';
				} elseif ( $word == 'ВікіВіды' ) {
					$word = 'ВікіВідаў';
				}
			break;
			case 'вінавальны': # akusative
				if ( $word == 'Вікіпэдыя' ) {
					$word = 'Вікіпэдыю';
				} elseif ( $word == 'ВікіСлоўнік' ) {
					$word = 'ВікіСлоўнік';
				} elseif ( $word == 'ВікіКнігі' ) {
					$word = 'ВікіКнігі';
				} elseif ( $word == 'ВікіКрыніца' ) {
					$word = 'ВікіКрыніцу';
				} elseif ( $word == 'ВікіНавіны' ) {
					$word = 'ВікіНавіны';
				} elseif ( $word == 'ВікіВіды' ) {
					$word = 'ВікіВіды';
				}
			break;
			case 'месны': # prepositional
				if ( $word == 'Вікіпэдыя' ) {
					$word = 'Вікіпэдыі';
				} elseif ( $word == 'ВікіСлоўнік' ) {
					$word = 'ВікіСлоўніку';
				} elseif ( $word == 'ВікіКнігі' ) {
					$word = 'ВікіКнігах';
				} elseif ( $word == 'ВікіКрыніца' ) {
					$word = 'ВікіКрыніцы';
				} elseif ( $word == 'ВікіНавіны' ) {
					$word = 'ВікіНавінах';
				} elseif ( $word == 'ВікіВіды' ) {
					$word = 'ВікіВідах';
				}
			break;
		}

		return $word; # this will return the original value for 'назоўны' (nominative) and all undefined case values
	}

}

?>
