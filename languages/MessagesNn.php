<?php
/** Norwegian (Nynorsk)
  *
  * @license http://www.gnu.org/copyleft/fdl.html GNU Free Documentation License
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
  *
  * @author Olve Utne
  * @author Guttorm Flatabø
  * @link http://meta.wikimedia.org/w/index.php?title=LanguageNn.php&action=history
  * @link http://nn.wikipedia.org/w/index.php?title=Brukar:Dittaeva/LanguageNn.php&action=history
  *
  * @package MediaWiki
  * @subpackage Language
  */


$quickbarSettings = array(
	'Ingen', 'Venstre', 'Høgre', 'Flytande venstre', 'Flytande høgre'
);

$skinNames = array(
	'standard'        => 'Klassisk',
	'nostalgia'       => 'Nostalgi',
	'cologneblue'     => 'Kölnerblå',
	'myskin'          => 'MiDrakt'
);

$datePreferences = array(
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short dmyt',
	'ISO 8601',
);

$datePreferenceMigrationMap = array(
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short tdmy',
);

$dateFormats = array(
	/*
	'Standard',
	'15. januar 2001 kl. 16:12',
	'15. jan. 2001 kl. 16:12',
	'16:12, 15. januar 2001',
	'16:12, 15. jan. 2001',
	'ISO 8601' => '2001-01-15 16:12:34'
 */
	'dmyt time' => 'H:i',
	'dmyt date' => 'j. F Y',
	'dmyt both' => 'j. F Y "kl." H:i',

	'short dmyt time' => 'H:i',
	'short dmyt date' => 'j. M. Y',
	'short dmyt both' => 'j. M. Y "kl." H:i',

	'tdmy time' => 'H:i',
	'tdmy date' => 'j. F Y',
	'tdmy both' => 'H:i, j. F Y',

	'short tdmy time' => 'H:i',
	'short tdmy date' => 'j. M. Y',
	'short tdmy both' => 'H:i, j. M. Y',
);

$bookstoreList = array(
	'Bibsys'       => 'http://ask.bibsys.no/ask/action/result?kilde=biblio&fid=isbn&lang=nn&term=$1',
	'BokBerit'     => 'http://www.bokberit.no/annet_sted/bocker/$1.html',
	'Bokkilden'    => 'http://www.bokkilden.no/ProductDetails.aspx?ProductId=$1',
	'Haugenbok'    => 'http://www.haugenbok.no/resultat.cfm?st=hurtig&isbn=$1',
	'Akademika'    => 'http://www.akademika.no/sok.php?isbn=$1',
	'Gnist'        => 'http://www.gnist.no/sok.php?isbn=$1',
	'Amazon.co.uk' => 'http://www.amazon.co.uk/exec/obidos/ISBN=$1',
	'Amazon.de'    => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'Amazon.com'   => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
$magicWords = array(
	#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#redirect', '#omdiriger'                                              ),
	'notoc'                  => array( 0,    '__NOTOC__', '__INGAINNHALDSLISTE__', '__INGENINNHOLDSLISTE__'         ),
	'forcetoc'               => array( 0,    '__FORCETOC__', '__ALLTIDINNHALDSLISTE__', '__ALLTIDINNHOLDSLISTE__'   ),
	'toc'                    => array( 0,    '__TOC__', '__INNHALDSLISTE__', '__INNHOLDSLISTE__'                    ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__INGABOLKENDRING__', '__INGABOLKREDIGERING__', '__INGENDELENDRING__'),
	'currentmonth'           => array( 1,    'CURRENTMONTH', 'MÅNADNO', 'MÅNEDNÅ'                                   ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME', 'MÅNADNONAMN', 'MÅNEDNÅNAVN'                       ),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV', 'MÅNADNOKORT', 'MÅNEDNÅKORT'                     ),
	'currentday'             => array( 1,    'CURRENTDAY', 'DAGNO', 'DAGNÅ'                                         ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME', 'DAGNONAMN', 'DAGNÅNAVN'                             ),
	'currentyear'            => array( 1,    'CURRENTYEAR', 'ÅRNO', 'ÅRNÅ'                                          ),
	'currenttime'            => array( 1,    'CURRENTTIME', 'TIDNO', 'TIDNÅ'                                        ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES', 'INNHALDSSIDETAL', 'INNHOLDSSIDETALL'              ),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES', 'FILTAL'                                              ),
	'pagename'               => array( 1,    'PAGENAME', 'SIDENAMN', 'SIDENAVN'                                     ),
	'pagenamee'              => array( 1,    'PAGENAMEE', 'SIDENAMNE', 'SIDENAVNE'                                  ),
	'namespace'              => array( 1,    'NAMESPACE', 'NAMNEROM', 'NAVNEROM'                                    ),
	'subst'                  => array( 0,    'SUBST:', 'LIMINN:'                                                    ),
	'msgnw'                  => array( 0,    'MSGNW:', 'IKWIKMELD:'                                                 ),
	'end'                    => array( 0,    '__END__', '__SLUTT__'                                                 ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb', 'mini', 'miniatyr'                               ),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1', 'mini=$1', 'miniatyr=$1'                   ),
	'img_right'              => array( 1,    'right', 'høgre', 'høyre'                                              ),
	'img_left'               => array( 1,    'left', 'venstre'                                                      ),
	'img_none'               => array( 1,    'none', 'ingen'                                                        ),
	'img_width'              => array( 1,    '$1px', '$1pk'                                                         ),
	'img_center'             => array( 1,    'center', 'centre', 'sentrum'                                          ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame', 'ramme'                                 ),
	'sitename'               => array( 1,    'SITENAME', 'NETTSTADNAMN'                                             ),
	'ns'                     => array( 0,    'NS:', 'NR:'                                                           ),
	'localurl'               => array( 0,    'LOCALURL:', 'LOKALLENKJE:', 'LOKALLENKE:'                             ),
	'localurle'              => array( 0,    'LOCALURLE:', 'LOKALLENKJEE:', 'LOKALLENKEE:'                          ),
	'server'                 => array( 0,    'SERVER', 'TENAR', 'TJENER'                                            ),
	'servername'             => array( 0,    'SERVERNAME', 'TENARNAMN', 'TJENERNAVN'                                ),
	'scriptpath'             => array( 0,    'SCRIPTPATH', 'SKRIPTSTI'                                              ),
	'grammar'                => array( 0,    'GRAMMAR:', 'GRAMMATIKK:'                                              ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__'                                       ),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__'                                     ),
	'currentweek'            => array( 1,    'CURRENTWEEK', 'VEKENRNO', 'UKENRNÅ'                                   ),
	'currentdow'             => array( 1,    'CURRENTDOW', 'VEKEDAGNRNO', 'UKEDAGNRNÅ'                              ),
	'revisionid'             => array( 1,    'REVISIONID', 'VERSJONSID'                                             )
);

$namespaceNames = array(
	NS_MEDIA          => 'Filpeikar',
	NS_SPECIAL        => 'Spesial',
	NS_MAIN           => '',
	NS_TALK           => 'Diskusjon',
	NS_USER           => 'Brukar',
	NS_USER_TALK      => 'Brukardiskusjon',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1-diskusjon',
	NS_IMAGE          => 'Fil',
	NS_IMAGE_TALK     => 'Fildiskusjon',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki-diskusjon',
	NS_TEMPLATE       => 'Mal',
	NS_TEMPLATE_TALK  => 'Maldiskusjon',
	NS_HELP           => 'Hjelp',
	NS_HELP_TALK      => 'Hjelpdiskusjon',
	NS_CATEGORY       => 'Kategori',
	NS_CATEGORY_TALK  => 'Kategoridiskusjon'
);

$separatorTransformTable = array(
	',' => "\xc2\xa0",
	'.' => ','
);
$linkTrail = '/^([æøåa-z]+)(.*)$/sDu';


#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------

$messages = array(
# User preference toggles
'tog-underline'           => 'Strek under lenkjer:',
'tog-highlightbroken'     => 'Vis lenkjer til tomme sider <a href="" class="new">slik</a> (alternativt slik<a href="" class="internal">?</a>)',
'tog-justify'	          => 'Blokkjusterte avsnitt',
'tog-hideminor'           => 'Skjul uviktige endringar på «siste endringar»',
'tog-usenewrc'            => 'Utvida funksjonalitet på «siste endringar» (JavaScript)',
'tog-numberheadings'      => 'Vis nummererte overskrifter',
'tog-showtoolbar'         => 'Vis endringsknappar (JavaScript)',
'tog-editondblclick'      => 'Endre sider med dobbelklikk (JavaScript)',
'tog-editsection'         => 'Endre avsnitt med hjelp av [endre]-lenkje',
'tog-editsectiononrightclick' => 'Endre avsnitt med å høgreklikke på avsnittsoverskrift (JavaScript)',
'tog-showtoc'             => 'Vis innhaldsliste (for sider med meir enn tre bolkar)',
'tog-rememberpassword'    => 'Hugs passordet til neste gong',
'tog-editwidth'           => 'Gjev endringsboksen full breidd',
'tog-watchdefault'        => 'Legg sider eg endrar i overvakingslista mi',
'tog-minordefault'        => 'Merk endringar som «uviktige» som standard',
'tog-previewontop'        => 'Vis førehandsvisinga føre endringsboksen',
'tog-previewonfirst'      => 'Førehandsvis første endring',
'tog-nocache'             => 'Ikkje bruk nettlesaren sitt mellomlager (cache)',
'tog-enotifwatchlistpages' => 'Send e-post når dei overvaka sidene mine blir endra',
'tog-enotifusertalkpages' => 'Send e-post når brukarsida mi blir endra',
'tog-enotifminoredits'    => 'Send e-post òg for uviktige endringar',
'tog-enotifrevealaddr'    => 'Vis e-postadressa mi i endrings-e-post',
'tog-shownumberswatching' => 'Vis kor mange som overvakar sida',
'tog-fancysig'            => 'Signatur utan automatisk lenkje',
'tog-externaleditor'      => 'Eksternt handsamingsprogram som standard',
'tog-externaldiff'        => 'Eksternt skilnadprogram som standard',

'underline-always'        => 'Alltid',
'underline-never'         => 'Aldri',
'underline-default'       => 'Nettlesarstandard',

'skinpreview'             => '(førehandsvis)',

# Dates
'sunday'                  => 'søndag',
'monday'                  => 'måndag',
'tuesday'                 => 'tysdag',
'wednesday'               => 'onsdag',
'thursday'                => 'torsdag',
'friday'                  => 'fredag',
'saturday'                => 'laurdag',
'january'                 => 'januar',
'february'                => 'februar',
'march'                   => 'mars',
'april'                   => 'april',
'may_long'                => 'mai',
'june'                    => 'juni',
'july'                    => 'juli',
'august'                  => 'august',
'september'               => 'september',
'october'                 => 'oktober',
'november'                => 'november',
'december'                => 'desember',
'jan'                     => 'jan',
'feb'                     => 'feb',
'mar'                     => 'mar',
'apr'                     => 'apr',
'may'                     => 'mai',
'jun'                     => 'jun',
'jul'                     => 'jul',
'aug'                     => 'aug',
'sep'                     => 'sep',
'oct'                     => 'okt',
'nov'                     => 'nov',
'dec'                     => 'des',

# Bits of text used by many pages:
'categories'              => 'Kategoriar',
'category_header'         => 'Artiklar i kategorien «$1»',
'subcategories'           => 'Underkategoriar',

'mainpage'		  => 'Hovudside',
'mainpagetext'	          => 'MediaWiki er no installert.',
'mainpagedocfooter'       => 'Sjå [http://meta.wikipedia.org/wiki/MediaWiki_localization dokumentasjon for å tilpasse brukargrensesnittet] og [http://meta.wikipedia.org/wiki/Help:Contents brukarmanualen] for bruk og konfigurasjonshjelp.',

'portal'		  => 'Brukarportal',
'portal-url'		  => 'Project:Brukarportal',
'about'			  => 'Om',
'aboutsite'               => 'Om {{SITENAME}}',
'aboutpage'		  => 'Project:Om',
'article'                 => 'Innhaldsside',
'help'			  => 'Hjelp',
'helppage'		  => 'Help:Innhald',
'bugreports'	          => 'Feilmeldingar',
'bugreportspage'          => 'Project:Feilmeldingar',
'sitesupport'             => 'Gåver',
'sitesupport-url'         => 'Project:Gåver',
'faq'			  => 'OSS',
'faqpage'		  => 'Project:OSS',
'edithelp'		  => 'Hjelp til endring',
'newwindow'		  => '(blir opna i eit nytt vindauge)',
'edithelppage'	          => 'Help:Endring',
'cancel'		  => 'Avbryt',
'qbfind'		  => 'Finn',
'qbbrowse'		  => 'Bla gjennom',
'qbedit'		  => 'Endre',
'qbpageoptions'           => 'Denne sida',
'qbpageinfo'	          => 'Samanheng',
'qbmyoptions'	          => 'Sidene mine',
'qbspecialpages'	  => 'Spesialsider',
'moredotdotdot'	          => 'Meir...',
'mypage'		  => 'Sida mi',
'mytalk'		  => 'Diskusjonssida mi',
'anontalk'		  => 'Diskusjonside for denne IP-adressa',
'navigation'              => 'Navigering',

# Metadata in edit box
'metadata'                => '<b>Metadata</b> (for forklaring, sjå <a href="$1">her</a>)',

'currentevents'           => 'Aktuelt',
'currentevents-url'       => 'Aktuelt',

'disclaimers'             => 'Vilkår',
'disclaimerpage'	      => 'Project:Vilkår',
'errorpagetitle'          => 'Feil',
'returnto'		          => 'Attende til $1.',
'tagline'      	          => 'Frå {{SITENAME}}',
'whatlinkshere'	          => 'Sider med lenkjer hit',
'help'			  => 'Hjelp',
'search'		  => 'Søk',
'searchbutton'	  => 'Søk',
'go'		          => 'Vis',
'history'		  => 'Sidehistorikk',
'history_short'           => 'Historikk',
'updatedmarker'           => 'oppdatert etter mitt siste besøk',
'info_short'	          => 'Informasjon',
'printableversion'        => 'Utskriftsversjon',
'print'                   => 'Skriv ut',
'edit'                    => 'Endre',
'editthispage'	          => 'Endre sida',
'delete'                  => 'Slett',
'deletethispage'          => 'Slett side',
'undelete_short'          => 'Attopprett $1 endringar',
'protect'                 => 'Vern',
'protectthispage'         => 'Vern denne sida',
'unprotect'               => 'fjern vern',
'unprotectthispage'       => 'Fjern vern av denne sida',
'newpage'                 => 'Ny side',
'talkpage'		  => 'Drøft sida',
'specialpage'             => 'Spesialside',
'personaltools'           => 'Personlege verktøy',
'postcomment'             => 'Legg til kommentar',
'articlepage'	          => 'Vis innhaldsside',
'talk'                    => 'Diskusjon',
'views'                   => 'Visningar',
'toolbox'                 => 'Verktøy',
'userpage'                => 'Vis brukarside',
'projectpage'           => 'Vis prosjektside',
'imagepage'               => 'Vis filside',
'viewtalkpage'            => 'Vis diskusjon',
'otherlanguages'          => 'På andre språk',
'redirectedfrom'          => '(Omdirigert frå $1)',
'lastmodifiedat'	          => 'Sist endra $2, $1.',
'viewcount'		  => 'Vist $1 gonger.',
'copyright'	          => 'Innhaldet er utgjeve under $1.',
'protectedpage'           => 'Verna side',
'badaccess'               => 'Tilgangsfeil',
'versionrequired'         => 'MediaWiki versjon $1 trengst',
'versionrequiredtext'     => 'For å bruke denne sida trengst MediaWiki versjon $1. Sjå [[{{ns:special}}:Version]]',
'nbytes'		  => '$1 byte',
'ok'			  => 'OK',
'retrievedfrom'           => 'Henta frå «$1»',
'newmessageslink'         => 'nye meldingar',
'editsection'             => 'endre',
'editold'             => 'endre',
'toc'                     => 'Innhaldsliste',
'showtoc'                 => 'vis',
'hidetoc'                 => 'gøym',
'thisisdeleted'           => 'Sjå eller attopprett $1?',
'viewdeleted'             => 'Sjå historikk for $1?',
'restorelink'             => '$1 sletta versjonar',
'feedlinks'               => 'Mating:',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'              => 'Innhaldsside',
'nstab-user'              => 'Brukarside',
'nstab-media'             => 'Filside',
'nstab-special'           => 'Spesial',
'nstab-project'                => 'Prosjektside',
'nstab-image'             => 'Fil',
'nstab-mediawiki'         => 'Systemmelding',
'nstab-template'          => 'Mal',
'nstab-help'              => 'Hjelp',
'nstab-category'          => 'Kategori',

# Main script and global functions
#
'nosuchaction'	          => 'Funksjonen finst ikkje',
'nosuchactiontext'        => 'Wikiprogramvaren kjenner ikkje att funksjonen som er spesifisert i nettadressa',
'nosuchspecialpage'       => 'Ei slik spesialside finst ikkje',
'nospecialpagetext'       => 'Du har bede om ei spesialside som ikkje finst, liste over spesialsider er [[Special:Specialpages|her]].',

# General errors
#
'error'			  => 'Feil',
'databaseerror'           => 'Databasefeil',
'dberrortext'	          => 'Det oppstod ein syntaksfeil i databaseførespurnaden. Dette kan tyde på ein feil i programvaren. Den sist prøvde førespurnaden var: <blockquote><tt>$1</tt></blockquote> frå funksjonen «<tt>$2</tt>». MySQL returnerte feilen «<tt>$3: $4</tt>».',
'dberrortextcl'           => 'Det oppstod ein syntaksfeil i databaseførespurnaden. Den sist prøvde førespurnaden var: «$1» frå funksjonen "$2".
MySQL returnerte feilen «$3: $4».',
'noconnect'		  => 'Wikien har tekniske problem og kunne ikkje kople til databasen.<br />$1',
'nodb'			  => 'Kunne ikkje velja databasen $1',
'cachederror'	          => 'Det følgjande er ein lagra kopi av den ønska sida, og er ikkje nødvendigvis oppdatert.',
'laggedslavemode'          => 'Åtvaring: Det er mogleg at sida ikkje er heilt oppdatert.',
'readonly'		          => 'Databasen er skriveverna',
'enterlockreason'         => 'Skriv ein grunn for vernet, inkludert eit overslag for kva tid det vil bli oppheva',
'readonlytext'	          => 'Databasen er akkurat no skriveverna, truleg for rutinemessig vedlikehald. Administratoren som verna han har gjeve denne forklaringa:<p>$1',
'missingarticle'          => 'Databasen fann ikkje teksten til ei side med namnet «$1» som han skulle ha funne.

Dette skjer oftast fordi du følgde ei lenkje til ei oppføring som har vorte sletta.
Sletta oppføringar kan vanlegvis attopprettast.

Dersom dette ikkje er tilfellet kan du ha funne ein feil i programvaren. Gje melding om dette til ein administrator, med adressa åt sida.',
'readonly_lag'            => 'Databasen er mellombels skriveverna for at databasetenarane skal kunna synkronisere seg mot kvarandre',
'internalerror'           => 'Intern feil',
'filecopyerror'           => 'Kunne ikkje kopiere fila frå «$1» til «$2».',
'filerenameerror'         => 'Kunne ikkje døype om fila frå «$1» til «$2».',
'filedeleteerror'         => 'Kunne ikkje slette fila «$1».',
'filenotfound'	          => 'Kunne ikkje finne fila «$1».',
'unexpected'	          => 'Uventa verdi: «$1»=«$2».',
'formerror'		  => 'Feil: Kunne ikkje sende skjema',
'badarticleerror'         => 'Handlinga kan ikkje utførast på denne sida.',
'cannotdelete'	          => 'Kunne ikkje slette fila. (Ho kan vera sletta av andre.)',
'badtitle'	 	  => 'Feil i tittelen',
'badtitletext'	          => 'Den ønska tittelen var ulovleg, tom eller feil lenka frå ei anna wiki.',
'perfdisabled'            => 'Beklagar! Denne funksjonen er mellombels deaktivert for å spara tenarkapasitet.',
'perfdisabledsub'         => 'Her er ein lagra kopi frå $1:',
'perfcached'              => 'Det følgjande er frå mellomlageret åt tenaren og er ikkje nødvendigvis oppdatert.',
'wrong_wfQuery_params'    => 'Feil parameter gjevne til wfQuery()<br />Funksjon: $1<br />Førespurnad: $2',
'viewsource'              => 'Vis kjeldetekst',
'protectedtext'           => 'Denne sida er verna for endring. Det kan vera fleire grunnar til dette, sjå [[{{ns:4}}:Verna side]].

Du kan sjå og kopiere kjeldeteksten til denne sida:',
'sqlhidden'               => '(SQL-førespurnaden er gøymd)',

# Login and logout pages
#
'logouttitle'	          => 'Logg ut',
'logouttext'	          => 'Du er no utlogga. Avhengig av innstillingane på tenaren kan nettlesaren no brukast anonymt på {{SITENAME}};
du kan logge inn att med same kontoen eller ein annan brukar kan logge inn. Ver merksam på at nokre sider kan fortsetje å bli viste som om du er innlogga inntil du tømmer mellomlageret til nettlesaren din.',
'welcomecreation'         => '== Hjarteleg velkommen til {{SITENAME}}, [[user:$1|$1]]! ==

Brukarkontoen din har vorte oppretta. Det er tilrådd at du skriv litt om deg sjølv på [[user:$1|brukarsida di]] og ser gjennom [[special:preferences|brukarinnstillingane dine]].',
'loginpagetitle'          => 'Logg inn',
'yourname'		          => 'Brukarnamn',
'yourpassword'	          => 'Passord',
'yourpasswordagain'       => 'Skriv opp att passordet',
'remembermypassword'      => 'Hugs passordet.',
'yourdomainname'          => 'Domenet ditt',
'externaldberror'         => 'Det var anten ein ekstern databasefeil i tilgjengekontrollen, eller du har ikkje løyve til å oppdatere den eksterne kontoen din.',
'loginproblem'	          => '<b>Du vart ikkje innlogga.</b><br />Prøv om att!',
'alreadyloggedin'         => '<strong>Brukar $1, du er allereie innlogga!</strong><br />',
'login'			  => 'Logg inn',
'loginprompt'             => 'Nettlesaren din må godta informasjonskapslar for at du skal kunna logge inn.',
'userlogin'		  => 'Lag brukarkonto / logg inn',
'logout'		  => 'Logg ut',
'userlogout'	          => 'Logg ut',
'notloggedin'	          => 'Ikkje innlogga',
'createaccount'	          => 'Opprett ny konto',
'createaccountmail'	  => 'over e-post',
'badretype'		  => 'Passorda du skreiv inn er ikkje like.',
'userexists'	          => 'Brukarnamnet er allereie i bruk. Vel eit nytt.',
'youremail'		  => 'E-postadresse*',
'yourrealname'		  => 'Namn*',
'yourlanguage'	          => 'Språk for brukargrensesnittet',
'yourvariant'             => 'Språkvariant',
'yournick'		          => 'Kallenamn (for signaturar)',
'email'                   => 'E-post',
'prefs-help-email-enotif' => 'Denne adressa blir også brukt til å sende deg endringsmeldingar dersom du har valt å ta den funksjonen i bruk.',
'prefs-help-realname'     => '* Namn (valfritt): Om du vel å fylle ut dette feltet, vil informasjonen bli brukt til å godskrive arbeid du har gjort.',
'loginerror'	          => 'Innloggingsfeil',
'prefs-help-email'        => '* E-post (valfritt): Gjer det mogleg for andre brukarar å ta kontakt med deg utan at du offentleggjer adressa.',
'nocookiesnew'	          => 'Brukarkontoen vart oppretta, men du er ikkje innlogga. {{SITENAME}} bruker informasjonskapslar for å logge inn brukarar,
nettlesaren din er innstilt for ikkje å godta desse. Etter at du har endra innstillingane slik at nettlesaren godtek informasjonskapslar, kan du logge inn med det nye brukarnamnet og passordet ditt.',
'nocookieslogin'	  => '{{SITENAME}} bruker informasjonskapslar for å logge inn brukarar, nettlesaren din er innstilt for ikkje å godta desse.
Etter at du har endra innstillingane slik at nettlesaren godtek informasjonskapslar kan du prøve å logge inn på nytt.',
'noname'		  => 'Du har ikkje oppgjeve gyldig brukarnamn.',
'loginsuccesstitle'       => 'Du er no innlogga',
'loginsuccess'	          => 'Du er no innlogga som «$1».',
'nosuchuser'	          => 'Det finst ikkje nokon brukar med brukarnamnet «$1». Sjekk at du har skrive rett eller bruk skjemaet under til å opprette ein ny konto.',
'nosuchusershort'         => 'Det finst ikkje nokon brukar med brukarnamnet «$1». Sjekk at du har skrive rett.',
'wrongpassword'	          => 'Du har oppgjeve eit ugyldig passord. Prøv om att.',
'mailmypassword'          => 'Send meg nytt passord',
'passwordremindertitle'   => 'Nytt passord til {{SITENAME}}',
'passwordremindertext'    => 'Nokon (truleg du, frå IP-adressa $1) bad oss sende deg eit nytt passord til {{SITENAME}}. Passordet for brukaren «$2» er no «$3». Du bør logge inn og endre passordet så snart som råd.',
'noemail'	         	  => 'Det er ikkje registrert noka e-postadresse åt brukaren «$1».',
'passwordsent'	          => 'Eit nytt passord er sendt åt e-postadressa registrert på brukaren «$1».',
'eauthentsent'            => 'Ein stadfestings-e-post er sendt til den oppgjevne e-postadressa. For at adressa skal kunna brukast, må du følgje instruksjonane i e-posten for å stadfeste at ho faktisk tilhøyrer deg.',
'mailerror'               => 'Ein feil oppstod ved sending av e-post: $1',
'acct_creation_throttle_hit' => 'Beklagar, du har allereie laga $1 brukarkontoar. Du har ikkje høve til å laga fleire.',
'emailauthenticated'      => 'E-postadressa di vart stadfest $1.',
'emailnotauthenticated'   => 'E-postadressa di er <strong>enno ikkje stadfest</strong>. Dei følgjande funksjonane kan ikkje bruke ho.',
'noemailprefs'            => '<strong>Du har ikkje oppgjeve noko e-postadresse</strong>, dei følgjande funksjonane vil ikkje verke.',
'emailconfirmlink'        => 'Stadfest e-post-adressa di',
'invalidemailaddress'     => 'E-postadressa kan ikkje brukast sidan ho er feil oppbygd. Skriv ei rett oppbygd adresse eller tøm feltet.',

# Edit page toolbar
'bold_sample'             => 'Halvfeit skrift',
'bold_tip'                => 'Halvfeit skrift',
'italic_sample'           => 'Kursivskrift',
'italic_tip'              => 'Kursivskrift',
'link_sample'             => 'Lenkjetittel',
'link_tip'                => 'Intern lenkje',
'extlink_sample'          => 'http://www.eksempel.no lenkjetittel',
'extlink_tip'             => 'Ekstern lenkje (hugs http:// prefiks)',
'headline_sample'         => 'Overskriftstekst',
'headline_tip'            => '2. nivå-overskrift',
'math_sample'             => 'Skriv formel her',
'math_tip'                => 'Matematisk formel (LaTeX)',
'nowiki_sample'           => 'Skriv uformatert tekst her',
'nowiki_tip'              => 'Sjå bort frå wikiformatering',
'image_sample'            => 'Eksempel.jpg',
'image_tip'               => 'Bilete eller lenkje til filomtale',
'media_sample'            => 'Eksempel.ogg',
'media_tip'               => 'Filpeikar',
'sig_tip'                 => 'Signaturen din med tidsstempel',
'hr_tip'                  => 'Vassrett line',

# Edit pages
#
'summary'		  => 'Samandrag',
'subject'		  => 'Emne/overskrift',
'minoredit'		  => 'Uviktig endring',
'watchthis'		  => 'Overvak side',
'savearticle'	          => 'Lagre',
'preview'		  => 'Førehandsvising',
'showpreview'	          => 'Førehandsvis',
'showdiff'                => 'Vis skilnad',
'blockedtitle'	          => 'Brukaren er blokkert',
'blockedtext'	          => 'Brukarnamnet ditt eller IP-adressa di er blokkert frå endring, av $1. Denne grunnen vart gjeven:<br />\'\'$2\'\'<p>Du kan kontakte $1 eller ein annan [[Project:Administratorar|administrator]] for å diskutere blokkeringa.

Ver merksam på at du ikkje kan bruke «send e-post åt brukar»-funksjonen så lenge du ikkje har ei gyldig e-postadresse registrert i [[Special:Preferences|innstillingane dine]].

IP-adressa di er $3. Legg henne ved eventuelle førespurnader.',
'whitelistedittitle'      => 'Du lyt logge inn for å gjera endringar',
'whitelistedittext'       => 'Du lyt [[{{ns:-1}}:Userlogin|logge inn]] for å endre sider.',
'whitelistreadtitle'      => 'Du lyt logge inn for å lesa',
'whitelistreadtext'       => 'Du lyt [[{{ns:-1}}:Userlogin|logge inn]] for å lesa sider.',
'whitelistacctitle'       => 'Du har ikkje løyve til å laga brukarkonto',
'whitelistacctext'        => 'For å laga brukarkontoar på denne wikien lyt du [[{{ns:-1}}:Userlogin|logge inn]] og ha rett type tilgang',
'loginreqtitle'	          => 'Innlogging trengst',
'loginreqlink'            => 'logge inn',
'loginreqpagetext'        => 'Du lyt $1 for å lesa andre sider.',
'accmailtitle'            => 'Passord er sendt.',
'accmailtext'             => 'Passordet for «$1» er vorte sendt til $2.',
'newarticle'	          => '(Ny)',
'newarticletext'          => '\'\'\'{{SITENAME}} har ikkje noka side med namnet {{PAGENAME}} enno.\'\'\'
* For å laga ei slik side kan du skrive i boksen under og klikke på «Lagre». Endringane vil vera synlege med det same.
* Om du er ny her er det tilrådd å sjå på [[Project:Retningsliner|retningsliner]] og [[Help:Innhald|hjelp]] først.
* Om du lagrar ei testside, vil du ikkje kunne slette ho sjølv. Ver difor venleg og bruk [[Project:Sandkasse|sandkassa]] til å eksperimentere.
* Dersom du ikkje ønskjer å endre sida, kan du utan risiko klikke på \'\'\'attende\'\'\'-knappen i nettlesaren din.',
'anontalkpagetext'        => '---- \'\'Dette er ei diskusjonsside for ein anonym brukar som ikkje har logga inn på eigen brukarkonto. Vi er difor nøydde til å bruke den numeriske IP-adressa knytt til internettoppkoplinga åt brukaren. Same IP-adressa kan vera knytt til fleire brukarar. Om du er ein anonym brukar og meiner at du har fått irrelevante kommentarar på ei slik side, [[{{ns:-1}}:Userlogin|logg inn]] slik at vi unngår framtidige forvekslingar med andre anonyme brukarar.\'\'',
'noarticletext'           => '\'\'\'Sida «{{PAGENAME}}» finst ikkje på {{SITENAME}} enno.\'\'\'
* Klikk på \'\'\'[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} endre]\'\'\' for å opprette sida.',
'clearyourcache'          => '\'\'\'Merk:\'\'\' Etter lagring vil det kanskje vera naudsynt at nettlesaren omgår mellomlageret sitt for at endringane skal tre i kraft. \'\'\'Mozilla og Firefox:\'\'\' trykk \'\'Ctrl-Shift-R\'\', \'\'\'Internet Explorer:\'\'\' \'\'Ctrl-F5\'\', \'\'\'Safari:\'\'\' \'\'Cmd-Shift-R\'\', \'\'\'Konqueror:\'\'\' \'\'F5\'\'.',
'usercssjsyoucanpreview'  => '<strong>Tip:</strong> Bruk «Førehandsvis»-knappen for å teste den nye CSS- eller JS-koden din føre du lagrar.',
'usercsspreview'          => '\'\'\'Hugs at du berre testar ditt eige CSS, det har ikkje vorte lagra enno!\'\'\'',
'userjspreview'           => '\'\'\'Hugs at du berre testar ditt eige JavaScript, det har ikkje vorte lagra enno!!\'\'\'',
'updated'		  => '(Oppdatert)',
'note'			  => '<strong>Merk:</strong>',
'previewnote'	          => 'Hugs at dette berre er ei førehandsvising og at teksten ikkje er lagra!',
'previewconflict'         => 'Dette er ei førehandsvising av teksten i endringsboksen over, slik han vil sjå ut om du lagrar han',
'editing'		  => 'Endrar $1',
'editinguser'		  => 'Endrar $1',
'editingsection'          => 'Endrar $1 (bolk)',
'editingcomment'          => 'Endrar $1 (kommentar)',
'editconflict'	          => 'Endringskonflikt: $1',
'explainconflict'         => 'Nokon annan har endra teksten sidan du byrja å skrive. Den øvste boksen inneheld den noverande teksten. Skilnaden mellom den lagra versjonen og din endra versjon er viste under. Versjonen som du har endra er i den nedste boksen. Du lyt flette endringane dine saman med den noverande teksten. <strong>Berre</strong> teksten i den øvste tekstboksen vil bli lagra når du klikkar på «Lagre».<br />',
'yourtext'		  => 'Teksten din',
'storedversion'           => 'Den lagra versjonen',
'nonunicodebrowser'       => '<strong>ÅTVARING: Nettlesaren din støttar ikkje «Unicode». For å omgå problemet blir teikn utanfor ASCII-standarden viste som heksadesimale kodar. Det vil vera ein fordel om du byter nettlesar. Sjå [[hjelp:unicode]] for meir informasjon.</strong><br />',
'editingold'	          => '<strong>ÅTVARING: Du endrar ein gammal versjon av denne sida. Om du lagrar ho, vil alle endringar gjorde etter denne versjonen bli overskrivne.</strong> (Men dei kan hentast fram att frå historikken.)<br />',
'yourdiff'		  => 'Skilnad',
'copyrightwarning'        => 'Merk deg at alle bidrag til {{SITENAME}} er å rekne som utgjevne under $2 (sjå $1 for detaljar). Om du ikkje vil ha teksten endra og kopiert under desse vilkåra, kan du ikkje leggje han her.<br />
Teksten må du ha skrive sjølv, eller kopiert frå ein ressurs som er kompatibel med vilkåra eller ikkje verna av opphavsrett.

<strong>LEGG ALDRI INN MATERIALE SOM ANDRE HAR OPPHAVSRETT TIL UTAN LØYVE FRÅ DEI!</strong>',
'copyrightwarning2'       => 'Merk deg at alle bidrag til {{SITENAME}} kan bli endra, omskrive og fjerna av andre bidragsytarar. Om du ikkje vil ha teksten endra under desse vilkåra, kan du ikkje leggje han her.<br />
Teksten må du ha skrive sjølv eller ha kopiert frå ein ressurs som er kompatibel med vilkåra eller ikkje verna av opphavsrett (sjå $1 for detaljar).

<strong>LEGG ALDRI INN MATERIALE SOM ANDRE HAR OPPHAVSRETT TIL UTAN LØYVE FRÅ DEI!</strong>',
'longpagewarning'         => '<strong>ÅTVARING: Denne sida er $1 KB lang; nokre nettlesarar kan ha problem med å handsama endringar av sider som nærmar seg eller er lengre enn 32 KB. Du bør vurdere å dele opp sida i mindre bolkar.</strong><br />',
'readonlywarning'         => '<strong>ÅTVARING: Databasen er skriveverna på grunn av vedlikehald, difor kan du ikkje lagre endringane dine akkurat no. Det kan vera lurt å  kopiere teksten din åt ei tekstfil, så du kan lagre han her seinare.</strong><br />',
'protectedpagewarning'    => '<strong>ÅTVARING: Denne sida er verna, slik at berre administratorar kan endre ho.</strong><br />',
'templatesused'	          => 'Malar brukte på denne sida:',

# History pages
#
'revhistory'	          => 'Historikk',
'nohistory'		  => 'Det finst ikkje nokon historikk for denne sida.',
'revnotfound'	          => 'Fann ikkje versjonen',
'revnotfoundtext'         => 'Den gamle versjonen av sida du spurde etter finst ikkje. Sjekk nettadressa du brukte for å komma deg åt denne sida.',
'loadhist'		  => 'Lastar historikk',
'currentrev'	          => 'Noverande versjon',
'revisionasof'	          => 'Versjonen frå $1',
'previousrevision'        => '←Eldre versjon',
'nextrevision'            => 'Nyare versjon→',
'currentrevisionlink'     => 'Vis noverande versjon',
'cur'			  => 'no',
'next'			  => 'neste',
'last'			  => 'førre',
'orig'			  => 'orig',
'histlegend'	          => 'Merk av for dei versjonane du vil samanlikne og trykk [Enter] eller klikk på knappen nedst på sida.<br />Forklaring: (no) = skilnad frå den noverande versjonen, (førre) = skilnad frå den førre versjonen, <b>u</b> = uviktig endring',
'deletedrev'              => '[sletta]',
'histfirst'               => 'Første',
'histlast'                => 'Siste',

# Diffs
#
'difference'	          => '(Skilnad mellom versjonar)',
'loadingrev'	          => 'lastar versjon for å sjå skilnad',
'lineno'		  => 'Line $1:',
'editcurrent'	          => 'Endre den noverande versjonen av denne sida',
'selectnewerversionfordiff' => 'Vel ein nyare versjon for samanlikning',
'selectolderversionfordiff' => 'Vel ein eldre versjon for samanlikning',
'compareselectedversions' => 'Samanlikn valde versjonar',

# Search results
#
'searchresults'           => 'Søkjeresultat',
'searchresulttext'        => 'For meir info om søkjefunksjonen i {{SITENAME}}, sjå [[Help:Søk|Hjelp]].',
'searchsubtitle'	          => 'Du søkte etter «[[:$1]]»', // plain text
'searchsubtitleinvalid'	          => 'Du søkte etter «$1»', // plain text
'badquery'		  => 'Feil utforma førespurnad',
'badquerytext'	          => 'Vi kunne ikkje svara på denne førespurnaden &mdash; Truleg fordi du prøvde å søkje etter eit ord med færre enn tre bokstavar, noko som ikkje er mogleg enno. Det kan òg vera du skreiv feil... Prøv om att.',
'matchtotals'	          => 'Førespurnaden «$1» gav treff på $2 sidetitlar og på teksten på $3 sider.',
'noexactmatch'               => '* \'\'\'{{SITENAME}} har ikkje noka side med [[:$1|dette namnet]].\'\'\'
* <big>\'\'\'Du kan [[:$1|opprette ho no]]\'\'\'</big>.<br />
(Men du bør søkje etter andre namnevariasjonar først, slik at du ikkje lagar ei side som allereie finst under eit anna namn!)',
'titlematches'	          => 'Sidetitlar med treff på førespurnaden',
'notitlematches'          => 'Ingen sidetitlar hadde treff på førespurnaden',
'textmatches'	          => 'Sider med treff på førespurnaden',
'notextmatches'	          => 'Ingen sider hadde treff på førespurnaden',
'prevn'			  => 'førre $1',
'nextn'			  => 'neste $1',
'viewprevnext'	          => 'Vis ($1) ($2) ($3).',
'showingresults'          => 'Nedanfor er opp til <strong>$1</strong> resultat som byrjar med nummer <strong>$2</strong> viste.',
'showingresultsnum'       => 'Nedanfor er <strong>$3</strong> resultat som byrjar med nummer <strong>$2</strong> viste.',
'nonefound'               => '\'\'\'Merk\'\'\': søk utan resultat kan komma av at du leitar etter alminnelege engelske ord som ikkje blir indekserte, eller det kan komma av at du har gjeve meir enn eitt søkjeord (berre sider som inneheld alle søkjeorda vil bli funne).',
'powersearch'             => 'Søk',
'powersearchtext'         => 'Søk i namnerom:<br />$1<br />$2<br />List omdirigeringar &nbsp; Søk etter: $3 $9',
'searchdisabled'          => 'Søkjefunksjonen på {{SITENAME}} er deaktivert på grunn av for stort press på tenarane akkurat no. I mellomtida kan du søkje gjennom Google eller Yahoo! Ver merksam på at registra deira kan vera utdaterte.',
'blanknamespace'        => '(Hovud)',

# Preferences page
#
'preferences'	        => 'Innstillingar',
'prefsnologin'          => 'Ikkje innlogga',
'prefsnologintext'	    => 'Du lyt vera [[Special:Userlogin|innlogga]] for å endre brukarinnstillingane dine.',
'prefsreset'	        => 'Innstillingane er tilbakestilte til siste lagra versjon.',
'qbsettings'	        => 'Snøggmeny',
'changepassword'        => 'Skift passord',
'skin'			=> 'Drakt',
'math'			=> 'Matematiske formlar',
'dateformat'            => 'Datoformat',
'datedefault' => 'Standard',
'math_failure'		    => 'Klarte ikkje å tolke formelen',
'math_unknown_error'	=> 'ukjend feil',
'math_unknown_function'	=> 'ukjend funksjon',
'math_lexing_error'	    => 'lexerfeil',
'math_syntax_error'	    => 'syntaksfeil',
'math_image_error'	    => 'PNG-konverteringa var mislukka; sjekk at latex, dvips, gs, og convert er rett installerte',
'math_bad_tmpdir'	    => 'Kan ikkje skrive til eller laga mellombels mattemappe',
'math_bad_output'	    => 'Kan ikkje skrive til eller laga mattemappe',
'math_notexvc'	        => 'Manglar texvc-program; sjå math/README for konfigurasjon.',
'prefs-personal'        => 'Brukaropplysningar',
'prefs-rc'              => 'Siste endringar og spirer',
'prefs-misc'            => 'Andre',
'saveprefs'		        => 'Lagre',
'resetprefs'	        => 'Rull attende',
'oldpassword'	        => 'Gammalt passord',
'newpassword'	        => 'Nytt passord',
'retypenew'		        => 'Nytt passord om att',
'textboxsize'	        => 'Endring',
'rows'			        => 'Rekkjer',
'columns'		        => 'Kolonnar',
'searchresultshead'     => 'Søk',
'resultsperpage'        => 'Resultat per side',
'contextlines'	        => 'Liner per resultat',
'contextchars'	        => 'Teikn per line i resultatet',
'stubthreshold'         => 'Grense (byte) for vising av spirer',
'recentchangescount'    => 'Tal titlar på «siste endringar»',
'savedprefs'	        => 'Brukarinnstillingane er lagra.',
'timezonelegend'        => 'Tidssone',
'timezonetext'	        => 'Tal timar lokal tid skil seg frå tenaren si tid.',
'localtime'	            => 'Lokaltid',
'timezoneoffset'        => 'Skilnad',
'servertime'	        => 'Tenartid',
'guesstimezone'         => 'Hent tidssone frå nettlesaren',
'defaultns'		=> 'Søk som standard i desse namneromma:',
'default'		=> 'standard',
'files'                 => 'Filer',

# User levels special page
#

# switching pan

'userrights-lookup-user' => 'Administrer brukargrupper',
'userrights-user-editname' => 'Skriv inn brukarnamn:',
'editusergroup'         => 'Endre brukargrupper',

# user groups editing
#
'userrights-editusergroup' => 'Endre brukargrupper',
'saveusergroups'        => 'Lagre brukargrupper',
'userrights-groupsmember' => 'Medlem av:',
'userrights-groupsavailable' => 'Tilgjengelege grupper:',
'userrights-groupshelp' => 'Vel grupper du vil at brukaren skal fjernast frå eller leggjast til. Grupper som ikkje er valde vil ikkje bli endra. Du kan velja vekk ei gruppe med [CTRL + venstreklikk]',

# Recent changes
#
'changes'               => 'endringar',
'recentchanges'         => 'Siste endringar',
'recentchangestext'     => 'På denne sida ser du dei sist endra sidene i {{SITENAME}}.',
'rcnote'		=> 'Nedanfor er dei siste <strong>$1</strong> endringane gjort dei siste <strong>$2</strong> dagane.',
'rcnotefrom'	        => 'Nedanfor er endringane frå <b>$2</b> inntil <b>$1</b> viste.',
'rclistfrom'	        => 'Vis nye endringar frå $1',
'rclinks'		=> 'Vis siste $1 endringar dei siste $2 dagane<br />$3',
'diff'			=> 'skil',
'hist'			=> 'hist',
'hide'			=> 'gøym',
'show'			=> 'vis',
'minoreditletter'       => 'u',
'newpageletter'         => 'n',
'sectionlink'           => '→',
'number_of_watching_users_pageview'     => '[$1 brukar(ar) overvakar]',

# Upload
#
'upload'		=> 'Last opp fil',
'uploadbtn'		=> 'Last opp fil',
'reupload'		=> 'Nytt forsøk',
'reuploaddesc'	        => 'Attende til opplastingsskjemaet.',
'uploadnologin'         => 'Ikkje innlogga',
'uploadnologintext'	    => 'Du lyt vera [[Special:Userlogin|innlogga]] for å kunna laste opp filer.',
'upload_directory_read_only' => 'Opplastingsmappa ($1) er skriveverna.',
'uploaderror'	        => 'Feil under opplasting av fil',
'uploadtext'	        => 'Dette er sida til å laste opp filer. Nyleg opplasta filer finn du på [[Special:Imagelist|filsida]]. Opplastingar og slettingar [[Special:Log|blir loggført]].

* For å bruke eit bilete på ei side, skriv inn ei lenkje av dette slaget: <tt><nowiki>[[{{ns:6}}:Eksempelbilete.jpg]]</nowiki></tt> eller <tt><nowiki>[[{{ns:6}}:Eksempelbilete.png|bilettekst]]</nowiki></tt>, eller <tt><nowiki>[[{{ns:-2}}:Eksempelfil.ogg]]</nowiki></tt> for lydar og andre filer. For å leggje inn eit bilete som miniatyr, skriv <tt><nowiki>[[{{ns:6}}:Eksempelbilete.jpg|mini|Bilettekst]]</nowiki></tt>. Sjå [[Help:Biletsyntaks|biletesyntaks-hjelp]] for meir informasjon.
* Om du lastar opp ei fil med same namn som ei eksisterande fil vil du bli beden om å stadfeste, og den eksisterande fila vil ikkje bli sletta.

Sjå [[Help:Laste opp fil|hjelp for filopplasting]] for meir informasjon om korleis dette skjemaet verkar og korleis ein bruker filer på wikisider.

For å laste opp ei fil bruker du «Bla gjennom...» eller «Browse...»-knappen som opnar ein standarddialog for val av fil. Når du vel ei fil, vil namnet på denne fila dukke opp i tekstfeltet ved sida av knappen. Skriv inn \'\'\'all\'\'\' nødvendig informasjon i \'\'Samandrag\'\'-feltet, kryss av at du ikkje bryt nokon sin opphavsrett, og klikk til slutt på \'\'Last opp fil\'\'.',
'uploadlog'             => 'opplastingslogg',
'uploadlogpage'         => 'Opplastingslogg',
'uploadlogpagetext'     => 'Dette er ei liste over filer som nyleg er lasta opp.',
'filename'		=> 'Filnamn',
'filedesc'		        => 'Skildring',
'fileuploadsummary'     => 'Skildring:',
'filestatus'            => 'Opphavsrettsstatus',
'filesource'            => 'Kjelde',
'copyrightpage'         => '{{ns:4}}:Opphavsrett',
'copyrightpagename'     => '{{SITENAME}} opphavsrett',
'uploadedfiles'	        => 'Filer som er opplasta',
'minlength'		=> 'Namnet på fila må ha minst tre teikn.',
'illegalfilename'	=> 'Filnamnet «$1» inneheld teikn som ikkje er tillatne i sidetitlar. Skift namn på fila og prøv på nytt.',
'badfilename'	        => 'Namnet på fila har vorte endra til «$1».',
'badfiletype'	        => 'Filformatet «.$1» er ikkje tillete.',
'largefile'		        => 'Det er frårådd å bruke filer som er større enn $1 byte, denne fila er $2 byte.',
'emptyfile'		=> 'Det ser ut til at fila du lasta opp er tom. Dette kan komma av ein skrivefeil i filnamnet. Sjekk og tenk etter om du verkeleg vil laste opp fila.',
'fileexists'		=> 'Ei fil med dette namnet finst allereie, sjekk $1 om du ikkje er sikker på om du vil endre namnet.',
'successfulupload'      => 'Opplastinga er ferdig',
'fileuploaded'	        => 'Fila «$1» er opplasta. Følg lenkja «$2» åt sida med skildring og fyll ut informasjon om fila &mdash; slik som kvar ho kom frå, kva tid ho vart laga og av kven, og andre ting du veit om fila.',
'uploadwarning'         => 'Opplastingsåtvaring',
'savefile'		        => 'Lagre fil',
'uploadedimage'         => 'Lasta opp «[[$1]]»',
'uploaddisabled'        => 'Beklagar, funksjonen for opplasting er deaktivert på denne nettenaren.',
'uploadscripted'        => 'Fila inneheld HTML- eller skriptkode som feilaktig kan bli tolka og køyrd av nettlesarar.',
'uploadcorrupt'         => 'Fila er øydelagd eller har feil etternamn. Sjekk fila og prøv på nytt.',
'uploadvirus'           => 'Fila innheld virus! Detaljar: $1',
'sourcefilename'        => 'Filsti',
'destfilename'          => 'Målfilnamn',

# Image list
#
'imagelist'		        => 'Filliste',
'imagelisttext'	        => 'Her er ei liste med $1 filer sorterte $2.',
'getimagelist'	        => 'hentar filliste',
'ilsubmit'		=> 'Søk',
'showlast'		=> 'Vis dei siste $1 filene sorterte $2.',
'byname'		=> 'etter namn',
'bydate'		=> 'etter dato',
'bysize'		=> 'etter storleik',
'imgdelete'		=> 'slett',
'imgdesc'		=> 'skildring',
'imglegend'		=> 'Forklaring: (skildring) = vis/endre filskildring.',
'imghistory'	        => 'Filhistorikk',
'revertimg'		=> 'rulltb',
'deleteimg'		=> 'slett',
'deleteimgcompletely'	=> 'Slett alle versjonar av fila',
'imghistlegend'         => 'Forklaring: (no) = dette er den noverande versjonen av fila, (slett) = slett denne versjonen, (rulltb) = tilbake til denne versjonen.<br /><i>Klikk på ein dato for å sjå fila som vart opplasta då</i>.',
'imagelinks'	        => 'Fillenkjer',
'linkstoimage'	        => 'Dei følgjande sidene har lenkjer til denne fila:',
'nolinkstoimage'        => 'Det finst ikkje noka side med lenkje til denne fila.',
'sharedupload'          => 'Denne fila er ei delt opplasting og kan brukast av andre prosjekt.',
'shareduploadwiki'      => 'Sjå $1 for meir informasjon.',
'shareduploadwiki-linktext' => 'filskildringssida',
'noimage'               => 'Det finst ikkje noka fil med dette namnet, men du kan $1',
'noimage-linktext'      => 'laste ho opp',
'uploadnewversion-linktext' => 'Last opp ny versjon av denne fila',

# Statistics
#
'statistics'	        => 'Statistikk',
'sitestats'		=> '{{SITENAME}}-statistikk',
'userstats' 	        => 'Brukarstatistikk',
'sitestatstext'         => 'Det er i alt \'\'\'$1\'\'\' sider i databasen. Dette inkluderer diskusjonssider, sider om {{SITENAME}}, småsider,
omdirigeringssider, og andre som truleg ikkje kan kallast innhaldssider. Om ein ser bort frå desse sidene, er det \'\'\'$2\'\'\' sider som truleg er innhaldssider.

Alle sidene er vortne viste \'\'\'$3\'\'\' gonger og endra \'\'\'$4\'\'\' gonger sidan programvaren vart installert. Det vil seie at kvar side gjennomsnittleg har vorte endra \'\'\'$5\'\'\' gonger, og vist \'\'\'$6\'\'\' gonger per endring.',
'userstatstext'         => '{{SITENAME}} har \'\'\'$1\'\'\' registrerte brukarar. \'\'\'$2\'\'\' (eller \'\'\'$4%\'\'\') av desse er administratorar (sjå $3).',

# Maintenance Page
#
'disambiguations'	=> 'Fleirtydingssider',
'disambiguationspage'	=> 'Template:Fleirtyding',
'disambiguationstext'	=> 'Dei følgjande artiklane har lenkjer til <i>artiklar med fleirtydige titlar</i>. Dei burde heller lenkje til ein ikkje-fleirtydig  tittel i staden.<br />Ein artikkeltittel blir handsama som fleirtydig om han har lenkjer frå $1.<br />Lenkjer frå andre namnerom er <i>ikkje</i> opprekna her.',
'doubleredirects'	=> 'Doble omdirigeringar',
'doubleredirectstext'	=> 'Kvar line inneheld lenkjer til den første og den andre omdirigeringa, og den første lina frå den andre omdirigeringsteksten. Det gjev som regel den «rette» målartikkelen, som den første omdirigeringa skulle ha peikt på.',
'brokenredirects'	    => 'Blindvegsomdirigeringar',
'brokenredirectstext'	=> 'Dei følgjande omdirigeringane viser til ei side som ikkje finst.',

# Miscellaneous special pages
#
'lonelypages'	        => 'Foreldrelause sider',
'uncategorizedpages'	=> 'Ikkje kategoriserte sider',
'uncategorizedcategories' => 'Ikkje kategoriserte kategoriar',
'unusedcategories'      => 'Ubrukte kategoriar',
'unusedimages'	        => 'Ubrukte filer',
'popularpages'	        => 'Populære sider',
'nviews'		=> '$1 visingar',
'wantedpages'	        => 'Etterspurde sider',
'mostlinked'            => 'Sider mest lenkja til',
'nlinks'		=> '$1 lenkjer',
'allpages'		=> 'Alle sider',
'prefixindex'           => 'Prefiksindeks',
'randompage'	        => 'Tilfeldig side',
'shortpages'	        => 'Korte sider',
'longpages'		=> 'Lange sider',
'deadendpages'          => 'Blindvegsider',
'listusers'		=> 'Brukarliste',
'specialpages'	        => 'Spesialsider',
'spheading'		=> 'Spesialsider for alle brukarar',
'restrictedpheading'    => 'Spesialsider med avgrensa tilgang',

'recentchangeslinked'   => 'Relaterte endringar',
'rclsub'		=> '(til sider med lenkje frå «$1»)',
'newpages'		=> 'Nye sider',
'ancientpages'		=> 'Eldste sider',
'intl'		        => 'Språklenkjer',
'move'                  => 'Flytt',
'movethispage'	        => 'Flytt side',
'unusedimagestext'      => '<p>Merk deg at andre internettsider kan ha lenkjer til filer som er lista her. Dei kan difor vera i aktiv bruk.</p>',
'unusedcategoriestext'  => 'Dei følgjande kategorisidene er oppretta, sjølv om ingen artikkel eller kategori brukar dei.',

'booksources'	        => 'Bokkjelder',
'categoriespagetext'    => 'Wikien har følgjande kategoriar.',
'data'                  => 'Data',
'userrights'            => 'Administrering av brukartilgang',
'groups'                => 'Brukargrupper',
'booksourcetext'        => 'Her er ei liste over lenkjer til internettsider som låner ut og/eller sel nye og/eller brukte bøker, og som kanskje har meir informasjon om bøker du leitar etter. {{SITENAME}} er ikkje nødvendigvis assosiert med nokon av desse sidene, og lista er <b>ikkje</b> å rekne som ei spesifikk tilråding om å bruke dei.',
'isbn'	                => 'ISBN',
'alphaindexline'        => '$1 til $2',
'version'		=> 'Versjon',
'log'                   => 'Loggar',
'alllogstext'	        => 'Kombinert vising av opplastings-, slette-, verne-, blokkerings- og administrator-loggar. Du kan avgrense visinga ved å velja loggtype, brukarnamn, og/eller sidnamn.',

# Special:Allpages
'nextpage'              => 'Neste side ($1)',
'allpagesfrom'          => 'Vis sider frå:',
'allarticles'           => 'Alle sider',
'allinnamespace'        => 'Alle sider ($1 namnerom)',
'allnotinnamespace'     => 'Alle sider (ikkje i $1-namnerommet)',
'allpagesprev'          => 'Førre',
'allpagesnext'          => 'Neste',
'allpagessubmit'        => 'Vis',

# Email this user
#
'mailnologin'	        => 'Inga avsendaradresse',
'mailnologintext'       => 'Du lyt vera [[Special:Userlogin|innlogga]] og ha ei gyldig e-postadresse sett i [[Special:Preferences|brukarinnstillingane]] for å sende e-post åt andre brukarar.',
'emailuser'		=> 'Send e-post åt denne brukaren',
'emailpage'		=> 'Send e-post åt brukar',
'emailpagetext'	        => 'Om denne brukaren har gjeve ei gyldig e-postadresse i brukarinnstillingane sine, vil dette skjemaet sende ei enkel melding. E-postadressa di frå brukarinnstillingane dine vil vera synleg i «Frå»-feltet i denne e-posten, slik at mottakaren kan svara deg.',
'usermailererror'       => 'E-post systemet gav feilmelding:',
'defemailsubject'       => '{{SITENAME}} e-post',
'noemailtitle'	        => 'Inga e-postadresse',
'noemailtext'	        => 'Denne brukaren har ikkje oppgjeve ei gyldig e-postadresse, eller har valt å ikkje opne for e-post frå andre brukarar.',
'emailfrom'		=> 'Frå',
'emailto'		=> 'Åt',
'emailsubject'	        => 'Emne',
'emailmessage'	        => 'Melding',
'emailsend'		=> 'Send',
'emailsent'		=> 'E-posten er sendt',
'emailsenttext'         => 'E-posten er sendt.',

# Watchlist
#
'watchlist'		=> 'Overvakingsliste',
'nowatchlist'	        => 'Du har ikkje noko i overvakingslista di.',
'watchnologin'	        => 'Ikkje innlogga',
'watchnologintext'	    => 'Du lyt vera [[Special:Userlogin|innlogga]] for å kunna endre overvakingslista.',
'addedwatch'	        => 'Lagt til overvakingslista',
'addedwatchtext'        => 'Sida «$1» er lagt til [[Special:Watchlist|overvakingslista]] di. Framtidige endringar av denne sida og den tilhøyrande diskusjonssida vil bli oppførde her, og sida vil vera \'\'\'utheva\'\'\' på «[[Special:Recentchanges|siste endringar]]» for å gjera deg merksam på henne.

Om du seinere vil fjerne sida frå overvakingslista, klikk på «Fjern overvaking» på den aktuelle sida.',
'removedwatch'	        => 'Fjerna frå overvakingslista',
'removedwatchtext'      => 'Sida «$1» er fjerna frå overvakingslista.',
'watch'                 => 'Overvak',
'watchthispage'	        => 'Overvak denne sida',
'unwatch'               => 'Fjern overvaking',
'unwatchthispage'       => 'Fjern overvaking',
'notanarticle'	        => 'Ikkje innhaldsside',
'watchnochange'         => 'Ingen av sidene i overvakingslista er endra i den valde perioden.',
'watchdetails'          => 'Du har $1 sider i overvakingslista di (diskusjonssider ikkje medrekna); du kan [[Special:Watchlist/edit|vise og endre den fullstendige lista]].',
'wlheader-enotif'       => '* Funksjonen for endringsmeldingar per e-post er på.',
'wlheader-showupdated'  => '* Sider som har vorte endra sidan du sist såg på dei er \'\'\'utheva\'\'\'',
'watchmethod-recent'    => 'sjekkar siste endringar for dei overvaka sidene',
'watchmethod-list'      => 'sjekkar om dei overvaka sidene er vortne endra i det siste',
'removechecked'         => 'Fjern dei valde sidene frå overvakingslista',
'watchlistcontains'     => 'Overvakingslista inneheld $1 sider.',
'watcheditlist'         => 'Dette er ei alfabetisk liste over sider du overvakar. For å fjerne sider frå lista må du velja dei sidene du vil fjerne og klikke på «Fjern overvaking»-knappen nedst på sida.',
'removingchecked'       => 'Fjernar dei valde sidene frå overvakingslista ...',
'couldntremove'         => 'Kunne ikkje fjerne «$1»...',
'iteminvalidname'       => 'Problem med «$1», ugyldig namn...',
'wlnote'                => 'Nedanfor er dei siste $1 endringane dei siste <b>$2</b> timane.',
'wlshowlast' 		    => 'Vis siste $1 timar $2 dagar $3',
'wlsaved'		        => 'Dette er ein mellomlagra versjon av overvakingslista di.',
'wlhideshowown'         => '$1 eigne endringar.',

'enotif_mailer'         => '{{SITENAME}}-endringsmeldingssendar',
'enotif_reset'          => 'Merk alle sider som vitja',
'enotif_newpagetext'    => 'Dette er ei ny side.',
'changed'               => 'endra',
'created'               => 'oppretta',
'enotif_subject'        => '{{SITENAME}}-sida $PAGETITLE har vorte $CHANGEDORCREATED av $PAGEEDITOR',
'enotif_lastvisited'    => 'Sjå $1 for alle endringane sidan siste vitjing.',
'enotif_body'           => 'Hei $WATCHINGUSERNAME,

{{SITENAME}}-sida $PAGETITLE har vorte $CHANGEDORCREATED $PAGEEDITDATE av $PAGEEDITOR, sjå $PAGETITLE_URL for den gjeldande versjonen.

$NEWPAGE

Bidragytaren sitt endringssamandrag: $PAGESUMMARY $PAGEMINOREDIT

Du kan kontakte bidragsytaren gjennom:
e-post: $PAGEEDITOR_EMAIL , eller
wiki: $PAGEEDITOR_WIKI

Du får ikkje fleire endringsmeldingar om denne sida før du har vitja henne på nytt. Du kan også tilbakestille endringsmeldingsstatus for alle sidene på overvakingslista di.

             Helsing din overvakande {{SITENAME}}-endringsmeldingssystemven

--
For å endre innstillingane for overvakingslista di, gå til
{{SERVER}}{{localurl:Special:Watchlist/edit}}

For hjelp og meir informasjon:
{{SERVER}}{{localurl:Hjelp:Overvaking}}',

# Delete/protect/revert
#
'deletepage'	        => 'Slett side',
'confirm'		=> 'Stadfest',
'excontent'             => 'innhaldet var: «$1»',
'excontentauthor'       => 'innhaldet var: «$1» (og den einaste bidragsytaren var «[[{{ns:2}}:$2|$2]]»)',
'exbeforeblank'         => 'innhaldet før sida vart tømd var: «$1»',
'exblank'               => 'sida var tom',
'confirmdelete'         => 'Stadfest sletting',
'deletesub'		=> '(Slettar «$1»)',
'historywarning'        => 'Åtvaring: Sida du held på å slette har ein historikk:',
'confirmdeletetext'     => 'Du held på å varig slette ei side eller eit bilete saman med heile den tilhøyrande historikken frå databasen. Stadfest at du verkeleg vil gjera dette, at du skjønner konsekvensane, og at du gjer dette i tråd med [[Project:Retningsliner|retningslinene]].',
'actioncomplete'        => 'Ferdig',
'deletedtext'	        => '«$1» er sletta. Sjå $2 for eit oversyn over dei siste slettingane.',
'deletedarticle'        => 'sletta «[[$1]]»',
'dellogpage'	        => 'Slettelogg',
'dellogpagetext'        => 'Her er ei liste over dei siste slettingane.',
'deletionlog'	        => 'slettelogg',
'reverted'		=> 'Attenderulla til ein tidlegare versjon',
'deletecomment'	        => 'Grunn for sletting',
'imagereverted'         => 'Attenderulling av tidlegare versjon ferdig.',
'rollback'		=> 'Rull attende endringar',
'rollback_short'        => 'Rull attende',
'rollbacklink'	        => 'rull attende',
'rollbackfailed'        => 'Kunne ikkje rulle attende',
'cantrollback'	        => 'Kan ikkje rulle attende fordi den siste brukaren er den einaste forfattaren.',
'alreadyrolled'	        => 'Kan ikkje rulle attende den siste endringa av [[$1]] gjort av [[{{ns:2}}:$2|$2]] ([[{{ns:3}}:$2|brukardiskusjon]]) fordi nokon andre allereie har endra sida att eller fjerna endringa.

Den siste endringa vart gjort av [[{{ns:2}}:$3|$3]] ([[{{ns:3}}:$3|brukardiskusjon]]).',
# only shown if there is an edit comment
'editcomment'           => 'Samandraget for endringa var: "<i>$1</i>".',
'revertpage'	        => 'Attenderulla endring gjort av [[{{ns:2}}:$2|$2]] til tidlegare versjon endra av [[{{ns:2}}:$1|$1]]',
'sessionfailure'        => 'Det ser ut til å vera eit problem med innloggingsøkta di. Handlinga er vorten avbroten for å vera føre var mot kidnapping av økta. Bruk attendeknappen i nettlesaren din og prøv om att.',
'protectlogpage'        => 'Vernelogg',
'protectlogtext'        => 'Dette er ei liste over sider som er vortne verna eller har fått fjerna vern. Sjå [[{{ns:4}}:Verna side]] for meir info.',
'protectedarticle'      => 'verna «[[$1]]»',
'unprotectedarticle'    => 'fjerna vern av «[[$1]]»',
'protectsub'            => '(Vernar «$1»)',
'confirmprotecttext'    => 'Er du sikker på at du vil verne denne sida?',
'confirmprotect'        => 'Stadfest vern',
'protectmoveonly'       => 'Berre vern mot flytting',
'protectcomment'        => 'Grunn til verning',
'unprotectsub'          => '(Fjernar vern av «$1»)',
'confirmunprotecttext'  => 'Er du sikker på at du vil fjerne vernet av denne sida?',
'confirmunprotect'      => 'Stadfest fjerning av vern',
'unprotectcomment'      => 'Grunn til fjerning av vern',

# Undelete
'undelete'              => 'Sletta sider',
'undeletepage'          => 'Sletta sider',
'viewdeletedpage'       => 'Sjå sletta sider',
'undeletepagetext'      => 'Dei følgjande sidene er sletta, men dei finst enno i arkivet og kan attopprettast. Arkivet blir periodevis sletta.',
'undeletearticle'       => 'Attopprett sletta side',
'undeleterevisions'     => '$1 versjonar arkiverte',
'undeletehistory'       => 'Om du attopprettar sida, vil alle versjonane i historikken også bli attoppretta. Dersom ei ny side med same namnet er oppretta sidan den gamle sida vart sletta, vil dei attoppretta versjonane dukke opp i historikken, og den nyaste versjonen vil bli verande som han er.',
'undeletehistorynoadmin' => 'Ein eller fleire versjonar av denne sida har blitt sletta. Grunnlaget for sletting er oppgjeve under, saman med informasjon om kven som sletta og når versjonane vart sletta. Innhaldet i dei sletta versjonane er berre tilgjengeleg for [[special:listusers/sysop|administratorar]].',
'undeleterevision'      => 'Sletta versjon frå $1',
'undeletebtn'           => 'Attopprett!',
'undeletedarticle'      => 'attoppretta «[[$1]]»',
'undeletedrevisions'    => '$1 versjonar attoppretta',

 # Namespace form on various pages
'namespace'             => 'Namnerom:',
'invert'                => 'Vreng val',

# Contributions
#
'contributions'	        => 'Brukarbidrag',
'mycontris'             => 'Eigne bidrag',
'contribsub'	        => 'For $1',
'nocontribs'	        => 'Det vart ikkje funne nokon endringar gjorde av denne brukaren.',
'ucnote'	        => 'Her er dei siste <b>$1</b> endringane frå denne brukaren dei siste <b>$2</b> dagane.',
'uclinks'	        => 'Vis dei siste $1 endringane; vis dei siste $2 dagane.',
'uctop'		        => ' (øvst)' ,
'newbies'               => 'ferskingar',

# What links here
#
'whatlinkshere'	        => 'Lenkjer hit',
'notargettitle'         => 'Inkje mål',
'notargettext'	        => 'Du har ikkje spesifisert noka målside eller nokon brukar å bruke denne funksjonen på.',
'linklistsub'	        => '(Liste over lenkjer)',
'linkshere'	        => 'Desse sidene har lenkjer hit:',
'nolinkshere'	        => 'Inga side har lenkje hit.',
'isredirect'	        => 'omdirigeringsside',

# Block/unblock IP
#
'blockip'		=> 'Blokker brukar',
'blockiptext'	        => 'Bruk skjemaet nedanfor for å blokkere skrivetilgangen frå ei spesifikk IP-adresse eller brukarnamn. Dette bør berre gjerast for å hindre hærverk, og i samsvar med [[Project:Retningsliner|retningslinene]]. Skriv grunngjeving nedanfor (t.d. med sitat frå sider som er vortne utsette for hærverk). Opphørstid for blokkeringa skriv ein med GNU standardformat, som er skildra i [http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html tar manualen] (engelsk), t.d. «1 hour», «2 days», «next Wednesday», «1 January 2017». Alternativt kan ei blokkering vera «indefinite» (ikkje fastsett) eller «infinite» (uendeleg).

For informasjon om korleis ein kan blokkere seriar av IP-adresser, sjå [http://meta.wikimedia.org/wiki/Range_blocks] (engelsk). For å oppheve blokkering, sjå  [[Special:Ipblocklist|blokkeringslista]].',
'ipaddress'		        => 'IP-adresse',
'ipadressorusername'    => 'IP-adresse eller brukarnamn',
'ipbreason'		=> 'Grunngjeving',
'ipbsubmit'		=> 'Blokker denne brukaren',
'ipbother'              => 'Anna tid',
'ipboptions'            => '2 timar:2 hours,1 dag:1 day,3 dagar:3 days,1 veke:1 week,2 veker:2 weeks,1 månad:1 month,3 månader:3 months,6 månader:6 months,1 år:1 year,endelaus:infinite',
'ipbotheroption'        => 'anna tid',
'badipaddress'	        => 'IP-adressa var ugyldig eller brukarblokkering er deaktivert på tenaren.',
'blockipsuccesssub'     => 'Blokkering utført',
'blockipsuccesstext'    => '«[[User:$1|$1]]» er blokkert.<br />Sjå [[Special:Ipblocklist|blokkeringslista]] for alle blokkeringar.',
'unblockip'		=> 'Opphev blokkering',
'unblockiptext'	        => 'Bruk skjemaet nedanfor for å oppheve blokkeringa av ein tidlegare blokkert brukar.',
'ipusubmit'		=> 'Opphev blokkering',
'ipblocklist'	        => 'Blokkerte IP-adresser og brukarnamn',
'blocklistline'	        => '$1, $2 blokkerte $3 ($4)',
'infiniteblock'         => 'uendeleg opphørstid',
'expiringblock'         => '$1 opphørstid',
'ipblocklistempty'      => 'Blokkeringslista er tom.',
'blocklink'		=> 'blokker',
'unblocklink'	        => 'opphev blokkering',
'contribslink'	        => 'bidrag',
'autoblocker'	        => 'Automatisk blokkert fordi du deler IP-adresse med [[{{ns:2}}:$1|$1]]. Grunngjeving gjeve for blokkeringa av $1 var: «$2».',
'blocklogpage'	        => 'Blokkeringslogg',
'blocklogentry'	        => 'Blokkerte «[[$1]]» med opphørstid $2',
'blocklogtext'	        => 'Dette er ein logg over blokkeringar og oppheving av blokkeringar gjorde av [[{{ns:4}}:Administratorar|administratorar]].
IP-adresser som blir automatisk blokkerte er ikkje lista her. Sjå [[{{ns:-1}}:Ipblocklist|blokkeringslista]] for alle aktive blokkeringar.',
'unblocklogentry'	=> 'oppheva blokkering av «$1»',
'range_block_disabled'	=> 'Funksjonen for blokkering av IP-adresse-seriar er deaktivert på tenaren.',
'ipb_expiry_invalid'	=> 'Ugyldig opphørstid.',
'ip_range_invalid'	=> 'Ugyldig IP-adresseserie.',
'proxyblocker'	        => 'Proxy-blokkerar',
'proxyblockreason'	=> 'Du er blokkert frå å endre fordi IP-adressa di tilhøyrer ein open mellomtenar (proxy). Du bør kontakte internettleverandøren din eller kundesørvis og gje dei beskjed, ettersom dette er eit alvorleg sikkerheitsproblem.',
'proxyblocksuccess'	=> 'Utført.',
'sorbs'         => 'SORBS DNSBL',
'sorbsreason'   => 'IP-adressa di er lista som ein open mellomtenar i [http://www.sorbs.net SORBS DNSBL].

Dersom du er feilaktig blokkert kan http://www.sorbs.net/faq/retest.shtml vera til hjelp for å få IP-adressa di fjerna frå svartelista.',
'sorbs_create_account_reason' => 'IP-adressa di er lista som ein open mellomtenar i [http://www.sorbs.net SORBS] DNSBL, og difor får du ikkje registrert deg.',

# Developer tools
#
'lockdb'		=> 'Skrivevern (lock) database',
'unlockdb'		=> 'Opphev skrivevern (unlock) av databasen',
'lockdbtext'	        => 'Å skriveverne databasen vil gjere det umogleg for alle brukarar å endre sider, brukarinnstillingar, overvakingslister og andre ting som krev endringar i databasen. Stadfest at du ønskjer å gjera dette, og at du vil låse opp databasen att når vedlikehaldet er ferdig.',
'unlockdbtext'	        => 'Å oppheva skrivevernet på databasen fører til at alle brukarar kan endre sider, brukarinnstillingar, overvakingslister og andre ting som krev endringar i databasen att. Stadfest at du ønskjer å gjera dette.',
'lockconfirm'	        => 'Ja, eg vil verkeleg skriveverne databasen.',
'unlockconfirm'	        => 'Ja, eg vil verkeleg oppheva skrivevernet på databasen.',
'lockbtn'		=> 'Skrivevern databasen',
'unlockbtn'		=> 'Opphev skrivevern på databasen',
'locknoconfirm'         => 'Du har ikkje stadfest handlinga.',
'lockdbsuccesssub'      => 'Databasen er no skriveverna',
'unlockdbsuccesssub'    => 'Srivevernet på databasen er no oppheva',
'lockdbsuccesstext'     => 'Databasen er no skriveverna. <br />Hugs å oppheve skrivevernet når du er ferdig med vedlikehaldet.',
'unlockdbsuccesstext'   => 'Skrivevernet er oppheva.',

# Make sysop
'makesysoptitle'	=> 'Gjer brukar om til administrator',
'makesysoptext'		=> 'Dette skjemaet kan brukast av byråkratar til å gjera vanlege brukarar om til administratorar. Skriv inn namnet på brukaren i tekstboksen og trykk på knappen for å gjere brukaren om til administrator',
'makesysopname'		=> 'Brukarnamn:',
'makesysopsubmit'	=> 'Gjer brukaren om til administrator',
'makesysopok'		=> '<b>Brukaren «$1» er no administrator</b>',
'makesysopfail'		=> '<b>Brukaren «$1» kunne ikkje gjerast om til administrator. (Skreiv du brukarnamnet rett?)</b>',
'setbureaucratflag'     => 'Gje byråkrat-tilgang',
'rightslogtext'         => 'Dette er ein logg over endringar av brukartilgang.',
'rights'		=> 'Tilgang:',
'set_user_rights'	=> 'Set brukartilgang',
'user_rights_set'	=> '<b>Brukartilgang for «$1» er oppdatert</b>',
'set_rights_fail'	=> '<b>Brukartilgang for «$1» kunne ikkje setjast. (Skreiv du brukarnamnet rett?)</b>',
'makesysop'             => 'Gje brukar administratortilgang',
'already_sysop'         => 'Denne brukaren har allereie administratortilgang',
'already_bureaucrat'    => 'Denne brukaren har allereie byråkrat-tilgang',

# Move page
#
'movepage'		=> 'Flytt side',
'movepagetext'	        => 'Ved å bruke skjemaet nedanfor kan du få omdøypt ei side og flytt heile historikken til det nye namnet. Den gamle tittelen vil bli ei omdirigeringsside til den nye tittelen. Lenkjer til den gamle tittelen vil ikkje bli endra. Pass på å sjekke for doble eller dårlege omdirigeringar. Du er ansvarleg for at alle lenkjene stadig peiker dit det er meininga at dei skal peike.

Merk at sida \'\'\'ikkje\'\'\' kan flyttast dersom det allereie finst ei side med den nye tittelen. Du kan likevel flytte ei side attende dit ho vart flytt frå dersom du gjer ein feil, så lenge den sida du flytter attende til ikkje er vorten endra sidan flyttinga.

<b>ÅTVARING!</b> Dette kan vera ei drastisk og uventa endring for ei populær side; ver sikker på at du skjønner konsekvensane av dette før du fortset.',
'movepagetalktext'      => 'Den tilhøyrande diskusjonssida, om ho finst, vil automatisk bli flytt med sida \'\'\'med mindre:\'\'\'
*Du flytter sida til eit anna namnerom,
*Ei diskusjonsside som ikkje er tom allereie finst under det nye namnet, eller
*Du fjernar merkinga i boksen nedanfor.

I desse falla lyt du flytte eller flette saman sida manuelt. Om det ikkje er mogleg for deg å gjera dette kan du kontakte ein [[{{ns:4}}:Administratorar|administrator]], men <b>ikkje</b> bruk klipp-og-lim metoden sidan dette ikkje tek vare på endringshistorikken.',
'movearticle'	        => 'Flytt side',
'movenologin'	        => 'Ikkje innlogga',
'movenologintext'       => 'Du lyt vera registrert brukar og vera [[Special:Userlogin|innlogga]] for å flytte ei side.',
'newtitle'		=> 'Til ny tittel',
'movepagebtn'	        => 'Flytt side',
'pagemovedsub'	        => 'Flyttinga er gjennomført',
'pagemovedtext'         => 'Sida «[[$1]]» er flytt til «[[$2]]».',
'articleexists'         => 'Ei side med det namnet finst allereie, eller det namnet du har valt er ikkje gyldig. Vel eit anna namn.',
'talkexists'	        => '\'\'\'Innhaldssida vart flytt, men diskusjonssida som høyrer til kunne ikkje flyttast fordi det allereie finst ei side med den nye tittelen. Du lyt flette dei saman manuelt. Dersom det ikkje er mogleg for deg å gjera dette kan du kontakte ein <a href="{{localurl:Project:Administratorar}}">administrator</a> &#8212; men <b>ikkje</b> bruk klipp-og-lim metoden sidan dette ikkje tek vare på endringshistorikken.\'\'\'',
'movedto'		=> 'er flytt til',
'movetalk'		=> 'Flytt diskusjonssida òg om ho finst.',
'talkpagemoved'         => 'Diskusjonssida som høyrer til vart òg flytt.',
'talkpagenotmoved'      => 'Diskusjonssida som høyrer til vart <strong>ikkje</strong> flytt.',
'1movedto2'		        => '«[[$1]]» flytt til «[[$2]]»',
'1movedto2_redir'       => '«[[$1]]» flytt over omdirigering til «[[$2]]»',
'movelogpage'           => 'Flyttelogg',
'movelogpagetext'       => 'Under er ei liste over sider som er flytte.',
'movereason'            => 'Grunngjeving',
'revertmove'            => 'attende',
'delete_and_move'       => 'Slett og flytt',
'delete_and_move_text'  => '== Sletting påkrevd ==

Målsida «[[$1]]» finst allereie. Vil du slette ho for å gje rom for flytting?',
'delete_and_move_reason' => 'Sletta for å gje rom for flytting',
'selfmove'              => 'Kjelde- og måltitlane er like; kan ikkje flytte sida over seg sjølv.',
'immobile_namespace'    => 'Måltittelen høyrer til eit namnerom som gjer at sida ikkje kan flyttast dit.',

# Export
'export'		=> 'Eksporter sider',
'exporttext'	        => 'Du kan eksportere teksten og endringshistorikken til ei side eller ein serie sider, pakka inn i litt XML. I framtida kan det hende at dette att kan bli importert til ei anna wiki som brukar MediaWiki-programvaren, men det er det ikkje støtte for dette i denne versjonen av MediaWiki.

For å eksportere sider, skriv tittelen i tekstboksen nedanfor, ein tittel per line, og vel om du vil ha med alle versjonane eller berre siste versjon.

Dersom du berre vil ha den siste versjonen kan du òg bruke ei lenkje, t.d. [[{{ns:Special}}:Export/MediaWiki]] for [[MediaWiki]] sida.',
'exportcuronly'	        => 'Berre eksporter siste versjonen, ikkje med heile historikken.',

# Namespace 8 related
'allmessages'	        => 'Systemmeldingar',
'allmessagesname' => 'Namn',
'allmessagesdefault' => 'Standardtekst',
'allmessagescurrent' => 'Noverande tekst',
'allmessagestext'	=> 'Dette er ei liste over systemmeldingar i MediaWiki-namnerommet.',
'allmessagesnotsupportedUI' => 'Det gjeldande språket for grensesnittet <b>$1</b>, støttar ikkje {{ns:-1}}:Allmessages.',
'allmessagesnotsupportedDB' => '{{ns:-1}}:Allmessages er ikkje støtta fordi "wgUseDatabaseMessages" ikkje er aktivert på tenaren.',

# Thumbnails
'thumbnail-more'	=> 'Forstørr',
'missingimage'		=> '<b>Bilete manglar</b><br /><i>$1</i>',
'filemissing'           => 'Fil manglar',

# Special:Import
'import'	            => 'Importer sider',
'importinterwiki'       => 'Transwikiimport',
'importtext'	        => 'Du må først eksportere sida du vil importere til ei fil som du lagrar på maskina di, deretter kan du laste ho inn her.
For å eksportere bruker du [[{{ns:-1}}:Export|eksportsida]] på kjeldewikien; hugs at kjelda òg må bruke MediaWiki-programvaren.',
'importfailed'	        => 'Importeringa var mislukka: $1',
'importnotext'	        => 'Tom eller ingen tekst',
'importsuccess'	        => 'Importeringa er ferdig!',
'importhistoryconflict' => 'Det kan vera at det er konflikt i historikken (kanskje sida vart importert før)',
'importnosources'       => 'Ingen kjelder for transwikiimport er oppgjevne og funksjonen for opplasting av historikk er deaktivert.',

# Keyboard access keys for power users
'accesskey-search'      => 'f',
'accesskey-minoredit'   => 'i',
'accesskey-save'        => 's',
'accesskey-preview'     => 'p',
'accesskey-diff'        => 'd',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search'        => 'Søk i {{SITENAME}} [alt-f]',
'tooltip-minoredit'     => 'Merk dette som ei uviktig endring [alt-i]',
'tooltip-save'          => 'Lagre endringane dine [alt-s]',
'tooltip-preview'       => 'Førehandsvis endringane dine, bruk denne funksjonen før du lagrar! [alt-p]',
'tooltip-diff'          => 'Vis skilnaden mellom din versjon og lagra versjon, utan å lagre. [alt-d]',
'tooltip-compareselectedversions' => 'Sjå endringane mellom dei valde versjonane av denne sida. [alt-v]',
'tooltip-watch'         => 'Legg denne sida til i overvakingslista di [alt-w]',

# Metadata
'nodublincore'          => 'Funksjonen for Dublin Core RDF metadata er deaktivert på denne tenaren.',
'nocreativecommons'     => 'Funksjonen for Creative Commons RDF er deaktivert på denne tenaren.',
'notacceptable'         => 'Wikitenaren kan ikkje gje data i noko format som programmet ditt kan lesa.',

# Attribution
'anonymous'             => 'Anonym(e) brukar(ar) av {{SITENAME}}',
'siteuser'              => '{{SITENAME}} brukar $1',
'lastmodifiedatby'        => 'Denne sida vart sist endra $2, $1 av $3.',
'and'                   => 'og',
'othercontribs'         => 'Basert på arbeid av $1.',
'others'                => 'andre',
'siteusers'             => '{{SITENAME}} brukar(ar) $1',
'creditspage'           => 'Sidegodskriving',
'nocredits'             => 'Det finst ikkje ikkje nokon godskrivingsinformasjon for denne sida.',

# Spam protection
'spamprotectiontitle'   => 'Filter for vern mot reklame',
'spamprotectiontext'    => 'Sida du prøvde å lagre vart blokkert av filteret for vern mot reklame (spam). Dette skjedde truleg på grunn av ei ekstern lenkje.',
'spamprotectionmatch'   => 'Den følgjande teksten utløyste reklamefilteret: $1',
'subcategorycount'      => 'Det er $1 underkategoriar av denne kategorien.',
'categoryarticlecount'  => 'Det er $1 innhaldssider i denne kategorien.',
'listingcontinuesabbrev' => ' vidare',

# Info page
'infosubtitle'          => 'Informasjon om side',
'numedits'              => 'Tal endringar (innhaldsside): $1',
'numtalkedits'          => 'Tal endringar (diskusjonsside): $1',
'numwatchers'           => 'Tal brukarar som overvakar: $1',
'numauthors'            => 'Tal ulike bidragsytarar (innhaldsside): $1',
'numtalkauthors'        => 'Tal ulike bidragsytarar (diskusjonsside): $1',

# Math options
'mw_math_png'           => 'Vis alltid som PNG',
'mw_math_simple'        => 'HTML om svært enkel, elles PNG',
'mw_math_html'          => 'HTML om mogleg, elles PNG',
'mw_math_source'        => 'Behald som TeX (for tekst-nettlesarar)',
'mw_math_modern'        => 'Tilrådd for moderne nettlesarar',
'mw_math_mathml'        => 'MathML dersom mogleg (eksperimentell)',

# Patrolling
'markaspatrolleddiff'   => 'Merk som patruljert',
'markaspatrolledtext'   => 'Merk denne innhaldssida som patruljert',
'markedaspatrolled'     => 'Merk som patruljert',
'markedaspatrolledtext' => 'Den valde versjonen er vorten merkt som patruljert.',
'rcpatroldisabled'      => 'Siste-endringar-patruljering er deaktivert',
'rcpatroldisabledtext'  => 'Patruljeringsfunksjonen er deaktivert.',

# stylesheets
'Monobook.js'           => '/*
<pre>
*/
/* verktøytips og snøggtastar */
var ta = new Object();
ta[\'pt-userpage\']             = new Array(\'.\',\'Brukarsida mi\');
ta[\'pt-anonuserpage\']         = new Array(\'.\',\'Brukarsida for ip-adressa du endrar under\');
ta[\'pt-mytalk\']               = new Array(\'n\',\'Diskusjonssida mi\');
ta[\'pt-anontalk\']             = new Array(\'n\',\'Diskusjon om endringar gjorde av denne ip-adressa\');
ta[\'pt-preferences\']          = new Array(\'\',\'Innstillingane mine\');
ta[\'pt-watchlist\']            = new Array(\'l\',\'Liste over sidene du overvakar.\');
ta[\'pt-mycontris\']            = new Array(\'y\',\'Liste over bidraga mine\');
ta[\'pt-login\']                = new Array(\'o\',\'Det er ikkje obligatorisk å logga inn, men medfører mange fordelar.\');
ta[\'pt-anonlogin\']            = new Array(\'o\',\'Det er ikkje obligatorisk å logga inn, men medfører mange fordelar.\');
ta[\'pt-logout\']               = new Array(\'o\',\'Logg ut\');
ta[\'ca-talk\']                 = new Array(\'t\',\'Diskusjon om innhaldssida\');
ta[\'ca-edit\']                 = new Array(\'e\',\'Du kan endre denne sida. Bruk førehandsvisings-knappen før du lagrar.\');
ta[\'ca-addsection\']           = new Array(\'+\',\'Legg til ein bolk på denne diskusjonssida.\');
ta[\'ca-viewsource\']           = new Array(\'e\',\'Denne sida er verna, men du kan sjå kjeldeteksten.\');
ta[\'ca-history\']              = new Array(\'h\',\'Eldre versjonar av denne sida.\');
ta[\'ca-protect\']              = new Array(\'=\',\'Vern denne sida\');
ta[\'ca-delete\']               = new Array(\'d\',\'Slett denne sida\');
ta[\'ca-undelete\']             = new Array(\'d\',\'Attopprett denne sida\');
ta[\'ca-move\']                 = new Array(\'m\',\'Flytt denne sida\');
ta[\'ca-watch\']                = new Array(\'w\',\'Legg denne sida til i overvakingslista di\');
ta[\'ca-unwatch\']              = new Array(\'w\',\'Fjern denne sida frå overvakingslista di\');
ta[\'search\']                  = new Array(\'f\',\'Søk gjennom denne wikien\');
ta[\'p-logo\']                  = new Array(\'\',\'Hovudside\');
ta[\'n-mainpage\']              = new Array(\'z\',\'Gå til hovudsida\');
ta[\'n-portal\']                = new Array(\'\',\'Om prosjektet, kva du kan gjera, kvar du finn saker og ting\');
ta[\'n-currentevents\']         = new Array(\'\',\'Aktuelt\');
ta[\'n-recentchanges\']         = new Array(\'r\',\'Liste over dei siste endringane som er gjort på wikien.\');
ta[\'n-randompage\']            = new Array(\'x\',\'Vis ei tilfeldig side\');
ta[\'n-help\']                  = new Array(\'\',\'Hjelp til å bruke alle funksjonane.\');
ta[\'n-sitesupport\']           = new Array(\'\',\'Støtt oss!\');
ta[\'t-whatlinkshere\']         = new Array(\'j\',\'Liste over alle wikisidene som har lenkjer hit\');
ta[\'t-recentchangeslinked\']   = new Array(\'k\',\'Siste endringar på sider denne sida lenkjer til\');
ta[\'feed-rss\']                = new Array(\'\',\'RSS-mating for denne sida\');
ta[\'feed-atom\']               = new Array(\'\',\'Atom-mating for denne sida\');
ta[\'t-contributions\']         = new Array(\'\',\'Sjå liste over bidrag frå denne brukaren\');
ta[\'t-emailuser\']             = new Array(\'\',\'Send ein e-post til denne brukaren\');
ta[\'t-upload\']                = new Array(\'u\',\'Last opp filer\');
ta[\'t-specialpages\']          = new Array(\'q\',\'Liste over spesialsider\');
ta[\'ca-nstab-main\']           = new Array(\'c\',\'Vis innhaldssida\');
ta[\'ca-nstab-user\']           = new Array(\'c\',\'Vis brukarsida\');
ta[\'ca-nstab-media\']          = new Array(\'c\',\'Direktelenkje (filpeikar) til fil\');
ta[\'ca-nstab-special\']        = new Array(\'\',\'Dette er ei spesialside, du kan ikkje endre ho.\');
ta[\'ca-nstab-project\']             = new Array(\'c\',\'Vis prosjektside\');
ta[\'ca-nstab-image\']          = new Array(\'c\',\'Vis filside\');
ta[\'ca-nstab-mediawiki\']      = new Array(\'c\',\'Vis systemmelding\');
ta[\'ca-nstab-template\']       = new Array(\'c\',\'Vis mal\');
ta[\'ca-nstab-help\']           = new Array(\'c\',\'Vis hjelpeside\');
ta[\'ca-nstab-category\']       = new Array(\'c\',\'Vis kategoriside\');
/*
</pre>
*/',

# image deletion
'deletedrevision'      => 'Slett gammal versjon $1.',

# browsing diffs
'previousdiff'         => '← Gå til førre skilnaden',
'nextdiff'             => 'Gå til neste skilnaden →',
'imagemaxsize'         => 'Avgrens bilete på filsider til (pikslar):',
'thumbsize'            => 'Miniatyrstørrelse:',
'showbigimage'         => 'Last ned høgoppløysingsversjon ($1x$2, $3 KB)',
'newimages'            => 'Filgalleri',
'showhidebots'         => '($1 bottar)',
'noimages'             => 'Her er ingen filer som kan visast.',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel'  => 'Brukar:',
'speciallogtitlelabel' => 'Tittel:',

'passwordtooshort'     => 'Passordet er for kort. Det må vera minst $1 teikn langt.',

# Media Warning
'mediawarning'         => '\'\'\'Åtvaring\'\'\': Denne fila kan innehalda skadelege program, ved å opna ho kan systemet ditt ta skade.
<hr />',

'fileinfo'             => '$1KB, MIME-type: <code>$2</code>',

# Metadata
'metadata'             => 'Metadata',

# Exif tags
'exif-imagewidth'      => 'Breidd',
'exif-imagelength'     => 'Høgd',
'exif-bitspersample'   => 'Bitar per komponent',
'exif-compression'     => 'Komprimeringsteknikk',
'exif-photometricinterpretation' => 'Pikselsamansetjing',
'exif-orientation'     => 'Retning',
'exif-samplesperpixel' => 'Tal komponentar',
'exif-planarconfiguration' => 'Dataarrangement',
'exif-ycbcrpositioning' => 'Y- og C-posisjon',
'exif-xresolution'     => 'Oppløysing i breidda',
'exif-yresolution'     => 'Oppløysing i høgda',
'exif-resolutionunit'  => 'Eining for X- og Y-oppløysing',
'exif-jpeginterchangeformatlength' => 'Byte JPEG-data',
'exif-referenceblackwhite' => 'Svart og kvitt referanseverdipar',
'exif-datetime'        => 'Dato og tid endra',
'exif-imagedescription' => 'Tittel',
'exif-make'            => 'Kameraprodusent',
'exif-model'           => 'Kameramodell',
'exif-software'        => 'Programvare brukt',
'exif-artist'          => 'Skapar',
'exif-copyright'       => 'Opphavsrettsleg eigar',
'exif-exifversion'     => 'Exif-versjon',
'exif-flashpixversion' => 'Støtta Flashpix versjon',
'exif-colorspace'      => 'Fargerom',
'exif-pixelydimension'  => 'Gyldig biletbreidd',
'exif-pixelxdimension'  => 'Gyldig bilethøgd',
'exif-makernote'        => 'Produsentnotat',
'exif-usercomment'      => 'Brukarkommentarar',
'exif-relatedsoundfile' => 'Tilknytt lydfil',
'exif-datetimeoriginal' => 'Dato og tid laga',
'exif-datetimedigitized' => 'Dato og tid digitalisert',
'exif-subsectime'       => 'Dato og tid subsekund',
'exif-subsectimeoriginal' => 'Dato og tid laga subsekund',
'exif-subsectimedigitized' => 'Dato og tid digitalisert subsekund',
'exif-exposuretime'     => 'Eksponeringstid',
'exif-fnumber'          => 'F-nummer',
'exif-exposureprogram'  => 'Eksponeringsprogram',
'exif-isospeedratings'  => 'Lysfølsemd (ISO)',
'exif-shutterspeedvalue' => 'Lukkarfart',
'exif-aperturevalue'    => 'Blendartal',
'exif-brightnessvalue'  => 'Lysstyrke',
'exif-exposurebiasvalue' => 'Exposure bias',
'exif-subjectdistance'  => 'Motivavstand',
'exif-meteringmode'     => 'Lysmålarmodus',
'exif-lightsource'      => 'Lyskjelde',
'exif-flash'            => 'Blits',
'exif-focallength'      => 'Linsefokallengd',
'exif-subjectarea'      => 'Motivområde',
'exif-flashenergy'      => 'Blitsstyrke',
'exif-focalplaneresolutionunit' => 'Oppløysingseining for fokalplanet',
'exif-subjectlocation'  => 'Motivplassering',
'exif-exposureindex'    => 'Eksponeringsindeks',
'exif-sensingmethod'    => 'Sensor',
'exif-filesource'       => 'Filkjelde',
'exif-scenetype'        => 'Scenetype',
'exif-cfapattern'       => 'CFA-mønster',
'exif-exposuremode'     => 'Eksponeringsmodus',
'exif-whitebalance'     => 'Kvitbalanse',
'exif-digitalzoomratio' => 'Digital zoom-rate',
'exif-focallengthin35mmfilm' => '(Tilsvarande) brennvidd ved 35 mm film',
'exif-scenecapturetype' => 'Motivtype',
'exif-gaincontrol'      => 'Scene control',
'exif-contrast'         => 'Kontrast',
'exif-saturation'       => 'Metting',
'exif-sharpness'        => 'Skarpleik',
'exif-subjectdistancerange' => 'Motivavstandsområde',
'exif-imageuniqueid'    => 'Unik bilete-ID',
'exif-gpsversionid'     => 'GPS-merke-versjon',
'exif-gpslatituderef'   => 'Nordleg eller sørleg breiddegrad',
'exif-gpslatitude'      => 'Breiddegrad',
'exif-gpslongituderef'  => 'Austleg eller vestleg lengdegrad',
'exif-gpslongitude'     => 'Lengdegrad',
'exif-gpsaltituderef'   => 'Høgdereferanse',
'exif-gpsaltitude'      => 'Høgd over havet',
'exif-gpstimestamp'     => 'GPS-tid (atomklokke)',
'exif-gpssatellites'    => 'Satellittar brukt for å måle',
'exif-gpsstatus'        => 'GPS-Mottakarstatus',
'exif-gpsmeasuremode'   => 'Målemodus',
'exif-gpsdop'           => 'Målepresisjon',
'exif-gpsspeedref'      => 'Fartsmåleining',
'exif-gpsspeed'         => 'Fart på GPS-mottakar',
'exif-gpstrackref'      => 'Referanse for rørsleretning',
'exif-gpstrack'         => 'Rørsleretning',
'exif-gpsimgdirectionref' => 'Referanse for retning åt biletet',
'exif-gpsimgdirection'  => 'Retninga åt biletet',
'exif-gpsmapdatum'      => 'Geodetisk kartleggingsdata brukt',
'exif-gpsdestlatituderef' => 'Referanse for målbreiddegrad',
'exif-gpsdestlatitude'  => 'Målbreiddegrad',
'exif-gpsdestlongituderef' => 'Referanse for mållengdegrad',
'exif-gpsdestlongitude' => 'Mållengdegrad',
'exif-gpsdestdistanceref' => 'Referanse for avstand til mål',
'exif-gpsdestdistance'  => 'Avstand til mål',
'exif-gpsprocessingmethod' => 'Namn på GPS-handsamingsmetode',
'exif-gpsareainformation'  => 'Namn på GPS-område',
'exif-gpsdatestamp'     => 'GPS-dato',
'exif-gpsdifferential'  => 'GPS differential correction',

# Exif attributes
'exif-compression-1'    => 'Ukomprimert',

'exif-orientation-1'    => 'Normal', // 0th row: top; 0th column: left
'exif-orientation-2'    => 'Spegla vassrett', // 0th row: top; 0th column: right
'exif-orientation-3'    => 'Rotert 180°', // 0th row: bottom; 0th column: right
'exif-orientation-4'    => 'Spegla loddrett', // 0th row: bottom; 0th column: left
'exif-orientation-5'    => 'Rotert 90° motsols og spegla vassrett', // 0th row: left; 0th column: top
'exif-orientation-6'    => 'Rotert 90° medsols', // 0th row: right; 0th column: top
'exif-orientation-7'    => 'Rotert 90° medsols og spegla loddrett', // 0th row: right; 0th column: bottom
'exif-orientation-8'    => 'Rotert 90° motsols', // 0th row: left; 0th column: bottom

'exif-componentsconfiguration-0' => 'finst ikkje',

'exif-exposureprogram-0' => 'Ikkje bestemt',
'exif-exposureprogram-1' => 'Manuelt',
'exif-exposureprogram-2' => 'Normalt program',
'exif-exposureprogram-3' => 'Blendarprioritet',
'exif-exposureprogram-4' => 'Lukkarprioritet',
'exif-exposureprogram-5' => 'Kreativt program (mest mogleg skarpt)',
'exif-exposureprogram-6' => 'Handlingsprogram (med vekt på snøgg lukkar)',
'exif-exposureprogram-7' => 'Portrettmodus (for nærbilete med uskarp bakgrunn)',
'exif-exposureprogram-8' => 'Landskapsmodus (for landskapsbilete med skarp bakgrunn)',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0' => 'Ukjent',
'exif-meteringmode-1' => 'Snittmåling',
'exif-meteringmode-2' => 'Snittmåling med vekt på midten',
'exif-meteringmode-3' => 'Punktmåling',
'exif-meteringmode-4' => 'Fleirpunktsmåling',
'exif-meteringmode-5' => 'Mønster',
'exif-meteringmode-6' => 'Delvis',
'exif-meteringmode-255' => 'Annan',

'exif-lightsource-0' => 'Ukjent',
'exif-lightsource-1' => 'Dagslys',
'exif-lightsource-2' => 'Fluorescerande',
'exif-lightsource-4' => 'Blits',
'exif-lightsource-9' => 'Fint vêr',
'exif-lightsource-10' => 'Overskya vêr',
'exif-lightsource-11' => 'Skugge',
'exif-lightsource-12' => 'Fluorescerande dagslys (D 5700 – 7100K)',
'exif-lightsource-13' => 'Dag, kvitt, fluorescerande (N 4600 – 5400K)',
'exif-lightsource-14' => 'Kjølig, kvitt, fluorescerande (W 3900 – 4500K)',
'exif-lightsource-15' => 'Kvitt fluorescerande (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standardlys A',
'exif-lightsource-18' => 'Standardlys B',
'exif-lightsource-19' => 'Standardlys C',
'exif-lightsource-255' => 'Anna lyskjelde',

'exif-focalplaneresolutionunit-2' => 'tommar',

'exif-sensingmethod-1' => 'Ikkje bestemt',
'exif-sensingmethod-2' => 'Einbrikka fargeområdesensor',
'exif-sensingmethod-3' => 'Tobrikka fargeområdesensor',
'exif-sensingmethod-4' => 'Trebrikka fargeområdesensor',
'exif-sensingmethod-7' => 'Trilinær sensor',

'exif-scenetype-1'     => 'Direkte fotografert bilete',

'exif-customrendered-0' => 'Normal prosess',
'exif-customrendered-1' => 'Tilpassa prosess',

'exif-exposuremode-0'   => 'Autoeksponert',
'exif-exposuremode-1'   => 'Manuelt eksponert',

'exif-whitebalance-0'   => 'Automatisk kvitbalanse',
'exif-whitebalance-1'   => 'Manuell kvitbalanse',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landskap',
'exif-scenecapturetype-2' => 'Portrett',
'exif-scenecapturetype-3' => 'Nattscene',

'exif-gaincontrol-0'      => 'Ingen',

'exif-contrast-0'         => 'Normal',
'exif-contrast-1'         => 'Mjuk',
'exif-contrast-2'         => 'Hard',

'exif-saturation-0'       => 'Normal',
'exif-saturation-1'       => 'Låg metting',
'exif-saturation-2'       => 'Høg metting',

'exif-sharpness-0'        => 'Normal',
'exif-sharpness-1'        => 'Mjuk',
'exif-sharpness-2'        => 'Hard',

'exif-subjectdistancerange-0' => 'Ukjent',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Nært',
'exif-subjectdistancerange-3' => 'Fjernt',

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n'      => 'Nordleg breiddegrad',
'exif-gpslatitude-s'      => 'Sørleg breiddegrad',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e'     => 'Austleg lengdegrad',
'exif-gpslongitude-w'     => 'Vestleg lengdegrad',

'exif-gpsstatus-a'        => 'Måling pågår',

'exif-gpsmeasuremode-2'   => 'todimensjonalt målt',
'exif-gpsmeasuremode-3'   => 'tredimensjonalt målt',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k'         => 'Kilometer per time',
'exif-gpsspeed-m'         => 'Engelsk mil per time',
'exif-gpsspeed-n'         => 'Knop',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t'     => 'Verkeleg retning',
'exif-gpsdirection-m'     => 'Magnetisk retning',

# external editor support
'edit-externally'         => 'Endre denne fila med eit eksternt program',
'edit-externally-help'    => 'Sjå [[Help:Eksterne program|instruksjonane]] for meir informasjon.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall'        => 'alle',
'imagelistall'            => 'alle',
'watchlistall1'           => 'alle',
'watchlistall2'           => 'alle',
'namespacesall'           => 'alle',

# E-mail address confirmation
'confirmemail'            => 'Stadfest e-postadresse',
'confirmemail_text'       => '{{SITENAME}} krev at du stadfester e-postadressa di
før du får brukt funksjonar knytt til e-post. Klikk på knappen under for å sende ei stadfestingsmelding
til adressa di. E-posten kjem med ei lenkje som har ein kode; opne
lenkja i nettlesaren din for å stadfeste at e-postadressa di er gyldig.',
'confirmemail_send'       => 'Send stadfestingsmelding',
'confirmemail_sent'       => 'Stadfestingsmelding er sendt.',
'confirmemail_sendfailed' => 'Kunne ikkje sende stadfestingsmelding. Sjå til at adressa ikkje har ugyldige bokstavar.',
'confirmemail_invalid'    => 'Feil stadfestingskode. Koden er kanskje for forelda.',
'confirmemail_success'    => 'E-postadressa di er stadfest. Du kan no logge inn og kose deg med {{SITENAME}}.',
'confirmemail_loggedin'   => 'E-postadressa di er stadfest.',
'confirmemail_error'      => 'Noko gjekk gale når stadfestinga di skulle lagrast.',

'confirmemail_subject'    => 'Stadfesting av e-postadresse frå {{SITENAME}}',
'confirmemail_body'       => 'Nokon, truleg du, frå IP-adressa $1, har registrert kontoen «$2» med di e-postadresse på {{SITENAME}}.

For å stadfeste at denne kontoen faktisk høyrer til deg og for å slå på
funksjonar tilknytt e-post på {{SITENAME}} må du opne denne lenkja i nettlesaren din:

$3

Dersom dette *ikkje* er deg, må du ikkje opne lenkja. Denne stadfestingskoden
blir forelda $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact'               => 'Prøv nøyaktig treff',
'searchfulltext'         => 'Søk i all tekst',
'createarticle'          => 'Lag side',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki transcluding is disabled]',
'scarytranscludefailed'   => '[Henting av mal for $1 gjekk ikkje, beklagar]',
'scarytranscludetoolong'  => '[Nettadressa er for lang, beklagar]',

# Trackbacks
'trackbackbox'           => "<div id='mw_trackbacks'>
Attendelenkjer for denne sida:<br />
$1
</div>",
'trackbackremove'        => ' ([$1 Slett])',
'trackbacklink'          => 'Attendelenkje',
'trackbackdeleteok'      => 'Attendelenkja vart sletta.',

# delete conflict
'deletedwhileediting'    => 'Åtvaring: Denne sida har blitt sletta medan du endra den!',
'confirmrecreate'        => 'Brukaren «[[User:$1|$1]]» ([[User talk:$1|brukardiskusjon]]) sletta denne sida medan du endra den med denne grunngjevinga:
: \'\'$2\'\'
Du må stadfesta om du verkjeleg vil nyopprette denne sida.',
'recreate'               => 'Nyopprett',
'tooltip-recreate'       => 'Ved å trykkje på «Nyopprett» vert sida oppretta på nytt.',

'unit-pixel' => 'px',

);


?>
