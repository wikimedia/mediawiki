<?php
/** Finnish (Suomi)
 *
 * @addtogroup Language
 */

$skinNames = array(
	'standard'          => 'Perus',
	'cologneblue'       => 'Kölnin sininen',
	'myskin'            => 'Oma tyylisivu'
);

$quickbarSettings = array(
	'Ei mitään', 'Tekstin mukana, vasen', 'Tekstin mukana, oikea', 'Pysyen vasemmalla', 'Pysyen oikealla'
);

$datePreferences = array(
	'default',
	'fi normal',
	'fi seconds',
	'fi numeric',
	'ISO 8601',
);

$defaultDateFormat = 'fi normal';

$dateFormats = array(
	'fi normal time' => 'H.i',
	'fi normal date' => 'j. F"ta" Y',
	'fi normal both' => 'j. F"ta" Y "kello" H.i',

	'fi seconds time' => 'H:i:s',
	'fi seconds date' => 'j. F"ta" Y',
	'fi seconds both' => 'j. F"ta" Y "kello" H:i:s',

	'fi numeric time' => 'H.i',
	'fi numeric date' => 'j.n.Y',
	'fi numeric both' => 'j.n.Y "kello" H.i',
);

$datePreferenceMigrationMap = array(
	'default',
	'fi normal',
	'fi seconds',
	'fi numeric',
);

$bookstoreList = array(
	'Bookplus'                      => 'http://www.bookplus.fi/product.php?isbn=$1',
	'Helsingin yliopiston kirjasto' => 'http://pandora.lib.hel.fi/cgi-bin/mhask/monihask.py?volname=&author=&keyword=&ident=$1&submit=Hae&engine_helka=ON',
	'Pääkaupunkiseudun kirjastot'   => 'http://www.helmet.fi/search*fin/i?SEARCH=$1',
	'Tampereen seudun kirjastot'    => 'http://kirjasto.tampere.fi/Piki?formid=fullt&typ0=6&dat0=$1'
);

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Toiminnot',
	NS_MAIN             => '',
	NS_TALK             => 'Keskustelu',
	NS_USER             => 'Käyttäjä',
	NS_USER_TALK        => 'Keskustelu_käyttäjästä',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Keskustelu_{{grammar:elative|$1}}',
	NS_IMAGE            => 'Kuva',
	NS_IMAGE_TALK       => 'Keskustelu_kuvasta',
	NS_MEDIAWIKI        => 'Järjestelmäviesti',
	NS_MEDIAWIKI_TALK   => 'Keskustelu_järjestelmäviestistä',
	NS_TEMPLATE         => 'Malline',
	NS_TEMPLATE_TALK    => 'Keskustelu_mallineesta',
	NS_HELP             => 'Ohje',
	NS_HELP_TALK        => 'Keskustelu_ohjeesta',
	NS_CATEGORY         => 'Luokka',
	NS_CATEGORY_TALK    => 'Keskustelu_luokasta'
);

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );
$linkTrail = '/^([a-zäö]+)(.*)$/sDu';

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Kaksinkertaiset_uudelleenohjaukset' ),
	'BrokenRedirects'           => array( 'Virheelliset_uudelleenohjaukset' ),
	'Disambiguations'           => array( 'Täsmennyssivut' ),
	'Userlogin'                 => array( 'Kirjaudu_sisään' ),
	'Userlogout'                => array( 'Kirjaudu_ulos' ),
	'Preferences'               => array( 'Asetukset' ),
	'Watchlist'                 => array( 'Tarkkailulista' ),
	'Recentchanges'             => array( 'Tuoreet_muutokset' ),
	'Upload'                    => array( 'Lisää_tiedosto' ),
	'Imagelist'                 => array( 'Tiedostoluettelo' ),
	'Newimages'                 => array( 'Uudet_kuvat' ),
	'Listusers'                 => array( 'Käyttäjät' ),
	'Statistics'                => array( 'Tilastot' ),
	'Randompage'                => array( 'Satunnainen_sivu' ),
	'Lonelypages'               => array( 'Yksinäiset_sivut' ),
	'Uncategorizedpages'        => array( 'Luokittelemattomat_sivut' ),
	'Uncategorizedcategories'   => array( 'Luokittelemattomat_luokat' ),
	'Uncategorizedimages'       => array( 'Luokittelemattomat_tiedostot' ),
	'Unusedcategories'          => array( 'Käyttämättömät_luokat' ),
	'Unusedimages'              => array( 'Käyttämättömät_tiedostot' ),
	'Wantedpages'               => array( 'Halutuimmat_sivut' ),
	'Wantedcategories'          => array( 'Halutuimmat_luokat' ),
	'Mostlinked'                => array( 'Viitatuimmat_sivut' ),
	'Mostlinkedcategories'      => array( 'Viitatuimmat_luokat' ),
	'Mostcategories'            => array( 'Luokitelluimmat_sivut' ),
	'Mostimages'                => array( 'Viitatuimmat_kuvat' ),
	'Mostrevisions'             => array( 'Muokatuimmat_sivut' ),
	'Shortpages'                => array( 'Lyhyet_sivut' ),
	'Longpages'                 => array( 'Pitkät_sivut' ),
	'Newpages'                  => array( 'Uudet_sivut' ),
	'Ancientpages'              => array( 'Kuolleet_sivut' ),
	'Deadendpages'              => array( 'Linkittömät_sivut' ),
	'Allpages'                  => array( 'Kaikki_sivut' ),
	'Prefixindex'               => array( 'Etuliiteluettelo' ) ,
	'Ipblocklist'               => array( 'Muokkausestot' ),
	'Specialpages'              => array( 'Toimintosivut' ),
	'Contributions'             => array( 'Muokkaukset' ),
	'Emailuser'                 => array( 'Lähetä_sähköpostia' ),
	'Whatlinkshere'             => array( 'Tänne_viittaavat_sivut' ),
	'Recentchangeslinked'       => array( 'Linkitetyt_muutokset' ),
	'Movepage'                  => array( 'Siirrä_sivu' ),
	'Blockme'                   => array( 'Estä_minut' ),
	'Booksources'               => array( 'Kirjalähteet' ),
	'Categories'                => array( 'Luokat' ),
	'Export'                    => array( 'Vie_sivuja' ),
	'Version'                   => array( 'Versio' ),
	'Allmessages'               => array( 'Järjestelmäviestit' ),
	'Log'                       => array( 'Loki', 'Lokit' ),
	'Blockip'                   => array( 'Estä' ),
	'Undelete'                  => array( 'Palauta' ),
	'Import'                    => array( 'Tuo_sivuja' ),
	'Lockdb'                    => array( 'Lukitse_tietokanta' ),
	'Unlockdb'                  => array( 'Avaa_tietokanta' ),
	'Userrights'                => array( 'Käyttöoikeudet' ),
	'MIMEsearch'                => array( 'MIME-haku' ),
	'Unwatchedpages'            => array( 'Tarkkailemattomat_sivut' ),
	'Listredirects'             => array( 'Uudelleenohjaukset' ),
	'Revisiondelete'            => array( 'Poista_muokkaus' ),
	'Unusedtemplates'           => array( 'Käyttämättömät_mallineet' ),
	'Randomredirect'            => array( 'Satunnainen_uudelleenohjaus' ),
	'Mypage'                    => array( 'Oma_sivu' ),
	'Mytalk'                    => array( 'Oma_keskustelu' ),
	'Mycontributions'           => array( 'Omat_muokkaukset' ),
	'Listadmins'                => array( 'Ylläpitäjät' ),
	'Popularpages'              => array( 'Suositut_sivut' ),
	'Search'                    => array( 'Haku' ),
	'Resetpass'                 => array( 'Alusta_salasana' ),
);


$messages = array(

# User preference toggles
'tog-underline'       => 'Alleviivaa linkit:',
'tog-highlightbroken' => 'Näytä linkit puuttuville sivuille <a href="#" class="new">näin</a> (vaihtoehtoisesti näin: <a href="#" class="internal">?</a>).',
'tog-justify'         => 'Tasaa kappaleet',
'tog-hideminor'       => 'Piilota pienet muutokset tuoreet muutokset -listasta',
'tog-extendwatchlist' => 'Laajenna tarkkailulista näyttämään kaikki tehdyt muutokset',
'tog-usenewrc'        => 'Kehittynyt tuoreet muutokset -listaus (JavaScript)',
'tog-numberheadings'  => 'Numeroi otsikot',
'tog-showtoolbar'     => 'Näytä työkalupalkki',
'tog-editondblclick'  => 'Muokkaa sivuja kaksoisnapsautuksella (JavaScript)',
'tog-editsection'     => 'Näytä muokkauslinkit jokaisen osion yläpuolella',
'tog-editsectiononrightclick' => 'Muokkaa osioita napsauttamalla otsikkoa hiiren oikealla painikkeella (JavaScript)',
'tog-showtoc'         =>'Näytä sisällysluettelo sivuille, joilla yli 3 otsikkoa',
'tog-rememberpassword'=> 'Älä kysy salasanaa saman yhteyden eri istuntojen välillä',
'tog-editwidth'       => 'Muokkauskenttä on sivun levyinen',
'tog-watchcreations'  => 'Lisää luomani sivut tarkkailulistalle',
'tog-watchdefault'    => 'Lisää oletuksena uudet ja muokatut sivut tarkkailulistalle',
'tog-watchmoves'      => 'Lisää siirtämäni sivut tarkkailulistalle',
'tog-watchdeletion'   => 'Lisää poistamani sivut tarkkailulistalle',
'tog-minordefault'    => 'Muutokset ovat oletuksena pieniä',
'tog-previewontop'    => 'Näytä esikatselu muokkauskentän yläpuolella',
'tog-previewonfirst'  => 'Näytä esikatselu heti, kun muokkaus aloitetaan',
'tog-nocache'         => 'Älä tallenna sivuja välimuistiin',
'tog-enotifwatchlistpages' => 'Lähetä sähköpostiviesti tarkkailtujen sivujen muutoksista',
'tog-enotifusertalkpages'  => 'Lähetä sähköpostiviesti, kun käyttäjäsivun keskustelusivu muuttuu',
'tog-enotifminoredits'     => 'Lähetä sähköpostiviesti myös pienistä muokkauksista',
'tog-enotifrevealaddr'     => 'Näytä sähköpostiosoitteeni muille lähetetyissä ilmoituksissa',
'tog-shownumberswatching'  => 'Näytä sivua tarkkailevien käyttäjien määrä',
'tog-fancysig'        => 'Muotoilematon allekirjoitus ilman automaattista linkkiä',
'tog-externaleditor'  => 'Käytä ulkoista tekstieditoria oletuksena',
'tog-externaldiff'    => 'Käytä ulkoista diff-ohjelmaa oletuksena',
'tog-showjumplinks'   => 'Lisää loikkaa-käytettävyyslinkit sivun alkuun',
'tog-uselivepreview'  => 'Käytä pikaesikatselua (JavaScript) (kokeellinen)',
'tog-forceeditsummary'=> 'Huomauta, jos yhteenvetoa ei ole annettu',
'tog-watchlisthideown'  => 'Piilota omat muokkaukset',
'tog-watchlisthidebots' => 'Piilota bottien muokkaukset',
'tog-watchlisthideminor'=> 'Piilota pienet muokkaukset',
'tog-nolangconversion'  => 'Älä tee muunnoksia kielivarianttien välillä',
'tog-ccmeonemails'      => 'Lähetä minulle kopio MediaWikin kautta lähetetyistä sähköposteista',
'tog-diffonly'        => 'Älä näytä sivun sisältöä versioita vertailtaessa',

'underline-always'    => 'Aina',
'underline-never'     => 'Ei koskaan',
'underline-default'   => 'Selaimen oletustapa',

'skinpreview'         => '(Esikatsele...)',

# dates
'sunday'      => 'sunnuntai',
'monday'      => 'maanantai',
'tuesday'     => 'tiistai',
'wednesday'   => 'keskiviikko',
'thursday'    => 'torstai',
'friday'      => 'perjantai',
'saturday'    => 'lauantai',
'sun'         => 'su',
'mon'         => 'ma',
'tue'         => 'ti',
'wed'         => 'ke',
'thu'         => 'to',
'fri'         => 'pe',
'sat'         => 'la',
'january'     => 'tammikuu',
'february'    => 'helmikuu',
'march'       => 'maaliskuu',
'april'       => 'huhtikuu',
'may_long'    => 'toukokuu',
'june'        => 'kesäkuu',
'july'        => 'heinäkuu',
'august'      => 'elokuu',
'september'   => 'syyskuu',
'october'     => 'lokakuu',
'november'    => 'marraskuu',
'december'    => 'joulukuu',
'january-gen' => 'tammikuun',
'february-gen'=> 'helmikuun',
'march-gen'   => 'maaliskuun',
'april-gen'   => 'huhtikuun',
'may-gen'     => 'toukokuun',
'june-gen'    => 'kesäkuun',
'july-gen'    => 'heinäkuun',
'august-gen'  => 'elokuun',
'september-gen' => 'syyskuun',
'october-gen' => 'lokakuun',
'november-gen'=> 'marraskuun',
'december-gen'=> 'joulukuun',
'jan'         => 'tammikuu',
'feb'         => 'helmikuu',
'mar'         => 'maaliskuu',
'apr'         => 'huhtikuu',
'may'         => 'toukokuu',
'jun'         => 'kesäkuu',
'jul'         => 'heinäkuu',
'aug'         => 'elokuu',
'sep'         => 'syyskuu',
'oct'         => 'lokakuu',
'nov'         => 'marraskuu',
'dec'         => 'joulukuu',

# Bits of text used by many pages:
#
'categories'          => 'Luokat',
'pagecategories'      => '{{PLURAL:$1|Luokka|Luokat}}',
'category_header'     => 'Sivut, jotka ovat luokassa $1',
'subcategories'       => 'Alaluokat',
'category-media-header' => 'Luokan ”$1” sisältämät mediatiedostot',
'mainpage'            => 'Etusivu',
'mainpagetext'        => '\'\'\'Mediawiki on onnistuneesti asennettu.\'\'\'',
'mainpagedocfooter'   => 'Lisätietoja käytöstä on sivulla [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide User\'s Guide].
=== Lisäohjeita===
* [http://www.mediawiki.org/wiki/Help:Configuration_settings Asetusten teko-ohjeita]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWikin FAQ]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce Sähköpostilista, jolla tiedotetaan MediaWikin uusista versioista]",
=== Asetukset ===
Tarkista, että alla olevat taivutusmuodot ovat oikein. Jos eivät, tee tarvittavat muutokset LocalSettings.php:hen seuraavasti:
 $wgGrammarForms[\'fi\'][\'genitive\'][\'{{SITENAME}}\'] = \'...\';
 $wgGrammarForms[\'fi\'][\'partitive\'][\'{{SITENAME}}\'] = \'...\';
 $wgGrammarForms[\'fi\'][\'elative\'][\'{{SITENAME}}\'] = \'...\';
 $wgGrammarForms[\'fi\'][\'inessive\'][\'{{SITENAME}}\'] = \'...\';
 $wgGrammarForms[\'fi\'][\'illative\'][\'{{SITENAME}}\'] = \'...\';
Taivutusmuodot: {{GRAMMAR:genitive|{{SITENAME}}}} (yön) — {{GRAMMAR:partitive|{{SITENAME}}}} (yötä) — {{GRAMMAR:elative|{{SITENAME}}}} (yöstä) — {{GRAMMAR:inessive|{{SITENAME}}}} (yössä) — {{GRAMMAR:illative|{{SITENAME}}}} (yöhön).',
'portal'              => 'Kahvihuone',
'portal-url'          => '{{ns:project}}:Kahvihuone',
'about'               => 'Tietoja',
'aboutsite'           => 'Tietoja {{GRAMMAR:elative|{{SITENAME}}}}',
'aboutpage'           => '{{ns:project}}:Tietoja',
'article'             => 'Sivu',
'help'                => 'Ohje',
'helppage'            => '{{ns:help}}:Ohje',
'bugreports'          => 'Ongelmat ja parannusehdotukset',
'bugreportspage'      => '{{ns:project}}:Ongelmat ja parannusehdotukset',
'sitesupport'         => 'Lahjoitukset',
'sitesupport-url'     => '{{ns:project}}:Lahjoitukset',

'faq'                 => 'FAQ',
'faqpage'             => '{{ns:project}}:FAQ',
'edithelp'            => 'Muokkausohjeet',
'newwindow'           => '(avautuu uuteen ikkunaan)',
'edithelppage'        => '{{ns:help}}:Kuinka_sivuja_muokataan',
'cancel'              => 'Keskeytä',
'qbfind'              => 'Etsi',
'qbbrowse'            => 'Selaa',
'qbedit'              => 'Muokkaa',
'qbpageoptions'       => 'Sivuasetukset',
'qbpageinfo'          => 'Sivun tiedot',
'qbmyoptions'         => 'Asetukset',
'qbspecialpages'      => 'Toimintosivut',
'moredotdotdot'       => 'Lisää...',
'mypage'              => 'Käyttäjäsivu',
'mytalk'              => 'Keskustelusivu',
'anontalk'            => 'Keskustele tämän IP:n kanssa',
'navigation'          => 'Valikko',

# Metadata in edit box
'metadata_help'       => 'Sisältökuvaukset (lisätietoja sivulla [[Project:Sisältökuvaukset]]):',

'currentevents'       => 'Ajankohtaista',
'currentevents-url'   => '{{ns:project}}:Ajankohtaista',

'disclaimers'         => 'Vastuuvapaus',
'disclaimerpage'      => '{{ns:project}}:Vastuuvapaus',
'privacy'             => 'Tietosuojakäytäntö',
'privacypage'         => '{{ns:project}}:Tietosuojakäytäntö',
'errorpagetitle'      => 'Virhe',
'returnto'            => 'Palaa sivulle $1.',
'tagline'             => '{{SITENAME}}',
'whatlinkshere'       => 'Tänne viittaavat sivut',
'help'                => 'Ohje',
'search'              => 'Haku',
'searchbutton'        => 'Etsi',
'go'                  => 'Siirry',
'searcharticle'       => 'Siirry',
'history'             => 'Historia',
'history_short'       => 'Historia',
'updatedmarker'       => 'päivitetty viimeisimmän käyntisi jälkeen',
'info_short'          => 'Tiedostus',
'printableversion'    => 'Tulostettava versio',
'permalink'           => 'Ikilinkki',
'print'               => 'Tulosta',
'edit'                => 'Muokkaa',
'editthispage'        => 'Muokkaa tätä sivua',
'delete'              => 'Poista',
'deletethispage'      => 'Poista tämä sivu',
'undelete_short'      => 'Palauta $1 muokkausta',
'protect'             => 'Suojaa',
'protectthispage'     => 'Suojaa tämä sivu',
'unprotect'           => 'Poista suojaus',
'unprotectthispage'   => 'Poista tämän sivun suojaus',
'newpage'             => 'Uusi sivu',
'talkpage'            => 'Keskustele tästä sivusta',
'specialpage'         => 'Toimintosivu',
'personaltools'       => 'Henkilökohtaiset työkalut',
'postcomment'         => 'Kommentti sivun loppuun',
'articlepage'         => 'Näytä varsinainen sivu',
'talk'                => 'Keskustelu',
'views'               => 'Näkymät',
'toolbox'             => 'Työkalut',
'userpage'            => 'Näytä käyttäjäsivu',
'projectpage'         => 'Näytä projektisivu',
'imagepage'           => 'Näytä kuvasivu',
'mediawikipage'       => 'Näytä viestisivu',
'templatepage'        => 'Näytä mallinesivu',
'viewhelppage'        => 'Näytä ohjesivu',
'categorypage'        => 'Näytä luokkasivu',
'viewtalkpage'        => 'Näytä keskustelusivu',
'otherlanguages'      => 'Muut kielet',
'redirectedfrom'      => 'Uudelleenohjattu sivulta $1',
'redirectpagesub'     => 'Uudelleenohjaussivu',
'lastmodifiedat'      => 'Sivua on viimeksi muutettu $1 kello $2.',
'viewcount'           => 'Tämä sivu on näytetty {{PLURAL:$1|yhden kerran|$1 kertaa}}.',
'copyright'           => 'Sisältö on käytettävissä lisenssillä $1.',
'protectedpage'       => 'Suojattu sivu',
'jumpto'              => 'Loikkaa:',
'jumptonavigation'    => 'valikkoon',
'jumptosearch'        => 'hakuun',

'badaccess'           => 'Lupa evätty',
'badaccess-group0'    => 'Sinulla ei ole lupaa suorittaa pyydettyä toimintoa.',
'badaccess-group1'    => 'Pyytämäsi toiminto on rajoitettu henkilöille ryhmässä $1.',
'badaccess-group2'    => 'Pyytämäsi toiminto on rajoitettu henkilöille ryhmissä $1.',
'badaccess-groups'    => 'Pyytämäsi toiminto on rajoitettu ryhmien $1 henkilöille.',

'versionrequired'     => 'Mediawikistä tarvitaan vähintään versio $1',
'versionrequiredtext' => 'Mediawikistä tarvitaan vähintään versio $1 tämän sivun käyttämiseen. Katso [[Special:Version|versio]]',

'ok'                  => 'OK',
'pagetitle'           => '$1 — {{SITENAME}}',
'retrievedfrom'       => 'Haettu osoitteesta $1',
'youhavenewmessages'  => 'Sinulle on $1 ($2).',
'newmessageslink'     => 'uusia viestejä',
'newmessagesdifflink' => 'viimeisin muutos',
'editsection'         => 'muokkaa',
'editold'         => 'muokkaa',
'editsectionhint'     => 'Muokkaa osiota $1',
'toc'                 => 'Sisällysluettelo',
'showtoc'             => 'näytä',
'hidetoc'             => 'piilota',
'thisisdeleted'       => 'Näytä tai palauta $1.',
'viewdeleted'         => 'Näytä $1?',
'restorelink'         => '{{PLURAL:$1|yksi poistettu muokkaus|$1 poistettua muokkausta}}',
'feedlinks'           => 'Uutissyötteet:',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'          => 'Sivu',
'nstab-user'          => 'Käyttäjäsivu',
'nstab-media'         => 'Media',
'nstab-special'       => 'Toiminto',
'nstab-project'       => 'Projektisivu',
'nstab-image'         => 'Tiedosto',
'nstab-mediawiki'     => 'Järjestelmäviesti',
'nstab-template'      => 'Malline',
'nstab-help'          => 'Ohje',
'nstab-category'      => 'Luokka',

# Main script and global functions
#
'nosuchaction'        => 'Määrittelemätön pyyntö',
'nosuchactiontext'    => 'Wikiohjelmisto ei tunnista URL:ssä määriteltyä pyyntöä',
'nosuchspecialpage'   => 'Kyseistä toimintosivua ei ole',
'nospecialpagetext'   => 'Wikiohjelmisto ei tunnista pyytämääsi toimintosivua.',

# General errors
#
'error'               => 'Virhe',
'databaseerror'       => 'Tietokantavirhe',
'dberrortext'         => 'Tietokantakyselyssä oli syntaksivirhe. Syynä saattaa olla virheellinen kysely, tai se saattaa johtua ohjelmointivirheestä. Viimeinen tietokantakysely, jota yritettiin, oli: <blockquote><tt>$1</tt></blockquote>. Se tehtiin funktiosta ”<tt>$2</tt>”. MySQL palautti virheen ”<tt>$3: $4</tt>”.',
'dberrortextcl'       => 'Tietokantakyselyssä oli syntaksivirhe. Viimeinen tietokantakysely, jota yritettiin, oli: ”$1”. Se tehtiin funktiosta ”$2”. MySQL palautti virheen ”$3: $4”.',
'noconnect'           => 'Tietokantaongelma.<br />$1',
'nodb'                => 'Tietokantaa $1 ei voitu valita',
'cachederror'         => 'Pyydetystä sivusta näytettiin välimuistissa oleva kopio, ja se saattaa olla vanhentunut.',
'laggedslavemode'     => 'Varoitus: Sivu ei välttämättä sisällä viimeisimpiä muutoksia.',
'readonly'            => 'Tietokanta on lukittu',
'enterlockreason'     => 'Anna lukituksen syy sekä sen arvioitu poistamisaika',
'readonlytext'        => '{{GRAMMAR:genitive|{{SITENAME}}}} tietokanta on tällä hetkellä lukittu. Uusia sivuja ei voi luoda eikä muitakaan muutoksia tehdä. Syynä ovat todennäköisimmin rutiininomaiset tietokannan ylläpitotoimet. Tietokannan lukinneen ylläpitäjän selitys: $1',
'missingarticle'      => 'Tietokannasta ei löytynyt sivua \'\'\'$1\'\'\'. Sivu on saatettu poistaa, tai palvelin ei ole ehtinyt vielä käsitellä sitä. Jälkimmäisessä tapauksessa koita hetken päästä uudelleen. Jos ongelma ei katoa, ota yhteyttä ylläpitäjään ja anna mukaan tämän sivun URL-osoite.',
'readonly_lag'        => 'Tietokanta on automaattisesti lukittu, jotta kaikki tietokantapalvelimet saisivat kaikki tuoreet muutokset',
'internalerror'       => 'Sisäinen virhe',
'filecopyerror'       => 'Tiedostoa <b>$1</b> ei voitu kopioida tiedostoksi <b>$2</b>.',
'filerenameerror'     => 'Tiedostoa <b>$1</b> ei voitu nimetä uudelleen nimellä <b>$2</b>.',
'filedeleteerror'     => 'Tiedostoa <b>$1</b> ei voitu poistaa.',
'filenotfound'        => 'Tiedostoa <b>$1</b> ei löytynyt.',
'unexpected'          => 'Odottamaton arvo: ”$1” on ”$2”.',
'formerror'           => 'Lomakkeen tiedot eivät kelpaa',
'badarticleerror'     => 'Toimintoa ei voi suorittaa tälle sivulle.',
'cannotdelete'        => 'Sivun tai tiedoston poisto epäonnistui. Joku muu on saattanut poistaa sen.',
'badtitle'            => 'Virheellinen otsikko',
'badtitletext'        => 'Pyytämäsi sivuotsikko oli virheellinen, tyhjä tai väärin linkitetty kieltenvälinen tai wikienvälinen linkki.',
'perfdisabled'        => 'Pahoittelut! Tämä ominaisuus ei toistaiseksi ole käytetössä, sillä se hidastaa tietokantaa niin paljon, että kukaan ei voi käyttää wikiä. Toiminto ohjelmoidaan tehokkaammaksi lähiaikoina. (Sinäkin voit tehdä sen! Tämä on vapaa ohjelmisto.)',
'perfdisabledsub'     => 'Tässä on tallennettu kopio $1', # obsolete? ei ole
'perfcached'          => 'Seuraava data on tuotu välimuistista, eikä se ole välttämättä ajan tasalla.',
'perfcachedts'        => 'Seuraava data on tuotu välimuistista ja se päivitettiin viimeksi $1.',
'querypage-no-updates'=> 'Tämän sivun tietoja ei toistaiseksi päivitetä.',
'wrong_wfQuery_params'=> 'Virheelliset parametrit wfQuery()<br />Funktio: $1<br />Tiedustelu: $2',
'viewsource'          => 'Lähdekoodi',
'viewsourcefor'       => 'sivulle $1',
'protectedpagetext'   => 'Tämä sivu on suojattu muutoksilta.',
'viewsourcetext'      => 'Voit tarkastella ja kopioida tämän sivun lähdekoodia:',
'protectedinterface'  => 'Tämä sivu sisältää ohjelmiston käyttöliittymätekstiä ja on suojattu häiriköinnin estämiseksi.',
'editinginterface'    => '<center>Muokkaat sivua, joka sisältää ohjelmiston käyttöliittymätekstiä.</center>',
'sqlhidden'           => '(SQL-kysely piilotettu)',
'cascadeprotected'    => 'Tämän sivu on suojattu muokkauksilta, koska se on sisällytetty alle oleviin laajennetusti suojattuihin sivuihin:',


# Login and logout pages
#
'logouttitle'         => 'Uloskirjautuminen',
'logouttext'          => 'Olet nyt kirjautunut ulos {{GRAMMAR:elative|{{SITENAME}}}}. Voit jatkaa {{GRAMMAR:genitive|{{SITENAME}}}} käyttöä nimettömänä, tai kirjautua uudelleen sisään.',
'welcomecreation'     => '== Tervetuloa, $1! == Käyttäjätunnuksesi on luotu. Älä unohda virittää [[Special:Preferences|{{GRAMMAR:genitive|{{SITENAME}}}} asetuksiasi]].',
'loginpagetitle'      => 'Sisäänkirjautuminen',
'yourname'            => 'Käyttäjätunnus',
'yourpassword'        => 'Salasana',
'yourpasswordagain'   => 'Salasana uudelleen',
'remembermypassword'  => 'Muista minut',
'yourdomainname'      => 'Verkkonimi',
'externaldberror'     => 'Tapahtui virhe ulkoisen autentikointitietokannan käytössä tai sinulla ei ole lupaa päivittää tunnustasi.',
'loginproblem'        => '<b>Sisäänkirjautuminen ei onnistunut.</b><br />Yritä uudelleen!',
'alreadyloggedin'     => '<strong>Käyttäjä $1, olet jo kirjautunut sisään!</strong><br />\n',
'login'               => 'Kirjaudu sisään',
'loginprompt'         => 'Kirjautumiseen tarvitaan evästeitä.',
'userlogin'           => 'Kirjaudu sisään tai luo tunnus',
'logout'              => 'Kirjaudu ulos',
'userlogout'          => 'Kirjaudu ulos',
'notloggedin'         => 'Et ole kirjautunut',
'nologin'             => 'Jos sinulla ei ole vielä käyttäjätunnusta, voit $1 sellaisen.',
'nologinlink'         => 'luoda',
'createaccount'       => 'Luo uusi käyttäjätunnus',
'gotaccount'          => 'Jos sinulla on jo tunnus, voit $1.',
'gotaccountlink'      => 'kirjautua sisään',
'createaccountmail'   => 'sähköpostitse',
'badretype'           => 'Syöttämäsi salasanat ovat erilaiset.',
'userexists'          => 'Pyytämäsi käyttäjänimi on jo käytössä. Ole hyvä ja valitse toinen käyttäjänimi.',
'youremail'           => 'Sähköpostiosoite:',
'username'            => 'Tunnus:',
'uid'                 => 'Numero:',
'yourrealname'        => 'Oikea nimi:',
'yourlanguage'        => 'Käyttöliittymän kieli:',
'yourvariant'         => 'Kielivariantti',
'yournick'            => 'Nimimerkki allekirjoituksia varten:',
'badsig'              => 'Allekirjoitus on epävalidi.',
'email'               => 'Sähköpostitoiminnot',
'prefs-help-email-enotif' => 'Tätä osoitetta käytetään myös artikkelien muuttumisilmoituksiin, jos ominaisuus on käytössä.',
'prefs-help-realname' => 'Oikea nimi (vapaaehtoinen): Nimesi näytetään käyttäjätunnuksesi sijasta sivun tekijäluettelossa.',
'loginerror'          => 'Sisäänkirjautumisvirhe',
'prefs-help-email'    => 'Sähköpostiosoite (vapaaehtoinen): Muut käyttäjät voivat ottaa sinuun yhteyttä sähköpostilla ilman, että osoitteesi paljastuu.',

'nocookiesnew'        => 'Käyttäjä luotiin, mutta et ole kirjautunut sisään. {{SITENAME}} käyttää evästeitä sisäänkirjautumisen yhteydessä. Selaimesi ei salli evästeistä. Kytke ne päälle, ja sitten kirjaudu sisään juuri luomallasi käyttäjänimellä ja salasanalla.',
'nocookieslogin'      => '{{SITENAME}} käyttää evästeitä sisäänkirjautumisen yhteydessä. Selaimesi ei salli evästeitä. Ota ne käyttöön, ja yritä uudelleen.',
'noname'              => 'Et ole määritellyt kelvollista käyttäjänimeä.',
'loginsuccesstitle'   => 'Sisäänkirjautuminen onnistui',
'loginsuccess'        => 'Olet kirjautunut käyttäjänä $1.',
'nosuchuser'          => 'Käyttäjää ”$1” ei ole olemassa. Tarkista kirjoititko nimen oikein, tai käytä alla olevaa lomaketta uuden käyttäjätunnuksen luomiseksi.',
'nosuchusershort'     => 'Käyttäjää nimeltä ”$1” ei ole. Kirjoititko nimen oikein?',
'nouserspecified'     => 'Käyttäjätunnusta ei ole määritelty.',
'wrongpassword'       => 'Syöttämäsi salasana ei ole oikein. Ole hyvä ja yritä uudelleen.',
'wrongpasswordempty'  => 'Et voi antaa tyhjää salasanaa.',
'mailmypassword'      => 'Lähetä uusi salasana sähköpostitse',
'passwordremindertitle' => 'Salasanamuistutus {{GRAMMAR:elative|{{SITENAME}}}}',

'passwordremindertext'=> 'Joku IP-osoitteesta $1 pyysi {{GRAMMAR:partitive|{{SITENAME}}}} ($4) lähettämään uuden salasanan. Salasana käyttäjälle $2 on nyt $3. Kirjaudu sisään ja vaihda salasana.',
'noemail'             => 'Käyttäjälle \'\'\'$1\'\'\' ei ole määritelty sähköpostiosoitetta.',
'passwordsent'        => 'Uusi salasana on lähetetty käyttäjän <b>$1</b> sähköpostiosoitteeseen.',
'blocked-mailpassword'=> 'Osoitteellesi on asetettu muokkausesto, joka estää käyttämästä salasanamuistutustoimintoa.',
'eauthentsent'        => 'Varmennussähköposti on lähetetty annettuun sähköpostiosoitteeseen. Muita viestejä ei lähetetä, ennen kuin olet toiminut viestin ohjeiden mukaan ja varmistanut, että sähköpostiosoite kuuluu sinulle.',
'throttled-mailpassword' => 'Salasanamuistutus on lähetetty viimeisen $1 tunnin sisällä. Salasanamuistutuksia lähetään enintään $1 tunnin välein.',
'mailerror'           => 'Virhe lähetettäessä sähköpostia: $1',
'acct_creation_throttle_hit' => 'Olet jo luonut $1 tunnusta. Et voi luoda uutta.',
'emailauthenticated'         => 'Sähköpostiosoitteesi varmennettiin $1.',
'emailnotauthenticated'      => 'Sähköpostiosoitettasi ei ole vielä varmennettu. Sähköpostia ei lähetetä liittyen alla oleviin toimintoihin.',
'noemailprefs'        => 'Sähköpostiosoitetta ei ole määritelty.',
'emailconfirmlink'    => 'Varmenna sähköpostiosoite',
'invalidemailaddress' => 'Sähköpostiosoitetta ei voida hyväksyä, koska se ei ole oikeassa muodossa. Ole hyvä ja anna oikea sähköpostiosoite tai jätä kenttä tyhjäksi.',
'accountcreated'      => 'Käyttäjätunnus luotiin',
'accountcreatedtext'  => 'Käyttäjän $1 käyttäjätunnus luotiin.',

# Password reset dialog
'resetpass'           => 'Salasanan alustus',
'resetpass_announce'  => 'Kirjauduit sisään sähköpostitse lähetetyllä väliaikaissalasanalla. Päätä sisäänkirjautuminen asettamalla uusi salasana.',
'resetpass_text'      => "<!-- Lisää tekstiä tähän -->",
'resetpass_header'    => 'Uuden salasanan asettaminen',
'resetpass_submit'    => 'Aseta salasana ja kirjaudu sisään',
'resetpass_success'   => 'Salasanan vaihto onnistui.',
'resetpass_bad_temporary' => 'Kelvoton väliaikaissalasana. Olet saattanut jo asettaa uuden salasanan tai pyytänyt uutta väliaikaissalasanaa.',
'resetpass_forbidden' => 'Salasanoja ei voi vaihtaa tässä wikissä',
'resetpass_missing'   => 'Ei syötettä.',

# Edit page toolbar
'bold_sample'         => 'Lihavoitu teksti',
'bold_tip'            => 'Lihavointi',
'italic_sample'       => 'Kursivoitu teksti',
'italic_tip'          => 'Kursivointi',
'link_sample'         => 'linkki',
'link_tip'            => 'Sisäinen linkki',
'extlink_sample'      => 'http://www.example.com linkin otsikko',
'extlink_tip'         => 'Ulkoinen linkki (muista http:// edessä)',
'headline_sample'     => 'Otsikkoteksti',
'headline_tip'        => 'Otsikko',
'math_sample'         => 'Lisää kaava tähän',
'math_tip'            => 'Matemaattinen kaava (LaTeX)',
'nowiki_sample'       => 'Lisää muotoilematon teksti tähän',
'nowiki_tip'          => 'Tekstiä, jota wiki ei muotoile',
'image_sample'        => 'Esimerkki.jpg',
'image_tip'           => 'Tallennettu kuva',
'media_sample'        => 'Esimerkki.ogg',
'media_tip'           => 'Mediatiedostolinkki',
'sig_tip'             => 'Allekirjoitus aikamerkinnällä',
'hr_tip'              => 'Vaakasuora viiva',

# Edit pages
#
'summary'             => 'Yhteenveto',
'subject'             => 'Aihe',
'minoredit'           => 'Tämä on pieni muutos',
'watchthis'           => 'Tarkkaile tätä sivua',
'savearticle'         => 'Tallenna sivu',
'preview'             => 'Esikatselu',
'showpreview'         => 'Esikatsele',
'showlivepreview'     => 'Pikaesikatselu',
'showdiff'            => 'Näytä muutokset',
'anoneditwarning'     => 'Et ole kirjautunut sisään. IP-osoitteesi kirjataan tämän sivun muokkaushistoriaan.',
'missingsummary'      => 'Et ole antanut yhteenvetoa. Jos valitset Tallenna uudelleen, niin muokkauksesi tallennetaan ilman yhteenvetoa.',
'missingcommenttext'  => 'Anna yhteenveto alle.',
'missingcommentheader'=> 'Et ole antanut otsikkoa kommentillesi. Valitse <em>Tallenna</em>, jos et halua antaa otsikkoa.',
'summary-preview'     => 'Yhteenvedon esikatselu',
'subject-preview'     => 'Otsikon esikatselu',
'blockedtitle'        => 'Pääsy estetty',
'blockedtext'         => 'Yritit muokata sivua tai luoda uuden sivun. $1 on estänyt pääsysi {{GRAMMAR:illative|{{SITENAME}}}} joko käyttäjänimesi tai IP-osoitteesi perusteella. Annettu syy estolle on: <br />\'\'$2\'\'<br />Jos olet sitä mieltä, että sinut on estetty syyttä, voit keskustella asiasta [[Project:Ylläpitäjät|ylläpitäjän]] kanssa. Huomaa, ettet voi lähettää sähköpostia {{GRAMMAR:genitive|{{SITENAME}}}} kautta, ellet ole asettanut olemassa olevaa sähköpostiosoitetta [[Special:Preferences|asetuksissa]]. Jos IP-osoitteesi on dynaaminen, eli se voi toisinaan vaihtua, olet saattanut saada estetyn osoitteen käyttöösi, ja esto vaikuttaa nyt sinuun. Jos tämä ongelma toistuu jatkuvasti, ota yhteyttä Internet-palveluntarjoajaasi tai {{GRAMMAR:genitive|{{SITENAME}}}} ylläpitäjään. IP-osoitteesi on $3 ja estotunnus on #$5.',
'blockedoriginalsource' => 'Sivun ”$1” lähdekoodi:',
'blockededitsource'   => 'Muokkauksesi sivuun ”$1”:',
'whitelistedittitle'  => 'Sisäänkirjautuminen vaaditaan muokkaamiseen',
'whitelistedittext'   => 'Sinun täytyy $1, jotta voisit muokata sivuja.',
'whitelistreadtitle'  => 'Sisäänkirjautuminen vaaditaan lukemiseen',
'whitelistreadtext'   => 'Sinun täytyy kirjautua [[Special:Userlogin|sisään]] lukeaksesi sivuja.',
'whitelistacctitle'   => 'Sinun ei ole sallittu luoda tunnusta',
'whitelistacctext'    => 'Saadaksesi oikeudet luoda tunnus sinun täytyy kirjautua [[Special:Userlogin|sisään]] ja sinulla tulee olla asiaankuuluvat oikeudet.',
'confirmedittitle'    => 'Sähköpostin varmennus',
'confirmedittext'     => 'Et voi muokata sivuja, ennen kuin olet varmentanut sähköpostiosoitteesi. Voit tehdä varmennuksen [[Special:Preferences|asetussivulla]].',
'loginreqtitle'       => 'Sisäänkirjautuminen vaaditaan',
'loginreqlink'        => 'kirjautua sisään',
'loginreqpagetext'    => 'Sinun täytyy $1, jotta voisit nähdä muut sivut.',

'accmailtitle'        => 'Salasana lähetetty.',
'accmailtext'         => 'käyttäjän \'\'\'$1\'\'\' salasana on lähetetty osoitteeseen \'\'\'$2\'\'\'.',
'newarticle'          => '(uusi)',
'newarticletext'      => 'Linkki toi sivulle, jota ei vielä ole. Voit luoda sivun kirjoittamalla alla olevaan tilaan. Jos et halua luoda sivua, käytä selaimen paluutoimintoa.',
'anontalkpagetext'    => '----\'\'Tämä on nimettömän käyttäjän keskustelusivu. Hän ei ole joko luonut itselleen käyttäjätunnusta tai ei käytä sitä. Siksi hänet tunnistetaan nyt numeerisella IP-osoitteella. Kyseinen IP-osoite voi olla useamman henkilön käytössä. Jos olet nimetön käyttäjä, ja sinusta tuntuu, että aiheettomia kommentteja on ohjattu sinulle, [[Special:Userlogin|luo itsellesi käyttäjätunnus tai kirjaudu sisään]] välttääksesi jatkossa sekaannukset muiden nimettömien käyttäjien kanssa.\'\'',
'noarticletext'       => '<big>\'\'\'{{GRAMMAR:inessive|{{SITENAME}}}} ei ole tämän nimistä sivua.\'\'\'</big>
* Voit kirjoittaa uuden sivun \'\'\'<span class="plainlinks">[{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} {{PAGENAME}}]</span>.\'\'\'
* Jos olet luonut sivun tällä nimellä, se on saatettu poistaa — katso [[Special:Log/delete|poistoloki]].',
'clearyourcache'        => '\'\'\'Huomautus:\'\'\' Selaimen välimuisti pitää tyhjentää asetusten tallentamisen jälkeen, jotta muutokset tulisivat voimaan:
*\'\'\'Mozilla, Konqueror ja Safari:\'\'\' napsauta \'\'Shift\'\'-näppäin pohjassa päivitä tai paina \'\'Ctrl-Shift-R\'\' (\'\'Cmd-Shift-R\'\' Applella)
*\'\'\'IE:\'\'\' napsauta \'\'Ctrl\'\'-näppäin pohjassa päivitä tai paina \'\'Ctrl-F5\'\'
*\'\'\'Konqueror\'\'\': napsauta päivitä tai paina \'\'F5\'\'
*\'\'\'Opera:\'\'\' saatat joutua tyhjentään välimuistin kokonaan (\'\'Tools→Preferences\'\').',
'usercssjsyoucanpreview' => 'Voit testata uutta CSS:ää tai JavaScriptiä ennen tallennusta esikatselulla.',
'usercsspreview'      => '\'\'\'Tämä on CSS:n esikatselu.\'\'\'',
'userjspreview'       => '\'\'\'Tämä on JavaScriptin esikatselu.\'\'\'',
'userinvalidcssjstitle' => 'Tyyliä nimeltä ”$1” ei ole olemassa. Käyttäjän määrittelemät .css- ja .js-sivut alkavat pienellä alkukirjaimella.',
'updated'             => '(Päivitetty)',
'note'                => 'Huomautus:', // TODO: NO WIKI MARKUP
'previewnote'         => '<strong>Tämä on vasta sivun esikatselu. Sivua ei ole vielä tallennettu!</strong>',
'session_fail_preview'=> '<strong>Muokkaustasi ei voitu tallentaa, koska istuntosi tiedot ovat kadonneet.</strong> Yritä uudelleen. Jos ongelma ei katoa, yritä kirjautua ulos ja takaisin sisään.',
'previewconflict'     => 'Tämä esikatselu näyttää miltä muokkausalueella oleva teksti näyttää tallennettuna.',
'session_fail_preview_html' => '<strong>Muokkaustasi ei voitu tallentaa, koska istuntosi tiedot ovat kadonneet.</strong>

Esikatselu on piilotettu varokeinona JavaScript-hyökkäyksiä vastaan – tässä wikissä on HTML-tila päällä.

Yritä uudelleen. Jos ongelma ei katoa, yritä kirjautua ulos ja takaisin sisään.',
'importing'           => 'Tuodaan sivua $1',
'editing'             => 'Muokataan sivua $1',
'editinguser'             => 'Muokataan sivua $1',
'editingsection'      => 'Muokataan osiota sivusta $1',
'editingcomment'      => 'Muokataan kommenttia sivulla $1',
'editconflict'        => 'Päällekkäinen muokkaus: $1',
'explainconflict'     => 'Joku muu on muuttanut tätä sivua sen jälkeen, kun aloit muokata sitä. Ylempi tekstialue sisältää tämänhetkisen tekstin. Tekemäsi muutokset näkyvät alemmassa ikkunassa. Sinun täytyy yhdistää muutoksesi olemassa olevaan tekstiin. \'\'\'Vain\'\'\' ylemmässä alueessa oleva teksti tallentuu, kun tallennat sivun.',
'yourtext'            => 'Oma tekstisi',
'storedversion'       => 'Tallennettu versio',
'nonunicodebrowser'   => '\'\'\'Varoitus: Selaimesi ei ole Unicode-yhteensopiva. Ole hyvä ja vaihda selainta, ennen kuin muokkaat sivua.\'\'\'',
'editingold'          => '<center><strong>Varoitus</strong>: Olet muokkaamassa vanhaa versiota tämän sivun tekstistä. Jos tallennat sen, kaikki tämän version jälkeen tehdyt muutokset katoavat.</center>',
'yourdiff'            => 'Eroavaisuudet',
'copyrightwarning'    => '<strong>Muutoksesi astuvat voimaan välittömästi.</strong> Jos haluat harjoitella muokkaamista, ole hyvä ja käytä [[Project:Hiekkalaatikko|hiekkalaatikkoa]].<br /><br />Kaikki {{GRAMMAR:illative|{{SITENAME}}}} tehtävät tuotokset katsotaan julkaistuksi $2 -lisenssin mukaisesti ($1). Jos et halua, että kirjoitustasi muokataan armottomasti ja uudelleenkäytetään vapaasti, älä tallenna kirjoitustasi. Tallentamalla muutoksesi lupaat, että kirjoitit tekstisi itse, tai kopioit sen jostain vapaasta lähteestä. <strong>ÄLÄ KÄYTÄ TEKIJÄNOIKEUDEN ALAISTA MATERIAALIA ILMAN LUPAA!</strong>',
'copyrightwarning2'   => '>Huomaa, että kuka tahansa voi muokata, muuttaa ja poistaa kaikkia sivustolle tekemiäsi lisäyksiä ja muutoksia. Muokkaamalla sivustoa luovutat sivuston käyttäjille tämän oikeuden ja takaat, että lisäämäsi aineisto on joko itse kirjoittamaasi tai peräisin jostain vapaasta lähteestä. Lisätietoja sivulla $1. <strong>TEKIJÄNOIKEUDEN ALAISEN MATERIAALIN KÄYTTÄMINEN ILMAN LUPAA ON EHDOTTOMASTI KIELLETTYÄ!</strong>',
'longpagewarning'     => '<center>Tämän sivun tekstiosuus on $1 binäärikilotavua pitkä. Harkitse, voisiko sivun jakaa pienempiin osiin.</center>',
'longpageerror'       => '<strong>Sivun koko on $1 binäärikilotavua. Sivua ei voida tallentaa, koska enimmäiskoko on $2 binäärikilotavua.</strong>',
'readonlywarning'     => '<strong>Varoitus</strong>: Tietokanta on lukittu huoltoa varten, joten voi olla ettet pysty tallentamaan muokkauksiasi juuri nyt. Saattaa olla paras leikata ja liimata tekstisi omaan tekstitiedostoosi ja tallentaa se tänne myöhemmin.',
'protectedpagewarning'=> '<center><small>Tämä sivu on lukittu. Vain ylläpitäjät voivat muokata sitä.</small></center>',
'semiprotectedpagewarning' => 'Vain rekisteröityneet käyttäjät voivat muokata tätä sivua.',
'cascadeprotectedwarning' => "<strong>Vain ylläpitäjät voivat muokata tätä sivua, koska se on sisällytetty alla oleviin laajennetusti suojattuihin sivuihin</strong>:",
'templatesused'       => 'Tällä sivulla käytetyt mallineet:',
'templatesusedpreview'=> 'Esikatselussa mukana olevat mallineet:',
'templatesusedsection'=> 'Tässä osiossa mukana olevat mallineet:',
'template-protected'  => '(suojattu)',
'template-semiprotected' => '(suojattu anonyymeiltä ja uusilta käyttäjiltä)',
'edittools'           => '<!-- Tässä oleva teksi näytetään muokkauskentän alla. -->',
'nocreatetitle'       => 'Sivujen luominen on rajoitettu',
'nocreatetext'        => 'Et voi luoda uusia sivuja. Voit muokata olemassa olevia sivuja tai luoda [[Special:Userlogin|käyttäjätunnukssen]].',

# "Undo" feature
'undo-success'        => 'Kumoaminen onnistui. Valitse <em>tallenna</em> toteuttaaksesi muutokset.',
'undo-failure'        => 'Muokkausta ei voitu kumota välissä olevien ristiriistaisten muutosten vuoksi. Kumoa muutokset käsin.',
'undo-summary'        => 'Kumottu muokkaus #$1, jonka teki [[Special:Contributions/$2|$2]] ([[User_talk:$2|keskustelu]])',

'cantcreateaccounttitle' => 'Tunnuksen luominen epäonnistui',
'cantcreateaccounttext'  => 'Tunnuksien luominen tästä IP-osoitteesta ($1) on estetty. Syynä tähän on luultavasti jatkuva häiriköinti yhteiskäyttökoneelta.',

# History pages
# 
'revhistory'          => 'Muutoshistoria',
'viewpagelogs'        => 'Näytä tämän sivun lokit',
'nohistory'           => 'Tällä sivulla ei ole muutoshistoriaa.',
'revnotfound'         => 'Versiota ei löydy',
'revnotfoundtext'     => 'Pyytämääsi versiota ei löydy. Tarkista URL-osoite, jolla hait tätä sivua.',
'loadhist'            => 'Ladataan sivuhistoriaa',
'currentrev'          => 'Nykyinen versio',
'revisionasof'        => 'Versio $1',
'revision-info'       => 'Versio hetkellä $1 – tehnyt $2',
'previousrevision'    => '← Vanhempi versio',
'nextrevision'        => 'Uudempi versio →',
'currentrevisionlink' => 'Nykyinen versio',
'cur'                 => 'nyk.',
'next'                => 'seur.',
'last'                => 'edell.',
'orig'                => 'alkup.',
'histlegend'          => 'Merkinnät: (nyk.) = eroavaisuudet nykyiseen versioon, (edell.) = eroavaisuudet edelliseen versioon, <span class="minor">p</span> = pieni muutos',
'deletedrev'          => '[poistettu]',
'histfirst'           => 'Ensimmäiset',
'histlast'            => 'Viimeisimmät',

'rev-deleted-comment' => '(kommentti poistettu)',
'rev-deleted-user'    => '(käyttäjänimi poistettu)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">Tämä versio on poistettu julkisesta arkistosta. [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} Poistolokissa] saattaa olla lisätietoja.</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">Tämä versio on poistettu julkisesta arkistosta.</div>',
'rev-delundel'        => 'näytä tai piilota',

# Revision deletion
#
'revisiondelete'      => 'Poista tai palauta versioita',
'revdelete-nooldid-title' => 'Ei kohdeversiota',
'revdelete-nooldid-text'  => 'Et ole valinnut kohdeversiota tai -versioita.',
'revdelete-selected'  => 'Valittu versio [[:$1]]:',
'revdelete-text'      => "Poistetut versiot näkyvät sivun historiassa, mutta niiden sisältö ei ole julkisesti saatavilla.\n\nMuut ylläpitäjät voivat lukea piilotetun sisällön ja palauttaa sen.",
'revdelete-legend'    => 'Version rajoitukset:',
'revdelete-hide-text' => 'Piilota version sisältö',
'revdelete-hide-comment'    => 'Piilota yhteenveto',
'revdelete-hide-user'       => 'Piilota tekijän tunnus tai IP-osoite',
'revdelete-hide-restricted' => 'Apply these restrictions to sysops as well as others',
'revdelete-log'       => 'Lokimerkintä:',
'revdelete-submit'    => 'Toteuta',
'revdelete-logentry'  => 'muutti sivun [[$1]] version näkyvyyttä',


# Diffs
#
'difference'           => 'Versioiden väliset erot',
'loadingrev'           => 'Ladataan versiota vertailua varten',
'lineno'               => 'Rivi $1:',
'editcurrent'          => 'Muokkaa tämän sivun uusinta versiota',
'selectnewerversionfordiff' => 'Valitse uudempi versio vertailuun',
'selectolderversionfordiff' => 'Valitse vanhempi versio vertailuun',
'compareselectedversions'   => 'Vertaile valittuja versioita',
'editundo'             => 'kumoa',
'diff-multi'           => '(Versioiden välissä {{PLURAL:$1|yksi muu muokkaus|$1 muuta muokkausta}}.)',

# Search results
#
'searchresults'       => 'Hakutulokset',
'searchresulttext'    => 'Saadaksesi lisätietoa hakutoiminnoista tällä sivustolla lue [[Project:Hakutoiminnot|sivuston hakuohje]].',
'searchsubtitle'      => 'Haku termeillä [[:$1]]',
'searchsubtitleinvalid'=> 'Haku termeillä $1',
'badquery'            => 'Kelvoton hakumerkkijono',
'badquerytext'        => 'Tekemäsi kysely ei ole kelvollinen. Tämä johtuu todennäköisesti siitä, että et ole määritellyt hakumerkkijonoa.',
'matchtotals'         => 'Haulla \'\'\'$1\'\'\' löytyi $2 osumaa sivujen otsikoista ja $3 osumaa sivujen sisällöistä.',
'noexactmatch'           => '<big>Otsikolla $1 ei ole sivua.</big>

:Voit [[$1|luoda aiheesta uuden sivun]].
:<small>Etsi ensin vastaavaa sivua, joka voi olla kirjoitusasultaan hieman erilainen</small>',
'titlematches'        => 'Osumat sivujen otsikoissa',
'notitlematches'      => 'Hakusanaa ei löytynyt minkään sivun otsikosta',
'textmatches'         => 'Osumat sivujen teksteissä',
'notextmatches'       => 'Hakusanaa ei löytynyt sivujen teksteistä',
'prevn'               => '← $1 edellistä',
'nextn'               => '$1 seuraavaa →',
'viewprevnext'        => "Näytä [$3] kerralla.\n\n$1 | $2",
'showingresults'      => '<b>$1</b> tulosta tuloksesta <b>$2</b> alkaen.',
'showingresultsnum'   => 'Alla on <b>$3</b> hakutulosta alkaen <b>$2.</b> tuloksesta.',
'nonefound'           => '\'\'\'Huomautus\'\'\': Epäonnistuneet haut johtuvat usein hyvin yleisten sanojen, kuten \'\'on\'\' ja \'\'ei\'\', etsimisestä tai useamman kuin yhden hakutermin määrittelemisestä. Vain sivut, joilla on kaikki hakutermin sanat, näkyvät tuloksissa.',
'powersearch'         => 'Etsi',
'powersearchtext'     => 'Hae nimiavaruuksista:<br />$1<br />$2 Luettele uudelleenohjaukset<br />Etsi: $3 $9',
'searchdisabled'      => '<p style="margin: 1.5em 2em 1em">Tekstihaku on poistettu toistaiseksi käytöstä suuren kuorman vuoksi. Voit käyttää alla olevaa Googlen hakukenttää sivujen etsimiseen, kunnes haku tulee taas käyttöön.<small>Huomaa, että ulkopuoliset kopiot {{GRAMMAR:genitive|{{SITENAME}}}} sisällöstä eivät välttämättä ole ajan tasalla.</small></p>',

'blanknamespace'      => '(sivut)',

# Preferences page
#
'preferences'         => 'Asetukset',
'mypreferences'       => 'Asetukset',
'prefsnologin'        => 'Et ole kirjautunut sisään.',
'prefsnologintext'    => 'Sinun täytyy [[Special:Userlogin|kirjautua sisään]], jotta voisit muuttaa asetuksiasi.',
'prefsreset'          => 'Asetukset on palautettu tallennetuista asetuksistasi.',
'qbsettings'          => 'Pikavalikko',
'changepassword'      => 'Vaihda salasanaa',
'skin'                => 'Ulkonäkö',
'math'                => 'Matematiikka',
'dateformat'          => 'Päiväyksen muoto',
'datedefault'         => 'Ei valintaa',
'datetime'            => 'Aika ja päiväys',
'math_failure'        => 'Jäsentäminen epäonnistui',
'math_unknown_error'  => 'Tuntematon virhe',
'math_unknown_function' => 'Tuntematon funktio',
'math_lexing_error'   => 'Tulkintavirhe',
'math_syntax_error'   => 'Jäsennysvirhe',
'math_image_error'    => 'PNG-muunnos epäonnistui; tarkista, että latex, dvips, gs ja convert on asennettu oikein.',
'math_bad_tmpdir'     => 'Matematiikan kirjoittaminen väliaikaishakemistoon tai tiedostonluonti ei onnistu',
'math_bad_output'     => 'Matematiikan tulostehakemistoon kirjoittaminen tai tuedostonluonti ei onnistu',
'math_notexvc'        => 'Texvc-sovellus puuttuu, lue math/READMEstä asennustietoja',
'prefs-personal'      => 'Käyttäjätiedot',
'prefs-rc'            => 'Tuoreet muutokset ja tyngät',
'prefs-watchlist'       => 'Tarkkailulista',
'prefs-watchlist-days'  => 'Tarkkailulistan ajanjakso:',
'prefs-watchlist-edits' => 'Tarkkailulistalla näytettävien muutosten määrä:',
'prefs-misc'          => 'Muut asetukset',
'saveprefs'           => 'Tallenna asetukset',
'resetprefs'          => 'Palauta tallennetut asetukset',
'oldpassword'         => 'Vanha salasana:',
'newpassword'         => 'Uusi salasana:',
'retypenew'           => 'Uusi salasana uudelleen:',
'textboxsize'         => 'Muokkaaminen',
'rows'                => 'Rivit:',
'columns'             => 'Sarakkeet:',
'searchresultshead'   => 'Haku',
'resultsperpage'      => 'Tuloksia sivua kohti:',
'contextlines'        => 'Rivien määrä tulosta kohti:',
'contextchars'        => 'Sisällön merkkien määrä riviä kohden:',
'stubthreshold'       => 'Tynkäsivun osoituskynnys:',
'recentchangescount'  => 'Sivujen määrä tuoreissa muutoksissa:',
'savedprefs'          => 'Asetuksesi tallennettiin onnistuneesti.',
'timezonelegend'      => 'Aikavyöhyke',
'timezonetext'        => 'Paikallisen ajan ja palvelimen ajan (UTC) välinen aikaero tunteina.',
'localtime'           => 'Paikallinen aika',
'timezoneoffset'      => 'Aikaero¹:',
'servertime'          => 'Palvelimen aika',
'guesstimezone'       => 'Utele selaimelta',
'allowemail'          => 'Salli sähköpostin lähetys osoitteeseen',
'defaultns'           => 'Etsi oletusarvoisesti näistä nimiavaruuksista:',
'default'             => 'oletus',
'files'               => 'Tiedostot',

# User rights
'userrights-lookup-user'   => 'Käyttöoikeuksien hallinta',
'userrights-user-editname' => 'Käyttäjätunnus:',
'editusergroup'            => 'Muokkaa käyttäjän ryhmiä',

'userrights-editusergroup'   => 'Käyttäjän ryhmät',
'saveusergroups'             => 'Tallenna',
'userrights-groupsmember'    => 'Jäsenenä ryhmissä:',
'userrights-groupsavailable' => 'Saatavilla olevat ryhmät:',
'userrights-groupshelp'      => 'Valitse ryhmät, jotka haluat poistaa tai lisätä. Valitsemattomia ryhmiä ei muuteta. Voit poistaa valinnan pitämällä Ctrl-näppäintä pohjassa napsautuksen aikana.',

# Groups
'group'                   => 'Ryhmä:',
'group-bot'               => 'botit',
'group-sysop'             => 'ylläpitäjät',
'group-bureaucrat'        => 'byrokraatit',
'group-all'               => '(kaikki)',
'group-bot-member'        => 'botti',
'group-sysop-member'      => 'ylläpitäjä',
'group-bureaucrat-member' => 'byrokraatti',
'grouppage-bot'           => '{{ns:project}}:Botit',
'grouppage-sysop'         => '{{ns:project}}:Ylläpitäjät',
'grouppage-bureaucrat'    => '{{ns:project}}:Byrokraatit',


# Recent changes
#
'changes'             => 'muutosta',
'recentchanges'       => 'Tuoreet muutokset',
'recentchangestext'   => 'Tällä sivulla voi seurata tuoreita {{GRAMMAR:illative|{{SITENAME}}}} tehtyjä muutoksia.',
'recentchanges-feed-description' => 'Tällä sivulla voi seurata tuoreita {{GRAMMAR:illative|{{SITENAME}}}} tehtyjä muutoksia.',
'rcnote'              => 'Alla on <b>$1</b> tuoreinta muutosta viimeisten <b>$2</b> päivän ajalta $3.',
'rcnotefrom'          => 'Alla on muutokset <b>$2</b> lähtien. Enintään <b>$1</b> merkintää näytetään.',
'rclistfrom'          => 'Näytä uudet muutokset $1 alkaen',
'rcshowhideminor'     => '$1 pienet muutokset',
'rcshowhidebots'      => '$1 botit',
'rcshowhideliu'       => '$1 kirjautuneet käyttäjät',
'rcshowhideanons'     => '$1 anonyymit käyttäjät',
'rcshowhidepatr'      => '$1 tarkastetut muutokset',
'rcshowhidemine'      => '$1 omat muutokset',
'rclinks'             => 'Näytä $1 tuoretta muutosta viimeisten $2 päivän ajalta.<br />$3',
'diff'                => 'ero',
'hist'                => 'historia',
'hide'                => 'piilota',
'show'                => 'näytä',
'minoreditletter'     => 'p',
'newpageletter'       => 'U',
'boteditletter'       => 'b',
'sectionlink'         => '→',
'number_of_watching_users_pageview' => '[$1 tarkkailevaa käyttäjää]', // TODO sigplu
'rc_categories'       => 'Vain luokista (erotin on ”|”)',
'rc_categories_any'   => 'Mikä tahansa',


# Upload
#
'upload'              => 'Lisää tiedosto',
'uploadbtn'           => 'Lähetä tiedosto',
'reupload'            => 'Lähetä uudelleen',
'reuploaddesc'        => 'Palaa lähetyslomakkeelle.',
'uploadnologin'       => 'Et ole kirjautunut sisään',
'uploadnologintext'   => 'Sinun pitää olla [[Special:Userlogin|kirjautuneena sisään]], jotta voisit lisätä tiedostoja.',
'upload_directory_read_only' => 'Palvelimella ei ole kirjoitusoikeuksia tallennushakemistoon ”<tt>$1</tt>”.',
'uploaderror'         => 'Tallennusvirhe',
'uploadtext'          => 'Ennen kuin lähetät tiedostoja {{GRAMMAR:illative|{{SITENAME}}}}, lue seuraava:
*\'\'Kirjoita tiedoston tietoihin tarkka tieto tiedoston lähteestä.\'\'
*\'\'Kerro tiedoston tekijänoikeuksien tila.\'\'
*\'\'Käytä järkevää tiedostonimeä.\'\' Nimeä tiedostosi mieluummin tyyliin ”Eiffel-torni Pariisissa, yökuva.jpg” kuin ”etpan1024c.jpg”. Näin vältät mahdollisesti jo olemassa olevan tiedoston korvaamisen omallasi.
*Laita johonkin aiheeseen liittyvään sivuun linkki kyseiseen tiedostoon, tai kirjoita kuvaussivulle kuvaus tiedoston sisällöstä.
*Jos haluat nähdä tai etsiä aiemmin lisättyjä tiedostoja, katso [[Special:Imagelist|tiedostoluettelo]]. Tallennukset ja poistot kirjataan [[Special:Log/upload|tiedostolokiin]].

Suositellut kuvaformaatit ovat JPEG valokuville, PNG piirroksille ja kuvakkeille ja Ogg Vorbis äänille. Voit liittää kuvan sivulle käyttämällä seuraavan muotoista merkintää \'\'\'<nowiki>[[Kuva:tiedosto.jpg]]</nowiki>\'\'\' tai \'\'\'<nowiki>[[Kuva:tiedosto.png|kuvausteksti]]</nowiki>\'\'\' tai \'\'\'<nowiki>[[media:tiedosto.ogg]]</nowiki>\'\'\' äänille.

Huomaa, että {{GRAMMAR:inessive|{{SITENAME}}}} muut voivat muokata tai poistaa lähettämäsi tiedoston, jos he katsovat, että se ei palvele projektin tarpeita. Tallentamismahdollisuutesi voidaan estää, jos käytät järjestelmää väärin.',
'uploadlog'           => 'Tiedostoloki',
'uploadlogpage'       => 'Tiedostoloki',
'uploadlogpagetext'   => 'Alla on luettelo uusimmista tiedostonlisäyksistä. Kaikki ajat näytetään palvelimen aikavyöhykkeessä (UTC).',
'filename'            => 'Tiedoston nimi',
'filedesc'            => 'Yhteenveto',
'fileuploadsummary'   => 'Yhteenveto:',
'filestatus'          => 'Tiedoston tekijänoikeudet',
'filesource'          => 'Lähde',
'copyrightpage'       => '{{ns:project}}:Tekijänoikeudet',
'copyrightpagename'   => '{{SITENAME}} ja tekijänoikeudet',
'uploadedfiles'       => 'Lisätyt tiedostot',
'ignorewarning'       => 'Tallenna tiedosto varoituksesta huolimatta.',
'ignorewarnings'      => 'Ohita kaikki varoitukset',
'minlength'           => 'Tiedoston nimessä pitää olla vähintään kolme merkkiä.',
'illegalfilename'     => 'Tiedoston nimessä \'\'\'$1\'\'\' on merkkejä, joita ei sallita sivujen nimissä. Vaihda tiedoston nimeä, ja yritä lähettämistä uudelleen.',
'badfilename'         => 'Tiedoston nimi vaihdettiin: $1.',
'badfiletype'         => '”<tt>.$1</tt>” ei ole suositeltava tiedostomuoto.',
'large-file'          => 'Tiedostojen enimmäiskoko on $1. Lähettämäsi tiedoston koko on $2.',
'largefileserver'     => 'Tämä tiedosto on suurempi kuin mitä palvelin sallii.',
'emptyfile'           => 'Tiedosto, jota yritit lähettää, näyttää olevan tyhjä. Tarkista, että kirjoitit polun ja nimen oikein ja että se ei ole liian suuri kohdepalvelimelle.',
'fileexists'          => 'Samanniminen tiedosto on jo olemassa. Katso tiedoston sivu $1, jos et ole varma, haluatko muuttaa sitä.',
'fileexists-forbidden'=> 'Samanniminen tiedosto on jo olemassa. Tallenna tiedosto jollakin toisella nimellä. Nykyinen tiedosto: [[{ns:image}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Samanniminen tiedosto on jo olemassa jaetussa mediavarastossa. Tallenna tiedosto jollakin toisella nimellä. Nykyinen tiedosto: [[Image:$1|thumb|center|$1]]',
'successfulupload'    => 'Tallennus onnistui',
'fileuploaded'        => 'Tiedosto \'\'\'$1\'\'\' on tallennettu onnistuneesti. Seuraa linkkiä ($2) kuvaussivulle, ja täytä tiedostoon liityvät tiedot, kuten mistä se on peräisin, milloin se on luotu, kuka sen loi ja mahdollisesti muita tietämiäsi tietoja. Jos tiedosto on kuva, voit lisätä sen sivulle näin: \'\'\'<nowiki>[[Kuva:$1|thumb|Kuvaus]]</nowiki>\'\'\'',
'uploadwarning'       => 'Tallennusvaroitus',
'savefile'            => 'Tallenna',
'uploadedimage'       => 'lisäsi tiedoston [[$1]]',
'uploaddisabled'      => '{{GRAMMAR:genitive|{{SITENAME}}}} ei voi lisätä tiedostoja.',
'uploaddisabledtext'  => 'Tiedostojen lisäys on poistettu käytöstä.',
'uploadscripted'      => 'Tämä tiedosto sisältää HTML-koodia tai skriptejä, jotka selain saattaa virheellisesti suorittaa.',
'uploadcorrupt'       => 'Tiedosto on vioittunut tai sillä on väärä tiedostopääte. Tarkista tiedosto ja lähetä se uudelleen.',
'uploadvirus'         => 'Tiedosto sisältää viruksen. Tarkemmat tiedot: $1',
'sourcefilename'      => 'Lähdenimi',
'destfilename'        => 'Kohdenimi',
'watchthisupload'     => 'Tarkkaile tätä sivua',
'filewasdeleted'      => 'Tämän niminen tiedosto on lisätty ja poistettu aikaisemmin. Tarkista $1 ennen jatkamista.',

'upload-proto-error'      => 'Virheellinen protokolla',
'upload-proto-error-text' => 'Etälähetys on mahdollista vain osoitteista, jotka alkavat merkkijonolla <code>http://</code> tai <code>ftp://</code>.',
'upload-file-error'       => 'Vakava virhe',
'upload-file-error-text'  => 'Väliaikaistiedoston luominen epäonnistui. Ota yhteyttä sivuston ylläpitäjään.',
'upload-misc-error'       => 'Virhe',
'upload-misc-error-text'  => 'Tiedoston etälähetys ei onnistunut. Varmista, että antamasi osoite on oikein ja toimiva. Jos virhe ei katoa, ota yhteyttä sivuston ylläpitäjään.',
'upload-curl-error6'      => 'Toimimaton osoite',
'upload-curl-error6-text' => 'Antamaasi osoitteeseen ei saatu yhteyttä. Varmista, että osoite on oikein ja että sivusto on saavutettavissa.',
'upload-curl-error28'     => 'Etälähetyksen aikakatkaisu',
'upload-curl-error28-text'=> 'Antamastasi osoitteesta ei saatu vastausta määräajassa. Varmista, että sivusto on saavutettavissa ja yritä uudelleen.',

'license'             => 'Lisenssi',
'nolicense'           => 'Ei lisenssiä',
'upload_source_url'   => ' (julkinen verkko-osoite)',
'upload_source_file'  => ' (tiedosto tietokoneella)',


# Image list
#
'imagelist'           => 'Tiedostoluettelo',
'imagelisttext'       => 'Alla on <strong>$1</strong> tiedostoa lajiteltuna <strong>$2</strong>.',
'imagelistforuser'    => 'Käyttäjän ”$1” lisäämät kuvat.',
'getimagelist'        => 'noudetaan tiedostoluetteloa',
'ilsubmit'            => 'Hae',
'showlast'            => 'Näytä viimeiset $1 tiedostoa lajiteltuna $2.',
'byname'              => 'nimen mukaan',
'bydate'              => 'päiväyksen mukaan',
'bysize'              => 'koon mukaan',
'imgdelete'           => 'poista',
'imgdesc'             => 'kuvaus',
'imgfile'             => 'tiedosto',
'imglegend'           => 'Merkinnät: (kuvaus) = näytä tai muokkaa tiedoston kuvausta.',
'imghistory'          => 'Historia',
'revertimg'           => 'palauta',
'deleteimg'           => 'poista',
'deleteimgcompletely' => 'poista',
'imghistlegend'       => 'Merkinnät: (nyk.) = nykyinen versio, (poista) = poista tämä vanha versio, (palauta) = palauta tiedosto tähän vanhaan versioon.<br />Napsauta päiväystä nähdäksesi silloin tallennettu tiedosto.',
'imagelinks'          => 'Viittaukset sivuilta',
'linkstoimage'        => 'Seuraavilta sivuilta on linkki tähän tiedostoon:',
'nolinkstoimage'      => 'Tähän tiedostoon ei ole linkkejä miltään sivulta.',
'sharedupload'        => 'Tämä tiedosto on jaettu ja muut projektit saattavat käyttää sitä.',
'shareduploadwiki'    => 'Katso $1 lisätietoja.',
'shareduploadwiki-linktext' => 'kuvaussivulta',
'noimage'             => 'Tämän nimistä tiedostoa ei ole olemassa. Voit $1 {{GRAMMAR:illative|{{SITENAME}}}}',
'noimage-linktext'    => 'lisätä tiedoston',
'uploadnewversion-linktext' => 'Lisää uusi versio tästä tiedostosta',
'imagelist_date'      => 'Päiväys',
'imagelist_name'      => 'Nimi',
'imagelist_user'      => 'Lähettäjä',
'imagelist_size'      => 'Koko (tavuja)',
'imagelist_description' => 'Kuvaus',
'imagelist_search_for'=> 'Nimihaku:',

# Mime search
#
'mimesearch'          => 'MIME-haku',
'mimetype'            => 'MIME-tyyppi:',
'download'            => 'lataa',

# Unused templates
'unusedtemplates'     => 'Käyttämättömät mallineet',
'unusedtemplatestext' => 'Tässä on lista kaikista mallineista, joita ei ole liitetty toiselle sivulle. Muista tarkistaa onko malline siitä huolimatta käytössä.',
'unusedtemplateswlh'  => 'muut linkit',


# Statistics
#
'statistics'          => 'Tilastot',
'sitestats'           => 'Sivuston tilastot',
'userstats'           => 'Käyttäjätilastot',
'sitestatstext'       => 'Tietokannassa on yhteensä $1 sivua. Tähän on laskettu mukaan keskustelusivut, {{GRAMMAR:genitive|{{SITENAME}}}} projektisivut, hyvin lyhyet sivut, uudelleenohjaukset sekä muita sivuja, joita ei voi pitää kunnollisina sivuina. Nämä poislukien tietokannassa on \'\'\'$2\'\'\' sivua.

{{GRAMMAR:illative|{{SITENAME}}}} on tallennettu \'\'\'$8\'\'\' tiedostoa.

Sivuja on katsottu yhteensä \'\'\'$3\'\'\' kertaa ja muokattu \'\'\'$4\'\'\' kertaa. Keskimäärin yhtä sivua on muokattu \'\'\'$5\'\'\' kertaa, ja muokkausta kohden sivua on katsottu keskimäärin \'\'\'$6\'\'\' kertaa.

Ohjelmiston suorittamia ylläpitotöitä on jonossa \'\'\'$7\'\'\' kappaletta.',
'userstatstext'       => 'Rekisteröityneitä käyttäjiä on \'\'\'$1\'\'\'. Näistä \'\'\'$2\'\'\' ($4%) on ylläpitäjiä ($5).',
'statistics-mostpopular' => 'Katsotuimmat sivut',

'disambiguations'     => 'Linkit täsmennyssivuihin',
'disambiguationspage' => 'Project:Linkkejä_täsmennyssivuihin',
'disambiguations-text' => 'Seuraavat artikkelit linkittävät <i>täsmennyssivuun</i>. Täsmennyssivun sijaan niiden pitäisi linkittää asianomaiseen aiheeseen.<br />Sivua kohdellaan täsmennyssivuna jos se käyttää mallinetta, johon on linkki sivulta [[MediaWiki:disambiguationspage]].',

'doubleredirects'     => 'Kaksinkertaiset uudelleenohjaukset',
'doubleredirectstext' => '<b>Huomio:</b> Tässä listassa saattaa olla virheitä. Yleensä kyseessä on sivu, jossa ensimmäisen #REDIRECTin jälkeen on tekstiä.<br />\nJokaisella rivillä on linkit ensimmäiseen ja toiseen uudelleenohjaukseen sekä toisen uudelleenohjauksen kohteen ensimmäiseen riviin, eli yleensä ”oikeaan” kohteeseen, johon ensimmäisen uudelleenohjauksen pitäisi osoittaa.',

'brokenredirects'     => 'Virheelliset uudelleenohjaukset',
'brokenredirectstext' => 'Seuraavat uudelleenohjaukset on linkitetty artikkeleihin, joita ei ole olemassa.',
'brokenredirects-edit'   => '(muokkaa)',
'brokenredirects-delete' => '(poista)',

# Miscellaneous special pages
#
'nbytes'              => '$1 {{PLURAL:$1|tavu|tavua}}',
'ncategories'         => '$1 {{PLURAL:$1|luokka|luokkaa}}',
'nlinks'              => '$1 {{PLURAL:$1|linkki|linkkiä}}',
'nmembers'            => '$1 {{PLURAL:$1|jäsen|jäsentä}}',
'nrevisions'          => '$1 {{PLURAL:$1|muutos|muutosta}}',
'nviews'              => '$1 {{PLURAL:$1|lataus|latausta}}',
'lonelypages'         => 'Yksinäiset sivut',
'lonelypagestext'     => 'Seuraaviin sivuhin ei ole linkkejä muualta wikistä.',
'uncategorizedpages'  => 'Luokittelemattomat sivut',
'uncategorizedcategories' => 'Luokittelemattomat luokat',
'uncategorizedimages' => 'Luokittelemattomat tiedostot',
'unusedcategories'    => 'Käyttämättömät luokat',
'unusedimages'        => 'Käyttämättömät tiedostot',
'popularpages'        => 'Suositut sivut',
'wantedcategories'    => 'Halutut luokat',
'wantedpages'         => 'Halutut sivut',
'mostlinked'          => 'Sivut, joihin on eniten linkkejä',
'mostlinkedcategories'=> 'Luokat, joihin on eniten linkkejä',
'mostcategories'      => 'Sivut, jotka ovat useissa luokissa',
'mostimages'          => 'Kuvat, joihin on eniten linkkejä',
'mostrevisions'       => 'Sivut, joilla on eniten muutoksia',
'allpages'            => 'Kaikki sivut',
'prefixindex'         => 'Sivut otsikon alun mukaan',
'randompage'          => 'Satunnainen sivu',
'shortpages'          => 'Lyhyet sivut',
'longpages'           => 'Pitkät sivut',
'deadendpages'        => 'Sivut, joilla ei ole linkkejä',
'deadendpagestext'    => 'Seuraavat sivut eivät linkitä muihin sivuihin wikissä.',
'listusers'           => 'Käyttäjälista',
'specialpages'        => 'Toimintosivut',
'spheading'           => 'Toimintosivut',
'restrictedpheading'  => 'Rajoitetut toimintosivut',
'recentchangeslinked' => 'Linkitettyjen sivujen muutokset',
'rclsub'              => 'Sivut, joihin linkki sivulta $1',
'newpages'            => 'Uudet sivut',
'newpages-username'   => 'Käyttäjätunnus:',
'ancientpages'        => 'Kauan muokkaamattomat sivut',
'intl'                => 'Kieltenväliset linkit',
'move'                => 'Siirrä',
'movethispage'        => 'Siirrä tämä sivu',
'unusedimagestext'    => 'Huomaa, että muut verkkosivut saattavat viitata tiedostoon suoran URL:n avulla, jolloin tiedosto saattaa olla tässä listassa, vaikka sitä käytetäänkin.',
'unusedcategoriestext'=> 'Nämä luokat ovat olemassa, mutta niitä ei käytetä.',

# Book sources
'booksources'         => 'Kirjalähteet',
'booksources-search-legend' => 'Etsi kirjalähteitä',
'booksources-isbn'    => 'ISBN:',
'booksources-go'      => 'Etsi',
'booksources-text'    => 'Alla linkkejä ulkopuolisiin sivustoihin, joilla myydään uusia ja käytettyjä kirjoja. Sivuilla voi myös olla lisätietoa kirjoista.',

'categoriespagetext'  => '{{GRAMMAR:inessive|{{SITENAME}}}} on seuraavat luokat:',
'data'                => 'Data', // TODO: CHECK ME
'userrights'          => 'Käyttöoikeuksien hallinta',
'groups'              => 'Ryhmät',

'isbn'                => 'ISBN',
'unwatchedpages'      => 'Tarkkailemattomat sivut',
'listredirects'       => 'Uudelleenohjaukset',
'randomredirect'      => 'Satunnainen uudelleenohjaus',


# No reason to overwrite
'alphaindexline'      => '$1…$2',
'version'             => 'Versio',
'log'                 => 'Lokit',  # XXX: don't make this lowercase, it messed up the sorting on Special:Specialpages
'alllogstext'         => 'Yhdistetty lokien näyttö. Voit rajoittaa listaa valitsemalla lokityypin, käyttäjän tai sivun johon muutos on kohdistunut.',
'logempty'            => 'Ei tapahtumia lokissa.',

# Special:Allpages
'nextpage'            => 'Seuraava sivu ($1)',
'prevpage'            => 'Edellinen sivu ($1)',
'allpagesfrom'        => 'Näytä sivuja lähtien sivusta:',
'allarticles'         => 'Kaikki sivut',
'allinnamespace'      => 'Kaikki sivut nimiavaruudessa $1',
'allnotinnamespace'   => 'Kaikki sivut, jotka eivät ole nimiavaruudessa $1',
'allpagesprev'        => 'Edellinen',
'allpagesnext'        => 'Seuraava',
'allpagessubmit'      => 'Vaihda',
'allpagesprefix'      => 'Näytä sivut, joiden otsikko alkaa',
'allpagesbadtitle'    => 'Annettu otsikko oli kelvoton tai siinä oli wikien välinen etuliite.',

# Special:Listusers
'listusersfrom'       => 'Näytä käyttäjät alkaen:',

# Email this user
#
'mailnologin'         => 'Lähettäjän osoite puuttuu',
'mailnologintext'     => 'Sinun pitää olla [[Special:Userlogin|kirjautuneena sisään]] ja [[Special:Preferences|asetuksissasi]] pitää olla toimiva ja <strong>varmennettu</strong> sähköpostiosoite, jotta voit lähettää sähköpostia muille käyttäjille.',
'emailuser'           => 'Lähetä sähköpostia tälle käyttäjälle',
'emailpage'           => 'Lähetä sähköpostia käyttäjälle',
'emailpagetext'       => 'Jos tämä käyttäjä on antanut asetuksissaan kelvollisen sähköpostiosoitteen, alla olevalla lomakeella voi lähettää yhden viestin hänelle. Omissa asetuksissasi annettu sähköpostiosoite näkyy sähköpostin lähettäjän osoitteena, jotta vastaanottaja voi vastata viestiin.',
'usermailererror'     => 'Postitus palautti virheen:',
'defemailsubject'     => '{{SITENAME}}-sähköposti',
'noemailtitle'        => 'Ei sähköpostiosoitetta',
'noemailtext'         => 'Tämä käyttäjä ei ole määritellyt kelpoa sähköpostiosoitetta tai ei halua postia muilta käyttäjiltä.',
'emailfrom'           => 'Lähettäjä',
'emailto'             => 'Vastaanottaja',
'emailsubject'        => 'Aihe',
'emailmessage'        => 'Viesti',
'emailsend'           => 'Lähetä',
'emailccme'           => 'Lähetä kopio viestistä minulle.',
'emailccsubject'      => 'Kopio lähettämästäsi viestistä osoitteeseen $1: $2',
'emailsent'           => 'Sähköposti lähetetty',
'emailsenttext'       => 'Sähköpostiviestisi on lähetetty.',

# Watchlist
#
'watchlist'           => 'Tarkkailulista',
'watchlistfor'        => 'käyttäjälle <b>$1</b>',
'nowatchlist'         => 'Tarkkailulistallasi ei ole sivuja.',
'watchlistanontext'   => 'Sinun täytyy $1, jos haluat käyttää tarkkailulistaa.',
'watchlistcount'      => 'Tarkkailulistallasi on <b>$1</b> sivua, keskustelusivut mukaan lukien.',
'clearwatchlist'      => 'Tarkkailulistan tyhjentäminen',
'watchlistcleartext'  => 'Haluatko tyhjentää tarkkailulistan?',
'watchlistclearbutton'=> 'Tyhjennä tarkkailusta',
'watchlistcleardone'  => 'Tarkkailulista on tyhjennetty. $1 sivua poistettiin listalta.',
'watchnologin'        => 'Et ole kirjautunut sisään',
'watchnologintext'    => 'Sinun pitää kirjautua sisään, jotta voisit käyttää tarkkailulistaa.',
'addedwatch'          => 'Lisätty tarkkailulistalle',
'addedwatchtext'      => 'Sivu \'\'\'$1\'\'\' on lisätty [[Special:Watchlist|tarkkailulistallesi]]. Tulevaisuudessa sivuun ja sen keskustelusivuun tehtävät muutokset listataan täällä. Sivu on \'\'\'lihavoitu\'\'\' [[Special:Recentchanges|tuoreiden muutosten listassa]], jotta huomaisit sen helpommin. Jos haluat myöhemmin poistaa sivun tarkkailulistaltasi, napsauta linkkiä \'\'lopeta tarkkailu\'\' sivun reunassa.',
'removedwatch'        => 'Poistettu tarkkailulistalta',
'removedwatchtext'    => 'Sivu \'\'\'$1\'\'\' on poistettu tarkkailulistaltasi.',
'watch'               => 'Tarkkaile',
'watchthispage'       => 'Tarkkaile tätä sivua',
'unwatch'             => 'Lopeta tarkkailu',
'unwatchthispage'     => 'Lopeta tarkkailu',
'notanarticle'        => 'Ei ole sivu',
'watchnochange'       => 'Valittuna ajanjaksona yhtäkään tarkkailemistasi sivuista ei muokattu.',
'watchdetails'        => 'Keskustelusivuja mukaan laskematta tarkkailun alla on $1 sivua, joista $2 on muokattu määritellyllä aikavälillä. <span class="plainlinks"> [$4 Muokkaa listaa]</span>.',
'wlheader-enotif'     => '* Sähköposti-ilmoitukset ovat käytössä.',
'wlheader-showupdated'=> '* Sivut, joita on muokattu viimeisen käyntisi jälkeen on merkitty \'\'\'paksummalla\'\'\'',
'watchmethod-recent'  => 'tarkistetaan tuoreimpia muutoksia tarkkailluille sivuille',
'watchmethod-list'    => 'tarkistetaan tarkkailtujen sivujen tuoreimmat muutokset',
'removechecked'       => 'Poista valitut sivut tarkkailulistalta',
'watchlistcontains'   => 'Tarkkailulistallasi on $1 sivua.',
'watcheditlist'       => 'Tässä on aakkostettu lista tarkkailemistasi sivuista. Merkitse niiden sivujen ruudut, jotka haluat poistaa tarkkailulistaltasi.',
'removingchecked'     => 'Merkityt sivut poistettiin tarkkailulistalta.',
'couldntremove'       => 'Sivua $1 ei voitu poistaa tarkkailulistalta',
'iteminvalidname'     => 'Sivun $1 kanssa oli ongelmia! Sivun nimessä on vikaa.',
'wlnote'              => 'Alla on <b>$1</b> muutosta viimeisen <b>$2</b> tunnin ajalta.', // TODO NOWIKIMARKUP
'wlshowlast'          => 'Näytä viimeiset $1 tuntia tai $2 päivää$3',
'wlsaved'             => 'Tämä on tallennettu versio tarkkailulistastasi.',
'watchlist-show-bots' => 'Näytä bottien muokkaukset',
'watchlist-hide-bots' => 'Piilota bottien muokkaukset',
'watchlist-show-own'  => 'Näytä omat muokkaukset',
'watchlist-hide-own'  => 'Piilota omat muokkaukset',
'watchlist-show-minor'=> 'Näytä pienet muokkaukset',
'watchlist-hide-minor'=> 'Piilota pienet muokkaukset',
'wldone'              => 'Muutokset tehty.',
'watching'            => 'Lisätään tarkkailulistalle...',
'unwatching'          => 'Poistetaan tarkkailulistalta...',

'enotif_mailer'       => '{{GRAMMAR:genitive|{{SITENAME}}}} sivu on muuttunut -ilmoitus',
'enotif_reset'        => 'Merkitse kaikki sivut katsotuiksi',
'enotif_newpagetext'  => 'Tämä on uusi sivu.',
'changed'             => 'muuttanut sivua',
'created'             => 'luonut sivun',
'enotif_subject'      => '$PAGEEDITOR on $CHANGEDORCREATED $PAGETITLE',
'enotif_lastvisited'  => 'Osoitteessa $1 on kaikki muutokset viimeisen käyntisi jälkeen.',
'enotif_body'         => 'Käyttäjä $WATCHINGUSERNAME,

{{GRAMMAR:genitive|{{SITENAME}}}} käyttäjä $PAGEEDITOR on $CHANGEDORCREATED $PAGETITLE $PAGEEDITDATE. Nykyinen versio on osoitteessa $PAGETITLE_URL .

$NEWPAGE

Muokkaajan yhteenveto: $PAGESUMMARY $PAGEMINOREDIT

Ota yhteyttä muokkaajaan:
sähköposti: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Uusia ilmoituksia tästä sivusta ei tule kunnes vierailet sivulla. Voit myös nollata ilmoitukset kaikille tarkkailemillesi sivuille tarkkailulistallasi.

             {{GRAMMAR:genitive|{{SITENAME}}}} ilmoitusjärjestelmä

--
Tarkkailulistan asetuksia voit muuttaa osoitteessa:
{{fullurl:Special:Watchlist/edit}}

Palaute ja lisäapu osoitteessa:
{{fullurl:Help:Sähköposti-ilmoitus}}',


# Delete/protect/revert
#
'deletepage'          => 'Poista sivu',
'confirm'             => 'Toteuta',
'excontent'           => 'sisälsi: ”$1”',
'excontentauthor'     => 'sisälsi: ”$1” (ainoa muokkaaja oli $2)',
'exbeforeblank'       => 'ennen tyhjentämistä sisälsi: ”$1”',
'exblank'             => 'oli tyhjä',
'confirmdelete'       => 'Vahvista poisto',
'deletesub'           => 'Sivun $1 poisto',
'historywarning'      => 'Varoitus: Sivulla, jonka aiot poistaa on muokkaushistoria:',
'confirmdeletetext'   => 'Olet poistamassa sivun tai tiedoston ja kaiken sen historian. Ymmärrä teon seuraukset ja tee poisto {{GRAMMAR:genitive|{{SITENAME}}}} käytäntöjen mukaisesti.',
'actioncomplete'      => 'Toiminto suoritettu',
'deletedtext'         => '\'\'\'[[Special:Undelete/$1|$1]]\'\'\' on poistettu. Katso $2 nähdäksesi listan viimeaikaisista poistoista.',
'deletedarticle'      => 'poisti sivun $1',
'dellogpage'          => 'Poistoloki',
'dellogpagetext'      => 'Alla on loki viimeisimmistä poistoista.',
'deletionlog'         => 'poistoloki',
'reverted'            => 'Palautettu aikaisempaan versioon',
'deletecomment'       => 'Poistamisen syy',
'imagereverted'       => 'Aikaisempaan versioon palauttaminen onnistui.',
'rollback'            => 'palauta aiempaan versioon',
'rollback_short'      => 'Palautus',
'rollbacklink'        => 'palauta',
'rollbackfailed'      => 'Palautus epäonnistui',
'cantrollback'        => 'Aiempaan versioon ei voi palauttaa, koska viimeisin kirjoittaja on sivun ainoa tekijä.',
'alreadyrolled'       => 'Käyttäjän [[User:$2|$2]] ([[User_talk:$2|keskustelu]]) tekemiä muutoksia sivuun $1 ei voi kumota, koska käyttäjä [[User:$3|$3]] ([[User_talk:$3|keskustelu]]) on tehnyt uudempia muutoksia.',
'editcomment'         => 'Muokkauksen yhteenveto oli: <i>$1</i>.', // TODO NOWIKIMARKUP
'revertpage'          => 'Käyttäjän [[Special:Contributions/$2|$2]] ([[User_talk:$2|keskustelu]]) muokkaukset kumottiin ja sivu palautettiin viimeisimpään käyttäjän [[User:$1|$1]] tekemään versioon.',

'sessionfailure'      => 'Istuntosi kanssa on ongelma. Muutosta ei toteutettu varotoimena sessionkaappauksien takia. Käytä selaimen paluutoimintoa ja päivitä sivu, jolta tulit, ja koita uudelleen.',
'protectlogpage'      => 'Suojausloki',
'protectlogtext'      => 'Alla loki sivujen suojauksista ja suojauksien poistoista.',
'protectedarticle'    => 'suojasi sivun $1',
'unprotectedarticle'  => 'poisti suojauksen sivulta $1',
'protectsub'          => 'Sivun $1 suojaus',
'confirmprotecttext'  => 'Haluatko varmasti suojata tämän sivun?',
'confirmprotect'      => 'Vahvista suojaus',
'protectmoveonly'     => 'Suojaa vain siirroilta',
'protectcomment'      => 'Suojauksen syy:',
'unprotectsub'        => 'Suojauksen poisto sivulta $1',
'confirmunprotecttext'=> 'Haluatko varmasti poistaa tämän sivun suojauksen?',
'confirmunprotect'    => 'Vahvista suojauksen poisto',
'unprotectcomment'    => 'Syy suojauksen poistoon',
'protect-unchain'     => 'Käytä siirtosuojausta',
'protect-text'        => 'Voit katsoa ja muuttaa sivun ”<strong>$1</strong>” suojauksia:',
'protect-viewtext'    => 'Et voi muuttaa sivun ”<strong>$1</strong>” suojauksia. Alla on sivun nykyiset suojaukset:',
'protect-cascadeon'   => "Tämä sivu on suojauksen kohteena, koska se on sisällytetty alla oleviin laajennetusti suojattuihin sivuihin. Voit muuttaa tämän sivun suojaustasoa, mutta se ei vaikuta laajennettuun suojaukseen.",
'protect-default'     => '(ei rajoituksia)',
'protect-level-autoconfirmed' => 'Estä uudet ja anonyymit käyttäjät',
'protect-level-sysop' => 'Vain ylläpitäjät',
'protect-summary-cascade' => 'laajennettu',
'protect-cascade'     => 'Laajenna suojaus koskemaan kaikkia tähän sivuun sisällytettyjä sivuja.',

# restrictions (nouns)
'restriction-edit'    => 'muokkaus',
'restriction-move'    => 'siirto',


# Undelete
'undelete'            => 'Palauta poistettuja sivuja',
'undeletepage'        => 'Poistettujen sivujen selaus',
'viewdeletedpage'     => 'Poistettujen sivujen selaus',
'undeletepagetext'    => 'Seuraavat sivut on poistettu, mutta ne löytyvät vielä arkistosta, joten ne ovat palautettavissa. Arkisto saatetaan tyhjentää aika ajoin.',
'undeleteextrahelp'  => 'Palauta sivu valitsemalla <b><i>Palauta</i></b>. Voit palauttaa versiota valikoivasti valitsemalla vain niiden versioiden valintalaatikot, jotka haluat palauttaa.',
'undeletearticle'     => 'Palauta poistettu sivu',
'undeleterevisions'   => '{{PLURAL:$1|yksi versio|$1 versiota}} arkistoitu.',
'undeletehistory'     => 'Jos palautat sivun, kaikki versiot lisätään sivun historiaan. Jos uusi sivu samalla nimellä on luotu poistamisen jälkeen, palautetut versiot lisätään sen historiaan, ja olemassa olevaa versiota ei korvata automaattisesti.',
'undeletehistorynoadmin' => 'Tämä sivu on poistettu. Syy sivun poistamiseen näkyy yhteenvedossa, jossa on myös tiedot, ketkä ovat muokanneet tätä sivua ennen poistamista. Sivujen varsinainen sisältö on vain ylläpitäjien luettavissa.',
'undelete-revision'    => 'Poistettu sivu $1 hetkellä $2',
'undeleterevision-missing' => 'Virheellinen tai puuttuva versio. Se on saatettu palauttaa tai poistaa arkistosta.',
'undeletebtn'         => 'Palauta',
'undeletereset'       => 'Tyhjennä',
'undeletecomment'     => 'Kommentti:',
'undeletedarticle'    => 'palautti sivun [[$1]]',
'undeletedrevisions'  => '$1 versiota palautettiin',
'undeletedrevisions-files' => '$1 versiota ja $2 tiedosto(a) palautettiin',
'undeletedfiles'      => "$1 tiedosto(a) palautettiin",
'cannotundelete'      => 'Palauttaminen epäonnistui.',
'undeletedpage'       => '<big>\'\'\'”$1” on palautettu.\'\'\'</big>

[[Special:Log/delete|Poistolokista]] löydät listan viimeisimmistä poistoista ja palautuksista.',
'undelete-header'     => '[[Special:Log/delete|poistolokissa]] on lista viimeisimmistä poistoista.',
'undelete-search-box' => 'Etsi poistettuja sivuja',
'undelete-search-prefix' => 'Näytä sivut, jotka alkavat merkkijonolla:',
'undelete-search-submit' => 'Hae',
'undelete-no-results' => 'Poistoarkistosta ei löytynyt haettuja sivuja.',


'namespace'           => 'Nimiavaruus:',
'invert'              => 'Käännä nimiavaruusvalinta päinvastaiseksi',

# Contributions
#
'contributions'       => 'Käyttäjän muokkaukset',
'mycontris'           => 'Muokkaukset',
'contribsub'          => 'Käyttäjän $1 muokkaukset',
'nocontribs'          => 'Näihin ehtoihin sopivia muokkauksia ei löytynyt.',
'ucnote'              => 'Alla on \'\'\'$1\'\'\' viimeisintä tämän käyttäjän tekemää muokkausta viimeisten \'\'\'$2\'\'\' päivän aikana.',
'uclinks'             => 'Katso $1 viimeisintä muokkausta; katso $2 viimeisintä päivää.',
'uctop'               => ' (uusin)' ,
'newbies'             => 'tulokkaat',

'sp-contributions-newest' => 'Uusimmat',
'sp-contributions-oldest' => 'Vanhimmat',
'sp-contributions-newer'  => '← $1 uudempaa',
'sp-contributions-older'  => '$1 vanhempaa →',
'sp-contributions-newbies-sub' => 'Uusien tulokkaiden muokkaukset',
'sp-contributions-blocklog'    => 'estot',

# What links here
#
'whatlinkshere'       => 'Tänne viittaavat sivut',
'notargettitle'       => 'Ei kohdetta',
'notargettext'        => 'Et ole määritellyt kohdesivua tai -käyttäjää johon toiminto kohdistuu.',
'linklistsub'         => 'Lista linkeistä',
'linkshere'           => 'Seuraavilta sivuilta on linkki sivulle <b>[[:$1]]</b>:',
'nolinkshere'         => 'Sivulle <b>[[:$1]]</b> ei ole linkkejä.',
'isredirect'          => 'uudelleenohjaussivu',
'istemplate'          => 'sisällytetty mallineeseen',

# Block/unblock IP
#
'blockip'             => 'Aseta muokkausesto',
'blockiptext'         => 'Tällä lomakkeella voit estää käyttäjän tai IP-osoitteen muokkausoikeudet. Muokkausoikeuksien poistamiseen pitää olla syy, esimerkiksi sivujen vandalisointi. Kirjoita syy siihen varattuun kenttään.<br />Vanhenemisajat noudattavat GNUn standardimuotoa, joka on kuvattu tar-manuaalissa ([http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html] [EN]), esimerkiksi ”1 hour”, ”2 days”, ”next Wednesday”, 2005-08-29”. Esto voi olla myös ”indefinite” tai ”infinite”, joka kestää kunnes se poistetaan.',
'ipaddress'           => 'IP-osoite', // TODO bug
'ipadressorusername'  => 'IP-osoite tai käyttäjätunnus',
'ipbexpiry'           => 'Umpeutuu',
'ipbreason'           => 'Syy',
'ipbanononly'         => 'Estä vain kirjautumattomat käyttäjät',
'ipbcreateaccount'    => 'Estä tunnusten luonti',
'ipbenableautoblock'  => 'Estä viimeisin IP-osoite, josta käyttäjä on muokannut, sekä ne osoitteet, joista hän jatkossa yrittää muokata.',
'ipbsubmit'           => 'Estä',
'ipbother'            => 'Vapaamuotoinen kesto',
'ipboptions'          => '2 tuntia:2 hours,1 päivä:1 day,3 päivää:3 days,1 viikko:1 week,2 viikkoa:2 weeks,1 kuukausi:1 month,3 kuukautta:3 months,6 kuukautta:6 months,1 vuosi:1 year,ikuisesti:infinite',
'ipbotheroption'      => 'Muu kesto',
'badipaddress'        => 'IP-osoite on väärin muotoiltu.',
'blockipsuccesssub'   => 'Esto onnistui',
'blockipsuccesstext'  => 'Käyttäjä tai IP-osoite \'\'\'$1\'\'\' on estetty.<br />Nykyiset estot löytyvät [[Special:Ipblocklist|estolistalta]].',
'ipb-unblock-addr'    => 'Poista käyttäjän $1 esto',
'ipb-unblock'         => 'Poista käyttäjän tai IP-osoitteen muokkausesto',
'ipb-blocklist-addr'  => 'Näytä käyttäjän $1 estot',
'ipb-blocklist'       => 'Näytä estot',
'unblockip'           => 'Muokkauseston poisto',
'unblockiptext'       => 'Tällä lomakkeella voit poistaa käyttäjän tai IP-osoitteen muokkauseston.',
'ipusubmit'           => 'Poista esto',
'unblocked'           => 'Käyttäjän [[User:$1|$1]] esto on poistettu',
'ipblocklist'         => 'Lista estetyistä IP-osoitteista',
'ipblocklistempty'    => 'Estolista on tyhjä.',
'blocklistline'       => '$1 — $2 on estänyt käyttäjän $3 ($4)',
'infiniteblock'       => 'ikuisesti',
'expiringblock'       => 'vanhenee $1',
'anononlyblock'       => 'vain kirjautumattomat',
'noautoblockblock'    => 'ei automaattista IP-osoitteiden estoa',
'createaccountblock'  => 'tunnusten luonti estetty',
'blocklink'           => 'estä',
'unblocklink'         => 'poista esto',
'contribslink'        => 'muokkaukset',
'autoblocker'         => 'Olet automaattisesti estetty, koska jaat IP-osoitteen käyttäjän $1 kanssa. Eston syy: $2.', // TODO: IS WIKIMARKUP?
'blocklogpage'        => 'Estoloki',
'blocklogentry'       => 'esti käyttäjän tai IP-osoitteen $1. Eston kesto $2 ($3)',
'blocklogtext'        => 'Tässä on loki muokkausestoista ja niiden purkamisista. Automaattisesti estettyjä IP-osoitteita ei kirjata. Tutustu [[Special:Ipblocklist|estolistaan]] nähdäksesi listan tällä hetkellä voimassa olevista estoista.',
'unblocklogentry'     => 'poisti käyttäjältä $1 muokkauseston',
'block-log-flags-anononly' => 'vain kirjautumattomat käyttäjät',
'block-log-flags-nocreate' => 'tunnusten luonti estetty',
'block-log-flags-autoblock' => 'automaattinen IP-osoitteiden esto',
'range_block_disabled'=> 'Ylläpitäjän oikeus luoda alue-estoja ei ole käytössä.',
'ipb_expiry_invalid'  => 'Virheellinen umpeutumisaika.',
'ip_range_invalid'    => 'Virheellinen IP-alue.',
'ipb_already_blocked' => '”$1” on jo estetty.',
'ipb_cant_unblock'    => 'Estoa ”$1” ei löytynyt. Se on saatettu poistaa.',
'proxyblocker'        => 'Välityspalvelinesto',
'proxyblockreason'    => 'IP-osoitteestasi on estetty muokkaukset, koska se on avoin välityspalvelin. Ota yhteyttä Internet-palveluntarjoajaasi tai tekniseen tukeen ja kerro heillä tästä tietoturvaongelmasta.',
'proxyblocksuccess'   => 'Valmis.',
'sorbs'               => 'SORBS-DNSBL',
'sorbsreason'         => 'IP-osoitteesti on listattu avoimena välityspalvelimena [http://www.sorbs.net SORBSin] mustalla listalla.',
'sorbs_create_account_reason' => 'IP-osoitteesi on listattu avoimena välityspalvelimena [http://www.sorbs.net SORBSin] mustalla listalla. Et voi luoda käyttäjätunnusta.',


# Developer tools
#
'lockdb'              => 'Lukitse tietokanta',
'unlockdb'            => 'Vapauta tietokanta',
'lockdbtext'          => 'Tietokannan lukitseminen estää käyttäjiä muokkaamasta sivuja, vaihtamasta asetuksia, muokkaamasta tarkkailulistoja ja tekemästä muita tietokannan muuttamista vaativia toimia. Ole hyvä ja vahvista, että tämä on tarkoituksesi, ja että vapautat tietokannan kun olet suorittanut ylläpitotehtävät.',
'unlockdbtext'        => 'Tietokannan vapauttaminen antaa käyttäjille mahdollisuuden muokkata sivuja, vaihtamaa asetuksia, muokkata tarkkailulistoja ja tehdä muita tietokannan muuttamista vaativia toimia. Ole hyvä ja vahvista, että tämä on tarkoituksesi.',
'lockconfirm'         => 'Kyllä, haluan varmasti lukita tietokannan.',
'unlockconfirm'       => 'Kyllä, haluan varmasti vapauttaa tietokannan.',
'lockbtn'             => 'Lukitse tietokanta',
'unlockbtn'           => 'Vapauta tietokanta',
'locknoconfirm'       => 'Et merkinnyt vahvistuslaatikkoa.',
'lockdbsuccesssub'    => 'Tietokannan lukitseminen onnistui',
'unlockdbsuccesssub'  => 'Tietokannan vapauttaminen onnistui',
'lockdbsuccesstext'   => 'Tietokanta on lukittu.<br />Muista vapauttaa tietokanta ylläpitotoimenpiteiden jälkeen.',
'unlockdbsuccesstext' => 'Tietokanta on vapautettu.',
'lockfilenotwritable' => 'Tietokannan lukitustiedostoa ei voi kirjoittaa. Tarkista oikeudet.',
'databasenotlocked'   => 'Tietokanta ei ole lukittu.',


# Make sysop
'rightslog'           => 'Käyttöoikeusloki',
'rightslogtext'       => 'Alla on loki on käyttäjien käyttöoikeuksien muutoksista.',
'rightslogentry'      => 'Käyttäjän [[$1]] oikeudet muutettiin ryhmistä $2 ryhmiin $3',
'rightsnone'          => '(ei oikeuksia)',


# Move page
#
'movepage'            => 'Siirrä sivu',
'movepagetext'        => 'Alla olevalla lomakkeella voit nimetä uudelleen sivuja, jolloin niiden koko historia siirtyy uuden nimen alle. Vanhasta sivusta tulee uudelleenohjaussivu, joka osoittaa uuteen sivuun. Vanhaan sivuun suunnattuja linkkejä ei muuteta, joten muista tehdä tarkistukset kaksinkertaisten tai rikkinäisten uudelleenohjausten varalta. \'\'\'Olet vastuussa siitä, että linkit osoittavat sinne, mihin niiden on tarkoituskin osoittaa.\'\'\'<br \>Huomaa, että sivua \'\'\'ei\'\'\' siirretä mikäli uusi otsikko on olemassa olevan sivun käytössä, paitsi milloin kyseessä on tyhjä sivu tai uudelleenohjaus, jolla ei ole muokkaushistoriaa. Tämä tarkoittaa sitä, että voit siirtää sivun takaisin vanhalle nimelleen mikäli teit virheen, mutta et voi kirjoittaa olemassa olevan sivun päälle. Jos sivu tarvitsee siirtää olemassa olevan sivun päälle, ota yhteyttä [[Special:Listusers/sysop|ylläpitäjään]].',
'movepagetalktext'    => 'Sivuun mahdollisesti kytketty keskustelusivu siirretään automaattisesti, \'\'\'paitsi jos\'\'\':
*Siirrät sivua nimiavaruudesta toiseen
*Kohdesivulla on olemassa keskustelusivu, joka ei ole tyhjä, tai
*Kumoat alla olevan ruudun asetuksen.

Näissä tapauksissa sivut täytyy siirtää tai yhdistää käsin.',
'movearticle'         => 'Siirrä sivu',
'movenologin'         => 'Et ole kirjautunut sisään',
'movenologintext'     => 'Sinun pitää olla rekisteröitynyt käyttäjä ja kirjautua sisään, jotta voisit siirtää sivun.',
'newtitle'            => 'Uusi nimi sivulle',
'move-watch'          => 'Tarkkaile tätä sivua',
'movepagebtn'         => 'Siirrä sivu',
'pagemovedsub'        => 'Siirto onnistui',
'pagemovedtext'       => 'Sivu \'\'\'[[$1]]\'\'\' siirrettiin nimelle \'\'\'[[$2]]\'\'\'.',
'articleexists'       => 'Kohdesivu on jo olemassa, tai valittu nimi ei ole sopiva. Ole hyvä ja valitse uusi nimi.',
'talkexists'          => 'Sivun siirto onnistui, mutta keskustelusivua ei voitu siirtää, koska uuden otsikon alla on jo keskustelusivu. Keskustelusivujen sisältö täytyy yhdistää käsin.',
'movedto'             => 'Siirretty uudelle otsikolle',
'movetalk'            => 'Siirrä myös keskustelusivu.',
'talkpagemoved'       => 'Myös sivun keskustelusivu siirrettiin.',
'talkpagenotmoved'    => 'Sivun keskustelusivua \'\'\'ei\'\'\' siirretty.',
'1movedto2'           => 'siirsi sivun ”$1” uudelle nimelle ”$2”',
'1movedto2_redir'     => 'siirsi sivun ”$1” uudelleenohjauksen ”$2” päälle',
'movelogpage'         => 'Siirtoloki',
'movelogpagetext'     => 'Anna on loki siirretyistä sivuista.',
'movereason'          => 'Syy',
'revertmove'          => 'kumoa',
'delete_and_move'     => 'Poista kohdesivu ja siirrä',
'delete_and_move_text'   => 'Kohdesivu [[$1]] on jo olemassa. Haluatko poistaa sen, jotta nykyinen sivu voitaisiin siirtää?',
'delete_and_move_confirm'=> 'Poista sivu',
'delete_and_move_reason' => 'Sivu on siirron tiellä.',
'selfmove'            => 'Lähde- ja kohdenimi ovat samat.',
'immobile_namespace'  => 'Sivuja ei voi siirtää tähän nimiavaruuteen.',

# Export

'export'              => 'Sivujen vienti',
'exporttext'          => 'Voit viedä sivun tai sivujen tekstiä ja muokkaushistoriaa XML-muodossa. Tämä tieto voidaan tuoda johonkin toiseen wikiin, jossa käytetään MediaWiki-ohjelmistoa.<br \>Syötä sivujen otsikoita riveittäin alla olevaan laatikkoon. Valitse myös, haluatko kaikki versiot sivuista, vai ainoastaan nykyisen version.<br \>Jälkimmäisessä tapauksessa voit myös käyttää linkkiä. Esimerkiksi sivun {{Mediawiki:mainpage}} saa vietyä linkistä [[{{ns:Special}}:Export/{{Mediawiki:mainpage}}]].',
'exportcuronly'       => 'Liitä mukaan ainoastaan uusin versio, ei koko historiaa.',
'exportnohistory'     => "----\nSivujen koko historian vienti on estetty suorituskykysyistä.",
'export-submit'       => 'Vie',


# Namespace 8 related

'allmessages'         => 'Järjestelmäviestit',
'allmessagesname'     => 'Nimi',
'allmessagesdefault'  => 'Oletusarvo',
'allmessagescurrent'  => 'Nykyinen arvo',
'allmessagestext'     => 'Tämä on luettelo kaikista MediaWiki-nimiavaruudessa olevista viesteistä.',
'allmessagesnotsupportedUI' => 'Tämä sivu ei tue käyttöliittymäkieltäsi <b>$1</b> tässä MediaWikissä.',
'allmessagesnotsupportedDB' => 'Tämä sivu ei ole käytössä, koska <tt>$wgUseDatabaseMessages</tt>-asetus on pois päältä.',
'allmessagesfilter'   => 'Viestiavainsuodatin:',
'allmessagesmodified' => 'Näytä vain muutetut',


# Thumbnails

'thumbnail-more'      => 'Suurenna',
'missingimage'        => '<b>Puuttuva kuva</b><br /><i>$1</i>',
'filemissing'         => 'Tiedosto puuttuu',
'thumbnail_error'     => 'Pienoiskuvan luominen epäonnistui: $1',

# Special:Import
'import'              => 'Tuo sivuja',
'importinterwiki'     => 'Tuo sivuja lähiwikeistä',
'import-interwiki-text'      => 'Valitse wiki ja sivun nimi. Versioiden päivämäärät ja muokkaajat säilytetään. Kaikki wikienväliset tuonnit kirjataan [[Special:Log/import|tuontilokiin]].',
'import-interwiki-history'   => 'Kopioi sivun koko historia',
'import-interwiki-submit'    => 'Tuo',
'import-interwiki-namespace' => 'Siirrä nimiavaruuteen:',
'importtext'          => 'Vie sivuja lähdewikistä käyttäen [[Special:Export|vienti]]-työkalua. Tallenna tiedot koneellesi ja tallenna ne täällä.',
'importstart'         => 'Tuodaan sivuja...',
'import-revision-count' => '$1 {{PLURAL:$1|versio|versiota}}',
'importnopages'       => 'Ei tuotavia sivuja.',
'importfailed'        => 'Tuonti epäonnistui: $1',
'importunknownsource' => 'Tuntematon lähdetyyppi',
'importcantopen'      => 'Tuontitiedoston avaus epäonnistui',
'importbadinterwiki'  => 'Kelpaamaton wikienvälinen linkki',
'importnotext'        => 'Tyhjä tai ei tekstiä',
'importsuccess'       => 'Tuonti onnistui!',
'importhistoryconflict' => 'Sivusta on olemassa tuonnin kanssa ristiriitainen muokkausversio. Tämä sivu on saatettu tuoda jo aikaisemmin.',
'importnosources'     => 'Wikienvälisiä tuontilähteitä ei ole määritelty ja suorat historiatallennukset on poistettu käytöstä.',
'importnofile'        => 'Mitään tuotavaa tiedostoa ei lähetetty.',
'importuploaderror'   => 'Tiedoston lähettäminen epäonnistui. Tiedosto saattaa olla liian suuri.',

# import log
'importlogpage'                    => 'Tuontiloki',
'importlogpagetext'                => 'Loki toisista wikeistä tuoduista sivuista.',
'import-logentry-upload'           => 'toi sivun ”[[$1]]” lähettämällä tiedoston',
'import-logentry-upload-detail'    => '$1 versio(ta)',
'import-logentry-interwiki'        => 'toi toisesta wikistä sivun ”$1”',
'import-logentry-interwiki-detail' => '$1 versio(ta) sivusta $2',



# Metadata
'nodublincore'        => 'Dublin Core RDF-metatieto on poissa käytöstä tällä palvelimella.',
'nocreativecommons'   => 'Creative Commonsin RDF-metatieto on poissa käytöstä tällä palvelimella.',
'notacceptable'       => 'Wikipalvelin ei voi näyttää tietoja muodossa, jota ohjelmasi voisi lukea.',

# Attribution

'anonymous'           => '{{GRAMMAR:genitive|{{SITENAME}}}} anonyymit käyttäjät',
'siteuser'            => '{{GRAMMAR:genitive|{{SITENAME}}}} käyttäjä $1',
'lastmodifiedatby'    => 'Tätä sivua muokkasi viimeksi ”$3” $2 kello $1.',
'and'                 => 'ja',
'othercontribs'       => 'Perustuu työlle, jonka teki $1.',
'others'              => 'muut',
'siteusers'           => '{{GRAMMAR:genitive|{{SITENAME}}}} käyttäjä(t) $1',
'creditspage'         => 'Sivun tekijäluettelo',
'nocredits'           => 'Tämän sivun tekijäluettelotietoja ei löydy.',

# Spam protection
#
'spamprotectiontitle' => 'Mainossuodatin',
'spamprotectiontext'  => 'Mainossuodatin on estänyt sivun tallentamisen. Syynä on todennäköisimmin {{GRAMMAR:genitive|{{SITENAME}}}} ulkopuolelle osoittava linkki.',
'spamprotectionmatch' => 'Teksti, joka ei läpäissyt mainossuodatinta: $1',
'subcategorycount'    => 'Tällä luokalla on {{PLURAL:$1|yksi alaluokka|$1 alaluokkaa}}.',
'categoryarticlecount'=> 'Tässä luokassa on {{PLURAL:$1|yksi sivu|$1 sivua}}.',
'category-media-count'=> 'Tässä luokassa on {{PLURAL:$1|yksi tiedosto|$1 tiedostoa}}.',
'listingcontinuesabbrev' => ' jatkuu',
'spambot_username'    => 'MediaWikin mainospoistaja',
'spam_reverting'      => 'Palautettu viimeisimpään versioon, joka ei sisällä linkkejä kohteeseen $1.',
'spam_blanking'       => 'Kaikki versiot sisälsivät linkkejä kohteeseen $1. Sivu tyhjennety.',


# Info page
'infosubtitle'        => 'Tietoja sivusta',
'numedits'            => 'Sivun muokkausten määrä: $1',
'numtalkedits'        => 'Keskustelusivun muokkausten määrä: $1',
'numwatchers'         => 'Tarkkailijoiden määrä: $1',
'numauthors'          => 'Sivun erillisten kirjoittajien määrä: $1',
'numtalkauthors'      => 'Keskustelusivun erillisten kirjoittajien määrä: $1',

# Math options
'mw_math_png'         => 'Näytä aina PNG:nä',
'mw_math_simple'      => 'Näytä HTML:nä, jos yksinkertainen, muuten PNG:nä',
'mw_math_html'        => 'Näytä HTML:nä, jos mahdollista, muuten PNG:nä',
'mw_math_source'      => 'Näytä TeX-muodossa (tekstiselaimille)',
'mw_math_modern'      => 'Suositus nykyselaimille',
'mw_math_mathml'      => 'Näytä MathML:nä jos mahdollista (kokeellinen)',

# Patrolling
'markaspatrolleddiff'   => 'Merkitse tarkastetuksi',
'markaspatrolledtext'   => 'Merkitse muokkaus tarkastetuksi',
'markedaspatrolled'     => 'Tarkastettu',
'markedaspatrolledtext' => 'Valittu versio on tarkastettu.',
'rcpatroldisabled'      => 'Tuoreiden muutosten tarkastustoiminto ei ole käytössä',
'rcpatroldisabledtext'  => 'Tuoreiden muutosten tarkastustoiminto ei ole käytössä.',
'markedaspatrollederror'     => 'Muutoksen merkitseminen tarkastetuksi epäonnistui.',
'markedaspatrollederrortext' => 'Tarkastetuksi merkittävää versiota ei ole määritelty.',
'markedaspatrollederror-noautopatrol' => 'Et voi merkitä omia muutoksiasi tarkastetuiksi.',


'common.css'          => '/* Tämä sivu sisältää koko sivustoa muuttavia tyylejä. */',
'monobook.css'        => '/* Tämä sivu sisältää Monobook-ulkoasua muuttavia tyylejä. */',

'common.js'           => '/* Tämän sivun koodi liitetään jokaiseen sivulataukseen */',
'monobook.js'         => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Keyboard access keys for power users
'accesskey-ca-nstab-project' => 'c',

# Tooltip help for the actions
'tooltip-ca-addsection'         => 'Lisää kommentti tälle sivulle',
'tooltip-ca-delete'             => 'Poista tämä sivu',
'tooltip-ca-edit'               => 'Muokkaa tätä sivua',
'tooltip-ca-history'            => 'Sivun aikaisemmat versiot',
'tooltip-ca-move'               => 'Siirrä tämä sivu',
'tooltip-ca-nstab-category'     => 'Näytä luokkasivu',
'tooltip-ca-nstab-help'         => 'Näytä ohjesivu',
'tooltip-ca-nstab-image'        => 'Näytä tiedostosivu',
'tooltip-ca-nstab-main'         => 'Näytä sisältösivu',
'tooltip-ca-nstab-media'        => 'Näytä mediasivu',
'tooltip-ca-nstab-mediawiki'    => 'Näytä järjestelmäviesti',
'tooltip-ca-nstab-project'      => 'Näytä projektisivu',
'tooltip-ca-nstab-special'      => 'Tämä on toimintosivu',
'tooltip-ca-nstab-template'     => 'Näytä malline',
'tooltip-ca-nstab-user'         => 'Näytä käyttäjäsivu',
'tooltip-ca-protect'            => 'Suojaa tämä sivu',
'tooltip-ca-talk'               => 'Keskustele sisällöstä',
'tooltip-ca-undelete'           => 'Palauta tämä sivu',
'tooltip-ca-unwatch'            => 'Poista tämä sivu tarkkailulistaltasi',
'tooltip-ca-viewsource'         => 'Näytä sivun lähdekoodi',
'tooltip-ca-watch'              => 'Lisää tämä sivu tarkkailulistallesi',
'tooltip-n-currentevents'       => 'Taustatietoa tämänhetkisistä tapahtumista',
'tooltip-n-help'                => 'Ohjeita',
'tooltip-n-mainpage'            => 'Mene etusivulle',
'tooltip-n-portal'              => 'Keskustelua projektista',
'tooltip-n-randompage'          => 'Avaa satunnainen sivu',
'tooltip-n-recentchanges'       => 'Lista tuoreista muutoksista',
'tooltip-n-sitesupport'         => 'Tue sivuston toimintaa',
'tooltip-p-logo'                => 'Etusivu',
'tooltip-pt-anonlogin'          => 'Kirjaudu sisään tai luo tunnus',
'tooltip-pt-anontalk'           => 'Keskustelu tämän IP-osoitteen muokkauksista',
'tooltip-pt-anonuserpage'       => 'IP-osoitteesi käyttäjäsivu',
'tooltip-pt-login'              => 'Kirjaudu sisään tai luo tunnus',
'tooltip-pt-logout'             => 'Kirjaudu ulos',
'tooltip-pt-mycontris'          => 'Lista omista muokkauksista',
'tooltip-pt-mytalk'             => 'Oma keskustelusivu',
'tooltip-pt-preferences'        => 'Omat asetukset',
'tooltip-pt-userpage'           => 'Oma käyttäjäsivu',
'tooltip-pt-watchlist'          => 'Lista sivuista, joiden muokkauksia tarkkailet',
'tooltip-t-contributions'       => 'Näytä lista tämän käyttäjän muokkauksista',
'tooltip-t-emailuser'           => 'Lähetä sähköpostia tälle käyttäjälle',
'tooltip-t-recentchangeslinked' => 'Viimeisimmät muokkaukset sivuissa, joille viitataan tältä sivulta',
'tooltip-t-specialpages'        => 'Näytä toimintosivut',
'tooltip-t-upload'              => 'Lisää kuvia tai muita mediatiedostoja',
'tooltip-t-whatlinkshere'       => 'Lista sivuista, jotka viittavat tänne',
'tooltip-compareselectedversions' => 'Vertaile valittuja versioita',
'tooltip-diff'                    => 'Näytä tehdyt muutokset',
'tooltip-feed-atom'               => 'Atom-syöte tälle sivulle',
'tooltip-feed-rss'                => 'RSS-syöte tälle sivulle',
'tooltip-minoredit'               => 'Merkitse tämä pieneksi muutokseksi',
'tooltip-preview'                 => 'Esikatsele muokkausta ennen tallennusta',
'tooltip-save'                    => 'Tallenna muokkaukset',
'tooltip-search'                  => 'Etsi {{GRAMMAR:elative|{{SITENAME}}}}',
'tooltip-watch'                   => 'Lisää tämä sivu tarkkailulistaan',

# Patrol log
'patrol-log-page' => 'Muutostentarkastusloki',
'patrol-log-line' => 'merkitsi sivun $2 muutoksen $1 tarkastetuksi $3',
'patrol-log-auto' => '(automaattinen)',
'patrol-log-diff' => 'r$1',


# image deletion
'deletedrevision'     => 'Poistettiin vanha versio $1.',

# browsing diffs
'previousdiff'        => '← Edellinen muutos',
'nextdiff'            => 'Seuraava muutos →',

'imagemaxsize'        => 'Rajoita kuvien koko kuvien kuvaussivuilla arvoon:',
'thumbsize'           => 'Pikkukuvien koko:',
'showbigimage'        => 'Lataa korkeatarkkuuksinen versio ($1×$2, $3 KiB)',

'newimages'           => 'Uudet kuvat',
'showhidebots'        => '($1 botit)',
'noimages'            => 'Ei uusia kuvia.',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Käyttäjä:',
'speciallogtitlelabel'=> 'Kohde:',

'passwordtooshort'    => 'Salasanasi on liian lyhyt. Salasanan pitää olla vähintään $1 merkkiä pitkä.',

# Media Warning
'mediawarning'        => '\'\'\'Varoitus\'\'\': Tämä tiedosto saattaa sisältää vahingollista koodia, ja suorittamalla sen järjestelmäsi voi muuttua epäluotettavaksi.<hr />',

'fileinfo'            => '$1 KiB, MIME-tyyppi: <code>$2</code>',


# Metadata
'metadata'            => 'Sisältökuvaukset',
'metadata-help'       => 'Tämä tiedosto sisältää esimerkiksi kuvanlukijan, digikameran tai kuvankäsittelyohjelman lisäämiä lisätietoja. Kaikki tiedot eivät enää välttämättä vastaa todellisuutta, jos kuvaa on muokattu sen alkuperäisen luonnin jälkeen.

This file contains additional information, probably added from the digital camera or scanner used to create or digitize it. If the file has been modified from its original state, some details may not fully reflect the modified image.',
'metadata-expand'     => 'Näytä kaikki sisältökuvaukset',
'metadata-collapse'   => 'Näytä vain tärkeimmät sisältökuvaukset',
'metadata-fields'     => 'Seuraavat kentät ovat esillä kuvasivulla, kun sisältötietotaulukko on pienennettynä.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',


# Exif tags
'exif-imagewidth'     =>'Leveys',
'exif-imagelength'    =>'Korkeus',
'exif-bitspersample'  =>'Bittiä komponentissa',
'exif-compression'    =>'Pakkaustapa',
'exif-photometricinterpretation' =>'Kuvapisteen koostumus',
'exif-orientation'               =>'Suunta',
'exif-samplesperpixel'           =>'Komponenttien lukumäärä',
'exif-planarconfiguration'       =>'Tiedon järjestely',
'exif-ycbcrsubsampling'          =>'Y:n ja C:n alinäytteistyssuhde',
'exif-ycbcrpositioning'          =>'Y:n ja C:n asemointi',
'exif-xresolution'    =>'Kuvan resoluutio leveyssuunnassa',
'exif-yresolution'    =>'Kuvan resoluutio korkeussuunnassa',
'exif-resolutionunit' =>'Resoluution yksikkö X- ja Y-suunnassa',
'exif-stripoffsets'   =>'Kuvatiedon sijainti',
'exif-rowsperstrip'   =>'Kaistan rivien lukumäärä',
'exif-stripbytecounts'=>'Tavua pakatussa kaistassa',
'exif-jpeginterchangeformat'       =>'Etäisyys JPEG SOI:hin',
'exif-jpeginterchangeformatlength' =>'JPEG-tiedon tavujen lukumäärä',
'exif-transferfunction'            =>'Siirtofunktio',
'exif-whitepoint'                  =>'Valkoisen pisteen väriarvot',
'exif-primarychromaticities'       =>'Päävärien väriarvot',
'exif-ycbcrcoefficients'           =>'Väriavaruuden muuntomatriisin kertoimet',
'exif-referenceblackwhite'         =>'Musta-valkoparin vertailuarvot',
'exif-datetime'                    =>'Viimeksi muokattu',
'exif-imagedescription'            =>'Kuvan nimi',
'exif-make'           =>'Kameran valmistaja',
'exif-model'          =>'Kameran malli',
'exif-software'       =>'Käytetty ohjelmisto',
'exif-artist'         =>'Tekijä',
'exif-copyright'      =>'Tekijänoikeuden omistaja',
'exif-exifversion'    =>'Exif-versio',
'exif-flashpixversion'=>'Tuettu Flashpix-versio',
'exif-colorspace'     =>'Väriavaruus',
'exif-componentsconfiguration' =>'Kunkin komponentin määritelmä',
'exif-compressedbitsperpixel'  =>'Kuvan pakkaustapa',
'exif-pixelydimension'         =>'Käyttökelpoinen kuvan leveys',
'exif-pixelxdimension'         =>'Käyttökelpoinen kuvan korkeus',
'exif-makernote'               =>'Valmistajan merkinnät',
'exif-usercomment'             =>'Käyttäjän kommentit',
'exif-relatedsoundfile'        =>'Liitetty äänitiedosto',
'exif-datetimeoriginal'        =>'Luontipäivämäärä',
'exif-datetimedigitized'       =>'Digitointipäivämäärä',
'exif-subsectime'              =>'Aikaleiman sekunninosat',
'exif-subsectimeoriginal'      =>'Luontiaikaleiman sekunninosat',
'exif-subsectimedigitized'     =>'Digitointiaikaleiman sekunninosat',
'exif-exposuretime'            =>'Valotusaika',
'exif-exposuretime-format'     => '$1 s ($2)',
'exif-fnumber'                 =>'Aukkosuhde',
'exif-fnumber-format'          =>'f/$1',
'exif-exposureprogram'         =>'Valotusohjelma',
'exif-spectralsensitivity'     =>'Värikirjoherkkyys',
'exif-isospeedratings'         =>'Herkkyys (ISO)',
'exif-oecf'                    =>'Optoelektroninen muuntokerroin',
'exif-shutterspeedvalue'       =>'Suljinaika',
'exif-aperturevalue'           =>'Aukko',
'exif-brightnessvalue'         =>'Kirkkaus',
'exif-exposurebiasvalue'       =>'Valotuksen korjaus',
'exif-maxaperturevalue'        =>'Suurin aukko',
'exif-subjectdistance'         =>'Kohteen etäisyys',
'exif-meteringmode'            =>'Mittaustapa',
'exif-lightsource'             =>'Valolähde',
'exif-flash'                   =>'Salama',
'exif-focallength'             =>'Linssin polttoväli',
'exif-focallength-format'      =>'$1 mm',
'exif-subjectarea'             =>'Kohteen ala',
'exif-flashenergy'             =>'Salaman teho',
'exif-spatialfrequencyresponse'=>'Tilataajuusvaste',
'exif-focalplanexresolution'   =>'Tarkennustason X-resoluutio',
'exif-focalplaneyresolution'   =>'Tarkennustason Y-resoluutio',
'exif-focalplaneresolutionunit'=>'Tarkennustason resoluution yksikkö',
'exif-subjectlocation'=>'Kohteen sijainti',
'exif-exposureindex'  =>'Valotusindeksi',
'exif-sensingmethod'  =>'Mittausmenetelmä',
'exif-filesource'     =>'Tiedostolähde',
'exif-scenetype'      =>'Kuvatyyppi',
'exif-cfapattern'     =>'CFA-kuvio',
'exif-customrendered' =>'Muokattu kuvankäsittely',
'exif-exposuremode'   =>'Valotustapa',
'exif-whitebalance'   =>'Valkotasapaino',
'exif-digitalzoomratio'      =>'Digitaalinen suurennoskerroin',
'exif-focallengthin35mmfilm' =>'35 mm:n filmiä vastaava polttoväli',
'exif-scenecapturetype'      =>'Kuvan kaappaustapa',
'exif-gaincontrol'    =>'Kuvasäätö',
'exif-contrast'       =>'Kontrasti',
'exif-saturation'     =>'Värikylläisyys',
'exif-sharpness'      =>'Terävyys',
'exif-devicesettingdescription' =>'Laitteen asetuskuvaus',
'exif-subjectdistancerange'     =>'Kohteen etäisyysväli',
'exif-imageuniqueid'  =>'Kuvan yksilöivä tunniste',
'exif-gpsversionid'   =>'GPS-muotoilukoodin versio',
'exif-gpslatituderef' =>'Pohjoinen tai eteläinen leveysaste',
'exif-gpslatitude'    =>'Leveysaste',
'exif-gpslongituderef'=>'Itäinen tai läntinen pituusaste',
'exif-gpslongitude'   =>'Pituusaste',
'exif-gpsaltituderef' =>'Korkeuden vertailukohta',
'exif-gpsaltitude'    =>'Korkeus',
'exif-gpstimestamp'   =>'GPS-aika (atomikello)',
'exif-gpssatellites'  =>'Mittaukseen käytetyt satelliitit',
'exif-gpsstatus'      =>'Vastaanottimen tila',
'exif-gpsmeasuremode' =>'Mittaustila',
'exif-gpsdop'         =>'Mittatarkkuus',
'exif-gpsspeedref'    =>'Nopeuden yksikkö',
'exif-gpsspeed'       =>'GPS-vastaanottimen nopeus',
'exif-gpstrackref'    =>'Liikesuunnan vertailukohta',
'exif-gpstrack'       =>'Liikesuunta',
'exif-gpsimgdirectionref' =>'Kuvan suunnan vertailukohta',
'exif-gpsimgdirection'    =>'Kuvan suunta',
'exif-gpsmapdatum'        =>'Käytetty geodeettinen maanmittaustieto',
'exif-gpsdestlatituderef' =>'Loppupisteen leveysasteen vertailukohta',
'exif-gpsdestlatitude'    =>'Loppupisteen leveysaste',
'exif-gpsdestlongituderef'=>'Loppupisteen pituusasteen vertailukohta',
'exif-gpsdestlongitude'   =>'Loppupisteen pituusaste',
'exif-gpsdestbearingref'  =>'Loppupisteen suuntiman vertailukohta',
'exif-gpsdestbearing'     =>'Loppupisteen suuntima',
'exif-gpsdestdistanceref' =>'Loppupisteen etäisyyden vertailukohta',
'exif-gpsdestdistance'    =>'Loppupisteen etäisyys',
'exif-gpsprocessingmethod'=>'GPS-käsittelymenetelmän nimi',
'exif-gpsareainformation' =>'GPS-alueen nimi',
'exif-gpsdatestamp'       =>'GPS-päivämäärä',
'exif-gpsdifferential'    =>'GPS-differentiaalikorjaus',

# Exif attributes

'exif-compression-1'  => 'Pakkaamaton',
'exif-compression-6'  => 'JPEG',

'exif-unknowndate'    => 'Tuntematon päiväys',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1'  => 'Normaali', // 0th row: top; 0th column: left
'exif-orientation-2'  => 'Käännetty vaakasuunnassa', // 0th row: top; 0th column: right
'exif-orientation-3'  => 'Käännetty 180°', // 0th row: bottom; 0th column: right
'exif-orientation-4'  => 'Käännetty pystysuunnassa', // 0th row: bottom; 0th column: left
'exif-orientation-5'  => 'Käännetty 90° vastapäivään ja pystysuunnassa', // 0th row: left; 0th column: top
'exif-orientation-6'  => 'Käännetty 90° myötäpäivään', // 0th row: right; 0th column: top
'exif-orientation-7'  => 'Käännetty 90° myötäpäivään ja pystysuunnassa', // 0th row: right; 0th column: bottom
'exif-orientation-8'  => 'Käännetty 90° vastapäivään', // 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'chunky format',
'exif-planarconfiguration-2' => 'planar format',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'ei ole',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Ei määritelty',
'exif-exposureprogram-1' => 'Käsinsäädetty',
'exif-exposureprogram-2' => 'Perusohjelma',
'exif-exposureprogram-3' => 'Aukon etuoikeus',
'exif-exposureprogram-4' => 'Suljinajan etuoikeus',
'exif-exposureprogram-5' => 'Luova ohjelma (painotettu syvyysterävyyttä)',
'exif-exposureprogram-6' => 'Toimintaohjelma (painotettu nopeaa suljinaikaa)',
'exif-exposureprogram-7' => 'Muotokuvatila (lähikuviin, joissa tausta on epätarkka)',
'exif-exposureprogram-8' => 'Maisematila (maisemakuviin, joissa tausta on tarkka)',

'exif-subjectdistance-value' => '$1 metriä',

'exif-meteringmode-0' => 'Tuntematon',
'exif-meteringmode-1' => 'Keskiarvo',
'exif-meteringmode-2' => 'Keskustapainotteinen keskiarvo',
'exif-meteringmode-3' => 'Piste',
'exif-meteringmode-4' => 'Monipiste',
'exif-meteringmode-5' => 'Kuvio',
'exif-meteringmode-6' => 'Osittainen',
'exif-meteringmode-255' => 'Muu',

'exif-lightsource-0'  => 'Tuntematon',
'exif-lightsource-1'  => 'Päivänvalo',
'exif-lightsource-2'  => 'Loisteputki',
'exif-lightsource-3'  => 'Hehkulamppu (keinovalo)',
'exif-lightsource-4'  => 'Salama',
'exif-lightsource-9'  => 'Hyvä sää',
'exif-lightsource-10' => 'Pilvinen sää',
'exif-lightsource-11' => 'Varjoinen',
'exif-lightsource-12' => 'Päivänvaloloisteputki (D 5700 – 7100K)',
'exif-lightsource-13' => 'Päivänvalkoinen loisteputki (N 4600 – 5400K)',
'exif-lightsource-14' => 'Kylmä valkoinen loisteputki (W 3900 – 4500K)',
'exif-lightsource-15' => 'Valkoinen loisteputki (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Oletusvalo A',
'exif-lightsource-18' => 'Oletusvalo B',
'exif-lightsource-19' => 'Oletusvalo C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISO-studiohehkulamppu',
'exif-lightsource-255'=> 'Muu valonlähde',

'exif-focalplaneresolutionunit-2' => 'tuumaa',

'exif-sensingmethod-1' => 'Määrittelemätön',
'exif-sensingmethod-2' => 'Yksisiruinen värikenno',
'exif-sensingmethod-3' => 'Kaksisiruinen värikenno',
'exif-sensingmethod-4' => 'Kolmisiruinen värikenno',
'exif-sensingmethod-5' => 'Sarjavärikenno',
'exif-sensingmethod-7' => 'Trilineaarikenno',
'exif-sensingmethod-8' => 'Sarjalineaarivärikenno',

'exif-filesource-3'   => 'DSC',

'exif-scenetype-1'    => 'Suoraan valokuvattu kuva',

'exif-customrendered-0' => 'Normaali käsittely',
'exif-customrendered-1' => 'Muokattu käsittely',

'exif-exposuremode-0' => 'Automaattinen valotus',
'exif-exposuremode-1' => 'Käsinsäädetty valotus',
'exif-exposuremode-2' => 'Automaattinen haarukointi',

'exif-whitebalance-0' => 'Automaattinen valkotasapaino',
'exif-whitebalance-1' => 'Käsinsäädetty valkotasapaino',

'exif-scenecapturetype-0' => 'Perus',
'exif-scenecapturetype-1' => 'Maisema',
'exif-scenecapturetype-2' => 'Henkilökuva',
'exif-scenecapturetype-3' => 'Yökuva',

'exif-gaincontrol-0'  => 'Ei ole',
'exif-gaincontrol-1'  => 'Matala ylävahvisus',
'exif-gaincontrol-2'  => 'Korkea ylävahvistus',
'exif-gaincontrol-3'  => 'Matala alavahvistus',
'exif-gaincontrol-4'  => 'Korkea alavahvistus',

'exif-contrast-0'     => 'Normaali',
'exif-contrast-1'     => 'Pehmeä',
'exif-contrast-2'     => 'Kova',

'exif-saturation-0'   => 'Normaali',
'exif-saturation-1'   => 'Alhainen värikylläisyys',
'exif-saturation-2'   => 'Korkea värikylläisyys',

'exif-sharpness-0'    => 'Normaali',
'exif-sharpness-1'    => 'Pehmeä',
'exif-sharpness-2'    => 'Kova',
'exif-subjectdistancerange-0' => 'Tuntematon',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Lähikuva',
'exif-subjectdistancerange-3' => 'Kaukokuva',

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n'  => 'Pohjoista leveyttä',
'exif-gpslatitude-s'  => 'Eteläistä leveyttä',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Itäistä pituutta',
'exif-gpslongitude-w' => 'Läntistä pituutta',

'exif-gpsstatus-a'    => 'Mittaus käynnissä',
'exif-gpsstatus-v'    => 'Ristiinmittaus',

'exif-gpsmeasuremode-2' => '2-ulotteinen mittaus',
'exif-gpsmeasuremode-3' => '3-ulotteinen mittaus',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k'     => 'km/h',
'exif-gpsspeed-m'     => 'mailia tunnissa',
'exif-gpsspeed-n'     => 'solmua',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Todellinen suunta',
'exif-gpsdirection-m' => 'Magneettinen suunta',

# external editor support
'edit-externally'     => 'Muokkaa tätä tiedostoa ulkoisessa sovelluksessa',
'edit-externally-help'=> 'Katso [http://meta.wikimedia.org/wiki/Help:External_editors ohjeet], jos haluat lisätietoja.',

'recentchangesall'    => "kaikki", // unsure
'imagelistall'        => "kaikki", // unsure
'watchlistall1'       => 'kaikkia',
'watchlistall2'       => ', koko historia',
'namespacesall'       => 'kaikki',

# E-mail address confirmation
'confirmemail'        => 'Varmenna sähköpostiosoite',
'confirmemail_noemail'=> 'Sinulla ei ole kelvollista sähköpostiosoitetta [[Special:Preferences|asetuksissasi]].',
'confirmemail_text'   => 'Tämä wiki vaatii sähköpostiosoitteen varmentamisen, ennen kuin voit käyttää sähköpostitoimintoja. Lähetä alla olevasta painikkeesta varmennusviesti osoitteeseesi. Viesti sisältää linkin, jonka avaamalla varmennat sähköpostiosoitteesi.',
'confirmemail_pending'=> '<div class="error">Varmennusviesti on jo lähetetty. Jos loit tunnuksen äskettäin, odota muutama minuutti viestin saapumista, ennen kuin yrität uudelleen.</div>',
'confirmemail_send'   => 'Lähetä varmennusviesti',
'confirmemail_sent'   => 'Varmennusviesti lähetetty.',
'confirmemail_oncreate' => 'Varmennusviesti lähetettiin sähköpostiosoitteeseesi. Varmennuskoodia ei tarvita sisäänkirjautumiseen, mutta se täytyy antaa, ennen kuin voit käyttää sähköpostitoimintoja tässä wikissä.',
'confirmemail_sendfailed' => 'Varmennusviestin lähettäminen epäonnistui. Tarkista, onko osoitteessa kiellettyjä merkkejä.

Postitusohjelma palautti: $1',
'confirmemail_invalid'    => 'Varmennuskoodi ei kelpaa. Koodi on voinut vanhentua.',
'confirmemail_needlogin'  => 'Sinun täytyy $1, jotta voisit varmistaa sähköpostiosoitteesi.',
'confirmemail_success'    => 'Sähköpostiosoitteesi on nyt varmennettu. Voit kirjautua sisään.',
'confirmemail_loggedin'   => 'Sähköpostiosoitteesi on nyt varmennettu.',
'confirmemail_error'  => 'Jokin epäonnistui varmennnuksen tallentamisessa.',
'confirmemail_subject'=> '{{GRAMMAR:genitive|{{SITENAME}}}} sähköpostiosoitteen varmennus',
'confirmemail_body'   => 'Joku IP-osoitteesta $1 on rekisteröinyt {{GRAMMAR:inessive|{{SITENAME}}}} tunnuksen $2 tällä sähköpostiosoitteella.

Varmenna, että tämä tunnus kuuluu sinulle avamaalla seuraava linkki selaimellasi:

$3

Jos tämä tunnus ei ole sinun, ÄLÄ seuraa linkkiä. Varmennuskoodi vanhenee $4.',


# Inputbox extension, may be useful in other contexts as well
'tryexact'            => 'Koita tarkkaa osumaa',
'searchfulltext'      => 'Etsi koko tekstiä',
'createarticle'       => 'Luo sivu',

# Scary transclusion
'scarytranscludedisabled' => '[Wikienvälinen sisällytys ei ole käytössä]',
'scarytranscludefailed'   => '[Mallineen hakeminen epäonnistui: $1]', // kauhee?
'scarytranscludetoolong'  => '[Verkko-osoite on liian pitkä]',

# Trackbacks
'trackbackbox' => '<div id="mw_trackbacks">Artikkelin trackbackit:<br />$1</div>',
'trackbackremove' => ' ([$1 poista])',
'trackbacklink' => 'Trackback',
'trackbackdeleteok' => 'Trackback poistettiin.',

# delete conflict

'deletedwhileediting' => '<center>\'\'\'Varoitus\'\'\': Tämä sivu on poistettu sen jälkeen, kun aloitit sen muokkaamisen!</center>',
'confirmrecreate'     => 'Käyttäjä \'\'\'[[User:$1|$1]]\'\'\' ([[User_talk:$1|keskustelu]]) poisti sivun sen jälkeen, kun aloit muokata sitä. Syy oli:
: \'\'$2\'\'
Ole hyvä ja varmista, että haluat luoda sivun uudelleen.',
'recreate' => 'Luo uudelleen',
'tooltip-recreate'    => 'Luo sivu uudelleen',

'unit-pixel'          => ' px',

# HTML dump
'redirectingto'       => 'Uudelleenohjataan sivulle [[$1]]...',

# action=purge
'confirm_purge'       => "Poistetaanko tämän sivun välimuistikopiot?\n\n$1",
'confirm_purge_button'=> 'Poista',

'youhavenewmessagesmulti' => 'Sinulla on uusia viestejä sivuilla $1',

'searchcontaining'    => 'Etsi artikkeleita, jotka sisältävät ”$1”.',
'searchnamed'         => 'Etsi artikkeleita, joiden nimi on ”$1”.',
'articletitles'       => 'Artikkelit, jotka alkavat merkkijonolla ”$1”',
'hideresults'         => 'Piilota tulokset',

# DISPLAYTITLE
'displaytitle'        => '(Linkitä tämä sivu merkinnällä [[$1]])',

'loginlanguagelabel'  => 'Kieli: $1',

# Multipage image navigation
'imgmultipageprev'    => '← edellinen sivu',
'imgmultipagenext'    => 'seuraava sivu →',
'imgmultigo'          => 'Mene!',
'imgmultigotopre'     => 'Mene sivulle',

# Table pager
'ascending_abbrev'    => 'nouseva',
'descending_abbrev'   => 'laskeva',
'table_pager_next'    => 'Seuraava sivu',
'table_pager_prev'    => 'Edellinen sivu',
'table_pager_first'   => 'Ensimmäinen sivu',
'table_pager_last'    => 'Viimeinen sivu',
'table_pager_limit'   => 'Näytä $1 nimikettä sivulla',
'table_pager_limit_submit' => 'Mene',
'table_pager_empty'   => 'Ei tuloksia',

# Auto-summaries
'autosumm-blank'      => 'Ak: Sivu tyhjennettiin',
'autosumm-replace'    => 'Ak: Sivun sisältö korvattiin sisällöllä ”$1”',
'autoredircomment'    => 'Ak: Uudelleenohjaus sivulle [[$1]]',
'autosumm-new'        => 'Ak: Uusi sivu: $1',

# Page history in an feed (RSS / Atom)
'feed-invalid'        => 'Virheellinen syötetyyppi.',
'history-feed-title'  => 'Muutoshistoria',
'history-feed-description'    => 'Tämän sivun muutoshistoria',
'history-feed-item-nocomment' => '$1 ($2)',
'history-feed-empty'  => 'Pyydettyä sivua ei ole olemassa. 
Se on saatettu poistaa wikistä tai nimetä uudelleen. 
Kokeile [[Special:Search|hakua]] löytääksesi asiaan liittyviä sivuja.',

'sp-newimages-showfrom' => 'Näytä uudet kuvat alkaen $1',

# Size units
'size-bytes'        => '$1 B',
'size-kilobytes'    => '$1 KiB',
'size-megabytes'    => '$1 MiB',
'size-gigabytes'    => '$1 GiB',


);
?>
