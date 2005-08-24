<?php
/** Finnish (Suomi)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

# Revised 2005-08-25 for MediaWiki 1.6alpha -- Nikerabbit

/* private */ $wgNamespaceNamesFi = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Toiminnot',
	NS_MAIN             => '',
	NS_TALK             => 'Keskustelu',
	NS_USER             => 'Käyttäjä',
	NS_USER_TALK        => 'Keskustelu_käyttäjästä',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => FALSE,  # Set in constructor
	NS_IMAGE            => 'Kuva',
	NS_IMAGE_TALK       => 'Keskustelu_kuvasta',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Malline',
	NS_TEMPLATE_TALK    => 'Keskustelu_mallineesta',
	NS_HELP             => 'Ohje',
	NS_HELP_TALK        => 'Keskustelu_ohjeesta',
	NS_CATEGORY         => 'Luokka',
	NS_CATEGORY_TALK    => 'Keskustelu_luokasta'

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFi = array(
	'Ei mitään', 'Tekstin mukana, vasen', 'Tekstin mukana, oikea', 'Pysyen vasemmalla'
);

/* private */ $wgSkinNamesFi = array(
	'standard'          => 'Perus',
	'cologneblue'       => 'Kölnin sininen',
	'myskin'            => 'Oma tyylisivu'
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsFi = array(
	'Ei valintaa',
	'15. tammikuuta 2001 kello 16.12',
	'15. tammikuuta 2001 kello 16:12:34',
	'15.1.2001 16.12',
	'ISO 8601' => '2001-01-15 16:12:34'
);

/* private */ $wgBookstoreListFi = array(
	'Akateeminen kirjakauppa'       => 'http://www.akateeminen.com/search/tuotetieto.asp?tuotenro=$1',
	'Bookplus'                      => 'http://www.bookplus.fi/product.php?isbn=$1',
	'Helsingin yliopiston kirjasto' => 'http://pandora.lib.hel.fi/cgi-bin/mhask/monihask.py?volname=&author=&keyword=&ident=$1&submit=Hae&engine_helka=ON',
	'Pääkaupunkiseudun kirjastot'   => 'http://www.helmet.fi/search*fin/i?SEARCH=$1',
	'Tampereen seudun kirjastot'    => 'http://pandora.lib.hel.fi/cgi-bin/mhask/monihask.py?volname=&author=&keyword=&ident=$1-1&lang=kaikki&mat_type=kaikki&submit=Hae&engine_tampere=ON'
) + $wgBookstoreListEn;

# Current practices (may be changed if not good ones)
# Refer namespaces with the English name or 'Project' in case of project namespace
# Avoid any hard coded references to any particular subject which may not apply everywhere, e.g. artikkeli, wikipedia
# Don't use participial phrases (lauseenkastikkeita) incorrectly
# Avoid unnecessary parenthesis, quotes and html code
#

#-------------------------------------------------------------------
# Translated messages
#-------------------------------------------------------------------

/* private */ $wgAllMessagesFi = array(

# User preference toggles
'tog-underline'       => 'Alleviivaa linkit',
'tog-highlightbroken' => 'Näytä linkit puuttuville sivuille <a href="" class="new">näin</a> (vaihtoehtoisesti näin: <a href="" class="internal">?</a>).',
'tog-justify'         => 'Tasaa kappaleet',
'tog-hideminor'       => 'Piilota pienet muutokset tuoreet muutokset -listasta',
'tog-usenewrc'        => 'Kehittynyt tuoreet muutokset -listaus (JavaScript)',
'tog-numberheadings'  => 'Numeroi otsikot',
'tog-showtoolbar'     => 'Näytä työkalupalkki',
'tog-editondblclick'  => 'Muokkaa sivuja kaksoisnapsautuksella (JavaScript)',
'tog-editsection'     => 'Näytä muokkauslinkit jokaisen osion yläpuolella',
'tog-editsectiononrightclick' => 'Muokkaa osioita napsauttamalla otsikkoa hiiren oikealla painikkeella (JavaScript)',
'tog-showtoc'         =>'Näytä sisällysluettelo sivuille, joilla yli 3 otsikkoa',
'tog-rememberpassword'=> 'Älä kysy salasanaa saman yhteyden eri istuntojen välillä',
'tog-editwidth'       => 'Muokkauskenttä on sivun levyinen',
'tog-watchdefault'    => 'Lisää oletuksena uudet ja muokatut sivut tarkkailulistalle',
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
'category'            => 'Luokka',
'category_header'     => 'Sivut, jotka ovat luokassa $1',
'subcategories'       => 'Alaluokat',
'linktrail'           => '/^((?:[a-z]|ä|ö|å)+)(.*)$/sD',
'mainpage'            => 'Etusivu',
'mainpagetext'        => 'Mediawiki on onnistuneesti asennettu.',
'mainpagedocfooter'   => 'Lisätietoja käytöstä ja asetusten tekoa varten on sivuilla [http://meta.wikipedia.org/wiki/MediaWiki_i18n documentation on customizing the interface] ja [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User\'s Guide].<br />Tarkista, että alla olevat taivutusmuodot ovat oikein. Jos eivät, tee tarvittavat muutokset LanguageFi.php:n <tt>convertGrammar</tt>-funktioon.<br />{{GRAMMAR:genitive|{{SITENAME}}}} (yön) — {{GRAMMAR:partitive|{{SITENAME}}}} (yötä) — {{GRAMMAR:elative|{{SITENAME}}}} (yöstä) — {{GRAMMAR:inessive|{{SITENAME}}}} (yössä) — {{GRAMMAR:illative|{{SITENAME}}}} (yöhön).',
'portal'              => 'Kahvihuone',
'portal-url'          => 'Project:Kahvihuone',
'about'               => 'Tietoja',
'aboutsite'           => 'Tietoja {{GRAMMAR:elative|{{SITENAME}}}}',
'aboutpage'           => 'Project:Tietoja',
'article'             => 'Sivu',
'help'                => 'Ohje',
'helppage'            => 'Help:Ohje',
'bugreports'          => 'Ongelmat ja parannusehdotukset',
'bugreportspage'      => 'Project:Ongelmat ja parannusehdotukset',
'sitesupport'         => 'Lahjoitukset', # Set a URL in $wgSiteSupportPage in LocalSettings.php
'sitesupport-url'     => 'Project:Lahjoitukset',

'faq'                 => 'VUKK',
'faqpage'             => 'Project:VUKK',
'edithelp'            => 'Muokkausohjeet',
'newwindow'           => '(avautuu uuteen ikkunaan)',
'edithelppage'        => 'Help:Kuinka_sivuja_muokataan', // TODO: no docs
'cancel'              => 'Keskeytä',
'qbfind'              => 'Etsi',
'qbbrowse'            => 'Selaa',
'qbedit'              => 'Muokkaa',
'qbpageoptions'       => 'Sivuasetukset',
'qbpageinfo'          => 'Sivun tiedot',
'qbmyoptions'         => 'Asetukset',
'qbspecialpages'      => 'Toimintosivut',
'moredotdotdot'       => 'Lisää...',
'mypage'              => 'Oma käyttäjäsivu',
'mytalk'              => 'Oma keskustelusivu',
'anontalk'            => 'Keskustele tämän IP:n kanssa',
'navigation'          => 'Valikko',
'currentevents'       => 'Ajankohtaista',
'currentevents-url'   => 'Project:Ajankohtaista',

'disclaimers'         => 'Vastuuvapaus',
'disclaimerpage'      => 'Project:Vastuuvapaus',
'errorpagetitle'      => 'Virhe',
'returnto'            => 'Palaa sivulle $1.',
'tagline'             => '{{SITENAME}}',
'whatlinkshere'       => 'Tänne viittaavat sivut',
'help'                => 'Ohje',
'search'              => 'Etsi',
'go'                  => 'Siirry',
'history'             => 'Historia',
'history_short'       => 'Historia',
'updatedmarker'       => 'päivitetty viimeisimmän käyntisi jälkeen',
'info_short'          => 'Tiedostus',
'printableversion'    => 'Tulostettava versio',
'permalink'           => 'Ikilinkki',
'edit'                => 'Muokkaa',
'editthispage'        => 'Muokkaa tätä sivua',
'delete'              => 'Poista',
'deletethispage'      => 'Poista tämä sivu',
'undelete_short'      => 'Palauta $1 muokkausta',
'undelete_short1'     => 'Palauta 1 muokkaus',
'protect'             => 'Suojaa',
'protectthispage'     => 'Suojaa tämä sivu',
'unprotect'           => 'Poista suojaus',
'unprotectthispage'   => 'Poista tämän sivun suojaus',
'newpage'             => 'Uusi sivu',
'talkpage'            => 'Keskustele tästä sivusta',
'specialpage'         => 'Toimintosivu',
'personaltools'       => 'Henkilökohtaiset työkalut',
'postcomment'         => 'Kommentti sivun loppuun',
'addsection'          => '+',
'articlepage'         => 'Näytä varsinainen sivu',
'subjectpage'         => 'Näytä aihe', # For compatibility
'talk'                => 'Keskustelu',
'toolbox'             => 'Työkalut',
'userpage'            => 'Näytä käyttäjän sivu',
'wikipediapage'       => 'Näytä erikoissivu',
'imagepage'           => 'Näytä kuvasivu',
'viewtalkpage'        => 'Näytä keskustelusivu',
'otherlanguages'      => 'Muut kielet',
'redirectedfrom'      => 'Uudelleenohjattu sivulta $1',
'lastmodified'        => 'Sivua on viimeksi muutettu  $1.',
'viewcount'           => 'Tämä sivu on näytetty $1 kertaa.',
'copyright'           => 'Sisältö on käytettävissä lisenssillä $1.',
'poweredby'           => '{{GRAMMAR:genitive|{{SITENAME}}}} tarjoaa [http://www.mediawiki.org/ MediaWiki], avoimen lähdekoodin ohjelmisto.',
'printsubtitle'       => '(Lähde: {{SERVER}})',
'protectedpage'       => 'Suojattu sivu',
'administrators'      => 'Project:Ylläpitäjät',
'sysoptitle'          => 'Vaatii ylläpitäjäoikeudet',
'sysoptext'           => 'Tämän toiminnon voi suorittaa vain käyttäjä, jolla on ylläpitäjäoikeudet. Katso $1.',
'developertitle'      => 'Ohjelmiston kehittäjän oikeuksia vaaditaan',
'developertext'       => 'Yrittämäsi toiminnon voi suorittaa vain henkilö, jolla on ohjelmistokehittäjänoikeudet. Katso $1.',

'badaccess'           => 'Lupa evätty',
'badaccesstext'       => 'Toiminto, jonka halusit suorittaa on rajoitettu käyttäjille, joilla on oikeus "$2". Katso $1.',

'versionrequired'     => 'Mediawikistä tarvitaan vähintään versio $1',
'versionrequiredtext' => 'Mediawikistä tarvitaan vähintään versio $1 tämän sivun käyttämiseen. Katso [[Special:Version|versio]]',

'nbytes'              => '$1 tavua',
'go'                  => 'Siirry',
'ok'                  => 'OK',
'sitetitle'           => '{{SITENAME}}',
'pagetitle'           => '$1 — {{SITENAME}}',
'sitesubtitle'        => '',
'retrievedfrom'       => 'Haettu osoitteesta $1',
'newmessages'         => 'Sinulle on $1',
'newmessageslink'     => 'uusia viestejä.',
'editsection'         => 'muokkaa',
'toc'                 => 'Sisällysluettelo',
'showtoc'             => 'näytä',
'hidetoc'             => 'piilota',
'thisisdeleted'       => 'Näytä tai palauta $1.',
'viewdeleted'         => 'Näytä $1?',
'restorelink1'        => 'yksi poistettu muokkaus.',
'restorelink'         => '$1 poistettua muokkausta',
'feedlinks'           => 'Uutissyötteet:',
'sitenotice'          => '', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'          => 'Sivu',
'nstab-user'          => 'Käyttäjäsivu',
'nstab-media'         => 'Media',
'nstab-special'       => 'Toiminto',
'nstab-wp'            => 'Projektisivu',
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
'dberrortext'         => 'Tietokantakyselyssä oli syntaksivirhe. Syynä saattaa olla virheellinen kysely, tai se saattaa johtua ohjelmointivirheestä. Viimeinen tietokantakysely, jota yritettiin, oli: <blockquote><tt>$1</tt></blockquote>. Se tehtiin funktiosta "<tt>$2</tt>". MySQL palautti virheen "<tt>$3: $4</tt>".',
'dberrortextcl'       => 'Tietokantakyselyssä oli syntaksivirhe. Viimeinen tietokantakysely, jota yritettiin, oli: "$1". Se tehtiin funktiosta "$2". MySQL palautti virheen "$3: $4".\n',
'noconnect'           => 'Tietokantaan ei saatu yhteyttä, ole hyvä ja yritä uudestaan.',
'nodb'                => 'Tietokantaa $1 ei voitu valita',
'cachederror'         => 'Pyydetystä sivusta näytettiin välimuistissa oleva kopio, ja se saattaa olla vanhentunut.',
'laggedslavemode'     => 'Varoitus: Sivu ei välttämättä sisällä viimeisimpiä muutoksia.',
'readonly'            => 'Tietokanta on lukittu',
'enterlockreason'     => 'Anna lukituksen syy sekä sen arvioitu poistamisaika',
'readonlytext'        => '{{GRAMMAR:genitive|{{SITENAME}}}} tietokanta on tällä hetkellä lukittu. Uusia sivuja ei voi luoda eikä muitakaan muutoksia tehdä. Syynä ovat todennäköisimmin rutiininomaiset tietokannan ylläpitotoimet. Tietokannan lukinneen ylläpitäjän selitys: <p>$1',
'missingarticle'      => 'Tietokannasta ei löytynyt sivua <b>$1</b>. Koita hetken päästä uudelleen. Jos ongelma ei katoa, ota yhteyttä ylläpitäjään ja anna mukaan tämän sivun URL-osoite.',
'readonly_lag'        => 'Tietokanta on automaattisesti lukittu, jotta kaikki tietokantapalvelimet saisivat kaikki tuoreet muutokset',
'internalerror'       => 'Sisäinen virhe',
'filecopyerror'       => 'Tiedostoa <b>$1</b> ei voitu kopioida tiedostoksi <b>$2</b>.',
'filerenameerror'     => 'Tiedostoa <b>$1</b> ei voitu nimetä uudelleen nimellä <b>$2</b>.',
'filedeleteerror'     => 'Tiedostoa <b>$1</b> ei voitu poistaa.',
'filenotfound'        => 'Tiedostoa <b>$1</b> ei löytynyt.',
'unexpected'          => 'Odottamaton arvo: "$1"="$2".',
'formerror'           => 'Lomakkeen tiedot eivät kelpaa',
'badarticleerror'     => 'Toimintoa ei voi suorittaa tälle sivulle.',
'cannotdelete'        => 'Sivun tai tiedoston poisto epäonnistui. Joku muu on saattanut poistaa sen.',
'badtitle'            => 'Virheellinen otsikko',
'badtitletext'        => 'Pyytämäsi sivuotsikko oli virheellinen, tyhjä tai väärin linkitetty kieltenvälinen tai wikienvälinen otsikko.',
'perfdisabled'        => 'Pahoittelut! Tämä ominaisuus ei toistaiseksi ole käytetössä, sillä se hidastaa tietokantaa niin paljon, että kukaan ei voi käyttää wikiä. Toiminto ohjelmoidaan tehokkaammaksi lähiaikoina. (Sinäkin voit tehdä sen! Tämä on vapaa ohjelmisto.)',
'perfdisabledsub'     => 'Tässä on tallennettu kopio $1', # obsolete? ei ole
'perfcached'          => 'Seuraava data on tuotu välimuistista, eikä se ole välttämättä ajan tasalla.',
'wrong_wfQuery_params'=> 'Virheelliset parametrit wfQuery()<br />Funktio: $1<br />Tiedustelu: $2',
'viewsource'          => 'Lähdekoodi',
'protectedtext'       => '<big>\'\'\'Tämä sivu on suojattu muutoksilta\'\'\'</big>
* [[Talk:{{PAGENAME}}|Keskustele tästä sivusta]] muiden kanssa
----
Sivun lähdekoodi:',
'sqlhidden'           => '(SQL-kysely piilotettu)',

# Login and logout pages
#
'logouttitle'         => 'Uloskirjautuminen',
'logouttext'          => 'Olet nyt kirjautunut ulos {{GRAMMAR:elative|{{SITENAME}}}}. Voit jatkaa {{GRAMMAR:genitive|{{SITENAME}}}} käyttöä nimettömänä, tai kirjautua uudelleen sisään.',
'welcomecreation'     => '== Tervetuloa, $1! == <p>Käyttäjätunnuksesi on luotu. Älä unohda virittää [[Special:Preferences|{{GRAMMAR:genitive|{{SITENAME}}]] asetuksiasi]].</p>',
'loginpagetitle'      => 'Sisäänkirjautuminen',
'yourname'            => 'Käyttäjätunnus',
'yourpassword'        => 'Salasana',
'yourpasswordagain'   => 'Salasana uudelleen',
'newusersonly'        => 'vain uudet käyttäjät',
'remembermypassword'  => 'Muista minut',
'yourdomainname'      => 'Verkkonimi',
'externaldberror'     => 'Tapahtui virhe ulkoisen autentikointitietokannan käytössä tai sinulla ei ole lupaa päivittää tunnustasi.',
'loginproblem'        => '<b>Sisäänkirjautuminen ei onnistunut.</b><br />Yritä uudelleen!',
'alreadyloggedin'     => '<strong>Käyttäjä $1, olet jo kirjautunut sisään!</strong><br />\n',
'login'               => 'Kirjaudu sisään',
'loginprompt'         => '<!-- -->',
'userlogin'           => 'Kirjaudu sisään tai luo tunnus',
'logout'              => 'Kirjaudu ulos',
'userlogout'          => 'Kirjaudu ulos',
'notloggedin'         => 'Et ole kirjautunut',
'createaccount'       => 'Luo uusi käyttäjätunnus',
'createaccountmail'   => 'sähköpostitse',
'badretype'           => 'Syöttämäsi salasanat ovat erilaiset.',
'userexists'          => 'Pyytämäsi käyttäjänimi on jo käytössä. Ole hyvä ja valitse toinen käyttäjänimi.',
'youremail'           => 'Sähköpostiosoite',
'yourrealname'        => 'Nimi',
'yourlanguage'        => 'Käyttöliittymän kieli',
'yourvariant'         => 'Kielivariantti', // TODO: CHECK ME (language varian)
'yournick'            => 'Nimimerkki allekirjoituksia varten',
'email'               => 'Sähköpostitoiminnot',
'emailforlost'        => '&nbsp;',
'prefs-help-email-enotif' => 'Tätä osoitetta käytetään myös artikkelien muuttumisilmoituksiin, jos ominaisuus on käytössä.',
'prefs-help-realname' => 'Nimi (vapaaehtoinen): Nimeäsi käytetään antaamaan kunnia työllesi.',
'loginerror'          => 'Sisäänkirjautumisvirhe',
'prefs-help-email'    => 'Sähköpostiosoite (vapaaehtoinen): Muut käyttäjät voivat ottaa sinuun yhteyttä sähköpostilla ilman, että osoitteesi paljastuu.',

'nocookiesnew'        => 'Käyttäjä luotiin, mutta et ole kirjautunut sisään. {{SITENAME}} käyttää evästeitä sisäänkirjautumisen yhteydessä. Selaimesi ei salli evästeistä. Kytke ne päälle, ja sitten kirjaudu sisään juuri luomallasi käyttäjänimellä ja salasanalla.',
'nocookieslogin'      => '{{SITENAME}} käyttää evästeitä sisäänkirjautumisen yhteydessä. Selaimesi ei salli evästeitä. Ota ne käyttöön, ja yritä uudelleen.',
'noname'              => 'Et ole määritellyt kelvollista käyttäjänimeä.',
'loginsuccesstitle'   => 'Sisäänkirjautuminen onnistui',
'loginsuccess'        => 'Olet kirjautunut käyttäjänä $1.',
'nosuchuser'          => 'Käyttäjää <strong>$1</strong> ei ole olemassa. Tarkista kirjoititko nimen oikein, tai käytä alla olevaa lomaketta uuden käyttäjätunnuksen luomiseksi.', // TODO NOWIKIMARKUP
'nosuchusershort'     => 'Käyttäjää nimellä <b>$1</b> ei ole. Kirjoititko nimen oikein?', // TODO NO WIKIMARKUP
'wrongpassword'       => 'Syöttämäsi salasana ei ole oikein. Ole hyvä ja yritä uudelleen.',
'mailmypassword'      => 'Lähetä minulle uusi salasana sähköpostilla',
'passwordremindertitle' => 'Salasanamuistutus {{GRAMMAR:elative|{{SITENAME}}}}',

'passwordremindertext'=> 'Joku IP-osoitteesta $1 pyysi {{GRAMMAR:partitive|{{SITENAME}}}} lähettämään uuden salasanan. Salasana käyttäjälle $2 on nyt $3. Kirjaudu sisään ja vaihda salasana.',
'noemail'             => 'Käyttäjälle \'\'\'$1\'\'\' ei ole määritelty sähköpostiosoitetta.',
'passwordsent'        => 'Uusi salasana on lähetetty käyttäjän <b>$1</b> sähköpostiosoitteeseen.', // TODO NOWIKIMARKUP
'eauthentsent'        => 'Varmennussähköposti on lähetetty annettuun sähköpostiosoitteeseen. Muita viestejä ei lähetetä, ennen kuin olet toiminut viestin ohjeiden mukaan ja varmistanut, että sähköpostiosoite kuuluu sinulle.',
'loginend'            => '\'\'\'Rekisteröidäksesi käyttäjätunnuksen:\'\'\'
#<small>Valitse itsellesi käyttäjätunnus ja kirjoita se \'\'käyttäjätunnus\'\'-kenttään.</small>
#<small>Valitse salasana ja kirjoita se sekä \'\'salasana\'\'- että \'\'salasana uudelleen\'\' -kenttiin.</small>
#<small>Halutessasi voit kirjoittaa sähköpostiosoitteesi \'\'sähköpostiosoite\'\'-kenttään. Jos annat sähköpostiosoitteesi, muut käyttäjät voivat lähettää sinulle sähköpostia saamatta osoitettasi tietoonsa, ja voit pyytää uuden salasanan, mikäli satut unohtamaan salasanasi.</small>

\'\'\'Kirjautuaksesi sisään:\'\'\'
*<small>Syötä käyttäjätunnuksesi ja salasanasi.</small>

<small>Huomaa, että {{GRAMMAR:illative|{{SITENAME}}}} kirjautuminen edellyttää evästeiden käyttöä.</small>',
'mailerror'           => 'Virhe lähetettäessä sähköpostia: $1',
'acct_creation_throttle_hit' => 'Olet jo luonut $1 tunnusta. Et voi luoda uutta.',
'emailauthenticated'         => 'Sähköpostiosoitteesi varmennettiin $1.',
'emailnotauthenticated'      => 'Sähköpostiosoitteesi ei ole vielä varmennettu. Sähköpostia ei lähetetä liittyen alla oleviin toimintoihin.',
'noemailprefs'        => 'Sähköpostiosoitetta ei ole määritelty. <!-- Seuraavat ominaisuudet eivät ole käytössä. -->', // MAYCHANGE
'emailconfirmlink'    => 'Varmenna sähköpostiosoite',
'invalidemailaddress' => 'Sähköpostiosoitetta ei voida hyväksyä, koska se ei ole oikeassa muodossa. Ole hyvä ja anna oikea sähköpostiosoite tai jätä kenttä tyhjäksi.',



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
'hr_tip'              => 'Vaakasuora viiva (käytä rajoitetusti)',
'infobox'             => 'Napsauta painiketta saadaksesi esimerkkitekstin',

# alert box shown in browsers where text selection does not work, test e.g. with mozilla or konqueror
'infobox_alert'       => "Syötä teksti, jonka haluat muotoilluksi.\\n Se näytetään toisessa laatikossa leikkaa-liimaamista varten.\\nEsimerkki:\\n$1\\n muuttuu seuraavaksi:\\n$2",

# Edit pages
#
'summary'             => 'Yhteenveto',
'subject'             => 'Aihe',
'minoredit'           => 'Tämä on pieni muutos',
'watchthis'           => 'Tarkkaile tätä sivua',
'savearticle'         => 'Tallenna sivu',
'preview'             => 'Esikatselu',
'showpreview'         => 'Esikatsele',
'showdiff'            => 'Näytä muutokset',
'blockedtitle'        => 'Pääsy estetty',
'blockedtext'         => 'Yritit muokata sivua tai luoda uuden sivun. $1 on estänyt pääsysi {{GRAMMAR:illative|{{SITENAME}}}} joko käyttäjänimesi tai IP-osoitteesi perusteella. Annettu syy estolle on: <br />\'\'$2\'\'<p>Jos olet sitä mieltä, että sinut on estetty syyttä, voit keskustella asiasta [[Project:Ylläpitäjät|ylläpitäjän]] kanssa. Huomaa, ettet voi lähettää sähköpostia {{GRAMMAR:genitive|{{SITENAME}}}} kautta, ellet ole asettanut olemassaolevaa sähköpostiosoitetta [[Special:Preferences|asetuksissa]] ==Syytön?== Ajoittain kokonaisia IP-alueita tai yhteiskäytössä olevia osoitteita estetään. Se tarkoittaa, että myös viattomat käyttäjät voivat joutua estetyksi. Jos IP-osoitteesi on dynaaminen, eli se voi toisinaan vaihtua, olet saattanut saada estetyn osoitteen käyttöösi, ja esto vaikuttaa nyt sinuun. Jos tämä ongelma toistuu jatkuvasti, ota yhteyttä Internet-palveluntarjoajaasi tai {{GRAMMAR:genitive|{{SITENAME}}}} ylläpitäjään. Ilmoita IP-osoitteesi, joka on $3.',

'whitelistedittitle'  => 'Sisäänkirjautuminen vaaditaan muokkaamiseen',
'whitelistedittext'   => 'Sinun täytyy kirjautua [[Special:Userlogin|sisään]] muokataksesi sivuja.',
'whitelistreadtitle'  => 'Sisäänkirjautuminen vaaditaan lukemiseen',
'whitelistreadtext'   => 'Sinun täytyy kirjautua [[Special:Userlogin|sisään]] lukeaksesi sivuja.',
'whitelistacctitle'   => 'Sinun ei ole sallittu luoda tunnusta',
'whitelistacctext'    => 'Saadaksesi oikeudet luoda tunnus sinun täytyy kirjautua [[Special:Userlogin|sisään]] ja sinulla tulee olla asiaankuuluvat oikeudet.',
'loginreqtitle'       => 'Sisäänkirjautuminen vaaditaan',
'loginreqlink'        => 'kirjautua sisään',
'loginreqpagetext'    => 'Sinun täytyy $1, jotta voisit nähdä muut sivut.',

'accmailtitle'        => 'Salasana lähetetty.',
'accmailtext'         => 'käyttäjän \'\'\'$1\'\'\' salasana on lähetetty osoitteeseen \'\'\'$2\'\'\'.',
'newarticle'          => '(uusi)',
'newarticletext'      => 'Linkki toi sivulle, jota ei vielä ole. Voit luoda sivun kirjoittamalla alla olevaan tilaan. Jos et halua luoda sivua, käytä selaimen paluutoimintoa.',
'talkpagetext'        => '<!-- MediaWiki:talkpagetext -->',
'anontalkpagetext'    => '----\'\'Tämä on nimettömän käyttäjän keskustelusivu. Hän ei ole joko luonut itselleen käyttäjätunnusta tai ei käytä sitä. Siksi hänet tunnistetaan nyt numeerisella IP-osoitteella. Kyseinen IP-osoite voi olla useamman henkilön käytössä. Jos olet nimetön käyttäjä, ja sinusta tuntuu, että aiheettomia kommentteja on ohjattu sinulle, [[Special:Userlogin|luo itsellesi käyttäjätunnus tai kirjaudu sisään]] välttääksesi jatkossa sekaannukset muiden nimettömien käyttäjien kanssa.\'\'',
'noarticletext'       => '<big>\'\'\'{{GRAMMAR:inessive|{{SITENAME}}}} ei ole tämän nimistä sivua.\'\'\'</big>
* Voit kirjoittaa uuden sivun \'\'\'<span class="plainlinks">[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} {{PAGENAME}}]</span>.\'\'\'
* Jos olet luonut sivun tällä nimellä, se on saatettu poistaa — katso [[Special:Log/delete|poistoloki]].',
'clearyourcache'      => '\'\'\'Huomautus:\'\'\' Selaimen välimuisti pitää ehkä tyhjentää asetusten tallentamisen jälkeen, jotta muutokset tulisivat voimaan: \'\'\'Mozilla, Firefox ja Safari:\'\'\' napsauta shift-näppäin pohjassa päivitä tai  paina \'\'shift-ctrl-r\'\' / \'\'shift-cmd-r\'\', \'\'\'IE:\'\'\' \'\'ctrl-f5\'\' tai \'\'\'Konqueror:\'\'\' \'\'F5\'\'.',
'usercssjsyoucanpreview' => '\'\'\'Vinkki:\'\'\' Käytä esikatselupainiketta testataksesi uutta CSS:ää tai JavaScriptiä ennen tallennusta.',
'usercsspreview'      => '\'\'\'Tämä on vasta CSS:n testaus ja esikatselu.\'\'\'',
'userjspreview'       => '\'\'\'Tämä on vasta JavaScriptin testaus ja esikatselu.\'\'\'',
'updated'             => '(Päivitetty)',
'note'                => 'Huomautus: ', // TODO: NO WIKI MARKUP
'previewnote'         => 'Tämä on vasta sivun esikatselu. Sivua ei ole vielä tallennettu!',
'previewconflict'     => 'Tämä esikatselu näyttää miltä muokkausalueella oleva teksti näyttää tallennettuna.',
'editing'             => 'Muokataan sivua $1',
'editingsection'      => 'Muokataan osiota sivusta $1',
'editingcomment'      => 'Muokataan kommenttia sivulla $1',
'editconflict'        => 'Päällekkäinen muokkaus: $1',
'explainconflict'     => 'Joku muu on muuttanut tätä sivua sen jälkeen, kun aloit muokata sitä. Ylempi tekstialue sisältää tämänhetkisen tekstin. Tekemäsi muutokset näkyvät alemmassa ikkunassa. Sinun täytyy yhdistää muutoksesi olemassa olevaan tekstiin. \'\'\'Vain\'\'\' ylemmässä alueessa oleva teksti tallentuu, kun tallennat sivun.',
'yourtext'            => 'Oma tekstisi',
'storedversion'       => 'Tallennettu versio',
'nonunicodebrowser'   => '\'\'\'Varoitus: Selaimesi ei ole Unicode-yhteensopiva. Ole hyvä ja vaihda selainta, ennen kuin muokkaat sivua.\'\'\'',
'editingold'          => '<center><strong>Varoitus</strong>: Olet muokkaamassa vanhaa versiota tämän sivun tekstistä. Jos tallennat sen, kaikki tämän version jälkeen tehdyt muutokset katoavat.</center>',
'yourdiff'            => 'Eroavaisuudet',
'copyrightwarning'    => '<strong>Muutoksesi astuvat voimaan välittömästi.</strong> Jos haluat harjoitella muokkaamista, ole hyvä ja käytä [[Project:Hiekkalaatikko|hiekkalaatikkoa]].<br /><br />Kaikki {{GRAMMAR:illative|{{SITENAME}}}} tehtävät tuotokset katsotaan julkaistuksi GNU Free Documentation -lisenssin mukaisesti ([[Project:{{SITENAME}} ja tekijänoikeudet|lisätietoja]]). Jos et halua, että kirjoitustasi muokataan armottomasti ja uudelleenkäytetään vapaasti, älä tallenna kirjoitustasi. Tallentamalla muutoksesi lupaat, että kirjoitit tekstisi itse, tai kopioit sen jostain vapaasta lähteestä. <strong>ÄLÄ KÄYTÄ TEKIJÄNOIKEUDEN ALAISTA MATERIAALIA ILMAN LUPAA!</strong>',
'copyrightwarning2'   => '<br />Huomaa, että kuka tahansa voi muokata, muuttaa ja poistaa kaikkia sivustolle tekemiäsi lisäyksiä ja muutoksia. Muokkaamalla sivustoa luovutat sivuston käyttäjille tämän oikeuden ja takaat, että lisäämäsi aineisto on joko itse kirjoittamaasi tai peräisin jostain vapaasta lähteestä. <strong>TEKIJÄNOIKEUDEN ALAISEN MATERIAALIN KÄYTTÄMINEN ILMAN LUPAA ON EHDOTTOMASTI KIELLETTYÄ!</strong>',
'longpagewarning'     => '<center>Tämän sivun tekstiosuus on $1 binäärikilotavua pitkä. Harkitse, voisiko sivun jakaa pienempiin osiin.</center>',
'readonlywarning'     => '<strong>Varoitus</strong>: Tietokanta on lukittu huoltoa varten, joten voi olla ettet pysty tallentamaan muokkauksiasi juuri nyt. Saattaa olla paras leikata ja liimata tekstisi omaan tekstitiedostoosi ja tallentaa se tänne myöhemmin.',
'protectedpagewarning'=> '<center><small>Tämä sivu on lukittu. Vain ylläpitäjät voivat muokata sitä.</small></center>',
'templatesused'       => 'Tällä sivulla käytetyt mallineet:',

# History pages
#
'revhistory'          => 'Muutoshistoria',
'nohistory'           => 'Tällä sivulla ei ole muutoshistoriaa.',
'revnotfound'         => 'Versiota ei löydy',
'revnotfoundtext'     => 'Pyytämääsi versiota ei löydy. Tarkista URL-osoite, jolla hait tätä sivua.',
'loadhist'            => 'Ladataan sivuhistoriaa',
'currentrev'          => 'Nykyinen versio',
'revisionasof'        => 'Versio $1',
'revisionasofwithlink'=> 'Versio, joka luotiin $1.<br />$3 | $2 | $4',
'previousrevision'    => '← Vanhempi versio',
'nextrevision'        => 'Uudempi versio →',
'currentrevisionlink' => 'Näytä nykyinen versio',
'cur'                 => 'nyk.',
'next'                => 'seur.',
'last'                => 'edell.',
'orig'                => 'alkup.',
'histlegend'          => 'Merkinnät: (nyk.) = eroavaisuudet nykyiseen versioon, (edell.) = eroavaisuudet edelliseen versioon, <span class="minor">p</span> = pieni muutos', // TODO NO WIKIMARKUP
'history_copyright'   => '-',
'histfirst'           => 'Ensimmäiset',
'histlast'            => 'Viimeisimmät',

# Diffs
#
'difference'           => 'Versioiden väliset erot',
'loadingrev'           => 'Ladataan versiota vertailua varten',
'lineno'               => 'Rivi $1:',
'editcurrent'          => 'Muokkaa tämän sivun uusinta versiota',
'selectnewerversionfordiff' => 'Valitse uudempi versio vertailuun',
'selectolderversionfordiff' => 'Valitse vanhempi versio vertailuun',
'compareselectedversions'   => 'Vertaile valittuja versioita',

# Search results
#
'searchresults'       => 'Hakutulokset',
'searchresulttext'    => '<!-- -->',
'searchquery'         => 'Haku termeillä $1',
'badquery'            => 'Kelvoton hakumerkkijono',
'badquerytext'        => 'Tekemäsi kysely ei ole kelvollinen. Tämä johtuu todennäköisesti siitä, että et ole määritellyt hakumerkkijonoa.',
'matchtotals'         => 'Haulla \'\'\'$1\'\'\' löytyi $2 osumaa sivujen otsikoista ja $3 osumaa sivujen sisällöistä.',
'nogomatch'           => '<big>Täsmälleen tällä otsikolla ei ole sivua.</big>

:Voit [[$1|luoda aiheesta uuden sivun]].
:<small>Etsi ensin vastaavaa sivua, joka voi olla kirjoitusasultaan hieman erilainen</small>
',
'titlematches'        => 'Osumat sivujen otsikoissa',
'notitlematches'      => 'Hakusanaa ei löytynyt minkään sivun otsikosta',
'textmatches'         => 'Osumat sivujen teksteissä',
'notextmatches'       => 'Hakusanaa ei löytynyt sivujen teksteistä',
'prevn'               => '← $1 edellistä',
'nextn'               => '$1 seuraavaa →',
'viewprevnext'        => 'Näytä [$3] kerralla.<br />$1 | $2',
'showingresults'      => '<b>$1</b> tulosta tuloksesta <b>$2</b> alkaen.',
'showingresultsnum'   => 'Alla on <b>$3</b> hakutulosta alkaen <b>$2.</b> tuloksesta.',
'nonefound'           => '\'\'\'Huomautus\'\'\': Epäonnistuneet haut johtuvat usein hyvin yleisten sanojen, kuten \'\'on\'\' ja \'\'ei\'\', etsimisestä tai useamman kuin yhden hakutermin määrittelemisestä. Vain sivut, joilla on kaikki hakutermin sanat, näkyvät tuloksissa.',
'powersearch'         => 'Etsi',
'powersearchtext'     => 'Haku nimiavaruuksista:<br />$1<br /><b>Etsi</b> $3 $9 $2 Luettele uudelleenohjaukset', # TODO NOWIKIMARKUP
'searchdisabled'      => '<p style="margin: 1.5em 2em 1em">Tekstihaku on poistettu toistaiseksi käytöstä suuren kuorman vuoksi. Voit käyttää alla olevaa Googlen hakukenttää sivujen etsimiseen, kunnes haku tulee taas käyttöön.<small>Huomaa, että ulkopuoliset kopiot {{GRAMMAR:genitive|{{SITENAME}}}} sisällöstä eivät välttämättä ole ajan tasalla.</small></p>', # TODO NOWIKIMARKUP

'blanknamespace'      => '(sivut)',

# Preferences page
#
'preferences'         => 'Asetukset',
'prefsnologin'        => 'Et ole kirjautunut sisään',
'prefsnologintext'    => 'Sinun täytyy [[Special:Userlogin|kirjautua sisään]], jotta voisit muuttaa asetuksia.',
'prefslogintext'      => 'Olet kirjautunut sisään käyttäjänä \'\'\'$1\'\'\'. Sisäinen tunnistenumerosi on \'\'\'$2\'\'\'.',
'prefsreset'          => 'Asetukset on palautettu tallennetuista asetuksistasi.',
'qbsettings'          => 'Pikavalikko',
'changepassword'      => 'Vaihda salasanaa',
'skin'                => 'Ulkonäkö',
'math'                => 'Matematiikka',
'dateformat'          => 'Päiväyksen muoto',
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
'prefs-misc'          => 'Muut asetukset',
'saveprefs'           => 'Tallenna asetukset',
'resetprefs'          => 'Palauta tallennetut asetukset',
'oldpassword'         => 'Vanha salasana',
'newpassword'         => 'Uusi salasana',
'retypenew'           => 'Uusi salasana uudelleen',
'textboxsize'         => 'Muokkaaminen',
'rows'                => 'Rivit',
'columns'             => 'Sarakkeet',
'searchresultshead'   => 'Haku',
'resultsperpage'      => 'Tuloksia sivua kohti',
'contextlines'        => 'Rivien määrä tulosta kohti',
'contextchars'        => 'Sisällön merkkien määrä riviä kohden',
'stubthreshold'       => 'Tynkäsivun osoituskynnys',
'recentchangescount'  => 'Sivujen määrä tuoreissa muutoksissa',
'savedprefs'          => 'Asetuksesi tallennettiin onnistuneesti.',
'timezonelegend'      => 'Aikavyöhyke',
'timezonetext'        => 'Paikallisen ajan ja palvelimen ajan (UTC) välinen aikaero tunteina.',
'localtime'           => 'Paikallinen aika',
'timezoneoffset'      => 'Aikaero',
'servertime'          => 'Palvelimen aika',
'guesstimezone'       => 'Utele selaimelta',
'emailflag'           => 'Estä sähköpostin lähetys osoitteeseen',
'defaultns'           => 'Etsi oletusarvoisesti näistä nimiavaruuksista:',
'default'             => 'oletus',
'files'               => 'Tiedostot',

# User levels special page
#

# switching pan
/*'groups-lookup-group' => 'Manage group rights',
'groups-group-edit' => 'Existing groups: ',
'editgroup' => 'Edit Group',
'addgroup' => 'Add Group',*/

'userrights-lookup-user'   => 'Käyttöoikeuksien hallinta',
'userrights-user-editname' => 'Käyttäjätunnus: ',
'editusergroup'            => 'Muokkaa käyttäjän ryhmiä',

# user groups editing
#
'userrights-editusergroup' => 'Käyttäjän ryhmät',
'saveusergroups' => 'Tallenna',
'userrights-groupsmember' => 'Jäsenenä ryhmissä:',
'userrights-groupsavailable' => 'Saatavilla olevat ryhmät:',
'userrights-groupshelp' => 'Valitse ryhmät, jotka haluat poistaa tai lisätä. Valitsemattomia ryhmiä ei muuteta. Voit poistaa valinnan pitämällä Ctrl-näppäintä pohjassa napsautuksen aikana.',

# Default group names and descriptions
#
'group-sysop-name'      => 'Ylläpitäjät',
'group-sysop-desc'      => 'Luotetut käyttäjät voivat estää käyttäjiä ja poistaa ja suojata artikkeleita',
'group-bureaucrat-name' => 'Byrokraatit',
'group-bureaucrat-desc' => 'Byrokraatit voivat tehdä ylläpitäjiä',
'group-bot-name'        => 'Botit',
'group-bot-desc'        => '',

# Recent changes
#
'changes'             => 'muutosta',
'recentchanges'       => 'Tuoreet muutokset',
'recentchangestext'   => 'Tällä sivulla voi seurata tuoreita {{GRAMMAR:illative|{{SITENAME}}}} tehtyjä muutoksia.',
'rcloaderr'           => 'Ladataan tuoreita muutoksia',
'rcnote'              => 'Alla on <b>$1</b> tuoreinta muutosta viimeisten <b>$2</b> päivän ajalta.',
'rcnotefrom'          => 'Alla on muutokset <b>$2</b> lähtien. Enintään <b>$1</b> merkintää näytetään.',
'rclistfrom'          => 'Näytä uudet muutokset $1 alkaen',
'showhideminor'       => '$1 pienet muutokset | $2 botit | $3 kirjautuneet | $4 tarkastetut',
'rclinks'             => 'Näytä $1 tuoretta muutosta viimeisten $2 päivän ajalta.<br />$3',
'rchide'              => 'muodossa $4 ; $1 pientä muutosta; $2 toissijaista nimiavaruutta; $3 moninkertaista muutosta.',
'rcliu'               => ' ; $1 muokkausta sisäänkirjautuneilta ',
'diff'                => 'ero',
'hist'                => 'historia',
'hide'                => 'piilota',
'show'                => 'näytä',
'tableform'           => 'taulukko',
'listform'            => 'luettelo',
'nchanges'            => '$1 muutosta',
'minoreditletter'     => 'p',
'newpageletter'       => 'U',
'sectionlink'         => '→',
'number_of_watching_users_RCview'   => '[$1]',
'number_of_watching_users_pageview' => '[$1 tarkkailevaa käyttäjää]', // TODO sigplu

# Upload
#
'upload'              => 'Tallenna tiedosto',
'uploadbtn'           => 'Tallenna tiedosto',
'uploadlink'          => 'Tallenna kuvia',
'reupload'            => 'Uusi tallennus',
'reuploaddesc'        => 'Paluu tallennuslomakkeelle.',
'uploadnologin'       => 'Et ole kirjaunut sisään',
'uploadnologintext'   => 'Sinun pitää olla [[Special:Userlogin|kirjautuneena sisään]], jotta voisit tallentaa tiedostoja.',
'upload_directory_read_only' => 'Palvelimella ei ole kirjoitusoikeuksia tallennushakemistoon "$1".',
'uploaderror'         => 'Tallennusvirhe',
'uploadtext'          => '\'\'\'SEIS!\'\'\' Ennen kuin tallennat tiedostoja {{GRAMMAR:illative|{{SITENAME}}}}, tutustu [[Project:Tiedostojen tallennus|sääntöihin]] ja noudata niitä.
*\'\'Kirjoita tiedoston tietoihin tarkka tieto tiedoston lähteestä.\'\' Jos teit tiedoston itse, sano se. Jos löysit tiedoston Internetistä, varmista, että sitä saa käyttää {{GRAMMAR:inessive|{{SITENAME}}}} laita mukaan linkki kyseiselle sivulle.
*\'\'Kerro tiedoston tekijänoikeuksien tila.\'\'
*\'\'Käytä järkevää tiedostonimeä.\'\' Nimeä tiedostosi mieluummin tyyliin ”Eiffel-torni Pariisissa, yökuva.jpg” kuin ”etpan1024c.jpg”. Näin vältät mahdollisesti jo olemassa olevan tiedoston korvaamisen omallasi. Voit etsiä aikaisemmin tallennettuja tiedostoja [[Special:Imagelist|tiedostoluettelosta]].
*Laita johonkin aiheeseen liittyvään sivuun linkki kyseiseen tiedostoon, tai kirjoita kuvaussivulle kuvaus tiedoston sisällöstä.
*Jos haluat nähdä tai etsiä aiemmin tallennettuja tiedostoja, katso [[Special:Imagelist|tiedostoluettelo]]. Tallennukset ja poistot kirjataan [[Special:Log/upload|tallennuslokiin]].

Suositellut kuvaformaatit ovat JPEG valokuville, PNG piirroksille ja kuvakkeille ja Ogg Vorbis äänille. Voit liittää kuvan sivulle käyttämällä seuraavan muotoista merkintää \'\'\'<nowiki>[[Kuva:tiedosto.jpg]]</nowiki>\'\'\' tai \'\'\'<nowiki>[[Kuva:tiedosto.png|kuvausteksti]]</nowiki>\'\'\' tai \'\'\'<nowiki>[[media:tiedosto.ogg]]</nowiki>\'\'\' äänille.

Huomaa, että {{GRAMMAR:inessive|{{SITENAME}}}} muut voivat muokata tai poistaa tallentamasi tiedoston, jos he katsovat, että se ei palvele projektin tarpeita. Tallentamismahdollisuutesi voidaan estää, jos käytät järjestelmää väärin.',
'uploadlog'           => 'Tallennusloki',
'uploadlogpage'       => 'Tallennusloki',
'uploadlogpagetext'   => 'Alla on luettelo uusimmista tallennuksista. Kaikki ajat näytetään palvelimen aikavyöhykkeessä (UTC).',
'filename'            => 'Tiedoston nimi',
'filedesc'            => 'Yhteenveto',
'fileuploadsummary'   => 'Yhteenveto:',
'filestatus'          => 'Tiedoston tekijänoikeudet',
'filesource'          => 'Lähde',
'copyrightpage'       => 'Project:Tekijänoikeudet',
'copyrightpagename'   => '{{SITENAME}} ja tekijänoikeudet',
'uploadedfiles'       => 'Tallennetut tiedostot',
'ignorewarning'       => 'Jätä tämä varoitus huomiotta, ja tallenna tiedosto.',
'minlength'           => 'Tiedoston nimessä pitää olla vähintään kolme merkkiä.',
'illegalfilename'     => 'Tiedoston nimessä \'\'\'$1\'\'\' on merkkejä, joita ei sallita sivujen nimissä. Vaihda tiedoston nimeä, ja yritä tallentamista uudelleen.',
'badfilename'         => 'Tiedoston nimi vaihdettiin: $1.',
'badfiletype'         => '".$1" ei ole suositeltava tiedostomuoto.',
'largefile'           => 'Tiedostojen ei tulisi olla yli $1 kilotavun kokoisia. Tiedoston, jonka yritit tallentaa, koko on $2.',
'emptyfile'           => 'Tiedosto, jonka yritit tallentaa näyttää olevan tyhjä. Tarkista, että kirjoitit polun ja nimen oikein ja että se ei ole liian suuri kohdepalvelimelle.',
'fileexists'          => 'Samanniminen tiedosto on jo olemassa. Katso tiedoston sivu $1, jos et ole varma, haluatko muuttaa sitä.',
'successfulupload'    => 'Tallennus onnistui',
'fileuploaded'        => 'Tiedosto \'\'\'$1\'\'\' on tallennettu onnistuneesti. Seuraa linkkiä ($2) kuvaussivulle, ja täytä tiedostoon liityvät tiedot, kuten mistä se on peräisin, milloin se on luotu, kuka sen loi ja mahdollisesti muita tietämiäsi tietoja. Jos tiedosto on kuva, voit lisätä sen sivulle näin: \'\'\'<nowiki>[[Kuva:$1|thumb|Kuvaus]]</nowiki>\'\'\'',
'uploadwarning'       => 'Tallennusvaroitus',
'savefile'            => 'Tallenna',
'uploadedimage'       => 'tallensi tiedoston [[$1]]',
'uploaddisabled'      => 'Tiedostojen tallentaminen ei ole käytöstä.',
'uploadscripted'      => 'Tämä tiedosto sisältää HTML-koodia tai skriptejä, jotka selain saattaa virheellisesti suorittaa.',
'uploadcorrupt'       => 'Tiedosto on vioittunut tai sillä on väärä tiedostopääte. Tarkista tiedosto ja lähetä se uudelleen.',
'uploadvirus'         => 'Tiedosto sisältää viruksen. Tarkemmat tiedot: $1',
'sourcefilename'      => 'Lähdenimi',
'destfilename'        => 'Kohdenimi',

# Image list
#
'imagelist'           => 'Tiedostoluettelo',
'imagelisttext'       => 'Alla on $1 tiedostoa lajiteltuna $2.',
'getimagelist'        => 'noudetaan tiedostoluetteloa',
'ilsubmit'            => 'Hae',
'showlast'            => 'Näytä viimeiset $1 tiedostoa lajiteltuna $2.',
'byname'              => 'nimen mukaan',
'bydate'              => 'päiväyksen mukaan',
'bysize'              => 'koon mukaan',
'imgdelete'           => 'poista',
'imgdesc'             => 'kuvaus',
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
'noimage'             => 'Tämän nimistä tiedostoa ei ole olemassa. Voit $1 {{GRAMMAR:genitive|{{SITENAME}}}}',
'noimage-linktext'    => 'tallentaa tiedoston',
'uploadnewversion'    => '[$1 Tallenna] uusi versio tästä tiedostosta',

# Statistics
#
'statistics'          => 'Tilastoja',
'sitestats'           => 'Sivuston tilastoja',
'userstats'           => 'Käyttäjätilastoja',
'sitestatstext'       => 'Tietokannassa on yhteensä \'\'\'$1\'\'\' sivua. Tähän on laskettu mukaan keskustelusivut, {{GRAMMAR:genitive|{{SITENAME}}}} erikoissivut, hyvin lyhyet sivut, uudelleenohjaukset sekä muita sivuja, joita ei voi pitää kunnollisina sivuina. Nämä poislukien tietokannassa on \'\'\'$2\'\'\' sivua.<br />Sivuja on katsottu yhteensä \'\'\'$3\'\'\' kertaa ja muokattu \'\'\'$4\'\'\' kertaa. Keskimäärin yhtä sivua on muokattu \'\'\'$5\'\'\' kertaa, ja muokkausta kohden sivua on katsottu keskimäärin \'\'\'$6\'\'\' kertaa.',
'userstatstext'       => 'Rekisteröityneitä käyttäjiä on \'\'\'$1\'\'\'. Näistä \'\'\'$2\'\'\' on ylläpitäjiä.<!-- $3 -->',

# Maintenance Page
#
# NOTICE: Currently not used
'maintenance'         => 'Ylläpitosivu',
'maintnancepagetext'  => 'Tämä sivu sisältää useita käteviä työkaluja jokapäiväistä ylläpitoa varten. Jotkut näistä toiminnoista kuormittavat tietokantaa, joten ole hyvä äläkä paina päivitysnappia jokaisessa kohdassa ;-)',
'maintenancebacklink' => 'Takaisin ylläpitosivulle',
'disambiguations'     => 'Linkit tarkennusivuihin',
'disambiguationspage' => 'Project:Linkkejä_tarkennussivuihin',
'disambiguationstext' => 'Seuraavat artikkelit linkittävät <i>tarkennussivuun</i>. Sen sijasta niiden pitäisi linkittää asianomaiseen aiheeseen.<br />Sivua kohdellaan tarkennussivuna jos siihen on linkki sivulta $1.<br />Linkkejä muihin nimiavaruuksiin <i>ei</i> ole listattu tässä.',
'doubleredirects'     => 'Kaksinkertaiset uudelleenohjaukset',
'doubleredirectstext' => '<b>Huomio:</b> Tässä listassa saattaa olla virheitä. Yleensä kyseessä on sivu, jossa ensimmäisen #REDIRECTin jälkeen on tekstiä.<br />\nJokaisella rivillä on linkit ensimmäiseen ja toiseen uudelleenohjaukseen sekä toisen uudelleenohjauksen kohteen ensimmäiseen riviin, eli yleensä \'oikeaan\' kohteeseen, johon ensimmäisen uudelleenohjauksen pitäisi osoittaa.',
'brokenredirects'     => 'Virheelliset uudelleenohjaukset',
'brokenredirectstext' => 'Seuraavat uudelleenohjaukset on linkitetty artikkeleihin, joita ei ole olemassa.',
'selflinks'           => 'Sivut, jotka linkittävät itseensä',
'selflinkstext'       => 'Seuraavat sivut sisältävät linkkejä itseensä.',
'mispeelings'         => 'Kirjoitusvirheitä sisältävät sivut',
'mispeelingstext'     => 'Seuraavat sivut sisältävät yleisen kirjoitusvirheen, joka on lueteltu sivulla $1. Oikea kirjoitustapa on ehkä annettu (tähän tapaan).',
'mispeelingspage'     => 'Lista tavallisimmista kirjoitusvirheistä',
'missinglanguagelinks'=> 'Puuttuvat kielilinkit',
'missinglanguagelinksbutton'  => 'Etsi puuttuvat kielilinkit',
'missinglanguagelinkstext'    => 'Näitä artikkeleita <i>ei</i> ole linkitetty vastineeseensa $1:ssä. Uudelleenohjauksia ja alasivuja <i>ei</i> ole näytetty.', // suks

# Miscellaneous special pages
#
'orphans'             => 'Orposivut',
'geo'                 => 'GEO-koordinaatit',
'validate'            => 'Kelpuuta sivu',
'lonelypages'         => 'Yksinäiset sivut',
'uncategorizedpages'  => 'Luokittelemattomat sivut',
'uncategorizedcategories' => 'Luokittelemattomat luokat',
'unusedcategories'    => 'Käyttämättömät luokat',
'unusedimages'        => 'Käyttämättömät tiedostot',
'popularpages'        => 'Suositut sivut',
'nviews'              => '$1 latausta',
'wantedpages'         => 'Halutut sivut',
'mostlinked'          => 'Sivut, joihin on eniten linkkejä',
'nlinks'              => '$1 linkkiä',
'allpages'            => 'Kaikki sivut',
'prefixindex'         => 'Etuliiteluettelo',
'randompage'          => 'Satunnainen sivu',
'shortpages'          => 'Lyhyet sivut',
'longpages'           => 'Pitkät sivut',
'deadendpages'        => 'Sivut, joilla ei ole linkkejä',
'listusers'           => 'Käyttäjälista',
'specialpages'        => 'Toimintosivut',
'spheading'           => 'Toimintosivut',
'restrictedpheading'  => 'Rajoitetut toimintosivut',
'protectpage'         => 'Suojaa sivu',
'recentchangeslinked' => 'Linkitettyjen sivujen muutokset',
'rclsub'              => 'Sivut, joihin linkki sivulta $1',
'debug'               => 'Virheenetsintä',
'newpages'            => 'Uudet sivut',
'ancientpages'        => 'Kauan muokkaamattomat sivut',
'intl'                => 'Kieltenväliset linkit',
'move'                => 'Siirrä',
'movethispage'        => 'Siirrä tämä sivu',
'unusedimagestext'    => 'Huomaa, että muut verkkosivut saattavat viitata tiedostoon suoran URL:n avulla, jolloin tiedosto saattaa olla tässä listassa, vaikka sitä käytetäänkin.',
'unusedcategoriestext'=> 'Nämä luokat ovat olemassa, mutta niitä ei käytetä.',
'booksources'         => 'Kirjalähteet',
'categoriespagetext'  => '{{GRAMMAR:inessive|{{SITENAME}}}} on seuraavat luokat:',
'data'                => 'Data', // TODO: CHECK ME
'userrights'          => 'Käyttöoikeuksien hallinta',
'groups'              => 'Ryhmät',
'booksourcetext'      => 'Alla on lista linkeistä ulkopuolisiin sivustoihin, joilla myydään uusia ja käytettyjä kirjoja. Niillä voi myös olla lisätietoa kirjoista, joita etsit. {{SITENAME}} ei liity mitenkään näihin sivustoihin, eikä tätä listaa tule pitää suosituksena tai hyväksyntänä.',
'isbn'                => 'ISBN',

# No reason to overwrite
//  'rfcurl'              => 'http://www.faqs.org/rfcs/rfc$1.html',
'alphaindexline'      => 'Alkaen sivusta $1 päättyen sivuun $2',
'version'             => 'Versio',
'log'                 => 'Lokit',
'alllogstext'         => 'Yhdistetty tallennus-, poisto-, suojaus-, esto- ja ylläpitolokien näyttö. Voit rajoittaa listaa valitsemalla lokityypin, käyttäjän tai sivun johon muutos on kohdistunut.',

# Special:Allpages
'nextpage'            => 'Seuraava sivu ($1)',
'allpagesfrom'        => 'Näytä sivuja lähtien sivusta:',
'allarticles'         => 'Kaikki sivut',
'allnonarticles'      => 'Kaikki sivut, jotka eivät ole oletusnimiavaruudessa',
'allinnamespace'      => 'Kaikki sivut nimiavaruudessa $1',
'allnotinnamespace'   => 'Kaikki sivut, jotka eivät ole nimiavaruudessa $1',
'allpagesprev'        => 'Edellinen',
'allpagesnext'        => 'Seuraava',
'allpagessubmit'      => 'Vaihda',

# Email this user
#
'mailnologin'         => 'Lähettäjän osoite puuttuu',
'mailnologintext'     => 'Sinun pitää olla [[Special:Userlogin|kirjautuneena sisään]] ja [[Special:Preferences|asetuksissasi]] pitää olla toimiva sähköpostiosoite jotta voit lähettää sähköpostia muille käyttäjille.',
'emailuser'           => 'Lähetä sähköpostia tälle käyttäjälle',
'emailpage'           => 'Lähetä sähköpostia käyttäjälle',
'emailpagetext'       => 'Jos tämä käyttäjä on antanut asetuksissaan kelvollisen sähköpostiosoitteen, alla olevalla lomakeella voi lähettää yhden viestin hänelle. Omissa asetuksissasi annettu sähköpostiosoite näkyy sähköpostin lähettäjän osoitteena, jotta vastaanottaja voi vastata viestiin.',
'usermailererror'     => 'Postitus palautti virheen: ',
'defemailsubject'     => '{{SITENAME}}-sähköposti',
'noemailtitle'        => 'Ei sähköpostiosoitetta',
'noemailtext'         => 'Tämä käyttäjä ei ole määritellyt kelpoa sähköpostiosoitetta tai ei halua postia muilta käyttäjiltä.',
'emailfrom'           => 'Lähettäjä',
'emailto'             => 'Vastaanottaja',
'emailsubject'        => 'Aihe',
'emailmessage'        => 'Viesti',
'emailsend'           => 'Lähetä',
'emailsent'           => 'Sähköposti lähetetty',
'emailsenttext'       => 'Sähköpostiviestisi on lähetetty.',

# Watchlist
#
'watchlist'           => 'Tarkkailulista',
'watchlistsub'        => 'Käyttäjälle $1',
'nowatchlist'         => 'Tarkkailulistallasi ei ole sivuja.',
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
'wlhideshowown'       => '$1 omat muokkaukset.',
'wlshow'              => 'Näytä',
'wlhide'              => 'Piilota',

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
{{SERVER}}{{localurl:Special:Watchlist/edit}}

Palaute ja lisäapu osoitteessa:
{{SERVER}}{{localurl:Help:Sähköposti-ilmoitus}}',


# Delete/protect/revert
#
'deletepage'          => 'Poista sivu',
'confirm'             => 'Toteuta',
'excontent'           => 'sisälsi: \'$1 \'',
'excontentauthor'     => 'sisälsi: \'$1\' (ainoa muokkaaja oli $2)',
'exbeforeblank'       => 'ennen tyhjentämistä sisälsi: \'$1\'',
'exblank'             => 'oli tyhjä',
'confirmdelete'       => 'Vahvista poisto',
'deletesub'           => 'Sivun $1 poisto',
'historywarning'      => 'Varoitus: Sivulla, jonka aiot poistaa on muokkaushistoria: ',
'confirmdeletetext'   => 'Olet tuhomassa sivun tai tiedoston ja kaiken sen historian tietokannasta. Ymmärrä teon seuraukset ja tee poisto {{GRAMMAR:genitive|{{SITENAME}}}} käytännön mukaisesti.',
'actioncomplete'      => 'Toiminto suoritettu',
'deletedtext'         => '\'\'\'$1\'\'\' on poistettu. Katso $2 nähdäksesi tallenteen viimeaikaisista poistoista.',
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
'cantrollback'        => 'Aiempaan versioon ei voi palauttaa; viimeisin kirjoittaja on sivun ainoa tekijä.',
'alreadyrolled'       => 'Käyttäjän [[User:$2|$2]] ([[User_talk:$2|keskustelu]]) tekemiä muutoksia sivuun $1 ei voi palauttaa. Käyttäjä [[User:$3|$3]] ([[User_talk:$3|keskustelu]]) on tehnyt uudempia muutoksia.',
'editcomment'         => 'Muokkauksen yhteenveto oli: <i>$1</i>.', // TODO NOWIKIMARKUP
'revertpage'          => 'Käyttäjän [[Special:Contributions/$2|$2]] ([[User_talk:$2|keskustelu]]) muokkaukset palautettiin viimeisimpään käyttäjän $1 tekemään muutokseen.',

'sessionfailure'      => 'Istuntosi kanssa on ongelma. Muutosta ei toteutettu varotoimena sessionkaappauksien takia. Käytä selaimen paluutoimintoa ja päivitä sivu, jolta tulit, ja koita uudelleen.',
'protectlogpage'      => 'Suojausloki',
'protectlogtext'      => 'Alla loki sivujen suojauksista ja suojauksien poistoista.',
'protectedarticle'    => 'suojasi sivun $1',
'unprotectedarticle'  => 'poisti suojauksen sivulta $1',
'protectsub'          => 'Sivun $1 suojaus',
'confirmprotecttext'  => 'Haluatko varmasti suojata tämän sivun?',
'confirmprotect'      => 'Vahvista suojaus',
'protectmoveonly'     => 'Suojaa vain siirroilta',
'protectcomment'      => 'Suojauksen syy',
'unprotectsub'        => 'Suojauksen poisto sivulta $1',
'confirmunprotecttext'=> 'Haluatko varmasti poistaa tämän sivun suojauksen?',
'confirmunprotect'    => 'Vahvista suojauksen poisto',
'unprotectcomment'    => 'Syy suojauksen poistoon',

# Undelete
'undelete'            => 'Palauta poistettuja sivuja',
'undelete_short'      => 'Palauta $1 versiota',
'undeletepage'        => 'Poistettujen sivujen selaus',
'viewdeletedpage'     => 'Poistettujen sivujen selaus',
'undeletepagetext'    => 'Seuraavat sivut on poistettu, mutta ne löytyvät vielä arkistosta, joten ne ovat palautettavissa. Arkisto saatetaan tyhjentää aika ajoin.',
'undeletearticle'     => 'Palauta poistettu sivu',
'undeleterevisions'   => '$1 versiota arkistoitu.',
'undeletehistory'     => 'Jos palautat sivun, kaikki versiot lisätään sivun historiaan. Jos uusi sivu samalla nimellä on luotu poistamisen jälkeen, palautetut versiot lisätään sen historiaan, ja olemassa olevaa versiota ei korvata automaattisesti.',
'undeletehistorynoadmin' => 'Tämä sivu on poistettu. Syy sivun poistamiseen näkyy yhteenvedossa, jossa on myös tiedot, ketkä ovat muokanneet tätä sivua ennen poistamista. Sivujen varsinainen sisältö on vain ylläpitäjien luettavissa.',
'undeleterevision'    => 'Poistettu versio hetkellä $1',
'undeletebtn'         => 'Palauta!',
'undeletedarticle'    => 'palautti sivun $1',
'undeletedrevisions'  => '$1 versiota palautettiin',
'undeletedtext'       => 'Sivu [[$1]] on palautettu onnistuneesti. Lista viimeisimmistä poistoista ja palautuksista on [[Special:Log/delete|poistolokissa]].',

'namespace'           => 'Nimiavaruus',
'invert'              => 'Käännä nimiavaruusvalinta päinvastaiseksi',

# Contributions
#
'contributions'       => 'Käyttäjän muokkaukset',
'mycontris'           => 'Omat muokkaukset',
'contribsub'          => 'Käyttäjän $1 muokkaukset',
'nocontribs'          => 'Näihin ehtoihin sopivia muokkauksia ei löytynyt.',
'ucnote'              => 'Alla on \'\'\'$1\'\'\' viimeisintä tämän käyttäjän tekemää muokkausta viimeisten \'\'\'$2\'\'\' päivän aikana.',
'uclinks'             => 'Katso $1 viimeisintä muokkausta; katso $2 viimeisintä päivää.',
'uctop'               => ' (uusin)' ,
'newbies'             => 'tulokkaat',
'contribs-showhideminor' => '$1 pienet muutokset',

# What links here
#
'whatlinkshere'       => 'Tänne viittaavat sivut',
'notargettitle'       => 'Ei kohdetta',
'notargettext'        => 'Et ole määritellyt kohdesivua tai -käyttäjää johon toiminto kohdistuu.',
'linklistsub'         => 'Lista linkeistä',
'linkshere'           => 'Seuraavilta sivuilta on linkki tälle sivulle:',
'nolinkshere'         => 'Tänne ei ole linkkejä.',
'isredirect'          => 'uudelleenohjaussivu',

# Block/unblock IP
#
'blockip'             => 'Aseta muokkausesto',
'blockiptext'         => 'Tällä lomakkeella voit estää käyttäjän tai IP-osoitteen muokkausoikeudet. Muokkausoikeuksien poistamiseen pitää olla syy, esimerkiksi sivujen vandalisointi. Kirjoita syy siihen varattuun kenttään.<br />Vanhenemisajat noudattavat GNUn standardimuotoa, joka on kuvattu tar-manuaalissa ([http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html] [EN]), esimerkiksi ”1 hour”, ”2 days”, ”next Wednesday”, ”1 January 2017”. Esto voi olla myös ”indefinite” tai ”infinite”, joka kestää siihen asti, että se poistetaan.',
'ipaddress'           => 'IP-osoite', // TODO bug
'ipadressorusername'  => 'IP-osoite tai käyttäjätunnus',
'ipbexpiry'           => 'Umpeutuu',
'ipbreason'           => 'Syy',
'ipbsubmit'           => 'Estä',
'ipbother'            => 'Vapaamuotoinen kesto',
'ipboptions'          => '2 tuntia:2 hours,1 päivä:1 day,3 päivää:3 days,1 viikko:1 week,2 viikkoa:2 weeks,1 kuukausi:1 month,3 kuukautta:3 months,6 kuukautta:6 months,1 vuosi:1 year,ikuisesti:infinite',
'ipbotheroption'      => 'Muu kesto',
'badipaddress'        => 'IP-osoite on väärin muotoiltu.',
'blockipsuccesssub'   => 'Esto onnistui',
'blockipsuccesstext'  => 'Käyttäjä tai IP-osoite \'\'\'$1\'\'\' on estetty.<br />Nykyiset estot löytyvät [[Special:Ipblocklist|estolistalta]].',
'unblockip'           => 'Muokkauseston poisto',
'unblockiptext'       => 'Tällä lomakkeella voit poistaa käyttäjän tai IP-osoitteen muokkauseston.',
'ipusubmit'           => 'Poista esto',
'ipusuccess'          => 'IP-osoitteen tai käyttäjän <b>$1<b> esto poistettu', // TODO NOWIKIMARKUP
'ipblocklist'         => 'Lista estetyistä IP-osoitteista',
'ipblocklistempty'    => 'Estolista on tyhjä.',
'blocklistline'       => '$1 — $2 on estänyt käyttäjän $3 ($4)',
'infiniteblock'       => 'ikuisesti',
'expiringblock'       => 'vanhenee $1',
'blocklink'           => 'esto',
'unblocklink'         => 'poista esto',
'contribslink'        => 'muokkaukset',
'autoblocker'         => 'Olet automaattisesti estetty, koska jaat IP-osoitteen käyttäjän $1 kanssa. Eston syy: $2.', // TODO: IS WIKIMARKUP?
'blocklogpage'        => 'Estoloki',
'blocklogentry'       => 'esti käyttäjän tai IP-osoitteen $1. Eston kesto: $2',
'blocklogtext'        => 'Tässä on loki muokkausestoista ja niiden purkamisista. Automaattisesti estettyjä IP-osoitteita ei kirjata. Tutustu [[Special:Ipblocklist|estolistaan]] nähdäksesi listan tällä hetkellä voimassa olevista estoista.',
'unblocklogentry'     => 'poisti käyttäjältä $1 muokkauseston',
'range_block_disabled'=> 'Ylläpitäjän oikeis luoda alue-estoja ei ole käytöstä.',
'ipb_expiry_invalid'  => 'Virheellinen umpeutumisaika.',
'ip_range_invalid'    => 'Virheellinen IP-alue.',
'proxyblocker'        => 'Välityspalvelinesto',
'proxyblockreason'    => 'IP-osoitteestasi on estetty muokkaukset, koska se on avoin välityspalvelin. Ota yhteyttä Internet-palveluntarjoajaasi tai tekniseen tukeen ja kerro heillä tästä tietoturvaongelmasta.',
'proxyblocksuccess'   => 'Valmis.',
'sorbs'               => 'SORBS DNSBL',
'sorbsreason'         => 'IP-osoitteesti on listattu avoimena välityspalvelimena [http://www.sorbs.net SORBSin] mustalla listalla.',

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

# Make sysop
'makesysoptitle'      => 'Tee käyttäjästä ylläpitäjä',
'makesysoptext'       => 'Byrokraatit voivat tällä lomakkeella tehdä käyttäjistä ylläpitäjiä ja byrokraatteja. Kirjoita laatikkoon sen käyttäjän nimi, jolle haluat antaa oikeuksia.',
'makesysopname'       => 'Käyttäjän nimi:',
'makesysopsubmit'     => 'Tee käyttäjästä ylläpitäjä',
'makesysopok'         => 'Käyttäjä <b>$1</b> on nyt ylläpitäjä.',
'makesysopfail'       => 'Käyttäjästä <b>$1</b> ei voitu tehdä ylläpitäjää. Kirjoititko nimen oikein?', // TODO: NOWIKIMARKUP
'setbureaucratflag'   => 'Tee käyttäjästä myös byrokraatti',
'bureaucratlog'       => 'Byrokraattiloki',
'rightslogtext'       => 'Alla on loki on käyttäjien käyttöoikeuksien muutoksista.',
'bureaucratlogentry'  => 'Käyttäjän $1 ryhmäoikeudet muutettiin ryhmästä $2 ryhmään $3', // TODO: Inflect me
'rights'              => 'Oikeudet:',
'set_user_rights'     => 'Aseta käyttäjän oikeudet',
'user_rights_set'     => 'Käyttäjän <b>$1</b> oikeudet päivitetty.',
'set_rights_fail'     => 'Käyttäjän <b>$1</b> oikeuksia ei voita asettaa. Kirjoititko nimen oikein?',
'makesysop'           => 'Tee käyttäjästä ylläpitäjä',
'already_sysop'       => 'Käyttäjä on jo ylläpitäjä',
'already_bureaucrat'  => 'Käyttäjä on jo byrokraatti',
'already_steward'     => 'Käyttäjä on jo ylivalvoja',


# Validation
# Let this mature a bit

# Move page
#
'movepage'            => 'Siirrä sivu',
'movepagetext'        => 'Alla olevalla lomakkeella voit nimetä uudelleen sivuja, jolloin niiden koko historia siirtyy uuden nimen alle. Vanhasta sivusta tulee uudelleenohjaussivu, joka osoittaa uuteen sivuun. Vanhaan sivuun suunnattuja linkkejä ei muuteta, muista tehdä tarkistukset kaksinkertaisten tai rikkinäisten uudellenohjausten varalta. Olet vastuussa siitä, että linkit osoittavat sinne, mihin niiden on tarkoituskin osoittaa.

Huomaa, että sivua \'\'\'ei\'\'\' siirretä mikäli uusi otsikko on olemassaolevan sivun käytössä, paitsi milloin kyseessä on tyhjä sivu tai uudelleenohjaus, jolla ei ole muokkaushistoriaa. Tämä tarkoittaa sitä, että voit siirtää sivun takaisin vanhalle nimelleen mikäli teit virheen, mutta et voi kirjoittaa olemassa olevan sivun päälle. Jos sivu tarvitsee siirtää olemassa olevan sivun päälle, ota yhteyttä [[Special:Listusers|ylläpitäjään]].

\'\'\'HUOMIO!\'\'\'
Saatat olla tekemässä huomattavaa ja odottamatonta muutosta suositulle sivulle. Ole varma, että ymmärrät seuraukset ennen kuin jatkat.',
'movepagetalktext'    => 'Sivuun mahdollisesti kytketty keskustelusivu siirretään automaattisesti, \'\'\'paitsi jos\'\'\':
*Siirrät sivua nimiavaruudesta toiseen
*Kohdesivulla on olemassa keskustelusivu, joka ei ole tyhjä, tai
*Kumoat allaolevan ruudun asetuksen.

Näissä tapauksissa sivut täytyy siirtää tai yhdistää käsin.',
'movearticle'         => 'Siirrä sivu',
'movenologin'         => 'Et ole kirjautunut sisään',
'movenologintext'     => 'Sinun pitää olla rekisteröitynyt käyttäjä ja kirjautua sisään, jotta voisit siirtää sivun.',
'newtitle'            => 'Uusi nimi sivulle',
'movepagebtn'         => 'Siirrä sivu',
'pagemovedsub'        => 'Siirto onnistui',
'pagemovedtext'       => 'Sivu \'\'\'[[$1]]\'\'\' siirrettiin nimelle \'\'\'[[$2]]\'\'\'.',
'articleexists'       => 'Kohdesivu on jo olemassa, tai valittu nimi ei ole sopiva. Ole hyvä ja valitse uusi nimi.',
'talkexists'          => 'Sivun siirto onnistui, mutta keskustelusivua ei voitu siirtää, koska uuden otsikon alla on jo keskustelusivu. Ole hyvä ja yhdistä keskustelusivujen sisältö käsin.',
'movedto'             => 'Siirretty uudelle otsikolle',
'movetalk'            => 'Siirrä myös keskustelusivu, jos mahdollista.',
'talkpagemoved'       => 'Myös sivun keskustelusivu siirrettiin.',
'talkpagenotmoved'    => 'Sivun keskustelusivua \'\'\'ei\'\'\' siirretty.',
'1movedto2'           => 'siirsi sivun $1 uudelle nimelle $2',
'1movedto2_redir'     => 'siirsi sivun $1 uudelleenohjauksen $2 päälle',
'movelogpage'         => 'Siirtoloki',
'movelogpagetext'     => 'Anna on loki siirretyistä sivuista.',
'movereason'          => 'Syy',
'revertmove'          => 'kumoa',
'delete_and_move'     => 'Poista kohdesivu ja siirrä',
'delete_and_move_text'   => 'Kohdesivu [[$1]] on jo olemassa. Haluatko poistaa sen, jotta nykyinen sivu voitaisiin siirtää?',
'delete_and_move_reason' => 'Sivu on siirron tiellä.',
'selfmove'            => 'Lähde- ja kohdenimi ovat samat.',
'immobile_namespace'  => 'Sivuja ei voi siirtää tähän nimiavaruuteen.',

# Export

'export'              => 'Sivujen vienti',
'exporttext'          => 'Voit viedä sivun tai sivujen tekstiä ja muokkaushistoriaa XML-muodossa. Tulevaisuudessa tämä tieto voidaan tuoda johonkin toiseen wikiin, jossa käytetään MediaWiki-ohjelmistoa. Nykyisessä MediaWikin versiossa tätä ei tosin vielä tueta.

Syötä sivujen otsikoita riveittäin alla olevaan laatikkoon. Valitse myös, haluatko kaikki versiot sivuista, vai ainoastaan nykyisen version.

Jälkimmäisessä tapauksessa voit myös käyttää linkkiä. Esimerkiksi [[Juna]]sivun saa vietyä linkistä [[Special:Export/Juna]].',
'exportcuronly'       => 'Liitä mukaan ainoastaan uusin versio, ei koko historiaa.',

# Namespace 8 related

'allmessages'         => 'Kaikki järjestelmäviestit',
'allmessagesname'     => 'Nimi',
'allmessagesdefault'  => 'Oletusarvo',
'allmessagescurrent'  => 'Nykyinen arvo',
'allmessagestext'     => 'Tämä on luettelo kaikista MediaWiki-nimiavaruudessa olevista viesteistä.',
'allmessagesnotsupportedUI' => 'Special:Allmessages-sivu ei tue täällä käyttöliittymäkieltäsi <b>$1</b>.',
'allmessagesnotsupportedDB' => 'Special:AllMessages-sivu ei ole käytössä, koska wgUseDatabaseMessages-asetus on pois päältä.',

# Thumbnails

'thumbnail-more'      => 'Suurenna',
'missingimage'        => '<b>Puuttuva kuva</b><br /><i>$1</i>',
'filemissing'         => 'Tiedosto puuttuu',

# Special:Import
'import'            => 'Tuo sivuja',
'importinterwiki'   => 'Tuo sivuja lähiwikeistä',
'importtext'        => 'Vie sivuja lähdewikistä käyttäen Special:Export-työkalua. Tallenna tiedot koneellesi ja tallenna ne täällä.',
'importfailed'      => 'Tuonti epäonnistui: $1',
'importnotext'      => 'Tyhjä tai ei tekstiä',
'importsuccess'     => 'Tuonti onnistui!',
'importhistoryconflict' => 'Sivusta on olemassa tuonnin kanssa ristiriitainen muokkausversio. Tämä sivu on saatettu tuoda jo aikaisemmin.',
'importnosources'   => 'Wikienvälisiä tuontilähteitä ei ole määritelty ja suorat historiatallennukset on poistettu käytöstä.',

# Keyboard access keys for power users
'accesskey-search'    => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save'      => 's',
'accesskey-preview'   => 'p',
'accesskey-diff'      => 'd',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search'      => 'Etsi {{GRAMMAR:elative|{{SITENAME}}}} [alt-f]',
'tooltip-minoredit'   => 'Merkitse tämä pieneksi muutokseksi [alt-i]',
'tooltip-save'        => 'Tallenna muokkaukset [alt-s]',
'tooltip-preview'     => 'Esikatsele muokkausta ennen tallennusta [alt-p]',
'tooltip-diff'        => 'Näytä tehdyt muutokset [alt-d]',
'tooltip-compareselectedversions' => 'Vertaile valittuja versioita [alt-v]',
'tooltip-watch'       => 'Lisää tämä sivu tarkkailulistaan [alt-w]',

# Metadata
'nodublincore'        => 'Dublin Core RDF-metatieto on poissa käytöstä tällä palvelimella.',
'nocreativecommons'   => 'Creative Commonsin RDF-metatieto on poissa käytöstä tällä palvelimella.',
'notacceptable'       => 'Wikipalvelin ei voi näyttää tietoja muodossa, jota ohjelmasi voisi lukea.',

# Attribution

'anonymous'           => '{{GRAMMAR:genitive|{{SITENAME}}}} anonyymit käyttäjät',
'siteuser'            => '{{GRAMMAR:genitive|{{SITENAME}}}} käyttäjä $1',
'lastmodifiedby'      => 'Tätä sivua muokkasi viimeksi $2, $1.',
'and'                 => 'ja',
'othercontribs'       => 'Perustuu työlle, jonka teki $1.',
'others'              => 'muut',
'siteusers'           => '{{GRAMMAR:genitive|{{SITENAME}}}} käyttäjä(t) $1',
'creditspage'         => 'Sivun tekijäluettelo',
'nocredits'           => 'Tämän sivun tekijäluettelotietoja ei löydy.',

# Spam protection
#
'spamprotectiontitle' => 'Roskapostisuodatin',
'spamprotectiontext'  => 'Roskapostisuodatin on estänyt sivun tallentamisen. Syynä on todennäköisimmin {{GRAMMAR:genitive|{{SITENAME}}}} ulkopuolelle osoittava linkki.',
'spamprotectionmatch' => 'Teksti, joka ei läpäissyt roskapostisuodatinta: $1',
'subcategorycount'    => 'Tällä luokalla on $1 alaluokkaa.',
'subcategorycount1'   => 'Tällä luokalla on $1 alaluokka.',
'categoryarticlecount'=> 'Tässä luokassa on $1 sivua.',
'categoryarticlecount1' => 'Tässä luokassa on yksi sivu.',
'usenewcategorypage'  => '1\n\nLaita ensimmäiseksi merkiksi nolla, kun et halua käyttää uutta luokittelutyyliä.',
'listingcontinuesabbrev' => ' jatkuu',

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

// HOX
# Patrolling
'markaspatrolleddiff'   => 'Merkitse tarkastetuksi',
'markaspatrolledlink'   => '[$1]',
'markaspatrolledtext'   => 'Merkitse muokkaus tarkastetuksi',
'markedaspatrolled'     => 'Tarkastettu',
'markedaspatrolledtext' => 'Valittu versio on tarkastettu.',
'rcpatroldisabled'      => 'Tuoreiden muutosten tarkastustoiminto ei ole käytössä',
'rcpatroldisabledtext'  => 'Tuoreiden muutosten tarkastustoiminto ei ole käytössä.',

'Monobook.css' => "/* Tätä sivua muokkaamalla voi muokata koko sivuston Monobook-tyyliä */",

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => "/* <pre> */
ta = new Object();
ta['pt-userpage'] = new Array('.','Oma käyttäjäsivu');
ta['pt-anonuserpage'] = new Array('.','IP-osoitteesi käyttäjäsivu');
ta['pt-mytalk'] = new Array('n','Oma keskustelusivu');
ta['pt-anontalk'] = new Array('n','Keskustelu tämän IP-osoitteen muokkauksista');
ta['pt-preferences'] = new Array('','Omat asetukset');
ta['pt-watchlist'] = new Array('l','Lista sivuista, joiden muokkauksia tarkkailet');
ta['pt-mycontris'] = new Array('y','Lista omista muokkauksista');
ta['pt-login'] = new Array('o','Kirjaudu sisään tai luo tunnus');
ta['pt-anonlogin'] = new Array('o','Kirjaudu sisään tai luo tunnus');
ta['pt-logout'] = new Array('o','Kirjaudu ulos');
ta['ca-talk'] = new Array('t','Keskustele sisällöstä');
ta['ca-edit'] = new Array('e','Muokkaa tätä sivua');
ta['ca-addsection'] = new Array('+','Lisää kommentti tälle sivulle');
ta['ca-viewsource'] = new Array('e','Näytä sivun lähdekoodi');
ta['ca-history'] = new Array('h','Sivun aikaisemmat versiot');
ta['ca-protect'] = new Array('=','Suojaa tämä sivu');
ta['ca-delete'] = new Array('d','Poista tämä sivu');
ta['ca-undelete'] = new Array('d','Palauta tämä sivu');
ta['ca-move'] = new Array('m','Siirrä tämä sivu');
ta['ca-watch'] = new Array('w','Lisää tämä sivu tarkkailulistallesi');
ta['ca-unwatch'] = new Array('w','Poista tämä sivu tarkkailulistaltasi');
ta['search'] = new Array('f','Etsi sivu');
ta['p-logo'] = new Array('','Etusivu');
ta['n-mainpage'] = new Array('z','Mene etusivulle');
ta['n-portal'] = new Array('','Keskustelua projektista');
ta['n-currentevents'] = new Array('','Taustatietoa tämänhetkisistä tapahtumista');
ta['n-recentchanges'] = new Array('r','Lista tuoreista muutoksista');
ta['n-randompage'] = new Array('x','Avaa satunnainen sivu');
ta['n-help'] = new Array('','Ohjeita');
ta['n-sitesupport'] = new Array('','Tue sivuston toimintaa');
ta['t-whatlinkshere'] = new Array('j','Lista sivuista, jotka viittavat tänne');
ta['t-recentchangeslinked'] = new Array('k','Viimeisimmät muokkaukset sivuissa, joille viitataan tältä sivulta');
ta['feed-rss'] = new Array('','RSS-syöte tälle sivulle');
ta['feed-atom'] = new Array('','Atom-syöte tälle sivulle');
ta['t-contributions'] = new Array('','Näytä lista tämän käyttäjän muokkauksista');
ta['t-emailuser'] = new Array('','Lähetä sähköpostia tälle käyttäjälle');
ta['t-upload'] = new Array('u','Tallenna kuvia tai muita mediatiedostoja');
ta['t-specialpages'] = new Array('q','Näytä toimintosivut');
ta['t-print']=new Array('', 'Lataa sivun tulostamiseen sopivalla tyylisivulla. Voit aina käyttää suoraan selaimen tulosta-toimintoa.');
ta['t-permalink'] = new Array('', 'Ikuisesti toimiva linkki sivun tähän versioon, paitsi jos sivu poistetaan.');
ta['ca-nstab-main'] = new Array('c','Näytä sisältösivu');
ta['ca-nstab-user'] = new Array('c','Näytä käyttäjäsivu');
ta['ca-nstab-media'] = new Array('c','Näytä mediasivu');
ta['ca-nstab-special'] = new Array('','Tämä on toimintosivu');
ta['ca-nstab-wp'] = new Array('c','Näytä projektisivu');
ta['ca-nstab-image'] = new Array('c','Näytä tiedostosivu');
ta['ca-nstab-mediawiki'] = new Array('c','Näytä järjestelmäviesti');
ta['ca-nstab-template'] = new Array('c','Näytä malline');
ta['ca-nstab-help'] = new Array('c','Näytä ohjesivu');
ta['ca-nstab-category'] = new Array('c','Näytä luokkasivu');
/* </pre> */",

# image deletion
'deletedrevision'     => 'Poistettiin vanha versio $1.',

# browsing diffs
'previousdiff'        => '← Edellinen muutos',
'nextdiff'            => 'Seuraava muutos →',

'imagemaxsize'        => 'Rajoita kuvien koko kuvien kuvaussivuilla arvoon ',
'thumbsize'           => 'Pikkukuvien koko: ',
'showbigimage'        => 'Lataa korkeatarkkuuksinen versio ($1×$2, $3 KiB)',

'newimages'           => 'Uudet kuvat',
'showhidebots'        => '($1 botit)',
'noimages'            => 'Ei uusia kuvia.',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Käyttäjä: ',
'speciallogtitlelabel'=> 'Kohde: ',

'passwordtooshort'    => 'Salasanasi on liian lyhyt. Salasanan pitää olla vähintään $1 merkkiä pitkä.',

# Media Warning
'mediawarning' => '\'\'\'Varoitus\'\'\': Tämä tiedosto saattaa sisältää vahingollista koodia, ja suorittamalla sen järjestelmäsi voi muuttua epäluotettavaksi.
<hr>',

'fileinfo' => '$1 KiB, MIME-tyyppi: <code>$2</code>',

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
'confirmemail_text'   => 'Tämä wiki vaatii sähköpostiosoitteen varmentamisen, ennen kuin voit käyttää sähköpostitoimintoja. Lähetä alla olevasta painikkeesta varmennusviesti osoitteeseesi. Viesti sisältää linkin, jonka avaamalla varmennat sähköpostiosoitteesi.',
'confirmemail_send'   => 'Lähetä varmennusviesti',
'confirmemail_sent'   => 'Varmennusviesi lähetetty.',
'confirmemail_sendfailed' => 'Varmennusviestin lähettäminen epäonnistui. Tarkista, onko osoitteessa kiellettyjä merkkejä.',
'confirmemail_invalid'    => 'Varmennuskoodi ei kelpaa. Koodi on voinut vanhentua.',
'confirmemail_success'    => 'Sähköpostiosoitteesi on nyt varmennettu. Voit kirjautua sisään.',
'confirmemail_loggedin'   => 'Sähköpostiosoitteesti on nyt varmennettu.',
'confirmemail_error'  => 'Jokin epäonnistui varmennnuksen tallentamisessa.',
'confirmemail_subject'=> '{{GRAMMAR:genitive|{{SITENAME}}}} sähköpostiosoitteen varmennus',
'confirmemail_body'   => 'Joku IP-osoitteesta $1 on rekisteröinyt {{GRAMMAR:inessive|{{SITENAME}}}} tunnuksen $2 tällä sähköpostiosoitteella.

Varmenna, että tämä tunnus kuuluu sinulle avamaalla seuraava linkki selaimellasi:

$3

Jos tämä tunnus ei ole sinun, ÄLÄ seuraa linkkiä. Varmennuskoodi vanhenee $4.
',


# Inputbox extension, may be useful in other contexts as well
'tryexact'            => 'Koita tarkkaa osumaa',
'searchfulltext'      => 'Etsi koko tekstiä',
'createarticle'       => 'Luo sivu',

# delete conflict

'deletedwhileediting' => '<center>\'\'\'Varoitus\'\'\': Tämä sivu on poistettu sen jälkeen, kun aloitit sen muokkaamisen!</center>',
'confirmrecreate' => 'Käyttäjä \'\'\'[[{{ns:user}}:$1|$1]]\'\'\' ([[{{ns:user_talk}}:$1|keskustelu]]) poisti sivun sen jälkeen, kun aloit muokata sitä. Syy oli:
: \'\'$2\'\'
Ole hyvä ja varmista, että haluat luoda sivun uudelleen.',
'recreate' => 'Luo uudelleen',
'tooltip-recreate' => '',

'unit-pixel' => ' px',

);


	#--------------------------------------------------------------------------
	# Internationalisation code
	#--------------------------------------------------------------------------

class LanguageFi extends LanguageUtf8 {
	function LanguageFi() {
		global $wgNamespaceNamesFi, $wgMetaNamespace;
		LanguageUtf8::LanguageUtf8();
		$wgNamespaceNamesFi[NS_PROJECT_TALK] = 'Keskustelu_' . $this->convertGrammar( $wgMetaNamespace, 'elative' );
	}

	function getBookstoreList () {
		global $wgBookstoreListFi ;
		return $wgBookstoreListFi ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFi;
		return $wgNamespaceNamesFi;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFi;
		return $wgQuickbarSettingsFi;
	}

	function getSkinNames() {
		global $wgSkinNamesFi;
		return $wgSkinNamesFi;
	}

	function getDateFormats() {
		global $wgDateFormatsFi;
		return $wgDateFormatsFi;
	}

	/**
	 * @access public
	 * @param mixed  $ts the time format which needs to be turned into a
	 *               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param bool   $adj whether to adjust the time output according to the
	 *               user configured offset ($timecorrection)
	 * @param mixed  $format what format to return, if it's false output the
	 *               default one.
	 * @param string $timecorrection the time offset as returned by
	 *               validateTimeZone() in Special:Preferences
	 * @return string
	 */
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$yyyy = substr( $ts, 0, 4 );
		$mm = substr( $ts, 4, 2 );
		$m = 0 + $mm;
		$mmmm = $this->getMonthName( $mm ) . 'ta';
		$dd = substr( $ts, 6, 2 );
		$d = 0 + $dd;

		$datePreference = $this->dateFormat($format);
		switch( $datePreference ) {
			case '3': return "$d.$m.$yyyy";
			case 'ISO 8601': return "$yyyy-$mm-$dd";
			default: return "$d. $mmmm $yyyy";
		}
	}

	/**
	* @access public
	* @param mixed  $ts the time format which needs to be turned into a
	*               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*               user configured offset ($timecorrection)
	* @param mixed  $format what format to return, if it's false output the
	*               default one (default true)
	* @param string $timecorrection the time offset as returned by
	*               validateTimeZone() in Special:Preferences
	* @return string
	*/
	function time( $ts, $adj = false, $format = true, $timecorrection = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$hh = substr( $ts, 8, 2 );
		$mm =  substr( $ts, 10, 2 );
		$ss = substr( $ts, 12, 2 );

		$datePreference = $this->dateFormat($format);
		switch( $datePreference ) {
			case '2':
			case 'ISO 8601': return "$hh:$mm:$ss";
			default: return "$hh.$mm";
		}
	}

	/**
	* @access public
	* @param mixed  $ts the time format which needs to be turned into a
	*               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*               user configured offset ($timecorrection)
	* @param mixed  $format what format to return, if it's false output the
	*               default one (default true)
	* @param string $timecorrection the time offset as returned by
	*               validateTimeZone() in Special:Preferences
	* @return string
	*/
	function timeanddate( $ts, $adj = false, $format = true, $timecorrection = false) {
		$date = $this->date( $ts, $adj, $format, $timecorrection );
		$time = $this->time( $ts, $adj, $format, $timecorrection );

		$datePreference = $this->dateFormat($format);
		switch( $datePreference ) {
			case '3':
			case 'ISO 8601': return "$date $time";
			default: return "$date kello $time";
		}
	}

	function getMessage( $key ) {
		global $wgAllMessagesFi;
		if( isset( $wgAllMessagesFi[$key] ) ) {
			return $wgAllMessagesFi[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	/**
	 * Finnish numeric formatting is 123 456,78.
	 * Notice that the space is non-breaking.
	 */
	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ", " );
	}

	/**
	 * Avoid grouping whole numbers between 0 to 9999
	 */
	function commafy($_) {
		if (!preg_match('/^\d{1,4}$/',$_)) {
			return strrev((string)preg_replace('/(\d{3})(?=\d)(?!\d*\.)/','$1,',strrev($_)));
		} else {
			return $_;
		}
	}

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	function convertGrammar( $word, $case ) {
		# These rules are not perfect, but they are currently only used for site names so it doesn't
		# matter if they are wrong sometimes. Just add a special case for your site name if necessary.
		switch ( $case ) {
			case 'genitive':
				if ( $word == 'Wikisitaatit' ) {
					$word = 'Wikisitaattien';
				} else {
					$word .= 'n';
				}
			break;
			case 'elative':
				if ( $word == 'Wikisitaatit' ) {
					$word = 'Wikisitaateista';
				} else {
					if ( mb_substr($word, -1) == 'y' ) {
						$word .= 'stä';
					} else {
						$word .= 'sta';
					}
				}
				break;
			case 'partitive':
				if ( $word == 'Wikisitaatit' ) {
					$word = 'Wikisitaatteja';
				} else {
					if ( mb_substr($word, -1) == 'y' ) {
						$word .= 'ä';
					} else {
						$word .= 'a';
					}
				}
				break;
			case 'illative':
				# Double the last letter and add 'n'
				# mb_substr has a compatibility function in GlobalFunctions.php
				if ( $word == 'Wikisitaatit' ) {
					$word = 'Wikisitaatteihin';
				} else {
					$word = $word . mb_substr($word,-1) . 'n';
				}
				break;
			case 'inessive':
				if ( $word == 'Wikisitaatit' ) {
					$word = 'Wikisitaateissa';
				} else {
					if ( mb_substr($word, -1) == 'y' ) {
						$word .= 'ssä';
					} else {
						$word .= 'ssa';
					}
				}
				break;

		}
		return $word;
	}

}

?>
