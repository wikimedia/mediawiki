<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

# FIXME: use $wgSitename, $wgMetaNamespace instead of hard-coded Wikipedia

# NOTE: To turn off 'Current Events' in the sidebar,
# set 'currentevents' => '-'

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
# See Language.php for more notes.

/* private */ $wgNamespaceNamesFi = array(
        NS_MEDIA                => 'Media',
        NS_SPECIAL              => 'Toiminnot',
        NS_MAIN                 => '',
        NS_TALK                 => 'Keskustelu',
        NS_USER                 => 'Käyttäjä',
        NS_USER_TALK            => 'Keskustelu_käyttäjästä',
        NS_PROJECT              => $wgMetaNamespace,
        NS_PROJECT_TALK         => FALSE,  # Set in constructor
        NS_IMAGE                => 'Kuva',
        NS_IMAGE_TALK           => 'Keskustelu_kuvasta',
        NS_MEDIAWIKI            => 'MediaWiki',
        NS_MEDIAWIKI_TALK       => 'MediaWiki_talk',
        NS_TEMPLATE             => 'Template',
        NS_TEMPLATE_TALK        => 'Template_talk',
        NS_HELP                 => 'Ohje',
        NS_HELP_TALK            => 'Keskustelu_ohjeesta',
        NS_CATEGORY             => 'Luokka',
        NS_CATEGORY_TALK        => 'Keskustelu_luokasta'

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFi = array(
        'Ei mitään', 'Tekstin mukana, vasen', 'Tekstin mukana, oikea', 'Pysyen vasemmalla'
);

/* private */ $wgSkinNamesFi = array(
        'standard'     => 'Perus',
        'nostalgia'    => 'Nostalgia',
        'cologneblue'  => 'Kölnin sininen',
        'davinci'      => 'DaVinci',
        'mono'         => 'Mono',
        'monobook'     => 'MonoBook',
        'myskin'       => 'Oma tyylisivu',  
        'chick'        => 'Chick' // kenties ranskan "chic" 'tyylikäs'? --Mikalaari
);
//myskinin nyk. toiminnallisuus on se, että saa käyttää selaimen "käyttäjän tyylisivu"-toimintoa

/* private */ $wgBookstoreListFi = array(
        'AddALL'                        => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
        'PriceSCAN'                     => 'http://www.pricescan.com/books/bookDetail.asp?isbn=$1',
        'Akateeminen kirjakauppa'       => 'http://www.akateeminen.com/search/tuotetieto.asp?tuotenro=$1',
        'Amazon.com'                    => 'http://www.amazon.com/exec/obidos/ISBN=$1',
        'Barnes & Noble'                => 'http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
        'Bookplus'                      => 'http://www.bookplus.fi/product.php?isbn=$1',
        'Helsingin yliopiston kirjasto' => 'http://pandora.lib.hel.fi/cgi-bin/mhask/monihask.py?volname=&author=&keyword=&ident=$1&submit=Hae&engine_helka=ON',
        'Pääkaupunkiseudun kirjastot'   => 'http://www.helmet.fi/search*fin/i?SEARCH=$1',
        'Tampereen seudun kirjastot'    => 'http://pandora.lib.hel.fi/cgi-bin/mhask/monihask.py?volname=&author=&keyword=&ident=$1-1&lang=kaikki&mat_type=kaikki&submit=Hae&engine_tampere=ON'
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex
/* private */ $wgAllMessagesFi = array(
'special_version_prefix'    => ' ',
'special_version_postfix'   => ' ',
# User preference toggles
'tog-underline'             => 'Alleviivaa linkit',
'tog-highlightbroken'       => 'Näytä linkit puuttuville sivuille <a href="" class="new">näin </a> (vaihtoehtoisesti näin: <a href="" class="internal">?</a>).',
'tog-justify'               => 'Tasaa kappaleet',
'tog-hideminor'             => 'Piilota pienet muutokset tuoreet muutokset -listasta',
'tog-usenewrc'              => 'Kehittynyt tuoreet muutokset -listaus (ei toimi kaikilla selaimilla)',
'tog-numberheadings'        => 'Numeroi otsikot',
'tog-showtoolbar'           => 'Näytä työkalupalkki',
'tog-editondblclick'        => 'Muokkaa sivuja kaksoisnapsautuksella (JavaScript)',
'tog-editsection'           => 'Näytä muokkauslinkit jokaisen kappaleen yläpuolella',
'tog-editsectiononrightclick'=>'Muokkaa kappaleita otsikon oikealla hiirennapsautuksella (JavaScript)',
'tog-showtoc'               =>'Näytä sisällysluettelo<br />(sivuille, joilla yli 3 otsikkoa)',
'tog-rememberpassword'      => 'Älä kysy salasanaa saman yhteyden eri istuntojen välillä',
'tog-editwidth'             => 'Muokkauskenttä on sivun levyinen',
'tog-watchdefault'          => 'Lisää oletuksena uudet ja muokatut sivut tarkkailulistalle',
'tog-minordefault'          => 'Muutokset ovat oletuksena pieniä',
'tog-previewontop'          => 'Näytä esikatselu muokkauskentän yläpuolella',
'tog-nocache'               => 'Älä tallenna sivuja välimuistiin',

# dates
'sunday'     => 'sunnuntai',
'monday'     => 'maanantai',
'tuesday'    => 'tiistai',
'wednesday'  => 'keskiviikko',
'thursday'   => 'torstai',
'friday'     => 'perjantai',
'saturday'   => 'lauantai',
'january'    => 'tammikuu',
'february'   => 'helmikuu',
'march'      => 'maaliskuu',
'april'      => 'huhtikuu',
'may_long'   => 'toukokuu',
'june'       => 'kesäkuu',
'july'       => 'heinäkuu',
'august'     => 'elokuu',
'september'  => 'syyskuu',
'october'    => 'lokakuu',
'november'   => 'marraskuu',
'december'   => 'joulukuu',
'jan'        => 'tammi',
'feb'        => 'helmi',
'mar'        => 'maalis',
'apr'        => 'huhti',
'may'        => 'touko',
'jun'        => 'kesä',
'jul'        => 'heinä',
'aug'        => 'elo',
'sep'        => 'syys',
'oct'        => 'loka',
'nov'        => 'marras',
'dec'        => 'joulu',

# Bits of text used by many pages:
#
'categories'            => 'Luokat',
'category'              => 'Luokka',
'category_header'       => 'Luokkaan "$1" kuuluvat artikkelit',
'subcategories'         => 'Alaluokat',
'linktrail'             => '/^((?:[a-z]|ä|ö|å)+)(.*)$/sD',
'mainpage'              => 'Etusivu',
'mainpagetext'          => 'Wiki-ohjelmisto on onnistuneesti asennettu.',
'mainpagedocfooter'     => 'Lisätietoja käyttö ja asetusten tekoa varten on sivuilla [http://meta.wikipedia.org/wiki/MediaWiki_i18n documentation on customizing the interface]
ja [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User\'s Guide].',
'portal'                => 'Kahvihuone',
'portal-url'            => 'Project:Kahvihuone',
'about'                 => 'Tietoja',
'aboutsite'             => 'Tietoja {{grammar:elative|{{SITENAME}}}}',
'aboutpage'             => 'Project:Tietoja',
'article'               => 'Artikkeli',
'help'                  => 'Ohje',
'helppage'              => '{{ns:12}}:Ohje', // Rikkoo nykyisen Nahru
'wikititlesuffix'       => '{{SITENAME}}',
'bugreports'            => 'Ongelmat ja parannusehdotukset', //Virheraportti -> Ongelmat ja parannusehdotukset
'bugreportspage'        => 'Project:Ongelmat ja parannusehdotukset',
'sitesupport'           => 'Lahjoitukset', # Set a URL in $wgSiteSupportPage in LocalSettings.php
'faq'                   => 'VUKK',
'faqpage'               => 'Project:VUKK',
'edithelp'              => 'Muokkausohjeet',
'newwindow'             => '(avautuu uuteen ikkunaan)',
'edithelppage'          => '{{ns:12}}:Kuinka_sivuja_muokataan', // TODO
'cancel'                => 'Keskeytä',
'qbfind'                => 'Etsi',
'qbbrowse'              => 'Selaa',
'qbedit'                => 'Muokkaa',
'qbpageoptions'         => 'Sivuasetukset',
'qbpageinfo'            => 'Sivun tiedot',
'qbmyoptions'           => 'Asetukset',
'qbspecialpages'        => 'Toimintosivut',
'moredotdotdot'         => 'Lisää...',
'mypage'                => 'Oma käyttäjäsivu',
'mytalk'                => 'Oma keskustelusivu',
'anontalk'              => 'Keskustele tämän IP:n kanssa',
'navigation'            => 'Valikko',
'currentevents'         => 'Ajankohtaista',
'disclaimers'           => 'Vastuuvapaus',
'disclaimerpage'        => 'Project:Vastuuvapaus',
'errorpagetitle'        => 'Virhe',
'returnto'              => 'Palaa sivulle $1.',
'tagline'               => '{{SITENAME}}', // ei enää käytössä loppuliite eng.: vapaa tietosanakirja
'whatlinkshere'         => 'Tänne linkitetyt sivut',
'help'                  => 'Ohje',
'search'                => 'Etsi',
'go'                    => 'Siirry',
'history'               => 'Vanhemmat versiot',
'history_short'         => 'Historia',
'info_short'            => 'Tiedostus',
'printableversion'      => 'Tulostettava versio',
'edit'                  => 'Muokkaa',
'editthispage'          => 'Muokkaa tätä sivua',
'delete'                => 'Poista',
'deletethispage'        => 'Poista tämä sivu',
'undelete_short'        => 'Palauta $1 muokkausta',
'undelete_short1'        => 'Palauta 1 muokkausta',
'protect'               => 'Suojaa',
'protectthispage'       => 'Suojaa tämä sivu',
'unprotect'             => 'Poista suojaus',
'unprotectthispage'     => 'Poista tämän sivun suojaus',
'newpage'               => 'Uusi sivu',
'talkpage'              => 'Keskustele tästä sivusta',
'specialpage'           => 'Toimintosivu',
'personaltools'         => 'Henkilökohtaiset työkalut',
'postcomment'           => 'Kommentti sivun loppuun',
'addsection'            => '+',
'articlepage'           => 'Näytä artikkeli',
'subjectpage'           => 'Näytä aihe', # For compatibility
'talk'                  => 'Keskustelu',
'toolbox'               => 'Työkalut',
'userpage'              => 'Näytä käyttäjän sivu',
'wikipediapage'         => 'Näytä artikkeli',
'imagepage'             => 'Näytä kuvasivu',
'viewtalkpage'          => 'Näytä keskustelusivu',
'otherlanguages'        => 'Muut kielet',
'redirectedfrom'        => '(Uudelleenohjattu sivulta $1)',
'lastmodified'          => 'Sivua on viimeksi muutettu  $1.',
'viewcount'             => 'Tämä sivu on näytetty $1 kertaa.',
'copyright'             => 'Sisältö on käytettävissä lisenssillä $1.',
'poweredby'             => '{{grammar:genitive|{{SITENAME}}}} tarjoaa [http://www.mediawiki.org/ MediaWiki], avoimen lähdekoodin ohjelmisto.',  
'gnunote'             => "Kaikki teksti on saatavilla <a class=internal href=\"$wgScriptPath/GNU_FDL\">GNU Free Documentation -lisenssin</a> ehdoilla.",
'printsubtitle'         => '(Lähde: {{SERVER}})',
'protectedpage'         => 'Suojattu sivu',
'administrators'        => 'Project:Ylläpitäjät',
'sysoptitle'            => 'Vaatii ylläpitäjäoikeudet',
'sysoptext'             => 'Tämän toiminnon voi suorittaa vain käyttäjä, jolla on ylläpitäjäoikeudet. Katso $1.',
'developertitle'        => 'Ohjelmiston kehittäjän oikeuksia vaaditaan',
'developertext'         => 'Yrittämäsi toiminnon voi suorittaa vain henkilö, jolla on ohjelmistokehittäjänoikeudet. Katso $1.',
'bureaucrattitle'       => 'Tämän toiminnon suorittamiseen tarvitaan byrokraattioikeudet',
'bureaucrattext'        => 'Tämän toiminnon voivat suorittaa vain ylläpitäjät, joilla on byrokraattioikeudet.',
'nbytes'                => '$1 tavua',
'go'                    => 'Siirry',
'ok'                    => 'OK',
'sitetitle'             => '{{SITENAME}}',
'pagetitle'             => '$1 - {{SITENAME}}',
'sitesubtitle'          => 'Vapaa tietosanakirja',
'retrievedfrom'         => 'Haettu "$1":sta', // suks
'newmessages'           => 'Sinulle on $1',
'newmessageslink'       => 'uutta viestiä.',
'editsection'           => 'muokkaa',
'toc'                   => 'Sisällysluettelo',
'showtoc'               => 'näytä',
'hidetoc'               => 'piilota',
'thisisdeleted'         => 'Näytä tai palauta $1?',
'restorelink'           => '$1 poistettua muokkausta',
'feedlinks'             => 'Uutissyötteet:',
'sitenotice'            => '', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Artikkeli',
'nstab-user'      => 'Käyttäjäsivu',
'nstab-media'     => 'Media',
'nstab-special'   => 'Toiminto',
'nstab-wp'        => 'Projektisivu',
'nstab-image'     => 'Kuva',
'nstab-mediawiki' => 'Järjestelmäviesti',
'nstab-template'  => 'Malline',
'nstab-help'      => 'Ohje',
'nstab-category'  => 'Luokka',

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
'readonly'            => 'Tietokanta on lukittu',
'enterlockreason'     => 'Anna lukituksen syy sekä sen arvioitu poistamisaika.',
'readonlytext'        => '{{grammar:genitive|{{SITENAME}}}} tietokanta on tällä hetkellä lukittu. Uusia artikkeleita tai muita muutoksia ei voi tehdä. Syynä ovat todennäköisimmin rutiininomaiset tietokannan ylläpitotoimet. Tietokannan lukinneen ylläpitäjän selitys: <p>$1',
'missingarticle'      => 'Tietokanta ei löytänyt sivun "$1" tekstiä, jonka olisi pitänyt löytyä. Todennäköisesti kyseessä on ohjelmointivirhe, ei tietokantavirhe. Ole hyvä ja ilmoita virheesta ja anna URL ylläpidolle.',
'internalerror'       => 'Sisäinen virhe',
'filecopyerror'       => 'Tiedostoa "$1" ei voitu kopioida tiedostoksi "$2".',
'filerenameerror'     => 'Tiedoston "$1" nimeä ei voitu vaihtaa "$2":ksi.',
'filedeleteerror'     => 'Tiedostoa "$1" ei voitu poistaa.',
'filenotfound'        => 'Tiedostoa "$1" ei löytynyt.',
'unexpected'          => 'Odottamaton arvo: "$1"="$2".',
'formerror'           => 'Virhe: lomaketta ei voitu lähettää',
'badarticleerror'     => 'Toimintoa ei voi suorittaa tälle sivulle.',
'cannotdelete'        => 'Määriteltyä sivua tai kuvaa ei voitu poistaa. (Joku muu on saattanut jo poistaa sen.)',
'badtitle'            => 'Virheellinen otsikko',
'badtitletext'        => 'Pyytämäsi sivuotsikko oli virheellinen, tyhjä tai väärin linkitetty kieltenvälinen tai wikienvälinen otsikko.',
'perfdisabled'        => 'Pahoittelut! Tämä ominaisuus ei toistaiseksi ole käytettävissä, sillä se hidastaa tietokantaa niin paljon, että kukaan ei voi käyttää wikiä. Toiminto ohjelmoidaan tehokkaammaksi lähiaikoina. (Sinäkin voit tehdä sen! Tämä on vapaa ohjelmisto.)',
'perfdisabledsub'     => 'Tässä on tallennettu kopio $1', # obsolete? ei ole
'perfcached'          => 'Seuraava data on tuotu välimuistista, eikä se ole välttämättä ajan tasalla.',
'wrong_wfQuery_params' => 'Virheelliset parametrit wfQuery():yyn<br />
Funktio: $1<br />
Tiedustelu: $2
',
'viewsource'          => 'Lähdekoodi',
'protectedtext'       => ' Tämä sivu on lukittu niin, että sitä ei voi muokata. Syitä tähän voi olla useita, ks. [[{{ns:4}}:Protected page]]. Voit katsoa sivun lähdekoodia ja kopioida sen:',
'seriousxhtmlerrors'  => 'XHTML-merkkauskielessä havaittiin vakavia virheitä.',

# Login and logout pages
#
'logouttitle'         => 'Käyttäjän uloskirjautuminen',
'logouttext'          => 'Olet nyt kirjautunut ulos {{grammar:elative|{{SITENAME}}}}.
Voit jatkaa {{grammar:genitive|{{SITENAME}}}} käyttöä nimettömänä, tai
kirjautua uudelleen sisään. Huomaa että jotkut sivut saattavat näyttää, että olet kirjautunut sisään. Sen pitäisi korjautua tyhjentämällä selaimesi välimuisti.\n',

'welcomecreation'     => '== Tervetuloa, $1! == <p> Käyttäjätunnuksesi on luotu. Älä unohda virittää omia {{SITENAME}}-asetuksiasi.',
'loginpagetitle'      => 'Käyttäjän sisäänkirjautuminen',
'yourname'            => 'Käyttäjätunnus',
'yourpassword'        => 'Salasana',
'yourpasswordagain'   => 'Salasana uudelleen',
'newusersonly'        => '(vain uudet käyttäjät)',
'remembermypassword'  => 'Muista salasana saman yhteyden istunnoissa',
'loginproblem'        => '<b>Sisäänkirjautumisessasi oli ongelmia.</b><br />Yritä uudelleen!',
'alreadyloggedin'     => '<font color=red><b>Käyttäjä $1, olet jo kirjautunut sisään!</b></font><br />\n',
'login'               => 'Kirjaudu sisään',
'loginprompt'         => 'Sinulla täytyy olla evästeet käytössä, jos haluat kirjautua {{grammar:illative|{{SITENAME}}}}.',
'userlogin'           => 'Kirjaudu sisään tai luo tunnus',
'logout'              => 'Kirjaudu ulos',
'userlogout'          => 'Kirjaudu ulos',
'notloggedin'         => 'Et ole kirjautunut',
'createaccount'       => 'Luo uusi käyttäjätunnus',
'createaccountmail'   => 'sähköpostitse',
'badretype'           => 'Syöttämäsi salasanat ovat erilaiset.',
'userexists'          => 'Pyytämäsi käyttäjänimi on jo käytössä. Ole hyvä ja valitse toinen käyttäjänimi.',
'youremail'           => 'Sähköpostiosoitteesi*',
'yourrealname'        => 'Oikea nimesi*',
'yourlanguage'        => 'Käyttöliittymän kieli',
'yournick'            => 'Nimimerkki (allekirjoituksia varten)',
'emailforlost'        => ' Jos unohdat salasanasi, voit pyytää uuden salasanan, joka lähetetään sähköpostiosoitteeseesi.',
'prefs-help-userdata' => '* <strong>Sähköposti</strong> (valinnainen): Ihmiset voivat ottaa yhteyttä sinuun sivuston kautta ilman että sähköpostiosoitteesi paljastuu lähettäjälle. Myös unohtunut salasana voidaan lähettää sähköpostiisi.',
'loginerror'          => 'Sisäänkirjautumisvirhe, ole hyvä ja yritä uudelleen.',
'nocookiesnew'        => 'Käyttäjä luotiin, mutta et ole kirjautunut sisään. {{SITENAME}} käyttää evästeitä sisäänkirjautumisen yhteydessä. Selaimesi ei salli evästeistä. Kytke ne päälle, ja sitten kirjaudu sisään juuri luomallasi käyttäjänimellä ja salasanalla.',
'nocookieslogin'      => '{{SITENAME}} käyttää evästeitä sisäänkirjautumisen yhteydessä. Selaimesi ei salli evästeitä. Ota ne käyttöön, ja yritä uudelleen.',
'noname'              => 'Et ole määritellyt kelvollista käyttäjänimeä.',
'loginsuccesstitle'   => 'Sisäänkirjoittautuminen onnistui',
'loginsuccess'        => 'Olet nyt kirjautunut {{grammar:genitive|{{SITENAME}}}} käyttäjänä "$1".',
'nosuchuser'          => ' Käyttäjänimeä "$1" ei ole olemassa. Tarkista kirjoititko nimen oikein, tai käytä alla olevaa lomaketta uuden käyttäjätunnuksen luomiseksi.',
'wrongpassword'       => 'Syöttämäsi salasana ei ole oikein. Ole hyvä ja yritä uudelleen.',
'mailmypassword'      => 'Lähetä minulle uusi salasana sähköpostilla',
'passwordremindertitle' => 'Salasanamuistutus {{grammar:elative|{{SITENAME}}}}',
// merkki
'passwordremindertext'  => 'Joku (todennäköisesti sinä) IP-osoitteesta $1 pyysi {{grammar:partitive|{{SITENAME}}}} lähettämään uuden salasanan. Salasana käyttäjälle "$2" on nyt "$3". Kirjaudu sisään ja vaihda heti salasanasi.',
'noemail'             => 'Käyttäjälle "$1" ei ole määritelty sähköpostiosoitetta.',
'passwordsent'        => 'Uusi salasana on lähetetty käyttäjän "$1" sähköpostiosoitteeseen. Kirjaudu sisään uudestaan, kun olet saanut sen.',
'loginend'            => ' ',
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
'infobox_alert'=>"Syötä teksti, jonka haluat muotoilluksi.\\n Se näytetään toisessa laatikossa leikkaa-liimaamista varten.\\nEsimerkki:\\n$1\\n muuttuu seuraavaksi:\\n$2",

# Edit pages
#
'summary'             => 'Yhteenveto (<a href="{{localurle:Wikipedia:Yhteenveto}}" target="blank" title="Lisätietoja yhteenvetokentästä">?</a>)', // TODO
'subject'             => 'Aihe',
'minoredit'           => 'Tämä on pieni muutos',
'watchthis'           => 'Tarkkaile tätä artikkelia',
'savearticle'         => 'Tallenna sivu',
'preview'             => 'Esikatselu',
'showpreview'         => 'Esikatsele',
'blockedtitle'        => 'Pääsy estetty',
'blockedtext'         => "Yritit muokata sivua tai luoda uuden sivun. $1 on estänyt pääsysi {{grammar:illative|{{SITENAME}}}} joko käyttäjänimesi tai IP-osoitteesi perusteella. Annettu syy estolle on: <br />''$2''<p>Jos olet sitä mieltä, että sinut on estetty syyttä, voit keskustella asiasta [[Project:Ylläpitäjät|ylläpitäjän]] kanssa. Huomaa, ettet voi lähettää sähköpostia {{grammar:genitive|{{SITENAME}}}} kautta, ellet ole asettanut olemassaolevaa sähköpostiosoitetta [[Special:Preferences|asetuksissa]] ==Syytön?== Ajoittain kokonaisia IP-alueita tai yhteiskäytössä olevia osoitteita estetään. Se tarkoittaa, että myös viattomat käyttäjät voivat joutua estetyksi. Jos IP-osoitteesi on dynaaminen (eli se voi toisinaan vaihtua), olet saattanut saada estetyn osoitteen käyttöösi, ja esto vaikuttaa nyt sinuun. Jos tämä ongelma toistuu jatkuvasti, ota yhteyttä Internet-palveluntarjoajaasi tai {{grammar:genitive|{{SITENAME}}}} ylläpitäjään. Ilmoita IP-osoitteesi, joka on $3.",

'whitelistedittitle'   => 'Sisäänkirjautuminen vaaditaan muokkaamiseen',
'whitelistedittext'    => 'Sinun täytyy kirjautua [[Special:Userlogin|login]] muokataksesi sivuja.',
'whitelistreadtitle'   => 'Sisäänkirjautuminen vaaditaan lukemiseen',
'whitelistreadtext'    => 'Sinun täytyy kirjautua [[Special:Userlogin|login]] lukeaksesi sivuja.',
'whitelistacctitle'    => 'Sinun ei ole sallittu luoda tunnusta',
'whitelistacctext'     => 'Saadaksesi oikeudet luoda tunnus sinun täytyy kirjautua [[Special:Userlogin|sisään]] ja sinulla tulee olla asiaankuuluvat oikeudet.',
'loginreqtitle'        => 'Sisäänkirjautuminen vaaditaan',
'loginreqtext'         => 'Sinun täytyy [[Special:Userlogin|kirjautua]] nähdäksesi muut sivut.',

'accmailtitle'         => 'Salasana lähetetty.',
'accmailtext'          => 'käyttäjän "$1" salasana on lähetetty osoitteeseen $2.',
'newarticle'           => '(uusi)',
'newarticletext'       => "Linkki toi sivulle, jota ei vielä ole. Voit luoda artikkelin kirjoittamalla alla olevaan tilaan (ks. [[Project:Ohje|ohje]]). Jos et halua luoda sivua, käytä selaimen '''paluu'''-toimintoa.",
'talkpagetext'         => '<!-- MediaWiki:talkpagetext -->',
'anontalkpagetext'     => "----''Tämä on nimettömän käyttäjän keskustelusivu. Hän ei ole joko luonut itselleen käyttäjätunnusta tai ei käytä sitä. Siksi hänet tunnistetaan nyt numeerisella [[IP-osoite|IP-osoitteella]]. Kyseinen IP-osoite voi olla useamman henkilön käytössä. Jos olet nimetön käyttäjä, ja sinusta tuntuu, että merkityksettömiä kommentteja on ohjattu sinulle, [[{{ns-1}}:Userlogin|luo itsellesi käyttäjätunnus tai kirjaudu sisään]] välttääksesi jatkossa sekaannukset muiden nimettömien käyttäjien kanssa.''",
'noarticletext'        => "<div style='border: 1px solid #ccc; padding: 7px;'>'''{{grammar:inessive|{{SITENAME}}}} ei vielä ole tämän nimistä artikkelia.''' * Voit '''[{{SERVER}}{{localurle:{{NAMESPACE}}:{{PAGENAMEE}}|action=edit}} kirjoittaa uuden artikkelin tästä aiheesta]'''.* Katso onko [[Wikisanakirja]]ssa artikkelia sanasta [[wikt:{{NAMESPACE}}:{{PAGENAME}}|{{NAMESPACE}}:{{PAGENAME}}]]. * Jos olet luonut artikkelin tällä nimellä, se on mahdollisesti poistettu — tarkista [[{{SITENAME}}:Poistoloki|poistoloki]]. Jos olet poistosta eri mieltä, kirjoita reklamaatio sivulle [[{{SITENAME}}:Poistettavat sivut]]. </div>",
'clearyourcache'       => "'''Huomautus:''' Selaimen välimuisti pitää tyhjentää asetusten tallentamisen jälkeen, jotta muutokset tulisivat voimaan: '''Mozilla:''' ''ctrl-r'', '''IE ja Opera:''' ''ctrl-f5'', '''Safari:''' ''cmd-r'', '''Konqueror''' ''ctrl-r''.",
'usercssjsyoucanpreview' => "<strong>Vinkki:</strong> Käytä Esikatselu-painiketta testataksesi uutta CSS:ää tai JavaScriptiä ennen tallennusta",
'usercsspreview'       => "'''Muista, että tämä on vain käyttäjän CSS:n esikatselu, sitä ei ole vielä tallennettu!'''",
'userjspreview'        => "'''Muista, että tämä on vain käyttäjän JavaScriptin testaus ja esikatselu, sitä ei ole vielä tallennettu!'''",
'updated'              => '(Päivitetty)',
'note'                 => '<strong>Huomautus:</strong> ',
'previewnote'          => 'Tämä on vasta sivun esikatselu. Sivua ei ole vielä tallennettu!',
'previewconflict'      => 'Tämä esikatselu näyttää miltä muokkausalueella oleva teksti näyttää tallennettuna.',
'editing'              => 'Muokataan sivua $1',
'sectionedit'          => ' (lohko)',
'commentedit'          => ' (kommentti)',
'editconflict'         => 'Päällekkäinen muokkaus: $1',
'explainconflict'      => 'Joku muu on muuttanut tätä sivua sen jälkeen, kun aloit muokata sitä.
Ylempi tekstialue sisältää tämänhetkisen tekstin.
Tekemäsi muutokset näkyvät alemmassa ikkunassa.
Sinun täytyy yhdistää muutoksesi olemassa olevaan tekstiin.
<b>Vain</b> ylemmässä alueessa oleva teksti tallentuu, kun tallennat sivun.\n<p>',
'yourtext'             => 'Oma tekstisi',
'storedversion'        => 'Tallennettu versio',
'editingold'           => '<strong>VAROITUS: Olet muokkaamassa vanhentunutta versiota tämän sivun tekstistä.
Jos tallennat sen, kaikki tämän version jälkeen tehdyt muutokset katoavat.</strong>\n',
'yourdiff'             => 'Eroavaisuudet',
'copyrightwarning'     => '<strong>Muutoksesi astuvat voimaan välittömästi.</strong> Jos haluat harjoitella muokkaamista, ole hyvä ja käytä [[Project:Hiekkalaatikko|hiekkalaatikkoa]].<br/><br/>Kaikki {{GRAMMAR:illative|{{SITENAME}}}} tehtävät tuotokset katsotaan julkaistuksi GNU Free Documentation -lisenssin mukaisesti ([[Project:{{SITENAME}} ja tekijänoikeudet|lisätietoja]]). Jos et halua, että kirjoitustasi muokataan armottomasti ja uudelleenkäytetään vapaasti, älä tallenna kirjoitustasi. Tallentamalla muutoksesi lupaat, että kirjoitit tekstisi itse, tai kopioit sen jostain vapaasta lähteestä. <strong>ÄLÄ KÄYTÄ TEKIJÄNOIKEUDEN ALAISTA MATERIAALIA ILMAN LUPAA!</strong>',
'copyrightwarning2'    => 'Huomaa, että kuka tahansa voi muokata, muuttaa tai poistaa kaikkia {{grammar:illative|{{SITENAME}}}} tehtyjä lisäyksiä. Jos et halua, että kirjoittamaasi tekstiä muokataan, älä lähetä sitä tänne.<br /> Lupaa myös, että kirjoitit tämän itse, tai kopioit sen jostain vapaasta lähteestä. <strong>ÄLÄ KÄYTÄ TEKIJÄNOIKEUDEN ALAISTA MATERIAALIA ILMAN LUPAA!</strong>',
'longpagewarning'      => 'VAROITUS: Tämän sivun tekstiosuus on $1 kilotavua pitkä. Joillakin selaimilla voi olla vaikeuksia yli 32 kilotavun kokoisten sivujen muokkaamisessa. Harkitse, voisiko sivun jakaa pienempiin osiin.',
'readonlywarning'      => 'VAROITUS: Tietokanta on lukittu huoltoa varten, joten voi olla ettet pysty tallentamaan muokkauksiasi juuri nyt. Saattaa olla paras leikata ja liimata tekstisi omaan tekstitiedostoosi ja tallentaa se tänne myöhemmin.',
'protectedpagewarning' => "VAROITUS: Tämä sivu on lukittu siten että vain ylläpitäjä-oikeuksilla varustetut käyttäjät voivat muokata sitä. Varmista että noudatat <a href='$wgScript/{{ns:4}:Suojatut_sivut'>Suojattuja sivuja koskevia neuvoja</a>.",
'templatesused'        => 'Tällä sivulla käytetyt mallineet:',

# History pages
#
'revhistory'           => 'Muutoshistoria',
'nohistory'            => 'Tällä sivulla ei ole muutoshistoriaa.',
'revnotfound'          => 'Versiota ei löydy',
'revnotfoundtext'      => 'Pyytämääsi vanhaa versiota ei löydy.
Tarkista URL, jolla hait tätä sivua.\n',
'loadhist'             => 'Ladataan sivuhistoriaa',
'currentrev'           => 'Nykyinen versio',
'revisionasof'         => 'Versio $1',
'revisionasofwithlink' => '(Versio $1; $2:stä)', //suks
'currentrevisionlink'  => 'Näytä nykyinen versio',
'cur'                  => 'nyk.',
'next'                 => 'seur.',
'last'                 => 'edell.',
'orig'                 => 'alkup.',
'histlegend'           => "Merkinnät: (nyk.) = eroavaisuudet nykyiseen versioon,
(edell.) = eroavaisuudet edelliseen versioon, '''p''' = pieni muutos",
'history_copyright'    => '-',

# Diffs
#
'difference'           => '(Versioiden väliset erot)',
'loadingrev'           => 'Ladataan versiota vertailua varten',
'lineno'               => 'Rivi $1:',
'editcurrent'          => 'Muokkaa tämän sivun uusinta versiota',
'selectnewerversionfordiff' => 'Valitse uudempi versio vertailuun',
'selectolderversionfordiff' => 'Valitse vanhempi versio vertailuun',
'compareselectedversions'   => 'Vertaile valittuja versioita',

# Search results
#
'searchresults'        => 'Hakutulokset',
'searchresulttext'     => 'Lisätietoja {{GRAMMAR:genitive|{{SITENAME}}}} hakutoiminnoista, katso $1.',
'searchquery'          => 'Haku \'$1\'',
'badquery'             => 'Tekemääsi kyselyä ei voida prosessoida. Tämä johtuu todennäköisesti siitä, että olet yrittänyt etsiä sanaa, jossa on alle kolme kirjainta, jota ei vielä tueta. Se voi johtua myös väärinkirjoitetusta lausekkeesta, esimerkiksi "hevonen ja ja kuolaimet"',
'badquerytext'         => 'Tekemääsi kyselyä ei voida prosessoida.
Tämä johtuu todennäköisesti siitä, että olet yrittänyt etsiä sanaa, 
jossa on alle kolme kirjainta. Tätä ei vielä tueta.
Se voi johtu myös väärinkirjoitetusta lausekkeesta, esimerkiksi
\'hevonen ja ja kuolaimet\'. Yritä uudelleen.',
'matchtotals'          => 'Haulla \'$1\' saatiin $2 osumaa artikkelien otsikoihin ja $3osumaa artikkeliteksteihin.',
'nogomatch'            => '<span style="font-size: 135%; font-weight: bold; margin-left: .6em">Täsmälleen tällä otsikolla ei ole sivua.</span> <span style="display: block; margin: 1.5em 2em">Voit joko <b><a href="$1" class="new">luoda aiheesta uuden artikkelin</a></b> tai <a href="/wiki/{{ns:4}}:Artikkelitoiveet">lisätä sen toivottujen sivujen listaan</a>. <span style="display:block; font-size: 89%; margin-left:.2em">Etsi kuitenkin ensin vastaavaa artikkelia, se voi olla kirjoitusasultaan hieman erilainen</span>',
'titlematches'         => 'Osumat artikkelien otsikoissa',
'notitlematches'       => 'Hakusanaa ei löytynyt minkään artikkelin otsikosta',
'textmatches'          => 'Osumat artikkelien teksteissä',
'notextmatches'        => 'Hakusanaa ei löytynyt artikkelien teksteistä',
'prevn'                => 'edelliset $1',
'nextn'                => 'seuraavat $1',
'viewprevnext'         => 'näytä ($1) ($2) ($3).',
'showingresults'       => '<b>$1</b> tulosta tuloksesta <b>$2</b> alkaen.',
'showingresultsnum'    => "Alla <b>$3</b> hakutulosta alkaen tuloksesta <b>$2</b>.",
'nonefound'            => '<strong>Huomautus</strong>: epäonnistuneet haut johtuvat usein hyvin yleisten sanojen, kuten \'on\' ja \'ei\', etsimisestä,
joita ei indeksoida, tai useamman kuin yhden hakutermin määrittelemisestä (vain sivut,
joilla on kaikki hakutermin sanat, näkyvät tuloksissa).',
'powersearch'          => 'Etsi',
'powersearchtext'      => '
Haku nimiavaruuksista:<br />
$1<br />
$2 Luettele uudelleenohjauksista   Etsi $3 $9',
"searchdisabled"       => '<p style="margin: 1.5em 2em 1em">Tekstihaku on poistettu toistaiseksi käytöstä suuren kuorman vuoksi. Voit käyttää alla olevaa Googlen hakukenttää sivujen etsimiseen, kunnes haku tulee taas käyttöön.<span style="font-size: 89%; display: block; margin-left: .2em">Huomaa, että ulkopuoliset kopiot {{GRAMMAR:genitive|{{SITENAME}}}} sisällöstä eivät välttämättä ole ajan tasalla.</span></p>',
'blanknamespace' => '(Tietosanakirja-artikkelit)', //HOX? -F (eng. Main, saks. Haupt-, ransk. Principal   -TJ)

# Preferences page
#
'preferences'           => 'Asetukset',
'prefsnologin'          => 'Ei kirjauduttu sisään',
'prefsnologintext'      => "Sinun täytyy olla <a href=\"/wiki/{{ns:-1}}:Userlogin\">kirjautuneena sisään</a> jotta voisit muuttaa käyttäjän asetuksia.", // TODO
'prefslogintext'        => "Olet kirjautuneena sisään käyttäjänä \"$1\". Sisäinen tunnistenumerosi on $2.",
'prefsreset'            => 'Asetukset on palautettu talletettujen mukaisiksi.',
'qbsettings'            => 'Pikavalikon asetukset', 
'qbsettingsnote'        => "Tämä asetus toimii ainostaan ulkoasutyyleillä \'Perus\' ja \'Kölnin sininen\'.",
'changepassword'        => 'Vaihda salasanaa',
'skin'                  => 'Ulkonäkö',
'math'                  => 'Matematiikan näyttäminen',
'dateformat'            => 'Päiväyksen muoto',
'math_failure'          => 'Jäsentäminen epäonnistui',
'math_unknown_error'    => 'Tuntematon virhe',
'math_unknown_function' => 'Tuntematon funktio ',
'math_lexing_error'     => 'Tulkintavirhe',
'math_syntax_error'     => 'Jäsennysvirhe',
'math_image_error'      => 'PNG-muunnos epäonnistui; tarkista, että latex, dvips, gs ja convert on asennettu oikein.',
'math_bad_tmpdir'       => 'Matematiikan väliaikaishakemistoon kirjoittaminen
tai tiedostonluonti ei onnistu',
'math_bad_output'       => 'Matematiikan tulostehakemistoon kirjoittaminen tai tuedostonluonti ei onnistu',
'math_notexvc'          => 'Texvc-sovellus puuttuu, lue math/README:stä asennustietoja',
'prefs-personal'        => 'Käyttäjätiedot',
'prefs-rc'              => 'Tuoreet muutokset ja tynkien näyttö',
'prefs-misc'            => 'Muut asetukset',
'saveprefs'             => 'Tallenna asetukset',
'resetprefs'            => 'Palauta alkuperäiset asetukset',
'oldpassword'           => 'Vanha salasana',
'newpassword'           => 'Uusi salasana',
'retypenew'             => 'Uusi salasana (uudelleen)',
'textboxsize'           => 'Tekstikentän koko',
'rows'                  => 'Rivit',
'columns'               => 'Sarakkeet',
'searchresultshead'     => 'Hakutulosten asetukset',
'resultsperpage'        => 'Tuloksia sivua kohti',
'contextlines'          => 'Rivien määrä tulosta kohti',
'contextchars'          => 'Sisällön merkkien määrä riviä kohden',
'stubthreshold'         => 'Tynkäartikkelin osoituskynnys',
'recentchangescount'    => 'Otsikoiden määrä viimeisimmissä muutoksissa',
'savedprefs'            => 'Asetuksesi on tallennettu.',
'timezonelegend'        => 'Aikavyöhyke',
'timezonetext'          => 'Paikallisen ajan ja palvelimen ajan (UTC) välinen aikaero tunteina.',
'localtime'             => 'Paikallinen aika',
'timezoneoffset'        => 'Aikaero',
'servertime'            => 'Palvelimen aika',
'guesstimezone'         => 'Utele selaimelta',
'emailflag'             => 'Estä sähköpostin lähetys osoitteeseen',
'defaultns'             => 'Etsi oletusarvoisesti näistä nimiavaruuksista:',

# Recent changes
#
'changes'               => 'muutosta',
'recentchanges'         => 'Tuoreet muutokset',
'recentchangestext'     => 'Tältä sivulta voi seurata tuoreita {{grammar:illative|{{SITENAME}}}} tehtyjä muutoksia.
[[{{ns:project}}:Tervetuloa {{grammar:illative|{{SITENAME}}}}|Tervetuloa {{grammar:illative|{{SITENAME}}}}!]] Katso seuraavia sivuja: [[{{ns:project}}:Kysymyksiä ja vastauksia {{grammar:elative|{{SITENAME}}}}|Useimmin kysyttyjä asioita]] ja [[{{ns:project}}:Sääntöjä ja ohjeita|{{grammar:genitive|{{SITENAME}}}} säännöt]] (erityisesti [[{{ns:project}}:Merkitsemiskäytäntöjä|Merkitsemiskäytännöt]], [[{{ns:project}}:Neutraali näkökulma|Neutraali näkökulma]]).
Jos haluat nähdä {{grammar:genitive|{{SITENAME}}}} onnistuvan, on erittäin tärkeää, että et lisää materiaalia,
jonka käyttöä rajoittavat [[Project:{{SITENAME}} ja tekijänoikeudet|tekijänoikeudet]].
Oikeudelliset seuraukset voivat vahingoittaa projektia vakavasti, joten kunnioita muiden tekijänoikeuksia.
Katso myös [http://meta.wikipedia.org/wiki/Special:Recentchanges recent meta discussion].', // tuo teksti ei enää käytössä ehkä, mutta pidetään silti -TJ
'rcloaderr'            => 'Ladataan tuoreita muutoksia',
'rcnote'               => 'Alla on <strong>$1</strong> tuoreinta muutosta viimeisten <strong>$2</strong> päivän ajalta.',
'rcnotefrom'           => 'Alla on muutokset <b>$2</b> lähtien (<b>$1</b> asti).',
'rclistfrom'           => 'Näytä uudet muutokset $1 alkaen',
'showhideminor'        => "$1 pienet muutokset | $2 botit | $3 kirjautuneet | $4 vahditut ",
'rclinks'              => " Näytä $1 tuoretta muutosta viimeisten $2 päivän ajalta.<br />$3",
'rchide'               => "muodossa $4 ; $1 pientä muutosta; $2 toissijaista nimiavaruutta; $3 moninkertaista muutosta.",
'rcliu'                => " ; $1 muokkausta sisäänkirjautuneilta ",
'diff'                 => 'ero',
'hist'                 => 'historia',
'hide'                 => 'piilota',
'show'                 => 'näytä',
'tableform'            => 'taulukko',
'listform'             => 'luettelo',
'nchanges'             => '$1 muutosta',
'minoreditletter'      => 'p',
'newpageletter'        => 'U',
'sectionlink'          => '&rarr;', // TODO: CHECK

# Upload
#
'upload'               => 'Tallenna tiedosto',
'uploadbtn'            => 'Tallenna tiedosto',
'uploadlink'           => 'Tallenna kuvia',
'reupload'             => 'Tallenna uudelleen',
'reuploaddesc'         => 'Paluu tallennuslomakkeelle.',
'uploadnologin'        => 'Ei sisäänkirjautumista',
'uploadnologintext'    => 'Sinun pitää olla [[Special:Userlogin||kirjautuneena sisään]] tallentaaksesi tiedoston.',
'uploadfile'           => 'Tallenna tiedosto',
'uploaderror'          => 'Tallennusvirhe',
'uploadtext'           => '<div style="border: 1px solid grey; background: #ddf; padding: 7px; margin: 0 auto;"> <strong>SEIS!</strong> Ennen kuin tallennat tiedostoja {{grammar:illative|{{SITENAME}}}}, tutustu [[Project:Kuvien_käyttösäännöt|kuvien käyttösääntöihin]] ja noudata niitä. <ul><li><em>Kirjoita kuvan tietoihin tarkka tieto kuvan lähteestä.</em> Jos teit kuvan itse, sano se. Jos löysit kuvan Internetistä, laita mukaan linkki kyseiselle sivulle.<br /> </li> <li><em>Kerro kuvan tekijänoikeuksien tila.</em> Lisää teksti <tt>{{GFDL}}</tt> tekstiisi, jos tiedosto on lisensoitu [[GNU FDL]]:n mukaisesti, <tt>{{PD}}</tt> jos kuva on [[public domain]]. Katso [[Project:{{ns:10}}#Tekijänoikeus|mallineet]] nähdäksesi lisää näitä valmiita mallineita.<br /> </li> <li><em>Käytä järkevää tiedostonimeä.</em> Nimeä tiedostosi mieluummin tyyliin "Eiffel-torni Pariisissa, yökuva.jpg" kuin "etpan1024c.jpg". Näin vältät mahdollisesti jo olemassaolevan kuvan korvaamisen omallasi. Voit etsiä olemassaolevia kuvia [[Special:Imagelist|Luettelo kuvista]]-sivulta.<br /> </li> <li>Laita johonkin aiheeseen liittyvään artikkeliin linkki kyseiseen tiedostoon, tai käytä kuvaussivua kuvataksesi mitä tiedosto sisältää (mieluummin toteuta molemmat).<br /> </li> <li>Näyttääksesi tai etsiäksesi aiemmin tallennettuja kuvia, katso [[Special:Imagelist|luetteloa tallennetuista kuvista]]. Tallennukset ja poistot kirjataan [[Project:Tallennusloki|tallennuslokiin]].<br /> </li> </ul> <p>Suositeltavimmat kuvaformaatit ovat [[JPEG]] valokuville, [[PNG]] piirroksille ja kuvakkeille ja [[Ogg Vorbis]] äänille. Nimeä tiedostosi kuvaavasti välttääksesi sekaannuksia. Liittääksesi kuvan artikkeliin käytä seuraavan muotoista linkkiä <b>[[Kuva:tiedosto.jpg]]</b> tai <b>[[Kuva:tiedosto.png|kuvausteksti]]</b> tai <b>[[media:tiedosto.ogg]]</b> äänille. <p>Huomaa, että  {{grammar:genitive|{{SITENAME}}}} sivuilla muut voivat muokata tai poistaa tallentamasi tiedoston, jos he katsovat, että se ei palvele projektin tarpeita, ja sinun tallentamismahdollisuutesi voidaan estää, jos väärinkäytät järjestelmää. </div> <p>Käytä alla olevaa lomaketta tallentaaksesi uusia kuvatiedostoja artikkelien kuvittamista varten. Useimmissa selaimissa näet "Browse..." tai "Selaa..."-painikkeen, josta aukeaa käyttöjärjestelmäsi normaali tiedostonavausikkuna. Valitsemalla tiedoston täydentyy tiedoston nimi painikkeen vieressä olevaan tekstikenttään. Sinun täytyy myös kuitata, että et riko tekijänoikeuksia tallentaessasi tiedostoa. Paina "Tallenna"-painiketta tallentaaksesi. Tämä voi kestää jonkin aikaa, jos sinulla on hidas Internet-yhteys.',
'uploadlog'           => 'Tallennusloki',
'uploadlogpage'       => 'Tallennusloki',
'uploadlogpagetext'   => 'Alla on luettelo uusimmista tallennuksista. Kaikki ajat näytetään palvelimen aikoina (UTC).',
'filename'            => 'Tiedoston nimi',
'filedesc'            => 'Yhteenveto',
'filestatus'          => 'Tiedoston tekijänoikeudet',
'filesource'          => 'Lähde',
'affirmation'         => 'Lupaan, että tämän tiedoston tekijänoikeuksien haltija sallii sen käytön $1-lisenssin mukaisesti.',
'copyrightpage'       => 'Project:Tekijänoikeudet',
'copyrightpagename'   => '{{SITENAME}} ja tekijänoikeudet',
'uploadedfiles'       => 'Tallennetut tiedostot',
'noaffirmation'       => 'Vahvista, ettei lähettämäsi tiedosto riko tekijänoikeuksia.',
'ignorewarning'       => 'Jätä tämä varoitus huomiotta, ja tallenna tiedosto.',
'minlength'           => 'Kuvan nimessä pitää olla vähintään kolme merkkiä.',
'illegalfilename'     => 'Tiedoston nimessä "$1" on merkkejä, joita ei sallita sivujen nimissä. Vaihda tiedoston nimeä, ja yritä tallentamista uudelleen.',
'badfilename'         => 'Kuva on siirretty nimelle "$1".',
'badfiletype'         => '".$1" ei ole suositeltavassa tiedostomuodossa.',
'largefile'           => 'Kuvien ei tulisi olla yli 100 kilotavun kokoisia.',
'emptyfile'           => 'Tallentamasi tiedosto näyttäisi olevan tyhjä. Tämä voi johtua kirjoitusvirheestä tiedoston nimessä. Tarkista, haluatko tallentaa juuri tämän tiedoston.',
'fileexists'          => 'Tämän niminen tiedosto on jo olemassa. Tarkista $1, ellet ole varma, että haluat muuttaa sitä.',
'successfulupload'    => 'Tallennus onnistui',
'fileuploaded'        => 'Tiedosto "$1" on tallennettu onnistuneesti. Seuraa linkkiä ($2) kuvaussivulle, ja täytä kuvaan liityvät tiedot, kuten mistä se on peräisin, milloin se on luotu, kuka sen loi ja mahdollisesti muita tietämiäsi tietoja. Jos tiedosto on kuva, voit lisätä sen artikkeliin näin: <tt>[[Kuva:$1|thumb|Kuvaus]]</tt>',
'uploadwarning' => 'Tallennusvaroitus',
'savefile'    => 'Tallenna',
'uploadedimage' => 'tallennettiin "[[$1]]"',
'uploaddisabled' => 'Tiedostojen lähettäminen on toistaiseksi poissa käytöstä.',

# Image list
#
'imagelist'   => 'Luettelo kuvista',
'imagelisttext'       => 'Alla on $1 kuvan luettelo lajiteltuna $2.',
'getimagelist'        => 'noudetaan kuvaluettelo',
'ilshowmatch' => 'Haku kuvista: ',
'ilsubmit'            => 'Hae',
'showlast'            => 'Näytä viimeiset $1 kuvaa lajiteltuna $2.',
'byname'              => 'nimen mukaan',
'bydate'              => 'päiväyksen mukaan',
'bysize'              => 'koon mukaan',
'imgdelete'           => 'poista',
'imgdesc'             => 'kuvaus',
'imglegend'           => 'Merkinnät: (kuvaus) = näytä/muokkaa kuvan kuvausta.',
'imghistory'  => 'Kuvan historia',
'revertimg'           => 'palauta',
'deleteimg'           => 'poista',
'deleteimgcompletely'         => 'poista',
'imghistlegend' => 'Merkinnät: (nyk.) = nykyinen kuva, (poista) = poista 
tämä vanha versio, (palauta) = palauta kuva tähän vanhaan versioon.
<br /><i>Klikkaa päiväystä nähdäksesi silloin tallennettu kuva</i>.',
'imagelinks'  => 'Kuvalinkit',
'linkstoimage'        => 'Seuraavilta sivuilta on linkki tähän kuvaan:',
'nolinkstoimage' => 'Tähän kuvaan ei ole linkkejä miltään sivulta.',

# Statistics
#
'statistics'  => 'Tilastoja',
'sitestats'           => 'Sivuston tilastoja',
'userstats'           => 'Käyttäjätilastoja',
'sitestatstext' => "Tietokannassa on yhteensä <b>$1</b> sivua. Tähän on laskettu mukaan keskustelusivut, {{grammar:elative|{{SITENAME}}}} kertovat sivut, lyhyet \"tynkäsivut\", uudelleenohjaukset sekä muita sivuja, joita ei voi pitää kunnollisina artikkeleina. Nämä poislukien tietokannassa on <b>$2</b> sivua, joita voidaan todennäköisesti pitää oikeina artikkeleina. <!--<p> Sivuja on katsottu yhteensä <b>$3</b> kertaa ja muokattu <b>$4</b> kertaa ohjelmiston päivittämisen jälkeen (20. heinäkuuta 2002). Keskimäärin yhtä sivua on muokattu <b>$5</b> kertaa, ja muokkausta kohden sivua on katsottu keskimäärin <b>$6</b> kertaa.--> ===Tarkempia tilastoja=== *[[{{ns:project}}:Tilastot]] *[http://www.wikipedia.org/wikistats/EN/Sitemap.htm Wikitilastot]",

# Maintenance Page
#
'maintenance'         => 'Ylläpitosivu',
'maintnancepagetext'  => 'Tämä sivu sisältää useita käteviä työkaluja jokapäiväistä ylläpitoa varten. Jotkut näistä toiminnoista kuormittavat tietokantaa, joten ole hyvä äläkä paina päivitysnappia jokaisessa kohdassa ;-)',
'maintenancebacklink' => 'Takaisin ylläpitosivulle',
'disambiguations'     => 'Tarkennussivu',
'disambiguationspage' => '{{ns:project}}:Linkkejä_tarkennussivuihin',
'disambiguationstext' => 'Seuraavat artikkelit linkittävät <i>tarkennussivuun</i>. Sen sijasta niiden pitäisi linkittää asianomaiseen aiheeseen.<br />Sivua kohdellaan tarkennussivuna jos siihen on linkki sivulta $1.<br />Linkkejä muihin nimiavaruuksiin <i>ei</i> ole listattu tässä.',
'doubleredirects'     => 'Kaksinkertaiset uudelleenohjaukset',
'doubleredirectstext' => '<b>Huomio:</b> Tässä listassa saattaa olla virheitä. Yleensä kyseessä on sivu, jossa ensimmäisen #REDIRECTin jälkeen on tekstiä.<br />\nJokaisella rivillä on linkit ensimmäiseen ja toiseen uudelleenohjaukseen sekä toisen uudelleenohjauksen kohteen ensimmäiseen riviin, eli yleensä \'oikeaan\' kohteeseen, johon ensimmäisen uudelleenohjauksen pitäisi osoittaa.',
'brokenredirects'     => 'Virheelliset uudelleenohjaukset',
'brokenredirectstext' => 'Seuraavat uudelleenohjaukset on linkitetty artikkeleihin, joita ei ole olemassa.',
'selflinks'           => 'Sivut, jotka linkittävät itseensä',
'selflinkstext'               => 'Seuraavat sivut sisältävät linkkejä itseensä, vaikka ei pitäisi.',
'mispeelings'           => 'Kirjoitusvirheitä sisältävät sivut',
'mispeelingstext' => 'Seuraavat sivut sisältävät yleisen kirjoitusvirheen, joka on lueteltu sivulla $1. Oikea kirjoitustapa on ehkä annettu (tähän tapaan).',
'mispeelingspage'       => 'Lista tavallisimmista kirjoitusvirheistä',
'missinglanguagelinks'  => 'Puuttuvat kielilinkit',
'missinglanguagelinksbutton'    => 'Etsi puuttuvat kielilinkit',
'missinglanguagelinkstext'      => 'Näitä artikkeleita <i>ei</i> ole linkitetty vastineeseensa $1:ssä. Uudelleenohjauksia ja alasivuja <i>ei</i> ole näytetty.', // suks

# Miscellaneous special pages
#
'orphans'     => 'Orposivut',
'geo'           => 'GEO-koordinaatit',
'validate'              => 'Kelpuuta sivu',
'lonelypages' => 'Yksinäiset sivut',
'uncategorizedpages'    => 'Luokittelemattomat sivut',
'unusedimages'        => 'Käyttämättömät kuvat',
'popularpages'        => 'Suositut sivut',
'nviews'              => '$1 latausta',
'wantedpages' => 'Halutut sivut',
'nlinks'              => '$1 linkkiä',
'allpages'            => 'Kaikki sivut',
'randompage'  => 'Satunnainen sivu',
'shortpages'  => 'Lyhyet sivut',
'longpages'           => 'Pitkät sivut',
'deadendpages'  => 'Sivut, joilta ei linkkejä',
'listusers'           => 'Käyttäjälista',
'listadmins'    => 'Ylläpitäjälista',
'specialpages'        => 'Toimintosivut',
'spheading'           => 'Toimintosivut',
'sysopspheading' => 'Toimintosivut järjestelmän ylläpitäjille',
'developerspheading' => 'Toimintosivut ohjelmoijille',
'protectpage' => 'Suojaa sivu',
'recentchangeslinked' => 'Tähän liittyvät muutokset',
'rclsub'                => "(sivuihin on linkki sivulta \"$1\")",
'debug'                       => 'Virheenetsintä',
'newpages'            => 'Uudet sivut',
'ancientpages'          => 'Kauan muokkaamattomat sivut',
'intl'          => 'Kieltenväliset linkit',
'move' => 'Siirrä',
'movethispage'        => 'Siirrä tämä sivu',
'unusedimagestext' => '<p>Huomaa, että muut verkkosivut, kuten toiset Wikipediat, saattavat viitata kuvaan suoran URL:n kautta, jolloin kuva saattaa olla tässä listassa vaikka sitä käytetäänkin.',
'booksources' => 'Kirjalähteet',
'booksourcetext' => 'Alla on lista linkeistä ulkopuolisiin sivustoihin,
joilla myydään uusia ja käytettyjä kirjoja. Niillä voi myös olla lisätietoa
kirjoista, joita etsit. {{SITENAME}} ei liity mitenkään niihin, eikä
tätä listaa tule pitää suosituksena tai hyväksyntänä.',
'isbn'  => 'ISBN',
'rfcurl' =>  "http://www.faqs.org/rfcs/rfc$1.html",
'alphaindexline' => "Alkaen sivusta $1 päättyen sivuun $2",
'version'               => 'Versio',
'log'           => 'Lokit',
'alllogstext'   => 'Yhdistetty tallennus-, poisto-, suojaus-, esto- ja ylläpitolokien näyttö.
Voit rajoittaa listaa valitsemalla lokityypin, käyttäjän tai sivun johon muutos on kohdistunut.',

# Special:Allpages
'nextpage'          => 'Seuraava sivu ($1)',
'articlenamespace'  => '(artikkelit)',
'allpagesformtext'  => 'Näytä sivuja alkaen sivusta: $1 Valitse nimiavaruus: $2 $3',
'allarticles'       => 'Kaikki artikkelit',
'allpagesprev'      => 'Edellinen',
'allpagesnext'      => 'Seuraaa',
'allinnamespace' => 'Kaikki sivut (nimiavaruudessa $1 )',
'allpagessubmit'    => 'Mene',

# Email this user
#
'mailnologin' => 'Lähettäjän osoite puuttuu',
'mailnologintext' => "Sinun pitää olla [[Special:Userlogin|kirjautuneena sisään]] ja [[Special:Preferences|asetuksissasi]] pitää olla toimiva sähköpostiosoite jotta voit lähettää sähköpostia muille käyttäjille.",
'emailuser'           => 'Lähetä sähköpostia tälle käyttäjälle',
'emailpage'           => 'Lähetä sähköpostia käyttäjälle',
'emailpagetext'       => 'Jos tämä käyttäjä on antanut asetuksissaan kelvollisen
sähköpostiosoitteen, alla olevalla lomakeella voi lähettää yhden viestin.
Omissa asetuksissasi annettu sähköpostiosoite tulee näkymään sähköpostin lähettäjän osoitteena, jotta vastaanottaja voi vastata.',
'usermailererror' => 'Postitus palautti virheen: ',
'defemailsubject'  => "{{SITENAME}} e-mail",
'noemailtitle'        => 'Ei sähköpostiosoitetta',
'noemailtext' => 'Tämä käyttäjä ei ole määritellyt kelpoa sähköpostiosoitetta tai ei halua postia muilta käyttäjiltä.',
'emailfrom'           => 'Lähettäjä',
'emailto'             => 'Vastaanottaja',
'emailsubject'        => 'Aihe',
'emailmessage'        => 'Viesti',
'emailsend'           => 'Lähetä',
'emailsent'           => 'Sähköposti lähetetty',
'emailsenttext' => 'Sähköpostiviestisi on lähetetty.',

# Watchlist
#
'watchlist'   => 'Tarkkailulista',
'watchlistsub'          => "(käyttäjälle \"$1\")",
'nowatchlist' => 'Tarkkailulistallasi ei ole sivuja.',
'watchnologin'        => 'Et ole kirjautunut sisään',
'watchnologintext'      => "Sinun pitää olla [[Special:Userlogin|kirjautunut sisään]] muokataksesi tarkkailulistaasi.",
'addedwatch'  => 'Lisätty tarkkailulistalle',
'addedwatchtext'        => "Sivu \"$1\" on lisätty [[Special:Watchlist|tarkkailulistallesi]]. Tulevaisuudessa sivuun ja sen keskustelusivuun tehtävät muutokset listataan täällä. Sivu on '''lihavoitu''' [[Special:Recentchanges|viimeisimpien muutosten listassa]], jotta huomaisit sen helpommin. Jos haluat myöhemmin poistaa sivun tarkkailulistaltasi, napsauta linkkiä ''lopeta tarkkailu'' sivun reunassa.",
'removedwatch'        => 'Poistettu tarkkailulistalta',
'removedwatchtext'      => "Sivu \"$1\" on poistettu tarkkailulistaltasi.",
'watch' => 'Tarkkaile',
'watchthispage'       => 'Tarkkaile tätä sivua',
'unwatch' => 'Lopeta tarkkailu',
'unwatchthispage' => 'Lopeta tarkkailu',
'notanarticle'        => 'Ei ole artikkeli',
'watchnochange'         => 'Näytettynä ajanjaksona, mitään tarkkailemistasi sivuista ei muokattu.',
'watchdetails'          => "(Keskustelusivuja mukaan laskematta tarkkailun alla on $1 sivua. <a href='$4'>Muokkaa listaa</a>.)",
'watchmethod-recent'=> 'tarkistetaan tuoreimpia muutoksia tarkkailluille sivuille',
'watchmethod-list'      => 'tarkistetaan tarkkailtujen sivujen tuoreimmat muutokset',
'removechecked'         => 'Poista valitut sivut tarkkailulistalta',
'watchlistcontains' => "Tarkkailulistallasi on $1 sivua.",
'watcheditlist'         => 'Tässä on aakkostettu lista tarkkailemistasi sivuista. Merkitse niiden sivujen ruudut, jotka haluat poistaa tarkkailulistaltasi.',
'removingchecked'       => 'Poistetaan toivotut sivut tarkkailulistalta...',
'couldntremove'         => "Ei voitu poistaa sivua '$1'...",
'iteminvalidname'       => "Sivunimen '$1' kanssa oli ongelmia, ei ole toimiva nimi...",
'wlnote'                => "Alla ovat $1 muutosta viimeisen <b>$2</b> tunnin ajalta.",
'wlshowlast'            => "Näytä viimeiset $1 tuntia $2 päivää $3",
'wlsaved'               => 'Tämä on tallennettu versio tarkkailulistastasi.',

# Delete/protect/revert
#
'deletepage'  => 'Poista sivu',
'confirm'             => 'Vahvista',
'excontent' => 'sisälsi:',
'exbeforeblank' => 'ennen tyhjentämistä sisälsi:',
'exblank' => 'oli tyhjä',
'confirmdelete' => 'Vahvista poisto',
'deletesub'             => "(Poistetaan \"$1\")",
'confirmdeletetext' => 'Olet tuhoamassa pysyvästi sivun tai kuvan ja kaiken sen historian tietokannasta. Vahvista, että todella aiot tehdä näin ja että ymmärrät seuraukset, sekä että teet tämän [[Project:Policy|{{grammar:genitive|{{SITENAME}}}} käytännön]] mukaisesti.',
'actioncomplete' => 'Toiminto suoritettu',
'deletedtext'   => "\"$1\" on poistettu. Katso $2 nähdäksesi tallenteen viimeaikaisista poistoista.",
'deletedarticle' => 'poisti sivun \'[[$1]]\'',
'dellogpage'  => 'Poistoloki',
'dellogpagetext' => 'Alla on lista viimeisimmistä poistoista.
Kaikki ajat ovat palvelimen ajassa (UTC).',
'deletionlog' => 'poistoloki',
'reverted'    => 'Palautettu aikaisempaan versioon',
'deletecomment'       => 'Poistamisen syy',
'imagereverted' => 'Aikaisempaan versioon palauttaminen onnistui.',
'rollback'    => 'palauta aiempaan versioon',
'rollback_short' => 'Palautus',
'rollbacklink'        => 'palauta',
'rollbackfailed' => 'Palautus epäonnistui',
'cantrollback'        => 'Aiempaan versioon ei voi palauttaa; viimeisin kirjoittaja on artikkelin ainoa tekijä.',
'alreadyrolled' => " Käyttäjän [[{{ns:2}}:$2|$2]] ([[{{ns:3}}:$2|keskustelu]]) tekemiä muutoksia sivuun [[$1]] ei voi palauttaa. Käyttäjä [[{{ns:2}}:$3|$3]] ([[{{ns:3}}:$3|keskustelu]]) on tehnyt uudempia muutoksia.",
#   only shown if there is an edit comment
'editcomment' => "Muokkauksen yhteenveto oli: \"<i>$1</i>\".",
'revertpage'    => "Käyttäjän [[Special:Contributions/$2|$2]] ([[{{ns:3}}:$2|keskustelu]]) muokkaukset palautettiin viimeisimpään käyttäjän $1 tekemään muutokseen.",
'protectlogpage' => 'Protection_log',
'protectlogtext' => "Alla on lista suojatuista ja suojaamattomista sivuista. Lisää tietoa löydät sivulta [[{{ns:4}}:Suojattu sivu]].",
'protectedarticle' => "suojattu [[$1]]",
'unprotectedarticle' => "suojaus poistettu sivulta [[$1]]",
'protectsub' =>"(Suojataan \"$1\")",
'confirmprotecttext' => 'Haluatko varmasti suojata tämän sivun?',
'confirmprotect' => 'Vahvista suojaus',
'protectcomment' => 'Suojauksen syy',
'unprotectsub' =>"(Poistetaan suojaus sivulta \"$1\")",
'confirmunprotecttext' => 'Haluatko varmasti poistaa tämän sivun suojauksen?',
'confirmunprotect' => 'Vahvista suojauksen poisto',
'unprotectcomment' => 'Syy suojauksen poistoon',
'protectreason' => '(anna syy)',

# Undelete
'undelete' => 'Palauta poistettu sivu',
'undelete_short' => 'Palauta $1 muokkausta',
'undeletepage' => 'Selaa ja palauta poistettuja sivuja',
'undeletepagetext' => 'Seuraavat sivut on poistettu, mutta ne löytyvät vielä arkistosta, joten ne ovat palautettavissa. Arkisto saatetaan tyhjentää aika ajoin.',
'undeletearticle' => 'Palauta poistettu artikkeli',
'undeleterevisions' => "$1 versiota arkistoitu.",
'undeletehistory' => 'Jos palautat artikkelin, kaikki versiot lisätään sivun historiaan.
Jos uusi, samanniminen sivu on luotu poistamisen jälkeen, palautetut versiot lisätään sen historiaan,
ja olemassa olevaa versiota ei korvata automaattisesti.',
'undeleterevision' => "Poistettu versio hetkellä $1",
'undeletebtn' => 'Palauta!',
'undeletedarticle' => "palautettu \"$1\"",
'undeletedtext'   => "Artikkeli [[$1]] on palautettu onnistuneesti.
Lista viimeisimmistä poistoista ja palautuksista on sivulla [[{{ns:4}}:Poistoloki]].",

# Contributions
#
'contributions'       => 'Käyttäjän muokkaukset',
'mycontris'   => 'Omat muokkaukset',
'contribsub'  => "Käyttäjälle $1",
'nocontribs'  => 'Näihin ehtoihin sopivia muokkauksia ei löytynyt.',
'ucnote'      => "Alla on <b>$1</b> viimeisintä tämän käyttäjän tekemää muokkausta viimeisten <b>$2</b> päivän aikana.",
'uclinks'     => "Katso $1 viimeisintä muokkausta; katso $2 viimeisintä päivää.",
'uctop'               => ' (uusin)' ,
'newbies'       => 'tulokkaat',

# What links here
#
'whatlinkshere'       => 'Tänne viittaavat sivut',
'notargettitle' => 'Ei kohdetta',
'notargettext'        => 'Et ole määritellyt kohdesivua tai -käyttäjää johon toiminto kohdustuu.',
'linklistsub' => '(Lista linkeistä)',
'linkshere'   => 'Seuraavat sivut on linkitetty tänne:',
'nolinkshere' => 'Tänne ei ole linkkejä.',
'isredirect'  => 'uudelleenohjaussivu',

# Block/unblock IP
#
'blockip'     => 'Aseta muokkausesto',
'blockiptext' => "Tällä lomakkeella voit estää käyttäjän tai IP-osoitteen muokkausoikeudet. Muokkausoikeuksien poistamiseen pitää olla syy, esimerkiksi artikkeleiden vandalisointi. Kirjoita syy siihen varattuun kenttään.

Vanhenemisajat noudattavat GNUn standardimuotoa, joka on kuvattu tar manuaalissa ([http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html] [EN]), esimerkiksi \"1 hour\", \"2 days\", \"next Wednesday\", \"1 January 2017\". Esto voi olla myös \"indefinite\" or \"infinite\" (kestää siihen asti, että se poistetaan).",
'ipaddress'           => 'IP-osoite tai käyttäjätunnus',
'ipbexpiry'             => 'Umpeutuu',
'ipbreason'           => 'Syy',
'ipbsubmit'           => 'Estä tämä osoite',
'badipaddress'        => 'IP-osoite on väärin muotoiltu.',
'noblockreason' => 'Sinun täytyy antaa syy estämiselle.',
'blockipsuccesssub' => 'Esto onnistui',
'blockipsuccesstext' => "Käyttäjä tai IP-osoite '''$1''' on estetty. <br />Nykyiset estot löytyvät [[Special:Ipblocklist|estolistalta]].",
'unblockip'           => 'Poista IP-osoitteen muokkausesto',
'unblockiptext'       => 'Käytä alla olevaa lomaketta poistaaksesi kirjoitusesto aikaisemmin estetyltä IP-osoitteelta.',
'ipusubmit'           => 'Poista tämän osoitteen esto',
'ipusuccess'    => "IP-osoitteen \"$1\" esto poistettu",
'ipblocklist' => 'Lista estetyistä IP-osoitteista',
'blocklistline' => " $1, $2 on estänyt käyttäjän $3 (umpeutuu $4)",
'blocklink'           => 'esto',
'unblocklink' => 'poista esto',
'contribslink'        => 'muokkaukset',
'autoblocker'   => "Automaattisesti estetty, koska jaat IP-osoitteen \"$1\" kanssa. Syy: \"$2\".",
'blocklogpage'  => 'Estoloki',
'blocklogentry' => 'estetty [[{{ns:2}}:$1|$1]] ([[{{ns:3}}:$1|keskustelu]]) ([[Special:Contributions/$1|muokkaukset]]), vanhenee \'\'$2\'\'',
'blocklogtext'  => 'Tässä on loki muokkausestoista ja niiden purkamisista. Automaattisesti estettyjä IP-osoitteita ei kirjata. Tutustu [[Special:Ipblocklist|estolistaan]] nähdäksesi listan tällä hetkellä voimassa olevista estoista.',
'unblocklogentry'       => 'poisti käyttäjältä \'$1\' muokkauseston',
'range_block_disabled'  => 'Ylläpitäjän toiminto luoda alue-estoja on poissa käytöstä.',
'ipb_expiry_invalid'    => 'Virheellinen umpeutumisaika.',
'ip_range_invalid'      => "Virheellinen IP-alue.\n",
'proxyblocker'  => 'Välityspalvelinesto',
'proxyblockreason'      => 'IP-osoitteestasi on estetty muokkaukset, koska se on avoin välityspalvelin. Ota yhteyttä Interet-palveluntarjoajaasi tai tekniseen tukeen ja kerro heillä tästä tietoturvaongelmasta.',
'proxyblocksuccess'     => "Valmis.\n",

# Developer tools
#
'lockdb'      => 'Lukitse tietokanta',
'unlockdb'    => 'Vapauta tietokanta',
'lockdbtext'  => 'Tietokannan lukitseminen estää käyttäjiä muokkaamasta sivuja, vaihtamasta asetuksia, muokkaamasta tarkkailulistoja ja tekemästä muita tietokannan muuttammista vaativia toimia. Ole hyvä ja vahvista, että tämä on tarkoituksesi, ja että vapautat tietokannan kun olet suorittanut ylläpitotoimet.', 
'unlockdbtext'        => 'Tietokannan vapauttaminen antaa käyttäjille mahdollisuuden muokkata sivuja, vaihtamaa asetuksia, muokkata tarkkailulistoja ja tehdä muita tietokannan muuttammista vaativia toimia. Ole hyvä ja vahvista, että tämä on tarkoituksesi.',
'lockconfirm' => 'Kyllä, haluan varmasti lukita tietokannan.',
'unlockconfirm'       => 'Kyllä, haluan varmasti vapauttaa tietokannan.',
'lockbtn'     => 'Lukitse tietokanta',
'unlockbtn'   => 'Vapauta tietokanta',
'locknoconfirm' => 'Et merkinnyt vahvistuslaatikkoa.',
'lockdbsuccesssub' => 'Tietokannan lukitseminen onnistui',
'unlockdbsuccesssub' => 'Tietokannan vapauttaminen onnistui',
'lockdbsuccesstext' => 'Tietokanta on lukittu.
<br />Muista vapauttaa tietokanta ylläpitotoimenpiteiden jälkeen.',
'unlockdbsuccesstext' => 'Tietokanta on vapautettu.',

# SQL query
#
'asksql'              => 'SQL-kysely',
'asksqltext'  => 'Tämä ominaisuus ei ole käytössä',
'sqlislogged'   => 'Panethan merkille, että kaikki kyselyt kirjataan ylös.',
'sqlquery'            => 'Kirjoita kysely',
'querybtn'            => 'Lähetä kysely',
'selectonly'    => 'Vain {{grammar:genitive|{{SITENAME}}}} kehittäjät voivat tehdä muita kuin "SELECT"-hakuja.',
'querysuccessful' => 'Kysely onnistui',

# Make sysop
'makesysoptitle'        => 'Tee käyttäjästä ylläpitäjä',
'makesysoptext'         => 'Byrokraatit voivat tällä lomakkeella tehdä käyttäjistä ylläpitäjiä.
Kirjoita laatikkoon sen käyttäjän nimi, josta haluat tehdä ylläpitäjän',
'makesysopname'         => 'Käyttäjän nimi:',
'makesysopsubmit'       => 'Tee käyttäjästä ylläpitäjä',
'makesysopok'           => '<b>Käyttäjä "$1" on nyt ylläpitäjä</b>',
'makesysopfail'         => '<b>Käyttäjästä "$1" ei voitu tehdä ylläpitäjää. (Kirjoititko nimen oikein?)</b>',
'setbureaucratflag' => 'Aseta byrokraattikenttä',
'bureaucratlog'         => 'Bureaucrat_log',
'bureaucratlogentry'    => 'Käyttäjän "$1" oikeudet asetettu seuraaviksi: "$2"',
'rights'                => 'Oikeudet:',
'set_user_rights'       => 'Aseta käyttäjän oikeudet',
'user_rights_set'       => '<b>Käyttäjän "$1" oikeudet päivitetty</b>',
'set_rights_fail'       => '<b>Käyttäjän "$1" oikeuksia ei voita asettaa. (Kirjoititko nimen oikein?)</b>',
'makesysop'             => 'Tee käyttäjästä ylläpitäjä',

//HOX Validation: missä näitä käytetään? -> http://meta.wikimedia.org/wiki/Article_validation
# Validation
'val_clear_old' => 'Poista sivulle $1 aiemmin antamani validiointitiedot',
'val_merge_old' => 'Käytä aiempaa arviotani, kun en ole antanut mielipidettä',
'val_form_note' => '<b>Vinkki:</b> Tietojen säilytys tarkoittaa, että kaikkiin
valitun version kohtiin, joihin <i>et ole antanut mielipidettä</i>, asetetaan
arvo ja kommentti vanhemmista versioista. Tiedot siirretään tuoreimmasta
mielipiteen sisältävästä versiosta. Jos esimerkiksi uutta versiota
validioidessasi haluat muuttaa mieltä yhdessä ainoassa kohdassa, aseta arvo
vain siihen, jolloin muut kohdat säilyvät samoina kuin ennenkin.',
'val_noop' => 'ei mielipidettä',
'val_percent' => '<b>$1%</b><br />($2 / $3 pistettä<br /> $4 käyttäjältä)',
'val_percent_single' => '<b>$1%</b><br />($2 / $3 pistettä<br /> yhdeltä käyttäjältä)',
'val_total' => 'Yhteensä',
'val_version' => 'Versio',
'val_tab' => 'Validioi',
'val_this_is_current_version' => 'tämä on tuorein versio',
'val_version_of' => '$1:n versio',
'val_table_header' => '<tr><th>luokka</th>$1<th colspan=4>mielipide</th>$1<th>kommentti</th></tr>\n',
'val_stat_link_text' => 'Tilastotietoa artikkelin validioinnista',
'val_view_version' => 'Katso tätä versiota',
'val_validate_version' => 'Validioi tämä versio',
'val_user_validations' => 'Käyttäjä on validioinut $1 sivua.',
'val_no_anon_validation' => 'Vain sisään kirjautuneet käyttäjät voivat validioida artikkeleita.',
'val_validate_article_namespace_only' => 'Vain artikkeleita voi validioida. Tämä sivu ei <i>ei</i> ole artikkeleiden nimiavaruudessa.',
'val_validated' => 'Validiointi on valmis.',
'val_article_lists' => 'Luettelo validioiduista artikkeleista',
'val_page_validation_statistics' => 'Tilastotietoa $1:n sivujen validioinneista',

# Move page
#
'movepage'    => 'Siirrä sivu',
'movepagetext'        => "Alla olevalla lomakkeella voit antaa sivulle uuden nimen, jolloin sen koko muokkaushistoria siirtyy mukana. Vanhasta sivusta tulee edelleenohjaussivu, joka johtaa uudelle sivulle. Vanhaan sivuun osoittavia linkkejä ei muuteta. Muista tehdä tarkistukset kaksinkertaisten tai rikkinäisten ohjausten varalta. Olet vastuussa siitä, että linkit osoittavat sinne, mihin niiden on tarkoituskin osoittaa. Huomaa, että sivua '''ei''' siirretä mikäli uusi nimi on jo jonkin sivun otsikkona, paitsi milloin kyseessä on tyhjä sivu tai edelleenohjaus, jolla ei ole muokkaushistoriaa. Voit siis nimetä sivun entisen nimiseksi mikäli siirsit sen erehdyksessä, mutta et voi kirjoittaa olemassaolevan sivun päälle. Jos haluat siirtää sivun toisen sivun päälle, ota yhteyttä [[Special:Listadmins|ylläpitäjään]]. <b>HUOMIO!</b> Saatat olla tekemässä huomattavaa ja odottamatonta muutosta suositulle sivulle. Ole varma, että ymmärrät seuraukset ennen kuin jatkat.",
'movearticle' => 'Siirrä sivu',
'movenologin' => 'Et ole kirjautunut sisään',
'movenologintext' => 'Sinun pitää olla rekisteröitynyt käyttäjäksi ja <a href="/wiki/{{ns:-1}}:Userlogin"> kirjautuneena sisään</a> jotta voisit siirtää sivun.',
'newtitle'    => 'Uusi nimi sivulle',
'movepagebtn' => 'Siirrä sivu',
'pagemovedsub'        => 'Siirto onnistui',
'pagemovedtext' => 'Sivu "[[$1]]" siirrettiin, uusi otsikko on "[[$2]]".',
'articleexists' => 'Siten nimetty sivu on jo olemassa, tai valittu nimi ei ole sopiva. Ole hyvä ja valitse uusi nimi.',
'talkexists'  => 'Sivun siirto onnistui, mutta keskustelusivua ei voitu siirtää, koska uuden otsikon alla on jo keskustelusivu. Ole hyvä ja yhdistä keskustelusivujen sisältö käsin.',
'movedto'             => 'Siirretty uudelle otsikolle',
'movetalk'            => 'Siirrä myös sen keskustelusivu, jos mahdollista.',
'talkpagemoved' => 'Myös artikkelin keskustelusivu siirrettiin.',
'talkpagenotmoved' => 'Artikkelin keskustelusivua <strong>ei</strong> siirretty.',
'1movedto2'             => '$1 siirretty sivulle $2',
'1movedto2_redir' => '$1 siirretty edelleenohjauksen päälle sivulle $2',

# Export

'export'                => 'Sivujen vienti',
'exporttext'    => 'Voit viedä sivun tai sivujen tekstiä ja muokkaushistoriaa XML-muodossa. Tulevaisuudessa tämä tieto voidaan tuoda johonkin toiseen wikiin, jossa käytetään MediaWiki-ohjelmistoa. Tämänhetkisessä MediaWikin versiossa tätä ei tosin vielä tueta.

Viedäksesi artikkelisivuja, syötä sivujen otsikoita riveittäin alla olevaan laatikkoon. Valitse myös, haluatko kaikki versiot sivuista, vai ainoastaan nykyisen version.

Jälkimmäisessä tapauksessa voit myös käyttää linkkiä, esim. [[Juna]]-artikkelin tapauksessa [[Special:Export/Juna]].',
'exportcuronly' => 'Liitä mukaan ainoastaan uusin versio, ei koko historiaa.',

# Namespace 8 related

'allmessages'   => 'Kaikki järjestelmäviestit',
'allmessagestext'       => 'Tämä on luettelo kaikista MediaWiki-nimiavaruudesta olevista viesteistä.',

# Thumbnails

'thumbnail-more'        => 'Suurenna',
'missingimage'          => '<b>Puuttuva kuva</b><br /><i>$1</i>\n',

# Special:Import
'import'        => 'Tuo sivuja',
'importtext'    => 'Vie sivuja lähde-wikistä käyttäen {{ns:-1}}:Export-työkalua. Tallenna tiedot koneellesi ja tallenna ne täällä.',
'importfailed'  => 'Tuonti epäonnistui: $1',
'importnotext'  => 'Tyhjä tai ei tekstiä',
'importsuccess' => 'Tuonti onnistui!',
'importhistoryconflict' => 'Sivusta on olemassa tuonnin kanssa ristiriitainen muokkausversio (tämä sivu on saatettu tuoda jo aikaisemmin)',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Etsi tästä wikistä [alt-f]',
'tooltip-minoredit' => 'Merkitse tämä pieneksi muutokseksi [alt-i]',
'tooltip-save' => 'Tallenna muokkauksesi [alt-s]',
'tooltip-preview' => 'Esikatsele muokkauksesi - käytä tätä ennen tallennusta! [alt-p]',
'tooltip-compareselectedversions' => 'Vertaile tämän sivun kahta valittua versiota. [alt-v]',

# stylesheets

'Monobook.css' => '/* tätä tiedostoa muokkaamalla voit muuttaa sivuston monobook-ulkoasua */',
#'Monobook.js' => '/* tätä tiedostoa muokkaamalla voit muuttaa sivuston javascriptejä */',

# Metadata
'nodublincore' => 'Dublin Core RDF-metatieto on poissa käytöstä tällä palvelimella.',
'nocreativecommons' => 'Creative Commonsin RDF-metatieto on poissa käytöstä tällä palvelimella.',
'notacceptable' => 'Wiki-palvelin ei voi näyttää tietoja muodossa, jota ohjelmasi voisi lukea.',

# Attribution

'anonymous' => "{{grammar:genitive|{{SITENAME}}}} anonyymit käyttäjät",
'siteuser' => "{{grammar:genitive|{{SITENAME}}}} käyttäjä $1",
'lastmodifiedby' => "Tätä sivua muokkasi viimeksi $2, $1.",
'and' => 'ja',
'othercontribs' => "Perustuu työlle, jonka teki $1.",
'others' => 'muut',
'siteusers' => "{{grammar:genitive|{{SITENAME}}}} käyttäjä(t) $1",
'creditspage' => 'Sivun tekijäluettelo',
'nocredits' => 'Tämän sivun tekijäluettelotietoja ei löydy.',

# Spam protection
//HOX?
'spamprotectiontitle' => 'Roskapostisuodatin',
'spamprotectiontext' => 'Roskapostisuodatin on lukinnut sivun, jonka haluat tallentaa. Syynä on todennäköisimmin Wikipedian ulkopuolelle osoittava linkki.',
'spamprotectionmatch' => 'Teksti joka ei läpäissyt roskapostisuodatinta: $1',
'subcategorycount' => 'Tällä luokalla on $1 alaluokkaa.',
'subcategorycount1' => 'Tällä luokalla on $1 alaluokka.',
'categoryarticlecount' => 'Tässä luokassa on $1 artikkelia.',
'categoryarticlecount1' => 'Tässä luokassa on $1 artikkeli.',
'usenewcategorypage' => '1\n\nLaita ensimmäiseksi merkiksi nolla, kun et halua käyttää uutta luokittelutyyliä.',

# Info page
"infosubtitle" => "Tietoja sivusta",
"numedits" => "Muokkausten määrä (artikkeli): $1",
"numtalkedits" => "Muokkausten määrä (keskustelusivu): $1",
"numwatchers" => "Tarkkailijoiden määrä: $1",
"numauthors" => "Erillisten kirjoittajien määrä (artikkeli): $1",
"numtalkauthors" => "Erillisten kirjoittajien määrä (keskustelusivu): $1",

# Math options
        'mw_math_png' => 'Näytä aina PNG:nä',
        'mw_math_simple' => 'Näytä HTML:nä, jos yksinkertainen, muuten PNG:nä',
        'mw_math_html' => 'Näytä HTML:nä, jos mahdollista, muuten PNG:nä',
        'mw_math_source' => 'Näytä TeX-muodossa (tekstiselaimille)',
        'mw_math_modern' => 'Suositus nykyselaimille',
        'mw_math_mathml' => 'MathML jos mahdollista (kokeellinen)',

// HOX
# Patrolling
'markaspatrolleddiff'   => "Merkitse tarkastetuksi",
'markaspatrolledlink'   => "<div class='patrollink'>[$1]</div>",
'markaspatrolledtext'   => "Merkitse artikkeli tarkastetuksi",
'markedaspatrolled'     => "Tarkastettu",
'markedaspatrolledtext' => "Valittu versio on tarkastettu.",
'rcpatroldisabled'      => "Tuoreiden muutosten tarkastus on pois käytöstä",
'rcpatroldisabledtext'  => "Tuoreiden muutosten tarkastustoiminto on toistaiseksi pois käytöstä.",

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => "/* tooltips and access keys */
ta = new Object();
ta['pt-userpage'] = new Array('.','Oma käyttäjäsivu'); 
ta['pt-anonuserpage'] = new Array('.','IP-osoitteesi käyttäjäsivu'); 
ta['pt-mytalk'] = new Array('n','Oma keskustelusivu'); 
ta['pt-anontalk'] = new Array('n','Keskustelu tämän IP-osoitteen muokkauksista'); 
ta['pt-preferences'] = new Array('','Omat asetukset'); 
ta['pt-watchlist'] = new Array('l','Lista sivuista joiden muokkauksia tarkkailet'); 
ta['pt-mycontris'] = new Array('y','Lista omista muokkauksista'); 
ta['pt-login'] = new Array('o','Toivomme että kirjautuisit sisään ennen muokkauksia, mutta tämä ei ole kuitenkaan välttämätöntä'); 
ta['pt-anonlogin'] = new Array('o','Toivomme että kirjautuisit sisään ennen muokkauksia, mutta tämä ei ole kuitenkaan välttämätöntä'); 
ta['pt-logout'] = new Array('o','Kirjaudu ulos'); 
ta['ca-talk'] = new Array('t','Keskustelua sisällöstä'); 
ta['ca-edit'] = new Array('e','Muokkaa tätä sivua'); 
ta['ca-addsection'] = new Array('+','Lisää kommentti tälle sivulle'); 
ta['ca-viewsource'] = new Array('e','Näytä sivun lähdekoodi'); 
ta['ca-history'] = new Array('h','Aikaisemmat versiot tästä sivusta'); 
ta['ca-protect'] = new Array('=','Suojaa tämä sivu'); 
ta['ca-delete'] = new Array('d','Poista tämä sivu'); 
ta['ca-undelete'] = new Array('d','Palauta tämän sivun muokkaukset, jotka tehtiin ennen sivun poistamista'); 
ta['ca-move'] = new Array('m','Siirrä tämä sivu'); 
ta['ca-nomove'] = new Array('','Sinulla ei ole oikeuksia siirtää tätä sivua'); 
ta['ca-watch'] = new Array('w','Lisää tämä sivu tarkkailulistallesi'); 
ta['ca-unwatch'] = new Array('w','Poista tämä sivu tarkkailulistaltasi'); 
ta['search'] = new Array('f','Etsi tästä wikistä'); 
ta['p-logo'] = new Array('','Etusivu'); 
ta['n-mainpage'] = new Array('z','Mene etusivulle'); 
ta['n-portal'] = new Array('','Keskustelua projektista'); 
ta['n-currentevents'] = new Array('','Etsi taustatietoa tämänhetkisistä tapahtumista'); 
ta['n-recentchanges'] = new Array('r','Lista tuoreista muutoksista'); 
ta['n-randompage'] = new Array('x','Avaa satunnainen artikkelisivu'); 
ta['n-help'] = new Array('','Ohjeita.'); 
ta['n-sitesupport'] = new Array('','Tue sivuston toimintaa'); 
ta['t-whatlinkshere'] = new Array('j','Lista kaikista wikin sivuista jotka viittavat tänne'); 
ta['t-recentchangeslinked'] = new Array('k','Viimeisimmät muokkaukset artikkeleissa, jotka viittavat tälle sivulle'); 
ta['feed-rss'] = new Array('','RSS-syöte tälle sivulle'); 
ta['feed-atom'] = new Array('','Atom-syöte tälle sivulle'); 
ta['t-contributions'] = new Array('','Näytä lista tämän käyttäjän muokkauksista'); 
ta['t-emailuser'] = new Array('','Lähetä sähköpostia tälle käyttäjälle'); 
ta['t-upload'] = new Array('u','Tallenna kuvia tai muita mediatiedostoja'); 
ta['t-specialpages'] = new Array('q','Näytä toimintosivut'); 
ta['ca-nstab-main'] = new Array('c','Näytä sisältösivu'); 
ta['ca-nstab-user'] = new Array('c','Näytä käyttäjäsivu'); 
ta['ca-nstab-media'] = new Array('c','Näytä mediasivu'); 
ta['ca-nstab-special'] = new Array('','Tämä on toimintosivu, et voi muokata sivua.'); 
ta['ca-nstab-wp'] = new Array('a','Näytä projektisivu'); 
ta['ca-nstab-image'] = new Array('c','Näytä kuvasivu'); 
ta['ca-nstab-mediawiki'] = new Array('c','Näytä järjestelmäviesti'); 
ta['ca-nstab-template'] = new Array('c','Näytä malline'); 
ta['ca-nstab-help'] = new Array('c','Näytä ohjesivu'); 
ta['ca-nstab-category'] = new Array('c','Näytä luokkasivu');",

# image deletion
'deletedrevision' => 'Poistettiin vanha versio $1.',

# browsing diffs
'previousdiff' => '&larr; Mene edelliseen ', // +"diff"
'nextdiff' => 'Mene seuraavaan &rarr;', // +"diff"

'imagemaxsize' => 'Rajoita kuvien kokoa kuvien kuvaussivuilla arvoon: ',
'showbigimage' => 'Lataa korkeatarkkuuksinen versio ($1×$2, $3 KiB)',

'newimages' => 'Galleria uusista kuvista',


);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

require_once( 'LanguageUtf8.php' );

class LanguageFi extends LanguageUtf8 {
	function LanguageFi() {
		global $wgNamespaceNamesFi, $wgMetaNamespace;
		$wgNamespaceNamesFi[NS_WP_TALK] = 'Keskustelu_' . $this->convertGrammar( $wgMetaNamespace, 'elative' );
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

	function getUserToggle( $tog ) {
		$togs =& $this->getUserToggles();
		return wfMsg("tog-".$tog);
	}

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		     $this->getMonthName( substr( $ts, 4, 2 ) ) . "ta " .
		     substr( $ts, 0, 4 );
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
		return $this->date( $ts, $adj ) . " kello " . $this->time( $ts, $adj );
	}

	function getMessage( $key )
	{
		global $wgAllMessagesFi;
		if( isset( $wgAllMessagesFi[$key] ) ) {
			return $wgAllMessagesFi[$key];
		} else {
			return Language::getMessage( $key );
		}
	}

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{grammar:case|word}}
	function convertGrammar( $word, $case ) {
		# These rules are not perfect, but they are currently only used for site names so it doesn't
		# matter if they are wrong sometimes. Just add a special case for your site name if necessary. 
		# TODO: in the future add better version.
		switch ( $case ) {
			case 'genitive':
				$word .= 'n';
				break;
			case 'elative':
				if ( mb_substr($word, -1) == 'y' ) {
					$word .= 'stä';
				} else {
					$word .= 'sta';
				}
				break;
			case 'partitive':
				if ( mb_substr($word, -1) == 'y' ) {
					$word .= 'ä';
				} else {
					$word .= 'a';
				}
				break;
			case 'illative':
				# Double the last letter and add 'n'
				# mb_substr has a compatibility function in GlobalFunctions.php
				$word = $word . mb_substr($word,-1) . 'n';
				break;
			case 'inessive':
				if ( mb_substr($word, -1) == 'y' ) {
					$word .= 'ssä';
				} else {
					$word .= 'ssa';
				}
				break;


		}
		return $word;
	}
}
?>
