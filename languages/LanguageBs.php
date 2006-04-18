<?php
/** Bosnian (bosanski)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesBs = array(
	NS_MEDIA            => "Medija",
	NS_SPECIAL          => "Posebno",
	NS_MAIN             => "",
	NS_TALK             => "Razgovor",
	NS_USER             => "Korisnik",
	NS_USER_TALK        => "Razgovor_sa_korisnikom",
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => FALSE,  # Set in constructor
	NS_IMAGE            => "Slika",
	NS_IMAGE_TALK       => "Razgovor_o_slici",
	NS_MEDIAWIKI        => "MedijaViki",
	NS_MEDIAWIKI_TALK   => "Razgovor_o_MedijaVikiju",
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Razgovor_o_šablonu',
	NS_HELP             => 'Pomoć',
	NS_HELP_TALK        => 'Razgovor_o_pomoći',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsBs = array(
 "Nikakva", "Pričvršćena lijevo", "Pričvršćena desno", "Plutajuća lijevo"
);

/* private */ $wgSkinNamesBs = array(
 "Obična", "Nostalgija", "Kelnsko plavo", "Pedington", "Monparnas"
) + $wgSkinNamesEn;

/* private */ $wgUserTogglesBs = array(
	'nolangconversion',
) + $wgUserTogglesEn;

/* private */ $wgDateFormatsBs = array(
	'Nije bitno',
	'06:12, 5. januar 2001.',
	'06:12, 5 januar 2001',
	'06:12, 05.01.2001.',
	'06:12, 5.1.2001.',
	'06:12, 5. jan 2001.',
	'06:12, 5 jan 2001',
	'6:12, 5. januar 2001.',
	'6:12, 5 januar 2001',
	'6:12, 05.01.2001.',
	'6:12, 5.1.2001.',
	'6:12, 5. jan 2001.',
	'6:12, 5 jan 2001',
);

/* private */ $wgMagicWordsBs = array(
#	ID                                CASE SYNONYMS
	MAG_REDIRECT             => array( 0, '#Preusmjeri', '#redirect', '#preusmjeri', '#PREUSMJERI' ),
	MAG_NOTOC                => array( 0, '__NOTOC__', '__BEZSADRŽAJA__' ),
	MAG_FORCETOC             => array( 0, '__FORCETOC__', '__FORSIRANISADRŽAJ__' ),
	MAG_TOC                  => array( 0, '__TOC__', '__SADRŽAJ__' ),
	MAG_NOEDITSECTION        => array( 0, '__NOEDITSECTION__', '__BEZ_IZMENA__', '__BEZIZMENA__' ),
	MAG_START                => array( 0, '__START__', '__POČETAK__' ),
	MAG_END                  => array( 0, '__END__', '__KRAJ__' ),
	MAG_CURRENTMONTH         => array( 1, 'CURRENTMONTH', 'TRENUTNIMJESEC' ),
	MAG_CURRENTMONTHNAME     => array( 1, 'CURRENTMONTHNAME', 'TRENUTNIMJESECIME' ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1, 'CURRENTMONTHNAMEGEN', 'TRENUTNIMJESECROD' ),
	MAG_CURRENTMONTHABBREV   => array( 1, 'CURRENTMONTHABBREV', 'TRENUTNIMJESECSKR' ),
	MAG_CURRENTDAY           => array( 1, 'CURRENTDAY', 'TRENUTNIDAN' ),
	MAG_CURRENTDAYNAME       => array( 1, 'CURRENTDAYNAME', 'TRENUTNIDANIME' ),
	MAG_CURRENTYEAR          => array( 1, 'CURRENTYEAR', 'TRENUTNAGODINA' ),
	MAG_CURRENTTIME          => array( 1, 'CURRENTTIME', 'TRENUTNOVRIJEME' ),
	MAG_NUMBEROFARTICLES     => array( 1, 'NUMBEROFARTICLES', 'BROJČLANAKA' ),
	MAG_NUMBEROFFILES        => array( 1, 'NUMBEROFFILES', 'BROJDATOTEKA', 'BROJFAJLOVA' ),
	MAG_PAGENAME             => array( 1, 'PAGENAME', 'STRANICA' ),
	MAG_PAGENAMEE            => array( 1, 'PAGENAMEE', 'STRANICE' ),
	MAG_NAMESPACE            => array( 1, 'NAMESPACE', 'IMENSKIPROSTOR' ),
	MAG_NAMESPACEE           => array( 1, 'NAMESPACEE', 'IMENSKIPROSTORI' ),
	MAG_FULLPAGENAME         => array( 1, 'FULLPAGENAME', 'PUNOIMESTRANE' ),
	MAG_FULLPAGENAMEE        => array( 1, 'FULLPAGENAMEE', 'PUNOIMESTRANEE' ),
	MAG_MSG                  => array( 0, 'MSG:', 'POR:' ),
	MAG_SUBST                => array( 0, 'SUBST:', 'ZAMJENI:' ),
	MAG_MSGNW                => array( 0, 'MSGNW:', 'NVPOR:' ),
	MAG_IMG_THUMBNAIL        => array( 1, 'thumbnail', 'thumb', 'mini' ),
	MAG_IMG_MANUALTHUMB      => array( 1, 'thumbnail=$1', 'thumb=$1', 'mini=$1' ),
	MAG_IMG_RIGHT            => array( 1, 'right', 'desno', 'd' ),
	MAG_IMG_LEFT             => array( 1, 'left', 'lijevo', 'l' ),
	MAG_IMG_NONE             => array( 1, 'none', 'n', 'bez' ),
	MAG_IMG_WIDTH            => array( 1, '$1px', '$1piksel' , '$1p' ),
	MAG_IMG_CENTER           => array( 1, 'center', 'centre', 'centar', 'c' ),
	MAG_IMG_FRAMED           => array( 1, 'framed', 'enframed', 'frame', 'okvir', 'ram' ),
	MAG_INT                  => array( 0, 'INT:', 'INT:' ),
	MAG_SITENAME             => array( 1, 'SITENAME', 'IMESAJTA' ),
	MAG_NS                   => array( 0, 'NS:', 'IP:' ),
	MAG_LOCALURL             => array( 0, 'LOCALURL:', 'LOKALNAADRESA:' ),
	MAG_LOCALURLE            => array( 0, 'LOCALURLE:', 'LOKALNEADRESE:' ),
	MAG_SERVER               => array( 0, 'SERVER', 'SERVER' ),
	MAG_SERVERNAME           => array( 0, 'SERVERNAME', 'IMESERVERA' ),
	MAG_SCRIPTPATH           => array( 0, 'SCRIPTPATH', 'SKRIPTA' ),
	MAG_GRAMMAR              => array( 0, 'GRAMMAR:', 'GRAMATIKA:' ),
	MAG_NOTITLECONVERT       => array( 0, '__NOTITLECONVERT__', '__NOTC__', '__BEZTC__' ),
	MAG_NOCONTENTCONVERT     => array( 0, '__NOCONTENTCONVERT__', '__NOCC__', '__BEZCC__' ),
	MAG_CURRENTWEEK          => array( 1, 'CURRENTWEEK', 'TRENUTNASEDMICA' ),
	MAG_CURRENTDOW           => array( 1, 'CURRENTDOW', 'TRENUTNIDOV' ),
	MAG_REVISIONID           => array( 1, 'REVISIONID', 'IDREVIZIJE' ),
	MAG_PLURAL               => array( 0, 'PLURAL:', 'MNOŽINA:' ),
	MAG_FULLURL              => array( 0, 'FULLURL:', 'PUNURL:' ),
	MAG_FULLURLE             => array( 0, 'FULLURLE:', 'PUNURLE:' ),
	MAG_LCFIRST              => array( 0, 'LCFIRST:', 'LCPRVI:' ),
	MAG_UCFIRST              => array( 0, 'UCFIRST:', 'UCPRVI:' ),
	MAG_LC                   => array( 0, 'LC:', 'LC:' ),
	MAG_UC                   => array( 0, 'UC:', 'UC:' ),
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesBs.php');
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageBs extends LanguageUtf8 {
    function LanguageBs() {
        global $wgNamespaceNamesBs, $wgMetaNamespace;
        LanguageUtf8::LanguageUtf8();
        $wgNamespaceNamesBs[NS_PROJECT_TALK] = 'Razgovor_' .
            str_replace( ' ', '_',
                $this->convertGrammar( $wgMetaNamespace, 'instrumental' ) );
    }

    function getNamespaces() {
        global $wgNamespaceNamesBs;
        return $wgNamespaceNamesBs;
    }

    function getQuickbarSettings() {
        global $wgQuickbarSettingsBs;
        return $wgQuickbarSettingsBs;
    }

    function getDateFormats() {
        global $wgDateFormatsBs;
        return $wgDateFormatsBs;
    }

    function getMessage( $key ) {
        global $wgAllMessagesBs;
        if(array_key_exists($key, $wgAllMessagesBs))
            return $wgAllMessagesBs[$key];
        else
            return parent::getMessage($key);
    }

    function fallback8bitEncoding() {
        return "iso-8859-2";
    }

    function formatNum( $number, $year = false ) {
        return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
    }

    # Convert from the nominative form of a noun to some other case
    # Invoked with {{GRAMMAR:case|word}}
    function convertGrammar( $word, $case ) {
        switch ( $case ) {
            case 'genitiv': # genitive
                if ( $word == 'Vikipedija' ) {
                    $word = 'Vikipedije';
                } elseif ( $word == 'Vikiknjige' ) {
                    $word = 'Vikiknjiga';
                } elseif ( $word == 'Vikivijesti' ) {
                    $word = 'Vikivijesti';
                } elseif ( $word == 'Vikicitati' ) {
                    $word = 'Vikicitata';
                } elseif ( $word == 'Vikiizvor' ) {
                    $word = 'Vikiizvora';
                } elseif ( $word == 'Vikiriječnik' ) {
                    $word = 'Vikiriječnika';
                }
            break;
            case 'dativ': # dative
                if ( $word == 'Vikipedija' ) {
                    $word = 'Vikipediji';
                } elseif ( $word == 'Vikiknjige' ) {
                    $word = 'Vikiknjigama';
                } elseif ( $word == 'Vikicitati' ) {
                    $word = 'Vikicitatima';
                } elseif ( $word == 'Vikivijesti' ) {
                    $word = 'Vikivijestima';
                } elseif ( $word == 'Vikiizvor' ) {
                    $word = 'Vikiizvoru';
                } elseif ( $word == 'Vikiriječnik' ) {
                    $word = 'Vikiriječniku';
                }
            break;
            case 'akuzativ': # akusative
                if ( $word == 'Vikipedija' ) {
                    $word = 'Vikipediju';
                } elseif ( $word == 'Vikiknjige' ) {
                    $word = 'Vikiknjige';
                } elseif ( $word == 'Vikicitati' ) {
                    $word = 'Vikicitate';
                } elseif ( $word == 'Vikivijesti' ) {
                    $word = 'Vikivijesti';
                } elseif ( $word == 'Vikiizvor' ) {
                    $word = 'Vikiizvora';
                } elseif ( $word == 'Vikiriječnik' ) {
                    $word = 'Vikiriječnika';
                }
            break;
            case 'vokativ': # vocative
                if ( $word == 'Vikipedija' ) {
                    $word = 'Vikipedijo';
                } elseif ( $word == 'Vikiknjige' ) {
                    $word = 'Vikiknjige';
                } elseif ( $word == 'Vikicitati' ) {
                    $word = 'Vikicitati';
                } elseif ( $word == 'Vikivijesti' ) {
                    $word = 'Vikivijesti';
                } elseif ( $word == 'Vikiizvor' ) {
                    $word = 'Vikizivoru';
                } elseif ( $word == 'Vikiriječnik' ) {
                    $word = 'Vikiriječniče';
                }
            break;
            case 'instrumental': # instrumental
                if ( $word == 'Vikipedija' ) {
                    $word = 's Vikipediom';
                } elseif ( $word == 'Vikiknjige' ) {
                    $word = 's Vikiknjigama';
                } elseif ( $word == 'Vikicitati' ) {
                    $word = 's Vikicitatima';
                } elseif ( $word == 'Vikivijesti' ) {
                    $word = 's Vikivijestima';
                } elseif ( $word == 'Vikiizvor' ) {
                    $word = 's Vikiizvorom';
                } elseif ( $word == 'Vikiriječnik' ) {
                    $word = 's Vikiriječnikom';
                } else {
                    $word = 's ' . $word;
                }
            break;
            case 'lokativ': # locative
                if ( $word == 'Vikipedija' ) {
                    $word = 'o Wikipediji';
                } elseif ( $word == 'Vikiknjige' ) {
                    $word = 'o Vikiknjigama';
                } elseif ( $word == 'Vikicitati' ) {
                    $word = 'o Vikicitatima';
                } elseif ( $word == 'Vikivijesti' ) {
                    $word = 'o Vikivijestima';
                } elseif ( $word == 'Vikiizvor' ) {
                    $word = 'o Vikiizvoru';
                } elseif ( $word == 'Vikiriječnik' ) {
                    $word = 'o Vikiriječniku';
                } else {
                    $word = 'o ' . $word;
                }
            break;
        }

        return $word; # this will return the original value for 'nominativ' (nominative) and all undefined case values
    }

}

?>
