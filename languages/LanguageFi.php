<?php
/** Finnish (Suomi)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

# Revised 2005-04-08 for MediaWiki 1.4.0 -- Nikerabbit

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
# See Language.php for more notes.

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
  'nostalgia'         => 'Nostalgia',
  'cologneblue'       => 'Kölnin sininen',
  'davinci'           => 'DaVinci',
  'mono'              => 'Mono',
  'monobook'          => 'MonoBook',
  'myskin'            => 'Oma tyylisivu',
  'chick'             => 'Chick' // kenties ranskan "chic" 'tyylikäs'? --Mikalaari | Peruskini, ei tällä hetkellä käytössä -- Nikerabbit
);
//myskinin nyk. toiminnallisuus on se, että saa käyttää selaimen "käyttäjän tyylisivu"-toimintoa

/* private */ $wgBookstoreListFi = array(
  'Akateeminen kirjakauppa'       => 'http://www.akateeminen.com/search/tuotetieto.asp?tuotenro=$1',
  'Bookplus'                      => 'http://www.bookplus.fi/product.php?isbn=$1',
  'Helsingin yliopiston kirjasto' => 'http://pandora.lib.hel.fi/cgi-bin/mhask/monihask.py?volname=&author=&keyword=&ident=$1&submit=Hae&engine_helka=ON',
  'Pääkaupunkiseudun kirjastot'   => 'http://www.helmet.fi/search*fin/i?SEARCH=$1',
  'Tampereen seudun kirjastot'    => 'http://pandora.lib.hel.fi/cgi-bin/mhask/monihask.py?volname=&author=&keyword=&ident=$1-1&lang=kaikki&mat_type=kaikki&submit=Hae&engine_tampere=ON'
) + $wgBookstoreListEn;

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------


# Current practices (may be changed if not good ones)
# Refer namespaces with the English name or 'Project' in case of project namespace
# Avoid any hard coded references to any particular subject which may not apply everywhere, e.g. artikkeli, wikipedia
# Don't use participial phrases (lauseenkastikkeita) incorrectly
# Avoid unnecessary parenthesis, quotes and html code
#

# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex
/* private */ $wgAllMessagesFi = array(
'special_version_prefix'  => ' ',
'special_version_postfix' => ' ',
# User preference toggles
'tog-underline'       => 'Alleviivaa linkit',
'tog-highlightbroken' => 'Näytä linkit puuttuville sivuille <a href="" class="new">näin </a> (vaihtoehtoisesti näin: <a href="" class="internal">?</a>).',
'tog-justify'         => 'Tasaa kappaleet',
'tog-hideminor'       => 'Piilota pienet muutokset tuoreet muutokset -listasta',
'tog-usenewrc'        => 'Kehittynyt tuoreet muutokset -listaus. Tämä ei toimi kaikilla selaimilla.',
'tog-numberheadings'  => 'Numeroi otsikot',
'tog-showtoolbar'     => 'Näytä työkalupalkki',
'tog-editondblclick'  => 'Muokkaa sivuja kaksoisnapsautuksella (JavaScript)',
'tog-editsection'     => 'Näytä muokkauslinkit jokaisen kappaleen yläpuolella',
'tog-editsectiononrightclick' => 'Muokkaa kappaleita otsikon oikealla hiirennapsautuksella (JavaScript)',
'tog-showtoc'         =>'Näytä sisällysluettelo sivuille, joilla yli 3 otsikkoa',
'tog-rememberpassword'=> 'Älä kysy salasanaa saman yhteyden eri istuntojen välillä',
'tog-editwidth'       => 'Muokkauskenttä on sivun levyinen',
'tog-watchdefault'    => 'Lisää oletuksena uudet ja muokatut sivut tarkkailulistalle',
'tog-minordefault'    => 'Muutokset ovat oletuksena pieniä',
'tog-previewontop'    => 'Näytä esikatselu muokkauskentän yläpuolella',
'tog-previewonfirst'  => 'Näytä esikatselu heti, kun muokkaus aloitetaan',
'tog-nocache'         => 'Älä tallenna sivuja välimuistiin',
'tog-fancysig'        => 'Muotoilematon allekirjoitus ilman automaattista linkkiä',

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
'jan'         => 'tammi',
'feb'         => 'helmi',
'mar'         => 'maalis',
'apr'         => 'huhti',
'may'         => 'touko',
'jun'         => 'kesä',
'jul'         => 'heinä',
'aug'         => 'elo',
'sep'         => 'syys',
'oct'         => 'loka',
'nov'         => 'marras',
'dec'         => 'joulu',

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
'wikititlesuffix'     => '{{SITENAME}}',
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
'history'             => 'Vanhemmat versiot',
'history_short'       => 'Historia',
'info_short'          => 'Tiedostus',
'printableversion'    => 'Tulostettava versio',
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
'gnunote'             => 'Kaikki teksti on saatavilla <a class=internal href="$wgScriptPath/GNU_FDL">GNU Free Documentation -lisenssin</a> ehdoilla.',
'printsubtitle'       => '(Lähde: {{SERVER}})',
'protectedpage'       => 'Suojattu sivu',
'administrators'      => 'Project:Ylläpitäjät',
'sysoptitle'          => 'Vaatii ylläpitäjäoikeudet',
'sysoptext'           => 'Tämän toiminnon voi suorittaa vain käyttäjä, jolla on ylläpitäjäoikeudet. Katso $1.',
'developertitle'      => 'Ohjelmiston kehittäjän oikeuksia vaaditaan',
'developertext'       => 'Yrittämäsi toiminnon voi suorittaa vain henkilö, jolla on ohjelmistokehittäjänoikeudet. Katso $1.',
'bureaucrattitle'     => 'Tämän toiminnon suorittamiseen tarvitaan byrokraattioikeudet',
'bureaucrattext'      => 'Tämän toiminnon voivat suorittaa vain ylläpitäjät, joilla on byrokraattioikeudet.',
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
'restorelink'         => '$1 poistettua muokkausta',
'feedlinks'           => 'Uutissyötteet:',
'sitenotice'          => '', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'          => 'Sivu',
'nstab-user'          => 'Käyttäjäsivu',
'nstab-media'         => 'Media',
'nstab-special'       => 'Toiminto',
'nstab-wp'            => 'Projektisivu',
'nstab-image'         => 'Kuva',
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
'laggedslave'         => 'Varoitus: tämä sivu saattaa olla vanhentunut.',
'readonly'            => 'Tietokanta on lukittu',
'enterlockreason'     => 'Anna lukituksen syy sekä sen arvioitu poistamisaika',
'readonlytext'        => '{{GRAMMAR:genitive|{{SITENAME}}}} tietokanta on tällä hetkellä lukittu. Uusia sivuja ei voi luoda eikä muitakaan muutoksia tehdä. Syynä ovat todennäköisimmin rutiininomaiset tietokannan ylläpitotoimet. Tietokannan lukinneen ylläpitäjän selitys: <p>$1',
'missingarticle'      => 'Tietokannasta ei löytynyt sivua <b>$1</b>. Koita hetken päästä uudelleen. Jos ongelma ei katoa, ota yhteyttä ylläpitäjään ja anna mukaan tämän sivun URL-osoite.',
'internalerror'       => 'Sisäinen virhe',
'filecopyerror'       => 'Tiedostoa <b>$1</b> ei voitu kopioida tiedostoksi <b>$2</b>.',
'filerenameerror'     => 'Tiedostoa <b>$1</b> ei voitu nimetä uudelleen nimellä <b>$2</b>.',
'filedeleteerror'     => 'Tiedostoa <b>$1</b> ei voitu poistaa.',
'filenotfound'        => 'Tiedostoa <b>$1</b> ei löytynyt.',
'unexpected'          => 'Odottamaton arvo: "$1"="$2".',
'formerror'           => 'Virhe: lomaketta ei voitu lähettää',
'badarticleerror'     => 'Toimintoa ei voi suorittaa tälle sivulle.',
'cannotdelete'        => 'Määriteltyä sivua tai kuvaa ei voitu poistaa. Joku muu on saattanut poistaa sen.',
'badtitle'            => 'Virheellinen otsikko',
'badtitletext'        => 'Pyytämäsi sivuotsikko oli virheellinen, tyhjä tai väärin linkitetty kieltenvälinen tai wikienvälinen otsikko.',
'perfdisabled'        => 'Pahoittelut! Tämä ominaisuus ei toistaiseksi ole käytetössä, sillä se hidastaa tietokantaa niin paljon, että kukaan ei voi käyttää wikiä. Toiminto ohjelmoidaan tehokkaammaksi lähiaikoina. (Sinäkin voit tehdä sen! Tämä on vapaa ohjelmisto.)',
'perfdisabledsub'     => 'Tässä on tallennettu kopio $1', # obsolete? ei ole
'perfcached'          => 'Seuraava data on tuotu välimuistista, eikä se ole välttämättä ajan tasalla.',
'wrong_wfQuery_params'=> 'Virheelliset parametrit wfQuery()<br />Funktio: $1<br />Tiedustelu: $2',
'viewsource'          => 'Lähdekoodi',
'protectedtext'       => '<big>\'\'\'Tämä sivu on suojattu muutoksilta\'\'\'</big>
* [[Talk:{{PAGENAME}}|Keskustele tästä sivusta]] muiden kanssa
<p style="border-top:1px solid #ccc; margin-top:1.5em; padding-top:.5em">Sivun lähdekoodi:</p>',
'seriousxhtmlerrors'  => 'XHTML-merkkauskielessä havaittiin vakavia virheitä.',

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
'loginproblem'        => '<b>Sisäänkirjautuminen ei onnistunut.</b><br />Yritä uudelleen!',
'alreadyloggedin'     => '<font color=red><b>Käyttäjä $1, olet jo kirjautunut sisään!</b></font><br />\n',
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
'yournick'            => 'Nimimerkki (allekirjoituksia varten)',
'emailforlost'        => '',
'prefs-help-userdata' => '* <strong>Sähköposti</strong> (valinnainen): Ihmiset voivat ottaa yhteyttä sinuun sivuston kautta ilman, että sähköpostiosoitteesi paljastuu lähettäjälle. Myös unohtunut salasana voidaan lähettää sähköpostiisi.',
'loginerror'          => 'Sisäänkirjautumisvirhe',
'nocookiesnew'        => 'Käyttäjä luotiin, mutta et ole kirjautunut sisään. {{SITENAME}} käyttää evästeitä sisäänkirjautumisen yhteydessä. Selaimesi ei salli evästeistä. Kytke ne päälle, ja sitten kirjaudu sisään juuri luomallasi käyttäjänimellä ja salasanalla.',
'nocookieslogin'      => '{{SITENAME}} käyttää evästeitä sisäänkirjautumisen yhteydessä. Selaimesi ei salli evästeitä. Ota ne käyttöön, ja yritä uudelleen.',
'noname'              => 'Et ole määritellyt kelvollista käyttäjänimeä.',
'loginsuccesstitle'   => 'Sisäänkirjoittautuminen onnistui',
'loginsuccess'        => 'Olet kirjautunut käyttäjänä $1.',
'nosuchuser'          => 'Käyttäjää <strong>$1</strong> ei ole olemassa. Tarkista kirjoititko nimen oikein, tai käytä alla olevaa lomaketta uuden käyttäjätunnuksen luomiseksi.', // TODO NOWIKIMARKUP
'wrongpassword'       => 'Syöttämäsi salasana ei ole oikein. Ole hyvä ja yritä uudelleen.',
'nosuchusershort'     => 'Käyttäjää nimellä <b>$1</b> ei ole. Kirjoititko nimen oikein?', // TODO NO WIKIMARKUP
'mailmypassword'      => 'Lähetä minulle uusi salasana sähköpostilla',
'passwordremindertitle' => 'Salasanamuistutus {{GRAMMAR:elative|{{SITENAME}}}}',
// merkki
'passwordremindertext'=> 'Joku IP-osoitteesta $1 pyysi {{GRAMMAR:partitive|{{SITENAME}}}} lähettämään uuden salasanan. Salasana käyttäjälle $2 on nyt $3. Kirjaudu sisään ja vaihda salasanasi.',
'noemail'             => 'Käyttäjälle \'\'\'$1\'\'\' ei ole määritelty sähköpostiosoitetta.',
'passwordsent'        => 'Uusi salasana on lähetetty käyttäjän <b>$1</b> sähköpostiosoitteeseen.',
'loginend'            => '\'\'\'Rekisteröidäksesi käyttäjätunnuksen:\'\'\'
#<small>Valitse itsellesi käyttäjätunnus ja kirjoita se \'\'käyttäjätunnus\'\'-kenttään.</small>
#<small>Valitse salasana ja kirjoita se sekä \'\'salasana\'\'- että \'\'salasana uudelleen\'\' -kenttiin.</small>
#<small>Halutessasi voit kirjoittaa sähköpostiosoitteesi \'\'sähköpostiosoite\'\'-kenttään. Jos annat sähköpostiosoitteesi, muut käyttäjät voivat lähettää sinulle sähköpostia saamatta osoitettasi tietoonsa, ja voit pyytää uuden salasanan, mikäli satut unohtamaan salasanasi.</small>

\'\'\'Kirjautuaksesi sisään:\'\'\'
*<small>Syötä käyttäjätunnuksesi ja salasanasi.</small>

<small>Huomaa, että {{GRAMMAR:illative|{{SITENAME}}}} kirjautuminen edellyttää evästeiden käyttöä.</small>',
'mailerror'           => 'Virhe lähetettäessä sähköpostia: $1',
'acct_creation_throttle_hit' => 'Olet jo luonut $1 tunnusta. Et voi luoda uutta.',


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
'image_tip'           => 'Sisäinen kuva',
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
'loginreqtext'        => 'Sinun täytyy [[Special:Userlogin|kirjautua sisään]], jotta voisit nähdä muut sivut.',

'accmailtitle'        => 'Salasana lähetetty.',
'accmailtext'         => 'käyttäjän \'\'\'$1\'\'\' salasana on lähetetty osoitteeseen \'\'\'$2\'\'\'.',
'newarticle'          => '(uusi)',
'newarticletext'      => 'Linkki toi sivulle, jota ei vielä ole. Voit luoda sivun kirjoittamalla alla olevaan tilaan. Jos et halua luoda sivua, käytä selaimen paluutoimintoa.',
'talkpagetext'        => '<!-- MediaWiki:talkpagetext -->',
'anontalkpagetext'    => '----\'\'Tämä on nimettömän käyttäjän keskustelusivu. Hän ei ole joko luonut itselleen käyttäjätunnusta tai ei käytä sitä. Siksi hänet tunnistetaan nyt numeerisella IP-osoitteella. Kyseinen IP-osoite voi olla useamman henkilön käytössä. Jos olet nimetön käyttäjä, ja sinusta tuntuu, että aiheettomia kommentteja on ohjattu sinulle, [[Special:Userlogin|luo itsellesi käyttäjätunnus tai kirjaudu sisään]] välttääksesi jatkossa sekaannukset muiden nimettömien käyttäjien kanssa.\'\'',
'noarticletext'       => '<big>\'\'\'{{GRAMMAR:inessive|{{SITENAME}}}} ei ole tämän nimistä sivua.\'\'\'</big>
* Voit kirjoittaa uuden sivun \'\'\'[http:{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} {{PAGENAME}}].\'\'\'
* Jos olet luonut sivun tällä nimellä, se on saatettu poistaa — katso [[Special:Log/delete|poistoloki]].',
'clearyourcache'      => '\'\'\'Huomautus:\'\'\' Selaimen välimuisti pitää tyhjentää asetusten tallentamisen jälkeen, jotta muutokset tulisivat voimaan. Tallenna kaikki sivut ennen tyhjentämistä.<br />Koita seuraavia näppäinyhdistelmiä: \'\'\'Mozilla:\'\'\' \'\'ctrl-r\'\', \'\'\'IE ja Opera:\'\'\' \'\'ctrl-f5\'\', \'\'\'Safari:\'\'\' \'\'cmd-r\'\', \'\'\'Konqueror\'\'\' \'\'ctrl-r\'\'.',
'usercssjsyoucanpreview' => '\'\'\'Vinkki:\'\'\' Käytä esikatselupainiketta testataksesi uutta CSS:ää tai JavaScriptiä ennen tallennusta.',
'usercsspreview'      => '\'\'\'Tämä on vasta CSS:n testaus ja esikatselu.\'\'\'',
'userjspreview'       => '\'\'\'Tämä on vasta JavaScriptin testaus ja esikatselu.\'\'\'',
'updated'             => '(Päivitetty)',
'note'                => 'Huomautus: ', // TODO: NO WIKI MARKUP
'previewnote'         => 'Tämä on vasta sivun esikatselu. Sivua ei ole vielä tallennettu!',
'previewconflict'     => 'Tämä esikatselu näyttää miltä muokkausalueella oleva teksti näyttää tallennettuna.',
'editing'             => 'Muokataan sivua $1',
'sectionedit'         => ' (lohko)',
'commentedit'         => ' (kommentti)',
'editingsection'      => 'Muokataan osiota $1',
'editingcomment'      => 'Muokataan kommenttia sivulla $1',
'editconflict'        => 'Päällekkäinen muokkaus: $1',
'explainconflict'     => 'Joku muu on muuttanut tätä sivua sen jälkeen, kun aloit muokata sitä. Ylempi tekstialue sisältää tämänhetkisen tekstin. Tekemäsi muutokset näkyvät alemmassa ikkunassa. Sinun täytyy yhdistää muutoksesi olemassa olevaan tekstiin. \'\'\'Vain\'\'\' ylemmässä alueessa oleva teksti tallentuu, kun tallennat sivun.',
'yourtext'            => 'Oma tekstisi',
'storedversion'       => 'Tallennettu versio',
'nonunicodebrowser'   => '\'\'\'Varoitus: Selaimesi ei ole Unicode-yhteensopiva. Ole hyvä ja vaihda selainta, ennen kuin muokkaat sivua.\'\'\'',
'editingold'          => '<center style="font-weight:bold">VAROITUS: Olet muokkaamassa vanhaa versiota tämän sivun tekstistä. Jos tallennat sen, kaikki tämän version jälkeen tehdyt muutokset katoavat.</center>', # TODO: NOWIKIMARKUP
'yourdiff'            => 'Eroavaisuudet',
'copyrightwarning'    => '<strong>Muutoksesi astuvat voimaan välittömästi.</strong> Jos haluat harjoitella muokkaamista, ole hyvä ja käytä [[Project:Hiekkalaatikko|hiekkalaatikkoa]].<br/><br/>Kaikki {{GRAMMAR:illative|{{SITENAME}}}} tehtävät tuotokset katsotaan julkaistuksi GNU Free Documentation -lisenssin mukaisesti ([[Project:{{SITENAME}} ja tekijänoikeudet|lisätietoja]]). Jos et halua, että kirjoitustasi muokataan armottomasti ja uudelleenkäytetään vapaasti, älä tallenna kirjoitustasi. Tallentamalla muutoksesi lupaat, että kirjoitit tekstisi itse, tai kopioit sen jostain vapaasta lähteestä. <strong>ÄLÄ KÄYTÄ TEKIJÄNOIKEUDEN ALAISTA MATERIAALIA ILMAN LUPAA!</strong>',
'copyrightwarning2'   => '<br />Huomaa, että kuka tahansa voi muokata, muuttaa ja poistaa kaikkia sivustolle tekemiäsi lisäyksiä ja muutoksia. Muokkaamalla sivustoa luovutat sivuston käyttäjille tämän oikeuden ja takaat, että lisäämäsi aineisto on joko itse kirjoittamaasi tai peräisin jostain vapaasta lähteestä. <strong>TEKIJÄNOIKEUDEN ALAISEN MATERIAALIN KÄYTTÄMINEN ILMAN LUPAA ON EHDOTTOMASTI KIELLETTYÄ!</strong>',
'longpagewarning'     => '<center>Tämän sivun tekstiosuus on $1 binäärikilotavua pitkä. Harkitse, voisiko sivun jakaa pienempiin osiin.</center>',
'readonlywarning'     => 'VAROITUS: Tietokanta on lukittu huoltoa varten, joten voi olla ettet pysty tallentamaan muokkauksiasi juuri nyt. Saattaa olla paras leikata ja liimata tekstisi omaan tekstitiedostoosi ja tallentaa se tänne myöhemmin.',
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
'previousrevision'    => '? Vanhempi versio',
'nextrevision'        => 'Uudempi versio ?',
'currentrevisionlink' => 'Näytä nykyinen versio',
'cur'                 => 'nyk.',
'next'                => 'seur.',
'last'                => 'edell.',
'orig'                => 'alkup.',
'histlegend'          => 'Merkinnät: (nyk.) = eroavaisuudet nykyiseen versioon, (edell.) = eroavaisuudet edelliseen versioon, <b>p</b> = pieni muutos', // TODO NO WIKIMARKUP
'history_copyright'   => '-',

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
'badquerytext'        => 'Tekemääsi kyselyä ei ole kelvollinen. Tämä johtuu todennäköisesti siitä, että et ole määritellyt hakumerkkijonoa.',
'matchtotals'         => 'Haulla \'\'\'$1\'\'\' löytyi $2 osumaa sivujen otsikoista ja $3 osumaa sivujen sisällöistä.',
'nogomatch'           => '<big><strong>Täsmälleen tällä otsikolla ei ole sivua.</strong></big><br />Voit <strong><a href="$1" class="new">luoda aiheesta uuden sivun</a></strong> tai <a href="http:{{localurle:Project:Toiveet}}">lisätä sen toivottujen sivujen listaan</a>.<br /><small>Etsi ensin vastaavaa sivua, joka voi olla kirjoitusasultaan hieman erilainen</small><br /><br />', // TODO NO WIKIMARKUP
'titlematches'        => 'Osumat sivujen otsikoissa',
'notitlematches'      => 'Hakusanaa ei löytynyt minkään sivun otsikosta',
'textmatches'         => 'Osumat sivujen teksteissä',
'notextmatches'       => 'Hakusanaa ei löytynyt sivujen teksteistä',
'prevn'               => '? $1 edellistä',
'nextn'               => '$1 seuraavaa ?',
'viewprevnext'        => 'Näytä [$3] kerralla.<br />$1 | $2',
'showingresults'      => '<b>$1</b> tulosta tuloksesta <b>$2</b> alkaen.',
'showingresultsnum'   => 'Alla on <b>$3</b> hakutulosta alkaen <b>$2.</b> tuloksesta.',
'nonefound'           => '\'\'\'Huomautus\'\'\': Epäonnistuneet haut johtuvat usein hyvin yleisten sanojen, kuten \'\'on\'\' ja \'\'ei\'\', etsimisestä tai useamman kuin yhden hakutermin määrittelemisestä. Vain sivut, joilla on kaikki hakutermin sanat, näkyvät tuloksissa.',
'powersearch'         => 'Etsi',
'powersearchtext'     => '
Haku nimiavaruuksista:<br />
$1<br />
$2 Luettelo uudelleenohjauksista<br />Etsi $3 $9',
'searchdisabled'       => '<p style="margin: 1.5em 2em 1em">Tekstihaku on poistettu toistaiseksi käytöstä suuren kuorman vuoksi. Voit käyttää alla olevaa Googlen hakukenttää sivujen etsimiseen, kunnes haku tulee taas käyttöön.<small>Huomaa, että ulkopuoliset kopiot {{GRAMMAR:genitive|{{SITENAME}}}} sisällöstä eivät välttämättä ole ajan tasalla.</small></p>',
'blanknamespace'      => '(Oletusnimiavaruus)',

# Preferences page
#
'preferences'         => 'Asetukset',
'prefsnologin'        => 'Et ole kirjautunut sisään',
'prefsnologintext'    => 'Sinun täytyy [[Special:Userlogin|kirjautua sisään]], jotta voisit muuttaa asetuksia.', // TODO: NO WIKIMARKUP
'prefslogintext'      => 'Olet kirjautunut sisään käyttäjänä \'\'\'$1\'\'\'. Sisäinen tunnistenumerosi on \'\'\'$2\'\'\'.',
'prefsreset'          => 'Asetukset on palautettu talletettujen mukaisiksi.',
'qbsettings'          => 'Pikavalikon asetukset',
'qbsettingsnote'      => 'Tämä asetus toimii ainostaan ulkoasutyyleillä <b>Perus</b> ja <b>Kölnin sininen</b>.',
'changepassword'      => 'Vaihda salasanaa',
'skin'                => 'Ulkonäkö',
'math'                => 'Matematiikan näyttäminen',
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
'prefs-rc'            => 'Tuoreet muutokset ja tynkien näyttö',
'prefs-misc'          => 'Muut asetukset',
'saveprefs'           => 'Tallenna asetukset',
'resetprefs'          => 'Palauta alkuperäiset asetukset',
'oldpassword'         => 'Vanha salasana',
'newpassword'         => 'Uusi salasana',
'retypenew'           => 'Uusi salasana uudelleen',
'textboxsize'         => 'Muokkauskenttä',
'rows'                => 'Rivit',
'columns'             => 'Sarakkeet',
'searchresultshead'   => 'Hakutulosten asetukset',
'resultsperpage'      => 'Tuloksia sivua kohti',
'contextlines'        => 'Rivien määrä tulosta kohti',
'contextchars'        => 'Sisällön merkkien määrä riviä kohden',
'stubthreshold'       => 'Tynkäsivun osoituskynnys',
'recentchangescount'  => 'Otsikoiden määrä viimeisimmissä muutoksissa',
'savedprefs'          => 'Asetuksesi tallennettiin.',
'timezonelegend'      => 'Aikavyöhyke',
'timezonetext'        => 'Paikallisen ajan ja palvelimen ajan (UTC) välinen aikaero tunteina.',
'localtime'           => 'Paikallinen aika',
'timezoneoffset'      => 'Aikaero',
'servertime'          => 'Palvelimen aika',
'guesstimezone'       => 'Utele selaimelta',
'emailflag'           => 'Estä sähköpostin lähetys osoitteeseen',
'defaultns'           => 'Etsi oletusarvoisesti näistä nimiavaruuksista:',
'default'             => 'oletus',

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
'sectionlink'         => '?',

# Upload
#
'upload'              => 'Tallenna tiedosto',
'uploadbtn'           => 'Tallenna tiedosto',
'uploadlink'          => 'Tallenna kuvia',
'reupload'            => 'Uusi tallennus',
'reuploaddesc'        => 'Paluu tallennuslomakkeelle.',
'uploadnologin'       => 'Et ole kirjaunut sisään',
'uploadnologintext'   => 'Sinun pitää olla [[Special:Userlogin|kirjautuneena sisään]], jotta voisit tallentaa tiedostoja.', // TODO NO WIKIMARKUP
'uploadfile'          => 'Tallenna tiedosto',
'uploaderror'         => 'Tallennusvirhe',
'uploadtext'          => '\'\'\'SEIS!\'\'\' Ennen kuin tallennat tiedostoja {{GRAMMAR:illative|{{SITENAME}}}}, tutustu [[Project:Kuvien_käyttösäännöt|kuvien käyttösääntöihin]] ja noudata niitä.
*\'\'Kirjoita kuvan tietoihin tarkka tieto kuvan lähteestä.\'\' Jos teit kuvan itse, sano se. Jos löysit kuvan Internetistä, laita mukaan linkki kyseiselle sivulle.
*\'\'Kerro kuvan tekijänoikeuksien tila.\'\'
*\'\'Käytä järkevää tiedostonimeä.\'\' Nimeä tiedostosi mieluummin tyyliin ”Eiffel-torni Pariisissa, yökuva.jpg” kuin ”etpan1024c.jpg”. Näin vältät mahdollisesti jo olemassa olevan kuvan korvaamisen omallasi. Voit etsiä olemassaolevia kuvia [[Special:Imagelist|kuvaluettelosta]].
*Laita johonkin aiheeseen liittyvään sivuun linkki kyseiseen tiedostoon, tai kirjoita kuvaussivulle kuvaus tiedoston sisällöstä.
*Jos haluat nähdä tai etsiä aiemmin tallennettuja kuvia, katso [[Special:Imagelist|luetteloa tallennetuista kuvista]]. Tallennukset ja poistot kirjataan [[Special:Log/upload|tallennuslokiin]].

Suositellut kuvaformaatit ovat JPEG valokuville, PNG piirroksille ja kuvakkeille ja Ogg Vorbis äänille. Nimeä tiedostosi kuvaavasti välttääksesi sekaannuksia. Voit liittää kuvan sivulle käyttämällä seuraavan muotoista merkintää \'\'\'<nowiki>[[Kuva:tiedosto.jpg]]</nowiki>\'\'\' tai \'\'\'<nowiki>[[Kuva:tiedosto.png|kuvausteksti]]</nowiki>\'\'\' tai \'\'\'<nowiki>[[media:tiedosto.ogg]]</nowiki>\'\'\' äänille.

Huomaa, että {{GRAMMAR:inessive|{{SITENAME}}}} muut voivat muokata tai poistaa tallentamasi tiedoston, jos he katsovat, että se ei palvele projektin tarpeita. Tallentamismahdollisuutesi voidaan estää, jos käytät järjestelmää väärin.',
'uploadlog'           => 'Tallennusloki',
'uploadlogpage'       => 'Tallennusloki',
'uploadlogpagetext'   => 'Alla on luettelo uusimmista tallennuksista. Kaikki ajat näytetään palvelimen aikavyöhykkeessä (UTC).',
'filename'            => 'Tiedoston nimi',
'filedesc'            => 'Yhteenveto',
'filestatus'          => 'Tiedoston tekijänoikeudet',
'filesource'          => 'Lähde',
'affirmation'         => 'Lupaan, että tämän tiedoston tekijänoikeuksien haltija sallii sen käytön {{GRAMMAR:inessive|{{SITENAME}}}}.', // TODO: has $1
'copyrightpage'       => 'Project:Tekijänoikeudet',
'copyrightpagename'   => '{{SITENAME}} ja tekijänoikeudet',
'uploadedfiles'       => 'Tallennetut tiedostot',
'noaffirmation'       => 'Vahvista, ettei lähettämäsi tiedosto riko tekijänoikeuksia.',
'ignorewarning'       => 'Jätä tämä varoitus huomiotta, ja tallenna tiedosto.',
'minlength'           => 'Kuvan nimessä pitää olla vähintään kolme merkkiä.',
'illegalfilename'     => 'Tiedoston nimessä \'\'\'$1\'\'\' on merkkejä, joita ei sallita sivujen nimissä. Vaihda tiedoston nimeä, ja yritä tallentamista uudelleen.',
'badfilename'         => 'Kuva on siirretty nimelle $1.',
'badfiletype'         => '".$1" ei ole suositeltava tiedostomuoto.',
'largefile'           => 'Kuvien ei tulisi olla yli 100 kilotavun kokoisia.',
'emptyfile'           => 'Tiedosto, jota yritit tallentaa näyttäisi olevan tyhjä. Tarkista, että kirjoitit polun ja nimen oikein.',
'fileexists'          => 'Tämän niminen tiedosto on jo olemassa. Tarkista $1, ellet ole varma, että haluat muuttaa sitä.',
'successfulupload'    => 'Tallennus onnistui',
'fileuploaded'        => 'Tiedosto \'\'\'$1\'\'\' on tallennettu onnistuneesti. Seuraa linkkiä ($2) kuvaussivulle, ja täytä kuvaan liityvät tiedot, kuten mistä se on peräisin, milloin se on luotu, kuka sen loi ja mahdollisesti muita tietämiäsi tietoja. Jos tiedosto on kuva, voit lisätä sen sivulle näin: <tt>[[Kuva:$1|thumb|Kuvaus]]</tt>',
'uploadwarning'       => 'Tallennusvaroitus',
'savefile'            => 'Tallenna',
'uploadedimage'       => 'tallensi tiedoston [[$1]]', // TODO CHECK ME
'uploaddisabled'      => 'Tiedostojen lähettäminen on poissa käytöstä.',
'uploadcorrupt'       => 'Tiedosto on vioittunut tai sillä on väärä tiedostopääte. Tarkista tiedosto ja lähetä se uudelleen.',

# Image list
#
'imagelist'           => 'Luettelo kuvista',
'imagelisttext'       => 'Alla on $1 kuvan luettelo lajiteltuna $2.',
'getimagelist'        => 'noudetaan kuvaluettelo',
'ilshowmatch'         => 'Haku kuvista',
'ilsubmit'            => 'Hae',
'showlast'            => 'Näytä viimeiset $1 kuvaa lajiteltuna $2.',
'byname'              => 'nimen mukaan',
'bydate'              => 'päiväyksen mukaan',
'bysize'              => 'koon mukaan',
'imgdelete'           => 'poista',
'imgdesc'             => 'kuvaus',
'imglegend'           => 'Merkinnät: (kuvaus) = näytä/muokkaa kuvan kuvausta.',
'imghistory'          => 'Kuvan historia',
'revertimg'           => 'palauta',
'deleteimg'           => 'poista',
'deleteimgcompletely' => 'poista',
'imghistlegend'       => 'Merkinnät: (nyk.) = nykyinen kuva, (poista) = poista tämä vanha versio, (palauta) = palauta kuva tähän vanhaan versioon.<br />Napsauta päiväystä nähdäksesi silloin tallennettu kuva.',
'imagelinks'          => 'Kuvalinkit',
'linkstoimage'        => 'Seuraavilta sivuilta on linkki tähän kuvaan:',
'nolinkstoimage'      => 'Tähän kuvaan ei ole linkkejä miltään sivulta.',
'sharedupload'        => 'Tämä tiedosto on jaettu ja muut projektit saattavat käyttää sitä.',
'shareduploadwiki'    => 'Katso [$1 kuvan kuvaussivulta] lisätietoja.',

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
'disambiguations'     => 'Tarkennussivu',
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
'unusedimages'        => 'Käyttämättömät kuvat',
'popularpages'        => 'Suositut sivut',
'nviews'              => '$1 latausta',
'wantedpages'         => 'Halutut sivut',
'nlinks'              => '$1 linkkiä',
'allpages'            => 'Kaikki sivut',
'randompage'          => 'Satunnainen sivu',
'shortpages'          => 'Lyhyet sivut',
'longpages'           => 'Pitkät sivut',
'deadendpages'        => 'Sivut, joilta ei linkkejä',
'listusers'           => 'Käyttäjälista',
'listadmins'          => 'Ylläpitäjälista',
'specialpages'        => 'Toimintosivut',
'spheading'           => 'Toimintosivut',

/* Special page sections */
'asksqlpheading'        => 'Tietokantakyselyt',
'blockpheading'         => 'Esto',
'createaccountpheading' => 'Tunnuksen luominen',
'deletepheading'        => 'Sivujen poisto',
'userrightspheading'    => 'Käyttöoikeudet',
'grouprightspheading'   => 'Ryhmät', // TODO: Check me! (2005-03-17)
'siteadminpheading'     => 'Sivuston ylläpito',

'protectpage'         => 'Suojaa sivu',
'recentchangeslinked' => 'Linkitettyjen sivujen muutokset',
'rclsub'              => 'Sivut, joihin linkki sivulta $1',
'debug'               => 'Virheenetsintä',
'newpages'            => 'Uudet sivut',
'ancientpages'        => 'Kauan muokkaamattomat sivut',
'intl'                => 'Kieltenväliset linkit',
'move'                => 'Siirrä',
'movethispage'        => 'Siirrä tämä sivu',
'unusedimagestext'    => 'Huomaa, että muut verkkosivut saattavat viitata kuvaan suoran URL:n kautta, jolloin kuva saattaa olla tässä listassa, vaikka sitä käytetäänkin.',
'booksources'         => 'Kirjalähteet',
'categoriespagetext'  => '{{GRAMMAR:inessive|{{SITENAME}}}} on seuraavat luokat:',
'data'                => 'Data', // TODO: CHECK ME
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
'articlenamespace'    => '(sivut)',
'allpagesformtext'    => 'Näytä sivuja alkaen sivusta: $1 Valitse nimiavaruus: $2 $3',
'allarticles'         => 'Kaikki sivut',
'allpagesprev'        => 'Edellinen',
'allpagesnext'        => 'Seuraava',
'allinnamespace'   => 'Kaikki sivut nimiavaruudessa $1',
'allpagessubmit'      => 'Mene',

# Email this user
#
'mailnologin'         => 'Lähettäjän osoite puuttuu',
'mailnologintext'     => 'Sinun pitää olla [[Special:Userlogin|kirjautuneena sisään]] ja [[Special:Preferences|asetuksissasi]] pitää olla toimiva sähköpostiosoite jotta voit lähettää sähköpostia muille käyttäjille.',
'emailuser'           => 'Lähetä sähköpostia tälle käyttäjälle',
'emailpage'           => 'Lähetä sähköpostia käyttäjälle',
'emailpagetext'       => 'Jos tämä käyttäjä on antanut asetuksissaan kelvollisen sähköpostiosoitteen, alla olevalla lomakeella voi lähettää yhden viestin hänelle. Omissa asetuksissasi annettu sähköpostiosoite näkyy sähköpostin lähettäjän osoitteena, jotta vastaanottaja voi vastata viestiin.',
'usermailererror'     => 'Postitus palautti virheen: ',
'defemailsubject'     => '{{SITENAME}} e-mail',
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
'watchnologintext'    => 'Sinun pitää kirjautua sisään, jotta voisit käyttää tarkkailulistaa.', // TODO NO WIKIMARKUP
'addedwatch'          => 'Lisätty tarkkailulistalle',
'addedwatchtext'      => 'Sivu \'\'\'$1\'\'\' on lisätty [[Special:Watchlist|tarkkailulistallesi]]. Tulevaisuudessa sivuun ja sen keskustelusivuun tehtävät muutokset listataan täällä. Sivu on \'\'\'lihavoitu\'\'\' [[Special:Recentchanges|tuoreiden muutosten listassa]], jotta huomaisit sen helpommin. Jos haluat myöhemmin poistaa sivun tarkkailulistaltasi, napsauta linkkiä \'\'lopeta tarkkailu\'\' sivun reunassa.',
'removedwatch'        => 'Poistettu tarkkailulistalta',
'removedwatchtext'    => 'Sivu \'\'\'$1\'\'\' on poistettu tarkkailulistaltasi.',
'watch'               => 'Tarkkaile',
'watchthispage'       => 'Tarkkaile tätä sivua',
'unwatch'             => 'Lopeta tarkkailu',
'unwatchthispage'     => 'Lopeta tarkkailu',
'notanarticle'        => 'Ei ole sivu',
'watchnochange'       => 'Valittuna ajanajaksona yhtäkään tarkkailemistasi sivuista ei muokattu. ',
'watchdetails'        => 'Keskustelusivuja mukaan laskematta tarkkailun alla on $1 sivua. <a href="$4">Muokkaa listaa</a>.',
'watchmethod-recent'  => 'tarkistetaan tuoreimpia muutoksia tarkkailluille sivuille',
'watchmethod-list'    => 'tarkistetaan tarkkailtujen sivujen tuoreimmat muutokset',
'removechecked'       => 'Poista valitut sivut tarkkailulistalta',
'watchlistcontains'   => 'Tarkkailulistallasi on $1 sivua.',
'watcheditlist'       => 'Tässä on aakkostettu lista tarkkailemistasi sivuista. Merkitse niiden sivujen ruudut, jotka haluat poistaa tarkkailulistaltasi.',
'removingchecked'     => 'Merkityt sivut poistettiin tarkkailulistalta.',
'couldntremove'       => 'Sivua $1 ei voitu poistaa tarkkailulistalta',
'iteminvalidname'     => 'Sivun $1 kanssa oli ongelmia! Sivun nimessä on vikaa.',
'wlnote'              => 'Alla ovat $1 muutosta viimeisen <b>$2</b> tunnin ajalta.',
'wlshowlast'          => 'Näytä viimeiset $1 tuntia $2 päivää $3',
'wlsaved'             => 'Tämä on tallennettu versio tarkkailulistastasi.',

# Delete/protect/revert
#
'deletepage'          => 'Poista sivu',
'confirm'             => 'Vahvista',
'excontent'           => 'sisälsi:',
'exbeforeblank'       => 'ennen tyhjentämistä sisälsi:',
'exblank'             => 'oli tyhjä',
'confirmdelete'       => 'Vahvista poisto',
'deletesub'           => 'Sivun $1 poisto',
'historywarning'      => 'Varoitus: Sivulla, jonka aiot poistaa on muokkaushistoria: ',
'confirmdeletetext'   => 'Olet tuhomassa sivun tai kuvan ja kaiken sen historian tietokannasta pysyvästi. Vahvista, aiotko todella tehdä näin, ja että ymmärrät teon seuraukset ja että ymmärrät tekeväsi tämän {{GRAMMAR:genitive|{{SITENAME}}}} käytännön mukaisesti.',
'actioncomplete'      => 'Toiminto suoritettu',
'deletedtext'         => '<b>$1</b> on poistettu. Katso $2 nähdäksesi tallenteen viimeaikaisista poistoista.', // TODO NO WIKIMARKUP
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
'protectreason'       => 'Anna syy',

# Undelete
'undelete'            => 'Palauta poistettu sivu',
'undelete_short'      => 'Palauta $1 muokkausta',
'undeletepage'        => 'Selaa ja palauta poistettuja sivuja',
'undeletepagetext'    => 'Seuraavat sivut on poistettu, mutta ne löytyvät vielä arkistosta, joten ne ovat palautettavissa. Arkisto saatetaan tyhjentää aika ajoin.',
'undeletearticle'     => 'Palauta poistettu sivu',
'undeleterevisions'   => '$1 versiota arkistoitu.',
'undeletehistory'     => 'Jos palautat sivun, kaikki versiot lisätään sivun historiaan. Jos uusi sivu samalla nimellä on luotu poistamisen jälkeen, palautetut versiot lisätään sen historiaan, ja olemassa olevaa versiota ei korvata automaattisesti.',
'undeleterevision'    => 'Poistettu versio hetkellä $1',
'undeletebtn'         => 'Palauta!',
'undeletedarticle'    => 'palautti sivun $1',
'undeletedrevisions'  => '$1 versiota palautettiin',
'undeletedtext'       => 'Sivu [[$1]] on palautettu onnistuneesti. Lista viimeisimmistä poistoista ja palautuksista on [[Special:Log/delete|poistolokissa]].',

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

# What links here
#
'whatlinkshere'       => 'Tänne viittaavat sivut',
'notargettitle'       => 'Ei kohdetta',
'notargettext'        => 'Et ole määritellyt kohdesivua tai -käyttäjää johon toiminto kohdustuu.',
'linklistsub'         => 'Lista linkeistä',
'linkshere'           => 'Seuraavilta sivuilta on linkki tälle sivulle:',
'nolinkshere'         => 'Tänne ei ole linkkejä.',
'isredirect'          => 'uudelleenohjaussivu',

# Block/unblock IP
#
'blockip'             => 'Aseta muokkausesto',
'blockiptext'         => 'Tällä lomakkeella voit estää käyttäjän tai IP-osoitteen muokkausoikeudet. Muokkausoikeuksien poistamiseen pitää olla syy, esimerkiksi sivujen vandalisointi. Kirjoita syy siihen varattuun kenttään.<br />Vanhenemisajat noudattavat GNUn standardimuotoa, joka on kuvattu tar-manuaalissa ([http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html] [EN]), esimerkiksi ”1 hour”, ”2 days”, ”next Wednesday”, ”1 January 2017”. Esto voi olla myös ”indefinite” tai ”infinite”, joka kestää siihen asti, että se poistetaan.',
'ipaddress'           => 'IP-osoite tai käyttäjätunnus',
'ipbexpiry'           => 'Umpeutuu',
'ipbreason'           => 'Syy',
'ipbsubmit'           => 'Estä tämä osoite',
'badipaddress'        => 'IP-osoite on väärin muotoiltu.',
'noblockreason'       => 'Sinun täytyy antaa syy estolle.',
'blockipsuccesssub'   => 'Esto onnistui',
'blockipsuccesstext'  => 'Käyttäjä tai IP-osoite \'\'\'$1\'\'\' on estetty.<br />Nykyiset estot löytyvät [[Special:Ipblocklist|estolistalta]].',
'unblockip'           => 'Poista IP-osoitteen muokkausesto',
'unblockiptext'       => 'Käytä alla olevaa lomaketta poistaaksesi kirjoitusesto aikaisemmin estetyltä IP-osoitteelta.',
'ipusubmit'           => 'Poista tämän osoitteen esto',
'ipusuccess'          => 'IP-osoitteen \'\'\'$1\'\'\' esto poistettu',
'ipblocklist'         => 'Lista estetyistä IP-osoitteista',
'blocklistline'       => '$1 — $2 on estänyt käyttäjän $3 (vanhenee $4)',
'blocklink'           => 'esto',
'unblocklink'         => 'poista esto',
'contribslink'        => 'muokkaukset',
'autoblocker'         => 'Olet automaattisesti estetty, koska jaat IP-osoitteen käyttäjän $1 kanssa. Eston syy: $2.', // TODO: IS WIKIMARKUP?
'blocklogpage'        => 'Estoloki',
'blocklogentry'       => 'esti käyttäjän $1. Vanhenee: $2',
'blocklogtext'        => 'Tässä on loki muokkausestoista ja niiden purkamisista. Automaattisesti estettyjä IP-osoitteita ei kirjata. Tutustu [[Special:Ipblocklist|estolistaan]] nähdäksesi listan tällä hetkellä voimassa olevista estoista.',
'unblocklogentry'     => 'poisti käyttäjältä $1 muokkauseston',
'range_block_disabled'=> 'Ylläpitäjän oikeis luoda alue-estoja ei ole käytöstä.',
'ipb_expiry_invalid'  => 'Virheellinen umpeutumisaika.',
'ip_range_invalid'    => 'Virheellinen IP-alue.',
'proxyblocker'        => 'Välityspalvelinesto',
'proxyblockreason'    => 'IP-osoitteestasi on estetty muokkaukset, koska se on avoin välityspalvelin. Ota yhteyttä Internet-palveluntarjoajaasi tai tekniseen tukeen ja kerro heillä tästä tietoturvaongelmasta.',
'proxyblocksuccess'   => 'Valmis.',

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

# SQL query
#
'asksql'              => 'SQL-kysely',
'asksqltext'          => 'Tämä ominaisuus ei ole käytössä',
'sqlislogged'         => 'Panethan merkille, että kaikki kyselyt kirjataan ylös.',
'sqlquery'            => 'Kirjoita kysely',
'querybtn'            => 'Lähetä kysely',
'selectonly'          => 'Vain {{GRAMMAR:genitive|{{SITENAME}}}} kehittäjät voivat tehdä muita kuin SELECT-hakuja.',
'querysuccessful'     => 'Kysely onnistui',

# Make sysop
'makesysoptitle'      => 'Tee käyttäjästä ylläpitäjä',
'makesysoptext'       => 'Byrokraatit voivat tällä lomakkeella tehdä käyttäjistä ylläpitäjiä ja byrokraatteja. Kirjoita laatikkoon sen käyttäjän nimi, jolle haluat antaa oikeuksia.',
'makesysopname'       => 'Käyttäjän nimi:',
'makesysopsubmit'     => 'Tee käyttäjästä ylläpitäjä',
'makesysopok'         => 'Käyttäjä <b>$1</b> on nyt ylläpitäjä.',
'makesysopfail'       => 'Käyttäjästä <b>$1</b> ei voitu tehdä ylläpitäjää. Kirjoititko nimen oikein?', // TODO: NOWIKIMARKUP
'setbureaucratflag'   => 'Tee käyttäjästä myös byrokraatti',
'bureaucratlog'       => 'Byrokraattilogi',
'rightslogtext'       => 'Alla on loki on käyttäjien käyttöoikeuksien muutoksista.',
'bureaucratlogentry'  => 'antoi oikeudet käyttäjälle $1',
'rights'              => 'Oikeudet:',
'set_user_rights'     => 'Aseta käyttäjän oikeudet',
'user_rights_set'     => 'Käyttäjän <b>$1</b> oikeudet päivitetty.',
'set_rights_fail'     => 'Käyttäjän <b>$1</b> oikeuksia ei voita asettaa. Kirjoititko nimen oikein?',
'makesysop'           => 'Tee käyttäjästä ylläpitäjä',

//HOX Validation: missä näitä käytetään? -> http://meta.wikimedia.org/wiki/Article_validation
# Validation
'val_clear_old'       => 'Poista sivulle $1 aiemmin antamani validiointitiedot',
'val_merge_old'       => 'Käytä aiempaa arviotani, kun en ole antanut mielipidettä',
'val_form_note'       => '<b>Vinkki:</b> Tietojen säilytys tarkoittaa, että kaikkiin valitun version kohtiin, joihin <i>et ole antanut mielipidettä</i>, asetetaan  arvo ja kommentti vanhemmista versioista. Tiedot siirretään tuoreimmasta mielipiteen sisältävästä versiosta. Jos esimerkiksi uutta versiota validioidessasi haluat muuttaa mieltä yhdessä ainoassa kohdassa, aseta arvo vain siihen, jolloin muut kohdat säilyvät samoina kuin ennenkin.', // TODO: FIX ME
'val_noop'            => 'ei mielipidettä',
'val_percent'         => '<b>$1%</b><br />($2 / $3 pistettä<br /> $4 käyttäjältä)',
'val_percent_single'  => '<b>$1%</b><br />($2 / $3 pistettä<br /> yhdeltä käyttäjältä)',
'val_total'           => 'Yhteensä',
'val_version'         => 'Versio',
'val_tab'             => 'Validioi',
'val_this_is_current_version' => 'tämä on tuorein versio',
'val_version_of'      => 'Käyttäjän $1 versio', // TODO: CHECK ME
'val_table_header'    => '<tr><th>luokka</th>$1<th colspan=4>mielipide</th>$1<th>kommentti</th></tr>\n',
'val_stat_link_text'  => 'Tilastotietoa sivun validioinnista',
'val_view_version'    => 'Katso tätä versiota',
'val_validate_version'=> 'Validioi tämä versio',
'val_user_validations'=> 'Käyttäjä on validioinut $1 sivua.',
'val_no_anon_validation' => 'Vain sisään kirjautuneet käyttäjät voivat validioida sivuja.',
'val_validate_article_namespace_only' => 'Vain sivuja voi validioida. Tämä sivu ei <i>ei</i> ole oletusnimiavaruudessa.',
'val_validated'       => 'Validiointi on valmis.',
'val_article_lists'   => 'Luettelo validioiduista sivuista',
'val_page_validation_statistics' => 'Tilastotietoa $1:n sivujen validioinneista', // TODO: FIX ME

# Move page
#
'movepage'            => 'Siirrä sivu',
'movepagetext'        => 'Alla olevalla lomakkeella voit nimetä uudelleen sivuja, jolloin niiden koko historia siirtyy uuden nimen alle. Vanhasta sivusta tulee uudelleenohjaussivu, joka osoittaa uuteen sivuun. Vanhaan sivuun suunnattuja linkkejä ei muuteta, muista tehdä tarkistukset kaksinkertaisten tai rikkinäisten uudellenohjausten varalta. Olet vastuussa siitä, että linkit osoittavat sinne, mihin niiden on tarkoituskin osoittaa.

Huomaa, että sivua \'\'\'ei\'\'\' siirretä mikäli uusi otsikko on olemassaolevan sivun käytössä, paitsi milloin kyseessä on tyhjä sivu tai uudelleenohjaus, jolla ei ole muokkaushistoriaa. Tämä tarkoittaa sitä, että voit siirtää sivun takaisin vanhalle nimelleen mikäli teit virheen, mutta et voi kirjoittaa olemassa olevan sivun päälle. Jos on sivu tarvitsee siirtää olemassa olevan sivun päälle, ota yhteyttä [[Special:Listadmins|ylläpitäjään]].

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
'talkpagenotmoved'    => 'Sivun keskustelusivua <b>ei</b> siirretty.', // TODO: NO WIKIMARKUP
'1movedto2'           => '$1 siirretty sivulle $2',
'1movedto2_redir'     => '$1 siirretty edelleenohjauksen päälle sivulle $2',

# Export

'export'              => 'Sivujen vienti',
'exporttext'          => 'Voit viedä sivun tai sivujen tekstiä ja muokkaushistoriaa XML-muodossa. Tulevaisuudessa tämä tieto voidaan tuoda johonkin toiseen wikiin, jossa käytetään MediaWiki-ohjelmistoa. Nykyisessä MediaWikin versiossa tätä ei tosin vielä tueta.

Syötä sivujen otsikoita riveittäin alla olevaan laatikkoon. Valitse myös, haluatko kaikki versiot sivuista, vai ainoastaan nykyisen version.

Jälkimmäisessä tapauksessa voit myös käyttää linkkiä. Esimerkiksi [[Juna]]sivun saa vietyä linkistä [[Special:Export/Juna]].',
'exportcuronly'       => 'Liitä mukaan ainoastaan uusin versio, ei koko historiaa.',

# Namespace 8 related

'allmessages'         => 'Kaikki järjestelmäviestit',
/* CVS only 2005-03-17*/
'allmessagesname'     => 'Nimi',
'allmessagesdefault'  => 'Oletusarvo',
'allmessagescurrent'  => 'Nykyinen arvo',
'allmessagestext'     => 'Tämä on luettelo kaikista MediaWiki-nimiavaruudesta olevista viesteistä.',
'allmessagesnotsupportedUI' => 'Special:Allmessages-sivu ei tue täällä käyttöliittymäkieltäsi <b>$1</b>.',
'allmessagesnotsupportedDB' => 'Special:AllMessages-sivu ei ole käytössä, koska wgUseDatabaseMessages-asetus on pois päältä.',

# Thumbnails

'thumbnail-more'      => 'Suurenna',
'missingimage'        => '<b>Puuttuva kuva</b><br /><i>$1</i>',
'filemissing'         => 'Tiedosto puuttuu',

# Special:Import
'import'            => 'Tuo sivuja',
'importtext'        => 'Vie sivuja lähdewikistä käyttäen Special:Export-työkalua. Tallenna tiedot koneellesi ja tallenna ne täällä.',
'importfailed'      => 'Tuonti epäonnistui: $1',
'importnotext'      => 'Tyhjä tai ei tekstiä',
'importsuccess'     => 'Tuonti onnistui!',
'importhistoryconflict' => 'Sivusta on olemassa tuonnin kanssa ristiriitainen muokkausversio. Tämä sivu on saatettu tuoda jo aikaisemmin.',

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
'listingcontinuesabbrev' => 'jatk.',

# Info page
'infosubtitle'        => 'Tietoja sivusta',
'numedits'            => 'Sivun muokkausten määrä: $1',
'numtalkedits'        => 'Keskustelusivun muokkausten määrä: $1',
'numwatchers'         => 'Tarkkailijoiden määrä: $1',
'numauthors'          => 'Sivun erillisten kirjoittajien määrä: $1',
'numtalkauthors'      => 'Keskustelusivun erillisten kirjoittajien määrä: $1',

# Math options
  'mw_math_png'       => 'Näytä aina PNG:nä',
  'mw_math_simple'    => 'Näytä HTML:nä, jos yksinkertainen, muuten PNG:nä',
  'mw_math_html'      => 'Näytä HTML:nä, jos mahdollista, muuten PNG:nä',
  'mw_math_source'    => 'Näytä TeX-muodossa (tekstiselaimille)',
  'mw_math_modern'    => 'Suositus nykyselaimille',
  'mw_math_mathml'    => 'Näytä MathML:nä jos mahdollista (kokeellinen)',

// HOX
# Patrolling
'markaspatrolleddiff'   => 'Merkitse tarkastetuksi',
'markaspatrolledlink'   => '<div class="patrollink">[$1]</div>',
'markaspatrolledtext'   => 'Merkitse sivu tarkastetuksi',
'markedaspatrolled'     => 'Tarkastettu',
'markedaspatrolledtext' => 'Valittu versio on tarkastettu.',
'rcpatroldisabled'      => 'Tuoreiden muutosten tarkastus on pois käytöstä',
'rcpatroldisabledtext'  => 'Tuoreiden muutosten tarkastustoiminto on toistaiseksi pois käytöstä.',

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
ta['ca-talk'] = new Array('t','Keskustelua sisällöstä');
ta['ca-edit'] = new Array('e','Muokkaa tätä sivua');
ta['ca-addsection'] = new Array('+','Lisää kommentti tälle sivulle');
ta['ca-viewsource'] = new Array('e','Näytä sivun lähdekoodi');
ta['ca-history'] = new Array('h','Aikaisemmat versiot tästä sivusta');
ta['ca-protect'] = new Array('=','Suojaa tämä sivu');
ta['ca-delete'] = new Array('d','Poista tämä sivu');
ta['ca-undelete'] = new Array('d','Palauta tämä sivu');
ta['ca-move'] = new Array('m','Siirrä tämä sivu');
ta['ca-nomove'] = new Array('','Sinulla ei ole oikeuksia siirtää tätä sivua');
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
ta['ca-nstab-main'] = new Array('c','Näytä sisältösivu');
ta['ca-nstab-user'] = new Array('c','Näytä käyttäjäsivu');
ta['ca-nstab-media'] = new Array('c','Näytä mediasivu');
ta['ca-nstab-special'] = new Array('','Tämä on toimintosivu');
ta['ca-nstab-wp'] = new Array('a','Näytä projektisivu');
ta['ca-nstab-image'] = new Array('c','Näytä kuvasivu');
ta['ca-nstab-mediawiki'] = new Array('c','Näytä järjestelmäviesti');
ta['ca-nstab-template'] = new Array('c','Näytä malline');
ta['ca-nstab-help'] = new Array('c','Näytä ohjesivu');
ta['ca-nstab-category'] = new Array('c','Näytä luokkasivu');
/* </pre> */",

# image deletion
'deletedrevision'     => 'Poistettiin vanha versio $1.',

# browsing diffs
'previousdiff'        => '? Edellinen muutos',
'nextdiff'            => 'Seuraava muutos ?',

'imagemaxsize'        => 'Rajoita kuvien kokoa kuvien kuvaussivuilla arvoon: ',
'showbigimage'        => 'Lataa korkeatarkkuuksinen versio ($1×$2, $3 KiB)',

'newimages'           => 'Galleria uusista kuvista',
'noimages'            => 'Ei uusia kuvia.',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Käyttäjä: ',
'speciallogtitlelabel'=> 'Otsikko: ',

'passwordtooshort'    => 'Salasanasi on liian lyhyt. Salasanan pitää olla vähintään $1 merkkiä pitkä.',


);

  #--------------------------------------------------------------------------
  # Internationalisation code
  #--------------------------------------------------------------------------

class LanguageFi extends LanguageUtf8 {
  function LanguageFi() {
	$this->Language();
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

  function getNsText( $index ) {
    global $wgNamespaceNamesFi;
    return $wgNamespaceNamesFi[$index];
  }

  function getNsIndex( $text ) {
    global $wgNamespaceNamesFi;

    foreach ( $wgNamespaceNamesFi as $i => $n ) {
      if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
    }
    return false;
  }

  function getQuickbarSettings() {
    global $wgQuickbarSettingsFi;
    return $wgQuickbarSettingsFi;
  }

  function getSkinNames() {
    global $wgSkinNamesFi;
    return $wgSkinNamesFi;
  }

  function getMathNames() {
    global $wgMathNamesFi;
    return $wgMathNamesFi;
  }

  function date( $ts, $adj = false )
  {
    if ( $adj ) { $ts = $this->userAdjust( $ts ); }

    $d = (0 + substr( $ts, 6, 2 )) . '. ' .
    $this->getMonthName( substr( $ts, 4, 2 ) ) . 'ta ' . substr( $ts, 0, 4 );
    return $d;
  }

  function time( $ts, $adj = false, $seconds = true )
  {
    if ( $adj ) { $ts = $this->userAdjust( $ts ); }

    $t = substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 );
    if ( $seconds ) {
    $t .= ':' . substr( $ts, 12, 2 );
    }
    return $t;
  }

  function timeanddate( $ts, $adj = false )
  {
    return $this->date( $ts, $adj ) . ' kello ' . $this->time( $ts, $adj );
  }

  function getMessage( $key )
  {
    global $wgAllMessagesFi;
    if( isset( $wgAllMessagesFi[$key] ) ) {
      return $wgAllMessagesFi[$key];
    } else {
      return parent::getMessage( $key );
    }
  }
  
  var $digitTransTable = array(
    ',' => '&nbsp;',
    '.' => ','
  );
  
  function formatNum( $number ) {
    global $wgTranslateNumerals;
    return $wgTranslateNumerals ? strtr($number, $this->digitTransTable ) : $number;
  }

  # Convert from the nominative form of a noun to some other case
  # Invoked with {{GRAMMAR:case|word}}
  function convertGrammar( $word, $case ) {
    # These rules are not perfect, but they are currently only used for site names so it doesn't
    # matter if they are wrong sometimes. Just add a special case for your site name if necessary.
    # TODO: in the future add better version. (Nikerabbit)
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
