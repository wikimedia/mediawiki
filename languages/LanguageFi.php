<?php

# FIXME: use $wgSitename, $wgMetaNamespace instead of hard-coded Wikipedia

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
# See Language.php for more notes.

/* private */ $wgNamespaceNamesFi = array(
	-2	=> "Media",
	-1	=> "Toiminnot",
	0	=> "",
	1	=> "Keskustelu",
	2	=> "Käyttäjä",
	3	=> "Keskustelu_käyttäjästä",
	4	=> "Wikipedia",
	5	=> "Keskustelu_Wikipediasta",
	6	=> "Kuva",
	7	=> "Keskustelu_kuvasta",
	8	=> "MediaWiki",
	9	=> "MediaWiki_talk",
	10  => "Template",
	11  => "Template_talk"

);

/* private */ $wgQuickbarSettingsFi = array(
	"Ei mitään", "Tekstin mukana, vasen", "Tekstin mukana, oikea",
        "Pysyen vasemmalla"
);

/* private */ $wgSkinNamesFi = array(
	'standard' => "Perus",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Kölnin sininen",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);



/* private */ $wgValidSpecialPagesFi = array(
	"Userlogin"		=> "Sisäänkirjautuminen",
	"Userlogout"	=> "Uloskirjautuminen",
	"Preferences"	=> "Käyttäjäasetukset",
	"Watchlist"		=> "Tarkkaillut sivut",
	"Recentchanges" => "Viimeisimmät muutokset",
	"Upload"		=> "Lataa kuvatiedostoja",
	"Imagelist"		=> "Luettelo kuvista",
	"Listusers"		=> "Rekisteröityneet käyttäjät",
	"Statistics"	=> "Tilastot",
	"Randompage"	=> "Arvottu artikkeli",

	"Lonelypages"	=> "Orvot artikkelit",
	"Unusedimages"	=> "Orvot kuvat",
	"Popularpages"	=> "Suosituimmat artikkelit",
	"Wantedpages"	=> "Halutuimmat artikkelit",
	"Shortpages"	=> "Lyhyet artikkelit",
	"Longpages"		=> "Pitkät artikkelit",
	"Newpages"		=> "Uudet artikkelit",
	"Ancientpages"	=> "Oldest pages",
	"Allpages"		=> "Kaikki sivut otsikon mukaan järjestettynä",

	"Ipblocklist"	=> "Estetyt IP-osoitteet",
	"Maintenance" => "Ylläpitosivu",
	"Specialpages"  => "Toiminnot",
	"Contributions" => "Muokkaukset",
	"Emailuser"	=> "Lähetä käyttäjälle sähköposti",
	"Whatlinkshere" => "Viittaukset tähän sivuun",
	"Recentchangeslinked" => "Tuoreet muokkaukset linkitettyihin",
	"Movepage"	=> "Siirrä sivu",
	"Booksources"	=> "Ulkoiset kirjaviitteet",
	"Export"	=> "XML export",
	"Version"	=> "Version",
);

/* private */ $wgSysopSpecialPagesFi = array(
	"Blockip"	=> "Muokkausesto",
	"Asksql"	=> "Tietokantahaku",
	"Undelete"	=> "Palauta poistetut"
);

/* private */ $wgDeveloperSpecialPagesFi = array(
	"Lockdb"	=> "Lukitse tietokanta lukutilaan",
	"Unlockdb"	=> "Vapauta tietokannan lukitus",
);


/* private */ $wgAllMessagesFi = array(
'special_version_prefix' => '',
'special_version_postfix' => '',
# User Toggles

"tog-hover"		=> "Näytä vinkki wiki-linkkien päällä",
"tog-underline" => "Alleviivaa linkit",
"tog-highlightbroken" => "Näytä linkit puuttuville <a href=\"\" class=\"new\">näin </a> (vaitoehtoisesti näin: <a href=\"\" class=\"internal\">?</a>).",
"tog-justify"	=> "Tasaa kappaleet",
"tog-hideminor" => "Piilota pienet muutokset tuoreimpien muutosten listasta",
"tog-usenewrc" => "Kehittynyt tuoreet muutokset (ei kaikille selaimille)",
"tog-numberheadings" => "Numeroi otsikot",
"tog-showtoolbar" => "Show edit toolbar",
"tog-rememberpassword" => "Älä kysy salasanaa jos sama yhteys",
"tog-editwidth" => "Muokkausruutu käyttää koko ikkunanleveyttä",
"tog-editondblclick" => "Muokkaa sivuja kaksoisklikkauksella (JavaScript)",
"tog-watchdefault" => "Vahdi luomiani ja muokkaamiani sivuja",
"tog-minordefault" => "Muutokseni ovat pieniä ellen muuta sano",
"tog-previewontop" => "Näytä esikatselu ennen muokkausruutua, eikä jälkeen",
# Dates
'sunday' => 'sunnuntai',
'monday' => 'maanantai',
'tuesday' => 'tiistai',
'wednesday' => 'keskiviikko',
'thursday' => 'torstai',
'friday' => 'perjantai',
'saturday' => 'lauantai',
'january' => 'tammikuu',
'february' => 'helmikuu',
'march' => 'maaliskuu',
'april' => 'huhtikuu',
'may_long' => 'toukokuu',
'june' => 'kesäkuu',
'july' => 'heinäkuu',
'august' => 'elokuu',
'september' => 'syyskuu',
'october' => 'lokakuu',
'november' => 'marraskuu',
'december' => 'joulukuu',
'jan' => 'tammi',
'feb' => 'helmi',
'mar' => 'maalis',
'apr' => 'huhti',
'may' => 'touko',
'jun' => 'kesä',
'jul' => 'heinä',
'aug' => 'elo',
'sep' => 'syys',
'oct' => 'loka',
'nov' => 'marras',
'dec' => 'joulu',

# Bits of text used by many pages:
#
"linktrail"		=> "/^((?:ä|ö|[a-z])+)(.*)\$/sD",
"mainpage"		=> "Etusivu",
"about"			=> "Tietoja",
"aboutwikipedia" => "Tietoja Wikipediasta",
"aboutpage"		=> "Wikipedia:Tietoja",
"help"			=> "Ohje",
"helppage"		=> "Wikipedia:Ohje",
"wikititlesuffix" => "Wikipedia",
"bugreports"	=> "Bugiraportit",
"bugreportspage" => "Wikipedia:Bugiraportit",
"faq"			=> "FAQ",
"faqpage"		=> "Wikipedia:FAQ",
"edithelp"		=> "Muokkausohjeet",
"edithelppage"	=> "Wikipedia:Kuinka_sivuja_muokataan",
"cancel"		=> "Keskeytä",
"qbfind"		=> "Etsi",
"qbbrowse"		=> "Selaa",
"qbedit"		=> "Muokkaa",
"qbpageoptions" => "Sivuasetukset",
"qbpageinfo"	=> "Sivun tiedot",
"qbmyoptions"	=> "Omat asetukset",
"mypage"		=> "Oma sivu",
"mytalk"		=> "Oma keskustelusivu",
"currentevents" => "Ajankohtaista",
"errorpagetitle" => "Virhe",
"returnto"		=> "Palaa sivulle $1.",
"fromwikipedia"	=> "Tarjoaa Wikipedia, vapaa tietosanakirja.",
"whatlinkshere"	=> "Tänne linkitetyt sivut",
"help"			=> "Ohje",
"search"		=> "Etsi",
"go"		=> "Siirry",
"history"		=> "Vanhemmat versiot",
"printableversion" => "Tulostettava versio",
"editthispage"	=> "Muokkaa tätä sivua",
"deletethispage" => "Poista tämä sivu",
"protectthispage" => "Suojaa tämä sivu",
"unprotectthispage" => "Pura tämän sivun suojaus",
"newpage" => "Uusi sivu",
"talkpage"		=> "Keskustele tästä sivusta",
"articlepage"	=> "Näytä artikkeli",
"subjectpage"	=> "Näytä aihe", # For compatibility
"userpage" => "Näytä käyttäjän sivu",
"wikipediapage" => "Näytä artikkeli",
"imagepage" => 	"Näytä kuvasivu",
"viewtalkpage" => "Näytä keskustelusivu",
"otherlanguages" => "Muut kielet",
"redirectedfrom" => "(Uudelleenohjattu sivulta $1)",
"lastmodified"	=> "Sivua on viimeksi muutettu  $1.",
"viewcount"		=> "Tämä sivu on näytetty $1 kertaa.",
"gnunote" => "Kaikki teksti on saatavilla <a class=internal href='$wgScriptPath/GNU_FDL'>GNU Free Documentation -lisenssin</a> ehdoilla.",
"printsubtitle" => "(Lähde: http://fi.wikipedia.org)",
"protectedpage" => "Suojattu sivu",
"administrators" => "Wikipedia:Ylläpitäjät",
"sysoptitle"	=> "Vaatii ylläpitäjä-oikeudet",
"sysoptext"	=> "Tämän toiminnon voi suorittaa vain käyttäjä, jolla on \"ylläpitäjä\"-oikeudet.
Katso $1.",
"developertitle" => "Ohjelmiston kehittäjän oikeuksia vaaditaan",
"developertext"	=> "Yrittämäsi toiminnon voi suorittaa vain henkilö, jolla on \"ohjelmiston kehittäjän\" oikeudet.
Katso $1.",
"nbytes"		=> "$1 tavua",
"go"			=> "Siirry",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "Vapaa tietosanakirja",
"retrievedfrom" => "Haettu \"$1\":sta",
"newmessages" => "Sinulla on $1.",
"newmessageslink" => "uutta viestiä",


# Main script and global functions
#
"nosuchaction"	=> "Määrittelemätön pyyntö",
"nosuchactiontext" => "Wikipedia-ohjelmisto ei tunnista
URL:ssä määriteltyä pyyntöä",
"nosuchspecialpage" => "Kyseistä toimintosivua ei ole",
"nospecialpagetext" => "Wikipedia-ohjelmisto
ei tunnista pyytämääsi toimintosivua.",

# General errors
#
"error"			=> "Virhe",
"databaseerror" => "Tietokantavirhe",
"dberrortext"	=> "Tietokantakyselyssä tapahtui syntaksivirhe.
Syynä saattaa olla virheellinen kysely (katso $5), tai se saattaa
johtua ohjelmointivirheestä.
Viimeinen tietokantakysely, jota yritettiin, oli: 
<blockquote><tt>$1</tt></blockquote>.
Se tehtiin funktiosta \"<tt>$2</tt>\".
MySQL palautti virheen \"<tt>$3: $4</tt>\".",
"noconnect"		=> "Tietokantaan osoitteessa $1 ei saatu yhteyttä",
"nodb"			=> "Tietokantaa $1 ei voitu valita",
"readonly"		=> "Tietokanta on lukittu",
"enterlockreason" => "Anna syy lukitukselle sekä arvio lukituksen poistamisajankohdalle.",
"readonlytext"	=> "Wikipedian tietokanta on tällä hetkellä lukittu.
Uusia artikkeleita tai muita muutoksia ei voi tehdä.
Syynä ovat todennäköisimmin rutiininomaiset tietokannan ylläpitotoimet.
Tietokannan lukinneen ylläpitäjän selitys:
<p>$1",
"missingarticle" => "Tietokanta ei löytänyt sivun \"$1\". tekstiä, jonka olisi pitänyt löytyä. Todennäköisesti kyseessä on ohjelmointivirhe, ei tietokantavirhe. Ole hyvä ja ilmoita virheesta ja anna URL ylläpidolle.",
"internalerror" => "Sisäinen virhe",
"filecopyerror" => "Tiedostoa \"$1\" ei voitu kopioda tiedostoon \"$2\".",
"filerenameerror" => "Tiedostoa \"$1\" ei voitu uudelleennimetä \"$2\":ksi.",
"filedeleteerror" => "Tiedostoa \"$1\" ei voitu poistaa.",
"filenotfound"	=> "Tiedostoa \"$1\" ei löytynyt.",
"unexpected"	=> "Odottamaton arvo: \"$1\"=\"$2\".",
"formerror"		=> "Virhe: lomaketta ei voitu lähettää",	
"badarticleerror" => "Toimintoa ei voi suorittaa tälle sivulle.",
"cannotdelete"	=> "Määriteltyä sivua tai kuvaa ei voitu poistaa.
(Joku muu on saattanut jo poistaa sen.)",
"badtitle"		=> "Virheellinen otsikko",
"badtitletext" => "Pyytämäsi sivuotsikko oli virheellinen, tyhjä tai väärin linkitetty kieltenvälinen tai wikien välinen otsikko.",
"perfdisabled" => "Pahoittelut! Tämä ominaisuus ei toistaiseksi ole käytettävissä, sillä se hidastaa tietokantaa niin paljon, että kukaan ei voi käyttää wikiä. Toiminto tullaan kirjoittamaan uudelleen tehokkaammaksi lähitulevaisuudessa. (Ehkä sinä teet sen! Tämä on vapaa ohjelmisto.)",

# Login and logout pages
#
"logouttitle"	=> "Käyttäjän uloskirjautuminen",
"logouttext"	=> "Olet nyt kirjautunut ulos Wikipediasta.
Voit jatkaa Wikipedian käyttöä nimettömänä, tai
kirjautua uudelleen sisään samana tai eri käyttäjänä.\n",

"welcomecreation" => "<h2>Tervetuloa, $1!</h2><p>
Käyttäjätunnuksesi on luotu.
Älä unohda virittää omia Wikipedia-asetuksiasi.",

"loginpagetitle" => "Käyttäjän sisäänkirjautuminen",
"yourname"		=> "Käyttäjätunnus",
"yourpassword"	=> "Salasana",
"yourpasswordagain" => "Salasana uudelleen",
"newusersonly"	=> " (vain uudet käyttäjät)",
"remembermypassword" => "Muista salasana saman yhteyden istunnoissa",
"loginproblem"	=> "<b>Sisäänkirjautumisessasi oli ongelmia.</b><br>Yritä uudelleen!",
"alreadyloggedin" => "<font color=red><b>Käyttäjä $1, olet jo kirjautunut sisään!</b></font><br>\n",
"notloggedin"	=> "Et ole kirjautunut",

"login"			=> "Kirjaudu sisään",
"userlogin"		=> "Kirjaudu sisään",
"logout"		=> "Kirjaudu ulos",
"userlogout"	=> "Kirjaudu ulos",
"createaccount"	=> "Luo uusi käyttäjätunnus",
"badretype"		=> "Syöttämäsi salasanat ovat erilaiset.",
"userexists"	=> "Pyytämäsi käyttäjänimi on jo käytössä. Ole hyvä ja valitse toinen käyttäjänimi.",
"youremail"		=> "Sähköpostiosoitteesi",
"yournick"		=> "Nimimerkki (allekirjoituksia varten)",
"emailforlost"	=> "Jos unohdat salasanasi, voit pyytää uuden salasanan, joka lähetetään sähköpostiosoitteeseesi.",
"loginerror"	=> "Sisäänkirjautumisvirhe",
"noname"		=> "Et ole määritellyt kelvollista käyttäjänimeä.",
"loginsuccesstitle" => "Sisäänkirjoittautuminen onnistui",
"loginsuccess"	=> "Olet nyt kirjautunut Wikipediaan käyttäjänä \"$1\".",
"nosuchuser"	=> "Käyttäjänimeä \"$1\"  ei ole olemassa.
Tarkista kirjoititko nimen oikein, tai käytä alla olevaa lomaketta uuden käyttäjätunnuksen luomiseksi.",
"wrongpassword"	=> "Syöttämäsi salasana ei ole oikein. Ole hyvä ja yritä uudelleen.",
"mailmypassword" => "Lähetä minulle uusi salasana sähköpostilla",
"passwordremindertitle" => "Salasanamuistutus Wikipediasta",
"passwordremindertext" => "Joku (todennäköisesti sinä), IP-osoitteesta $1
pyysi Wikipediaa lähettämään uuden salasanan.
Salasana käyttäjälle \"$2\" on nyt \"$3\".
Kirjaudu sisään ja vaihda heti salasanasi.",
"noemail"		=> "Käyttäjälle \"$1\" ei ole määritelty sähköpostiosoitetta.",
"passwordsent"	=> "Uusi salasana on lähetetty käyttäjän \"$1\"
sähköpostiosoitteeseen. Kirjaudu sisään uudestaan, kun olet saanut sen.",
# Edit pages
#
"summary"               =>"Yhteenveto",
"minoredit"             => "Tämä on pieni muutos",
"watchthis"		=> "Tarkkaile tätä artikkelia",
"savearticle"	=> "Tallenna sivu",
"preview"		=> "Esikatselu",
"showpreview"	=> "Esikatsele",
"blockedtitle"	=> "Pääsy käyttäjältä estetty",
"blockedtext"	=> "$1 on estänyt pääsysi Wikpediaan joko käyttäjänimesi tai IP-osoitteesi perusteella.
Syynä tähän on:<br>''$2''<p>Ota yhteyttä henkilöön $1 tai johonkuhun
muuhun [[Wikipedia:ylläpitäjät|ylläpitäjään]] keskustellaksesi estosta.",
"newarticle"	=> "(uusi)",
"newarticletext" => "Olet seurannut linkkiä sivulle, jota ei ole vielä olemassa.Luodaksesi sivun, kirjoita alla olevaan laatikkoon  (katso [[Wikipedia:Ohje|ohjeesta]] lisätietoja). Jos tarkoituksesi ei ole luoda uutta sivua, paina selaimesi '''back'''-painiketta.",
"anontalkpagetext" => "----\n''Tämä on nimettömän käyttäjän keskustelusivu. Hän ei ole joko luonut itselleen käyttäjätunnusta tai ei käytä sitä. Siksi hänet tunnistetaan nyt numeerisella [[IP-osoite|IP-osoitteella]]. Kyseinen IP-osoite voi olla useamman henkilön käytössä. Jos olet nimetön käyttäjä, ja sinusta tuntuu, että merkityksettömiä kommentteja on ohjattu sinulle, [[Erikoissivut:Sisäänkirjautuminen|luo itsellesi käyttäjätunnus tai kirjaudu sisään]] välttääksesi jatkossa sekaannukset muiden nimettömien käyttäjien kanssa.''",
"noarticletext" => "(Tällä sivulla ei ole vielä tekstiä)",
"updated"		=> "(Päivitetty)",
"note"			=> "<strong>Huomautus:</strong> ",
"previewnote"	=> "Huomaa, että tämä on vasta sivun esikatselu. Sivua ei ole vielä tallennettu!",
"previewconflict" => "Tämä esikatselu näyttää, miltä yllä olevassa muokkausalueella oleva teksti näyttää tallennettuna.",
"editing"		=> "Muokataan sivua $1",
"editconflict"	=> "Muokkausristiriita: $1",
"explainconflict" => "Joku muu on muuttanut tätä sivua sen jälkeen, kun aloit muokata sitä.
Ylempi teksti alue sisältää tämänhetkisen tekstin.
Tekemäsi muutokset näkyvät alemmassa ikkunassa.
Sinun täytyy yhdistää muutoksesi olemassa olevaan tekstiin.
<b>Vain</b> ylemmässä alueessa oleva teksti tallentuu, kun tallennat sivun.\n<p>",
"yourtext"	=> "Oma tekstisi",
"storedversion" => "Talletettu versio",
"editingold"	=> "<strong>VAROITUS: Olet muokkaamassa vanhentunutta versiota tämän sivun tekstistä.
Jos tallennat sen, kaikki tämän version jälkeen tehdyt muutokset katoavat.</strong>\n",
"yourdiff"	=> "Eroavaisuudet",
"copyrightwarning" => "Huomaa, että kaikki Wikipediaan tehtävät tuotokset 
katsotaan julkaistuksi GNU Free Documentation -lisenssin mukaisesti 
(katso sivulta $1 yksityiskohtia). Jos et halua, että kirjoitustasi 
muokataan armottomasti ja uudelleenkäytetään vapaasti, älä tallenna kirjoitustasi.<br>
Lupaa myös, että kirjoitit tämän itse, tai kopioit sen jostain vapaasta lähteestä. 
<strong>ÄLÄ KÄYTÄ TEKIJÄNOIKEUDEN ALAISTA MATERIAALIA ILMAN LUPAA!</strong>",
"longpagewarning" => "VAROITUS: Tämän sivun tekstiosuus on $1 kilotavua pitkä. Joillakin selaimilla voi olla vaikeuksia yli 32 kilotavun kokoisten sivujen muokkaamisessa. Harkitse, voisiko sivun jakaa pienempiin osiin.",

# History pages
#
"revhistory"	=> "Versiohistoria",
"nohistory"	=> "Tällä sivulla ei ole muutoshistoriaa.",
"revnotfound"	=> "Versiota ei löydy",
"revnotfoundtext" => "Pyytämääsi vanhaa versiota ei löydy.
Tarkista URL, jolla hait tätä sivua.\n",
"loadhist"	=> "Ladataan sivuhistoriaa",
"currentrev"	=> "Nykyinen versio",
"revisionasof"	=> "Versio $1",
"cur"			=> "nyk.",
"next"			=> "seur.",
"last"			=> "edell.",
"orig"			=> "alkup.",
"histlegend"	=> "Merkinnät: (nyk.) = eroavaisuudet nykyiseen versioon,
(edell.) = eroavaisuudet edelliseen versioon, P = pieni muutos",

# Diffs
#
"difference"	=> "(Versioiden väliset erot)",
"loadingrev"	=> "Ladataan versiota vertailua varten",
"lineno"		=> "Rivi $1:",
"editcurrent"	=> "Muokkaa tämän sivun uusinta versiota",

# Search results
#
"searchresults" => "Hakutulokset",
"searchhelppage" => "Wikipedia:Haku",
"searchingwikipedia" => "Etsitään Wikipediasta",
"searchresulttext" => "Lisätietoja Wikipedian hakutoiminnoista, katso $1.",
"searchquery"	=> "Haku \"$1\"",
"badquery"	=> "Huonosti muotoiltu haku",
"badquerytext"	=> "Tekemääsi kyselyä ei voida prosessoida.
Tämä johtuu todennäköisesti siitä, että olet yrittänyt etsiä sanaa, 
jossa on alle kolme kirjainta. Tätä ei vielä tueta.
Se voi johtu myös väärinkirjoitetusta lausekkeesta, esimerkiksi
\"hevonen ja ja kuolaimet\". Yritä uudelleen.",
"matchtotals"	=> "Haulla \"$1\" saatiin $2 osumaa artikkelien otsikoihin ja $3osumaa artikkeliteksteihin.",
"nogomatch" => "Täsmälleen tällä otsikolla ei ole sivua. Yritä hakua. ",
"titlematches"	=> "Osumat artikkelien otsikoissa",
"notitlematches" => "Hakusanaa ei löytynyt minkään artikkelin otsikosta",
"textmatches"	=> "Osumat artikkelien teksteissä",
"notextmatches"	=> "Hakusanaa ei löytynyt artikkelien teksteistä",
"prevn"			=> "edelliset $1",
"nextn"			=> "seuraavat $1",
"viewprevnext"	=> "näytä ($1) ($2) ($3).",
"showingresults" => "Näytetään <b>$1</b> tulosta #<b>$2:sta</b> alkaen.",
"nonefound"		=> "<strong>Huomautus</strong>: epäonnistuneet haut johtuvat usein hyvin yleisten sanojen, kuten \"on\" ja \"ei\", etsimisestä,
joita ei indeksoida, tai useamman kuin yhden hakutermin määrittelemisestä (vain sivut,
joilla on kaikki hakutermin sanat, näkyvät tuloksissa).",
"powersearch" => "Etsi",
"powersearchtext" => "
Haku nimiavaruuksista:<br>
$1<br>
$2 Listaa uudelleenohjaukset &nbsp; Etsi $3 $9",

# Preferences page
#
"preferences"	=> "Asetukset",
"prefsnologin" => "Ei kirjauduttu sisään",
"prefsnologintext"	=> "Sinun täytyy olla <a href=\"" .
  wfLocalUrl( "Erityissivut:Userlogin" ) . "\">kirjautuneena sisään</a>
jotta voisit muuttaa käyttäjän asetuksia.",
"prefslogintext" => "Olet kirjautuneena sisään käyttäjänä \"$1\".
Sisäinen tunnistenumerosi on $2.",
"prefsreset"	=> "Asetukset on palautettu talletettujen mukaisiksi.",
"qbsettings"	=> "Pikavalikon asetukset", 
"changepassword" => "Vaihda salasanaa",
"skin"			=> "Ulkonäkö",
"math"			=> "Matematiikan näyttäminen.",
"math_failure"		=> "Parserointi epäonnistui",
"math_unknown_error"	=> "Tuntematon virhe",
"math_unknown_function"	=> "Tuntematon funktio ",
"math_lexing_error"	=> "Tulkintavirhe",
"math_syntax_error"	=> "Jäsennysvirhe",
"saveprefs"		=> "Tallenna asetukset",
"resetprefs"	=> "Palauta alkuperäiset asetukset",
"oldpassword"	=> "Vanha salasana",
"newpassword"	=> "Uusi salasana",
"retypenew"		=> "Uusi salasana (uudelleen)",
"textboxsize"	=> "Tekstikentän koko",
"rows"			=> "Rivit",
"columns"		=> "Sarakkeet",
"searchresultshead" => "Hakutulosten asetukset",
"resultsperpage" => "Tuloksia sivua kohti",
"contextlines"	=> "Rivien määrä kutakin tulosta kohti",
"contextchars"	=> "Sisällön merkkien määrä riviä kohden",
"stubthreshold" => "Tynkäartikkelin osoituskynnys",
"recentchangescount" => "Otsikoiden määrä viimeisimmissä muutoksissa",
"savedprefs"	=> "Asetuksesi on tallennettu.",
"timezonetext"	=> "Paikallisen ajan ja palvelimen ajan (UTC)
välinen aikaero tunteina.",
"localtime"	=> "Paikallinen aika",
"timezoneoffset" => "Aikaero",
"emailflag"	=> "Estä sähköpostin lähetys osoitteeseesi",

# Recent changes
#
"changes" => "muutokset",
"recentchanges" => "Tuoreet muutokset",
"recentchangestext" => "Tältä sivulta voi seurata tuoreita Wikipediaan tehtyjä muutoksia.
[[Wikipedia:Tervetuloa Wikipediaan|Tervetuloa Wikipediaan!]]
Katso seuraavia sivuja: [[Wikipedia:Kysymyksiä ja vastauksia Wikipediasta|Useimmin kysyttyjä asioita]], [[Wikipedia:Sääntöjä ja ohjeita|Wikipedian säännöt]]
(erityisesti [[Wikipedia:Merkitsemiskäytäntöjä|Merkitsemiskäytännöt]],
[[Wikipedia::Neutraali näkökulma|Neutraali näkökulma]]),
ja [[Wikipedia:Aloittelijan virheitä|Aloittelijan virheitä]].
Jos haluat nähdä Wikipedian onnistuvan, on erittäin tärkeää, että et lisää materiaalia,
jonka käyttöä rajoittavat [[Wikipedia ja Tekijänoikeudet|tekijänoikeudet]].
Oikeudelliset seuraukset voivat vahingoittaa projektia vakavasti, joten kunnioita muiden tekijänoikeuksia.
Katso myös [http://meta.wikipedia.org/wiki/Special:Recentchanges recent meta discussion].",
"rcloaderr"		=> "Ladataan tuoreita muutoksia",
"rcnote"		=> "Alla ovat tuoreet <strong>$1</strong> muutosta viimeisten <strong>$2</strong> päivän ajalta.",
"rcnotefrom"	=> "Alla on muutokset <b>$2:sta</b> lähtien (<b>$1</b> asti).",
"rclistfrom"	=> "Näytä uudet muutokset $1:sta alkaen",
# "rclinks"		=> "Näytä $1 tuoretta muutosta viimeisten $2 tunnin / $3 päivän ajalta",
"rclinks"		=> "Näytä $1 tuoretta muutosta viimeisten $2 päivän ajalta.",
"rchide"		=> "muodossa $4 ; $1 pientä muutosta; $2 toissijaista nimiavaruutta; $3 moninkertaista muutosta.",
"diff"			=> "ero",
"hist"			=> "historia",
"hide"			=> "piilota",
"show"			=> "näytä",
"tableform"		=> "taulukko",
"listform"		=> "luettelo",
"nchanges"		=> "$1 muutosta",
"minoreditletter" => "P",
"newpageletter" => "U",

# Upload
#
"upload"		=> "Tallenna tiedosto",
"uploadbtn"		=> "Tallenna tiedosto",
"uploadlink"	=> "Tallenna kuvia",
"reupload"		=> "Tallenna uudelleen",
"reuploaddesc"	=> "Paluu tallennuslomakkeelle.",
"uploadnologin" => "Ei sisäänkirjautumista",
"uploadnologintext"	=> "Sinun pitää olla <a href=\"" .
  wfLocalUrl( "Erityissivut:Userlogin" ) . "\">kirjautuneena sisään</a>
tallentaaksesi tiedoston.",
"uploadfile"	=> "Tallenna tiedosto",
"uploaderror"	=> "Tallennusvirhe",
"uploadtext"	=> "'''SEIS!''' Ennen kuin tallennat tänne,
tutustu ja seuraa Wikipedian [[Project:Kuvien käyttösäännöt|kuvienkäyttösääntöihin]].

Näyttääksesi tai etsiäksesi aiemmin tallennettuja kuvia,
katso [[Erityissivut:Luettelo kuvista|luetteloa tallennetuista kuvista]].
Tallennukset ja poistot kirjataan
[[Project:Tallennusloki|tallennuslokiin]].

Käytä allaolevaa lomaketta tallentaaksesi uusia kuvatiedostoja artikkelien
kuvittamista varten.
Useimmissa selaimissa näet \"Browse...\" tai \"Selaa...\"-painikkeen, josta aukeaa käyttöjärjestelmäsi normaali tiedostonavausikkuna.
Valitsemalla tiedoston täydentyy tiedoston nimi painikkeen vieressä olevaan tekstikenttään.
Sinun täytyy myös kuitata, että et riko tekijänoikeuksia tallentaessasi tiedostoa.
Paina \"Tallenna\"-painiketta tallentaaksesi.
Tämä voi kestää jonkin aikaa, jos sinulla on hidas Internet-yhteys.

Suositeltavimmat kuvaformaatit ovat JPEG valokuville, PNG
piirroksille ja kuvakkeille ja OGG äänille.
Nimeä tiedostosi kuvaavasti välttääksesi sekaannuksia.
Liittääksesi kuvan artikkeliin käytä seuraavan muotoista linkkiä
'''<nowiki>[[Kuva:tiedosto.jpg]]</nowiki>''' tai
'''<nowiki>[[Kuva:tiedosto.png|kuvausteksti]]</nowiki>''' tai
'''<nowiki>[[media:tiedosto.ogg]]</nowiki>''' äänille..

Huomaa, että Wikipedian sivuilla muut voivat muokata tai poistaa tallentamasi 
tiedoston, jos he katsovat, että se ei palvele tietosanakirjan tarpeita, ja 
sinun tallentamismahdollisuutesi voidaan estää, jos väärinkäytät järjestelmää.",
"uploadlog"		=> "Tallennusloki",
"uploadlogpage" => "Tallennusloki",
"uploadlogpagetext" => "Alla on luettelo uusimmista tallennuksista.
Kaikki ajat näytetään palvelimen aikoina (UTC).
<ul>
</ul>
",
"filename"	=> "Tiedoston nimi",
"filedesc"	=> "Yhteenveto",
"affirmation"	=> "Lupaan, että tämän tiedoston tekijänoikeuksien haltija sallii sen 
käytön $1 lisenssin mukaisesti.",
"copyrightpage" => "Wikipedia:Wikipedia ja tekijänoikeudet",
"copyrightpagename" => "Wikipedia ja tekijänoikeudet",
"uploadedfiles"	=> "Tallennetut tiedostot",
"noaffirmation" => "Vahvista, ettei lähettämäsi tiedosto riko tekijänoikeuksia.",
"ignorewarning"	=> "Jätä tämä varoitus huomiotta ja tallenna tiedosto.",
"minlength"	=> "Kuvan nimen pitää olla vähintään kolme merkkiä pitkä.",
"badfilename"	=> "Kuva on vaihdettu nimelle \"$1\".",
"badfiletype"	=> "\".$1\" ei ole suositeltavassa tiedostomuodossa.",
"largefile"	=> "Kuvien ei tulisi olla yli 100 kilotavun kokoisia.",
"successfulupload" => "Tallennus onnistui",
"fileuploaded"	=> "Tiedosto \"$1\" on tallennettu onnistuneesti.
Seuraa linkkiä ($2) kuvaussivulle ja täytä kuvaan liityvät tiedot, kuten
mistä se on peräisin, milloin se on luotu, kuka sen loi ja mahdollisesti muita tietoja, mitä tiedät siitä.",
"uploadwarning" => "Tallennusvaroitus",
"savefile"	=> "Tallenna",
"uploadedimage" => "Tallennettin \"$1\"",

# Image list
#
"imagelist"	=> "Luettelo kuvista",
"imagelisttext"	=> "Alla on $1 kuvan luettelo lajiteltuna $2.",
"getimagelist"	=> "noudetaan kuvaluettelo",
"ilshowmatch"	=> "Haku kuvista: ",
"ilsubmit"		=> "Hae",
"showlast"		=> "Näytä viimeiset $1 kuvaa lajiteltuna $2.",
"all"			=> "kaikki",
"byname"		=> "nimen mukaan",
"bydate"		=> "päiväyksen mukaan",
"bysize"		=> "koon mukaan",

"imgdelete"		=> "poista",
"imgdesc"		=> "kuvaus",
"imglegend"		=> "Merkinnät: (kuvaus) = näytä/muokkaa kuvan kuvausta.",
"imghistory"	=> "Kuvan historia",
"revertimg"		=> "palauta",
"deleteimg"		=> "poista",
"deleteimgcompletely"		=> "poista",
"imghistlegend" => "Merkinnät: (nyk.) = nykyinen kuva, (poista) = poista 
tämä vanha versio, (palauta) = palauta kuva tähän vanhaan versioon.
<br><i>Klikkaa päiväystä nähdäksesi silloin tallennettu kuva</i>.",
"imagelinks"	=> "Kuvalinkit",
"linkstoimage"	=> "Seuraavilta sivuilta on linkki tähän kuvaan:",
"nolinkstoimage" => "Tähän kuvaan ei ole linkkejä miltään sivulta.",

# Statistics
#
"statistics"	=> "Tilastoja",
"sitestats"		=> "Sivuston tilastoja",
"userstats"		=> "Käyttäjätilastoja",
"sitestatstext" => "Tietokannassa on yhteensä <b>$1</b> sivua.
Tähän on laskettu mukaan keskustelusivut, Wikipediasta kertovat sivut,
lyhyet \"tynkäsivut\", uudelleenohjaukset sekä muita sivuja joita
ei voi pitää kunnollisina artikkeleina. Nämä poislukien tietokannassa on
<b>$2</b> sivua joita voidaan todennäköisesti pitää oikeina artikkeleina.<p>
Sivuja on katsottu yhteensä <b>$3</b> kertaa ja muokattu <b>$4</b> kertaa
ohjelmiston päivittämisen jälkeen (20. heinäkuuta, 2002).
Keskimäärin sivua on muokattu  <b>$5</b> kertaa, ja muokkausta kohden sivua on katsottu keskimäärin <b>$6</b> kertaa.",
"userstatstext" => "Rekisteröityneitä käyttäjiä on <b>$1</b>.
<b>$2</b> näistä on ylläpitäjiä (katso $3).",

# Maintenance Page
#
"maintenance"		=> "Ylläpitosivu",
"maintnancepagetext"	=> "Tämä sivu sisältää useita käteviä työkaluja jokapäiväistä ylläpitoa varten. Jotkut näistä toiminnoista kuormittavat tietokantaa, joten ole hyvä äläkä paina päivitysnappia jokaisessa kohdassa ;-)",

"maintenancebacklink"	=> "Takaisin ylläpitosivulle",
"disambiguations"	=> "Tarkennussivu",
"disambiguationspage"	=> "Wikipedia:Linkkejä_tarkennussivuihin",
"disambiguationstext"	=> "Seuraavat artikkelit linkittävät <i>tarkennussivuun</i>. Sen sijasta niiden pitäisi linkittää asianomaiseen aiheeseen.<br>Sivua kohdellaan tarkennussivuna jos siihen on linkki sivulta $1.<br>Linkkejä muihin nimiavaruuksiin <i>ei</i> ole listattu tässä.",
"doubleredirects"	=> "Kaksinkertaiset uudelleenohjaukset",
"doubleredirectstext"	=> "<b>Huomio:</b> Tässä listassa saattaa olla virheitä. Yleensä kyseessä on sivu, jossa ensimmäisen #REDIRECT:in jälkeen on tekstiä.<br>\nJokaisella rivillä on linkit ensimmäiseen ja toiseen uudelleenohjaukseen sekä toisen uudelleenohjauksen kohteen ensimmäiseen riviin, eli yleensä \"oikeaan\" kohteeseen, johon ensimmäisen uudelleenohjauksen pitäisi osoittaa.",
"brokenredirects"	=> "Virheelliset uudelleenohjaukset",
"brokenredirectstext"	=> "Seuraavat uudelleenohjaukset on linkitetty artikkeleihin, joita ei ole olemassa.",
"selflinks"		=> "Sivut, jotka linkittävät itseensä",
"selflinkstext"		=> "Seuraavat sivut sisältävät linkkejä itseensä, vaikka ei pitäisi.",
"mispeelings"           => "Kirjoitusvirheitä sisältävät sivut",
"mispeelingstext"	=> "Seuraavat sivut sisältävät yleisen kirjoitusvirheen, joka on listattu sivulla $1. Oikea kirjoitustapa on ehkä annettu (tähän tapaan).",
"mispeelingspage"       => "Lista tavallisimmista kirjoitusvirheistä",
"missinglanguagelinks"  => "Puuttuvat kielilinkit",
"missinglanguagelinksbutton"    => "Etsi puuttuvat kielilinkit",
"missinglanguagelinkstext"      => "Näitä artikkeleita <i>ei</i> ole linkitetty vastineeseensa $1:ssä. Uudelleenohjauksia ja alasivuja <i>ei</i> ole näytetty.",

# Miscellaneous special pages
#
"orphans"	=> "Orposivut",
"lonelypages"	=> "Yksinäiset sivut",
"unusedimages"	=> "Käyttämättömät kuvat",
"popularpages"	=> "Suositut sivut",
"nviews"		=> "$1 latausta",
"wantedpages"	=> "Halutut sivut",
"nlinks"		=> "$1 linkkiä",
"allpages"		=> "Kaikki sivut",
"randompage"	=> "Arvottu sivu",
"shortpages"	=> "Lyhyet sivut",
"longpages"		=> "Pitkät sivut",
"listusers"		=> "Käyttäjälista",
"specialpages"	=> "Toimintosivut",
"spheading"		=> "Toimintosivut",
"sysopspheading" => "Toimintosivut järjestelmän ylläpitäjille",
"developerspheading" => "Toimintosivut ohjelmoijille",
"protectpage"	=> "Suojaa sivu",
"recentchangeslinked" => "Tähän liittyävt muutokset",
"rclsub"		=> "(sivuihin on linkki sivulta \"$1\")",
"debug"			=> "Virheenetsintä",
"newpages"		=> "Uudet sivut",
"movethispage"	=> "Siirrä tämä sivu",
"unusedimagestext" => "<p>Huomaa, että muut verkkosivut, kuten toiset Wikipediat, saattavat viitata kuvaan suoran URL:n kautta, jolloin kuva saattaa olla tässä listassa vaikka sitä käytetäänkin.",
"booksources"	=> "Kirjalähteet",
"booksourcetext" => "Alla on lista linkeistä ulkopuolisiin sivustoihin,
joilla myydään uusia ja käytettyjä kirjoja. Niillä voi myös olla lisätietoa
kirjoista, joita etsit. Wikipedia ei liity mitenkään niihin, eikä
tätä listaa tule pitää suosituksena tai hyväksyntänä.",
"alphaindexline" => "$1:n ja $2:n välillä",

# Email this user
#
"mailnologin"	=> "Lähettäjän osoite puuttuu",
"mailnologintext" => "Sinun pitää olla <a href=\"" .
  wfLocalUrl( "Erityissivut:Userlogin" ) . "\">kirjautuneena sisään</a>
ja <a href=\"" . wfLocalUrl( "Erityissivut:Preferences" ) . "\">asetuksissasi</a> pitää olla kelpoinen sähköpostiosoite jotta voit lähettä sähköpostia muille käyttäjille.",
"emailuser"		=> "Lähetä sähköpostia tälle käyttäjälle",
"emailpage"		=> "Lähetä sähköpostia käyttäjälle",
"emailpagetext"	=> "Jos tämä käyttäjä on antanut asetuksissaan kelvollisen
sähköpostiosoitteen, allaolevalla lomakeella voi lähettää yhden viestin.
Omissa asetuksissasi annettu sähköpostiosoite tulee näkymään sähköpostin lähettäjän osoitteena, jotta vastaanottaja voi vastata.",
"noemailtitle"	=> "Ei sähköpostiosoitetta",
"noemailtext"	=> "Tämä käyttäjä ei ole määritellyt kelpoa sähköpostiosoitetta tai ei halua postia muilta käyttäjiltä.",
"emailfrom"		=> "Lähettäjä",
"emailto"		=> "Vastaanottaja",
"emailsubject"	=> "Aihe",
"emailmessage"	=> "Viesti",
"emailsend"		=> "Lähetä",
"emailsent"		=> "Sähköposti lähetetty",
"emailsenttext" => "Sähköpostiviestisi on lähetetty.",


# Watchlist
#
"watchlist"	=> "Tarkkailulistani",
"watchlistsub"	=> "(Käyttäjälle \"$1\")",
"nowatchlist"	=> "Tarkkailulistallasi ei ole sivuja.",
"watchnologin"	=> "Et ole kirjautunut sisään",
"watchnologintext"	=> "Sinun pitää olla <a href=\"" .
  wfLocalUrl( "Erityissivut:Userlogin" ) . "\">kirjautuneena sisään</a>
jotta voit muokata tarkkailulistaasi.",
"addedwatch"	=> "Lisätty tarkkailulistalle",
"addedwatchtext" => "Sivu \"$1\" on lisätty <a href=\"" .
  wfLocalUrl( "Erityissivut:Watchlist" ) . "\">tarkkailulistallesi</a>.
Tulevaisuudessa sivuun ja sen keskustelusivuun tehtävät muutokset listataan täällä, ja sivu on <b>lihavoitu</b> <a href=\"" .
  wfLocalUrl( "Erityissivut:Recentchanges" ) . "\">viimeisimpien muutosten listassa</a>, jotta sen huomaisi helpommin.</p>
  <p>Jos haluat myöhemmin poistaa sivun tarkkailulistaltasi, klikkaa \"Lopeta tarkkailu\"-linkkiä sivun reunassa.",
"removedwatch"	=> "Poistettu tarkkailulistalta",
"removedwatchtext" => "Sivu \"$1\" on poistettu tarkkailulistaltasi.",
"watchthispage"	=> "Tarkkaile tätä sivua",
"unwatchthispage" => "Lopeta tarkkailu",
"notanarticle"	=> "Ei ole artikkeli",

# Delete/protect/revert
#
"deletepage"	=> "Poista sivu",
"confirm"		=> "Vahvista",
"confirmdelete" => "Vahvista poisto",
"deletesub"		=> "(Poistetaan \"$1\")",
"confirmdeletetext" => "Olet tuhoamassa pysyvästi sivun tai kuvan ja kaiken sen historian tietokannasta. Vahvista, että todella aiot tehdä näin ja että ymmärrät seuraukset, sekä että teet tämän [[Wikipedia:Policy|Wikipedian käytännön]] mukaisesti.",
"confirmcheck"	=> "Kyllä, haluan varmasti poistaa tämän.",
"actioncomplete" => "Toiminta suoritettu",
"deletedtext"	=> "\"$1\" on poistettu.
Katso $2 nähdäksesi tallenteen viimeaikaisista poistoista.",
"deletedarticle" => "poistettiin \"$1\"",
"dellogpage"	=> "Poistoloki",
"dellogpagetext" => "Alla on lista viimeisimmistä poistoista.
Kaikki ajat ovat palvelimen ajassa (UTC).
<ul>
</ul>
",
"deletionlog"	=> "poistoloki",
"reverted"	=> "Palautettu aikaisempaan versioon",
"deletecomment"	=> "Poistamisen syy",
"imagereverted" => "Aikaisempaan versioon palauttaminen onnistui.",
"rollback"	=> "palauta aiempaan versioon",

"rollbacklink"	=> "palauta",
"cantrollback"	=> "Aiempaan versioon ei voi palauttaa; viimeisin kirjoittaja on artikkelin ainoa tekijä.",
"revertpage"	=> "Palautettiin viimeiseen $1:n tekemään muutokseen.",

# Undelete
"undelete" => "Palauta poistettu sivu",
"undeletepage" => "Selaa ja palauta poistettuja sivuja",
"undeletepagetext" => "Seuraavat sivut on poistettu, mutta ne löytyvät vielä arkistosta, joten ne ovat palautettavissa. Arkisto saatetaan tyhjentää aika ajoin.",
"undeletearticle" => "Palauta poistettu artikkeli",
"undeleterevisions" => "$1 versiota arkistoitu.",
"undeletehistory" => "Jos palautat artikkelin, kaikki versiot lisätään sivun historiaan.
Jos uusi, samanniminen sivu on luotu poistamisen jälkeen, palautetut versiot lisätään sen historiaan,
ja olemassa olevaa versiota ei korvata automaattisesti.",
"undeleterevision" => "Poistettu versio hetkellä $1",
"undeletebtn" => "Palauta!",
"undeletedarticle" => "palautettiin \"$1\"",
"undeletedtext"   => "Artikkeli [[$1]] on palautettu onnistuneesti.
Lista viimeisimmistä poistoista ja palautuksista on sivulla [[Wikipedia:poistoloki]].",

# Contributions
#
"contributions"	=> "Käyttäjän muokkaukset",
"mycontris"	=> "Omat muokkaukseni",
"contribsub"	=> "Käyttäjälle $1",
"nocontribs"	=> "Näihin ehtoihin sopivia muokkauksia ei löytynyt.",
"ucnote"	=> "Alla on <b>$1</b> viimeisintä tämän käyttäjän tekemää muokkausta viimeisten <b>$2</b> päivän aikana.",
"uclinks"	=> "Katso $1 viimeisintä muokkausta; katso $2 viimeisintä päivää.",
"uctop"		=> " (alkuun)" ,

# What links here
#
"whatlinkshere"	=> "Tänne viittaavat sivut",
"notargettitle" => "Ei kohdetta",
"notargettext"	=> "Et ole määritellyt kohdesivua tai -käyttäjää johon toiminto kohdustuu.",
"linklistsub"	=> "(Lista linkeistä)",
"linkshere"	=> "Seuraavat sivut on linkitetty tänne:",
"nolinkshere"	=> "Tänne ei ole linkkejä.",
"isredirect"	=> "uudelleenohjaussivu",

# Block/unblock IP
#
"blockip"	=> "Aseta muokkausesto",
"blockiptext"	=> "Käytä allaolevaa lomaketta estämään kirjoitusoikeudet tietyltä IP-osoitteelta. Näin pitäisi tehdä vain vandalismin estämiseksi, ja samalla on noudatettava [[Wikipedia:Policy|Wikipedian käytäntöjä]].
Ilmoita syy alapuolella (esimerkiksi lista vandalisoiduista sivuista).",
"ipaddress"		=> "IP-osoite",
"ipbreason"		=> "Syy",
"ipbsubmit"		=> "Estä tämä osoite",
"badipaddress"	=> "IP-osoite on väärin muotoiltu.",
"noblockreason" => "Sinun täytyy antaa syy estämiselle.",
"blockipsuccesssub" => "Esto onnistui",
"blockipsuccesstext" => "IP-osoite \"$1\" on estetty.
<br>Katso [[Erityissivut:Ipblocklist|IP-estolista]] katsellaksesi estoja.",
"unblockip"		=> "Poista IP-osoitteen muokkausesto",
"unblockiptext"	=> "Käytä allaolevaa lomaketta poistaaksesi kirjoitusesto aikaisemmin estetyltä IP-osoitteelta.",
"ipusubmit"		=> "Poista tämän osoitteen esto",
"ipusuccess"	=> "IP-osoitteen \"$1\" esto poistettu",
"ipblocklist"	=> "Lista estetyistä IP-osoitteista",
"blocklistline"	=> "$1, $2 on estänyt $3:n",
"blocklink"		=> "esto",
"unblocklink"	=> "poista esto",
"contribslink"	=> "osuus",

# Developer tools
#
"lockdb"	=> "Lukitse tietokanta",
"unlockdb"	=> "Vapauta tietokanta",
"lockdbtext"	=> "Tietokannan lukitseminen estää käyttäjiä muokkaamasta sivuja, vaihtamasta asetuksia, muokkaamasta tarkkailulistoja ja tekemästä muita tietokannan muuttammista vaativia toimia. Ole hyvä ja vahvista, että tämä on tarkoituksesi, ja että vapautat tietokannan kun olet suorittanut ylläpitotoimet.", 
"unlockdbtext"	=> "Tietokannan vapauttaminen antaa käyttäjille mahdollisuuden muokkata sivuja, vaihtamaa asetuksia, muokkata tarkkailulistoja ja tehdä muita tietokannan muuttammista vaativia toimia. Ole hyvä ja vahvista, että tämä on tarkoituksesi.",
"lockconfirm"	=> "Kyllä, haluan varmasti lukita tietokannan.",
"unlockconfirm"	=> "Kyllä, haluan varmasti vapauttaa tietokannan.",
"lockbtn"	=> "Lukitse tietokanta",
"unlockbtn"	=> "Vapauta tietokanta",
"locknoconfirm" => "Et merkinnyt vahvistuslaatikkoa.",
"lockdbsuccesssub" => "Tietokannan lukitseminen onnistui",
"unlockdbsuccesssub" => "Tietokannan vapauttaminen onnistui",
"lockdbsuccesstext" => "Wikipedia-tietokanta on lukittu.
<br>Muista vapauttaa tietokanta ylläpitotoimenpiteiden jälkeen.",
"unlockdbsuccesstext" => "Wikipedia-tietokanta on vapautettu.",

# SQL query
#
"asksql"		=> "SQL-kysely",
"asksqltext"	=> "Käytä allaolevaa lomaketta tehdäksesi suoria kyselyitä Wikipedian tietokannasta.
Merkkijonovakioita merkitään yksinkertaisilla lainausmerkeillä ('näin').
Kyselyt voivat usein kuormittaa palvelinta pahastikin, joten käytä tätä toimintoa säästeliäästi.",
"sqlquery"		=> "Kirjoita kysely",
"querybtn"		=> "Lähetä kysely",
"selectonly"	=> "Vain Wikipedian kehittäjät voivat tehdä muita kuin \"SELECT\"-hakuja.",
"querysuccessful" => "Kysely onnistui",

# Move page
#
"movepage"	=> "Siirrä sivu",
"movepagetext"	=> "Alla olevalla lomakkeella voi uudelleennimetä sivuja, jolloin niiden koko historia siirtyy uuden nimen alle. Vanhasta sivusta tulee uudelleenohjaussivu, joka osoittaa uuteen sivuun. Vanhaan sivuun suunnattuja linkkejä ei muuteta, muista tehdä [[Erityissivut:Maintenance|tarkistukset]] kaksinkertaisten tai rikkinäisten uudellenohjausten varalta. Olet vastuussa siitä, että linkit osoittavat sinne, mihin niiden on tarkoituskin osoittaa.

Huomaa, että sivua '''ei''' siirretä mikäli uusi otsikko on olemassaolevan sivun käytössä, paitsi milloin kyseessä on tyhjä sivu tai uudelleenohjaus jolla ei ole muokkaushistoriaa. Tämä tarkoittaa sitä, että voit uudelleennimetä sivun takaisin vanhalle nimelleen mikäli teit virheen, mutta et voi kirjoittaa olemassaolevan sivun päälle.

<b>HUOMIO!</b>
Olet tekemässä huomattavaa ja odottamatonta muutosta suositulle sivulle;
ole varma, että ymmärrät seuraukset ennen kuin jatkat.",
"movepagetalktext" => "Sivuun mahdollisesti kytketty keskustelusivu siirretään automaattisesti, '''paitsi jos''':
*Siirrät sivua nimiavaruudesta toiseen
*Kohdesivulla on olemassa keskustelusivu, joka ei ole tyhjä, tai
*Kumoat allaolevan ruudun asetuksen.

Näissä tapauksissa sivut täytyy siirtää tai yhdistää käsin.",
"movearticle"	=> "Siirrä sivu",
"movenologin"	=> "Et ole kirjautunut sisään",
"movenologintext" => "Sinun pitää olla rekisteröitynyt käyttäjäksi ja <a href=\"" .  wfLocalUrl( "Erityissivut:Userlogin" ) . "\"> kirjautuneena sisään</a> jotta voisit siirtää sivun.",
"newtitle"	=> "Uusi nimi sivulle",
"movepagebtn"	=> "Siirrä sivu",
"pagemovedsub"	=> "Siirtäminen onnistui",
"pagemovedtext" => "Sivu \"[[$1]]\" siirrettiin, uusi otsikko \"[[$2]]\".",
"articleexists" => "Siten nimetty sivu on jo olemassa, tai valittu nimi ei ole sopiva. Ole hyvä ja valitse uusi nimi.",
"talkexists"	=> "Sivu siirrettiin onnistuneesti, mutta keskustelusivua ei voitu siirtää sillä uuden otsikon alla on jo keskustelusivu. Ole hyvä ja sulauta; sivut käsin.",
"movedto"		=> "Siirretty uudelle otsikolle",
"movetalk"		=> "Siirrä myös sen \"keskustelu\"-sivu, jos mahdollista.",
"talkpagemoved" => "Myös vastaava keskustelusivu siirrettiin.",
"talkpagenotmoved" => "Artikkelin keskustelusivua <strong>ei</strong> siirretty.",
# Math
	'mw_math_png' => "Näytä aina PNG:nä",
	'mw_math_simple' => "Näytä HTML:nä, jos yksinkertainen, muuten PNG:nä",
	'mw_math_html' => "Näytä HTML:nä, jos mahdollista, muuten PNG:nä",
	'mw_math_source' => "Näytä TeX-muotoon (tekstiselaimille)",
	'mw_math_modern' => "Suositus nykyselaimille",
);

require_once( "LanguageUtf8.php" );

class LanguageFi extends LanguageUtf8 {

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

	function specialPage( $name ) {
		return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFi;
		return $wgQuickbarSettingsFi;
	}

	function getSkinNames() {
		global $wgSkinNamesFi;
		return $wgSkinNamesFi;
	}

	# Inherit userAdjust()
 
	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . (0 + substr( $ts, 6, 2 )) . ", " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function time( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->time( $ts, $adj ) . " " . $this->date( $ts, $adj );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesFi;
		return $wgValidSpecialPagesFi;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesFi;
		return $wgSysopSpecialPagesFi;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesFi;
		return $wgDeveloperSpecialPagesFi;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesFi;
		return $wgAllMessagesFi[$key];
	}

}
?>
