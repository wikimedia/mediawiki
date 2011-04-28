<?php
/** Finnish (Suomi)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Agony
 * @author Centerlink
 * @author Cimon Avaro
 * @author Crt
 * @author Jaakonam
 * @author Jack Phoenix
 * @author Mobe
 * @author Nedergard
 * @author Nike
 * @author Ochs
 * @author Olli
 * @author Pxos
 * @author Silvonen
 * @author Str4nd
 * @author Tarmo
 * @author Wix
 * @author ZeiP
 * @author לערי ריינהארט
 */

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Toiminnot',
	NS_TALK             => 'Keskustelu',
	NS_USER             => 'Käyttäjä',
	NS_USER_TALK        => 'Keskustelu_käyttäjästä',
	NS_PROJECT_TALK     => 'Keskustelu_{{GRAMMAR:elative|$1}}',
	NS_FILE             => 'Tiedosto',
	NS_FILE_TALK        => 'Keskustelu_tiedostosta',
	NS_MEDIAWIKI        => 'Järjestelmäviesti',
	NS_MEDIAWIKI_TALK   => 'Keskustelu_järjestelmäviestistä',
	NS_TEMPLATE         => 'Malline',
	NS_TEMPLATE_TALK    => 'Keskustelu_mallineesta',
	NS_HELP             => 'Ohje',
	NS_HELP_TALK        => 'Keskustelu_ohjeesta',
	NS_CATEGORY         => 'Luokka',
	NS_CATEGORY_TALK    => 'Keskustelu_luokasta',
);

$namespaceAliases = array(
	'Kuva' => NS_FILE,
	'Keskustelu_kuvasta' => NS_FILE_TALK,
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

$magicWords = array(
	'redirect'              => array( '0', '#OHJAUS', '#UUDELLEENOHJAUS', '#REDIRECT' ),
	'notoc'                 => array( '0', '__EISISLUETT__', '__NOTOC__' ),
	'forcetoc'              => array( '0', '__SISLUETTPAKOTUS__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__SISÄLLYSLUETTELO__', '__TOC__' ),
	'noeditsection'         => array( '0', '__EIOSIOMUOKKAUSTA__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__EIOTSIKKOA__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'KULUVAKUU', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'KULUVAKUUNIMI', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'KULUVAKUUNIMIGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'KULUVAKUUNIMILYHYT', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'KULUVAPÄIVÄ', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'KULUVAPÄIVÄ2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'KULUVAPÄIVÄNIMI', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'KULUVAVUOSI', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'KULUVAAIKA', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'KULUVATUNTI', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'PAIKALLINENKUU', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'        => array( '1', 'PAIKALLINENKUUNIMI', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'PAIKALLINENKUUNIMIGEN', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'PAIKALLINENKUUNIMILYHYT', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'PAIKALLINENPÄIVÄ', 'LOCALDAY' ),
	'localday2'             => array( '1', 'PAIKALLINENPÄIVÄ2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'PAIKALLINENPÄIVÄNIMI', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'PAIKALLINENVUOSI', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'PAIKALLINENAIKA', 'LOCALTIME' ),
	'localhour'             => array( '1', 'PAIKALLINENTUNTI', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'SIVUMÄÄRÄ', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ARTIKKELIMÄÄRÄ', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'TIEDOSTOMÄÄRÄ', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'KÄYTTÄJÄMÄÄRÄ', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'MUOKKAUSMÄÄRÄ', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'SIVUHAKUMÄÄRÄ', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'SIVUNIMI', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'SIVUNIMIE', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'NIMIAVARUUS', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'NIMIAVARUUSE', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'KESKUSTELUAVARUUS', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'KESKUSTELUAVARUUSE', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'AIHEAVARUUS', 'ARTIKKELIAVARUUS', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'AIHEAVARUUSE', 'ARTIKKELIAVARUUSE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'KOKOSIVUNIMI', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'KOKOSIVUNIMIE', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'ALASIVUNIMI', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'ALASIVUNIMIE', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'KANTASIVUNIMI', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'KANTASIVUNIMIE', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'KESKUSTELUSIVUNIMI', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'KESKUSTELUSIVUNIMIE', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'AIHESIVUNIMI', 'ARTIKKELISIVUNIMI', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'AIHESIVUNIMIE', 'ARTIKKELISIVUNIMIE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'subst'                 => array( '0', 'VASTINE:', 'SUBST:' ),
	'img_thumbnail'         => array( '1', 'pienoiskuva', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'pienoiskuva=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'oikea', 'right' ),
	'img_left'              => array( '1', 'vasen', 'left' ),
	'img_none'              => array( '1', 'tyhjä', 'none' ),
	'img_center'            => array( '1', 'keskitetty', 'keski', 'center', 'centre' ),
	'img_framed'            => array( '1', 'kehys', 'kehystetty', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'kehyksetön', 'frameless' ),
	'img_page'              => array( '1', 'sivu=$1', 'sivu $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'yläoikea', 'yläoikea=$1', 'yläoikea $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'reunus', 'border' ),
	'img_baseline'          => array( '1', 'perustaso', 'baseline' ),
	'img_sub'               => array( '1', 'alaindeksi', 'sub' ),
	'img_super'             => array( '1', 'yläindeksi', 'super', 'sup' ),
	'img_top'               => array( '1', 'ylös', 'ylhäällä', 'top' ),
	'img_middle'            => array( '1', 'keskellä', 'middle' ),
	'img_bottom'            => array( '1', 'alas', 'alhaalla', 'bottom' ),
	'img_link'              => array( '1', 'linkki=$1', 'link=$1' ),
	'sitename'              => array( '1', 'SIVUSTONIMI', 'SITENAME' ),
	'ns'                    => array( '0', 'NA:', 'NS:' ),
	'localurl'              => array( '0', 'PAIKALLINENOSOITE:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'PAIKALLINENOSOITEE:', 'LOCALURLE:' ),
	'server'                => array( '0', 'PALVELIN', 'SERVER' ),
	'servername'            => array( '0', 'PALVELINNIMI', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'SKRIPTIPOLKU', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'TAIVUTUS:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'SUKUPUOLI:', 'GENDER:' ),
	'currentweek'           => array( '1', 'KULUVAVIIKKO', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'KULUVAVIIKONPÄIVÄ', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'PAIKALLINENVIIKKO', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'PAIKALLINENVIIKONPÄIVÄ', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'VERSIOID', 'REVISIONID' ),
	'revisionday'           => array( '1', 'VERSIOPÄIVÄ', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'VERSIOPÄIVÄ2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'VERSIOKUUKAUSI', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'VERSIOVUOSI', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'VERSIOAIKALEIMA', 'REVISIONTIMESTAMP' ),
	'plural'                => array( '0', 'MONIKKO:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'TÄYSIOSOITE:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'TÄYSIOSOITEE:', 'FULLURLE:' ),
	'displaytitle'          => array( '1', 'NÄKYVÄOTSIKKO', 'DISPLAYTITLE' ),
	'currentversion'        => array( '1', 'NYKYINENVERSIO', 'CURRENTVERSION' ),
	'currenttimestamp'      => array( '1', 'KULUVAAIKALEIMA', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'PAIKALLINENAIKALEIMA', 'LOCALTIMESTAMP' ),
	'language'              => array( '0', '#KIELI:', '#LANGUAGE:' ),
	'numberofadmins'        => array( '1', 'YLLÄPITÄJÄMÄÄRÄ', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'MUOTOILELUKU', 'FORMATNUM' ),
	'defaultsort'           => array( '1', 'AAKKOSTUS:', 'OLETUSAAKKOSTUS:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'TIEDOSTOPOLKU:', 'FILEPATH:' ),
	'hiddencat'             => array( '1', '__PIILOLUOKKA__', '__HIDDENCAT__' ),
	'pagesize'              => array( '1', 'SIVUKOKO', 'PAGESIZE' ),
	'noindex'               => array( '1', '__HAKUKONEKIELTO__', '__NOINDEX__' ),
	'protectionlevel'       => array( '1', 'SUOJAUSTASO', 'PROTECTIONLEVEL' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Kaksinkertaiset_ohjaukset', 'Kaksinkertaiset_uudelleenohjaukset' ),
	'BrokenRedirects'           => array( 'Virheelliset_ohjaukset', 'Virheelliset_uudelleenohjaukset' ),
	'Disambiguations'           => array( 'Täsmennyssivut' ),
	'Userlogin'                 => array( 'Kirjaudu_sisään' ),
	'Userlogout'                => array( 'Kirjaudu_ulos' ),
	'CreateAccount'             => array( 'Luo_tunnus' ),
	'Preferences'               => array( 'Asetukset' ),
	'Watchlist'                 => array( 'Tarkkailulista' ),
	'Recentchanges'             => array( 'Tuoreet_muutokset' ),
	'Upload'                    => array( 'Tallenna', 'Lisää_tiedosto' ),
	'Listfiles'                 => array( 'Tiedostoluettelo' ),
	'Newimages'                 => array( 'Uudet_tiedostot', 'Uudet_kuvat' ),
	'Listusers'                 => array( 'Käyttäjät' ),
	'Listgrouprights'           => array( 'Käyttäjäryhmien_oikeudet' ),
	'Statistics'                => array( 'Tilastot' ),
	'Randompage'                => array( 'Satunnainen_sivu' ),
	'Lonelypages'               => array( 'Yksinäiset_sivut' ),
	'Uncategorizedpages'        => array( 'Luokittelemattomat_sivut' ),
	'Uncategorizedcategories'   => array( 'Luokittelemattomat_luokat' ),
	'Uncategorizedimages'       => array( 'Luokittelemattomat_tiedostot' ),
	'Uncategorizedtemplates'    => array( 'Luokittelemattomat_mallineet' ),
	'Unusedcategories'          => array( 'Käyttämättömät_luokat' ),
	'Unusedimages'              => array( 'Käyttämättömät_tiedostot' ),
	'Wantedpages'               => array( 'Halutuimmat_sivut' ),
	'Wantedcategories'          => array( 'Halutuimmat_luokat' ),
	'Wantedfiles'               => array( 'Halutuimmat_tiedostot' ),
	'Wantedtemplates'           => array( 'Halutuimmat_mallineet' ),
	'Mostlinked'                => array( 'Viitatuimmat_sivut' ),
	'Mostlinkedcategories'      => array( 'Viitatuimmat_luokat' ),
	'Mostlinkedtemplates'       => array( 'Viitatuimmat_mallineet' ),
	'Mostimages'                => array( 'Viitatuimmat_tiedostot' ),
	'Mostcategories'            => array( 'Luokitelluimmat_sivut' ),
	'Mostrevisions'             => array( 'Muokatuimmat_sivut' ),
	'Fewestrevisions'           => array( 'Vähiten_muokatut_sivut' ),
	'Shortpages'                => array( 'Lyhyet_sivut' ),
	'Longpages'                 => array( 'Pitkät_sivut' ),
	'Newpages'                  => array( 'Uudet_sivut' ),
	'Ancientpages'              => array( 'Kuolleet_sivut' ),
	'Deadendpages'              => array( 'Linkittömät_sivut' ),
	'Protectedpages'            => array( 'Suojatut_sivut' ),
	'Protectedtitles'           => array( 'Suojatut_sivunimet' ),
	'Allpages'                  => array( 'Kaikki_sivut' ),
	'Prefixindex'               => array( 'Etuliiteluettelo' ),
	'Ipblocklist'               => array( 'Muokkausestot' ),
	'Unblock'                   => array( 'Poista_esto' ),
	'Specialpages'              => array( 'Toimintosivut' ),
	'Contributions'             => array( 'Muokkaukset' ),
	'Emailuser'                 => array( 'Lähetä_sähköpostia' ),
	'Confirmemail'              => array( 'Varmista_sähköpostiosoite' ),
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
	'FileDuplicateSearch'       => array( 'Kaksoiskappaleiden_haku' ),
	'Unwatchedpages'            => array( 'Tarkkailemattomat_sivut' ),
	'Listredirects'             => array( 'Ohjaukset', 'Ohjaussivut', 'Uudelleenohjaukset' ),
	'Revisiondelete'            => array( 'Poista_muokkaus' ),
	'Unusedtemplates'           => array( 'Käyttämättömät_mallineet' ),
	'Randomredirect'            => array( 'Satunnainen_ohjaus', 'Satunnainen_uudelleenohjaus' ),
	'Mypage'                    => array( 'Oma_sivu' ),
	'Mytalk'                    => array( 'Oma_keskustelu' ),
	'Mycontributions'           => array( 'Omat_muokkaukset' ),
	'Listadmins'                => array( 'Ylläpitäjät' ),
	'Listbots'                  => array( 'Botit' ),
	'Popularpages'              => array( 'Suositut_sivut' ),
	'Search'                    => array( 'Haku' ),
	'Resetpass'                 => array( 'Muuta_salasana', 'Alusta_salasana' ),
	'Withoutinterwiki'          => array( 'Kielilinkittömät_sivut' ),
	'MergeHistory'              => array( 'Liitä_muutoshistoria' ),
	'Filepath'                  => array( 'Tiedostopolku' ),
	'Invalidateemail'           => array( 'Hylkää_sähköpostiosoite' ),
	'Blankpage'                 => array( 'Tyhjä_sivu' ),
	'LinkSearch'                => array( 'Linkkihaku' ),
	'DeletedContributions'      => array( 'Poistetut_muokkaukset' ),
	'Tags'                      => array( 'Merkinnät' ),
	'Activeusers'               => array( 'Aktiiviset_käyttäjät' ),
	'ComparePages'              => array( 'Vertaa_sivuja' ),
	'Badtitle'                  => array( 'Kelpaamaton_otsikko' ),
);

$linkTrail = '/^([a-zäö]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Linkkien alleviivaus',
'tog-highlightbroken'         => 'Näytä linkit puuttuville sivuille <a href="#" class="new">näin</a> (vaihtoehtoisesti näin: <a href="#" class="internal">?</a>).',
'tog-justify'                 => 'Tasaa kappaleet',
'tog-hideminor'               => 'Piilota pienet muutokset tuoreet muutokset -listasta',
'tog-hidepatrolled'           => 'Piilota tarkastetut muutokset tuoreet muutokset -listasta',
'tog-newpageshidepatrolled'   => 'Piilota tarkastetut sivut uusien sivujen listalta',
'tog-extendwatchlist'         => 'Laajenna tarkkailulista näyttämään kaikki tehdyt muutokset eikä vain viimeisimmät',
'tog-usenewrc'                => 'Käytä kehittynyttä tuoreet muutokset -listausta (JavaScript)',
'tog-numberheadings'          => 'Numeroi otsikot',
'tog-showtoolbar'             => 'Näytä työkalupalkki (JavaScript)',
'tog-editondblclick'          => 'Muokkaa sivuja kaksoisnapsautuksella (JavaScript)',
'tog-editsection'             => 'Näytä muokkauslinkit jokaisen osion yläpuolella',
'tog-editsectiononrightclick' => 'Muokkaa osioita napsauttamalla otsikkoa hiiren oikealla painikkeella (JavaScript)',
'tog-showtoc'                 => 'Näytä sisällysluettelo sivuille, joilla yli 3 otsikkoa',
'tog-rememberpassword'        => 'Muista kirjautumisen tässä selaimessa (enintään $1 {{PLURAL:$1|päivä|päivää}})',
'tog-watchcreations'          => 'Lisää luomani sivut tarkkailulistalle',
'tog-watchdefault'            => 'Lisää muokkaamani sivut tarkkailulistalle',
'tog-watchmoves'              => 'Lisää siirtämäni sivut tarkkailulistalle',
'tog-watchdeletion'           => 'Lisää poistamani sivut tarkkailulistalle',
'tog-minordefault'            => 'Muutokset ovat oletuksena pieniä',
'tog-previewontop'            => 'Näytä esikatselu muokkauskentän yläpuolella',
'tog-previewonfirst'          => 'Näytä esikatselu heti, kun muokkaus aloitetaan',
'tog-nocache'                 => 'Älä tallenna sivuja selaimen välimuistiin',
'tog-enotifwatchlistpages'    => 'Lähetä sähköpostiviesti tarkkailtujen sivujen muutoksista',
'tog-enotifusertalkpages'     => 'Lähetä sähköpostiviesti, kun käyttäjäsivun keskustelusivu muuttuu',
'tog-enotifminoredits'        => 'Lähetä sähköpostiviesti myös pienistä muokkauksista',
'tog-enotifrevealaddr'        => 'Näytä sähköpostiosoitteeni muille lähetetyissä ilmoituksissa',
'tog-shownumberswatching'     => 'Näytä sivua tarkkailevien käyttäjien määrä',
'tog-oldsig'                  => 'Nykyisen allekirjoituksen esikatselu',
'tog-fancysig'                => 'Muotoilematon allekirjoitus ilman automaattista linkkiä',
'tog-externaleditor'          => 'Käytä ulkoista tekstieditoria oletuksena. Vain kokeneille käyttäjille, vaatii selaimen asetusten muuttamista. (<span class="plainlinks">[http://www.mediawiki.org/wiki/Manual:External_editors Ohje]</span>)',
'tog-externaldiff'            => 'Käytä oletuksena ulkoista työkalua sivun eri versioiden välisten erojen tarkasteluun. Vain kokeneille käyttäjille, vaatii selaimen asetusten muuttamista. (<span class="plainlinks">[http://www.mediawiki.org/wiki/Manual:External_editors Ohje]</span>)',
'tog-showjumplinks'           => 'Lisää loikkaa-käytettävyyslinkit sivun alkuun',
'tog-uselivepreview'          => 'Käytä pikaesikatselua (JavaScript) (kokeellinen)',
'tog-forceeditsummary'        => 'Huomauta, jos yhteenvetoa ei ole annettu',
'tog-watchlisthideown'        => 'Piilota omat muokkaukset',
'tog-watchlisthidebots'       => 'Piilota bottien muokkaukset',
'tog-watchlisthideminor'      => 'Piilota pienet muokkaukset',
'tog-watchlisthideliu'        => 'Piilota kirjautuneiden käyttäjien muokkaukset tarkkailulistalta',
'tog-watchlisthideanons'      => 'Piilota anonyymien käyttäjien muokkaukset tarkkailulistalta',
'tog-watchlisthidepatrolled'  => 'Piilota tarkastetut muokkaukset tarkkailulistalta',
'tog-nolangconversion'        => 'Älä tee muunnoksia kielivarianttien välillä',
'tog-ccmeonemails'            => 'Lähetä minulle kopio MediaWikin kautta lähetetyistä sähköposteista',
'tog-diffonly'                => 'Älä näytä sivun sisältöä versioita vertailtaessa',
'tog-showhiddencats'          => 'Näytä piilotetut luokat',
'tog-norollbackdiff'          => 'Älä näytä eroavaisuuksia palauttamisen jälkeen',

'underline-always'  => 'Aina',
'underline-never'   => 'Ei koskaan',
'underline-default' => 'Selaimen oletustapa',

# Font style option in Special:Preferences
'editfont-style'     => 'Muokkauskentän kirjasintyyppi',
'editfont-default'   => 'Selaimen oletus',
'editfont-monospace' => 'Tasalevyinen kirjasin',
'editfont-sansserif' => 'Sans-serif-kirjasin',
'editfont-serif'     => 'Serif-kirjasin',

# Dates
'sunday'        => 'sunnuntai',
'monday'        => 'maanantai',
'tuesday'       => 'tiistai',
'wednesday'     => 'keskiviikko',
'thursday'      => 'torstai',
'friday'        => 'perjantai',
'saturday'      => 'lauantai',
'sun'           => 'su',
'mon'           => 'ma',
'tue'           => 'ti',
'wed'           => 'ke',
'thu'           => 'to',
'fri'           => 'pe',
'sat'           => 'la',
'january'       => 'tammikuu',
'february'      => 'helmikuu',
'march'         => 'maaliskuu',
'april'         => 'huhtikuu',
'may_long'      => 'toukokuu',
'june'          => 'kesäkuu',
'july'          => 'heinäkuu',
'august'        => 'elokuu',
'september'     => 'syyskuu',
'october'       => 'lokakuu',
'november'      => 'marraskuu',
'december'      => 'joulukuu',
'january-gen'   => 'tammikuun',
'february-gen'  => 'helmikuun',
'march-gen'     => 'maaliskuun',
'april-gen'     => 'huhtikuun',
'may-gen'       => 'toukokuun',
'june-gen'      => 'kesäkuun',
'july-gen'      => 'heinäkuun',
'august-gen'    => 'elokuun',
'september-gen' => 'syyskuun',
'october-gen'   => 'lokakuun',
'november-gen'  => 'marraskuun',
'december-gen'  => 'joulukuun',
'jan'           => 'tammikuu',
'feb'           => 'helmikuu',
'mar'           => 'maaliskuu',
'apr'           => 'huhtikuu',
'may'           => 'toukokuu',
'jun'           => 'kesäkuu',
'jul'           => 'heinäkuu',
'aug'           => 'elokuu',
'sep'           => 'syyskuu',
'oct'           => 'lokakuu',
'nov'           => 'marraskuu',
'dec'           => 'joulukuu',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Luokka|Luokat}}',
'category_header'                => 'Sivut, jotka ovat luokassa $1',
'subcategories'                  => 'Alaluokat',
'category-media-header'          => 'Luokan ”$1” sisältämät tiedostot',
'category-empty'                 => "''Tässä luokassa ei ole sivuja eikä tiedostoja.''",
'hidden-categories'              => '{{PLURAL:$1|Piilotettu luokka|Piilotetut luokat}}',
'hidden-category-category'       => 'Piilotetut luokat',
'category-subcat-count'          => '{{PLURAL:$2|Tässä luokassa on vain seuraava alaluokka.|{{PLURAL:$1|Seuraava alaluokka kuuluu|Seuraavat $1 alaluokkaa kuuluvat}} tähän luokkaan. Alaluokkien kokonaismäärä luokassa on $2.}}',
'category-subcat-count-limited'  => 'Tässä luokassa on {{PLURAL:$1|yksi alaluokka|$1 alaluokkaa}}.',
'category-article-count'         => '{{PLURAL:$2|Tässä luokassa on vain seuraava sivu.|{{PLURAL:$1|Seuraava sivu kuuluu|Seuraavat $1 sivua kuuluvat}} tähän luokkaan. Sivujen kokonaismäärä luokassa on $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Tämä sivu kuuluu|Nämä $1 sivua kuuluvat}} nykyiseen luokkaan.',
'category-file-count'            => '{{PLURAL:$2|Tässä luokassa on vain seuraava tiedosto.|{{PLURAL:$1|Seuraava tiedosto kuuluu|Seuraavat $1 tiedostoa kuuluvat}} tähän luokkaan. Tiedostoja luokassa on yhteensä $2.}}',
'category-file-count-limited'    => 'Tässä luokassa on {{PLURAL:$1|yksi tiedosto|$1 tiedostoa}}.',
'listingcontinuesabbrev'         => 'jatkuu',
'index-category'                 => 'Indeksoidut sivut',
'noindex-category'               => 'Indeksointikiellolliset sivut',

'mainpagetext'      => "'''MediaWiki on onnistuneesti asennettu.'''",
'mainpagedocfooter' => "Lisätietoja käytöstä on sivulla [http://meta.wikimedia.org/wiki/Help:Contents User's Guide].

=== Lisäohjeita ===

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Asetusten teko-ohjeita]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWikin FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Sähköpostilista, jolla tiedotetaan MediaWikin uusista versioista]

=== Asetukset ===

Tarkista, että alla olevat taivutusmuodot ovat oikein. Jos eivät, tee tarvittavat muutokset LocalSettings.php:hen seuraavasti:
 \$wgGrammarForms['fi']['genitive']['{{SITENAME}}'] = '...';
 \$wgGrammarForms['fi']['partitive']['{{SITENAME}}'] = '...';
 \$wgGrammarForms['fi']['elative']['{{SITENAME}}'] = '...';
 \$wgGrammarForms['fi']['inessive']['{{SITENAME}}'] = '...';
 \$wgGrammarForms['fi']['illative']['{{SITENAME}}'] = '...';
Taivutusmuodot: {{GRAMMAR:genitive|{{SITENAME}}}} (yön) — {{GRAMMAR:partitive|{{SITENAME}}}} (yötä) — {{GRAMMAR:elative|{{SITENAME}}}} (yöstä) — {{GRAMMAR:inessive|{{SITENAME}}}} (yössä) — {{GRAMMAR:illative|{{SITENAME}}}} (yöhön).",

'about'         => 'Tietoja',
'article'       => 'Sivu',
'newwindow'     => '(avautuu uuteen ikkunaan)',
'cancel'        => 'Peruuta',
'moredotdotdot' => 'Lisää...',
'mypage'        => 'Käyttäjäsivu',
'mytalk'        => 'Keskustelusivu',
'anontalk'      => 'Keskustele tämän IP:n kanssa',
'navigation'    => 'Valikko',
'and'           => '&#32;ja',

# Cologne Blue skin
'qbfind'         => 'Etsi',
'qbbrowse'       => 'Selaa',
'qbedit'         => 'Muokkaa',
'qbpageoptions'  => 'Sivuasetukset',
'qbpageinfo'     => 'Sivun tiedot',
'qbmyoptions'    => 'Asetukset',
'qbspecialpages' => 'Toimintosivut',
'faq'            => 'Usein kysytyt kysymykset',
'faqpage'        => 'Project:Usein kysytyt kysymykset',

# Vector skin
'vector-action-addsection'       => 'Lisää aihe',
'vector-action-delete'           => 'Poista',
'vector-action-move'             => 'Siirrä',
'vector-action-protect'          => 'Suojaa',
'vector-action-undelete'         => 'Palauta',
'vector-action-unprotect'        => 'Muuta suojausta',
'vector-simplesearch-preference' => 'Ota käyttöön parannetut hakuehdotukset (vain Vector-ulkoasu)',
'vector-view-create'             => 'Luo',
'vector-view-edit'               => 'Muokkaa',
'vector-view-history'            => 'Näytä historia',
'vector-view-view'               => 'Lue',
'vector-view-viewsource'         => 'Näytä lähdekoodi',
'actions'                        => 'Toiminnot',
'namespaces'                     => 'Nimiavaruudet',
'variants'                       => 'Muuttujat',

'errorpagetitle'    => 'Virhe',
'returnto'          => 'Palaa sivulle $1.',
'tagline'           => '{{SITENAME}}',
'help'              => 'Ohje',
'search'            => 'Haku',
'searchbutton'      => 'Etsi',
'go'                => 'Siirry',
'searcharticle'     => 'Siirry',
'history'           => 'Historia',
'history_short'     => 'Historia',
'updatedmarker'     => 'päivitetty viimeisimmän käyntisi jälkeen',
'info_short'        => 'Tiedotus',
'printableversion'  => 'Tulostettava versio',
'permalink'         => 'Ikilinkki',
'print'             => 'Tulosta',
'edit'              => 'Muokkaa',
'create'            => 'Luo sivu',
'editthispage'      => 'Muokkaa tätä sivua',
'create-this-page'  => 'Luo tämä sivu',
'delete'            => 'Poista',
'deletethispage'    => 'Poista tämä sivu',
'undelete_short'    => 'Palauta {{PLURAL:$1|yksi muokkaus|$1 muokkausta}}',
'protect'           => 'Suojaa',
'protect_change'    => 'muuta',
'protectthispage'   => 'Suojaa tämä sivu',
'unprotect'         => 'Muuta suojausta',
'unprotectthispage' => 'Muuta tämän sivun suojauksia',
'newpage'           => 'Uusi sivu',
'talkpage'          => 'Keskustele tästä sivusta',
'talkpagelinktext'  => 'keskustelu',
'specialpage'       => 'Toimintosivu',
'personaltools'     => 'Henkilökohtaiset työkalut',
'postcomment'       => 'Uusi osio',
'articlepage'       => 'Näytä varsinainen sivu',
'talk'              => 'Keskustelu',
'views'             => 'Näkymät',
'toolbox'           => 'Työkalut',
'userpage'          => 'Näytä käyttäjäsivu',
'projectpage'       => 'Näytä projektisivu',
'imagepage'         => 'Näytä tiedostosivu',
'mediawikipage'     => 'Näytä viestisivu',
'templatepage'      => 'Näytä mallinesivu',
'viewhelppage'      => 'Näytä ohjesivu',
'categorypage'      => 'Näytä luokkasivu',
'viewtalkpage'      => 'Näytä keskustelusivu',
'otherlanguages'    => 'Muilla kielillä',
'redirectedfrom'    => 'Ohjattu sivulta $1',
'redirectpagesub'   => 'Ohjaussivu',
'lastmodifiedat'    => 'Sivua on viimeksi muutettu $1 kello $2.',
'viewcount'         => 'Tämä sivu on näytetty {{PLURAL:$1|yhden kerran|$1 kertaa}}.',
'protectedpage'     => 'Suojattu sivu',
'jumpto'            => 'Loikkaa:',
'jumptonavigation'  => 'valikkoon',
'jumptosearch'      => 'hakuun',
'view-pool-error'   => 'Valitettavasti palvelimet ovat ylikuormittuneet tällä hetkellä.
Liian monta käyttäjää yrittää tarkastella tätä sivua.
Odota hetki ennen kuin yrität uudelleen.

$1',
'pool-timeout'      => 'Lukon aikakatkaisu.',
'pool-queuefull'    => 'Lukkojono on täysi.',
'pool-errorunknown' => 'Tuntematon virhe.',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Tietoja {{GRAMMAR:elative|{{SITENAME}}}}',
'aboutpage'            => 'Project:Tietoja',
'copyright'            => 'Sisältö on käytettävissä lisenssillä $1.',
'copyrightpage'        => '{{ns:project}}:Tekijänoikeudet',
'currentevents'        => 'Ajankohtaista',
'currentevents-url'    => 'Project:Ajankohtaista',
'disclaimers'          => 'Vastuuvapaus',
'disclaimerpage'       => 'Project:Vastuuvapaus',
'edithelp'             => 'Muokkausohjeet',
'edithelppage'         => 'Help:Kuinka sivuja muokataan',
'helppage'             => 'Help:Sisällys',
'mainpage'             => 'Etusivu',
'mainpage-description' => 'Etusivu',
'policy-url'           => 'Project:Käytännöt',
'portal'               => 'Kahvihuone',
'portal-url'           => 'Project:Kahvihuone',
'privacy'              => 'Tietosuojakäytäntö',
'privacypage'          => 'Project:Tietosuojakäytäntö',

'badaccess'        => 'Lupa evätty',
'badaccess-group0' => 'Sinulla ei ole lupaa suorittaa pyydettyä toimintoa.',
'badaccess-groups' => 'Pyytämäsi toiminto on rajoitettu {{PLURAL:$2|ryhmän|ryhmien}} $1 jäsenille.',

'versionrequired'     => 'MediaWikistä tarvitaan vähintään versio $1',
'versionrequiredtext' => 'MediaWikistä tarvitaan vähintään versio $1 tämän sivun käyttämiseen. Katso [[Special:Version|versio]].',

'ok'                      => 'OK',
'pagetitle'               => '$1 – {{SITENAME}}',
'retrievedfrom'           => 'Haettu osoitteesta $1',
'youhavenewmessages'      => 'Sinulle on $1 ($2).',
'newmessageslink'         => 'uusia viestejä',
'newmessagesdifflink'     => 'viimeisin muutos',
'youhavenewmessagesmulti' => 'Sinulla on uusia viestejä sivuilla $1',
'editsection'             => 'muokkaa',
'editold'                 => 'muokkaa',
'viewsourceold'           => 'näytä lähdekoodi',
'editlink'                => 'muokkaa',
'viewsourcelink'          => 'näytä lähdekoodi',
'editsectionhint'         => 'Muokkaa osiota $1',
'toc'                     => 'Sisällysluettelo',
'showtoc'                 => 'näytä',
'hidetoc'                 => 'piilota',
'thisisdeleted'           => 'Näytä tai palauta $1.',
'viewdeleted'             => 'Näytä $1?',
'restorelink'             => '{{PLURAL:$1|yksi poistettu muokkaus|$1 poistettua muokkausta}}',
'feedlinks'               => 'Uutissyötteet:',
'feed-invalid'            => 'Virheellinen syötetyyppi.',
'feed-unavailable'        => 'Verkkosyötteet eivät ole saatavilla.',
'site-rss-feed'           => '$1-RSS-syöte',
'site-atom-feed'          => '$1-Atom-syöte',
'page-rss-feed'           => '$1 (RSS-syöte)',
'page-atom-feed'          => '$1 (Atom-syöte)',
'red-link-title'          => '$1 (sivua ei ole)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Sivu',
'nstab-user'      => 'Käyttäjäsivu',
'nstab-media'     => 'Media',
'nstab-special'   => 'Toimintosivu',
'nstab-project'   => 'Projektisivu',
'nstab-image'     => 'Tiedosto',
'nstab-mediawiki' => 'Järjestelmäviesti',
'nstab-template'  => 'Malline',
'nstab-help'      => 'Ohje',
'nstab-category'  => 'Luokka',

# Main script and global functions
'nosuchaction'      => 'Määrittelemätön pyyntö',
'nosuchactiontext'  => 'Ohjelmisto ei tunnista URL:ssä määriteltyä pyyntöä.
Olet saattanut kirjoittaa väärin, tai seurannut virheellistä linkkiä.
Tämä voi myös mahdollisesti olla ohjelmistovirhe.',
'nosuchspecialpage' => 'Kyseistä toimintosivua ei ole',
'nospecialpagetext' => '<strong>Ohjelmisto ei tunnista pyytämääsi toimintosivua.</strong>

Luettelo toimintosivuista löytyy sivulta [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Virhe',
'databaseerror'        => 'Tietokantavirhe',
'dberrortext'          => 'Tietokantakyselyssä oli syntaksivirhe.
Se saattaa johtua ohjelmointivirheestä.
Viimeinen tietokantakysely, jota yritettiin, oli:
<blockquote><tt>$1</tt></blockquote>.
Se tehtiin funktiosta ”<tt>$2</tt>”.
Tietokanta palautti virheen ”<tt>$3: $4</tt>”.',
'dberrortextcl'        => 'Tietokantakyselyssä oli syntaksivirhe. Viimeinen tietokantakysely, jota yritettiin, oli: ”$1”. Se tehtiin funktiosta ”$2”. Tietokanta palautti virheen ”$3: $4”.',
'laggedslavemode'      => 'Varoitus: Sivu ei välttämättä sisällä viimeisimpiä muutoksia.',
'readonly'             => 'Tietokanta on lukittu',
'enterlockreason'      => 'Anna lukituksen syy sekä sen arvioitu poistamisaika',
'readonlytext'         => 'Tietokanta on tällä hetkellä lukittu. Uusia sivuja ei voi luoda eikä muitakaan muutoksia tehdä. Syynä ovat todennäköisimmin rutiininomaiset tietokannan ylläpitotoimet.

Tietokannan lukinneen ylläpitäjän selitys: $1',
'missing-article'      => 'Sivun sisältöä ei löytynyt tietokannasta: $1 $2.

Useimmiten tämä johtuu vanhentuneesta vertailu- tai historiasivulinkistä poistettuun sivuun.

Jos kyseessä ei ole poistettu sivu, olet ehkä löytänyt virheen ohjelmistossa.
Ilmoita tämän sivun osoite wikin [[Special:ListUsers/sysop|ylläpitäjälle]].',
'missingarticle-rev'   => '(versio: $1)',
'missingarticle-diff'  => '(vertailu: $1, $2)',
'readonly_lag'         => 'Tietokanta on automaattisesti lukittu, jotta kaikki tietokantapalvelimet saisivat kaikki tuoreet muutokset',
'internalerror'        => 'Sisäinen virhe',
'internalerror_info'   => 'Sisäinen virhe: $1',
'fileappenderrorread'  => 'Ei voitu lukea tiedostoa ”$1” liittämisen aikana.',
'fileappenderror'      => 'Tiedostoa ”$1” ei voitu lisätä tiedostoon ”$2”.',
'filecopyerror'        => 'Tiedostoa <b>$1</b> ei voitu kopioida tiedostoksi <b>$2</b>.',
'filerenameerror'      => 'Tiedostoa <b>$1</b> ei voitu nimetä uudelleen nimellä <b>$2</b>.',
'filedeleteerror'      => 'Tiedostoa <b>$1</b> ei voitu poistaa.',
'directorycreateerror' => 'Hakemiston ”$1” luominen epäonnistui.',
'filenotfound'         => 'Tiedostoa <b>$1</b> ei löytynyt.',
'fileexistserror'      => 'Tiedostoon ”$1” kirjoittaminen epäonnistui: tiedosto on olemassa',
'unexpected'           => 'Odottamaton arvo: ”$1” on ”$2”.',
'formerror'            => 'Lomakkeen tiedot eivät kelpaa',
'badarticleerror'      => 'Toimintoa ei voi suorittaa tälle sivulle.',
'cannotdelete'         => 'Sivun tai tiedoston ”$1” poisto epäonnistui.
Joku muu on saattanut poistaa sen.',
'badtitle'             => 'Virheellinen otsikko',
'badtitletext'         => 'Pyytämäsi sivuotsikko oli virheellinen, tyhjä tai väärin linkitetty kieltenvälinen tai wikienvälinen linkki.',
'perfcached'           => 'Tiedot ovat välimuistista eivätkä välttämättä ole ajan tasalla.',
'perfcachedts'         => 'Seuraava data on tuotu välimuistista ja se päivitettiin viimeksi $1.',
'querypage-no-updates' => 'Tämän sivun tietoja ei toistaiseksi päivitetä.',
'wrong_wfQuery_params' => 'Virheelliset parametrit wfQuery()<br />Funktio: $1<br />Tiedustelu: $2',
'viewsource'           => 'Lähdekoodi',
'viewsourcefor'        => 'sivulle $1',
'actionthrottled'      => 'Toiminto nopeusrajoitettu',
'actionthrottledtext'  => 'Ylläpitosyistä tämän toiminnon suorittamista on rajoitettu. Olet suorittanut tämän toiminnon liian monta kertaa lyhyen ajan sisällä. Yritä myöhemmin uudelleen.',
'protectedpagetext'    => 'Tämä sivu on suojattu muutoksilta.',
'viewsourcetext'       => 'Voit tarkastella ja kopioida tämän sivun lähdekoodia:',
'protectedinterface'   => 'Tämä sivu sisältää ohjelmiston käyttöliittymätekstiä ja on suojattu häiriköinnin estämiseksi.',
'editinginterface'     => "'''Varoitus.''' Muokkaat sivua, joka sisältää ohjelmiston käyttöliittymätekstiä.
Muutokset tähän sivuun vaikuttavat muiden käyttäjien käyttöliittymän ulkoasuun.
Viestien kääntäminen tulisi tehdä [http://translatewiki.net/wiki/Main_Page?setlang=fi translatewiki.netissä] – MediaWikin kotoistusprojektissa.",
'sqlhidden'            => '(SQL-kysely piilotettu)',
'cascadeprotected'     => 'Tämä sivu on suojattu muokkauksilta, koska se on sisällytetty alla {{PLURAL:$1|olevaan laajennetusti suojattuun sivuun|oleviin laajennetusti suojattuihin sivuihin}}:
$2',
'namespaceprotected'   => "Et voi muokata sivuja nimiavaruudessa '''$1'''.",
'customcssjsprotected' => 'Sinulla ei ole oikeuksia muuttaa toisten käyttäjien henkilökohtaisia asetuksia.',
'ns-specialprotected'  => 'Toimintosivuja ei voi muokata.',
'titleprotected'       => "Käyttäjä [[User:$1|$1]] on asettanut tämän sivun luontikieltoon: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Virheellinen asetus: tuntematon virustutka: ''$1''",
'virus-scanfailed'     => 'virustarkistus epäonnistui virhekoodilla $1',
'virus-unknownscanner' => 'tuntematon virustutka:',

# Login and logout pages
'logouttext'                 => "'''Olet nyt kirjautunut ulos.'''

Voit jatkaa {{GRAMMAR:genitive|{{SITENAME}}}} käyttöä nimettömänä, tai [[Special:UserLogin|kirjautua uudelleen sisään]].
Huomaa, että jotkut sivut saattavat näkyä edelleen kuin olisit kirjautunut sisään, kunnes tyhjennät selaimen välimuistin.",
'welcomecreation'            => '== Tervetuloa $1! ==
Käyttäjätunnuksesi on luotu.
Älä unohda virittää {{GRAMMAR:genitive|{{SITENAME}}}} [[Special:Preferences|asetuksiasi]].',
'yourname'                   => 'Käyttäjätunnus',
'yourpassword'               => 'Salasana',
'yourpasswordagain'          => 'Salasana uudelleen',
'remembermypassword'         => 'Muista minut (enintään $1 {{PLURAL:$1|päivä|päivää}})',
'securelogin-stick-https'    => 'Jatka salatun yhteyden käyttämistä sisäänkirjautumisen jälkeen',
'yourdomainname'             => 'Verkkonimi',
'externaldberror'            => 'Tapahtui virhe ulkoisen autentikointitietokannan käytössä tai sinulla ei ole lupaa päivittää tunnustasi.',
'login'                      => 'Kirjaudu sisään',
'nav-login-createaccount'    => 'Kirjaudu sisään tai luo tunnus',
'loginprompt'                => 'Kirjautumiseen tarvitaan evästeitä.',
'userlogin'                  => 'Kirjaudu sisään tai luo tunnus',
'userloginnocreate'          => 'Kirjaudu sisään',
'logout'                     => 'Kirjaudu ulos',
'userlogout'                 => 'Kirjaudu ulos',
'notloggedin'                => 'Et ole kirjautunut',
'nologin'                    => "Jos sinulla ei ole vielä käyttäjätunnusta, '''$1'''.",
'nologinlink'                => 'voit luoda sellaisen',
'createaccount'              => 'Luo uusi käyttäjätunnus',
'gotaccount'                 => "Jos sinulla on jo tunnus, voit '''$1'''.",
'gotaccountlink'             => 'kirjautua sisään',
'createaccountmail'          => 'sähköpostitse',
'createaccountreason'        => 'Syy',
'badretype'                  => 'Syöttämäsi salasanat ovat erilaiset.',
'userexists'                 => 'Pyytämäsi käyttäjänimi on jo käytössä. Valitse toinen käyttäjänimi.',
'loginerror'                 => 'Sisäänkirjautumisvirhe',
'createaccounterror'         => 'Tunnuksen luonti ei onnistunut: $1',
'nocookiesnew'               => 'Käyttäjä luotiin, mutta et ole kirjautunut sisään. {{SITENAME}} käyttää evästeitä sisäänkirjautumisen yhteydessä. Selaimesi ei salli evästeistä. Kytke ne päälle, ja sitten kirjaudu sisään juuri luomallasi käyttäjänimellä ja salasanalla.',
'nocookieslogin'             => '{{SITENAME}} käyttää evästeitä sisäänkirjautumisen yhteydessä. Selaimesi ei salli evästeitä. Ota ne käyttöön, ja yritä uudelleen.',
'noname'                     => 'Et ole määritellyt kelvollista käyttäjänimeä.',
'loginsuccesstitle'          => 'Sisäänkirjautuminen onnistui',
'loginsuccess'               => "'''Olet kirjautunut käyttäjänä $1.'''",
'nosuchuser'                 => 'Käyttäjää ”$1” ei ole olemassa. Nimet ovat kirjainkoosta riippuvaisia. Tarkista kirjoititko nimen oikein, tai [[Special:UserLogin/signup|luo uusi käyttäjätunnus]].',
'nosuchusershort'            => 'Käyttäjää nimeltä ”<nowiki>$1</nowiki>” ei ole. Kirjoititko nimen oikein?',
'nouserspecified'            => 'Käyttäjätunnusta ei ole määritelty.',
'login-userblocked'          => 'Tämä käyttäjä on estetty. Kirjautuminen ei ole sallittua.',
'wrongpassword'              => 'Syöttämäsi salasana ei ole oikein. Ole hyvä ja yritä uudelleen.',
'wrongpasswordempty'         => 'Et voi antaa tyhjää salasanaa.',
'passwordtooshort'           => 'Salasanan täytyy olla vähintään {{PLURAL:$1|yhden merkin pituinen|$1 merkkiä pitkä}}.',
'password-name-match'        => 'Salasanasi täytyy olla eri kuin käyttäjätunnuksesi.',
'password-login-forbidden'   => 'Tämän käyttäjänimen ja salasanan käyttö on estetty.',
'mailmypassword'             => 'Lähetä uusi salasana sähköpostitse',
'passwordremindertitle'      => 'Salasanamuistutus {{GRAMMAR:elative|{{SITENAME}}}}',
'passwordremindertext'       => 'Joku IP-osoitteesta $1 pyysi {{GRAMMAR:partitive|{{SITENAME}}}} ($4) lähettämään uuden salasanan. Väliaikainen salasana käyttäjälle $2 on nyt $3. Kirjaudu sisään ja vaihda salasana. Väliaikainen salasana vanhenee {{PLURAL:$5|yhden päivän|$5 päivän}} kuluttua.

Jos joku muu on tehnyt tämän pyynnön, tai jos olet muistanut salasanasi ja et halua vaihtaa sitä, voit jättää tämän viestin huomiotta ja jatkaa vanhan salasanan käyttöä.',
'noemail'                    => "Käyttäjälle '''$1''' ei ole määritelty sähköpostiosoitetta.",
'noemailcreate'              => 'Sinun on annettava voimassa oleva sähköpostiosoite',
'passwordsent'               => 'Uusi salasana on lähetetty käyttäjän <b>$1</b> sähköpostiosoitteeseen.',
'blocked-mailpassword'       => 'Osoitteellesi on asetettu muokkausesto, joka estää käyttämästä salasanamuistutustoimintoa.',
'eauthentsent'               => 'Varmennussähköposti on lähetetty annettuun sähköpostiosoitteeseen. Muita viestejä ei lähetetä, ennen kuin olet toiminut viestin ohjeiden mukaan ja varmistanut, että sähköpostiosoite kuuluu sinulle.',
'throttled-mailpassword'     => 'Salasanamuistutus on lähetetty {{PLURAL:$1|kuluvan|kuluvien $1}} tunnin aikana. Salasanamuistutuksia lähetään enintään {{PLURAL:$1|tunnin|$1 tunnin}} välein.',
'mailerror'                  => 'Virhe lähetettäessä sähköpostia: $1',
'acct_creation_throttle_hit' => 'IP-osoitteestasi on luotu tähän wikiin jo {{PLURAL:$1|yksi tunnus|$1 tunnusta}} päivän aikana, joka suurin sallittu määrä tälle ajalle.
Tästä johtuen tästä IP-osoitteesta ei voi tällä hetkellä luoda uusia tunnuksia.',
'emailauthenticated'         => 'Sähköpostiosoitteesi varmennettiin $2 kello $3.',
'emailnotauthenticated'      => 'Sähköpostiosoitettasi ei ole vielä varmennettu. Sähköpostia ei lähetetä liittyen alla oleviin toimintoihin.',
'noemailprefs'               => 'Sähköpostiosoitetta ei ole määritelty.',
'emailconfirmlink'           => 'Varmenna sähköpostiosoite',
'invalidemailaddress'        => 'Sähköpostiosoitetta ei voida hyväksyä, koska se ei ole oikeassa muodossa. Ole hyvä ja anna oikea sähköpostiosoite tai jätä kenttä tyhjäksi.',
'accountcreated'             => 'Käyttäjätunnus luotiin',
'accountcreatedtext'         => 'Käyttäjän $1 käyttäjätunnus luotiin.',
'createaccount-title'        => 'Tunnuksen luominen {{GRAMMAR:illative|{{SITENAME}}}}',
'createaccount-text'         => 'Joku on luonut tunnuksen $2 {{GRAMMAR:illative|{{SITENAME}}}} ($4).
Tunnuksen $2 salasana on $3. Kirjaudu sisään ja vaihda salasanasi.

Sinun ei tarvitse huomioida tätä viestiä, jos tunnus on luotu virheellisesti.',
'usernamehasherror'          => 'Käyttäjätunnus ei voi sisältää tiivistemerkkejä.',
'login-throttled'            => 'Olet tehnyt liian monta kirjautumisyritystä.
Odota ennen kuin yrität uudelleen.',
'loginlanguagelabel'         => 'Kieli: $1',
'suspicious-userlogout'      => 'Pyyntösi kirjautua ulos evättiin, koska se näytti rikkinäisen selaimen tai välimuistipalvelimen lähettämältä.',

# E-mail sending
'php-mail-error-unknown' => 'Tuntematon virhe PHP:n mail()-funktiossa',

# Password reset dialog
'resetpass'                 => 'Muuta salasana',
'resetpass_announce'        => 'Kirjauduit sisään sähköpostitse lähetetyllä väliaikaissalasanalla. Päätä sisäänkirjautuminen asettamalla uusi salasana.',
'resetpass_text'            => '<!-- Lisää tekstiä tähän -->',
'resetpass_header'          => 'Muuta tunnuksen salasana',
'oldpassword'               => 'Vanha salasana',
'newpassword'               => 'Uusi salasana',
'retypenew'                 => 'Uusi salasana uudelleen',
'resetpass_submit'          => 'Aseta salasana ja kirjaudu sisään',
'resetpass_success'         => 'Salasanan vaihto onnistui.',
'resetpass_forbidden'       => 'Salasanoja ei voi vaihtaa.',
'resetpass-no-info'         => 'Et voi nähdä tätä sivua kirjautumatta sisään.',
'resetpass-submit-loggedin' => 'Muuta salasana',
'resetpass-submit-cancel'   => 'Peruuta',
'resetpass-wrong-oldpass'   => 'Virheellinen väliaikainen tai nykyinen salasana.
Olet saattanut jo onnistuneesti vaihtaa salasanasi tai pyytää uutta väliaikaista salasanaa.',
'resetpass-temp-password'   => 'Väliaikainen salasana:',

# Edit page toolbar
'bold_sample'     => 'Lihavoitu teksti',
'bold_tip'        => 'Lihavointi',
'italic_sample'   => 'Kursivoitu teksti',
'italic_tip'      => 'Kursivointi',
'link_sample'     => 'linkki',
'link_tip'        => 'Sisäinen linkki',
'extlink_sample'  => 'http://www.example.com linkin otsikko',
'extlink_tip'     => 'Ulkoinen linkki (muista http:// edessä)',
'headline_sample' => 'Otsikkoteksti',
'headline_tip'    => 'Otsikko',
'math_sample'     => 'Lisää kaava tähän',
'math_tip'        => 'Matemaattinen kaava (LaTeX)',
'nowiki_sample'   => 'Lisää muotoilematon teksti tähän',
'nowiki_tip'      => 'Tekstiä, jota wiki ei muotoile',
'image_sample'    => 'Esimerkki.jpg',
'image_tip'       => 'Tallennettu tiedosto',
'media_sample'    => 'Esimerkki.ogg',
'media_tip'       => 'Mediatiedostolinkki',
'sig_tip'         => 'Allekirjoitus aikamerkinnällä',
'hr_tip'          => 'Vaakasuora viiva',

# Edit pages
'summary'                          => 'Yhteenveto',
'subject'                          => 'Aihe tai otsikko',
'minoredit'                        => 'Tämä on pieni muutos',
'watchthis'                        => 'Lisää tarkkailulistaan',
'savearticle'                      => 'Tallenna sivu',
'preview'                          => 'Esikatselu',
'showpreview'                      => 'Esikatsele',
'showlivepreview'                  => 'Pikaesikatselu',
'showdiff'                         => 'Näytä muutokset',
'anoneditwarning'                  => "'''Varoitus:''' Et ole kirjautunut sisään.
IP-osoitteesi kirjataan tämän sivun muutoshistoriaan.",
'anonpreviewwarning'               => "''Et ole kirjautunut sisään. Tallentaminen kirjaa IP-osoitteesi tämän sivun muutoshistoriaan.''",
'missingsummary'                   => 'Et ole antanut yhteenvetoa. Jos valitset Tallenna uudelleen, niin muokkauksesi tallennetaan ilman yhteenvetoa.',
'missingcommenttext'               => 'Kirjoita viesti alle.',
'missingcommentheader'             => 'Et ole antanut otsikkoa kommentillesi. Napsauta ”{{int:savearticle}}”, jos et halua antaa otsikkoa.',
'summary-preview'                  => 'Yhteenvedon esikatselu:',
'subject-preview'                  => 'Otsikon esikatselu:',
'blockedtitle'                     => 'Pääsy estetty',
'blockedtext'                      => "'''Käyttäjätunnuksesi tai IP-osoitteesi on estetty.'''

Eston on asettanut $1.
Syy: '''$2'''

* Eston alkamisaika: $8
* Eston päättymisaika: $6
* Kohde: $7

Voit keskustella ylläpitäjän $1 tai toisen [[{{MediaWiki:Grouppage-sysop}}|ylläpitäjän]] kanssa estosta.
Huomaa, ettet voi lähettää sähköpostia {{GRAMMAR:genitive|{{SITENAME}}}} kautta, ellet ole asettanut olemassa olevaa sähköpostiosoitetta [[Special:Preferences|asetuksissa]] tai jos esto on asetettu koskemaan myös sähköpostin lähettämistä.
IP-osoitteesi on $3 ja estotunnus on #$5.
Liitä kaikki yllä olevat tiedot mahdollisiin kyselyihisi.",
'autoblockedtext'                  => "IP-osoitteesi on estetty automaattisesti, koska sitä on käyttänyt toinen käyttäjä, jonka on estänyt ylläpitäjä $1.
Eston syy on:

:''$2''

* Eston alkamisaika: $8
* Eston päättymisaika: $6
* Kohde: $7

Voit keskustella ylläpitäjän $1 tai toisen [[{{MediaWiki:Grouppage-sysop}}|ylläpitäjän]] kanssa estosta.

Huomaa, ettet voi lähettää sähköpostia {{GRAMMAR:genitive|{{SITENAME}}}} kautta, ellet ole asettanut olemassa olevaa sähköpostiosoitetta [[Special:Preferences|asetuksissa]] tai jos esto on asetettu koskemaan myös sähköpostin lähettämistä.

IP-osoitteesi on $3 ja estotunnus on #$5.
Liitä kaikki yllä olevat tiedot mahdollisiin kyselyihisi.",
'blockednoreason'                  => '(syytä ei annettu)',
'blockedoriginalsource'            => 'Sivun ”$1” lähdekoodi:',
'blockededitsource'                => 'Muokkauksesi sivuun ”$1”:',
'whitelistedittitle'               => 'Sisäänkirjautuminen vaaditaan muokkaamiseen',
'whitelistedittext'                => 'Sinun täytyy $1, jotta voisit muokata sivuja.',
'confirmedittext'                  => 'Et voi muokata sivuja, ennen kuin olet varmentanut sähköpostiosoitteesi. Voit tehdä varmennuksen [[Special:Preferences|asetussivulla]].',
'nosuchsectiontitle'               => 'Pyydettyä osiota ei ole',
'nosuchsectiontext'                => 'Yritit muokata osiota, jota ei ole olemassa.
Se on saatettu siirtää tai poistaa äskettäin.',
'loginreqtitle'                    => 'Sisäänkirjautuminen vaaditaan',
'loginreqlink'                     => 'kirjautua sisään',
'loginreqpagetext'                 => 'Sinun täytyy $1, jotta voisit nähdä muut sivut.',
'accmailtitle'                     => 'Salasana lähetetty.',
'accmailtext'                      => 'Satunnaisesti generoitu salasana käyttäjälle [[User talk:$1|$1]] on lähetetty osoitteeseen $2.

Salasanan tälle uudelle tunnukselle voi vaihtaa kirjautumisen jälkeen [[Special:ChangePassword|asetussivulla]].',
'newarticle'                       => '(uusi)',
'newarticletext'                   => 'Linkki toi sivulle, jota ei vielä ole.
Voit luoda sivun kirjoittamalla alla olevaan kenttään (katso [[{{MediaWiki:Helppage}}|ohjesivulta]] lisätietoja).
Jos et halua luoda sivua, käytä selaimen paluutoimintoa.',
'anontalkpagetext'                 => "----''Tämä on nimettömän käyttäjän keskustelusivu. Hän ei ole joko luonut itselleen käyttäjätunnusta tai ei käytä sitä. Siksi hänet tunnistetaan nyt numeerisella IP-osoitteella. Kyseinen IP-osoite voi olla useamman henkilön käytössä. Jos olet nimetön käyttäjä, ja sinusta tuntuu, että aiheettomia kommentteja on ohjattu sinulle, [[Special:UserLogin/signup|luo itsellesi käyttäjätunnus]] tai [[Special:UserLogin|kirjaudu sisään]] välttääksesi jatkossa sekaannukset muiden nimettömien käyttäjien kanssa.''",
'noarticletext'                    => 'Tällä hetkellä tällä sivulla ei ole tekstiä.
Voit [[Special:Search/{{PAGENAME}}|etsiä sivun nimellä]] muilta sivuilta,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} hakea aiheeseen liittyviä lokeja]
tai [{{fullurl:{{FULLPAGENAME}}|action=edit}} muokata tätä sivua]</span>.',
'noarticletext-nopermission'       => 'Tällä hetkellä tällä sivulla ei ole tekstiä.
Voit [[Special:Search/{{PAGENAME}}|etsiä sivun nimellä]] muilta sivuilta
tai <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} hakea aiheeseen liittyviä lokeja]</span>',
'userpage-userdoesnotexist'        => 'Käyttäjätunnusta $1 ei ole rekisteröity. Varmista haluatko muokata tätä sivua.',
'userpage-userdoesnotexist-view'   => 'Käyttäjätunnusta ”$1” ei ole rekisteröity.',
'blocked-notice-logextract'        => 'Tämä käyttäjä on tällä hetkellä estetty.
Alla on viimeisin estolokin tapahtuma:',
'clearyourcache'                   => "'''Huomautus:''' Selaimen välimuisti pitää tyhjentää asetusten tallentamisen jälkeen, jotta muutokset tulisivat voimaan:
'''Mozilla, Firefox ja Safari:''' napsauta Vaihto-näppäin pohjassa Päivitä tai paina ''Ctrl-F5'' tai ''Ctrl-R'' (''Command-R'' Macintoshilla);
'''Konqueror''': napsauta Päivitä tai paina ''F5'';
'''Opera:''' tyhjennä välimuisti: ''Tools→Preferences'';
'''Internet Explorer:''' napsauta ''Ctrl''-näppäin pohjassa Päivitä tai paina ''Ctrl-F5''.",
'usercssyoucanpreview'             => 'Voit testata uutta CSS:ää ennen tallennusta käyttämällä painiketta ”{{int:showpreview}}”.',
'userjsyoucanpreview'              => 'Voit testata uutta JavaScriptiä ennen tallennusta käyttämällä painiketta ”{{int:showpreview}}”.',
'usercsspreview'                   => "'''Tämä on CSS:n esikatselu. Muutoksia ei ole vielä tallennettu.'''",
'userjspreview'                    => "'''Tämä on JavaScriptin esikatselu.'''",
'sitecsspreview'                   => "'''Huomaa, että tämä on vasta CSS:n esikatselu.''' 
'''Muutoksia ei ole vielä tallennettu.'''",
'sitejspreview'                    => "'''Huomaa, että tämä on vasta JavaScript-koodin esikatselu.'''
'''Muutoksia ei ole vielä tallennettu.'''",
'userinvalidcssjstitle'            => "'''Varoitus:''' Tyyliä nimeltä ”$1” ei ole olemassa. Muista, että käyttäjän määrittelemät .css- ja .js-sivut alkavat pienellä alkukirjaimella, esim. {{ns:user}}:Matti Meikäläinen/vector.css eikä {{ns:user}}:Matti Meikäläinen/Vector.css.",
'updated'                          => '(Päivitetty)',
'note'                             => "'''Huomautus:'''",
'previewnote'                      => "'''Tämä on vasta sivun esikatselu. Sivua ei ole vielä tallennettu!'''",
'previewconflict'                  => 'Tämä esikatselu näyttää miltä muokkausalueella oleva teksti näyttää tallennettuna.',
'session_fail_preview'             => "'''Muokkaustasi ei voitu tallentaa, koska istuntosi tiedot ovat kadonneet.''' Yritä uudelleen. Jos ongelma ei katoa, yritä [[Special:UserLogout|kirjautua ulos]] ja takaisin sisään.",
'session_fail_preview_html'        => "'''Muokkaustasi ei voitu tallentaa, koska istuntosi tiedot ovat kadonneet.'''

Esikatselu on piilotettu varokeinona JavaScript-hyökkäyksiä vastaan – tässä wikissä on HTML-tila päällä.

Yritä uudelleen. Jos ongelma ei katoa, yritä [[Special:UserLogout|kirjautua ulos]] ja takaisin sisään.",
'token_suffix_mismatch'            => "'''Muokkauksesi on hylätty, koska asiakasohjelmasi ei osaa käsitellä välimerkkejä muokkaustarkisteessa. Syynä voi olla viallinen välityspalvelin.'''",
'editing'                          => 'Muokataan sivua $1',
'editingsection'                   => 'Muokataan osiota sivusta $1',
'editingcomment'                   => 'Muokataan uutta osiota sivulla $1',
'editconflict'                     => 'Päällekkäinen muokkaus: $1',
'explainconflict'                  => "Joku muu on muuttanut tätä sivua sen jälkeen, kun aloit muokata sitä.
Ylempi tekstialue sisältää tämänhetkisen tekstin.
Tekemäsi muutokset näkyvät alemmassa ikkunassa.
Sinun täytyy yhdistää muutoksesi olemassa olevaan tekstiin.
'''Vain''' ylemmässä alueessa oleva teksti tallentuu, kun tallennat sivun.",
'yourtext'                         => 'Oma tekstisi',
'storedversion'                    => 'Tallennettu versio',
'nonunicodebrowser'                => "'''Selaimesi ei ole Unicode-yhteensopiva. Ole hyvä ja vaihda selainta, ennen kuin muokkaat sivua.'''",
'editingold'                       => "'''Varoitus: Olet muokkaamassa vanhaa versiota tämän sivun tekstistä. Jos tallennat sen, kaikki tämän version jälkeen tehdyt muutokset katoavat.'''",
'yourdiff'                         => 'Eroavaisuudet',
'copyrightwarning'                 => "'''Muutoksesi astuvat voimaan välittömästi.''' Kaikki {{GRAMMAR:illative|{{SITENAME}}}} tehtävät tuotokset katsotaan julkaistuksi $2 -lisenssin mukaisesti ($1). Jos et halua, että kirjoitustasi muokataan armottomasti ja uudelleenkäytetään vapaasti, älä tallenna kirjoitustasi. Tallentamalla muutoksesi lupaat, että kirjoitit tekstisi itse, tai kopioit sen jostain vapaasta lähteestä. '''ÄLÄ KÄYTÄ TEKIJÄNOIKEUDEN ALAISTA MATERIAALIA ILMAN LUPAA!'''",
'copyrightwarning2'                => "Huomaa, että kuka tahansa voi muokata, muuttaa ja poistaa kaikkia sivustolle tekemiäsi lisäyksiä ja muutoksia. Muokkaamalla sivustoa luovutat sivuston käyttäjille tämän oikeuden ja takaat, että lisäämäsi aineisto on joko itse kirjoittamaasi tai peräisin jostain vapaasta lähteestä. Lisätietoja sivulla $1. '''TEKIJÄNOIKEUDEN ALAISEN MATERIAALIN KÄYTTÄMINEN ILMAN LUPAA ON EHDOTTOMASTI KIELLETTYÄ!'''",
'longpageerror'                    => "'''Sivun koko on $1 binäärikilotavua. Sivua ei voida tallentaa, koska enimmäiskoko on $2 binäärikilotavua.'''",
'readonlywarning'                  => "'''Varoitus: Tietokanta on lukittu huoltoa varten, joten voi olla ettet pysty tallentamaan muokkauksiasi juuri nyt.''' Saattaa olla paras leikata ja liimata tekstisi omaan tekstitiedostoosi ja tallentaa se tänne myöhemmin.

Lukitsemisen syy: $1",
'protectedpagewarning'             => "'''Varoitus: Tämä sivu on lukittu siten, että vain ylläpitäjät voivat muokata sitä.'''
Alla on viimeisin lokitapahtuma:",
'semiprotectedpagewarning'         => 'Tämä sivu on lukittu siten, että vain rekisteröityneet käyttäjät voivat muokata sitä.
Alla on viimeisin lokitapahtuma:',
'cascadeprotectedwarning'          => '<strong>Vain ylläpitäjät voivat muokata tätä sivua, koska se on sisällytetty alla {{PLURAL:$1|olevaan laajennetusti suojattuun sivuun|oleviin laajennetusti suojattuihin sivuihin}}</strong>:',
'titleprotectedwarning'            => "'''Tämä sivun luominen on rajoitettu vain osalle käyttäjistä [[Special:ListGroupRights|tietyillä oikeuksilla]].'''
Alla on viimeisin lokitapahtuma:",
'templatesused'                    => 'Tällä sivulla {{PLURAL:$1|käytetty malline|käytetyt mallineet}}:',
'templatesusedpreview'             => 'Esikatselussa mukana {{PLURAL:$1|oleva malline|olevat mallineet}}:',
'templatesusedsection'             => 'Tässä osiossa mukana {{PLURAL:$1|oleva malline|olevat mallineet}}:',
'template-protected'               => '(suojattu)',
'template-semiprotected'           => '(suojattu kirjautumattomilta ja uusilta käyttäjiltä)',
'hiddencategories'                 => 'Tämä sivu kuuluu {{PLURAL:$1|seuraavaan piilotettuun luokkaan|seuraaviin piilotettuihin luokkiin}}:',
'edittools'                        => '<!-- Tässä oleva teksti näytetään muokkauskentän alla. -->',
'nocreatetitle'                    => 'Sivujen luominen on rajoitettu',
'nocreatetext'                     => 'Et voi luoda uusia sivuja. Voit muokata olemassa olevia sivuja tai luoda [[Special:UserLogin|käyttäjätunnuksen]].',
'nocreate-loggedin'                => 'Sinulla ei ole oikeuksia luoda uusia sivuja.',
'sectioneditnotsupported-title'    => 'Osiomuokkaaminen ei ole tuettu.',
'sectioneditnotsupported-text'     => 'Osiomuokkaaminen ei ole tuettu tällä sivulla.',
'permissionserrors'                => 'Puutteelliset oikeudet',
'permissionserrorstext'            => 'Sinulla ei ole oikeutta suorittaa toimintoa {{PLURAL:$1|seuraavasta syystä|seuraavista syistä}} johtuen:',
'permissionserrorstext-withaction' => 'Sinulla ei ole lupaa {{lcfirst:$2}} {{PLURAL:$1|seuraavasta syystä|seuraavista syistä}} johtuen:',
'recreate-moveddeleted-warn'       => "'''Olet luomassa sivua, joka on aikaisemmin poistettu.'''

Harkitse, kannattaako sivua luoda uudelleen. Alla on tämän sivun poisto- ja siirtohistoria:",
'moveddeleted-notice'              => 'Tämä sivu on poistettu. Alla on tämän sivun poisto- ja siirtohistoria.',
'log-fulllog'                      => 'Näytä loki kokonaan',
'edit-hook-aborted'                => 'Laajennuskoodi esti muokkauksen antamatta syytä.',
'edit-gone-missing'                => 'Sivun päivitys ei onnistunut.
Se on ilmeisesti poistettu.',
'edit-conflict'                    => 'Päällekkäinen muokkaus.',
'edit-no-change'                   => 'Muokkauksesi sivuutettiin, koska tekstiin ei tehty mitään muutoksia.',
'edit-already-exists'              => 'Uuden sivun luominen ei onnistunut.
Se on jo olemassa.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Tällä sivulla on liian monta hitaiden laajennusfunktioiden kutsua.
Kutsuja pitäisi olla alle $2 {{PLURAL:$2|kappale|kappaletta}}, mutta nyt niitä on $1 {{PLURAL:$1|kappale|kappaletta}}.',
'expensive-parserfunction-category'       => 'Liiaksi hitaita jäsentimen laajennusfunktioita käyttävät sivut',
'post-expand-template-inclusion-warning'  => 'Varoitus: Sisällytettyjen mallineiden koko on liian suuri.
Joitakin mallineita ei ole sisällytetty.',
'post-expand-template-inclusion-category' => 'Mallineiden sisällytyksen kokorajan ylittävät sivut',
'post-expand-template-argument-warning'   => 'Varoitus: Tällä sivulla on ainakin yksi mallineen muuttuja, jonka sisällytetty koko on liian suuri.
Nämä muuttujat on jätetty käsittelemättä.',
'post-expand-template-argument-category'  => 'Käsittelemättömiä mallinemuuttujia sisältävät sivut',
'parser-template-loop-warning'            => 'Mallinesilmukka havaittu: [[$1]]',
'parser-template-recursion-depth-warning' => 'Mallineen rekursioraja ylittyi ($1)',
'language-converter-depth-warning'        => 'Kielimuuntimen syvyysraja ylittyi ($1)',

# "Undo" feature
'undo-success' => 'Kumoaminen onnistui. Valitse <em>tallenna</em> toteuttaaksesi muutokset.',
'undo-failure' => 'Muokkausta ei voitu kumota välissä olevien ristiriitaisten muutosten vuoksi. Kumoa muutokset käsin.',
'undo-norev'   => 'Muokkausta ei voitu perua, koska sitä ei ole olemassa tai se on poistettu.',
'undo-summary' => 'Kumottu muokkaus $1, jonka teki [[Special:Contributions/$2|$2]] ([[User talk:$2|keskustelu]])',

# Account creation failure
'cantcreateaccounttitle' => 'Tunnuksen luominen epäonnistui',
'cantcreateaccount-text' => "Käyttäjä [[User:$3|$3]] on estänyt käyttäjätunnusten luomisen tästä IP-osoitteesta ($1).

Käyttäjän $3 antama syy on ''$2''",

# History pages
'viewpagelogs'           => 'Näytä tämän sivun lokit',
'nohistory'              => 'Tällä sivulla ei ole muutoshistoriaa.',
'currentrev'             => 'Nykyinen versio',
'currentrev-asof'        => 'Nykyinen versio $1',
'revisionasof'           => 'Versio $1',
'revision-info'          => 'Versio hetkellä $1 – tehnyt $2',
'previousrevision'       => '← Vanhempi versio',
'nextrevision'           => 'Uudempi versio →',
'currentrevisionlink'    => 'Nykyinen versio',
'cur'                    => 'nyk.',
'next'                   => 'seur.',
'last'                   => 'edell.',
'page_first'             => 'ensimmäinen sivu',
'page_last'              => 'viimeinen sivu',
'histlegend'             => 'Merkinnät: (nyk.) = eroavaisuudet nykyiseen versioon, (edell.) = eroavaisuudet edelliseen versioon, <span class="minor">p</span> = pieni muutos',
'history-fieldset-title' => 'Selaa muutoshistoriaa',
'history-show-deleted'   => 'Vain poistetut',
'histfirst'              => 'Ensimmäiset',
'histlast'               => 'Viimeisimmät',
'historysize'            => '({{PLURAL:$1|1 tavu|$1 tavua}})',
'historyempty'           => '(tyhjä)',

# Revision feed
'history-feed-title'          => 'Muutoshistoria',
'history-feed-description'    => 'Tämän sivun muutoshistoria',
'history-feed-item-nocomment' => '$1 ($2)',
'history-feed-empty'          => 'Pyydettyä sivua ei ole olemassa.
Se on saatettu poistaa wikistä tai nimetä uudelleen.
Kokeile [[Special:Search|hakua]] löytääksesi asiaan liittyviä sivuja.',

# Revision deletion
'rev-deleted-comment'         => '(muokkausyhteenveto poistettu)',
'rev-deleted-user'            => '(käyttäjänimi poistettu)',
'rev-deleted-event'           => '(lokitapahtuma poistettu)',
'rev-deleted-user-contribs'   => '[käyttäjänimi tai IP-osoite poistettu – muokkaus piilotettu muokkauksista]',
'rev-deleted-text-permission' => "Tämä versio on '''poistettu'''.
Lisätietoja saattaa olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} poistolokissa].",
'rev-deleted-text-unhide'     => "Tämä versio sivusta on '''poistettu'''.
Lisätietoja saattaa olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} poistolokissa].
Ylläpitäjänä voit silti [$1 katsoa tämän version].",
'rev-suppressed-text-unhide'  => "Tämä versio sivusta on '''häivytetty'''.
Lisätietoja saattaa olla [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} häivytyslokissa].
Ylläpitäjänä voit silti [$1 katsoa tämän version].",
'rev-deleted-text-view'       => "Tämä versio on '''poistettu'''.
Ylläpitäjänä voit nähdä sen. Lisätietoja saattaa olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} poistolokissa].",
'rev-suppressed-text-view'    => "Tämä sivun versio on '''häivytetty'''.
Ylläpitäjänä voit nähdä sen. Lisätietoja saattaa olla [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} häivytyslokissa].",
'rev-deleted-no-diff'         => "Et voi katsoa tätä muokkausta, koska yksi versioista on '''poistettu'''.
Lisätietoja saattaa olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} poistolokissa].",
'rev-suppressed-no-diff'      => "Et voi katsoa tätä eroa koska yksi korjattu versio on '''poistettu'''.",
'rev-deleted-unhide-diff'     => "Yksi tämän muutoksen versioista on '''poistettu'''.
Lisätietoja saattaa olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} poistolokissa].
Ylläpitäjänä voit silti [$1 nähdä tämän muutoksen].",
'rev-suppressed-unhide-diff'  => "Yksi tämän muutosvertailun versioista on '''häivytetty'''.
Yksityiskohtia saattaa olla [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} häivytyslokissa].
Ylläpitäjänä voit silti [$1 nähdä tämän muutoksen], jos haluat jatkaa.",
'rev-deleted-diff-view'       => "Yksi tämän muutosvertailun versioista on '''poistettu'''.
Ylläpitäjänä voit silti nähdä tämän muutoksen. Lisätietoja saattaa olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} poistolokissa].",
'rev-suppressed-diff-view'    => "Yksi tämän muutosvertailun versioista on '''häivytetty'''.
Ylläpitäjänä voit silti nähdä tämän muutoksen. Lisätietoja saattaa olla [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} häivytyslokissa].",
'rev-delundel'                => 'näytä tai piilota',
'rev-showdeleted'             => 'näytä',
'revisiondelete'              => 'Poista tai palauta versioita',
'revdelete-nooldid-title'     => 'Ei kohdeversiota',
'revdelete-nooldid-text'      => 'Et ole valinnut kohdeversiota tai -versioita.',
'revdelete-nologtype-title'   => 'Lokityyppiä ei annettu',
'revdelete-nologtype-text'    => 'Et ole määritellyt lokin tyyppiä tälle toiminnolle.',
'revdelete-nologid-title'     => 'Epäkelpo lokitapahtuma',
'revdelete-nologid-text'      => 'Joko et ole määritellyt kohdetta lokitapahtumalle tämän toiminnon suorittamiseksi tai määriteltyä tapahtumaa ei ole.',
'revdelete-no-file'           => 'Määritettyä tiedostoa ei ole olemassa.',
'revdelete-show-file-confirm' => 'Haluatko varmasti nähdä poistetun version tiedostosta <nowiki>$1</nowiki>, joka on tallennettu $2 kello $3?',
'revdelete-show-file-submit'  => 'Kyllä',
'revdelete-selected'          => "'''{{PLURAL:$2|Valittu versio|Valitut versiot}} sivusta '''$1:''''''",
'logdelete-selected'          => "'''{{PLURAL:$1|Valittu lokimerkintä|Valitut lokimerkinnät}}:'''",
'revdelete-text'              => "'''Poistetut versiot näkyvät sivun historiassa, mutta niiden sisältö ei ole julkisesti saatavilla.'''
Muut ylläpitäjät {{GRAMMAR:inessive|{{SITENAME}}}} voivat silti lukea piilotetun sisällön ja he voivat palauttaa sen tämän käyttöliittymän kautta, ellei sitä ole rajoitettu.",
'revdelete-confirm'           => 'Varmista, että haluat tehdä tämän – ymmärrät seuraukset ja teet tämän [[{{MediaWiki:Policy-url}}|käytäntöjen]] mukaisesti.',
'revdelete-suppress-text'     => "Häivytystä pitäisi käyttää '''vain''' seuraavissa tapauksissa:
* Sopimattomat henkilötiedot
*: ''kotiosoitteet, puhelinnumerot, sosiaaliturvatunnukset ja muut.''",
'revdelete-legend'            => 'Version rajoitukset',
'revdelete-hide-text'         => 'Piilota version sisältö',
'revdelete-hide-image'        => 'Piilota tiedoston sisältö',
'revdelete-hide-name'         => 'Piilota toiminto ja kohde',
'revdelete-hide-comment'      => 'Piilota yhteenveto',
'revdelete-hide-user'         => 'Piilota tekijän tunnus tai IP-osoite',
'revdelete-hide-restricted'   => 'Piilota tiedot ylläpitäjiltä kuten muilta',
'revdelete-radio-same'        => '(älä muuta)',
'revdelete-radio-set'         => 'Kyllä',
'revdelete-radio-unset'       => 'Ei',
'revdelete-suppress'          => 'Piilota myös ylläpitäjiltä',
'revdelete-unsuppress'        => 'Poista rajoitukset palautetuilta versiolta',
'revdelete-log'               => 'Syy',
'revdelete-submit'            => 'Toteuta {{PLURAL:$1|valittuun versioon|valittuihin versioihin}}',
'revdelete-logentry'          => 'muutti sivun [[$1]] version näkyvyyttä',
'logdelete-logentry'          => 'muutti sivun [[$1]] näkyvyyttä',
'revdelete-success'           => "'''Version näkyvyys päivitetty.'''",
'revdelete-failure'           => "'''Version näkyvyyttä ei voitu päivittää:'''
$1",
'logdelete-success'           => 'Tapahtuman näkyvyys asetettu.',
'logdelete-failure'           => "'''Lokin näkyvyyttä ei voitu asettaa:'''
$1",
'revdel-restore'              => 'Muuta näkyvyyttä',
'revdel-restore-deleted'      => 'poistetut muutokset',
'revdel-restore-visible'      => 'näkyvät muutokset',
'pagehist'                    => 'Sivun muutoshistoria',
'deletedhist'                 => 'Poistettujen versioiden historia',
'revdelete-content'           => 'sisällön',
'revdelete-summary'           => 'yhteenvedon',
'revdelete-uname'             => 'käyttäjänimen',
'revdelete-restricted'        => 'asetti rajoitukset ylläpitäjille',
'revdelete-unrestricted'      => 'poisti rajoitukset ylläpitäjiltä',
'revdelete-hid'               => 'piilotti $1',
'revdelete-unhid'             => 'palautti näkyviin $1',
'revdelete-log-message'       => '$1 koskien $2 {{PLURAL:$2|versiota}}',
'logdelete-log-message'       => '$1 koskien $2 {{PLURAL:$2|tapahtumaa}}',
'revdelete-hide-current'      => 'Virhe tapahtui $2, $1 päivätyn kohteen piilottamisessa: tämä on nykyinen versio. Sitä ei voi piilottaa.',
'revdelete-show-no-access'    => 'Virhe näyttäessä kohtaa $2 kello $1: kohta on merkitty ”rajoitetuksi”.
Sinulla ei ole oikeutta siihen.',
'revdelete-modify-no-access'  => 'Virhe tapahtui $2, $1 kohteen muokkauksessa: tämä kohde on merkitty "rajoitetuksi". Sinulla ei ole oikeuksia sen muokkaukseen.',
'revdelete-modify-missing'    => 'Virhe muuttaessa kohdetta, jonka tunnus on $1: Se puuttuu tietokannasta.',
'revdelete-no-change'         => "'''Varoitus:''' kohdalle $2 kello $1 on asetettu valmiiksi näkyvyysasetuksia.",
'revdelete-concurrent-change' => 'Virhe $2, $1 päivätyn kohteen muokkauksessa: sen tilan on näköjään muuttanut joku sillä aikaa kun yritit muokata sitä. Ole hyvä ja tarkista lokit.',
'revdelete-only-restricted'   => 'Virhe piilotettaessa $1 kello $2 päivättyä kohdetta: Et voi poistaa kohteita ylläpitäjien näkyviltä valitsematta myös jotain muuta näkyvyysasetusta.',
'revdelete-reason-dropdown'   => '*Yleiset poistosyyt
** Tekijänoikeusrikkomus
** Sopimattomat henkilötiedot',
'revdelete-otherreason'       => 'Muu syy tai tarkennus',
'revdelete-reasonotherlist'   => 'Muu syy',
'revdelete-edit-reasonlist'   => 'Muokkaa poistosyitä',
'revdelete-offender'          => 'Version tekijä',

# Suppression log
'suppressionlog'     => 'Häivytysloki',
'suppressionlogtext' => 'Alla on lista uusimmista poistoista ja muokkausestoista, jotka sisältävät ylläpitäjiltä piilotettua materiaalia.
[[Special:IPBlockList|Muokkausestolistassa]] on tämänhetkiset muokkausestot.',

# History merging
'mergehistory'                     => 'Yhdistä sivuhistoriat',
'mergehistory-header'              => 'Tämä sivu mahdollistaa sivun muutoshistorian yhdistämisen uudemman sivun muutoshistoriaan.
Uuden ja vanhan sivun muutoksien pitää muodostaa jatkumo – ne eivät saa mennä ristikkäin.',
'mergehistory-box'                 => 'Yhdistä kahden sivun muutoshistoria',
'mergehistory-from'                => 'Lähdesivu',
'mergehistory-into'                => 'Kohdesivu',
'mergehistory-list'                => 'Liitettävissä olevat muutokset',
'mergehistory-merge'               => 'Seuraavat sivun [[:$1]] muutokset voidaan liittää sivun [[:$2]] muutoshistoriaan. Voit valita version, jota myöhempiä muutoksia ei liitetä. Selainlinkkien käyttäminen kadottaa tämän valinnan.',
'mergehistory-go'                  => 'Etsi muutokset',
'mergehistory-submit'              => 'Yhdistä versiot',
'mergehistory-empty'               => 'Ei liitettäviä muutoksia.',
'mergehistory-success'             => '{{PLURAL:$3|Yksi versio|$3 versiota}} sivusta [[:$1]] liitettiin sivuun [[:$2]].',
'mergehistory-fail'                => 'Muutoshistorian liittäminen epäonnistui. Tarkista määritellyt sivut ja versiot.',
'mergehistory-no-source'           => 'Lähdesivua $1 ei ole olemassa.',
'mergehistory-no-destination'      => 'Kohdesivua $1 ei ole olemassa.',
'mergehistory-invalid-source'      => 'Lähdesivulla pitää olla kelvollinen nimi.',
'mergehistory-invalid-destination' => 'Kohdesivulla pitää olla kelvollinen nimi.',
'mergehistory-autocomment'         => 'Yhdisti sivun [[:$1]] sivuun [[:$2]]',
'mergehistory-comment'             => 'Yhdisti sivun [[:$1]] sivuun [[:$2]]: $3',
'mergehistory-same-destination'    => 'Lähde- ja kohdesivut eivät voi olla samat',
'mergehistory-reason'              => 'Syy',

# Merge log
'mergelog'           => 'Yhdistämisloki',
'pagemerge-logentry' => 'liitti sivun [[$1]] sivuun [[$2]] (muokkaukseen $3 asti)',
'revertmerge'        => 'Kumoa yhdistäminen',
'mergelogpagetext'   => 'Alla on loki viimeisimmistä muutoshistorioiden yhdistämisistä.',

# Diffs
'history-title'            => 'Sivun $1 muutoshistoria',
'difference'               => 'Versioiden väliset erot',
'difference-multipage'     => '(Sivujen välinen eroavaisuus)',
'lineno'                   => 'Rivi $1:',
'compareselectedversions'  => 'Vertaile valittuja versioita',
'showhideselectedversions' => 'Näytä tai piilota valitut versiot',
'editundo'                 => 'kumoa',
'diff-multi'               => '(Näytettyjen versioiden välissä on {{PLURAL:$1|yksi muokkaus|$1 versiota, jotka ovat {{PLURAL:$2|yhden käyttäjän tekemiä|$2 eri käyttäjän tekemiä}}}}.)',
'diff-multi-manyusers'     => '(Versioiden välissä on {{PLURAL:$1|yksi muu muokkaus|$1 muuta muokkausta, jotka on tehnyt {{PLURAL:$2|yksi käyttäjä|yli $2 eri käyttäjää}}}}.)',

# Search results
'searchresults'                    => 'Hakutulokset',
'searchresults-title'              => 'Haun tulokset hakusanalle ”$1”',
'searchresulttext'                 => 'Lisätietoa {{GRAMMAR:genitive|{{SITENAME}}}} hakutoiminnoista on [[{{MediaWiki:Helppage}}|ohjesivulla]].',
'searchsubtitle'                   => "Etsit termillä '''[[:$1]]''' ([[Special:Prefixindex/$1|kaikki sivut alkaen termillä ”$1”]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|viittaukset sivuun ”$1”]])",
'searchsubtitleinvalid'            => 'Haku termeillä $1',
'toomanymatches'                   => 'Liian monta osumaa. Kokeile erilaista kyselyä.',
'titlematches'                     => 'Osumat sivujen otsikoissa',
'notitlematches'                   => 'Hakusanaa ei löytynyt minkään sivun otsikosta',
'textmatches'                      => 'Osumat sivujen teksteissä',
'notextmatches'                    => 'Hakusanaa ei löytynyt sivujen teksteistä',
'prevn'                            => '← {{PLURAL:$1|edellinen|$1 edellistä}}',
'nextn'                            => '{{PLURAL:$1|seuraava|$1 seuraavaa}} →',
'prevn-title'                      => '{{PLURAL:$1|Edellinen osuma|Edelliset $1 osumaa}}',
'nextn-title'                      => '{{PLURAL:$1|Seuraava osuma|Seuraavat $1 osumaa}}',
'shown-title'                      => 'Näytä $1 {{PLURAL:$1|osuma|osumaa}} sivulla',
'viewprevnext'                     => 'Näytä [$3] kerralla.

$1 {{int:pipe-separator}} $2',
'searchmenu-legend'                => 'Hakuasetukset',
'searchmenu-exists'                => "'''Sivu [[:$1]] löytyy tästä wikistä.'''",
'searchmenu-new'                   => "'''Luo sivu ''[[:$1]]'' tähän wikiin.'''",
'searchhelp-url'                   => 'Help:Sisällys',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Selaa sivuja tällä etuliitteellä]]',
'searchprofile-articles'           => 'Sisältösivut',
'searchprofile-project'            => 'Ohje- ja projektisivut',
'searchprofile-images'             => 'Kuvat ja tiedostot',
'searchprofile-everything'         => 'Kaikki',
'searchprofile-advanced'           => 'Laajennettu',
'searchprofile-articles-tooltip'   => 'Hae nimiavaruuksista $1',
'searchprofile-project-tooltip'    => 'Hae nimiavaruuksista $1',
'searchprofile-images-tooltip'     => 'Etsi tiedostoja',
'searchprofile-everything-tooltip' => 'Etsi kaikkialta (myös keskustelusivut)',
'searchprofile-advanced-tooltip'   => 'Etsi määritellyistä nimiavaruuksista',
'search-result-size'               => '$1 ({{PLURAL:$2|1 sana|$2 sanaa}})',
'search-result-category-size'      => '{{PLURAL:$1|1 jäsen|$1 jäsentä}} ({{PLURAL:$2|1 alaluokka|$2 alaluokkaa}}, {{PLURAL:$3|1 tiedosto|$3 tiedostoa}})',
'search-result-score'              => 'Asiaankuuluvuus: $1%',
'search-redirect'                  => '(ohjaus $1)',
'search-section'                   => '(osio $1)',
'search-suggest'                   => 'Tarkoititko: $1',
'search-interwiki-caption'         => 'Sisarprojektit',
'search-interwiki-default'         => 'Tulokset osoitteesta $1:',
'search-interwiki-more'            => '(lisää)',
'search-mwsuggest-enabled'         => 'näytä ehdotukset',
'search-mwsuggest-disabled'        => 'ilman ehdotuksia',
'search-relatedarticle'            => 'Hae samankaltaisia sivuja',
'mwsuggest-disable'                => 'Älä näytä ehdotuksia AJAXilla',
'searcheverything-enable'          => 'Hae kaikista nimiavaruuksista',
'searchrelated'                    => 'samankaltainen',
'searchall'                        => 'kaikki',
'showingresults'                   => "{{PLURAL:$1|'''Yksi''' tulos|'''$1''' tulosta}} tuloksesta '''$2''' alkaen.",
'showingresultsnum'                => "Alla on {{PLURAL:$3|'''Yksi''' hakutulos|'''$3''' hakutulosta}} alkaen '''$2.''' tuloksesta.",
'showingresultsheader'             => "{{PLURAL:$5|Tulokset '''$1'''–'''$3'''|Tulokset '''$1'''–'''$2''' kaikkiaan '''$3''' osuman joukosto}} haulle '''$4'''",
'nonefound'                        => "'''Huomautus''': Haku kohdistuu oletuksena vain tiettyihin nimiavaruuksiin.
Kokeile lisätä haun alkuun ''all:'', niin haku kohdistuu kaikkeen sisältöön (mukaanlukien keskustelut, mallineet jne.) tai kohdista haku haluttuun nimiavaruuteen.",
'search-nonefound'                 => 'Hakusi ei tuottanut tulosta.',
'powersearch'                      => 'Etsi',
'powersearch-legend'               => 'Laajennettu haku',
'powersearch-ns'                   => 'Hae nimiavaruuksista:',
'powersearch-redir'                => 'Luettele ohjaukset',
'powersearch-field'                => 'Etsi',
'powersearch-togglelabel'          => 'Muuta valinta',
'powersearch-toggleall'            => 'Valitse kaikki',
'powersearch-togglenone'           => 'Poista kaikki',
'search-external'                  => 'Ulkoinen haku',
'searchdisabled'                   => 'Tekstihaku on poistettu toistaiseksi käytöstä suuren kuorman vuoksi. Voit käyttää alla olevaa Googlen hakukenttää sivujen etsimiseen, kunnes haku tulee taas käyttöön. <small>Huomaa, että ulkopuoliset kopiot {{GRAMMAR:genitive|{{SITENAME}}}} sisällöstä eivät välttämättä ole ajan tasalla.</small>',

# Quickbar
'qbsettings'               => 'Pikavalikko',
'qbsettings-none'          => 'Ei mitään',
'qbsettings-fixedleft'     => 'Tekstin mukana, vasen',
'qbsettings-fixedright'    => 'Tekstin mukana, oikea',
'qbsettings-floatingleft'  => 'Pysyen vasemmalla',
'qbsettings-floatingright' => 'Pysyen oikealla',

# Preferences page
'preferences'                   => 'Asetukset',
'mypreferences'                 => 'Asetukset',
'prefs-edits'                   => 'Muokkauksia',
'prefsnologin'                  => 'Et ole kirjautunut sisään.',
'prefsnologintext'              => 'Sinun täytyy <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} kirjautua sisään]</span>, jotta voisit muuttaa asetuksiasi.',
'changepassword'                => 'Salasanan vaihto',
'prefs-skin'                    => 'Ulkoasu',
'skin-preview'                  => 'esikatselu',
'prefs-math'                    => 'Matematiikka',
'datedefault'                   => 'Ei valintaa',
'prefs-datetime'                => 'Aika ja päiväys',
'prefs-personal'                => 'Käyttäjätiedot',
'prefs-rc'                      => 'Tuoreet muutokset',
'prefs-watchlist'               => 'Tarkkailulista',
'prefs-watchlist-days'          => 'Tarkkailulistan ajanjakso',
'prefs-watchlist-days-max'      => 'Enintään 7 päivää',
'prefs-watchlist-edits'         => 'Tarkkailulistalla näytettävien muokkausten määrä',
'prefs-watchlist-edits-max'     => 'Enintään 1000',
'prefs-watchlist-token'         => 'Tarkkailulistan avain',
'prefs-misc'                    => 'Muut',
'prefs-resetpass'               => 'Muuta salasana',
'prefs-email'                   => 'Sähköpostiasetukset',
'prefs-rendering'               => 'Ulkoasu',
'saveprefs'                     => 'Tallenna asetukset',
'resetprefs'                    => 'Palauta tallennetut asetukset',
'restoreprefs'                  => 'Palauta kaikki oletusasetuksiin',
'prefs-editing'                 => 'Muokkaus',
'prefs-edit-boxsize'            => 'Muokkauskentän koko.',
'rows'                          => 'Rivejä',
'columns'                       => 'Sarakkeita',
'searchresultshead'             => 'Haku',
'resultsperpage'                => 'Tuloksia sivua kohti',
'contextlines'                  => 'Rivien määrä tulosta kohti',
'contextchars'                  => 'Sisällön merkkien määrä riviä kohden',
'stub-threshold'                => '<a href="#" class="stub">Tynkäsivun</a> osoituskynnys',
'stub-threshold-disabled'       => 'Ei käytössä',
'recentchangesdays'             => 'Näytettävien päivien määrä tuoreissa muutoksissa',
'recentchangesdays-max'         => 'Enintään $1 {{PLURAL:$1|päivä|päivää}}',
'recentchangescount'            => 'Näytettävien muutoksien määrä oletuksena',
'prefs-help-recentchangescount' => 'Tämä sisältää tuoreet muutokset, muutoshistoriat ja lokit.',
'prefs-help-watchlist-token'    => 'Tämän kentän täyttäminen salaisella avaimella tuottaa RSS-syötteen tarkkailulistastasi.
Kaikki, jotka tietävät tähän kenttään kirjoitetun avaimen pystyvät lukemaan tarkkailulistaa, joten valitse turvallinen arvo.
Tässä satunnaisesti tuotettu arvo, jota voit käyttää: $1',
'savedprefs'                    => 'Asetuksesi tallennettiin onnistuneesti.',
'timezonelegend'                => 'Aikavyöhyke',
'localtime'                     => 'Paikallinen aika',
'timezoneuseserverdefault'      => 'Käytä palvelimen oletusta',
'timezoneuseoffset'             => 'Muu (määritä aikaero)',
'timezoneoffset'                => 'Aikaero',
'servertime'                    => 'Palvelimen aika',
'guesstimezone'                 => 'Utele selaimelta',
'timezoneregion-africa'         => 'Afrikka',
'timezoneregion-america'        => 'Amerikka',
'timezoneregion-antarctica'     => 'Etelämanner',
'timezoneregion-arctic'         => 'Arktinen alue',
'timezoneregion-asia'           => 'Aasia',
'timezoneregion-atlantic'       => 'Atlantin valtameri',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Eurooppa',
'timezoneregion-indian'         => 'Intian valtameri',
'timezoneregion-pacific'        => 'Tyynimeri',
'allowemail'                    => 'Salli sähköpostin lähetys osoitteeseen',
'prefs-searchoptions'           => 'Haku',
'prefs-namespaces'              => 'Nimiavaruudet',
'defaultns'                     => 'Muussa tapauksessa hae näistä nimiavaruuksista:',
'default'                       => 'oletus',
'prefs-files'                   => 'Tiedostot',
'prefs-custom-css'              => 'Käyttäjäkohtainen CSS-tyylisivu',
'prefs-custom-js'               => 'Käyttäjäkohtainen JavaScript-sivu',
'prefs-common-css-js'           => 'Yhteiset CSS- ja JavaScript-sivut kaikille ulkoasuille',
'prefs-reset-intro'             => 'Voit käyttää tätä sivua palauttaaksesi kaikki asetuksesi sivuston oletusasetuksiin. Tätä ei voi kumota.',
'prefs-emailconfirm-label'      => 'Sähköpostin varmistus',
'prefs-textboxsize'             => 'Muokkauskentän koko',
'youremail'                     => 'Sähköpostiosoite',
'username'                      => 'Käyttäjätunnus',
'uid'                           => 'Numero',
'prefs-memberingroups'          => 'Jäsenenä {{PLURAL:$1|ryhmässä|ryhmissä}}',
'prefs-registration'            => 'Rekisteröintiaika',
'yourrealname'                  => 'Oikea nimi',
'yourlanguage'                  => 'Käyttöliittymän kieli',
'yourvariant'                   => 'Kielivariantti',
'yournick'                      => 'Allekirjoitus',
'prefs-help-signature'          => 'Kommentit keskustelusivuilla allekirjoitetaan merkinnällä <nowiki>~~~~</nowiki>, joka muuntuu allekirjoitukseksi ja aikaleimaksi.',
'badsig'                        => 'Allekirjoitus ei kelpaa.',
'badsiglength'                  => 'Allekirjoitus on liian pitkä – sen on oltava alle $1 {{PLURAL:$1|merkki|merkkiä}}.',
'yourgender'                    => 'Sukupuoli',
'gender-unknown'                => 'Määrittelemätön',
'gender-male'                   => 'Mies',
'gender-female'                 => 'Nainen',
'prefs-help-gender'             => 'Vapaaehtoinen. Tietoa käytetään ohjelmistossa kielellisesti oikeaan ilmaisuun. Tämä tieto on julkinen.',
'email'                         => 'Sähköpostitoiminnot',
'prefs-help-realname'           => 'Vapaaehtoinen. Nimesi näytetään käyttäjätunnuksesi sijasta sivun tekijäluettelossa.',
'prefs-help-email'              => 'Vapaaehtoinen. Mahdollistaa uuden salasanan pyytämisen, jos unohdat salasanasi. Voit myös sallia muiden käyttäjien ottaa sinuun yhteyttä sähköpostilla ilman, että osoitteesi paljastuu.',
'prefs-help-email-required'     => 'Sähköpostiosoite on pakollinen.',
'prefs-info'                    => 'Perustiedot',
'prefs-i18n'                    => 'Kieli',
'prefs-signature'               => 'Allekirjoitus',
'prefs-dateformat'              => 'Päiväyksen muoto',
'prefs-timeoffset'              => 'Aikavyöhyke',
'prefs-advancedediting'         => 'Lisäasetukset',
'prefs-advancedrc'              => 'Lisäasetukset',
'prefs-advancedrendering'       => 'Lisäasetukset',
'prefs-advancedsearchoptions'   => 'Lisäasetukset',
'prefs-advancedwatchlist'       => 'Lisäasetukset',
'prefs-displayrc'               => 'Perusasetukset',
'prefs-displaysearchoptions'    => 'Näyttöasetukset',
'prefs-displaywatchlist'        => 'Näyttöasetukset',
'prefs-diffs'                   => 'Erot',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'Sähköpostiosoite vaikuttaa kelvolliselta',
'email-address-validity-invalid' => 'Virheellinen sähköpostiosoite',

# User rights
'userrights'                   => 'Käyttöoikeuksien hallinta',
'userrights-lookup-user'       => 'Käyttöoikeuksien hallinta',
'userrights-user-editname'     => 'Käyttäjätunnus',
'editusergroup'                => 'Muokkaa käyttäjän ryhmiä',
'editinguser'                  => "Käyttäjän '''[[User:$1|$1]]''' oikeudet ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Käyttäjän ryhmät',
'saveusergroups'               => 'Tallenna',
'userrights-groupsmember'      => 'Käyttäjä on jäsenenä ryhmissä',
'userrights-groupsmember-auto' => 'Virtuaaliset ryhmät:',
'userrights-groups-help'       => 'Voit muuttaa ryhmiä, joissa tämä käyttäjä on.
* Merkattu valintaruutu tarkoittaa, että käyttäjä on kyseisessä ryhmässä.
* Merkkaamaton valintaruutu tarkoittaa, että käyttäjä ei ole kyseisessä ryhmässä.
* <nowiki>*</nowiki> tarkoittaa, että et pysty kumoamaan kyseistä operaatiota.',
'userrights-reason'            => 'Syy',
'userrights-no-interwiki'      => 'Sinulla ei ole lupaa muokata käyttöoikeuksia muissa wikeissä.',
'userrights-nodatabase'        => 'Tietokantaa $1 ei ole tai se ei ole paikallinen.',
'userrights-nologin'           => 'Sinun täytyy [[Special:UserLogin|kirjautua sisään]] ylläpitäjätunnuksella, jotta voisit muuttaa käyttöoikeuksia.',
'userrights-notallowed'        => 'Tunnuksellasi ei ole lupaa muuttaa käyttöoikeuksia.',
'userrights-changeable-col'    => 'Ryhmät, joita voit muuttaa',
'userrights-unchangeable-col'  => 'Ryhmät, joita et voi muuttaa',

# Groups
'group'               => 'Ryhmä',
'group-user'          => 'käyttäjät',
'group-autoconfirmed' => 'automaattisesti hyväksytyt käyttäjät',
'group-bot'           => 'botit',
'group-sysop'         => 'ylläpitäjät',
'group-bureaucrat'    => 'byrokraatit',
'group-suppress'      => 'häivytysoikeuden käyttäjät',
'group-all'           => '(kaikki)',

'group-user-member'          => 'käyttäjä',
'group-autoconfirmed-member' => 'automaattisesti hyväksytty käyttäjä',
'group-bot-member'           => 'botti',
'group-sysop-member'         => 'ylläpitäjä',
'group-bureaucrat-member'    => 'byrokraatti',
'group-suppress-member'      => 'häivytysoikeuden käyttäjä',

'grouppage-user'          => '{{ns:project}}:Käyttäjät',
'grouppage-autoconfirmed' => '{{ns:project}}:Automaattisesti hyväksytyt käyttäjät',
'grouppage-bot'           => '{{ns:project}}:Botit',
'grouppage-sysop'         => '{{ns:project}}:Ylläpitäjät',
'grouppage-bureaucrat'    => '{{ns:project}}:Byrokraatit',
'grouppage-suppress'      => '{{ns:project}}:Häivytysoikeudet',

# Rights
'right-read'                  => 'Lukea sivuja',
'right-edit'                  => 'Muokata sivuja',
'right-createpage'            => 'Luoda sivuja pois lukien keskustelusivut',
'right-createtalk'            => 'Luoda keskustelusivuja',
'right-createaccount'         => 'Luoda uusia käyttäjätunnuksia',
'right-minoredit'             => 'Merkitä muokkauksensa pieniksi',
'right-move'                  => 'Siirtää sivuja',
'right-move-subpages'         => 'Siirtää sivuja alasivuineen',
'right-move-rootuserpages'    => 'Siirtää käyttäjäsivuja',
'right-movefile'              => 'Siirtää tiedostoja',
'right-suppressredirect'      => 'Siirtää sivuja luomatta automaattisia ohjauksia',
'right-upload'                => 'Tallentaa tiedostoja',
'right-reupload'              => 'Korvata olemassa olevia tiedostoja uudella',
'right-reupload-own'          => 'Korvata itsetallennettu tiedosto uudella tiedostolla',
'right-reupload-shared'       => 'Korvata jaettuun mediavarastoon tallennettuja tiedostoja paikallisesti',
'right-upload_by_url'         => 'Tallentaa tiedostoja verkko-osoitteella',
'right-purge'                 => 'Päivittää tiedoston välimuistitetun version ilman varmennussivua',
'right-autoconfirmed'         => 'Muokata osittain suojattuja sivuja',
'right-bot'                   => 'Kohdellaan automaattisena prosessina',
'right-nominornewtalk'        => 'Tehdä pieniä muokkauksia käyttäjien keskustelusivuille siten, että käyttäjälle ei ilmoiteta siitä uutena viestinä',
'right-apihighlimits'         => 'Käyttää korkeampia rajoja API-kyselyissä',
'right-writeapi'              => 'Käyttää kirjoitus-APIa',
'right-delete'                => 'Poistaa sivuja',
'right-bigdelete'             => 'Poistaa sivuja, joilla on pitkä historia',
'right-deleterevision'        => 'Poistaa ja palauttaa sivujen versioita',
'right-deletedhistory'        => 'Tarkastella poistettuja versiotietoja ilman niihin liittyvää sisältöä',
'right-deletedtext'           => 'Tarkastella poistettujen sivujen tekstiä ja muutoksia poistettujen versioiden välillä',
'right-browsearchive'         => 'Tarkastella poistettuja sivuja',
'right-undelete'              => 'Palauttaa sivuja',
'right-suppressrevision'      => 'Tarkastella ja palauttaa ylläpitäjiltä piilotettuja versioita',
'right-suppressionlog'        => 'Tarkastella yksityisiä lokeja',
'right-block'                 => 'Asettaa toiselle käyttäjälle muokkausesto',
'right-blockemail'            => 'Estää käyttäjää lähettämästä sähköpostia',
'right-hideuser'              => 'Estää käyttäjätunnus ja piilottaa se näkyvistä',
'right-ipblock-exempt'        => 'Ohittaa IP-, automaattiset ja osoitealue-estot',
'right-proxyunbannable'       => 'Ohittaa automaattiset välityspalvelinestot',
'right-unblockself'           => 'Poistaa esto itseltään',
'right-protect'               => 'Muuttaa sivujen suojauksia ja muokata suojattuja sivuja',
'right-editprotected'         => 'Muokata suojattuja sivuja (pois lukien laajennettu sisällytyssuojaus)',
'right-editinterface'         => 'Muokata käyttöliittymätekstejä',
'right-editusercssjs'         => 'Muokata toisten käyttäjien CSS- ja JavaScript-tiedostoja',
'right-editusercss'           => 'Muokata toisten käyttäjien CSS-tiedostoja',
'right-edituserjs'            => 'Muokata toisten käyttäjien JavaScript-tiedostoja',
'right-rollback'              => 'Palauttaa nopeasti käyttäjän viimeisimmät muokkaukset sivuun',
'right-markbotedits'          => 'Kumota muokkauksia bottimerkinnällä',
'right-noratelimit'           => 'Ohittaa nopeusrajoitukset',
'right-import'                => 'Tuoda sivuja muista wikeistä',
'right-importupload'          => 'Tuoda sivuja tiedostosta',
'right-patrol'                => 'Merkitä muokkaukset tarkastetuiksi',
'right-autopatrol'            => 'Muokkaukset aina valmiiksi tarkastetuksi merkittyjä',
'right-patrolmarks'           => 'Nähdä tarkastusmerkit tuoreissa muutoksissa',
'right-unwatchedpages'        => 'Tarkastella listaa tarkkailemattomista sivuista',
'right-trackback'             => 'Lähettää trackback',
'right-mergehistory'          => 'Yhdistää sivujen historioita',
'right-userrights'            => 'Muuttaa kaikkia käyttäjäoikeuksia',
'right-userrights-interwiki'  => 'Muokata käyttäjien oikeuksia muissa wikeissä',
'right-siteadmin'             => 'Lukita tietokanta',
'right-reset-passwords'       => 'Alustaa muiden käyttäjien salasanoja',
'right-override-export-depth' => 'Viedä sivuja sisältäen viitatut sivut viiden syvyydellä',
'right-sendemail'             => 'Lähettää sähköpostia muille käyttäjille',

# User rights log
'rightslog'      => 'Käyttöoikeusloki',
'rightslogtext'  => 'Tämä on loki käyttäjien käyttöoikeuksien muutoksista.',
'rightslogentry' => 'muutti käyttäjän $1 oikeudet ryhmistä $2 ryhmiin $3',
'rightsnone'     => '(ei oikeuksia)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'lukea tätä sivua',
'action-edit'                 => 'muokata tätä sivua',
'action-createpage'           => 'luoda sivuja',
'action-createtalk'           => 'luoda keskustelusivuja',
'action-createaccount'        => 'luoda tätä käyttäjätunnusta',
'action-minoredit'            => 'merkitä tätä muokkausta pieneksi',
'action-move'                 => 'siirtää tätä sivua',
'action-move-subpages'        => 'siirtää tätä sivua, ja sen alasivuja',
'action-move-rootuserpages'   => 'siirtää käyttäjäsivuja',
'action-movefile'             => 'siirtää tätä tiedostoa',
'action-upload'               => 'tallentaa tätä tiedostoa',
'action-reupload'             => 'korvata tätä olemassa olevaa tiedostoa',
'action-reupload-shared'      => 'korvata tätä jaetun mediavaraston tiedostoa',
'action-upload_by_url'        => 'tallentaa tätä tiedostoa URL-osoitteesta',
'action-writeapi'             => 'käyttää kirjoitus-APIa',
'action-delete'               => 'poistaa tätä sivua',
'action-deleterevision'       => 'poistaa tätä versiota',
'action-deletedhistory'       => 'tarkastella tämän sivun poistettua historiaa',
'action-browsearchive'        => 'etsiä poistettuja sivuja',
'action-undelete'             => 'palauttaa tätä sivua',
'action-suppressrevision'     => 'tarkastella ja palauttaa tätä piilotettua versiota',
'action-suppressionlog'       => 'tarkastella tätä yksityislokia',
'action-block'                => 'estää tätä käyttäjää muokkaamasta',
'action-protect'              => 'muuttaa tämän sivun suojaustasoa',
'action-import'               => 'tuoda tätä sivua toisesta wikistä',
'action-importupload'         => 'tuoda tätä sivua tiedostosta',
'action-patrol'               => 'merkitä muiden muokkauksia tarkastetuiksi',
'action-autopatrol'           => 'saada muokkaukset automaattisesti tarkastetuiksi',
'action-unwatchedpages'       => 'tarkastella tarkkailemattomien sivujen listaa',
'action-trackback'            => 'lähettää trackbackia',
'action-mergehistory'         => 'yhdistää tämän sivun historiaa',
'action-userrights'           => 'muokata kaikkia käyttöoikeuksia',
'action-userrights-interwiki' => 'muokata muiden wikien käyttäjien käyttöoikeuksia',
'action-siteadmin'            => 'lukita tai avata tietokantaa',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|muutos|muutosta}}',
'recentchanges'                     => 'Tuoreet muutokset',
'recentchanges-legend'              => 'Tuoreiden muutosten asetukset',
'recentchangestext'                 => 'Tällä sivulla voi seurata tuoreita {{GRAMMAR:illative|{{SITENAME}}}} tehtyjä muutoksia.',
'recentchanges-feed-description'    => 'Tällä sivulla voi seurata tuoreita {{GRAMMAR:illative|{{SITENAME}}}} tehtyjä muutoksia.',
'recentchanges-label-newpage'       => 'Tämä muutos loi uuden sivun',
'recentchanges-label-minor'         => 'Tämä on pieni muutos',
'recentchanges-label-bot'           => 'Tämän muutoksen suoritti botti',
'recentchanges-label-unpatrolled'   => 'Tätä muutosta ei ole vielä tarkastettu',
'rcnote'                            => 'Alla on {{PLURAL:$1|yksi muutos|$1 tuoreinta muutosta}} {{PLURAL:$2|yhden päivän|$2 viime päivän}} ajalta $4 kello $5 asti.',
'rcnotefrom'                        => 'Alla on muutokset <b>$2</b> lähtien. Enintään <b>$1</b> merkintää näytetään.',
'rclistfrom'                        => 'Näytä uudet muutokset $1 alkaen',
'rcshowhideminor'                   => '$1 pienet muutokset',
'rcshowhidebots'                    => '$1 botit',
'rcshowhideliu'                     => '$1 kirjautuneet käyttäjät',
'rcshowhideanons'                   => '$1 anonyymit käyttäjät',
'rcshowhidepatr'                    => '$1 tarkastetut muutokset',
'rcshowhidemine'                    => '$1 omat muutokset',
'rclinks'                           => 'Näytä $1 tuoretta muutosta viimeisten $2 päivän ajalta.<br />$3',
'diff'                              => 'ero',
'hist'                              => 'historia',
'hide'                              => 'Piilota',
'show'                              => 'Näytä',
'minoreditletter'                   => 'p',
'newpageletter'                     => 'U',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|tarkkaileva käyttäjä|tarkkailevaa käyttäjää}}]',
'rc_categories'                     => 'Vain luokista (erotin on ”|”)',
'rc_categories_any'                 => 'Mikä tahansa',
'newsectionsummary'                 => '/* $1 */ uusi osio',
'rc-enhanced-expand'                => 'Näytä yksityiskohdat (JavaScript)',
'rc-enhanced-hide'                  => 'Piilota yksityiskohdat',

# Recent changes linked
'recentchangeslinked'          => 'Linkitettyjen sivujen muutokset',
'recentchangeslinked-feed'     => 'Linkitettyjen sivujen muutokset',
'recentchangeslinked-toolbox'  => 'Linkitettyjen sivujen muutokset',
'recentchangeslinked-title'    => 'Sivulta $1 linkitettyjen sivujen muutokset',
'recentchangeslinked-noresult' => 'Ei muutoksia linkitettyihin sivuihin annetulla aikavälillä.',
'recentchangeslinked-summary'  => "Tämä toimintosivu näyttää muutokset sivuihin, joihin on viitattu tältä sivulta. [[Special:Watchlist|Tarkkailulistallasi]] olevat sivut on '''lihavoitu'''.",
'recentchangeslinked-page'     => 'Sivu',
'recentchangeslinked-to'       => 'Näytä muutokset sivuihin, joilla on linkki annettuun sivuun',

# Upload
'upload'                      => 'Tallenna tiedosto',
'uploadbtn'                   => 'Tallenna tiedosto',
'reuploaddesc'                => 'Peruuta tallennus ja palaa tallennuslomakkeelle.',
'upload-tryagain'             => 'Lähetä muutettu tiedostokuvaus',
'uploadnologin'               => 'Et ole kirjautunut sisään',
'uploadnologintext'           => 'Sinun pitää olla [[Special:UserLogin|kirjautuneena sisään]], jotta voisit tallentaa tiedostoja.',
'upload_directory_missing'    => 'Tallennushakemisto $1 puuttuu, eikä palvelin pysty luomaan sitä.',
'upload_directory_read_only'  => 'Palvelimella ei ole kirjoitusoikeuksia tallennushakemistoon ”<tt>$1</tt>”.',
'uploaderror'                 => 'Tallennusvirhe',
'upload-recreate-warning'     => "'''Varoitus: Tiedosto tällä nimellä on poistettu tai siirretty.'''

Poisto- ja siirtoloki tälle sivulle näkyy alla:",
'uploadtext'                  => "Voit tallentaa tiedostoja alla olevalla lomakkeella. [[Special:FileList|Tiedostoluettelo]] sisältää listan tallennetuista tiedostoista. Tallennukset kirjataan myös [[Special:Log/upload|tallennuslokiin]], ja poistot [[Special:Log/delete|poistolokiin]].

Voit käyttää tiedostoja wikisivuilla seuraavilla tavoilla:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Tiedosto.jpg]]</nowiki></tt>''', käyttääksesi tiedoston täyttä versiota.
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Tiedosto.png|200px|thumb|left|Kuvausteksti]]</nowiki></tt>''', käyttääksesi tiedostoa sovitettuna 200 kuvapistettä leveään laatikkoon kuvaustekstillä.
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Tiedosto.ogg]]</nowiki></tt>''', jos haluat suoran linkin tiedostoon.",
'upload-permitted'            => 'Sallitut tiedostomuodot: $1.',
'upload-preferred'            => 'Suositellut tiedostomuodot: $1.',
'upload-prohibited'           => 'Kielletyt tiedostomuodot: $1.',
'uploadlog'                   => 'Tiedostoloki',
'uploadlogpage'               => 'Tiedostoloki',
'uploadlogpagetext'           => 'Alla on luettelo uusimmista tiedostonlisäyksistä. Kaikki ajat näytetään palvelimen aikavyöhykkeessä.',
'filename'                    => 'Tiedoston nimi:',
'filedesc'                    => 'Yhteenveto',
'fileuploadsummary'           => 'Yhteenveto',
'filereuploadsummary'         => 'Muutokset',
'filestatus'                  => 'Tiedoston tekijänoikeudet',
'filesource'                  => 'Lähde',
'uploadedfiles'               => 'Lisätyt tiedostot',
'ignorewarning'               => 'Tallenna tiedosto varoituksesta huolimatta.',
'ignorewarnings'              => 'Ohita kaikki varoitukset',
'minlength1'                  => 'Tiedoston nimessä pitää olla vähintään yksi merkki.',
'illegalfilename'             => "Tiedoston nimessä '''$1''' on merkkejä, joita ei sallita sivujen nimissä. Vaihda tiedoston nimeä, ja yritä lähettämistä uudelleen.",
'badfilename'                 => 'Tiedoston nimi vaihdettiin: $1.',
'filetype-mime-mismatch'      => 'Tiedostopääte ei vastaa MIME-tyyppiä.',
'filetype-badmime'            => '<tt>$1</tt> -tyypisten tiedostojen tallennus on kielletty.',
'filetype-bad-ie-mime'        => 'Tiedostoa ei voi tallentaa, koska Internet Explorer tunnistaa sen kielletyksi tiedostomuodoksi $1, joka on mahdollisesti vaarallinen.',
'filetype-unwanted-type'      => "'''.$1''' ei ole toivottu tiedostomuoto. {{PLURAL:$3|Suositeltu tiedostomuoto on|Suositeltuja tiedostomuotoja ovat}} $2.",
'filetype-banned-type'        => "'''.$1''' ei ole sallittu tiedostomuoto. {{PLURAL:$3|Sallittu tiedostomuoto on|Sallittuja tiedostomuotoja ovat}} $2.",
'filetype-missing'            => 'Tiedostolta puuttuu tiedostopääte – esimerkiksi <tt>.jpg</tt>.',
'empty-file'                  => 'Lähettämäsi tiedosto oli tyhjä.',
'file-too-large'              => 'Lähettämäsi tiedosto oli liian suuri.',
'filename-tooshort'           => 'Tiedostonimi on liian lyhyt.',
'filetype-banned'             => 'Tämä tiedostomuoto on estetty.',
'verification-error'          => 'Tämä tiedosto ei läpäissyt tiedoston tarkistusta.',
'hookaborted'                 => 'Laajennuksen kytköspiste keskeytti muutoksen, jota yritit tehdä.',
'illegal-filename'            => 'Tiedostonimi ei ole sallittu.',
'overwrite'                   => 'Olemassa olevan tiedoston korvaaminen ei ole sallittu.',
'unknown-error'               => 'Tapahtui tuntematon virhe.',
'tmp-create-error'            => 'Väliaikaistiedostoa ei voitu luoda.',
'tmp-write-error'             => 'Virhe kirjoitettaessa väliaikaistiedostoon.',
'large-file'                  => 'Tiedostojen enimmäiskoko on $1. Lähettämäsi tiedoston koko on $2.',
'largefileserver'             => 'Tämä tiedosto on suurempi kuin mitä palvelin sallii.',
'emptyfile'                   => 'Tiedosto, jota yritit lähettää, näyttää olevan tyhjä. Tarkista, että kirjoitit polun ja nimen oikein ja että se ei ole liian suuri kohdepalvelimelle.',
'fileexists'                  => "Samanniminen tiedosto on jo olemassa.
Katso tiedoston sivu '''<tt>[[:$1]]</tt>''', jos et ole varma, haluatko muuttaa sitä.
[[$1|thumb]]",
'filepageexists'              => "Kuvaussivu on jo olemassa tällä nimellä '''<tt>[[:$1]]</tt>''', mutta ei tiedostoa tällä nimellä.
Kirjoittamasi yhteenveto ei ilmesty kuvaussivulle.
Muuttaaksesi uuden yhteenvedon kuvaussivulle, sinun täytyy manuaalisesti muokata sitä.
[[$1|thumb]]",
'fileexists-extension'        => "Tiedosto, jolla on samankaltainen nimi, on jo olemassa: [[$2|thumb]]
* Tallennetun tiedoston nimi: '''<tt>[[:$1]]</tt>'''
* Olemassa olevan tiedoston nimi: '''<tt>[[:$2]]</tt>'''
Valitse toinen tiedostonimi.",
'fileexists-thumbnail-yes'    => "Tiedosto näyttäisi olevan pienennetty kuva ''(pienoiskuva)''. [[$1|thumb]]
Tarkista tiedosto '''<tt>[[:$1]]</tt>'''.
Jos yllä oleva tiedosto on alkuperäisversio samasta kuvasta, ei sille tarvitse tallentaa pienoiskuvaa.",
'file-thumbnail-no'           => "Tiedostonimi alkaa merkkijonolla '''<tt>$1</tt>'''. Tiedosto näyttäisi olevan pienennetty kuva ''(pienoiskuva)''.
Jos sinulla on tämän kuvan alkuperäinen versio, tallenna se. Muussa tapauksessa nimeä tiedosto uudelleen.",
'fileexists-forbidden'        => 'Samanniminen tiedosto on jo olemassa, eikä sitä voi korvata. Tallenna tiedosto jollakin toisella nimellä. Nykyinen tiedosto: [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Samanniminen tiedosto on jo olemassa jaetussa mediavarastossa. Tallenna tiedosto jollakin toisella nimellä. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Tämä tiedosto on kaksoiskappale {{PLURAL:$1|seuraavasta tiedostosta|seuraavista tiedostoista}}:',
'file-deleted-duplicate'      => 'Tiedosto, joka on identtinen tämän tiedoston kanssa ([[:$1]]) on aiemmin poistettu. Katso kyseisen tiedoston poistoloki ennen kuin jatkat uudelleentallentamista.',
'uploadwarning'               => 'Tallennusvaroitus',
'uploadwarning-text'          => 'Muuta alla olevaa tiedostokuvausta ja yritä uudelleen.',
'savefile'                    => 'Tallenna',
'uploadedimage'               => 'tallensi tiedoston [[$1]]',
'overwroteimage'              => 'tallensi uuden version [[$1]]',
'uploaddisabled'              => 'Tiedostojen tallennus ei ole käytössä.',
'copyuploaddisabled'          => 'Tallennus URL:n kautta on poistettu käytöstä.',
'uploadfromurl-queued'        => 'Tallennuksesi on siirretty jonoon.',
'uploaddisabledtext'          => 'Tiedostojen tallennus on poistettu käytöstä.',
'php-uploaddisabledtext'      => 'PHP:n tiedostojen lähetys ei ole käytössä. Tarkista asetukset kohdasta file_uploads.',
'uploadscripted'              => 'Tämä tiedosto sisältää HTML-koodia tai skriptejä, jotka selain saattaa virheellisesti suorittaa.',
'uploadvirus'                 => 'Tiedosto sisältää viruksen. Tarkemmat tiedot: $1',
'upload-source'               => 'Lähdetiedosto',
'sourcefilename'              => 'Lähdenimi',
'sourceurl'                   => 'URL-lähde',
'destfilename'                => 'Kohdenimi',
'upload-maxfilesize'          => 'Suurin sallittu tiedostokoko: $1',
'upload-description'          => 'Tiedoston kuvaus',
'upload-options'              => 'Tallennusasetukset',
'watchthisupload'             => 'Tarkkaile tätä tiedostoa',
'filewasdeleted'              => 'Tämän niminen tiedosto on lisätty ja poistettu aikaisemmin. Tarkista $1 ennen jatkamista.',
'upload-wasdeleted'           => "'''Varoitus: Olet tallentamassa tiedostoa, joka on jo aikaisemmin poistettu.'''

Harkitse, haluatko jatkaa tämän tiedoston tallentamista. Tiedoston poistoloki näkyy tässä:",
'filename-bad-prefix'         => "Tallentamasi tiedoston nimi alkaa merkkijonolla '''$1''', joka on yleensä digitaalikameroiden automaattisesti antama nimi, joka ei kuvaa tiedoston sisältöä. Anna tiedostolle kuvaavampi nimi.",
'upload-success-subj'         => 'Tallennus onnistui',
'upload-success-msg'          => 'Tallennuksesi [$2] onnistui. Tiedosto on saatavilla täällä: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Tallennusongelma',
'upload-failure-msg'          => 'Tiedoston tallentaminen osoitteesta [$2] ei onnistunut:

$1',
'upload-warning-subj'         => 'Tallennusvaroitus',
'upload-warning-msg'          => 'Tiedoston tallennuksessasi oli ongelmia [$2]. Voit palata [[Special:Upload/stash/$1|tallennussivulle]] ja korjata ongelman.',

'upload-proto-error'        => 'Virheellinen protokolla',
'upload-proto-error-text'   => 'Etälähetys on mahdollista vain osoitteista, jotka alkavat merkkijonolla <code>http://</code> tai <code>ftp://</code>.',
'upload-file-error'         => 'Vakava virhe',
'upload-file-error-text'    => 'Väliaikaistiedoston luominen epäonnistui. Ota yhteyttä sivuston [[Special:ListUsers/sysop|ylläpitäjään]].',
'upload-misc-error'         => 'Virhe',
'upload-misc-error-text'    => 'Tiedoston etälähetys ei onnistunut. Varmista, että antamasi osoite on oikein ja toimiva. Jos virhe ei katoa, ota yhteyttä sivuston ylläpitäjään.',
'upload-too-many-redirects' => 'URL sisälsi liian monta ohjausta',
'upload-unknown-size'       => 'Tuntematon koko',
'upload-http-error'         => 'HTTP-virhe: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Pääsy estetty',
'img-auth-nopathinfo'   => 'PATH_INFO puuttuu.
Palvelintasi ei ole asetettu välittämään tätä tietoa.
Se saattaa olla CGI-pohjainen eikä voi tukea img_authia.
Katso http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Pyydetty polku ei ole asetuksissa tiedostojen tallennushakemistona.',
'img-auth-badtitle'     => '”$1” ei kelpaa oikean otsikon muodostamiseen.',
'img-auth-nologinnWL'   => 'Et ole kirjautunut sisään ja tiedosto ”$1” ei ole sallittujen tiedostojen luettelossa.',
'img-auth-nofile'       => 'Tiedostoa ”$1” ei ole.',
'img-auth-isdir'        => 'Yrität päästä hakemistoon ”$1”.
Vain tiedostoihin pääsy on sallittu.',
'img-auth-streaming'    => 'Toistetaan tiedostoa ”$1”.',
'img-auth-public'       => 'Img_auth.php:n tarkoitus on näyttää tiedostoja yksityisessä wikissä.
Tämä wiki on asennettu julkiseksi wikiksi.
Parhaan turvallisuuden vuoksi img_auth.php on poissa käytöstä.',
'img-auth-noread'       => 'Käyttäjillä ei ole oikeutta lukea tiedostoa ”$1”.',

# HTTP errors
'http-invalid-url'      => 'Kelpaamaton URL: $1',
'http-invalid-scheme'   => 'Verkko-osoitteita kaavalla "$1" ei tueta',
'http-request-error'    => 'HTTP-pyyntö epäonnistui tuntemattoman virheen takia.',
'http-read-error'       => 'HTTP-lukuvirhe.',
'http-timed-out'        => 'HTTP-pyyntö aikakatkaistiin.',
'http-curl-error'       => 'Virhe noudettaessa verkko-osoitetta: $1',
'http-host-unreachable' => 'Ei voitu tavoittaa verkko-osoitetta',
'http-bad-status'       => 'HTTP-pyynnön aikana oli ongelma: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Toimimaton osoite',
'upload-curl-error6-text'  => 'Antamaasi osoitteeseen ei saatu yhteyttä. Varmista, että osoite on oikein ja että sivusto on saavutettavissa.',
'upload-curl-error28'      => 'Etälähetyksen aikakatkaisu',
'upload-curl-error28-text' => 'Antamastasi osoitteesta ei saatu vastausta määräajassa. Varmista, että sivusto on saavutettavissa ja yritä uudelleen.',

'license'            => 'Lisenssi',
'license-header'     => 'Lisenssi',
'nolicense'          => 'Ei lisenssiä',
'license-nopreview'  => '(esikatselua ei saatavilla)',
'upload_source_url'  => ' (julkinen verkko-osoite)',
'upload_source_file' => ' (tiedosto tietokoneella)',

# Special:ListFiles
'listfiles-summary'     => 'Tämä toimintosivu näyttää kaikki tallennetut tiedostot. Viimeisin tallennettu tiedosto on listalla ensimmäisenä. Ryhmittelyperustetta voi vaihtaa napsauttamalla sarakenimeä.',
'listfiles_search_for'  => 'Nimihaku',
'imgfile'               => 'tiedosto',
'listfiles'             => 'Tiedostoluettelo',
'listfiles_thumb'       => 'Pienoiskuva',
'listfiles_date'        => 'Päiväys',
'listfiles_name'        => 'Nimi',
'listfiles_user'        => 'Tallentaja',
'listfiles_size'        => 'Koko',
'listfiles_description' => 'Kuvaus',
'listfiles_count'       => 'Versioita',

# File description page
'file-anchor-link'          => 'Tiedosto',
'filehist'                  => 'Tiedoston historia',
'filehist-help'             => 'Päiväystä napsauttamalla näet millainen tiedosto oli sillä ajan hetkellä.',
'filehist-deleteall'        => 'poista kaikki',
'filehist-deleteone'        => 'poista tämä',
'filehist-revert'           => 'palauta',
'filehist-current'          => 'nykyinen',
'filehist-datetime'         => 'Päiväys',
'filehist-thumb'            => 'Pienoiskuva',
'filehist-thumbtext'        => 'Pienoiskuva $1 tallennetusta versiosta',
'filehist-nothumb'          => 'Ei pienoiskuvaa',
'filehist-user'             => 'Käyttäjä',
'filehist-dimensions'       => 'Koko',
'filehist-filesize'         => 'Tiedostokoko',
'filehist-comment'          => 'Kommentti',
'filehist-missing'          => 'Tiedosto puuttuu',
'imagelinks'                => 'Tiedostolinkit',
'linkstoimage'              => '{{PLURAL:$1|Seuraavalta sivulta|$1 sivulla}} on linkki tähän tiedostoon:',
'linkstoimage-more'         => 'Enemmän kuin $1 {{PLURAL:$1|sivu|sivua}} linkittää tähän tiedostoon.
Seuraava lista näyttää {{PLURAL:$1|ensimmäisen linkittävän sivun|$1 ensimmäistä linkittävää sivua}} tähän tiedostoon.
[[Special:WhatLinksHere/$2|Koko lista]] on saatavilla.',
'nolinkstoimage'            => 'Tähän tiedostoon ei ole linkkejä miltään sivulta.',
'morelinkstoimage'          => 'Näytä [[Special:WhatLinksHere/$1|lisää linkkejä]] tähän tiedostoon.',
'redirectstofile'           => '{{PLURAL:$1|Seuraava tiedosto ohjaa|Seuraavat $1 tiedostoa ohjaavat}} tähän tiedostoon:',
'duplicatesoffile'          => '{{PLURAL:$1|Seuraava tiedosto on tämän tiedoston kaksoiskappale|Seuraavat $1 tiedostoa ovat tämän tiedoston kaksoiskappaleita}} ([[Special:FileDuplicateSearch/$2|lisätietoja]]):',
'sharedupload'              => 'Tämä tiedosto on jaettu kohteesta $1 ja muut projektit saattavat käyttää sitä.',
'sharedupload-desc-there'   => 'Tämä tiedosto on jaettu kohteesta $1 ja muut projektit saattavat käyttää sitä.
Katso [$2 tiedoston kuvaussivulta] lisätietoja.',
'sharedupload-desc-here'    => 'Tämä tiedosto on jaettu kohteesta $1 ja muut projektit saattavat käyttää sitä.
Tiedot [$2 tiedoston kuvaussivulta] näkyvät alla.',
'filepage-nofile'           => 'Tämän nimistä tiedostoa ei ole olemassa.',
'filepage-nofile-link'      => 'Tämän nimistä tiedostoa ei ole olemassa, mutta voit [$1 tallentaa sen].',
'uploadnewversion-linktext' => 'Tallenna uusi versio tästä tiedostosta',
'shared-repo-from'          => 'kohteesta $1',
'shared-repo'               => 'jaettu mediavarasto',

# File reversion
'filerevert'                => 'Tiedoston $1 palautus',
'filerevert-legend'         => 'Tiedoston palautus',
'filerevert-intro'          => '<span class="plainlinks">Olet palauttamassa tiedostoa \'\'\'[[Media:$1|$1]]\'\'\' [$4 versioon, joka luotiin $2 kello $3].</span>',
'filerevert-comment'        => 'Syy',
'filerevert-defaultcomment' => 'Palautettiin versioon, joka luotiin $1 kello $2',
'filerevert-submit'         => 'Palauta',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' on palautettu [$4 versioon, joka luotiin $2 kello $3].</span>',
'filerevert-badversion'     => 'Tiedostosta ei ole luotu versiota kyseisellä ajan hetkellä.',

# File deletion
'filedelete'                  => 'Tiedoston $1 poisto',
'filedelete-legend'           => 'Tiedoston poisto',
'filedelete-intro'            => "Olet poistamassa tiedostoa '''[[Media:$1|$1]]''' ja kaiken sen historian.",
'filedelete-intro-old'        => '<span class="plainlinks">Olet poistamassa tiedoston \'\'\'[[Media:$1|$1]]\'\'\' [$4 $3 kello $2 luotua versiota].</span>',
'filedelete-comment'          => 'Syy',
'filedelete-submit'           => 'Poista',
'filedelete-success'          => "Tiedosto '''$1''' on poistettu.",
'filedelete-success-old'      => "Tiedoston '''[[Media:$1|$1]]''' $3 kello $2 luotu versio on poistettu.",
'filedelete-nofile'           => "Tiedostoa '''$1''' ei ole.",
'filedelete-nofile-old'       => "Tiedostosta '''$1''' ei ole olemassa pyydettyä versiota.",
'filedelete-otherreason'      => 'Muu syy tai tarkennus',
'filedelete-reason-otherlist' => 'Muu syy',
'filedelete-reason-dropdown'  => '*Yleiset poistosyyt
** Kaksoiskappale
** Tekijänoikeusrikkomus',
'filedelete-edit-reasonlist'  => 'Muokkaa poistosyitä',
'filedelete-maintenance'      => 'Tiedostojen poisto ja palautus on väliaikaisesti poistettu käytöstä huoltotoimien vuoksi.',

# MIME search
'mimesearch'         => 'MIME-haku',
'mimesearch-summary' => 'Tällä sivulla voit etsiä tiedostoja niiden MIME-tyypin perusteella. Syöte: sisältötyyppi/alatyyppi, esimerkiksi <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-tyyppi',
'download'           => 'lataa',

# Unwatched pages
'unwatchedpages' => 'Tarkkailemattomat sivut',

# List redirects
'listredirects' => 'Ohjaukset',

# Unused templates
'unusedtemplates'     => 'Käyttämättömät mallineet',
'unusedtemplatestext' => 'Tässä on lista nimiavaruuden {{ns:template}} kaikista sivuista, joita ei ole liitetty toiselle sivulle. Muista tarkistaa onko malline siitä huolimatta käytössä.',
'unusedtemplateswlh'  => 'muut linkit',

# Random page
'randompage'         => 'Satunnainen sivu',
'randompage-nopages' => '{{PLURAL:$2|Nimiavaruudessa|Nimiavaruuksissa}} $1 ei ole sivuja.',

# Random redirect
'randomredirect'         => 'Satunnainen ohjaus',
'randomredirect-nopages' => 'Nimiavaruudessa ”$1” ei ole ohjaussivuja.',

# Statistics
'statistics'                   => 'Tilastot',
'statistics-header-pages'      => 'Sivutilastot',
'statistics-header-edits'      => 'Muokkaustilastot',
'statistics-header-views'      => 'Katselutilastot',
'statistics-header-users'      => 'Käyttäjätilastot',
'statistics-header-hooks'      => 'Muut tilastot',
'statistics-articles'          => 'Sisältösivuja',
'statistics-pages'             => 'Sivuja',
'statistics-pages-desc'        => 'Kaikki sivut, sisältäen keskustelusivut, ohjaukset ja muut.',
'statistics-files'             => 'Tallennettuja tiedostoja',
'statistics-edits'             => 'Muokkauksia {{GRAMMAR:genitive|{{SITENAME}}}} perustamisen jälkeen',
'statistics-edits-average'     => 'Keskimäärin yhtä sivua muokattu',
'statistics-views-total'       => 'Sivuja katsottu yhteensä',
'statistics-views-total-desc'  => 'Näyttökertoihin eivät sisälly toimintosivut eikä sivut, joita ei ole olemassa',
'statistics-views-peredit'     => 'Sivuja katsottu muokkausta kohden',
'statistics-users'             => 'Rekisteröityneitä [[Special:ListUsers|käyttäjiä]]',
'statistics-users-active'      => 'Aktiivisia käyttäjiä',
'statistics-users-active-desc' => 'Käyttäjät, jotka ovat suorittaneet jonkin toiminnon {{PLURAL:$1|edellisen päivän|edellisten $1 päivän}} aikana.',
'statistics-mostpopular'       => 'Katsotuimmat sivut',

'disambiguations'      => 'Linkit täsmennyssivuihin',
'disambiguationspage'  => 'Template:Täsmennyssivu',
'disambiguations-text' => "Seuraavat artikkelit linkittävät ''täsmennyssivuun''. Täsmennyssivun sijaan niiden pitäisi linkittää asianomaiseen aiheeseen.<br />Sivua kohdellaan täsmennyssivuna jos se käyttää mallinetta, johon on linkki sivulta [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Kaksinkertaiset ohjaukset',
'doubleredirectstext'        => 'Tässä listassa on ohjaussivut, jotka ohjaavat toiseen ohjaussivuun.
Jokaisella rivillä on linkit ensimmäiseen ja toiseen ohjaukseen sekä toisen ohjauksen kohteen ensimmäiseen riviin, eli yleensä ”oikeaan” kohteeseen, johon ensimmäisen ohjauksen pitäisi osoittaa.
<del>Yliviivatut</del> kohteet on korjattu.',
'double-redirect-fixed-move' => '[[$1]] on siirretty, ja se ohjaa nyt sivulle [[$2]]',
'double-redirect-fixer'      => 'Ohjausten korjaaja',

'brokenredirects'        => 'Virheelliset ohjaukset',
'brokenredirectstext'    => 'Seuraavat ohjaukset osoittavat sivuihin, joita ei ole olemassa.',
'brokenredirects-edit'   => 'muokkaa',
'brokenredirects-delete' => 'poista',

'withoutinterwiki'         => 'Sivut, joilla ei ole kielilinkkejä',
'withoutinterwiki-summary' => 'Seuraavat sivut eivät viittaa erikielisiin versioihin:',
'withoutinterwiki-legend'  => 'Etuliite',
'withoutinterwiki-submit'  => 'Näytä',

'fewestrevisions' => 'Sivut, joilla on vähiten muutoksia',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|tavu|tavua}}',
'ncategories'             => '$1 {{PLURAL:$1|luokka|luokkaa}}',
'nlinks'                  => '$1 {{PLURAL:$1|linkki|linkkiä}}',
'nmembers'                => '$1 {{PLURAL:$1|jäsen|jäsentä}}',
'nrevisions'              => '$1 {{PLURAL:$1|muutos|muutosta}}',
'nviews'                  => '$1 {{PLURAL:$1|lataus|latausta}}',
'nimagelinks'             => 'Käytössä $1 {{PLURAL:$1|sivulla}}',
'ntransclusions'          => 'käytössä $1 {{PLURAL:$1|sivulla}}',
'specialpage-empty'       => 'Tämä sivu on tyhjä.',
'lonelypages'             => 'Yksinäiset sivut',
'lonelypagestext'         => 'Seuraaviin sivuihin ei ole linkkejä tai sisällytyksiä muualta wikistä.',
'uncategorizedpages'      => 'Luokittelemattomat sivut',
'uncategorizedcategories' => 'Luokittelemattomat luokat',
'uncategorizedimages'     => 'Luokittelemattomat tiedostot',
'uncategorizedtemplates'  => 'Luokittelemattomat mallineet',
'unusedcategories'        => 'Käyttämättömät luokat',
'unusedimages'            => 'Käyttämättömät tiedostot',
'popularpages'            => 'Suositut sivut',
'wantedcategories'        => 'Halutut luokat',
'wantedpages'             => 'Halutut sivut',
'wantedpages-badtitle'    => 'Virheellinen otsikko tuloksissa: $1',
'wantedfiles'             => 'Halutut tiedostot',
'wantedtemplates'         => 'Halutut mallineet',
'mostlinked'              => 'Viitatuimmat sivut',
'mostlinkedcategories'    => 'Viitatuimmat luokat',
'mostlinkedtemplates'     => 'Viitatuimmat mallineet',
'mostcategories'          => 'Luokitelluimmat sivut',
'mostimages'              => 'Viitatuimmat tiedostot',
'mostrevisions'           => 'Muokatuimmat sivut',
'prefixindex'             => 'Kaikki sivut katkaisuhaulla',
'shortpages'              => 'Lyhyet sivut',
'longpages'               => 'Pitkät sivut',
'deadendpages'            => 'Sivut, joilla ei ole linkkejä',
'deadendpagestext'        => 'Seuraavat sivut eivät linkitä muihin sivuihin wikissä.',
'protectedpages'          => 'Suojatut sivut',
'protectedpages-indef'    => 'Vain ikuisesti suojatut',
'protectedpages-cascade'  => 'Vain laajennetusti suojatut',
'protectedpagestext'      => 'Seuraavat sivut ovat suojattuja siirtämiseltä tai muutoksilta',
'protectedpagesempty'     => 'Ei suojattu sivuja.',
'protectedtitles'         => 'Suojatut sivunimet',
'protectedtitlestext'     => 'Seuraavien sivujen luonti on estetty.',
'protectedtitlesempty'    => 'Ei suojattuja sivunimiä näillä hakuehdoilla.',
'listusers'               => 'Käyttäjälista',
'listusers-editsonly'     => 'Näytä vain käyttäjät, joilla on muokkauksia',
'listusers-creationsort'  => 'Lajittele tunnuksen luontipäivämäärän mukaan',
'usereditcount'           => '$1 {{PLURAL:$1|muokkaus|muokkausta}}',
'usercreated'             => 'Luotu $1 kello $2',
'newpages'                => 'Uudet sivut',
'newpages-username'       => 'Käyttäjätunnus',
'ancientpages'            => 'Kauan muokkaamattomat sivut',
'move'                    => 'Siirrä',
'movethispage'            => 'Siirrä tämä sivu',
'unusedimagestext'        => 'Seuraavat tiedostot ovat olemassa, mutta niitä ei käytetä millään sivulla.
Huomaa, että muut verkkosivut saattavat viitata tiedostoon suoran URL:n avulla, jolloin tiedosto saattaa olla tässä listassa, vaikka sitä käytetäänkin.',
'unusedcategoriestext'    => 'Nämä luokat ovat olemassa, mutta niitä ei käytetä.',
'notargettitle'           => 'Ei kohdetta',
'notargettext'            => 'Et ole määritellyt kohdesivua tai -käyttäjää johon toiminto kohdistuu.',
'nopagetitle'             => 'Kohdesivua ei ole olemassa.',
'nopagetext'              => 'Määritettyä kohdesivua ei ole olemassa.',
'pager-newer-n'           => '← {{PLURAL:$1|1 uudempi|$1 uudempaa}}',
'pager-older-n'           => '{{PLURAL:$1|1 vanhempi|$1 vanhempaa}} →',
'suppress'                => 'Häivytys',

# Book sources
'booksources'               => 'Kirjalähteet',
'booksources-search-legend' => 'Etsi kirjalähteitä',
'booksources-isbn'          => 'ISBN',
'booksources-go'            => 'Siirry',
'booksources-text'          => 'Alla linkkejä ulkopuolisiin sivustoihin, joilla myydään uusia ja käytettyjä kirjoja. Sivuilla voi myös olla lisätietoa kirjoista.',
'booksources-invalid-isbn'  => 'Annettu ISBN-numero ei ole kelvollinen. Tarkista alkuperäisestä lähteestä kirjoitusvirheiden varalta.',

# Special:Log
'specialloguserlabel'  => 'Käyttäjä',
'speciallogtitlelabel' => 'Kohde',
'log'                  => 'Lokit',
'all-logs-page'        => 'Kaikki julkiset lokit',
'alllogstext'          => 'Tämä on yhdistetty lokien näyttö.
Voit rajoittaa listaa valitsemalla lokityypin, käyttäjän tai sivun johon muutos on kohdistunut. Jälkimmäiset ovat kirjainkokoherkkiä.',
'logempty'             => 'Ei tapahtumia lokissa.',
'log-title-wildcard'   => 'Kohde alkaa merkkijonolla',

# Special:AllPages
'allpages'          => 'Kaikki sivut',
'alphaindexline'    => '$1…$2',
'nextpage'          => 'Seuraava sivu ($1)',
'prevpage'          => 'Edellinen sivu ($1)',
'allpagesfrom'      => 'Alkaen sivusta',
'allpagesto'        => 'Loppuen sivuun',
'allarticles'       => 'Kaikki sivut',
'allinnamespace'    => 'Kaikki sivut nimiavaruudessa $1',
'allnotinnamespace' => 'Kaikki sivut, jotka eivät ole nimiavaruudessa $1',
'allpagesprev'      => 'Edellinen',
'allpagesnext'      => 'Seuraava',
'allpagessubmit'    => 'Hae',
'allpagesprefix'    => 'Katkaisuhaku',
'allpagesbadtitle'  => 'Annettu otsikko oli kelvoton tai siinä oli wikien välinen etuliite.',
'allpages-bad-ns'   => '{{GRAMMAR:inessive|{{SITENAME}}}} ei ole nimiavaruutta ”$1”.',

# Special:Categories
'categories'                    => 'Luokat',
'categoriespagetext'            => '{{PLURAL:$1|Seuraava luokka sisältää|Seuraavat luokat sisältävät}} sivuja tai mediatiedostoja.
[[Special:UnusedCategories|Käyttämättömiä luokkia]] ei näytetä.
Katso myös [[Special:WantedCategories|halutut luokat]].',
'categoriesfrom'                => 'Näytä alkaen luokasta',
'special-categories-sort-count' => 'järjestä koon mukaan',
'special-categories-sort-abc'   => 'järjestä nimen mukaan',

# Special:DeletedContributions
'deletedcontributions'             => 'Poistetut muokkaukset',
'deletedcontributions-title'       => 'Poistetut muokkaukset',
'sp-deletedcontributions-contribs' => 'muokkaukset',

# Special:LinkSearch
'linksearch'       => 'Ulkoiset linkit',
'linksearch-pat'   => 'Osoite',
'linksearch-ns'    => 'Nimiavaruus',
'linksearch-ok'    => 'Etsi',
'linksearch-text'  => 'Asteriskia (*) voi käyttää jokerimerkkinä, esimerkiksi ”*.wikipedia.org”.

Tuetut protokollat: <tt>$1</tt>.',
'linksearch-line'  => '$1 on linkitetty sivulta $2',
'linksearch-error' => 'Jokerimerkkiä voi käyttää ainoastaan osoitteen alussa.',

# Special:ListUsers
'listusersfrom'      => 'Katkaisuhaku',
'listusers-submit'   => 'Hae',
'listusers-noresult' => 'Käyttäjiä ei löytynyt.',
'listusers-blocked'  => '(estetty)',

# Special:ActiveUsers
'activeusers'            => 'Aktiivisten käyttäjien lista',
'activeusers-intro'      => 'Tämä on luettelo käyttäjistä, jotka ovat tehneet jotain viimeisen $1 {{PLURAL:$1|päivän}} sisällä.',
'activeusers-count'      => '$1 {{PLURAL:$1|muokkaus|muokkausta}} viimeisen {{PLURAL:$3|päivän|$3 päivän}} aikana',
'activeusers-from'       => 'Näytä käyttäjät alkaen',
'activeusers-hidebots'   => 'Piilota botit',
'activeusers-hidesysops' => 'Piilota ylläpitäjät',
'activeusers-noresult'   => 'Käyttäjiä ei löytynyt.',

# Special:Log/newusers
'newuserlogpage'              => 'Uudet käyttäjät',
'newuserlogpagetext'          => 'Tämä on loki luoduista käyttäjätunnuksista.',
'newuserlog-byemail'          => 'salasana lähetetty sähköpostitse',
'newuserlog-create-entry'     => 'Uusi käyttäjä',
'newuserlog-create2-entry'    => 'loi uuden käyttäjätunnuksen $1',
'newuserlog-autocreate-entry' => 'Automaattisesti luotu käyttäjätunnus',

# Special:ListGroupRights
'listgrouprights'                      => 'Käyttäjäryhmien oikeudet',
'listgrouprights-summary'              => 'Tämä lista sisältää tämän wikin käyttäjäryhmät sekä ryhmiin liitetyt käyttöoikeudet.
Lisätietoa yksittäisistä käyttäjäoikeuksista saattaa löytyä [[{{MediaWiki:Listgrouprights-helppage}}|erilliseltä ohjesivulta]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Myönnetyt oikeudet</span>
* <span class="listgrouprights-revoked">Kumotut oikeudet</span>',
'listgrouprights-group'                => 'Ryhmä',
'listgrouprights-rights'               => 'Oikeudet',
'listgrouprights-helppage'             => 'Help:Käyttöoikeudet',
'listgrouprights-members'              => '(jäsenlista)',
'listgrouprights-addgroup'             => 'Voi lisätä jäseniä {{PLURAL:$2|ryhmään|ryhmiin}} $1',
'listgrouprights-removegroup'          => 'Voi poistaa jäseniä {{PLURAL:$2|ryhmästä|ryhmistä}} $1',
'listgrouprights-addgroup-all'         => 'Voi lisätä jäseniä kaikkiin ryhmiin',
'listgrouprights-removegroup-all'      => 'Voi poistaa jäseniä kaikista ryhmistä',
'listgrouprights-addgroup-self'        => 'Voi lisätä itsensä {{PLURAL:$2|ryhmään|ryhmiin}} $1',
'listgrouprights-removegroup-self'     => 'Voi poistaa itsensä {{PLURAL:$2|ryhmästä|ryhmistä}} $1',
'listgrouprights-addgroup-self-all'    => 'Voi lisätä itsensä kaikkiin ryhmiin',
'listgrouprights-removegroup-self-all' => 'Voi poistaa itsensä kaikista ryhmistä',

# E-mail user
'mailnologin'          => 'Lähettäjän osoite puuttuu',
'mailnologintext'      => 'Sinun pitää olla [[Special:UserLogin|kirjautuneena sisään]] ja [[Special:Preferences|asetuksissasi]] pitää olla toimiva ja <strong>varmennettu</strong> sähköpostiosoite, jotta voit lähettää sähköpostia muille käyttäjille.',
'emailuser'            => 'Lähetä sähköpostia tälle käyttäjälle',
'emailpage'            => 'Lähetä sähköpostia käyttäjälle',
'emailpagetext'        => 'Jos tämä käyttäjä on antanut asetuksissaan kelvollisen sähköpostiosoitteen, alla olevalla lomakkeella voit lähettää hänelle viestin. [[Special:Preferences|Omissa asetuksissasi]] annettu sähköpostiosoite näkyy sähköpostin lähettäjän osoitteena, jotta vastaanottaja voi suoraan vastata viestiin.',
'usermailererror'      => 'Postitus palautti virheen:',
'defemailsubject'      => 'Sähköpostia sivustolta {{SITENAME}}',
'usermaildisabled'     => 'Käyttäjien sähköposti poistettu käytöstä',
'usermaildisabledtext' => 'Et voi lähettää sähköpostia muille käyttäjille tässä wikissä',
'noemailtitle'         => 'Ei sähköpostiosoitetta',
'noemailtext'          => 'Tämä käyttäjä ei ole määritellyt kelvollista sähköpostiosoitetta.',
'nowikiemailtitle'     => 'Sähköpostin lähettäminen ei sallittu',
'nowikiemailtext'      => 'Tämä käyttäjä ei halua sähköpostia muilta käyttäjiltä.',
'email-legend'         => 'Sähköpostin lähetys {{GRAMMAR:genitive|{{SITENAME}}}} käyttäjälle',
'emailfrom'            => 'Lähettäjä',
'emailto'              => 'Vastaanottaja',
'emailsubject'         => 'Aihe',
'emailmessage'         => 'Viesti',
'emailsend'            => 'Lähetä',
'emailccme'            => 'Lähetä kopio viestistä minulle.',
'emailccsubject'       => 'Kopio lähettämästäsi viestistä osoitteeseen $1: $2',
'emailsent'            => 'Sähköposti lähetetty',
'emailsenttext'        => 'Sähköpostiviestisi on lähetetty.',
'emailuserfooter'      => 'Tämän sähköpostin lähetti $1 käyttäjälle $2 käyttämällä ”Lähetä sähköpostia” -toimintoa {{GRAMMAR:inessive|{{SITENAME}}}}.',

# User Messenger
'usermessage-summary' => 'Jätetään järjestelmäviesti.',
'usermessage-editor'  => 'Järjestelmäviestittäjä',

# Watchlist
'watchlist'            => 'Tarkkailulista',
'mywatchlist'          => 'Tarkkailulista',
'watchlistfor2'        => 'Käyttäjälle $1 $2',
'nowatchlist'          => 'Tarkkailulistallasi ei ole sivuja.',
'watchlistanontext'    => 'Sinun täytyy $1, jos haluat käyttää tarkkailulistaa.',
'watchnologin'         => 'Et ole kirjautunut sisään',
'watchnologintext'     => 'Sinun pitää [[Special:UserLogin|kirjautua sisään]], jotta voisit käyttää tarkkailulistaasi.',
'addedwatch'           => 'Lisätty tarkkailulistalle',
'addedwatchtext'       => "Sivu '''<nowiki>$1</nowiki>''' on lisätty [[Special:Watchlist|tarkkailulistallesi]]. Tulevaisuudessa sivuun ja sen keskustelusivuun tehtävät muutokset listataan täällä. Sivu on '''lihavoitu''' [[Special:RecentChanges|tuoreiden muutosten listassa]], jotta huomaisit sen helpommin. Jos haluat myöhemmin poistaa sivun tarkkailulistaltasi, napsauta linkkiä ''lopeta tarkkailu'' sivun reunassa.",
'removedwatch'         => 'Poistettu tarkkailulistalta',
'removedwatchtext'     => "Sivu '''[[:$1]]''' on poistettu [[Special:Watchlist|tarkkailulistaltasi]].",
'watch'                => 'Tarkkaile',
'watchthispage'        => 'Tarkkaile tätä sivua',
'unwatch'              => 'Lopeta tarkkailu',
'unwatchthispage'      => 'Lopeta tarkkailu',
'notanarticle'         => 'Ei ole sivu',
'notvisiblerev'        => 'Versio on poistettu',
'watchnochange'        => 'Valittuna ajanjaksona yhtäkään tarkkailemistasi sivuista ei muokattu.',
'watchlist-details'    => 'Tarkkailulistalla on {{PLURAL:$1|$1 sivu|$1 sivua}} keskustelusivuja mukaan laskematta.',
'wlheader-enotif'      => '* Sähköposti-ilmoitukset ovat käytössä.',
'wlheader-showupdated' => "* Sivut, joita on muokattu viimeisen käyntisi jälkeen on '''lihavoitu'''.",
'watchmethod-recent'   => 'tarkistetaan tuoreimpia muutoksia tarkkailluille sivuille',
'watchmethod-list'     => 'tarkistetaan tarkkailtujen sivujen tuoreimmat muutokset',
'watchlistcontains'    => 'Tarkkailulistallasi on {{PLURAL:$1|yksi sivu|$1 sivua}}.',
'iteminvalidname'      => 'Sivun $1 kanssa oli ongelmia! Sivun nimessä on vikaa.',
'wlnote'               => "Alla on '''$1''' {{PLURAL:$1|muutos|muutosta}} viimeisen {{PLURAL:$2||'''$2'''}} tunnin ajalta.",
'wlshowlast'           => 'Näytä viimeiset $1 tuntia tai $2 päivää$3',
'watchlist-options'    => 'Tarkkailulistan asetukset',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Lisätään tarkkailulistalle...',
'unwatching' => 'Poistetaan tarkkailulistalta...',

'enotif_mailer'                => '{{GRAMMAR:genitive|{{SITENAME}}}} sivu on muuttunut -ilmoitus',
'enotif_reset'                 => 'Merkitse kaikki sivut katsotuiksi',
'enotif_newpagetext'           => 'Tämä on uusi sivu.',
'enotif_impersonal_salutation' => '{{SITENAME}}-käyttäjä',
'changed'                      => 'muuttanut sivua',
'created'                      => 'luonut sivun',
'enotif_subject'               => '$PAGEEDITOR on $CHANGEDORCREATED $PAGETITLE',
'enotif_lastvisited'           => 'Osoitteessa $1 on kaikki muutokset viimeisen käyntisi jälkeen.',
'enotif_lastdiff'              => 'Muutos on osoitteessa $1.',
'enotif_anon_editor'           => 'kirjautumaton käyttäjä $1',
'enotif_body'                  => 'Käyttäjä $WATCHINGUSERNAME,

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

Voit poistaa sivun tarkkailulistalta osoitteessa:
$UNWATCHURL

Palaute ja lisäapu osoitteessa:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Poista sivu',
'confirm'                => 'Toteuta',
'excontent'              => 'sisälsi: ”$1”',
'excontentauthor'        => 'sisälsi: ”$1” (ainoa muokkaaja oli $2)',
'exbeforeblank'          => 'ennen tyhjentämistä sisälsi: ”$1”',
'exblank'                => 'oli tyhjä',
'delete-confirm'         => 'Sivun ”$1” poistaminen',
'delete-legend'          => 'Sivun poisto',
'historywarning'         => "'''Varoitus:''' Sivua, jota olet poistamassa on muokattu noin $1 {{PLURAL:$1|kerta|kertaa}}:",
'confirmdeletetext'      => 'Olet poistamassa sivun tai tiedoston ja kaiken sen historian. Ymmärrä teon seuraukset ja tee poisto {{GRAMMAR:genitive|{{SITENAME}}}} [[{{MediaWiki:Policy-url}}|käytäntöjen]] mukaisesti.',
'actioncomplete'         => 'Toiminto suoritettu',
'actionfailed'           => 'Toiminto epäonnistui',
'deletedtext'            => '”<nowiki>$1</nowiki>” on poistettu.
Sivulla $2 on lista viimeaikaisista poistoista.',
'deletedarticle'         => 'poisti sivun [[$1]]',
'suppressedarticle'      => 'häivytti sivun [[$1]]',
'dellogpage'             => 'Poistoloki',
'dellogpagetext'         => 'Alla on loki viimeisimmistä poistoista.',
'deletionlog'            => 'poistoloki',
'reverted'               => 'Palautettu aikaisempaan versioon',
'deletecomment'          => 'Syy',
'deleteotherreason'      => 'Muu syy tai tarkennus',
'deletereasonotherlist'  => 'Muu syy',
'deletereason-dropdown'  => '*Yleiset poistosyyt
** Lisääjän poistopyyntö
** Tekijänoikeusrikkomus
** Roskaa',
'delete-edit-reasonlist' => 'Muokkaa poistosyitä',
'delete-toobig'          => 'Tällä sivulla on pitkä muutoshistoria – yli $1 {{PLURAL:$1|versio|versiota}}. Näin suurien muutoshistorioiden poistamista on rajoitettu suorituskykysyistä.',
'delete-warning-toobig'  => 'Tällä sivulla on pitkä muutoshistoria – yli $1 {{PLURAL:$1|versio|versiota}}. Näin suurien muutoshistorioiden poistaminen voi haitata sivuston suorituskykyä.',

# Rollback
'rollback'          => 'palauta aiempaan versioon',
'rollback_short'    => 'Palautus',
'rollbacklink'      => 'palauta',
'rollbackfailed'    => 'Palautus epäonnistui',
'cantrollback'      => 'Aiempaan versioon ei voi palauttaa, koska viimeisin kirjoittaja on sivun ainoa tekijä.',
'alreadyrolled'     => 'Käyttäjän [[User:$2|$2]] ([[User talk:$2|keskustelu]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) tekemiä muutoksia sivuun [[:$1]] ei voi kumota, koska joku muu on muuttanut sivua.

Viimeisimmän muokkauksen on tehnyt käyttäjä [[User:$3|$3]] ([[User talk:$3|keskustelu]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Muokkauksen yhteenveto oli: ''$1''.",
'revertpage'        => 'Käyttäjän [[Special:Contributions/$2|$2]] ([[User talk:$2|keskustelu]]) muokkaukset kumottiin ja sivu palautettiin viimeisimpään käyttäjän [[User:$1|$1]] tekemään versioon.',
'revertpage-nouser' => 'Käyttäjän (käyttäjänimi poistettu) muokkaukset kumottiin ja sivu palautettiin viimeisimpään käyttäjän [[User:$1|$1]] tekemään versioon.',
'rollback-success'  => 'Käyttäjän ”$1” tekemät muokkaukset kumottiin ja sivu palautettiin käyttäjän $2 versioon.',

# Edit tokens
'sessionfailure-title' => 'Istuntovirhe',
'sessionfailure'       => 'Istuntosi kanssa on ongelma. Muutosta ei toteutettu varotoimena istuntokaappauksien takia. Käytä selaimen paluutoimintoa ja päivitä sivu, jolta tulit, ja yritä uudelleen.',

# Protect
'protectlogpage'              => 'Suojausloki',
'protectlogtext'              => 'Alla on loki sivujen suojauksista ja suojauksien poistoista. Luettelo tällä hetkellä suojatuista sivuista löytyy [[Special:ProtectedPages|suojattujen sivujen luettelosta]].',
'protectedarticle'            => 'suojasi sivun [[$1]]',
'modifiedarticleprotection'   => 'muutti sivun [[$1]] suojaustasoa',
'unprotectedarticle'          => 'poisti suojauksen sivulta $1',
'movedarticleprotection'      => 'siirsi suojausasetukset sivulta [[$2]] sivulle [[$1]]',
'protect-title'               => 'Sivun $1 suojaus',
'prot_1movedto2'              => 'siirsi sivun ”$1” uudelle nimelle ”$2”',
'protect-legend'              => 'Suojaukset',
'protectcomment'              => 'Syy',
'protectexpiry'               => 'Vanhenee',
'protect_expiry_invalid'      => 'Vanhenemisaika ei kelpaa.',
'protect_expiry_old'          => 'Vanhenemisaika on menneisyydessä.',
'protect-unchain-permissions' => 'Avaa myöhemmät suojausvalinnat',
'protect-text'                => "Voit katsoa ja muuttaa sivun '''<nowiki>$1</nowiki>''' suojauksia.",
'protect-locked-blocked'      => "Et voi muuttaa sivun suojauksia, koska sinut on estetty. Alla on sivun ”'''$1'''” nykyiset suojaukset:",
'protect-locked-dblock'       => "Sivun suojauksia ei voi muuttaa, koska tietokanta on lukittu. Alla on sivun ”'''$1'''” nykyiset suojaukset:",
'protect-locked-access'       => "Sinulla ei ole tarvittavia oikeuksia sivujen suojauksen muuttamiseen. Alla on sivun ”'''$1'''” nykyiset suojaukset:",
'protect-cascadeon'           => 'Tämä sivu on suojauksen kohteena, koska se on sisällytetty alla {{PLURAL:$1|olevaan laajennetusti suojattuun sivuun|oleviin laajennetusti suojattuihin sivuihin}}. Voit muuttaa tämän sivun suojaustasoa, mutta se ei vaikuta laajennettuun suojaukseen.',
'protect-default'             => 'Salli kaikki käyttäjät',
'protect-fallback'            => 'Vaadi $1-oikeus',
'protect-level-autoconfirmed' => 'Estä uudet ja anonyymit käyttäjät',
'protect-level-sysop'         => 'Vain ylläpitäjät',
'protect-summary-cascade'     => 'laajennettu',
'protect-expiring'            => 'vanhenee $1',
'protect-expiry-indefinite'   => 'ikuinen',
'protect-cascade'             => 'Laajenna suojaus koskemaan kaikkia tähän sivuun sisällytettyjä sivuja.',
'protect-cantedit'            => 'Et voi muuttaa sivun suojaustasoa, koska sinulla ei ole oikeutta muokata sivua.',
'protect-othertime'           => 'Muu kesto',
'protect-othertime-op'        => 'muu kesto',
'protect-existing-expiry'     => 'Nykyinen vanhenemisaika: $3 kello $2',
'protect-otherreason'         => 'Muu syy tai tarkennus',
'protect-otherreason-op'      => 'Muu syy',
'protect-dropdown'            => '*Yleiset suojaussyyt
** Jatkuva vandalismi
** Jatkuva mainoslinkkien lisääminen
** Muokkaussota
** Suuri näkyvyys',
'protect-edit-reasonlist'     => 'Muokkaa suojaussyitä',
'protect-expiry-options'      => '1 tunti:1 hour,1 päivä:1 day,1 viikko:1 week,2 viikkoa:2 weeks,1 kuukausi:1 month,3 kuukautta:3 months,6 kuukautta:6 months,1 vuosi:1 year,ikuinen:infinite',
'restriction-type'            => 'Rajoitus',
'restriction-level'           => 'Suojaus',
'minimum-size'                => 'Vähimmäiskoko',
'maximum-size'                => 'Enimmäiskoko',
'pagesize'                    => 'tavua',

# Restrictions (nouns)
'restriction-edit'   => 'muokkaus',
'restriction-move'   => 'siirto',
'restriction-create' => 'luonti',
'restriction-upload' => 'tiedostotallennus',

# Restriction levels
'restriction-level-sysop'         => 'täysin suojattu',
'restriction-level-autoconfirmed' => 'osittaissuojattu',
'restriction-level-all'           => 'mikä tahansa suojaus',

# Undelete
'undelete'                     => 'Palauta poistettuja sivuja',
'undeletepage'                 => 'Poistettujen sivujen selaus',
'undeletepagetitle'            => "'''Poistetut versiot sivusta [[:$1]]'''.",
'viewdeletedpage'              => 'Poistettujen sivujen selaus',
'undeletepagetext'             => '{{PLURAL:$1|Seuraava sivu|Seuraavat sivut}} on poistettu, mutta {{PLURAL:$1|se löytyy|ne löytyvät}} vielä arkistosta, joten {{PLURAL:$1|se on|ne ovat}} palautettavissa. Arkisto saatetaan tyhjentää aika ajoin.',
'undelete-fieldset-title'      => 'Palauta versiot',
'undeleteextrahelp'            => "Palauttaaksesi sivun koko muutoshistorian, jätä kaikki valintalaatikot tyhjiksi ja napsauta '''''Palauta'''''. Voit palauttaa versiota valikoivasti valitsemalla vain niiden versioiden valintalaatikot, jotka haluat palauttaa. Voit tyhjentää kommenttikentän ja kaikki valintalaatikot napsauttamalla '''''Tyhjennä'''''.",
'undeleterevisions'            => '{{PLURAL:$1|Versio|$1 versiota}} arkistoitu.',
'undeletehistory'              => 'Jos palautat sivun, kaikki versiot lisätään sivun historiaan. Jos uusi sivu samalla nimellä on luotu poistamisen jälkeen, palautetut versiot lisätään sen historiaan.',
'undeleterevdel'               => 'Palautusta ei tehdä, jos sen seurauksena sivun uusin versio olisi osittain poistettu. Tässä tilanteessa poista uusimman poistettavan version piilotus. Tiedostoversioita, joihin sinulla ei ole katseluoikeutta ei palauteta.',
'undeletehistorynoadmin'       => 'Tämä sivu on poistettu. Syy sivun poistamiseen näkyy yhteenvedossa, jossa on myös tiedot, ketkä ovat muokanneet tätä sivua ennen poistamista. Sivujen varsinainen sisältö on vain ylläpitäjien luettavissa.',
'undelete-revision'            => 'Poistettu sivu $1 hetkellä $4 kello $5. Tekijä: $3.',
'undeleterevision-missing'     => 'Virheellinen tai puuttuva versio. Se on saatettu palauttaa tai poistaa arkistosta.',
'undelete-nodiff'              => 'Aikaisempaa versiota ei löytynyt.',
'undeletebtn'                  => 'Palauta',
'undeletelink'                 => 'näytä tai palauta',
'undeleteviewlink'             => 'näytä',
'undeletereset'                => 'Tyhjennä',
'undeleteinvert'               => 'Käänteinen valinta',
'undeletecomment'              => 'Syy',
'undeletedarticle'             => 'palautti sivun [[$1]]',
'undeletedrevisions'           => '{{PLURAL:$1|Yksi versio|$1 versiota}} palautettiin',
'undeletedrevisions-files'     => '{{PLURAL:$1|Yksi versio|$1 versiota}} ja {{PLURAL:$2|yksi tiedosto|$2 tiedostoa}} palautettiin',
'undeletedfiles'               => '{{PLURAL:$1|1 tiedosto|$1 tiedostoa}} palautettiin',
'cannotundelete'               => 'Palauttaminen epäonnistui.',
'undeletedpage'                => "'''$1 on palautettu.'''

[[Special:Log/delete|Poistolokista]] löydät listan viimeisimmistä poistoista ja palautuksista.",
'undelete-header'              => '[[Special:Log/delete|Poistolokissa]] on lista viimeisimmistä poistoista.',
'undelete-search-box'          => 'Etsi poistettuja sivuja',
'undelete-search-prefix'       => 'Näytä sivut, jotka alkavat merkkijonolla:',
'undelete-search-submit'       => 'Hae',
'undelete-no-results'          => 'Poistoarkistosta ei löytynyt haettuja sivuja.',
'undelete-filename-mismatch'   => 'Tiedoston version, jonka aikaleima on $1 palauttaminen epäonnistui, koska tiedostonimi ei ole sama.',
'undelete-bad-store-key'       => 'Tiedoston version, jonka aikaleima on $1 palauttaminen epäonnistui, koska tiedostoa ei ollut ennen poistoa.',
'undelete-cleanup-error'       => 'Käyttämättömän arkistotiedoston $1 poistaminen epäonnistui.',
'undelete-missing-filearchive' => 'Tiedostoarkiston tunnuksen $1 hakeminen epäonnistui. Tiedosto on saatettu jo palauttaa.',
'undelete-error-short'         => 'Tiedoston $1 palauttaminen epäonnistui',
'undelete-error-long'          => 'Tiedoston palauttaminen epäonnistui:

$1',
'undelete-show-file-confirm'   => 'Haluatko varmasti nähdä poistetun version tiedostosta <nowiki>$1</nowiki>, joka on tallennettu $2 kello $3?',
'undelete-show-file-submit'    => 'Kyllä',

# Namespace form on various pages
'namespace'      => 'Nimiavaruus',
'invert'         => 'Käänteinen valinta',
'blanknamespace' => '(sivut)',

# Contributions
'contributions'       => 'Käyttäjän muokkaukset',
'contributions-title' => 'Käyttäjän $1 muokkaukset',
'mycontris'           => 'Omat muokkaukset',
'contribsub2'         => 'Käyttäjän $1 ($2) muokkaukset',
'nocontribs'          => 'Näihin ehtoihin sopivia muokkauksia ei löytynyt.',
'uctop'               => ' (uusin)',
'month'               => 'Kuukausi',
'year'                => 'Vuosi',

'sp-contributions-newbies'             => 'Näytä uusien tulokkaiden muutokset',
'sp-contributions-newbies-sub'         => 'Uusien tulokkaiden muokkaukset',
'sp-contributions-newbies-title'       => 'Uusien tulokkaiden muokkaukset',
'sp-contributions-blocklog'            => 'estot',
'sp-contributions-deleted'             => 'poistetut muokkaukset',
'sp-contributions-uploads'             => 'tallennukset',
'sp-contributions-logs'                => 'lokit',
'sp-contributions-talk'                => 'keskustelu',
'sp-contributions-userrights'          => 'käyttöoikeuksien hallinta',
'sp-contributions-blocked-notice'      => 'Tämä käyttäjä on tällä hetkellä estetty. Alla on viimeisin estolokin tapahtuma:',
'sp-contributions-blocked-notice-anon' => 'Tämä IP-osoite on tällä hetkellä estetty.
Alla on viimeisin estolokin tapahtuma:',
'sp-contributions-search'              => 'Etsi muokkauksia',
'sp-contributions-username'            => 'IP-osoite tai käyttäjätunnus',
'sp-contributions-toponly'             => 'Näytä vain muokkaukset, jotka ovat viimeisimpiä versioita',
'sp-contributions-submit'              => 'Hae',

# What links here
'whatlinkshere'            => 'Tänne viittaavat sivut',
'whatlinkshere-title'      => 'Sivut, jotka viittaavat sivulle $1',
'whatlinkshere-page'       => 'Sivu',
'linkshere'                => 'Seuraavilta sivuilta on linkki sivulle <strong>[[:$1]]</strong>:',
'nolinkshere'              => 'Sivulle <strong>[[:$1]]</strong> ei ole linkkejä.',
'nolinkshere-ns'           => 'Sivulle <strong>[[:$1]]</strong> ei ole linkkejä valitussa nimiavaruudessa.',
'isredirect'               => 'ohjaussivu',
'istemplate'               => 'sisällytetty mallineeseen',
'isimage'                  => 'tiedostolinkki',
'whatlinkshere-prev'       => '← {{PLURAL:$1|edellinen sivu|$1 edellistä sivua}}',
'whatlinkshere-next'       => '{{PLURAL:$1|seuraava sivu|$1 seuraavaa sivua}} →',
'whatlinkshere-links'      => 'viittaukset',
'whatlinkshere-hideredirs' => '$1 ohjaukset',
'whatlinkshere-hidetrans'  => '$1 sisällytykset',
'whatlinkshere-hidelinks'  => '$1 linkit',
'whatlinkshere-hideimages' => '$1 tiedostolinkit',
'whatlinkshere-filters'    => 'Suotimet',

# Block/unblock
'blockip'                         => 'Estä käyttäjä',
'blockip-title'                   => 'Estä käyttäjä',
'blockip-legend'                  => 'Estä käyttäjä',
'blockiptext'                     => 'Tällä lomakkeella voit estää käyttäjän tai IP-osoitteen muokkausoikeudet. Muokkausoikeuksien poistamiseen [[{{MediaWiki:Policy-url}}|pitää olla syy]], esimerkiksi sivujen vandalisointi. Kirjoita syy siihen varattuun kenttään.<br />Vapaamuotoisen vanhenemisajat noudattavat GNUn standardimuotoa, joka on kuvattu tar-manuaalissa ([http://www.gnu.org/software/tar/manual/html_node/Date-input-formats.html] [EN]), esimerkiksi ”1 hour”, ”2 days”, ”next Wednesday”, ”2014-08-29”.',
'ipaddress'                       => 'IP-osoite',
'ipadressorusername'              => 'IP-osoite tai käyttäjätunnus',
'ipbexpiry'                       => 'Kesto',
'ipbreason'                       => 'Syy',
'ipbreasonotherlist'              => 'Muu syy',
'ipbreason-dropdown'              => '*Yleiset estosyyt
** Väärän tiedon lisääminen
** Sisällön poistaminen
** Mainoslinkkien lisääminen
** Sotkeminen tai roskan lisääminen
** Häiriköinti
** Useamman käyttäjätunnuksen väärinkäyttö
** Sopimaton käyttäjätunnus',
'ipbanononly'                     => 'Estä vain kirjautumattomat käyttäjät',
'ipbcreateaccount'                => 'Estä tunnusten luonti',
'ipbemailban'                     => 'Estä käyttäjää lähettämästä sähköpostia',
'ipbenableautoblock'              => 'Estä viimeisin IP-osoite, josta käyttäjä on muokannut, sekä ne osoitteet, joista hän jatkossa yrittää muokata.',
'ipbsubmit'                       => 'Estä',
'ipbother'                        => 'Vapaamuotoinen kesto',
'ipboptions'                      => '2 tuntia:2 hours,1 päivä:1 day,3 päivää:3 days,1 viikko:1 week,2 viikkoa:2 weeks,1 kuukausi:1 month,3 kuukautta:3 months,6 kuukautta:6 months,1 vuosi:1 year,ikuinen:infinite',
'ipbotheroption'                  => 'Muu kesto',
'ipbotherreason'                  => 'Muu syy tai tarkennus',
'ipbhidename'                     => 'Piilota tunnus muokkauksista ja listauksista',
'ipbwatchuser'                    => 'Tarkkaile tämän käyttäjän käyttäjä- ja keskustelusivua',
'ipballowusertalk'                => 'Salli tämän käyttäjän muokata omaa keskustelusivuaan ollessaan estetty',
'ipb-change-block'                => 'Estä uudelleen näillä asetuksilla',
'badipaddress'                    => 'IP-osoite on väärin muotoiltu.',
'blockipsuccesssub'               => 'Esto onnistui',
'blockipsuccesstext'              => 'Käyttäjä tai IP-osoite [[Special:Contributions/$1|$1]] on estetty.<br />
Nykyiset estot löytyvät [[Special:IPBlockList|estolistalta]].',
'ipb-edit-dropdown'               => 'Muokkaa estosyitä',
'ipb-unblock-addr'                => 'Poista käyttäjän $1 esto',
'ipb-unblock'                     => 'Poista käyttäjän tai IP-osoitteen muokkausesto',
'ipb-blocklist'                   => 'Näytä estot',
'ipb-blocklist-contribs'          => 'Käyttäjän $1 muokkaukset',
'unblockip'                       => 'Muokkauseston poisto',
'unblockiptext'                   => 'Tällä lomakkeella voit poistaa käyttäjän tai IP-osoitteen muokkauseston.',
'ipusubmit'                       => 'Poista esto',
'unblocked'                       => 'Käyttäjän [[User:$1|$1]] esto on poistettu',
'unblocked-id'                    => 'Esto $1 on poistettu',
'ipblocklist'                     => 'Estetyt käyttäjät',
'ipblocklist-legend'              => 'Haku',
'ipblocklist-username'            => 'Käyttäjätunnus tai IP-osoite',
'ipblocklist-sh-userblocks'       => '$1 käyttäjätunnusten estot',
'ipblocklist-sh-tempblocks'       => '$1 väliaikaiset estot',
'ipblocklist-sh-addressblocks'    => '$1 yksittäiset IP-estot',
'ipblocklist-submit'              => 'Hae',
'ipblocklist-localblock'          => 'Paikallinen esto',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Muu esto|Muut estot}}',
'blocklistline'                   => '$1 – $2 on estänyt käyttäjän $3 ($4)',
'infiniteblock'                   => 'ikuisesti',
'expiringblock'                   => 'vanhenee $1 kello $2',
'anononlyblock'                   => 'vain kirjautumattomat',
'noautoblockblock'                => 'ei automaattista IP-osoitteiden estoa',
'createaccountblock'              => 'tunnusten luonti estetty',
'emailblock'                      => 'sähköpostin lähettäminen estetty',
'blocklist-nousertalk'            => 'oman keskustelusivun muokkaaminen estetty',
'ipblocklist-empty'               => 'Estolista on tyhjä.',
'ipblocklist-no-results'          => 'Pyydettyä IP-osoitetta tai käyttäjätunnusta ei ole estetty.',
'blocklink'                       => 'estä',
'unblocklink'                     => 'poista esto',
'change-blocklink'                => 'muuta estoa',
'contribslink'                    => 'muokkaukset',
'autoblocker'                     => 'Olet automaattisesti estetty, koska jaat IP-osoitteen käyttäjän [[User:$1|$1]] kanssa. Eston syy: $2.',
'blocklogpage'                    => 'Estoloki',
'blocklog-showlog'                => 'Tämä käyttäjä on ollut estettynä.
Alla on ote estolokista.',
'blocklog-showsuppresslog'        => 'Tämä käyttäjä on ollut estettynä ja häivytettynä.
Alla on ote häivytyslokista.',
'blocklogentry'                   => 'esti käyttäjän tai IP-osoitteen [[$1]]. Eston kesto $2 $3',
'reblock-logentry'                => 'muutti käyttäjän tai IP-osoitteen [[$1]] eston asetuksia. Eston kesto $2 $3',
'blocklogtext'                    => 'Tämä on loki muokkausestoista ja niiden purkamisista. Automaattisesti estettyjä IP-osoitteita ei kirjata. Tutustu [[Special:IPBlockList|estolistaan]] nähdäksesi listan tällä hetkellä voimassa olevista estoista.',
'unblocklogentry'                 => 'poisti käyttäjältä $1 muokkauseston',
'block-log-flags-anononly'        => 'vain kirjautumattomat käyttäjät',
'block-log-flags-nocreate'        => 'tunnusten luonti estetty',
'block-log-flags-noautoblock'     => 'ei automaattista IP-osoitteiden estoa',
'block-log-flags-noemail'         => 'sähköpostin lähettäminen estetty',
'block-log-flags-nousertalk'      => 'oman keskustelusivun muokkaaminen estetty',
'block-log-flags-angry-autoblock' => 'kehittynyt automaattiesto käytössä',
'block-log-flags-hiddenname'      => 'käyttäjänimi piilotettu',
'range_block_disabled'            => 'Ylläpitäjän oikeus luoda alue-estoja ei ole käytössä.',
'ipb_expiry_invalid'              => 'Virheellinen umpeutumisaika.',
'ipb_expiry_temp'                 => 'Piilotettujen käyttäjätunnusten estojen tulee olla pysyviä.',
'ipb_hide_invalid'                => 'Tämän tunnuksen piilottaminen ei onnistu. Sillä saattaa olla liikaa muokkauksia.',
'ipb_already_blocked'             => '”$1” on jo estetty.',
'ipb-needreblock'                 => '== Estetty jo ==
$1 on jo estetty. Haluatko muuttaa eston asetuksia?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Muu esto|Muut estot}}',
'ipb_cant_unblock'                => 'Estoa ”$1” ei löytynyt. Se on saatettu poistaa.',
'ipb_blocked_as_range'            => 'IP-osoite $1 on estetty välillisesti ja sen estoa ei voi poistaa. Se on estetty osana verkkoaluetta $2, jonka eston voi poistaa',
'ip_range_invalid'                => 'Virheellinen IP-alue.',
'ip_range_toolarge'               => 'Suuremmat osoitealue-estot kuin /$1 eivät ole sallittuja.',
'blockme'                         => 'Estä minut',
'proxyblocker'                    => 'Välityspalvelinesto',
'proxyblocker-disabled'           => 'Tämä toiminto ei ole käytössä.',
'proxyblockreason'                => 'IP-osoitteestasi on estetty muokkaukset, koska se on avoin välityspalvelin. Ota yhteyttä Internet-palveluntarjoajaasi tai tekniseen tukeen ja kerro heillä tästä tietoturvaongelmasta.',
'proxyblocksuccess'               => 'Valmis.',
'sorbsreason'                     => 'IP-osoitteesi on listattu avoimena välityspalvelimena DNSBLin mustalla listalla.',
'sorbs_create_account_reason'     => 'IP-osoitteesi on listattu avoimena välityspalvelimena DNSBLin mustalla listalla. Et voi luoda käyttäjätunnusta.',
'cant-block-while-blocked'        => 'Et voi estää muita käyttäjiä ollessasi estetty.',
'cant-see-hidden-user'            => 'Käyttäjä, jota yrität estää on jo estetty ja piilotettu. Koska sinulla ei ole hideuser-oikeutta, et voi nähdä tai muokata käyttäjän estoa.',
'ipbblocked'                      => 'Et voi estää tai poistaa estoja muilta käyttäjiltä, koska itse olet estettynä',
'ipbnounblockself'                => 'Et ole oikeutettu poistamaan estoa itseltäsi',

# Developer tools
'lockdb'              => 'Lukitse tietokanta',
'unlockdb'            => 'Vapauta tietokanta',
'lockdbtext'          => 'Tietokannan lukitseminen estää käyttäjiä muokkaamasta sivuja, vaihtamasta asetuksia, muokkaamasta tarkkailulistoja ja tekemästä muita tietokannan muuttamista vaativia toimia. Ole hyvä ja vahvista, että tämä on tarkoituksesi, ja että vapautat tietokannan kun olet suorittanut ylläpitotehtävät.',
'unlockdbtext'        => 'Tietokannan vapauttaminen antaa käyttäjille mahdollisuuden muokata sivuja, vaihtaa asetuksia, muokata tarkkailulistoja ja tehdä muita tietokannan muuttamista vaativia toimia. Ole hyvä ja vahvista, että tämä on tarkoituksesi.',
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

# Move page
'move-page'                    => 'Sivun $1 siirto',
'move-page-legend'             => 'Siirrä sivu',
'movepagetext'                 => "Alla olevalla lomakkeella voit nimetä uudelleen sivuja, jolloin niiden koko historia siirtyy uuden nimen alle.
Vanhasta sivusta tulee ohjaussivu, joka osoittaa uuteen sivuun.
Voit päivittää sivuun viittaavat ohjaukset automaattisesti ohjaamaan uudelle nimelle.
Jos et halua tätä tehtävän automaattisesti, muista tehdä tarkistukset [[Special:DoubleRedirects|kaksinkertaisten]] tai [[Special:BrokenRedirects|rikkinäisten]] ohjausten varalta.
Olet vastuussa siitä, että linkit osoittavat sinne, mihin niiden on tarkoituskin osoittaa.

Huomaa, että sivua '''ei''' siirretä mikäli uusi otsikko on olemassa olevan sivun käytössä, paitsi milloin kyseessä on tyhjä sivu tai ohjaus, jolla ei ole muokkaushistoriaa.
Tämä tarkoittaa sitä, että voit siirtää sivun takaisin vanhalle nimelleen mikäli teit virheen, mutta et voi kirjoittaa olemassa olevan sivun päälle.

Tämä saattaa olla suuri ja odottamaton muutos suositulle sivulle. Varmista, että tiedät seuraukset ennen kuin siirrät sivun.",
'movepagetext-noredirectfixer' => "Alla olevalla lomakkeella voit nimetä uudelleen sivuja, jolloin niiden koko historia siirtyy uuden nimen alle. Vanhasta sivusta tulee ohjaussivu, joka osoittaa uuteen sivuun.

Tarkasta sivuun viittaavat ohjaukset [[Special:DoubleRedirects|kaksinkertaisten]] tai [[Special:BrokenRedirects|rikkinäisten]] ohjausten varalta. Olet vastuussa siitä, että linkit osoittavat sinne, mihin niiden on tarkoituskin osoittaa.

Huomaa, että sivua '''ei''' siirretä mikäli uusi otsikko on olemassa olevan sivun käytössä, paitsi milloin kyseessä on tyhjä sivu tai ohjaus, jolla ei ole muokkaushistoriaa. Tämä tarkoittaa sitä, että voit siirtää sivun takaisin vanhalle nimelleen mikäli teit virheen, mutta et voi kirjoittaa olemassa olevan sivun päälle.

Tämä saattaa olla suuri ja odottamaton muutos suositulle sivulle. Varmista, että tiedät seuraukset ennen kuin siirrät sivun.",
'movepagetalktext'             => "Sivuun mahdollisesti kytketty keskustelusivu siirretään automaattisesti, '''paitsi jos''':
*Siirrät sivua nimiavaruudesta toiseen
*Kohdesivulla on olemassa keskustelusivu, joka ei ole tyhjä, tai
*Kumoat alla olevan ruudun asetuksen.

Näissä tapauksissa sivut täytyy siirtää tai yhdistää käsin.",
'movearticle'                  => 'Siirrettävä sivu',
'moveuserpage-warning'         => "'''Varoitus:''' Olet siirtämässä käyttäjäsivua. Huomaa, että vain sivu siirretään ja käyttäjää ''ei'' nimetä uudelleen.",
'movenologin'                  => 'Et ole kirjautunut sisään',
'movenologintext'              => 'Sinun pitää olla rekisteröitynyt käyttäjä ja [[Special:UserLogin|kirjautua sisään]], jotta voisit siirtää sivun.',
'movenotallowed'               => 'Sinulla ei ole oikeuksia siirtää sivuja.',
'movenotallowedfile'           => 'Sinulla ei ole oikeuksia siirtää tiedostoja.',
'cant-move-user-page'          => 'Sinulla ei ole lupaa siirtää käyttäjäsivuja (lukuun ottamatta alasivuja).',
'cant-move-to-user-page'       => 'Sinulla ei ole lupaa siirtää sivuja käyttäjäsivuiksi (paitsi alasivuiksi).',
'newtitle'                     => 'Uusi nimi sivulle',
'move-watch'                   => 'Tarkkaile tätä sivua',
'movepagebtn'                  => 'Siirrä sivu',
'pagemovedsub'                 => 'Siirto onnistui',
'movepage-moved'               => "'''$1 on siirretty nimelle $2'''",
'movepage-moved-redirect'      => 'Ohjaus luotiin.',
'movepage-moved-noredirect'    => 'Ohjausta ei luotu.',
'articleexists'                => 'Kohdesivu on jo olemassa, tai valittu nimi ei ole sopiva. Ole hyvä ja valitse uusi nimi.',
'cantmove-titleprotected'      => 'Sivua ei voi siirtää tälle nimelle, koska tämän nimisen sivun luonti on estetty.',
'talkexists'                   => 'Sivun siirto onnistui, mutta keskustelusivua ei voitu siirtää, koska uuden otsikon alla on jo keskustelusivu. Keskustelusivujen sisältö täytyy yhdistää käsin.',
'movedto'                      => 'Siirretty uudelle otsikolle',
'movetalk'                     => 'Siirrä myös keskustelusivu',
'move-subpages'                => 'Siirrä kaikki alasivut (enintään $1)',
'move-talk-subpages'           => 'Siirrä kaikki keskustelusivun alasivut (enintään $1)',
'movepage-page-exists'         => 'Sivu $1 on jo olemassa ja sitä ei voi automaattisesti korvata.',
'movepage-page-moved'          => 'Sivu $1 on siirretty nimelle $2.',
'movepage-page-unmoved'        => 'Sivua $1 ei voitu siirtää nimelle $2.',
'movepage-max-pages'           => 'Enimmäismäärä sivuja on siirretty, eikä enempää siirretä enää automaattisesti.
$1 {{PLURAL:$1|sivu|sivua}} siirrettiin.',
'1movedto2'                    => 'siirsi sivun [[$1]] uudelle nimelle [[$2]]',
'1movedto2_redir'              => 'siirsi sivun [[$1]] ohjauksen [[$2]] päälle',
'move-redirect-suppressed'     => 'ei ohjausta',
'movelogpage'                  => 'Siirtoloki',
'movelogpagetext'              => 'Tämä on loki siirretyistä sivuista.',
'movesubpage'                  => '{{PLURAL:$1|Alasivu|Alasivut}}',
'movesubpagetext'              => 'Tällä sivulla on $1 {{PLURAL:$1|alasivu|alasivua}}, jotka näkyvät alla.',
'movenosubpage'                => 'Tällä sivulla ei ole alasivuja.',
'movereason'                   => 'Syy',
'revertmove'                   => 'kumoa',
'delete_and_move'              => 'Poista kohdesivu ja siirrä',
'delete_and_move_text'         => 'Kohdesivu [[:$1]] on jo olemassa. Haluatko poistaa sen, jotta nykyinen sivu voitaisiin siirtää?',
'delete_and_move_confirm'      => 'Poista sivu',
'delete_and_move_reason'       => 'Sivu on siirron tiellä.',
'selfmove'                     => 'Lähde- ja kohdenimi ovat samat.',
'immobile-source-namespace'    => 'Sivuja ei voi siirtää nimiavaruudessa ”$1”',
'immobile-target-namespace'    => 'Sivuja ei voi siirtää nimiavaruuteen ”$1”',
'immobile-target-namespace-iw' => 'Kielilinkki ei ole kelvollinen kohde sivun siirrolle.',
'immobile-source-page'         => 'Tämä sivu ei ole siirrettävissä.',
'immobile-target-page'         => 'Kyseiselle kohdenimelle ei voi siirtää.',
'imagenocrossnamespace'        => 'Tiedostoja ei voi siirtää pois tiedostonimiavaruudesta.',
'nonfile-cannot-move-to-file'  => 'Sivuja ei voi siirtää tiedostonimiavaruuteen.',
'imagetypemismatch'            => 'Uusi tiedostopääte ei vastaa tiedoston tyyppiä',
'imageinvalidfilename'         => 'Kohdenimi on virheellinen',
'fix-double-redirects'         => 'Päivitä kaikki tänne viittaavat ohjaukset ohjaamaan uudelle nimelle',
'move-leave-redirect'          => 'Jätä paikalle ohjaus',
'protectedpagemovewarning'     => "'''Varoitus:''' Tämä sivu on lukittu siten, että vain ylläpitäjät voivat siirtää sitä.
Alla on viimeisin lokitapahtuma:",
'semiprotectedpagemovewarning' => 'Tämä sivu on lukittu siten, että vain rekisteröityneet käyttäjät voivat siirtää sitä.
Alla on viimeisin lokitapahtuma:',
'move-over-sharedrepo'         => '== Tiedosto on olemassa ==
[[:$1]] on olemassa jaetussa tietovarastossa. Tiedoston siirtäminen tälle nimelle ohittaa jaetun tiedoston.',
'file-exists-sharedrepo'       => 'Valittu tiedostonimi on jo käytössä jaetussa varastossa.
Valitse toinen nimi.',

# Export
'export'            => 'Sivujen vienti',
'exporttext'        => 'Voit viedä sivun tai sivujen tekstiä ja muokkaushistoriaa XML-muodossa.
Tämä tieto voidaan tuoda toiseen käyttämällä MediaWikiä [[Special:Import|tuontisivun]] kautta.

Syötä sivujen otsikoita jokainen omalle rivilleen alla olevaan laatikkoon.
Valitse myös, haluatko kaikki versiot sivuista, vai ainoastaan nykyisen version.

Jälkimmäisessä tapauksessa voit myös käyttää linkkiä. Esimerkiksi sivun [[{{MediaWiki:Mainpage}}]] saa vietyä linkistä [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Liitä mukaan ainoastaan uusin versio – ei koko historiaa.',
'exportnohistory'   => '----
Sivujen koko historian vienti on estetty suorituskykysyistä.',
'export-submit'     => 'Vie',
'export-addcattext' => 'Lisää sivut luokasta',
'export-addcat'     => 'Lisää',
'export-addnstext'  => 'Lisää sivut nimiavaruudesta',
'export-addns'      => 'Lisää',
'export-download'   => 'Tallenna tiedostona',
'export-templates'  => 'Liitä mallineet',
'export-pagelinks'  => 'Sisällytä linkkien kohteina olevat sivut syvyydelle',

# Namespace 8 related
'allmessages'                   => 'Järjestelmäviestit',
'allmessagesname'               => 'Nimi',
'allmessagesdefault'            => 'Oletusarvo',
'allmessagescurrent'            => 'Nykyinen arvo',
'allmessagestext'               => 'Tämä on luettelo järjestelmäviesteistä, jotka ovat saatavilla MediaWiki-nimiavaruudessa.
Jos haluat muokata MediaWikin yleistä kotoistusta, käy [http://www.mediawiki.org/wiki/Localisation MediaWikin kotoistussivuilla] ja sivustolla [http://translatewiki.net translatewiki.net].',
'allmessagesnotsupportedDB'     => 'Tämä sivu ei ole käytössä, koska <tt>$wgUseDatabaseMessages</tt>-asetus on pois päältä.',
'allmessages-filter-legend'     => 'Suodata',
'allmessages-filter'            => 'Suodata muutosten perusteella',
'allmessages-filter-unmodified' => 'Muuttamaton',
'allmessages-filter-all'        => 'Kaikki',
'allmessages-filter-modified'   => 'Muutettu',
'allmessages-prefix'            => 'Suodata etuliitteellä',
'allmessages-language'          => 'Kieli:',
'allmessages-filter-submit'     => 'Siirry',

# Thumbnails
'thumbnail-more'           => 'Suurenna',
'filemissing'              => 'Tiedosto puuttuu',
'thumbnail_error'          => 'Pienoiskuvan luominen epäonnistui: $1',
'djvu_page_error'          => 'DjVu-tiedostossa ei ole pyydettyä sivua',
'djvu_no_xml'              => 'DjVu-tiedoston XML-vienti epäonnistui',
'thumbnail_invalid_params' => 'Virheelliset parametrit pienoiskuvalle',
'thumbnail_dest_directory' => 'Kohdehakemiston luominen ei onnistunut',
'thumbnail_image-type'     => 'Kuvamuoto ei ole tuettu',
'thumbnail_gd-library'     => 'GD-kirjastoa ei ole asennettu oikein. Funktio $1 puuttuu.',
'thumbnail_image-missing'  => 'Tiedosto näyttää puuttuvan: $1',

# Special:Import
'import'                     => 'Tuo sivuja',
'importinterwiki'            => 'Tuo sivuja lähiwikeistä',
'import-interwiki-text'      => 'Valitse wiki ja sivun nimi. Versioiden päivämäärät ja muokkaajat säilytetään. Kaikki wikienväliset tuonnit kirjataan [[Special:Log/import|tuontilokiin]].',
'import-interwiki-source'    => 'Lähdewiki/sivu:',
'import-interwiki-history'   => 'Kopioi sivun koko historia',
'import-interwiki-templates' => 'Liitä kaikki mallineet',
'import-interwiki-submit'    => 'Tuo',
'import-interwiki-namespace' => 'Kohdenimiavaruus:',
'import-upload-filename'     => 'Tiedostonimi:',
'import-comment'             => 'Syy',
'importtext'                 => 'Vie sivuja lähdewikistä käyttäen [[Special:Export|vientityökalua]].
Tallenna tiedot koneellesi ja tuo ne tällä sivulla.',
'importstart'                => 'Tuodaan sivuja...',
'import-revision-count'      => '$1 {{PLURAL:$1|versio|versiota}}',
'importnopages'              => 'Ei tuotavia sivuja.',
'imported-log-entries'       => 'Tuotu $1 {{PLURAL:$1|lokitapahtuma|lokitapahtumaa}}.',
'importfailed'               => 'Tuonti epäonnistui: $1',
'importunknownsource'        => 'Tuntematon lähdetyyppi',
'importcantopen'             => 'Tuontitiedoston avaus epäonnistui',
'importbadinterwiki'         => 'Kelpaamaton wikienvälinen linkki',
'importnotext'               => 'Tyhjä tai ei tekstiä',
'importsuccess'              => 'Tuonti onnistui!',
'importhistoryconflict'      => 'Sivusta on olemassa tuonnin kanssa ristiriitainen muokkausversio. Tämä sivu on saatettu tuoda jo aikaisemmin.',
'importnosources'            => 'Wikienvälisiä tuontilähteitä ei ole määritelty ja suorat historiatallennukset on poistettu käytöstä.',
'importnofile'               => 'Mitään tuotavaa tiedostoa ei lähetetty.',
'importuploaderrorsize'      => 'Tuontitiedoston tallennus epäonnistui. Tiedosto on suurempi kuin sallittu yläraja.',
'importuploaderrorpartial'   => 'Tuontitiedoston tallennus epäonnistui. Tiedostosta oli lähetetty vain osa.',
'importuploaderrortemp'      => 'Tuontitiedoston tallennus epäonnistui. Väliaikaistiedostojen kansio puuttuu.',
'import-parse-failure'       => 'XML-tuonti epäonnistui jäsennysvirheen takia.',
'import-noarticle'           => 'Ei tuotavaa sivua.',
'import-nonewrevisions'      => 'Kaikki versiot on tuotu aiemmin.',
'xml-error-string'           => '$1 rivillä $2, sarakkeessa $3 (tavu $4): $5',
'import-upload'              => 'Tallenna XML-tiedosto',
'import-token-mismatch'      => 'Istuntotiedot ovat kadonneet. Yritä uudelleen.',
'import-invalid-interwiki'   => 'Määritellystä wikistä ei voi tuoda.',

# Import log
'importlogpage'                    => 'Tuontiloki',
'importlogpagetext'                => 'Loki toisista wikeistä tuoduista sivuista.',
'import-logentry-upload'           => 'toi sivun ”[[$1]]” lähettämällä tiedoston',
'import-logentry-upload-detail'    => '{{PLURAL:$1|yksi versio|$1 versiota}}',
'import-logentry-interwiki'        => 'toi toisesta wikistä sivun ”$1”',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|yksi versio|$1 versiota}} wikistä $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Oma käyttäjäsivu',
'tooltip-pt-anonuserpage'         => 'IP-osoitteesi käyttäjäsivu',
'tooltip-pt-mytalk'               => 'Oma keskustelusivu',
'tooltip-pt-anontalk'             => 'Keskustelu tämän IP-osoitteen muokkauksista',
'tooltip-pt-preferences'          => 'Omat asetukset',
'tooltip-pt-watchlist'            => 'Lista sivuista, joiden muokkauksia tarkkailet',
'tooltip-pt-mycontris'            => 'Lista omista muokkauksista',
'tooltip-pt-login'                => 'Kirjaudu sisään tai luo tunnus',
'tooltip-pt-anonlogin'            => 'Kirjaudu sisään tai luo tunnus',
'tooltip-pt-logout'               => 'Kirjaudu ulos',
'tooltip-ca-talk'                 => 'Keskustele sisällöstä',
'tooltip-ca-edit'                 => 'Muokkaa tätä sivua',
'tooltip-ca-addsection'           => 'Lisää uusi osio',
'tooltip-ca-viewsource'           => 'Näytä sivun lähdekoodi',
'tooltip-ca-history'              => 'Sivun aikaisemmat versiot',
'tooltip-ca-protect'              => 'Suojaa tämä sivu',
'tooltip-ca-unprotect'            => 'Poista suojaus tältä sivulta',
'tooltip-ca-delete'               => 'Poista tämä sivu',
'tooltip-ca-undelete'             => 'Palauta tämä sivu',
'tooltip-ca-move'                 => 'Siirrä tämä sivu',
'tooltip-ca-watch'                => 'Lisää tämä sivu tarkkailulistallesi',
'tooltip-ca-unwatch'              => 'Poista tämä sivu tarkkailulistaltasi',
'tooltip-search'                  => 'Etsi {{GRAMMAR:elative|{{SITENAME}}}}',
'tooltip-search-go'               => 'Siirry sivulle, joka on tarkalleen tällä nimellä',
'tooltip-search-fulltext'         => 'Etsi sivuilta tätä tekstiä',
'tooltip-p-logo'                  => 'Etusivu',
'tooltip-n-mainpage'              => 'Mene etusivulle',
'tooltip-n-mainpage-description'  => 'Mene etusivulle',
'tooltip-n-portal'                => 'Keskustelua projektista',
'tooltip-n-currentevents'         => 'Taustatietoa tämänhetkisistä tapahtumista',
'tooltip-n-recentchanges'         => 'Lista tuoreista muutoksista',
'tooltip-n-randompage'            => 'Avaa satunnainen sivu',
'tooltip-n-help'                  => 'Ohjeita',
'tooltip-t-whatlinkshere'         => 'Lista sivuista, jotka viittaavat tänne',
'tooltip-t-recentchangeslinked'   => 'Viimeisimmät muokkaukset sivuissa, joille viitataan tältä sivulta',
'tooltip-feed-rss'                => 'RSS-syöte tälle sivulle',
'tooltip-feed-atom'               => 'Atom-syöte tälle sivulle',
'tooltip-t-contributions'         => 'Näytä lista tämän käyttäjän muokkauksista',
'tooltip-t-emailuser'             => 'Lähetä sähköpostia tälle käyttäjälle',
'tooltip-t-upload'                => 'Tallenna tiedostoja',
'tooltip-t-specialpages'          => 'Näytä toimintosivut',
'tooltip-t-print'                 => 'Tulostettava versio',
'tooltip-t-permalink'             => 'Ikilinkki sivun tähän versioon',
'tooltip-ca-nstab-main'           => 'Näytä sisältösivu',
'tooltip-ca-nstab-user'           => 'Näytä käyttäjäsivu',
'tooltip-ca-nstab-media'          => 'Näytä mediasivu',
'tooltip-ca-nstab-special'        => 'Tämä on toimintosivu',
'tooltip-ca-nstab-project'        => 'Näytä projektisivu',
'tooltip-ca-nstab-image'          => 'Näytä tiedostosivu',
'tooltip-ca-nstab-mediawiki'      => 'Näytä järjestelmäviesti',
'tooltip-ca-nstab-template'       => 'Näytä malline',
'tooltip-ca-nstab-help'           => 'Näytä ohjesivu',
'tooltip-ca-nstab-category'       => 'Näytä luokkasivu',
'tooltip-minoredit'               => 'Merkitse tämä pieneksi muutokseksi',
'tooltip-save'                    => 'Tallenna muokkaukset',
'tooltip-preview'                 => 'Esikatsele muokkausta ennen tallennusta',
'tooltip-diff'                    => 'Näytä tehdyt muutokset',
'tooltip-compareselectedversions' => 'Vertaile valittuja versioita',
'tooltip-watch'                   => 'Lisää tämä sivu tarkkailulistaan',
'tooltip-recreate'                => 'Luo sivu uudelleen',
'tooltip-upload'                  => 'Aloita tallennus',
'tooltip-rollback'                => 'Palauttaminen kumoaa viimeisimmän muokkaajan yhden tai useamman muutoksen yhdellä kertaa.',
'tooltip-undo'                    => 'Kumoaminen palauttaa tämän muutoksen ja avaa artikkelin esikatselussa. Yhteenvetokenttään voi kirjoittaa palautuksen syyn.',
'tooltip-preferences-save'        => 'Tallenna asetukset',
'tooltip-summary'                 => 'Kirjoita lyhyt yhteenveto',

# Stylesheets
'common.css'   => '/* Tämä sivu sisältää koko sivustoa muuttavia tyylejä. */',
'monobook.css' => '/* Tämä sivu sisältää Monobook-ulkoasua muuttavia tyylejä. */',

# Scripts
'common.js'      => '/* Tämän sivun koodi liitetään jokaiseen sivulataukseen */',
'nostalgia.js'   => '/* Tämän sivun JavaScript-koodi liitetään Nostalgia-tyyliin */',
'cologneblue.js' => '/* Tämän sivun JavaScript-koodi liitetään Kölnin sininen -tyyliin */',
'monobook.js'    => '/* Tämän sivun JavaScript-koodi liitetään Monobook-tyyliin */',
'simple.js'      => '/* Tämän sivun JavaScript-koodi liitetään Yksinkertaistettuun tyyliin */',
'modern.js'      => '/* Tämän sivun JavaScript-koodi liitetään Moderni-tyyliin */',

# Metadata
'nodublincore'      => 'Dublin Core RDF-metatieto on poissa käytöstä tällä palvelimella.',
'nocreativecommons' => 'Creative Commonsin RDF-metatieto on poissa käytöstä tällä palvelimella.',
'notacceptable'     => 'Wikipalvelin ei voi näyttää tietoja muodossa, jota ohjelmasi voisi lukea.',

# Attribution
'anonymous'        => '{{GRAMMAR:genitive|{{SITENAME}}}} {{PLURAL:$1|anonyymi käyttäjä|anonyymit käyttäjät}}',
'siteuser'         => '{{GRAMMAR:genitive|{{SITENAME}}}} käyttäjä $1',
'anonuser'         => '{{GRAMMAR:genitive|{{SITENAME}}}} anonyymi käyttäjä $1',
'lastmodifiedatby' => 'Tätä sivua muokkasi viimeksi $3 $1 kello $2.',
'othercontribs'    => 'Perustuu työlle, jonka teki $1.',
'others'           => 'muut',
'siteusers'        => '{{GRAMMAR:genitive|{{SITENAME}}}} {{PLURAL:$2|käyttäjä|käyttäjät}} $1',
'anonusers'        => '{{GRAMMAR:genitive|{{SITENAME}}}} {{PLURAL:$2|anonyymi käyttäjä|anonyymit käyttäjät}} $1',
'creditspage'      => 'Sivun tekijäluettelo',
'nocredits'        => 'Tämän sivun tekijäluettelotietoja ei löydy.',

# Spam protection
'spamprotectiontitle' => 'Mainossuodatin',
'spamprotectiontext'  => 'Mainossuodatin on estänyt sivun tallentamisen. Syynä on todennäköisimmin mustalistattu ulkopuoliselle sivustolle osoittava linkki.',
'spamprotectionmatch' => 'Teksti, joka ei läpäissyt mainossuodatinta: $1',
'spambot_username'    => 'MediaWikin mainospoistaja',
'spam_reverting'      => 'Palautettu viimeisimpään versioon, joka ei sisällä linkkejä kohteeseen $1.',
'spam_blanking'       => 'Kaikki versiot sisälsivät linkkejä kohteeseen $1. Sivu tyhjennetty.',

# Info page
'infosubtitle'   => 'Tietoja sivusta',
'numedits'       => 'Sivun muokkausten määrä: $1',
'numtalkedits'   => 'Keskustelusivun muokkausten määrä: $1',
'numwatchers'    => 'Tarkkailijoiden määrä: $1',
'numauthors'     => 'Sivun erillisten kirjoittajien määrä: $1',
'numtalkauthors' => 'Keskustelusivun erillisten kirjoittajien määrä: $1',

# Skin names
'skinname-standard'    => 'Perus',
'skinname-nostalgia'   => 'Nostalgia',
'skinname-cologneblue' => 'Kölnin sininen',
'skinname-monobook'    => 'Monobook',
'skinname-myskin'      => 'Oma tyylisivu',
'skinname-chick'       => 'Chick',
'skinname-simple'      => 'Yksinkertainen',
'skinname-modern'      => 'Moderni',

# Math options
'mw_math_png'    => 'Näytä aina PNG:nä',
'mw_math_simple' => 'Näytä HTML:nä, jos yksinkertainen, muuten PNG:nä',
'mw_math_html'   => 'Näytä HTML:nä, jos mahdollista, muuten PNG:nä',
'mw_math_source' => 'Näytä TeX-muodossa (tekstiselaimille)',
'mw_math_modern' => 'Suositus nykyselaimille',
'mw_math_mathml' => 'Näytä MathML:nä jos mahdollista (kokeellinen)',

# Math errors
'math_failure'          => 'Jäsentäminen epäonnistui',
'math_unknown_error'    => 'Tuntematon virhe',
'math_unknown_function' => 'Tuntematon funktio',
'math_lexing_error'     => 'Tulkintavirhe',
'math_syntax_error'     => 'Jäsennysvirhe',
'math_image_error'      => 'Muuntaminen PNG-tiedostomuotoon epäonnistui; tarkista, että latex ja dvipng (tai dvips, gs ja convert) on asennettu oikein.',
'math_bad_tmpdir'       => 'Matematiikan kirjoittaminen väliaikaishakemistoon tai tiedostonluonti ei onnistu',
'math_bad_output'       => 'Matematiikan tulostehakemistoon kirjoittaminen tai tiedostonluonti ei onnistu',
'math_notexvc'          => 'Texvc-sovellus puuttuu, lue math/READMEstä asennustietoja',

# Patrolling
'markaspatrolleddiff'                 => 'Merkitse tarkastetuksi',
'markaspatrolledtext'                 => 'Merkitse muokkaus tarkastetuksi',
'markedaspatrolled'                   => 'Tarkastettu',
'markedaspatrolledtext'               => 'Valittu versio sivusta [[:$1]] on merkitty tarkastetuksi.',
'rcpatroldisabled'                    => 'Tuoreiden muutosten tarkastustoiminto ei ole käytössä',
'rcpatroldisabledtext'                => 'Tuoreiden muutosten tarkastustoiminto ei ole käytössä.',
'markedaspatrollederror'              => 'Muutoksen merkitseminen tarkastetuksi epäonnistui.',
'markedaspatrollederrortext'          => 'Tarkastetuksi merkittävää versiota ei ole määritelty.',
'markedaspatrollederror-noautopatrol' => 'Et voi merkitä omia muutoksiasi tarkastetuiksi.',

# Patrol log
'patrol-log-page'      => 'Muutostentarkastusloki',
'patrol-log-header'    => 'Tämä on loki tarkastetuista muutoksista.',
'patrol-log-line'      => 'merkitsi sivun $2 muutoksen $1 tarkastetuksi $3',
'patrol-log-auto'      => '(automaattinen)',
'patrol-log-diff'      => 'versio $1',
'log-show-hide-patrol' => '$1 muutostentarkastusloki',

# Image deletion
'deletedrevision'                 => 'Poistettiin vanha versio $1',
'filedeleteerror-short'           => 'Tiedoston $1 poistaminen epäonnistui',
'filedeleteerror-long'            => 'Tiedoston poistaminen epäonnistui:

$1',
'filedelete-missing'              => 'Tiedostoa $1 ei voi poistaa, koska sitä ei ole olemassa.',
'filedelete-old-unregistered'     => 'Tiedoston version $1 ei ole tietokannassa.',
'filedelete-current-unregistered' => 'Tiedosto $1 ei ole tietokannassa.',
'filedelete-archive-read-only'    => 'Arkistohakemistoon ”$1” kirjoittaminen epäonnistui.',

# Browsing diffs
'previousdiff' => '← Vanhempi muutos',
'nextdiff'     => 'Uudempi muutos →',

# Media information
'mediawarning'         => "'''Varoitus''': Tämä tiedostomuoto saattaa sisältää vahingollista koodia.
Suorittamalla sen järjestelmäsi voi muuttua epäluotettavaksi.",
'imagemaxsize'         => 'Kuvien enimmäiskoko kuvaussivuilla',
'thumbsize'            => 'Pikkukuvien koko',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|sivu|sivua}}',
'file-info'            => '$1, MIME-tyyppi: $2',
'file-info-size'       => '$1×$2 px, $3, MIME-tyyppi: $4',
'file-nohires'         => '<small>Tarkempaa kuvaa ei ole saatavilla.</small>',
'svg-long-desc'        => 'SVG-tiedosto; oletustarkkuus $1×$2 kuvapistettä; tiedostokoko $3',
'show-big-image'       => 'Korkeatarkkuuksinen versio',
'show-big-image-thumb' => '<small>Esikatselun koko: $1×$2 px</small>',
'file-info-gif-looped' => 'toistuva',
'file-info-gif-frames' => '$1 {{PLURAL:$1|kehys|kehystä}}',
'file-info-png-looped' => 'toistuva',
'file-info-png-repeat' => 'toistettu $1 {{PLURAL:$1|kertaa|kertaa}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|kehys|kehystä}}',

# Special:NewFiles
'newimages'             => 'Uudet tiedostot',
'imagelisttext'         => 'Alla on {{PLURAL:$1|1 tiedosto|$1 tiedostoa}} lajiteltuna <strong>$2</strong>.',
'newimages-summary'     => 'Tällä toimintosivulla on viimeisimmät tallennetut tiedostot.',
'newimages-legend'      => 'Suodin',
'newimages-label'       => 'Tiedostonimi (tai osa siitä)',
'showhidebots'          => '($1 botit)',
'noimages'              => 'Ei uusia tiedostoja.',
'ilsubmit'              => 'Hae',
'bydate'                => 'päiväyksen mukaan',
'sp-newimages-showfrom' => 'Näytä uudet tiedostot alkaen $1 kello $2',

# Bad image list
'bad_image_list' => 'Listan muoto on seuraava:

Vain *-merkillä alkavat rivit otetaan huomioon.
Rivin ensimmäisen linkin on osoitettava tiedostoon.
Kaikki muut linkit ovat poikkeuksia eli toisin sanoen sivuja, joissa tiedostoa saa käyttää.',

# Metadata
'metadata'          => 'Sisältökuvaukset',
'metadata-help'     => 'Tämä tiedosto sisältää esimerkiksi kuvanlukijan, digikameran tai kuvankäsittelyohjelman lisäämiä lisätietoja. Kaikki tiedot eivät enää välttämättä vastaa todellisuutta, jos kuvaa on muokattu sen alkuperäisen luonnin jälkeen.',
'metadata-expand'   => 'Näytä kaikki sisältökuvaukset',
'metadata-collapse' => 'Näytä vain tärkeimmät sisältökuvaukset',
'metadata-fields'   => 'Seuraavat kentät ovat esillä kuvasivulla, kun sisältötietotaulukko on pienennettynä.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Leveys',
'exif-imagelength'                 => 'Korkeus',
'exif-bitspersample'               => 'Bittiä komponentissa',
'exif-compression'                 => 'Pakkaustapa',
'exif-photometricinterpretation'   => 'Kuvapisteen koostumus',
'exif-orientation'                 => 'Suunta',
'exif-samplesperpixel'             => 'Komponenttien lukumäärä',
'exif-planarconfiguration'         => 'Tiedon järjestely',
'exif-ycbcrsubsampling'            => 'Y:n ja C:n alinäytteistyssuhde',
'exif-ycbcrpositioning'            => 'Y:n ja C:n asemointi',
'exif-xresolution'                 => 'Kuvan resoluutio leveyssuunnassa',
'exif-yresolution'                 => 'Kuvan resoluutio korkeussuunnassa',
'exif-resolutionunit'              => 'Resoluution yksikkö X- ja Y-suunnassa',
'exif-stripoffsets'                => 'Kuvatiedon sijainti',
'exif-rowsperstrip'                => 'Kaistan rivien lukumäärä',
'exif-stripbytecounts'             => 'Tavua pakatussa kaistassa',
'exif-jpeginterchangeformat'       => 'Etäisyys JPEG SOI:hin',
'exif-jpeginterchangeformatlength' => 'JPEG-tiedon tavujen lukumäärä',
'exif-transferfunction'            => 'Siirtofunktio',
'exif-whitepoint'                  => 'Valkoisen pisteen väriarvot',
'exif-primarychromaticities'       => 'Päävärien väriarvot',
'exif-ycbcrcoefficients'           => 'Väriavaruuden muuntomatriisin kertoimet',
'exif-referenceblackwhite'         => 'Musta-valkoparin vertailuarvot',
'exif-datetime'                    => 'Viimeksi muokattu',
'exif-imagedescription'            => 'Kuvan nimi',
'exif-make'                        => 'Kameran valmistaja',
'exif-model'                       => 'Kameran malli',
'exif-software'                    => 'Käytetty ohjelmisto',
'exif-artist'                      => 'Tekijä',
'exif-copyright'                   => 'Tekijänoikeuden omistaja',
'exif-exifversion'                 => 'Exif-versio',
'exif-flashpixversion'             => 'Tuettu Flashpix-versio',
'exif-colorspace'                  => 'Väriavaruus',
'exif-componentsconfiguration'     => 'Kunkin komponentin määritelmä',
'exif-compressedbitsperpixel'      => 'Kuvan pakkaustapa',
'exif-pixelydimension'             => 'Käyttökelpoinen kuvan leveys',
'exif-pixelxdimension'             => 'Käyttökelpoinen kuvan korkeus',
'exif-makernote'                   => 'Valmistajan merkinnät',
'exif-usercomment'                 => 'Käyttäjän kommentit',
'exif-relatedsoundfile'            => 'Liitetty äänitiedosto',
'exif-datetimeoriginal'            => 'Luontipäivämäärä',
'exif-datetimedigitized'           => 'Digitointipäivämäärä',
'exif-subsectime'                  => 'Aikaleiman sekunninosat',
'exif-subsectimeoriginal'          => 'Luontiaikaleiman sekunninosat',
'exif-subsectimedigitized'         => 'Digitointiaikaleiman sekunninosat',
'exif-exposuretime'                => 'Valotusaika',
'exif-exposuretime-format'         => '$1 s ($2)',
'exif-fnumber'                     => 'Aukkosuhde',
'exif-exposureprogram'             => 'Valotusohjelma',
'exif-spectralsensitivity'         => 'Värikirjoherkkyys',
'exif-isospeedratings'             => 'Herkkyys (ISO)',
'exif-oecf'                        => 'Optoelektroninen muuntokerroin',
'exif-shutterspeedvalue'           => 'Suljinaika',
'exif-aperturevalue'               => 'Aukko',
'exif-brightnessvalue'             => 'Kirkkaus',
'exif-exposurebiasvalue'           => 'Valotuksen korjaus',
'exif-maxaperturevalue'            => 'Suurin aukko',
'exif-subjectdistance'             => 'Kohteen etäisyys',
'exif-meteringmode'                => 'Mittaustapa',
'exif-lightsource'                 => 'Valolähde',
'exif-flash'                       => 'Salama',
'exif-focallength'                 => 'Linssin polttoväli',
'exif-subjectarea'                 => 'Kohteen ala',
'exif-flashenergy'                 => 'Salaman teho',
'exif-spatialfrequencyresponse'    => 'Tilataajuusvaste',
'exif-focalplanexresolution'       => 'Tarkennustason X-resoluutio',
'exif-focalplaneyresolution'       => 'Tarkennustason Y-resoluutio',
'exif-focalplaneresolutionunit'    => 'Tarkennustason resoluution yksikkö',
'exif-subjectlocation'             => 'Kohteen sijainti',
'exif-exposureindex'               => 'Valotusindeksi',
'exif-sensingmethod'               => 'Mittausmenetelmä',
'exif-filesource'                  => 'Tiedostolähde',
'exif-scenetype'                   => 'Kuvatyyppi',
'exif-cfapattern'                  => 'CFA-kuvio',
'exif-customrendered'              => 'Muokattu kuvankäsittely',
'exif-exposuremode'                => 'Valotustapa',
'exif-whitebalance'                => 'Valkotasapaino',
'exif-digitalzoomratio'            => 'Digitaalinen suurennoskerroin',
'exif-focallengthin35mmfilm'       => '35 mm:n filmiä vastaava polttoväli',
'exif-scenecapturetype'            => 'Kuvan kaappaustapa',
'exif-gaincontrol'                 => 'Kuvasäätö',
'exif-contrast'                    => 'Kontrasti',
'exif-saturation'                  => 'Värikylläisyys',
'exif-sharpness'                   => 'Terävyys',
'exif-devicesettingdescription'    => 'Laitteen asetuskuvaus',
'exif-subjectdistancerange'        => 'Kohteen etäisyysväli',
'exif-imageuniqueid'               => 'Kuvan yksilöivä tunniste',
'exif-gpsversionid'                => 'GPS-muotoilukoodin versio',
'exif-gpslatituderef'              => 'Pohjoinen tai eteläinen leveysaste',
'exif-gpslatitude'                 => 'Leveysaste',
'exif-gpslongituderef'             => 'Itäinen tai läntinen pituusaste',
'exif-gpslongitude'                => 'Pituusaste',
'exif-gpsaltituderef'              => 'Korkeuden vertailukohta',
'exif-gpsaltitude'                 => 'Korkeus',
'exif-gpstimestamp'                => 'GPS-aika (atomikello)',
'exif-gpssatellites'               => 'Mittaukseen käytetyt satelliitit',
'exif-gpsstatus'                   => 'Vastaanottimen tila',
'exif-gpsmeasuremode'              => 'Mittaustila',
'exif-gpsdop'                      => 'Mittatarkkuus',
'exif-gpsspeedref'                 => 'Nopeuden yksikkö',
'exif-gpsspeed'                    => 'GPS-vastaanottimen nopeus',
'exif-gpstrackref'                 => 'Liikesuunnan vertailukohta',
'exif-gpstrack'                    => 'Liikesuunta',
'exif-gpsimgdirectionref'          => 'Kuvan suunnan vertailukohta',
'exif-gpsimgdirection'             => 'Kuvan suunta',
'exif-gpsmapdatum'                 => 'Käytetty geodeettinen maanmittaustieto',
'exif-gpsdestlatituderef'          => 'Loppupisteen leveysasteen vertailukohta',
'exif-gpsdestlatitude'             => 'Loppupisteen leveysaste',
'exif-gpsdestlongituderef'         => 'Loppupisteen pituusasteen vertailukohta',
'exif-gpsdestlongitude'            => 'Loppupisteen pituusaste',
'exif-gpsdestbearingref'           => 'Loppupisteen suuntiman vertailukohta',
'exif-gpsdestbearing'              => 'Loppupisteen suuntima',
'exif-gpsdestdistanceref'          => 'Loppupisteen etäisyyden vertailukohta',
'exif-gpsdestdistance'             => 'Loppupisteen etäisyys',
'exif-gpsprocessingmethod'         => 'GPS-käsittelymenetelmän nimi',
'exif-gpsareainformation'          => 'GPS-alueen nimi',
'exif-gpsdatestamp'                => 'GPS-päivämäärä',
'exif-gpsdifferential'             => 'GPS-differentiaalikorjaus',
'exif-objectname'                  => 'Lyhyt otsikko',

# EXIF attributes
'exif-compression-1' => 'Pakkaamaton',

'exif-unknowndate' => 'Tuntematon päiväys',

'exif-orientation-1' => 'Normaali',
'exif-orientation-2' => 'Käännetty vaakasuunnassa',
'exif-orientation-3' => 'Käännetty 180°',
'exif-orientation-4' => 'Käännetty pystysuunnassa',
'exif-orientation-5' => 'Käännetty 90° vastapäivään ja pystysuunnassa',
'exif-orientation-6' => 'Käännetty 90° myötäpäivään',
'exif-orientation-7' => 'Käännetty 90° myötäpäivään ja pystysuunnassa',
'exif-orientation-8' => 'Käännetty 90° vastapäivään',

'exif-planarconfiguration-1' => 'kokkaremuoto',
'exif-planarconfiguration-2' => 'litteämuoto',

'exif-componentsconfiguration-0' => 'ei ole',

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

'exif-meteringmode-0'   => 'Tuntematon',
'exif-meteringmode-1'   => 'Keskiarvo',
'exif-meteringmode-2'   => 'Keskustapainotteinen keskiarvo',
'exif-meteringmode-3'   => 'Piste',
'exif-meteringmode-4'   => 'Monipiste',
'exif-meteringmode-5'   => 'Kuvio',
'exif-meteringmode-6'   => 'Osittainen',
'exif-meteringmode-255' => 'Muu',

'exif-lightsource-0'   => 'Tuntematon',
'exif-lightsource-1'   => 'Päivänvalo',
'exif-lightsource-2'   => 'Loisteputki',
'exif-lightsource-3'   => 'Hehkulamppu (keinovalo)',
'exif-lightsource-4'   => 'Salama',
'exif-lightsource-9'   => 'Hyvä sää',
'exif-lightsource-10'  => 'Pilvinen sää',
'exif-lightsource-11'  => 'Varjoinen',
'exif-lightsource-12'  => 'Päivänvaloloisteputki (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Päivänvalkoinen loisteputki (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Kylmä valkoinen loisteputki (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Valkoinen loisteputki (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Oletusvalo A',
'exif-lightsource-18'  => 'Oletusvalo B',
'exif-lightsource-19'  => 'Oletusvalo C',
'exif-lightsource-24'  => 'ISO-studiohehkulamppu',
'exif-lightsource-255' => 'Muu valonlähde',

# Flash modes
'exif-flash-fired-0'    => 'Salama ei lauennut',
'exif-flash-fired-1'    => 'Salama lauennut',
'exif-flash-return-0'   => 'ei pulssivalon tunnistustoimintoa',
'exif-flash-return-2'   => 'pulssivalon paluuta ei havaittu',
'exif-flash-return-3'   => 'pulssivalon paluu havaittu',
'exif-flash-mode-1'     => 'salamavalo käytössä',
'exif-flash-mode-2'     => 'salamavalo estetty',
'exif-flash-mode-3'     => 'automaattitila',
'exif-flash-function-1' => 'Ei salamatoimintoa',
'exif-flash-redeye-1'   => 'punasilmäisyyden vähennystila',

'exif-focalplaneresolutionunit-2' => 'tuumaa',

'exif-sensingmethod-1' => 'Määrittelemätön',
'exif-sensingmethod-2' => 'Yksisiruinen värikenno',
'exif-sensingmethod-3' => 'Kaksisiruinen värikenno',
'exif-sensingmethod-4' => 'Kolmisiruinen värikenno',
'exif-sensingmethod-5' => 'Sarjavärikenno',
'exif-sensingmethod-7' => 'Trilineaarikenno',
'exif-sensingmethod-8' => 'Sarjalineaarivärikenno',

'exif-scenetype-1' => 'Suoraan valokuvattu kuva',

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

'exif-gaincontrol-0' => 'Ei ole',
'exif-gaincontrol-1' => 'Matala ylävahvistus',
'exif-gaincontrol-2' => 'Korkea ylävahvistus',
'exif-gaincontrol-3' => 'Matala alavahvistus',
'exif-gaincontrol-4' => 'Korkea alavahvistus',

'exif-contrast-0' => 'Normaali',
'exif-contrast-1' => 'Pehmeä',
'exif-contrast-2' => 'Kova',

'exif-saturation-0' => 'Normaali',
'exif-saturation-1' => 'Alhainen värikylläisyys',
'exif-saturation-2' => 'Korkea värikylläisyys',

'exif-sharpness-0' => 'Normaali',
'exif-sharpness-1' => 'Pehmeä',
'exif-sharpness-2' => 'Kova',

'exif-subjectdistancerange-0' => 'Tuntematon',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Lähikuva',
'exif-subjectdistancerange-3' => 'Kaukokuva',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Pohjoista leveyttä',
'exif-gpslatitude-s' => 'Eteläistä leveyttä',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Itäistä pituutta',
'exif-gpslongitude-w' => 'Läntistä pituutta',

'exif-gpsstatus-a' => 'Mittaus käynnissä',
'exif-gpsstatus-v' => 'Ristiinmittaus',

'exif-gpsmeasuremode-2' => '2-ulotteinen mittaus',
'exif-gpsmeasuremode-3' => '3-ulotteinen mittaus',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mailia tunnissa',
'exif-gpsspeed-n' => 'solmua',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Todellinen suunta',
'exif-gpsdirection-m' => 'Magneettinen suunta',

# External editor support
'edit-externally'      => 'Muokkaa tätä tiedostoa ulkoisessa sovelluksessa',
'edit-externally-help' => '(Katso [http://www.mediawiki.org/wiki/Manual:External_editors ohjeet], jos haluat lisätietoja.)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'kaikki',
'imagelistall'     => 'kaikki',
'watchlistall2'    => ', koko historia',
'namespacesall'    => 'kaikki',
'monthsall'        => 'kaikki',
'limitall'         => 'kaikki',

# E-mail address confirmation
'confirmemail'              => 'Varmenna sähköpostiosoite',
'confirmemail_noemail'      => 'Sinulla ei ole kelvollista sähköpostiosoitetta [[Special:Preferences|asetuksissasi]].',
'confirmemail_text'         => 'Tämä wiki vaatii sähköpostiosoitteen varmentamisen, ennen kuin voit käyttää sähköpostitoimintoja. Lähetä alla olevasta painikkeesta varmennusviesti osoitteeseesi. Viesti sisältää linkin, jonka avaamalla varmennat sähköpostiosoitteesi.',
'confirmemail_pending'      => 'Varmennusviesti on jo lähetetty. Jos loit tunnuksen äskettäin, odota muutama minuutti viestin saapumista, ennen kuin yrität uudelleen.',
'confirmemail_send'         => 'Lähetä varmennusviesti',
'confirmemail_sent'         => 'Varmennusviesti lähetetty.',
'confirmemail_oncreate'     => 'Varmennusviesti lähetettiin sähköpostiosoitteeseesi. Varmennuskoodia ei tarvita sisäänkirjautumiseen, mutta se täytyy antaa, ennen kuin voit käyttää sähköpostitoimintoja tässä wikissä.',
'confirmemail_sendfailed'   => 'Varmennusviestin lähettäminen epäonnistui. Tarkista, onko osoitteessa kiellettyjä merkkejä.

Postitusohjelma palautti: $1',
'confirmemail_invalid'      => 'Varmennuskoodi ei kelpaa. Koodi on voinut vanhentua.',
'confirmemail_needlogin'    => 'Sinun täytyy $1, jotta voisit varmistaa sähköpostiosoitteesi.',
'confirmemail_success'      => 'Sähköpostiosoitteesi on nyt varmennettu. Voit kirjautua sisään.',
'confirmemail_loggedin'     => 'Sähköpostiosoitteesi on nyt varmennettu.',
'confirmemail_error'        => 'Jokin epäonnistui varmennuksen tallentamisessa.',
'confirmemail_subject'      => '{{GRAMMAR:genitive|{{SITENAME}}}} sähköpostiosoitteen varmennus',
'confirmemail_body'         => 'Joku IP-osoitteesta $1 on rekisteröinyt {{GRAMMAR:inessive|{{SITENAME}}}} tunnuksen $2 tällä sähköpostiosoitteella.

Varmenna, että tämä tunnus kuuluu sinulle avaamalla seuraava linkki selaimellasi:

$3

Jos et ole rekisteröinyt tätä tunnusta, peruuta sähköpostiosoitteen varmennus avaamalla seuraava linkki:

$5

Varmennuskoodi vanhenee $4.',
'confirmemail_body_changed' => 'Joku IP-osoitteesta $1 on vaihtanut {{GRAMMAR:inessive|{{SITENAME}}}} tunnuksen $2 sähköpostiosoitteeksi tämän osoitteen.

Varmenna, että tämä tunnus kuuluu sinulle ja uudelleenaktivoi sähköpostitoiminnot avaamalla seuraava linkki selaimellasi:

$3

Jos tunnus ei kuulu sinulle, peruuta sähköpostiosoitteen varmennus avaamalla seuraava linkki:

$5

Varmennuskoodi vanhenee $4.',
'confirmemail_body_set'     => 'Joku, todennäköisesti sinä, IP-osoitteesta $1 on vaihtanut {{GRAMMAR:inessive|{{SITENAME}}}} tunnuksen $2 sähköpostiosoitteeksi tämän osoitteen.

Varmenna, että tämä tunnus kuuluu sinulle ja aktivoi sähköpostitoiminnot uudelleen avaamalla seuraava linkki selaimellasi:

$3

Jos tunnus ei kuulu sinulle, peruuta sähköpostiosoitteen varmennus avaamalla seuraava linkki:

$5

Varmennuskoodi vanhenee $4.',
'confirmemail_invalidated'  => 'Sähköpostiosoitteen varmennus peruutettiin',
'invalidateemail'           => 'Sähköpostiosoitteen varmennuksen peruuttaminen',

# Scary transclusion
'scarytranscludedisabled' => '[Wikienvälinen sisällytys ei ole käytössä]',
'scarytranscludefailed'   => '[Mallineen hakeminen epäonnistui: $1]',
'scarytranscludetoolong'  => '[Verkko-osoite on liian pitkä]',

# Trackbacks
'trackbackbox'      => 'Sivun trackbackit:<br />
$1',
'trackbackremove'   => '([$1 poista])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback poistettiin.',

# Delete conflict
'deletedwhileediting' => "'''Varoitus''': Tämä sivu on poistettu sen jälkeen, kun aloitit sen muokkaamisen!",
'confirmrecreate'     => "Käyttäjä '''[[User:$1|$1]]''' ([[User talk:$1|keskustelu]]) on poistanut sivun sen jälkeen, kun aloit muokata sitä. Syy oli:
: ''$2''
Varmista, että haluat luoda sivun uudelleen.",
'recreate'            => 'Luo uudelleen',

'unit-pixel' => ' px',

# action=purge
'confirm_purge_button' => 'Poista',
'confirm-purge-top'    => 'Poistetaanko tämän sivun välimuistikopiot?',
'confirm-purge-bottom' => 'Välimuistikopioiden poistaminen tyhjentää välimuistin ja pakottaa sivun uusimman version näkyviin.',

# Separators for various lists, etc.
'percent' => '$1&nbsp;%',

# Multipage image navigation
'imgmultipageprev' => '← edellinen sivu',
'imgmultipagenext' => 'seuraava sivu →',
'imgmultigo'       => 'Siirry',
'imgmultigoto'     => 'Sivu $1',

# Table pager
'ascending_abbrev'         => 'nouseva',
'descending_abbrev'        => 'laskeva',
'table_pager_next'         => 'Seuraava sivu',
'table_pager_prev'         => 'Edellinen sivu',
'table_pager_first'        => 'Ensimmäinen sivu',
'table_pager_last'         => 'Viimeinen sivu',
'table_pager_limit'        => 'Näytä $1 nimikettä sivulla',
'table_pager_limit_label'  => 'Kohteita sivua kohden:',
'table_pager_limit_submit' => 'Hae',
'table_pager_empty'        => 'Ei tuloksia',

# Auto-summaries
'autosumm-blank'   => 'Ak: Sivu tyhjennettiin',
'autosumm-replace' => 'Ak: Sivun sisältö korvattiin sisällöllä ”$1”',
'autoredircomment' => 'Ak: Ohjaus sivulle [[$1]]',
'autosumm-new'     => 'Ak: Uusi sivu: $1',

# Size units
'size-kilobytes' => '$1 KiB',
'size-megabytes' => '$1 MiB',
'size-gigabytes' => '$1 GiB',

# Live preview
'livepreview-loading' => 'Ladataan…',
'livepreview-ready'   => 'Ladataan… Valmis!',
'livepreview-failed'  => 'Pikaesikatselu epäonnistui!
Yritä normaalia esikatselua.',
'livepreview-error'   => 'Yhdistäminen epäonnistui: $1 ”$2”
Yritä normaalia esikatselua.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Muutokset, jotka ovat uudempia kuin $1 {{PLURAL:$1|sekunti|sekuntia}}, eivät välttämättä näy tällä sivulla.',
'lag-warn-high'   => 'Tietokannoilla on työjonoa. Muutokset, jotka ovat uudempia kuin $1 {{PLURAL:$1|sekunti|sekuntia}}, eivät välttämättä näy tällä sivulla.',

# Watchlist editor
'watchlistedit-numitems'       => 'Tarkkailulistallasi on {{PLURAL:$1|yksi sivu|$1 sivua}} keskustelusivuja lukuun ottamatta.',
'watchlistedit-noitems'        => 'Tarkkailulistasi on tyhjä.',
'watchlistedit-normal-title'   => 'Tarkkailulistan muokkaus',
'watchlistedit-normal-legend'  => 'Sivut',
'watchlistedit-normal-explain' => 'Tarkkailulistasi sivut on lueteltu alla. Voit poistaa sivuja valitsemalla niitä vastaavat valintaruudut ja napsauttamalla ”{{int:Watchlistedit-normal-submit}}”. Voit myös muokata listaa [[Special:Watchlist/raw|tekstimuodossa]].',
'watchlistedit-normal-submit'  => 'Poista',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Yksi sivu|$1 sivua}} poistettiin tarkkailulistaltasi:',
'watchlistedit-raw-title'      => 'Tarkkailulistan muokkaus',
'watchlistedit-raw-legend'     => 'Tarkkailulistan muokkaus',
'watchlistedit-raw-explain'    => 'Tarkkailulistasi sivut on lueteltu alla jokainen omalla rivillään. Voit muokata listaa lisäämällä ja poistamalla rivejä.
Kun olet valmis, napsauta ”{{int:Watchlistedit-raw-submit}}”.
Voit myös muokata listaa [[Special:Watchlist/edit|tavalliseen tapaan]].',
'watchlistedit-raw-titles'     => 'Sivut',
'watchlistedit-raw-submit'     => 'Päivitä tarkkailulista',
'watchlistedit-raw-done'       => 'Tarkkailulistasi on päivitetty.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Yksi sivu|$1 sivua}} lisättiin:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Yksi sivu|$1 sivua}} poistettiin:',

# Watchlist editing tools
'watchlisttools-view' => 'Näytä muutokset',
'watchlisttools-edit' => 'Muokkaa listaa',
'watchlisttools-raw'  => 'Lista raakamuodossa',

# Core parser functions
'unknown_extension_tag' => 'Tuntematon laajennuskoodi ”$1”.',
'duplicate-defaultsort' => 'Varoitus: Oletuslajitteluavain ”$2” korvaa aiemman oletuslajitteluavaimen ”$1”.',

# Special:Version
'version'                          => 'Versio',
'version-extensions'               => 'Asennetut laajennukset',
'version-specialpages'             => 'Toimintosivut',
'version-parserhooks'              => 'Jäsenninkytkökset',
'version-variables'                => 'Muuttujat',
'version-skins'                    => 'Ulkoasut',
'version-other'                    => 'Muut',
'version-mediahandlers'            => 'Median käsittelijät',
'version-hooks'                    => 'Kytköspisteet',
'version-extension-functions'      => 'Laajennusfunktiot',
'version-parser-extensiontags'     => 'Jäsentimen laajennustagit',
'version-parser-function-hooks'    => 'Jäsentimen laajennusfunktiot',
'version-skin-extension-functions' => 'Ulkoasun laajennusfunktiot',
'version-hook-name'                => 'Kytköspisteen nimi',
'version-hook-subscribedby'        => 'Kytkökset',
'version-version'                  => '(Versio $1)',
'version-license'                  => 'Lisenssi',
'version-poweredby-credits'        => "Tämä wiki käyttää '''[http://www.mediawiki.org/ MediaWikiä]'''. Copyright © 2001–$1 $2.",
'version-poweredby-others'         => 'muut',
'version-license-info'             => 'MediaWiki on vapaa ohjelmisto – voit levittää sitä ja/tai muokata sitä Free Software Foundationin GNU General Public Licensen ehdoilla, joko version 2 tai halutessasi mikä tahansa myöhemmän version mukaisesti.

MediaWikiä levitetään siinä toivossa, että se olisi hyödyllinen, mutta ilman mitään takuuta; ilman edes hiljaista takuuta kaupallisesti hyväksyttävästä laadusta tai soveltuvuudesta tiettyyn tarkoitukseen. Katso GPL-lisenssistä lisää yksityiskohtia.

Sinun olisi pitänyt saada [{{SERVER}}{{SCRIPTPATH}}/COPYING kopio GNU General Public Licensestä] tämän ohjelman mukana. Jos et saanut kopiota, kirjoita siitä osoitteeseen Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA tai [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html lue se Internetissä].',
'version-software'                 => 'Asennettu ohjelmisto',
'version-software-product'         => 'Tuote',
'version-software-version'         => 'Versio',

# Special:FilePath
'filepath'         => 'Tiedoston osoite',
'filepath-page'    => 'Tiedosto',
'filepath-submit'  => 'Siirry',
'filepath-summary' => 'Tämä toimintosivu palauttaa tiedoston URL-osoitteen. Anna tiedoston nimi ilman {{ns:file}}-nimiavaruusliitettä.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Kaksoiskappaleiden haku',
'fileduplicatesearch-summary'  => 'Etsii tiedoston kaksoiskappaleita hajautusarvon perusteella.

Kirjoita tiedostonimi ilman ”{{ns:file}}:”-etuliitettä.',
'fileduplicatesearch-legend'   => 'Etsi kaksoiskappaleita',
'fileduplicatesearch-filename' => 'Tiedostonimi',
'fileduplicatesearch-submit'   => 'Etsi',
'fileduplicatesearch-info'     => '$1×$2 kuvapistettä<br />Tiedostokoko: $3<br />MIME-tyyppi: $4',
'fileduplicatesearch-result-1' => 'Tiedostolla ”$1” ei ole identtisiä kaksoiskappaleita.',
'fileduplicatesearch-result-n' => 'Tiedostolla ”$1” on {{PLURAL:$2|yksi identtinen kaksoiskappale|$2 identtistä kaksoiskappaletta}}.',

# Special:SpecialPages
'specialpages'                   => 'Toimintosivut',
'specialpages-note'              => '----
* Normaalit toimintosivut.
* <strong class="mw-specialpagerestricted">Rajoitetut toimintosivut.</strong>',
'specialpages-group-maintenance' => 'Ylläpito',
'specialpages-group-other'       => 'Muut',
'specialpages-group-login'       => 'Kirjautuminen ja tunnusten luonti',
'specialpages-group-changes'     => 'Muutokset ja lokit',
'specialpages-group-media'       => 'Media',
'specialpages-group-users'       => 'Käyttäjät',
'specialpages-group-highuse'     => 'Sivujen käyttöaste',
'specialpages-group-pages'       => 'Sivulistaukset',
'specialpages-group-pagetools'   => 'Sivutyökalut',
'specialpages-group-wiki'        => 'Wikitiedot ja työkalut',
'specialpages-group-redirects'   => 'Ohjaavat toimintosivut',
'specialpages-group-spam'        => 'Mainostenpoistotyökalut',

# Special:BlankPage
'blankpage'              => 'Tyhjä sivu',
'intentionallyblankpage' => 'Tämä sivu on tarkoituksellisesti tyhjä.',

# External image whitelist
'external_image_whitelist' => ' #Älä muuta tätä riviä lainkaan.<pre>
#Laita säännöllisten lausekkeiden palaset (vain osa, joka menee //-merkkien väliin) alle
#Niitä verrataan ulkoisten (suoralinkitettyjen) kuvien URLeihin
#Ne jotka sopivat, näytetään kuvina, muutoin kuviin näytetään vain linkit
#Rivit, jotka alkavat #-merkillä ovat kommentteja
#Tämä on riippumaton kirjainkoosta

#Laita kaikki säännöllisten lausekkeiden palaset tämän rivit yläpuolelle. Älä muuta tätä riviä lainkaan</pre>',

# Special:Tags
'tags'                    => 'Voimassa olevat muutosmerkinnät',
'tag-filter'              => '[[Special:Tags|Merkintäsuodatin]]',
'tag-filter-submit'       => 'Suodata',
'tags-title'              => 'Merkinnät',
'tags-intro'              => 'Tämä sivu luetteloi merkinnät, joilla ohjelmisto voi merkitä muokkauksia, ja niiden tarkoitukset.',
'tags-tag'                => 'Merkintänimi',
'tags-display-header'     => 'Näkyvyys muutosluetteloissa',
'tags-description-header' => 'Täysi kuvaus tarkoituksesta',
'tags-hitcount-header'    => 'Merkityt muutokset',
'tags-edit'               => 'muokkaa',
'tags-hitcount'           => '$1 {{PLURAL:$1|muutos|muutosta}}',

# Special:ComparePages
'comparepages'     => 'Vertaile sivuja',
'compare-selector' => 'Vertaile sivuversioita',
'compare-page1'    => 'Sivu 1',
'compare-page2'    => 'Sivu 2',
'compare-rev1'     => 'Versio 1',
'compare-rev2'     => 'Versio 2',
'compare-submit'   => 'Vertaile',

# Database error messages
'dberr-header'      => 'Wikissä on tietokantaongelma',
'dberr-problems'    => 'Tällä sivustolla on teknisiä ongelmia.',
'dberr-again'       => 'Odota hetki ja lataa sivu uudelleen.',
'dberr-info'        => '(Tietokantapalvelimeen yhdistäminen epäonnistui: $1)',
'dberr-usegoogle'   => 'Voit koettaa hakea Googlesta, kunnes virhe korjataan.',
'dberr-outofdate'   => 'Googlen indeksi ei välttämättä ole ajan tasalla.',
'dberr-cachederror' => 'Alla on välimuistissa oleva sivun versio, joka ei välttämättä ole ajan tasalla.',

# HTML forms
'htmlform-invalid-input'       => 'Antamassasi syötteessä on ongelmia',
'htmlform-select-badoption'    => 'Antamasi arvo ei ole kelvollinen.',
'htmlform-int-invalid'         => 'Antamasi arvo ei ole kokonaisluku.',
'htmlform-float-invalid'       => 'Antamasi arvo ei ole numero.',
'htmlform-int-toolow'          => 'Annettu arvo on pienempi kuin alaraja $1',
'htmlform-int-toohigh'         => 'Annettu arvo on suurempi kuin yläraja $1',
'htmlform-required'            => 'Tämä arvo on vaadittu',
'htmlform-submit'              => 'Lähetä',
'htmlform-reset'               => 'Kumoa muutokset',
'htmlform-selectorother-other' => 'Muu',

# SQLite database support
'sqlite-has-fts' => '$1, jossa on tuki kokotekstihaulle',
'sqlite-no-fts'  => '$1, jossa ei ole tukea kokotekstihaulle',

);
