<?php
require_once("LanguageUtf8.php");
$wgInputEncoding	= "utf-8";
$wgOutputEncoding	= "utf-8";
$wgEditEncoding		= "x";

# See language.doc

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesEo = array(
	NS_MEDIA          => "Media",
	NS_SPECIAL        => "Speciala",
	NS_MAIN           => "",
	NS_TALK           => "Diskuto",
	NS_USER           => "Vikipediisto",
	NS_USER_TALK      => "Vikipediista_diskuto",
	NS_WIKIPEDIA      => $wgMetaNamespace, # FIXME: Generalize v-isto kaj v-io
	NS_WIKIPEDIA_TALK => "{$wgMetaNamespace}_diskuto", # FIXME
	NS_IMAGE          => "Dosiero", #FIXME: Check the magic for Image: and Media:
	NS_IMAGE_TALK     => "Dosiera_diskuto",
	NS_MEDIAWIKI      => "MediaWiki",
	NS_MEDIAWIKI_TALK => "MediaWiki_diskuto",
	NS_TEMPLATE       => "Ŝablono",
	NS_TEMPLATE_TALK  => "Ŝablona_diskuto",
	NS_HELP           => "Helpo",
	NS_HELP_TALK      => "Helpa_diskuto",
	NS_CATEGORY       => "Kategorio",
	NS_CATEGORY_TALK  => "Kategoria_diskuto",

) + $wgNamespaceNamesEn;

# Heredu apriorajn preferojn: wgDefaultUserOptionsEn

/* private */ $wgQuickbarSettingsEo = array(
	"Nenia", "Fiksiĝas maldekstre", "Fiksiĝas dekstre", "Ŝvebas maldekstre"
);

/* private */ $wgSkinNamesEo = array(
	'standard' => "Klasika",
	'nostalgia' => "Nostalgio",
	'cologneblue' => "Kolonja Bluo",
	'davinci' => "DaVinci",
	'mono' => "Senkolora",
	'monobook' => "Librejo",
	'myskin' => 'MySkin',
	'chick' => 'Kokido',
);



# Se eble, trovu Esperantajn libroservoj traserĉeblaj laŭ ISBN
# $wgBookstoreListEo = ..



# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
# *Ne ŝanĝu* la nomojn en la maldekstra kolumno, ili estas internaj
# nomoj de programfunkcioj. La dekstra kolumno enhavas kelkajn
# malplenaĵojn; ili restu tiel, por ke tiuj funkcioj ne listiĝu
# en la listo da specialaj paĝoj.
/* private */ $wgValidSpecialPagesEo = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Ŝanĝu miajn preferojn",
	"Watchlist"		=> "Mian atentaron", # Listo de paĝoj, kiujn la uzulo elektis por atenti
	"Recentchanges" => "Lastaj ŝanĝoj al paĝoj",
	"Upload"		=> "Alŝutu bildojn kaj dosierojn",
	"Imagelist"		=> "Alŝutitaj dosieroj",
	"Listusers"		=> "Enskribitaj uzuloj",
	"Statistics"	=> "Statistiko pri la paĝaro",
	"Randompage"	=> "Hazarda paĝo",

	"Lonelypages"	=> "Paĝoj neligitaj",
	"Unusedimages"	=> "Bildoj neligitaj",
	"Popularpages"	=> "Plej vizitataj paĝoj",
	"Wantedpages"	=> "Plej dezirataj paĝoj",
	"Shortpages"	=> "Mallongaj artikoloj",
	"Longpages"		=> "Longegaj artikoloj",
	"Newpages"		=> "Novaj artikoloj",
	"Ancientpages"	=> "Antikvaj artikoloj",
	"Allpages"		=> "Ĉiu paĝo laŭ titolo",

	"Ipblocklist"	=> "Forbaritaj IP-adresoj",
    "Maintenance" => "Ripariloj kaj zorgiloj", # angle "Maintenance page"
	"Specialpages"  => "",
	"Contributions" => "",
    "Emailuser"     => "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "Eksteraj libroservoj",
	"Export"	=> "Elkopii per XML",
	"Version"	=> "Versionoj de la programaro",
);

/* private */ $wgSysopSpecialPagesEo = array(
	"Blockip"		=> "Forbaru fi-IP-adreson",
	"Asksql"		=> "Informomendu je la datumbazo",
	"Undelete"		=> "Restarigu forigitan paĝon"
);

# FIXME
/* private */ $wgDeveloperSpecialPagesEo = array(
	"Lockdb"		=> "Forŝlosi datumaron",
	"Unlockdb"		=> "Repermesu ŝanĝon al datumaro",
);

/* private */ $wgAllMessagesEo = array(
'special_version_prefix' => '',
'special_version_postfix' => '',
# User toggles
"tog-hover"		=> "Montru helpilon super viki-ligiloj",
"tog-underline" => "Substreku ligilojn",
"tog-highlightbroken" => "Ruĝigu ligilojn al neekzistantaj paĝoj",
"tog-justify"	=> "Alkadrigu liniojn",
"tog-hideminor" => "Kaŝu malgrandajn redaktetojn ĉe <i>Lastaj ŝanĝoj</i>",
"tog-usenewrc"  => "Novstila Lastaj Ŝanĝoj (bezonas JavaSkripton)",
"tog-numberheadings" => "Aŭtomate numeru sekciojn",
"tog-showtoolbar" => "Show edit toolbar",
"tog-editondblclick" => "Redaktu per duobla alklako (JavaScript)",
"tog-editsection" => "Montru [redaktu]-ligiloj por sekcioj",
"tog-editsectiononrightclick" => "Redaktu sekciojn per dekstra musklako",
"tog-showtoc" => "Montru liston de enhavoj",
"tog-rememberpassword" => "Memoru mian pasvorton",
"tog-editwidth" => "Redaktilo estu plenlarĝa",
"tog-watchdefault" => "Priatentu paĝojn de vi redaktintajn",
"tog-minordefault" => "Marku ĉiujn redaktojn malgrandaj",
"tog-previewontop" => "Montru antaŭrigardon antaŭ redaktilo",
"tog-altencoding" => "Montru supersignojn X-sisteme", # FIXME: forpreni cxi tiun; estas cimoplena
# Dates

'sunday' => "dimanĉo",
'monday' => "lundo",
'tuesday' => "mardo",
'wednesday' => "merkredo",
'thursday' => "ĵaŭdo",
'friday' => "vendredo",
'saturday' => "sabato",
'january' => "januaro",
'february' => "februaro",
'march' => "marto",
'april' => "aprilo",
'may_long' => "majo",
'june' => "junio",
'july' => "julio",
'august' => "aŭgusto",
'september' => "septembro",
'october' => "oktobro",
'november' => "novembro",
'december' => "decembro",
'jan' => "Jan",
'feb' => "Feb",
'mar' => "Mar",
'apr' => "Apr",
'may' => "Maj",
'jun' => "Jun",
'jul' => "Jul",
'aug' => "Aŭg",
'sep' => "Sep",
'oct' => "Okt",
'nov' => "Nov",
'dec' => "Dec",

# Teksteroj uzataj fare de diversaj paĝoj:
#
"categories" => "Kategorioj",
"category" => "kategorio",
"category_header" => "Artikoloj en kategorio \"$1\"",
"subcategories" => "Subkategorioj",

# Tiuj literoj, kiuj aperu kiel parto de la ligilo en formo "[[lingvo]]jn" ktp:
"linktrail"     => "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Ĉefpaĝo",
"about"			=> "Enkonduko",
"aboutwikipedia" => "Pri {{SITENAME}}", #FIXME
"aboutpage"		=> "{{ns:4}}:Enkonduko",
'article'		=> "Artikolo",
"help"			=> "Helpo",
"helppage"		=> "{{ns:4}}:Helpo",
"wikititlesuffix" => "{{SITENAME}}",
"bugreports"	=> "Raportu cimojn",
"bugreportspage" => "{{ns:4}}:Raportu_cimojn",
"sitesupport"   => "Subteno",
"sitesupportpage" => "", # FIXME
"faq"			=> "Oftaj demandoj",
"faqpage"		=> "{{ns:4}}:Oftaj demandoj",
"edithelp"		=> "Helpo pri redaktado",
"newwindow"		=> "(en nova fenestro)",
"edithelppage"	=> "{{ns:4}}:Kiel_redakti_paĝon", #FIXME: Kontrolu
"cancel"		=> "Nuligu",
"qbfind"		=> "Trovu",
"qbbrowse"		=> "Foliumado", # FIXME
"qbedit"		=> "Redaktado", #FIXME
"qbpageoptions" => "Paĝagado", #FIXME
"qbpageinfo"	=> "Paĝinformoj", #FIXME
"qbmyoptions"	=> "Personaĵoj", #FIXME
"qbspecialpages"	=> "Specialaj paĝoj",
"moredotdotdot"	=> "Pli...",
"mypage"		=> "Mia paĝo", #FIXME
"mytalk"        => "Mia diskuto",
"currentevents" => "Aktualaĵoj", #FIXME - Novaĵoj? Aktualaj novaĵoj? Aktualaj eventoj?
"errorpagetitle" => "Eraro", #FIXME - Arero? ;)
"returnto"		=> "Revenu al $1.",
"fromwikipedia"	=> "El {{SITENAME}}, la libera enciklopedio.",
"whatlinkshere"	=> "Paĝoj kiuj ligas ĉi tien",
"help"			=> "Helpo",
"search"		=> "Serĉu",
"go"			=> "Ek",
"history"		=> "Malnovaj versioj",
"history_short"	=> "Historio",
"printableversion" => "Presebla versio", 
"editthispage"	=> "Redaktu la paĝon",
"deletethispage" => "Forigu la paĝon",
"protectthispage" => "Protektu la paĝon", #FIXME: Ĉu 'gardu' / "protekti" bonas /Bertilo
"unprotectthispage" => "Malprotektu la paĝon", #FIXME: ĉu 'malgardu', 'ne plu', ktp? / "(mal)gardi" ne estas bona /Bertilo
"newpage"		=> "Nova paĝo",
"talkpage"		=> "Diskutu la paĝon",
"postcomment"   => "Afiŝu komenton",
"articlepage"	=> "Vidu la artikolon",
"subjectpage"	=> "Vidu la artikolon", #FIXME: ?
"talk"			=> "Diskuto",
"toolbox"		=> "Iloj",
"userpage"		=> "Vidu personan paĝon",
"wikipediapage"	=> "Vidu meta-paĝon",
"imagepage"		=> "Vidu dosieropaĝon",
"viewtalkpage"	=> "Vidu diskutopaĝon",
"otherlanguages" => "Aliaj lingvoj",
"redirectedfrom" => "(Alidirektita el $1)",
"lastmodified"	=> "Laste redaktita je $1.",
"viewcount"		=> "Montrita $1-foje.",
"gnunote"		=> "La enhavo de {{SITENAME}} disponeblas laŭ permesilo <a class='internal' href='$wgScript/GFDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(El http://eo.wikipedia.org)",
"protectedpage" => "Protektita paĝo", #FIXME: ĉu "gardita" ktp?
"administrators" => "{{ns:4}}:Administrantoj", # FIXME?
"sysoptitle"	=> "Konto de administranto bezonatas",
"sysoptext"		=> "La ago kiun vi petis fari estas
farebla nur de uzuloj agnoskitaj kiel \"sistemestroj\".
Bonvolu legi $1.", #FIXME
"developertitle" => "Sistemestra konto nepras",
"developertext"	=> "Nur tiuj kiuj havas la staton, \"programisto\", povas fari tiun agon.
Vidu $1.",
"nbytes"		=> "$1 bitokoj",
"go"			=> "Ek!", #FIXME
"ok"			=> "Ek!", #FIXME
"sitetitle"		=> "{{SITENAME}}",
"pagetitle"		=> "$1 - {{SITENAME}}",
"sitesubtitle"	=> "La Libera Enciklopedio", # FIXME
"retrievedfrom" => "Citita el \"$1\"", #FIXME: Aperas post presita paĝo
"newmessages"	=> "Jen $1 por vi.",
"newmessageslink" => "nova mesaĝo",
"editsection"   => "redaktu",
"toc"           => "Enhavo",
"showtoc"       => "montru",
"hidetoc"       => "kaŝu",
"thisisdeleted" => "Vidu aŭ restarigu $1?",
"restorelink" => "$1 forigita(j)n versio(j)n",

# Main script and global functions
#
"nosuchaction"	=> "Ne ekzistas tia ago",
"nosuchactiontext" => "La agon ('action') nomitan de la URL
ne agnoskas la programaro de {{SITENAME}}",
"nosuchspecialpage" => "Ne ekzistas tia speciala paĝo",
"nospecialpagetext" => "Vi petis specialan paĝon kiun
ne agnoskas la programaro de {{SITENAME}}",

# General errors
#
"error"         => "Eraro", #FIXME: Fuŝo
"databaseerror" => "Datumbaza eraro",
"dberrortext"	=> "Sintakseraro okazis en informpeto al la datumaro.
Eble kaŭzis tion malpermesita serĉomendo (vidu je $5),
aŭ eble tio indikas cimon en la programaro.
Jen la plej laste provita informmendo:
<blockquote><tt><nowiki>$1</nowiki></tt></blockquote>
el la funkcio \"<tt>$2</tt>\". 
MySQL redonis eraron  \"<tt>$3: $4</tt>\".",
"noconnect"		=> "Neeblis konekti al la datumbazo; estas ia erarao aŭ oni riparadas la servilon.",
"nodb"			=> "Neeblis elekti datumaron $1",
"cachederror"   => "Intertempe, jen konservita kopio de la petita paĝo (ĝi eble ne estas ĝisdata).",
"readonly"		=> "Datumaro ŝlosita, nurlega",
"enterlockreason" => "Bonvolu klarigi, kial oni ŝlosas la datumaron, kaj
la estimatan tempon de malŝlosado.",
"readonlytext"	=> "La datumaro de {{SITENAME}} estas nun ŝlosita kontraŭ
novaj aldonaj kaj aliaj ŝanĝoj, probable pro laŭkutima flegado de la datumaro.
Bonvolu reprovu post iom da tempo.

La ŝlosinto lasis la jenan mesaĝon:
<p>$1</p>\n",
"missingarticle" => "La datumbazo ne trovis la tekston de
artikolo, kiun ĝi devus trovi, nomita \"$1\".
Ĉi tio ne estas eraro de la datumbazo, sed probable cimo en la programo.
Bonvolu raporti ĉi tion al iu sistemestro, kaj rimarkigi la retadreson (URL).",
"internalerror" => "Interna eraro",
"filecopyerror" => "Neeblis kopii dosieron  \"$1\" al \"$2\".",
"filerenameerror" => "Neeblis alinomi dosieron \"$1\" al \"$2\".",
"filedeleteerror" => "Neeblis forigi dosieron \"$1\".",
"filenotfound"	=> "Neeblis trovi dosieron \"$1\".",
"unexpected"	=> "Neatendita valuto: \"$1\"=\"$2\".",
"formerror"		=> "Eraro: neeblis liveri formulon",	
"badarticleerror" => "Tiu ago ne povas esti aplikata al tiu artikolo.",
"cannotdelete"  => "Neeblis forigi la elektitan paĝon aŭ dosieron.",
"badtitle"		=> "Nevalida titolo",
"badtitletext"	=> "La petita paĝotitolo estas nevalida, malplena, aŭ
malĝuste ligita interlingva aŭ intervikia titolo.",
"perfdisabled" => "Ni petas pardonon! La petita funkcio estas malebligita
provizore por konservi la rapidecon de la servilo.",
"perfdisabledsub" => "Jen konservita kopio laŭ $1:",
"viewsource" => "Vidu vikitekston",
"protectedtext" => "Tiu ĉi paĝon estas ŝlosita kontraŭ redaktado;
estas diversaj eblaj kialoj por tio.
Bv legi [[{{ns:4}}:Ŝlositaj paĝoj]].

Vi ja rajtas vidi kaj kopii la fontotekston de la vikipaĝo:",

# Login and logout pages
#
"logouttitle"	=> "Elsalutu!",
"logouttext"	=> "Vi elsalutis kaj finis vian seancon.
Vi rajtas daŭre Vikipediumi sennome, aŭ vi povas reensaluti
kiel la sama aŭ kiel alia uzulo.\n", #FIXME

"welcomecreation" => "<h2>Bonvenon, $1!</h2> Via konto estas kreita.
<font color=\"red\">Ne forgesu fari viajn {{SITENAME}}-preferojn!</font>",

"loginpagetitle" => "Ensalutu / enskribu", #FIXME
"yourname"		=> "Via salutnomo", #FIXME ĉu kaŝnomo ĉu uzantonomo ĉu kontonomo ktp?
"yourpassword"	=> "Via pasvorto",
"yourpasswordagain" => "Retajpu pasvorton",
"newusersonly"	=> " (nur novaj uzuloj)",
"remembermypassword" => "Rememoru mian pasvorton.",
"loginproblem"	=> "<b>Okazis problemo pri via ensalutado.</b><br />Bonvolu reprovi!",
"alreadyloggedin" => "<font color=\"red\"><b>Uzulo $1, vi jam estas ensalutinta!</b></font><br />\n",

"login"			=> "Ensalutu", #FIXME, what exactly do the following go to?
"userlogin"		=> "Ensalutu",
"logout"		=> "Elsalutu",
"userlogout"	=> "Elsalutu",
"notloggedin"	=> "Ne ensalutinta",
"createaccount"	=> "Kreu novan konton",
"badretype"		=> "La pasvortoj kiujn vi tajpis ne egalas.",
"userexists"	=> "Jam estas uzulo kun la nomo kiun vi elektis. Bonvolu elekti alian nomon.",
"youremail"		=> "Via retpoŝtadreso",
"yournick"		=> "Via kaŝnomo (por subskriboj)", #FIXME - ĉu kaŝnomo, plumnomo? / "Kaŝnomo" ŝajnas bona /Bertilo
"emailforlost"	=> "Se vi forgesos vian pasvorton, vi povas peti ke ni sendu novan al via retpoŝtadreso.",
"loginerror"	=> "Ensaluta eraro", #FIXME
"noname"		=> "Vi ne enmetis validan salutnomon.",
"loginsuccesstitle" => "Ensalutado sukcesis",
"loginsuccess"	=> "Vi nun estas en la {{SITENAME}} kiel uzulo \"$1\".",
"nosuchuser"	=> "Neniu uzulo nomiĝas \"$1\".
Bonvolu kontroli vian literumadon, aŭ uzu la malsupran formularon por krei novan konton.",
"wrongpassword"	=> "Vi tajpis malĝustan pasvorton. Bonvolu provi denove.",
"mailmypassword" => "Retpoŝtu al mi novan pasvorton",
"passwordremindertitle" => "Rememorigo el {{SITENAME}} pri perdita pasvorto", #FIXME
"passwordremindertext" => "Iu (probable vi, el IP-adreso $1)
petis, ke ni sendu al vi novan pasvorton por ensaluti {{SITENAME}}n.
La pasvorto por uzulo \"$2\" nun estas \"$3\".
Ni rekomendas, ke vi nun ensalutu kaj ŝanĝu vian pasvorton.", #FIXME
"noemail"		=> "Retpoŝtadreso ne estas registrita por uzulo \"\".",
"passwordsent"	=> "Oni sendis novan pasvorton al la retpoŝtadreso
registrita por \"$1\".
Bonvolu saluti denove ricevinte ĝin.",

# Edit pages
#
"summary"		=> "Resumo",
"minoredit"		=> "Ĉi tiu ŝanĝo estas redakteto",
"watchthis"		=> "Atentadu la artikolon",
"savearticle"	=> "Konservu ŝanĝojn",
"preview"		=> "Antaŭrigardo",
"showpreview"	=> "Antaŭrigardu", #FIXME eh?
"blockedtitle"	=> "Uzulo forbarita", #FIXME ĉu 'Konto forbarita'?
"blockedtext"	=> "Via konto aŭ IP-adreso estis forbarita fare de $1,
kiu priskribis la kialon jene:<br />$2
<p>Vi rajtas kontakti tiun administranton por pridiskuti la forbaradon.", #FIXME - sistemestro?
"newarticle"	=> "(Nova)",
"newarticletext" => "Vi sekvis ligilon al paĝo jam ne ekzistanta.
Se vi volas krei ĝin, ektajpu sube (vidu la [[{{ns:4}}:Helpo|helpopaĝo]] por klarigoj.)
Se vi malintence alvenis ĉi tien, simple alklaku la \"reen\" butonon de via retumilo.",
"anontalkpagetext" => "---- ''Jen diskutopaĝo por iu anonima kontribuanto kiu ne jam kreis
konton aŭ ne uzas ĝin. Ni tial devas uzi la cifran [[IP-adreso]] por tiun identigi.
Tia IA-adreso povas kundividiĝi de pluraj uzuloj. Se vi estas anonimulo kaj preferus
eviti tiajn maltrafajn komentojn kaj konfuziĝon kun aliaj anonimuloj ĉe via retero,
bonvolu [[Speciala:Userlogin|kreu konton aŭ ensalutu]].",
"noarticletext" => "(La paĝo nun estas malplena)", #FIXME
"updated"		=> "(Ŝanĝo registrita)", #FIXME: ?
"note"			=> "<strong>Noto:</strong> ", #FIXME: Where does this come from?
"previewnote"	=> "Memoru, ke ĉi tio estas nur antaŭrigardo kaj ne jam konservita!",
"previewconflict" => "La jena antaŭrigardo montras la tekston el la supra tekstujo,
kiel ĝi aperos se vi elektos konservi la paĝon.", #FIXME
"editing"		=> "Redaktante $1",
"sectionedit"	=> " (sekcion)",
"commentedit"	=> " (komenton)",
"editconflict"	=> "Redakta konflikto: $1",
"explainconflict" => "Iu alia ŝanĝis la paĝon post kiam vi ekredaktis.
La supra tekstujo enhavas la aktualan tekston de la artikolo.
Viaj ŝanĝoj estas en la malsupra tekstujo.
Vi devas mem kunfandi viajn ŝanĝojn kaj la jaman tekston.
<b>Nur</b> la teksto en la supra tekstujo estos konservita kiam
vi alklakos \"Konservu\".\n<p>" , #FIXME - double-check that this makes sense
"yourtext"		=> "Via teksto",
"storedversion" => "Registrita versio",
"editingold"	=> "<strong>AVERTO: Vi nun redaktas malnovan version de tiu ĉi artikolo.
Se vi konservos vian redakton, ĉiuj ŝanĝoj faritaj post tiu versio perdiĝos.</strong>\n",
"yourdiff"		=> "Malsamoj",
"copyrightwarning" => "Bonvolu noti, ke ĉiu kontribuaĵo al la {{SITENAME}}
estu rigardata kiel eldonita laŭ la <i>GNU Free Documentation License</i> (vidu je $1).
Se vi volas, ke via verkaĵo ne estu redaktota senkompate kaj disvastigota
laŭvole, ne alklaku \"Konservu\".
Vi ankaŭ ĵuras, ke vi mem verkis la tekston, aŭ ke vi kopiis ĝin el
fonto senkopirajta. <strong>NE UZU KOPIRAJTAJN VERKOJN SENPERMESE!</strong>",
"longpagewarning" => "AVERTO: Tiu ĉi paĝo longas $1 kilobitokojn; kelkaj retumiloj
povas fuŝi redaktante paĝojn je longo proksime aŭ preter 32kb.
Se eble, bonvolu disigi la paĝon al malpli grandajn paĝerojn.",
"readonlywarning" => "AVERTO: La datumbazo estas ŝlosita por teknika laboro;
pro tio neeblas nun konservi vian redaktadon. Vi povas elkopii kaj englui
la tekston al tekstdosiero por poste reenmeti ĝin al la vikio.",
"protectedpagewarning" => "AVERTO: Tiu ĉi paĝo estas ŝlosita kontraŭ redaktado
krom per administrantoj (t.e., vi). Bv certiĝi, ke vi sekvas la normojn de
la komunumo per via redaktado. Vidu [[{{ns:4}}:Ŝlositaj paĝoj]].",

# History pages
#
"revhistory"	=> "Historio de redaktoj",

"nohistory"		=> "Ne ekzistas historio de redaktoj por ĉi tiu paĝo.", #FIXME
"revnotfound"	=> "Ne ekzistas malnova versio de la artikolo", #fixme
"revnotfoundtext" => "Ne eblis trovi malnovan version de la artikolo kiun vi petis.
Bonvolu kontroli la retadreson (URL) kiun vi uzis por atingi la paĝon.\b",
"loadhist"		=> "Ŝarĝas redaktohistorion", #FIXME Apparently not used

"currentrev"	=> "Aktuala versio", #FIXME ĉu "plej lasta"?
"revisionasof"	=> "Kiel registrite je $1",
"cur"			=> "nun",

"next"			=> "sekv",
"last"			=> "ant",
"orig"			=> "orig",
"histlegend"	=> "Klarigo: (nun) = vidu malsamojn kompare kun la nuna versio,
(ant) = malsamojn kompare kun la antaŭa versio, M = malgranda redakteto",

# Diffs
#
"difference"	=> "(Malsamoj inter versioj)",
"loadingrev"	=> "ŝarĝas version por malsamoj", #FIXME Apparently not used
"lineno"		=> "Linio $1:",
"editcurrent"	=> "Redaktu la nunan version de la paĝo",

# Search results
#
"searchresults" => "Serĉrezultoj",
"searchhelppage" => "{{ns:4}}:Serĉado",
"searchingwikipedia" => "Priserĉante la {{SITENAME}}n",
"searchresulttext" => "Por pliaj informoj kiel priserĉi la {{SITENAME}}n, vidu $1.",
"searchquery"	=> "Serĉmendo \"$1\"",
"badquery"		=> "Misformita serĉmendo",
"badquerytext"	=> "Via serĉmendo ne estis plenumebla.
Eble vi provis serĉi vorton malpli longan ol tri literoj. 
Tion la programo ne jam povas fari. Ankaŭ eblas, ke vi mistajpis la
esprimon".
#", ekzemple \"fiŝoj kaj kaj skaloj\"".   # FIXME ? eblas
". Bonvolu reserĉi per alia mendo.",
"matchtotals"	=> "La serĉmendo \"$1\" liveris $2 artikolojn laŭ titolo
kaj $3 artikolojn laŭ enhavo.",
"nogomatch"	=> "Neniu paĝo havas precize la titolon; provas tekstoserĉon... ",
"titlematches"	=> "Trovitaj laŭ titolo",
"notitlematches" => "Neniu trovita laŭ titolo",
"textmatches"	=> "Trovitaj laŭ enhavo",
"notextmatches"	=> "Neniu trovita laŭ enhavo",
"prevn"			=> "$1 antaŭajn",
"nextn"			=> "$1 sekvajn",
"viewprevnext"	=> "Montru ($1) ($2) ($3).",
"showingresults" => "Montras <b>$1</b> trovitajn ekde la <b>$2</b>-a.",
"showingresultsnum" => "Montras <b>$3</b> trovitajn ekde la <b>$2</b>-a.",
"nonefound"		=> "<strong>Noto</strong>: malsukcesaj serĉoj ofte
okazas ĉar oni serĉas tro da ofte uzataj vortoj, kiujn ne enhavas la indekso,
aŭ ĉar oni petas tro da serĉvortoj (nur paĝoj kiuj enhavas ĉiun serĉvorton
montriĝos en la rezulto).",
"powersearch" => "Trovu",

"powersearchtext" => "
Serĉu en sekcioj: :<br />
$1<br />
$2 Kun alidirektiloj   Serĉu $3 $9",
"searchdisabled" => "<p>Oni provizore malŝaltis serĉadon per la plenteksta
indekso pro troŝarĝita servilo. Intertempe, vi povas serĉi per google:</p>
                                                                                                                                                        
",
"googlesearch" => "<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Google Search\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br /><input type=radio
name=sitesearch value=\"\"> TTT <input type=radio name=sitesearch
value=\"{$wgServer}\" checked> {$wgServer} <br />
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->
",
"blanknamespace" => "(Artikoloj)",

# Preferences page
#
"preferences"	=> "Preferoj",
"prefsnologin" => "Ne jam salutis!",
"prefsnologintext"	=> "<a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">Ensalutu</a>
kaj vi povos ŝanĝi viajn preferojn.",
"prefslogintext" => "Vi ensalutis kiel \"$1\".
Via interna identeconumero estas $2.",
"prefsreset"	=> "Preferoj reprenitaj el la registro.", #FIXME: Hmm...
"qbsettings"	=> "Preferoj pri ilaro", 
"changepassword" => "Ŝanĝu pasvorton",
"skin"			=> "Aspekto",
"math"			=> "Tradukas matematikaĵon",
"math_failure"	=> "Malsukcesis analizi formulon",
"math_unknown_error"	=> "Nekonata eraro",
"math_unknown_function"	=> "Nekonata funkcio",
"math_lexing_error"	=> "Leksika analizo malsukcesis",
"math_syntax_error" => "Eraro de sintakso",
'prefs-personal'	=> 'Idento',
'prefs-rc'		=> 'Lastaj Ŝanĝoj kaj elmontro de stumpoj',
'prefs-misc'	=> 'Miksitaĵoj',
"saveprefs"		=> "Konservu preferojn",
"resetprefs"	=> "Restarigi antaŭajn preferojn",
"oldpassword"	=> "Malnova pasvorto",
"newpassword"	=> "Nova pasvorto",
"retypenew"		=> "Retajpu novan pasvorton",
"textboxsize"	=> "Grandeco de redakta tekstujo",
"rows"			=> "Linioj",
"columns"		=> "Kolumnoj",
"searchresultshead" => "Agordaĵoj pri serĉorezulto",
"resultsperpage" => "Montru trovitajn po",
"contextlines"	=> "Montru liniojn el paĝoj po",
"contextchars"	=> "Montru literojn el linioj ĝis po",
"stubthreshold" => "Indiku paĝojn malpli grandajn ol",
"recentchangescount" => "Montru kiom da titoloj en 'Lastaj ŝanĝoj'",
"savedprefs"	=> "Viaj preferoj estas konservitaj.",
'timezonelegend'	=> 'Horzono',
"timezonetext"	=> "Indiku je kiom da horoj via
loka horzono malsamas disde tiu de la servilo (UTC).
Ekzemple, por la Centra Eŭropa Horzono, indiku \"1\" vintre aŭ \"2\" dum somertempo.",
"localtime"	=> "Loka horzono",
"timezoneoffset" => "Malsamo", #FIXME (?)
"servertime"	=> "Norma tempo aktuale",
"guesstime"		=> "Petu al foliumilo",
"emailflag"     => "Malakceptu retmesaĝojn de aliaj vikipediistoj",
"defaultns"		=> "Serĉu la jenajn sekciojn:",

# Recent changes
#
"changes"	=> "ŝanĝoj", # RIPARUMIN n?
"recentchanges" => "Lastaj ŝanĝoj",
"recentchangestext" => "Sekvu la plej lastajn ŝanĝojn al la vikio per ĉi tiu paĝo.",
"rcloaderr"		=> "Ŝarĝas lastajn ŝanĝojn",
"rcnote"		=> "Jen la plej lastaj <b>$1</b> ŝanĝoj en la lastaj <b>$2</b> tagoj.",
"rcnotefrom"	=> "Jen la ŝanĝoj ekde <b>$2</b> (lastaj ĝis <b>$1</b>).",
"rclistfrom"	=> "Montru novajn ŝanĝojn ekde $1",
"rclinks"		=> "Montru $1 lastajn ŝanĝojn; montru la ŝanĝojn dum la $2 lastaj tagoj.",
"diff"			=> "malsamoj",
"hist"			=> "historio",
"hide"			=> "kaŝu",
"show"			=> "montru",
"tableform"		=> "tabelo",
"listform"		=> "listo",
"nchanges"		=> "$1 ŝanĝoj",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Alŝutu dosieron",
"uploadbtn"		=> "Alŝutu dosieron",
"uploadlink"	=> "Alŝutu bildon", # Ĉu neuzata?
"reupload"		=> "Realŝutu",
"reuploaddesc"	=> "Revenu al la alŝuta formularo.",
"uploadnologin" => "Ne ensalutinta",
"uploadnologintext"	=> "Se vi volas alŝuti dosierojn, vi devas <a href=\"" .
  wfLocalUrl( "Speciala:Userlogin" ) . "\">ensaluti</a>.",
"uploadfile"	=> "Alŝutu dosieron",
"uploaderror"	=> "Eraro okazis dum alŝuto",
"uploadtext"	=> "Por okulumi aŭ serĉi jam alŝutitajn dosierojn,
aliru la [[Special:Imagelist|liston de alŝutaĵoj]].
Ĉiuj alŝutoj kaj forigoj estas registrataj en la
[[Project:Loglibro de alŝutaĵoj|alŝuta loglibro]].

Uzu ĉi tiun formularon por alŝuti novajn bildojn kaj aliajn dosierojn
por ilustrado de viaj artikoloj.
Ĉe kutimaj retumiloj, vi vidos ĉi-sube butonon \"Foliumi...\" aŭ simile;
tiu malfermas la dosierelektilon de via operaciumo.
Kiam vi elektos dosieron, ĝia nomo plenigos la tekstujon apud la butono.
Vi ankaŭ nepre devas klakjesi la skatolon por aserti, ke vi ne
malobeas la leĝan kopirajton de aliuloj per alŝuto de la dosiero.
Por plenumi la alŝutadon, alklaku la butono \"Alŝutu\".
Tio ĉi eble iomete longe daŭros, se estas granda dosiero kaj se via interreta konekto malrapidas.

La dosiertipoj preferataj ĉe {{SITENAME}} estas JPEG por fotografaĵoj,
PNG por grafikaĵoj, diagramoj, ktp; kaj OGG por sonregistraĵoj.
Bonvolu doni al via dosiero nomon informan, por eviti konfuzon.
Por enmeti la dosieron en artikolon, skribu ligilon laŭ la formo
'''<nowiki>[[dosiero:dosiero.jpg]]</nowiki>''' aŭ
'''<nowiki>[[dosiero:bildo.png|teksto por retumiloj negrafikaj]]</nowiki>''', aŭ
'''<nowiki>[[dosiero:dosiero.ogg]]</nowiki>''' por sono.

Bonvolu rimarki, ke same kiel artikoloj en la {{SITENAME}}, aliaj Vikipediistoj
rajtas redakti, anstataŭigi, aŭ forigi viajn alŝutaĵojn se ili pensas, ke
tio servus la enciklopedion. Se vi aĉe misuzas la sistemon, eblas ke vi estos
forbarita.",
"uploadlog"		=> "loglibro de alŝutaĵoj",
"uploadlogpage" => "Loglibro_de_alŝutaĵoj",
"uploadlogpagetext" => "Jen la plej laste alŝutitaj dosieroj.
Ĉiuj tempoj montriĝas laŭ la horzono UTC.
<ul>
</ul>
",
"filename"		=> "Dosiernomo",
"filedesc"		=> "Priskribo",
"affirmation"	=> "Mi asertas, ke la laŭleĝa posedanto de la kopirajto
de ĉi tiu dosiero konsentas eldoni ĝin laŭ la $1.",
"copyrightpage" => "{{ns:4}}:Kopirajto",
"copyrightpagename" => "permesilo GFDL uzata por la {{SITENAME}}",
"uploadedfiles"	=> "Alŝutitaj dosieroj",
"noaffirmation" => "Vi nepre devas aserti, ke via alŝutaĵo ne malobeas la leĝojn de kopirajto.",
"ignorewarning"	=> "Malatentu averton kaj tamen konservu la dosieron.",
"minlength"		=> "Dosiernomo devas havi pli ol du literojn.",
"badfilename"	=> "Dosiernomo estis ŝanĝita al \"$1\".",
"badfiletype"	=> "\".$1\" estas dosiertipo malrekomendata.",
"largefile"		=> "Oni rekomendas, ke dosieroj ne superu grandon de 100 kilobitoj.",
"successfulupload" => "Alŝuto sukcesis!",
"fileuploaded"	=> "Vi sukcese alŝutis dosieron \"$1\".
Bonvolu sekvi la jenan ligilo: ($2) al la priskrib-paĝo kaj
verki iom da informo pri la dosiero. Ekzemple, de kie ĝi devenas;
kiam ĝi estis kreita, kaj kiu kreis ĝin; kaj ion ajn, kion vi scias pri ĝi.",
"uploadwarning" => "Averto",
"savefile"		=> "Konservu dosieron",
"uploadedimage" => "alŝutis \"$1\"",
"uploaddisabled" => "Ni petas pardonon, sed oni malebligis alŝutadon.",

# Image list
#
"imagelist"		=> "Listo de alŝutitaj dosieroj",
"imagelisttext"	=> "Jen listo de $1 alŝutaĵoj, ordigitaj laŭ $2.",
"getimagelist"	=> "akiras dosierliston",
"ilshowmatch"	=> "Montru tiujn dosierojn kies nomoj trafas",
"ilsubmit"		=> "Trovu!",
"showlast"		=> "Montru la $1 lastajn bildojn laŭ $2.",
"all"			=> "ĉiuj",
"byname"		=> "nomo",
"bydate"		=> "dato",
"bysize"		=> "grandeco",
"imgdelete"		=> "forigu",
"imgdesc"		=> "pri",
"imglegend"		=> "(pri) = montru/redaktu priskribon de dosiero.",
"imghistory"	=> "Historio de alŝutoj",
"revertimg"		=> "res",
"deleteimg"		=> "for",
"deleteimgcompletely"		=> "for",
"imghistlegend" => "(nun) = ĉi tiu estas la nuna versio de la dosiero, (for) = forigu
ĉi tiun malnovan version, (res) = restarigu ĉi tiun malnovan version.
<br /><i>Por vidi la dosieron laŭdate, alklaku la daton</i>.",
"imagelinks"	=> "Ligiloj al la dosiero",
"linkstoimage"	=> "La jenaj paĝoj ligas al ĉi tiu dosiero:",
"nolinkstoimage" => "Neniu paĝo ligas al ĉi tiu dosiero.",

# Statistics
#
"statistics"	=> "Statistiko",
"sitestats"		=> "Pri la retejo",
"userstats"		=> "Pri la uzularo",
"sitestatstext" => "Troviĝas en nia datumaro sume '''$1''' paĝoj.
Tiu nombro enhavas \"diskutpaĝojn\", paĝojn pri {{SITENAME}}, \"artikoletetojn\", alidirektilojn, kaj aliajn, kiuj eble ne vere estas artikoloj. Malatentante ilin, oni povas nombri '''$2''' probablajn ĝustajn
artikolojn.

Oni vidis sume '''$3''' paĝojn, kaj redaktis sume '''$4''' paĝojn
ekde la starigo de la nuna vikiprogramo (novembro 2002).
Tio estas meznombre po unu paĝo por '''$5''' paĝoj viditaj, kaj por '''$6''' redaktoj.",
"userstatstext" => "Enskribiĝis '''$1''' uzuloj. El tiuj, '''$2''' estas administrantoj (vidu $3).",

# Maintenance Page
#
"maintenance"		=> "Ripariloj kaj zorgiloj",
"maintnancepagetext"	=> "Jen diversaj iloj por riparado kaj ĝenerala zorgado de la datumaro.
Kelkaj funkcioj povas streĉi la datumbazon, do bonvolu ne reŝuti post ĉiu riparita ero!",
"maintenancebacklink"	=> "Revenu al la ilaro",
"disambiguations"	=> "Misligitaj apartigiloj",
"disambiguationspage"	=> "{{ns:4}}:Apartigiloj",
"disambiguationstext"	=> "La jenaj paĝoj alligas <i>paĝon-apartigilon</i>. Ili devus anstataŭe alligi la ĝustan temon.<br />Oni konsideras tiujn paĝojn, kiujn alligas $1 apartigiloj.<br />Ligado el ne-artikolaj sekcioj <i>ne</i> listiĝas ĉi tie.",
"doubleredirects"	=> "Duoblaj alidirektadoj",
"doubleredirectstext"	=> "<b>Atentu:</b> Eblas, ke la jena listo enhavas falsajn rezultojn. Ĝenerale, tio signifas, ke estas plua teksto kun ligiloj post la #REDIRECT.<br />
Ĉiu linio montras ligilojn ĉe la unua kaj dua alidirektadoj, kaj la unua linio de la teksto de la dua alidirektado, kiu ĝenerale montras la \"veran\" artikolon, kiu devus celi la unuan alidirektadon.",
"brokenredirects"	=> "Rompitaj alidirektadoj",
"brokenredirectstext"	=> "La jenaj alidirektadoj ligas al neekzistantaj artikoloj.",
"selflinks"		=> "Paĝoj memligantaj",
"selflinkstext"		=> "La jenaj paĝoj enhavas ligilon al si mem, kiuj neutilas.",
"mispeelings"           => "Paĝoj kun misliterumoj",
"mispeelingstext"               => "La jenaj paĝoj enhavas unu el la oftaj misliterumadoj listitaj en $1. La ĝusta literumo montriĝos (ĉi tiel).",
"mispeelingspage"       => "Listo de oftaj misliterumoj",
"missinglanguagelinks"  => "Mankantaj interlingvaj ligiloj",
"missinglanguagelinksbutton"    => "Montru mankajn interlingvajn ligilojn por",
"missinglanguagelinkstext"      => "La jenaj artikoloj <i>ne</i> ligas al sia versio en la lingvo $1. Alidirektadoj kaj subpaĝoj <i>ne</i> montriĝas.",


# Miscellaneous special pages
#
"orphans"		=> "Neligitaj paĝoj",
"lonelypages"	=> "Neligitaj paĝoj",
"unusedimages"	=> "Neuzataj bildoj",
"popularpages"	=> "Plej vizitataj paĝoj",
"nviews"		=> "$1-foje",
"wantedpages"	=> "Dezirataj paĝoj",
"nlinks"		=> "$1 ligiloj",
"allpages"		=> "Ĉiuj paĝoj",
"randompage"	=> "Hazarda paĝo",
"shortpages"	=> "Paĝetoj",
"longpages"		=> "Paĝegoj",
"listusers"		=> "Uzularo",
"specialpages"	=> "Specialaj paĝoj",
"spheading"		=> "Specialaj paĝoj",
"sysopspheading" => "Specialaj paĝoj por uzado de administrantoj",
"developerspheading" => "Specialaj paĝoj nur por uzado de programistoj",
"protectpage"	=> "Protektu paĝon",
"recentchangeslinked" => "Rilataj paĝoj",
"rclsub"		=> "(al paĝoj ligitaj de \"$1\")",
"debug"			=> "Kontraŭcima", #CHUCK ĉu "malcimigu"? | Pli bone "sencimigi" /Bertilo
"newpages"		=> "Novaj paĝoj",
"movethispage"	=> "Movu la paĝon",
"unusedimagestext" => "Notu, ke aliaj TTT-ejoj, ekzemple
la alilingvaj {{SITENAME}}j, povas rekte ligi al dosiero per URL.
Tio ne estus enkalkutita en la jena listo.",
"booksources"	=> "Libroservoj",
"booksourcetext" => "Jen ligilaro al aliaj TTT-ejoj, kiuj vendas librojn,
kaj/aŭ informumos pri la libro ligita.
La {{SITENAME}} ne estas komerce ligita al tiuj vendejoj, kaj la listo ne estu
komprenata kiel rekomendo aŭ reklamo.", 
"alphaindexline" => "$1 ĝis $2",

# Email this user
#
"mailnologin"	=> "Neniu alsendota adreso",
"mailnologintext" => "Vi nepre estu <a href=\"" .
  wfLocalUrl( "Speciala:Userlogin" ) . "\">salutanta</a>
kaj havanta validan retpoŝtadreson en viaj <a href=\"" .
  wfLocalUrl( "Speciala:Preferences" ) . "\">preferoj</a>
por retpoŝti al aliaj Vikipediistoj.",
"emailuser"		=> "Retpoŝtu",
"emailpage"		=> "Retpoŝtu",
"emailpagetext"	=> "Se la alsendota vikipediisto donis validan retpoŝtadreson
en la preferoj, vi povas sendi unu mesaĝon per la jena formulo.
La retpoŝtadreso, kiun vi metis en la preferoj, aperos kiel \"El\"-adreso
de la poŝto, por ke la alsendonto povos respondi.",
"noemailtitle"	=> "Neniu retpoŝtadreso",
"noemailtext"	=> "Ĉi tiuj vikipediistoj aŭ ne donis validan retpoŝtadreson
aŭ elektis ne ricevi retpoŝton de aliaj vikipediistoj.",
"emailfrom"		=> "El",
"emailto"		=> "Al",
"emailsubject"	=> "Subjekto",
"emailmessage"	=> "Mesaĝo",
"emailsend"		=> "Sendu",
"emailsent"		=> "Retmesaĝo sendita",
"emailsenttext" => "Via retmesaĝo estas sendita.",



# Watchlist
#
"watchlist"		=> "Atentaro",
"watchlistsub"	=> "(de uzulo \"$1\")",
"nowatchlist"	=> "Vi ne jam elektis priatenti iun ajn paĝon.",
"watchnologin"	=> "Ne salutinta",
"watchnologintext"	=> "Nepras <a href=\"" .
  wfLocalUrl( "Speciala:Userlogin" ) . "\">saluti</a>
por ŝanĝi vian atentaron.",
"addedwatch"	=> "Aldonis al atentaro",
"addedwatchtext" => "La paĝo \"[[$1]]\" estis aldonita al via [[Speciala:Watchlist|atentaro]].
Estontaj ŝanĝoj al tiu paĝo aperos en '''grasa tiparo''' en la
[[Speciala:Recentchanges|listo de Lastaj Ŝanĝoj]],
kaj estos kalkulitaj en la listo de via atentaro.

Se vi poste volos eksigi la paĝon el via atentaro, alklaku \"Malatentu paĝon\" en la ilobreto.",
"removedwatch"	=> "Forigis el atentaro",
"removedwatchtext" => "La paĝo \"[[$1]]\" estas forigita el via atentaro.",
"watchthispage"	=> "Priatentu paĝon",
"unwatchthispage" => "Malatentu paĝon",
"notanarticle"	=> "Ne estas artikolo",
"watchnochange" => "Neniu artikolo en via atentaro redaktiĝis dum la prispektita tempoperiodo.",
"watchdetails" => "(Vi priatentas $1 paĝojn [krom diskutopaĝoj];
laste $2 paĝoj entute redaktiĝis en la vikio; $3...
<a href='$4'>redaktu vian atentaron</a>.)",
"watchmethod-recent" => "traserĉas lastajn redaktojn",
"watchmethod-list" => "traserĉas priatentitajn",
"removechecked" => "Forprenu elektitajn el la listo",
"watchlistcontains" => "Via atentaro enhavas $1 paĝojn.",
"watcheditlist" => "Jen listo de ĉiu paĝtitolo en via atentaro.
Elektu forigotajn paĝojn kaj alklaku 'forprenu elektitajn' sube.",
"removingchecked" => "Forprenas elektitajn...",
"couldntremove" => "Neeblas forigi titolon '$1'...",
"iteminvalidname" => "Ia eraro pri '$1', nevalida titolo...",
"wlnote" => "Jen la lastaj $1 redaktoj de la lastaj <b>$2</b> horoj.",
"wlshowlast" => "Montru el lastaj $1 horoj $2 tagoj $3",


# Delete/protect/revert
#
"deletepage"	=> "Forigu paĝon",
"confirm"		=> "Konfirmu",
"excontent"		=> "enhavis:",
"exbeforeblank" => "antaŭ malplenigo enhavis:",
"exblank"       => "estis malplena",
"confirmdelete" => "Konfirmu forigadon",
"deletesub"		=> "(Forigas \"$1\")",
"historywarning" => "Averto: la forigota paĝo havas historion: ",
"confirmdeletetext" => "Vi forigos la artikolon aŭ dosieron kaj
forviŝos ĝian tutan historion el la datumaro.<br />
Bonvolu konfirmi, ke vi vere intencas tion, kaj ke vi komprenas
la sekvojn, kaj ke vi ja sekvas la [[{{ns:4}}:Reguloj pri forigado|regulojn pri forigado]].",
"confirmcheck"	=> "Jes, mi tutkore certas ke mi volas forigi tiun artikolon/dosieron.",
"actioncomplete" => "Ago farita",
"deletedtext"	=> "\"$1\" estas forigita.
Vidu la paĝon $2 por registro de lastatempaj forigoj.",
"deletedarticle" => "forigis \"$1\"",
"dellogpage"	=> "Loglibro_de_forigoj", # NEPRE NE FORIGU LA "_"-SIGNOJN!
"dellogpagetext" => "Jen listo de la plej lastaj forigoj el la datumaro.
Ĉiuj tempoj sekvas la horzonon UTC.
<ul>
</ul>
",
"deletionlog"	=> "listo de forigoj",
"reverted"		=> "Restarigis antaŭan version",
"deletecomment"	=> "Kialo por forigo",
"imagereverted" => "Restarigo de antaŭa versio sukcesis.",
"rollback"	=> "Restarigu antaŭan redakton",
"rollbacklink" => "restarigu antaŭan",
"rollbackfailed" => "Restarigo malsukcesis",
"cantrollback" => "Neeblas restarigi antaŭan redakton; la redaktinto lasta estas la sola de la paĝo.",
"alreadyrolled" => "Neeblas restarigi lastan redakton al [[$1]]
de [[Vikipediisto:$2|$2]] ([[Vikipediista diskuto:$2|diskuto]]) pro tio,
ke oni intertempe redaktis la paĝon.

Lasta redaktinto estas [[Vikipediisto:$3|$3]] ([[Vikipediista diskuto:$3|diskuto]]). ",
"editcomment" => "La komento estis: '<i>$1</i>'.",
"revertpage"	=> "Restarigis lastan redakton de $1",

# Undelete
"undelete" => "Restarigu forigitan paĝon",
"undeletepage" => "Montru kaj restarigu forigitajn paĝojn",
"undeletepagetext" => "La jenaj paĝoj estis forigitaj, sed ankoraŭ restas arkivitaj,
kaj oni povas restarigi ilin. La arkivo povas esti malplenigita periode.",
"undeletearticle" => "Restarigu forigitan artikolon",
"undeleterevisions" => "$1 versioj arkivitaj",
"undeletehistory" => "Se vi restarigos la paĝon, ĉiuj versioj estos restarigitaj
en la historio. Se nova paĝo kun la sama nomo estis kreita post la forigo, la restarigitaj
versioj aperos antaŭe en la historio, kaj la aktuala versio ne estos anstataŭigita.",
"undeleterevision" => "Forigita versio de $1", # ( estas tempo)
"undeletebtn" => "Restarigu!",
"undeletedarticle" => "restarigis \"$1\"",
"undeletedtext"   => "La artikolo [[$1]] estas sukcese restarigita.
Vidu [[{{ns:4}}:Loglibro de forigoj]] por registro de lastatempaj forigoj kaj restarigoj.",

# Contributions
#
"contributions"	=> "Kontribuoj de Vikipediisto",
"mycontris"	=> "Miaj kontribuoj",
"contribsub"	=> "De $1",
"nocontribs"	=> "Trovis neniajn redaktojn laŭ tiu kriterio.",
"ucnote"		=> "Jen la <b>$1</b> lastaj redaktoj de tiu Vikipediisto dum la <b>$2</b> lastaj tagoj.",
"uclinks"		=> "Montru la $1 lastajn redaktojn; montru la $2 lastajn tagojn.",
"uctop"			=> " (lasta)",

# What links here
#
"whatlinkshere"	=> "Ligiloj ĉi tien",
"notargettitle" => "Sen celpaĝo",
"notargettext"	=> "Vi ne precizigis, kiun paĝon aŭ uzulon priumi.",
"linklistsub"	=> "(Listo de ligiloj)",
"linkshere"		=> "La jenaj paĝoj ligas ĉi tien:",
"nolinkshere"	=> "Neniu paĝo ligas ĉi tien.",
"isredirect"	=> "alidirekto",

# Block/unblock IP
#
"blockip"		=> "Forbaru IP-adreson/nomon",
"blockiptext"	=> "Per la jena formularo vi povas forbari iun nomon aŭ
IP-adreson de la rajto enskribiĝi en la vikion.
Oni tion faru ''nur'' por eviti vandalismon, kaj sekvante la
[[{{ns:4}}:Reguloj pri forbarado|regulojn pri forbarado]].
Klarigu la precizan kialon malsupre (ekzemple, citu paĝojn, kiuj estis
vandalumitaj).",
"ipaddress"		=> "IP-adreso/nomo",
"ipbreason"		=> "Kialo",
"ipbsubmit"		=> "Forbaru la adreson",
"badipaddress"	=> "Neniu uzanto, aŭ la IP-adreso estas misformita.",
"noblockreason" => "Vi nepre klarigu kialon pri la forbaro.",
"blockipsuccesssub" => "Sukcesis forbari",
"blockipsuccesstext" => "\"$1\" estas forbarita.
<br />Vidu la [[Special:Ipblocklist|liston de IP-forbaroj]].",
"unblockip"		=> "Malforbaru IP-adreson/nomon",
"unblockiptext"	=> "Per la jena formulo vi povas repovigi al iu
forbarita IP-adreso/nomo la povon enskribi en la vikio.",
"ipusubmit"		=> "Malforbaru la adreson",
"ipusuccess"	=> "\"$1\" estas malforbarita",
"ipblocklist"	=> "Listo de forbaritaj IP-adresoj/nomoj",
"blocklistline"	=> "Je $1, $2 forbaris $3",
"blocklink"		=> "forbaru",
"unblocklink"	=> "malforbaru",
"contribslink"	=> "kontribuoj",
"autoblocker"	=> "Provizore forbarita aŭtomate pro tio, ke vi uzas saman IP-adreson kiel \"$1\", kiu estis blokita pro tio: \"$2\".",

# Developer tools
#
"lockdb"		=> "Ŝlosi datumaron",
"unlockdb"		=> "Malŝlosi datumaron",
"lockdbtext"	=> "Se vi ŝlosos la datumaron, tio malebligos al ĉiuj uzuloj
redakti paĝojn, ŝanĝi preferojn, priumi atentarojn, kaj fari diversajn aliajn
aferojn, por kiuj nepras ŝanĝi la datumaron.
Bonvolu certigu, ke vi efektive intencas tion fari, kaj ke vi ja malŝlosos
la datumaron post ol vi finos vian riparadon.",
"unlockdbtext"	=> "Se vi malŝlosos la datumaron, tio reebligos al ĉiuj uzuloj
redakti paĝojn, ŝanĝi preferojn, priumi la atentaron, kaj fari aliajn aferojn,
por kiuj nepras ŝanĝi al la datumaro.
Bonvolu certigu, ke vi efektive intencas tion fari.",
"lockconfirm"	=> "Jes, mi vere volas ŝlosi la datumaron.",
"unlockconfirm"	=> "Jes, mi vere volas malŝlosi la datumaron.",
"lockbtn"		=> "Ŝlosi datumaron",
"unlockbtn"		=> "Malŝlosi datumaron",
"locknoconfirm" => "Vi ne konfirmis.",
"lockdbsuccesssub" => "Datumaro ŝlosita",
"unlockdbsuccesssub" => "Datumaro malŝlosita",
"lockdbsuccesstext" => "La datumaro de {{SITENAME}} estas ŝlosita.
<br />Ne forgesu malŝlosi ĝin post kiam vi finos la riparadon.",
"unlockdbsuccesstext" => "La datumaro de {{SITENAME}} estas malŝlosita.",

# SQL query
#
"asksql"		=> "SQL-informpeto",
"asksqltext"	=> "Per la jena formulo vi povas rekte peti la datumbazon
per informpeto SQL-a.
Tio povas ege ŝarĝi la servilon, do bonvolu uzi tiun eblon ŝpare kaj singarde.",
"sqlquery"		=> "Tajpu informpeton",
"querybtn"		=> "Petu!",
"selectonly"	=> "Informpetojn krom \"SELECT\" estas limigitaj je
{{SITENAME}}-programistoj.",
"querysuccessful" => "Informpeto sukcesis",

# Move page
#
"movepage"		=> "Movu paĝon",
"movepagetext"	=> "Per la jena formulo vi povas ŝanĝi la nomon de iu paĝo, kunportante
ĝian historion de redaktoj je la nova nomo.
La antaŭa titolo fariĝos alidirektilo al la nova titolo.
Ligiloj al la antaŭa titolo <i>ne</i> estos ŝanĝitaj; uzu
la [[Speciala:Maintenance|riparilojn kaj zorgilojn]] por certigi,
ke ne restos duoblaj aŭ fuŝitaj alidirektiloj.
Kiel movanto, vi respondecas pri ĝustigado de fuŝitaj ligiloj.

Notu, ke la paĝo '''ne''' estos movita se jam ekzistas paĝo
ĉe la nova titolo, krom se ĝi estas malplena aŭ alidirektilo
al ĉi tiu paĝo, kaj sen antaŭa redaktohistorio. Pro tio, vi ja
povos removi la paĝon je la antaŭa titolo se vi mistajpus, kaj
neeblas ke vi neintence forviŝus ekzistantan paĝon per movo.

<b>AVERTO!</b>
Tio povas esti drasta kaj neatendita ŝanĝo por populara paĝo;
bonvolu certigi vin, ke vi komprenas ties konsekvencojn antaŭ
ol vi antaŭeniru.",

"movepagetalktext" => "La movo aŭtomate kunportos la diskuto-paĝon, se tia ekzistas, '''krom se:'''
*Vi movas la paĝon tra sekcioj (ekz de ''Nomo'' je ''Vikipediisto:Nomo''),
*Ne malplena diskuto-paĝo jam ekzistas je la nova nomo, aŭ
*Vi malelektas la suban ŝaltilon.

Tiujokaze, vi nepre permane kunigu la diskuto-paĝojn se vi tion deziras.",
"movearticle"	=> "Movu paĝon",
"movenologin"	=> "Ne ensalutinta",
"movenologintext" => "Vi nepre estu registrita uzulo kaj <a href=\"" .
  wfLocalUrl( "Speciala:Userlogin" ) . "\">ensalutu</a>
por rajti movi paĝojn.",
"newtitle"		=> "Al nova titolo",
"movepagebtn"	=> "Movu paĝon",
"pagemovedsub"	=> "Sukcesis movi",
"pagemovedtext" => "Paĝo \"[[$1]]\" estas movita al \"[[$2]]\".",
"articleexists" => "Paĝo kun tiu nomo jam ekzistas, aŭ la nomo kiun vi elektis ne validas.
Bonvolu elekti alian nomon.",
"talkexists"	=> "Oni ja sukcesis movi la paĝon mem, sed
ne movis la diskuto-paĝon ĉar jam ekzistas tia ĉe la nova titolo.
Bonvolu permane kunigi ilin.",
"movedto"		=> "movis al",
"movetalk"		=> "Movu ankaŭ la \"diskuto\"-paĝon, se ĝi ekzistas.",
"talkpagemoved" => "Ankaŭ la diskutpaĝo estas movita.",
"talkpagenotmoved" => "La diskutpaĝo <strong>ne</strong> estas movita.",
# Math
	'mw_math_png' => "Ĉiam krei PNG-bildon",
	'mw_math_simple' => "HTMLigu se simple, aŭ PNG",
	'mw_math_html' => "HTMLigu se eble, aŭ PNG",
	'mw_math_source' => "Lasu TeX-fonton (por tekstfoliumiloj)",
	'mw_math_modern' => "Rekomendita por modernaj foliumiloj",
	'mw_math_mathml' => "MathML seeble (provizora)",

);


class LanguageEo extends LanguageUtf8 {

	function getDefaultUserOptions () {
		$opt = Language::getDefaultUserOptions();
		$opt["altencoding"] = 0;
		return $opt;
	}

	function getNamespaces() {
		global $wgNamespaceNamesEo;
		return $wgNamespaceNamesEo;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesEo;
		return $wgNamespaceNamesEo[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesEo;

		foreach ( $wgNamespaceNamesEo as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( 0 == strcasecmp( "Special", $text ) ) return -1;
		if( 0 == strcasecmp( "Wikipedia", $text ) ) return 4;
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEo;
		return $wgQuickbarSettingsEo;
	}

	function getSkinNames() {
		global $wgSkinNamesEo;
		return $wgSkinNamesEo;
	}

	# Heredu userAdjust()
 
	# La dato- kaj tempo-funkciojn oni povas precizigi laŭ lingvo
	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . 
		  substr( $ts, 0, 4 );
		return $d;
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesEo;
		return $wgValidSpecialPagesEo;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesEo;
		return $wgSysopSpecialPagesEo;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesEo;
		return $wgDeveloperSpecialPagesEo;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesEo;
		if(array_key_exists($key, $wgAllMessagesEo))
			return $wgAllMessagesEo[$key];
		else
			return Language::getMessage($key);
	}

	function iconv( $in, $out, $string ) {
		# For most languages, this is a wrapper for iconv
		# Por multaj lingvoj, ĉi tiu nur voku la sisteman funkcion iconv()
		# Ni ankaŭ konvertu X-sistemajn surogotajn
		if( strcasecmp( $in, "x" ) == 0 and strcasecmp( $out, "utf-8" ) == 0) {
			$xu = array (
				"xx" => "x" , "xX" => "x" ,
				"Xx" => "X" , "XX" => "X" ,
				"Cx" => "\xc4\x88" , "CX" => "\xc4\x88" ,
				"cx" => "\xc4\x89" , "cX" => "\xc4\x89" ,
				"Gx" => "\xc4\x9c" , "GX" => "\xc4\x9c" ,
				"gx" => "\xc4\x9d" , "gX" => "\xc4\x9d" ,
				"Hx" => "\xc4\xa4" , "HX" => "\xc4\xa4" ,
				"hx" => "\xc4\xa5" , "hX" => "\xc4\xa5" ,
				"Jx" => "\xc4\xb4" , "JX" => "\xc4\xb4" ,
				"jx" => "\xc4\xb5" , "jX" => "\xc4\xb5" ,
				"Sx" => "\xc5\x9c" , "SX" => "\xc5\x9c" ,
				"sx" => "\xc5\x9d" , "sX" => "\xc5\x9d" ,
				"Ux" => "\xc5\xac" , "UX" => "\xc5\xac" ,
				"ux" => "\xc5\xad" , "uX" => "\xc5\xad"
				) ;
			return preg_replace ( '/([cghjsu]x?)((?:xx)*)(?!x)/ei',
			  'strtr( "$1", $xu ) . strtr( "$2", $xu )', $string );
		} else if( strcasecmp( $in, "UTF-8" ) == 0 and strcasecmp( $out, "x" ) == 0 ) {
			$ux = array (
				"x" => "xx" , "X" => "Xx" ,
				"\xc4\x88" => "Cx" , "\xc4\x89" => "cx" ,
				"\xc4\x9c" => "Gx" , "\xc4\x9d" => "gx" ,
				"\xc4\xa4" => "Hx" , "\xc4\xa5" => "hx" ,
				"\xc4\xb4" => "Jx" , "\xc4\xb5" => "jx" ,
				"\xc5\x9c" => "Sx" , "\xc5\x9d" => "sx" ,
				"\xc5\xac" => "Ux" , "\xc5\xad" => "ux"
			) ;
			# Double Xs only if they follow cxapelutaj literoj.
			return preg_replace( '/((?:[cghjsu]|\xc4[\x88\x89\x9c\x9d\xa4\xa5\xb4\xb5]'.
			  '|\xc5[\x9c\x9d\xac\xad])x*)/ei', 'strtr( "$1", $ux )', $string );
		}
		return iconv( $in, $out, $string );
	}

	function checkTitleEncoding( $s ) {
		global $wgInputEncoding;
		
        	# Check for X-system backwards-compatibility URLs
		$ishigh = preg_match( '/[\x80-\xff]/', $s);
		$isutf = preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
			'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s );
		
		if($ishigh and !$isutf) {
			# Assume Latin1
			$s = utf8_encode( $s );
		} else {
			if( preg_match( '/(\xc4[\x88\x89\x9c\x9d\xa4\xa5\xb4\xb5]'.
				'|\xc5[\x9c\x9d\xac\xad])/', $s ) )
			return $s;
		}

		if( preg_match( '/[cghjsu]x/i', $s ) )
			return $this->iconv( "x", "utf-8", $s );
		return $s;
	}

	function setAltEncoding() {
		global $wgEditEncoding, $wgInputEncoding, $wgOutputEncoding;
		$wgInputEncoding = "utf-8";
		$wgOutputEncoding = "x";
		$wgEditEncoding = "";
	}

}

?>
