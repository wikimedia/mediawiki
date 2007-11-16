<?php
/** Greek (Ελληνικά)
 *
 * @addtogroup Language
 *
 * @author Dead3y3
 * @author ZaDiak
 * @author Assassingr
 * @author MF-Warburg
 * @author G - ג
 * @author Nike
 * @author Consta
 */

/**
  * Translation by Pasok Internet Volunteers
  * http://forum.pasok.gr
  * version 1.0 (initial release)
  *
  *The project for the translation of MediaWiki into Greek
  *was undertaken by a group of ICT volunteers working under
  *the auspices of the Greek political party PASOK.
  *
  *The idea behind this effort was  to provide an extensible,
  *easy-to-use and non-intimidating tool for content development
  *and project management, to be used throughout the administrative
  *and political structure of PASOK by staff, volunteers, party members
  *and elected officials (all of whom possess varying degrees of ICT skills).
  *
  *The PASOK ICT team and the volunteers who worked on this project are
  *now returning the translated interface to the Open-Source Community
  *with over 98% of the messages translated into user-friendly Greek.
  *
  *We hope that it will be used as a tool by other civil society organizations
  *in Greece, and that it will enhance the collective creation and the dissemination
  *of knowledge - an essential component of the democratic process.
  */

$namespaceNames = array(
	NS_MEDIA            => 'Μέσον',
	NS_SPECIAL          => 'Ειδικό',
	NS_MAIN	            => '',
	NS_TALK	            => 'Συζήτηση',
	NS_USER             => 'Χρήστης',
	NS_USER_TALK        => 'Συζήτηση_χρήστη',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_συζήτηση',
	NS_IMAGE            => 'Εικόνα',
	NS_IMAGE_TALK       => 'Συζήτηση_εικόνας',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Πρότυπο',
	NS_TEMPLATE_TALK    => 'Συζήτηση_προτύπου',
	NS_HELP             => 'Βοήθεια',
	NS_HELP_TALK        => 'Συζήτηση_βοήθειας',
	NS_CATEGORY         => 'Κατηγορία',
	NS_CATEGORY_TALK    => 'Συζήτηση_κατηγορίας',
);
$fallback8bitEncoding = 'iso-8859-7';
$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([a-z]+)(.*)$/sD';


$datePreferences = array(
	'default',
	'dmy',
	'ISO 8601',
);

$defaultDateFormat = 'dmy';

$datePreferenceMigrationMap = array(
	'default',
	'dmy',
	'dmy',
	'dmy'
);

$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);


/**
 * Magic words
 * Customisable syntax for wikitext and elsewhere.
 *
 * IDs must be valid identifiers, they can't contain hyphens.
 *
 * Note to translators:
 *   Please include the English words as synonyms.  This allows people
 *   from other wikis to contribute more easily.
 *
 * This array can be modified at runtime with the LanguageGetMagic hook
 */
$magicWords = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#ΑΝΑΚΑΤΕΥΘΥΝΣΗ',	'#REDIRECT'              ),
	'notoc'                  => array( 0,    '__ΧΩΡΙΣΠΠ__',		'__NOTOC__'              ),
	'nogallery'              => array( 0,    '__ΧΩΡΙΣΠΙΝΑΚΟΘΗΚΗ__',	'__NOGALLERY__'          ),
	'forcetoc'               => array( 0,    '__ΜΕΠΠ__', 		'__FORCETOC__'           ),
	'toc'                    => array( 0,    '__ΠΠ__',		'__TOC__'                ),
	'noeditsection'          => array( 0,    '__ΧΩΡΙΣΕΠΕΞΕΝΟΤ__',	'__NOEDITSECTION__'      ),
	'currentmonth'           => array( 1,    'ΤΡΕΧΩΝΜΗΝΑΣ',		'CURRENTMONTH'           ),
	'currentmonthname'       => array( 1,    'ΤΡΕΧΩΝΜΗΝΑΣΟΝΟΜΑ',	'CURRENTMONTHNAME'       ),
	'currentmonthnamegen'    => array( 1,    'ΤΡΕΧΩΝΜΗΝΑΣΓΕΝΙΚΗ',	'CURRENTMONTHNAMEGEN'    ),
	'currentmonthabbrev'     => array( 1,    'ΤΡΕΧΩΝΜΗΝΑΣΣΥΝΤ',	'CURRENTMONTHABBREV'     ),
	'currentday'             => array( 1,    'ΤΡΕΧΟΥΣΑΜΕΡΑ',	'CURRENTDAY'             ),
	'currentday2'            => array( 1,    'ΤΡΕΧΟΥΣΑΜΕΡΑ2',	'CURRENTDAY2'            ),
	'currentdayname'         => array( 1,    'ΤΡΕΧΟΥΣΑΜΕΡΑΟΝΟΜΑ',	'CURRENTDAYNAME'         ),
	'currentyear'            => array( 1,    'ΤΡΕΧΟΝΕΤΟΣ',		'CURRENTYEAR'            ),
	'currenttime'            => array( 1,    'ΤΡΕΧΩΝΧΡΟΝΟΣ',	'CURRENTTIME'            ),
	'currenthour'            => array( 1,    'ΤΡΕΧΟΥΣΑΩΡΑ',		'CURRENTHOUR'            ),
	'localmonth'             => array( 1,    'ΤΟΠΙΚΟΣΜΗΝΑΣ',	'LOCALMONTH'             ),
	'localmonthname'         => array( 1,    'ΤΟΠΙΚΟΣΜΗΝΑΣΟΝΟΜΑ',	'LOCALMONTHNAME'         ),
	'localmonthnamegen'      => array( 1,    'ΤΟΠΙΚΟΣΜΗΝΑΣΓΕΝΙΚΗ',	'LOCALMONTHNAMEGEN'      ),
	'localmonthabbrev'       => array( 1,    'ΤΟΠΙΚΟΣΜΗΝΑΣΣΥΝΤ',	'LOCALMONTHABBREV'       ),
	'localday'               => array( 1,    'ΤΟΠΙΚΗΜΕΡΑ',		'LOCALDAY'               ),
	'localday2'              => array( 1,    'ΤΟΠΙΚΗΜΕΡΑ2',		'LOCALDAY2'              ),
	'localdayname'           => array( 1,    'ΤΟΠΙΚΗΜΕΡΑΟΝΟΜΑ',	'LOCALDAYNAME'           ),
	'localyear'              => array( 1,    'ΤΟΠΙΚΟΕΤΟΣ',		'LOCALYEAR'              ),
	'localtime'              => array( 1,    'ΤΟΠΙΚΟΣΧΡΟΝΟΣ',	'LOCALTIME'              ),
	'localhour'              => array( 1,    'ΤΟΠΙΚΗΩΡΑ',		'LOCALHOUR'              ),
	'numberofpages'          => array( 1,    'ΑΡΙΘΜΟΣΣΕΛΙΔΩΝ',	'NUMBEROFPAGES'          ),
	'numberofarticles'       => array( 1,    'ΑΡΙΘΜΟΣΑΡΘΡΩΝ',	'NUMBEROFARTICLES'       ),
	'numberoffiles'          => array( 1,    'ΑΡΙΘΜΟΣΑΡΧΕΙΩΝ',	'NUMBEROFFILES'          ),
	'numberofusers'          => array( 1,    'ΑΡΙΘΜΟΣΧΡΗΣΤΩΝ',	'NUMBEROFUSERS'          ),
	'numberofedits'          => array( 1,    'ΑΡΙΘΜΟΣΑΛΛΑΓΩΝ',	'NUMBEROFEDITS'          ),
	'pagename'               => array( 1,    'ΟΝΟΜΑΣΕΛΙΔΑΣ',	'PAGENAME'               ),
	'pagenamee'              => array( 1,    'ΟΝΟΜΑΣΕΛΙΔΑΣΚ',	'PAGENAMEE'              ),
	'namespace'              => array( 1,    'ΠΕΡΙΟΧΗ',		'NAMESPACE'              ),
	'namespacee'             => array( 1,    'ΠΕΡΙΟΧΗΚ',		'NAMESPACEE'             ),
	'talkspace'              => array( 1,    'ΠΕΡΙΟΧΗΣΥΖΗΤΗΣΕΩΝ',	'TALKSPACE'              ),
	'talkspacee'             => array( 1,    'ΠΕΡΙΟΧΗΣΥΖΗΤΗΣΕΩΝΚ',	'TALKSPACEE'              ),
	'subjectspace'           => array( 1,    'ΠΕΡΙΟΧΗΘΕΜΑΤΩΝ',	'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'          => array( 1,    'ΠΕΡΙΟΧΗΘΕΜΑΤΩΝΚ',	'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'           => array( 1,    'ΠΛΗΡΕΣΟΝΟΜΑΣΕΛΙΔΑΣ',	'FULLPAGENAME'           ),
	'fullpagenamee'          => array( 1,    'ΠΛΗΡΕΣΟΝΟΜΑΣΕΛΙΔΑΣΚ',	'FULLPAGENAMEE'          ),
	'subpagename'            => array( 1,    'ΟΝΟΜΑΥΠΟΣΕΛΙΔΑΣ',	'SUBPAGENAME'            ),
	'subpagenamee'           => array( 1,    'ΟΝΟΜΑΥΠΟΣΕΛΙΔΑΣΚ',	'SUBPAGENAMEE'           ),
	'basepagename'           => array( 1,    'ΒΑΣΗΟΝΟΜΑΤΟΣΣΕΛΙΔΑΣ',	'BASEPAGENAME'           ),
	'basepagenamee'          => array( 1,    'ΒΑΣΗΟΝΟΜΑΤΟΣΣΕΛΙΔΑΣΚ','BASEPAGENAMEE'          ),
	'talkpagename'           => array( 1,    'ΟΝΟΜΑΣΕΛΙΔΑΣΣΥΖΗΤΗΣΕΩΝ', 'TALKPAGENAME'        ),
	'talkpagenamee'          => array( 1,    'ΟΝΟΜΑΣΕΛΙΔΑΣΣΥΖΗΤΗΣΕΩΝΚ', 'TALKPAGENAMEE'      ),
	'subjectpagename'        => array( 1,    'ΟΝΟΜΑΣΕΛΙΔΑΣΘΕΜΑΤΟΣ',	'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'       => array( 1,    'ΟΝΟΜΑΣΕΛΙΔΑΣΘΕΜΑΤΟΣΚ',	'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                    => array( 0,    'ΚΕΙΜΕΝΟ:',		'MSG:'                   ),
	'subst'                  => array( 0,    'ΑΛΛΑΓΗ:',		'SUBST:'                 ),
	'msgnw'                  => array( 0,    'ΑΠΛΟΚΕΙΜΕΝΟ:',	'MSGNW:'                 ),
	'img_thumbnail'          => array( 1,    'μινιατούρα',		'thumbnail', 'thumb'     ),
	'img_manualthumb'        => array( 1,    'μινιατούρα=$1',	'thumbnail=$1', 'thumb=$1'),
	'img_right'              => array( 1,    'δεξιά',		'right'                  ),
	'img_left'               => array( 1,    'αριστερά',		'left'                   ),
	'img_none'               => array( 1,    'καθόλου',		'none'                   ),
	'img_width'              => array( 1,    '$1εσ',		'$1px'                   ),
	'img_center'             => array( 1,    'κέντρο',		'center', 'centre'       ),
	'img_framed'             => array( 1,    'με-πλαίσιο',		'framed', 'enframed', 'frame' ),
	'img_frameless'          => array( 1,    'χωρίςς-πλαίσιο',	'frameless'              ),
	'img_page'               => array( 1,    'σελίδα=$1', 'σελίδα $1',	'page=$1', 'page $1'     ),
	'img_upright'            => array( 1,    'κατακόρυφα', 'κατακόρυφα =$1', 'κατακόρυφα $1',	'upright', 'upright=$1', 'upright $1'  ),
	'img_border'             => array( 1,    'πλαίσιο',		'border'                 ),
	'img_baseline'           => array( 1,    'γραμμήβάσης',		'baseline'               ),
	'img_sub'                => array( 1,    'δείκτης',		'sub'                    ),
	'img_super'              => array( 1,    'εκθέτης',		'super', 'sup'           ),
	'img_top'                => array( 1,    'άνω',			'top'                    ),
	'img_text_top'           => array( 1,    'πάνω-από-το-κείμενο',	'text-top'               ),
	'img_middle'             => array( 1,    'μέσο',		'middle'                 ),
	'img_bottom'             => array( 1,    'κάτω',		'bottom'                 ),
	'img_text_bottom'        => array( 1,    'κάτω-από-το-κείμενο',	'text-bottom'            ),
	'int'                    => array( 0,    'ΕΣΩΤ:',		'INT:'                   ),
	'sitename'               => array( 1,    'ΙΣΤΟΧΩΡΟΣ',		'SITENAME'               ),
	'ns'                     => array( 0,    'ΧΟ:',			'NS:'                    ),
	'localurl'               => array( 0,    'ΤΟΠΙΚΟURL:',		'LOCALURL:'              ),
	'localurle'              => array( 0,    'ΤΟΠΙΚΟURLΚ:',		'LOCALURLE:'             ),
	'server'                 => array( 0,    'ΕΞΥΠΗΡΕΤΗΤΗΣ',	'SERVER'                 ),
	'servername'             => array( 0,    'ΟΝΟΜΑΕΞΥΠΗΡΕΤΗΤΗ',	'SERVERNAME'             ),
	'scriptpath'             => array( 0,    'ΔΙΑΔΡΟΜΗΠΡΟΓΡΑΜΜΑΤΟΣ','SCRIPTPATH'             ),
	'grammar'                => array( 0,    'ΓΡΑΜΜΑΤΙΚΗ:',		'GRAMMAR:'               ),
	'notitleconvert'         => array( 0,    '__ΧΩΡΙΣΜΕΤΑΤΡΟΠΗΤΙΤΛΟΥ__',	'__NOTITLECONVERT__', '__NOTC__'),
	'nocontentconvert'       => array( 0,    '__ΧΩΡΙΣΜΕΤΑΤΡΟΠΗΠΕΡΙΧΟΜΕΝΟΥ__',	'__NOCONTENTCONVERT__', '__NOCC__'),
	'currentweek'            => array( 1,    'ΤΡΕΧΟΥΣΑΕΒΔΟΜΑΔΑ',	'CURRENTWEEK'            ),
	'currentdow'             => array( 1,    'ΤΡΕΧΟΥΣΑΜΕΡΑΕΒΔΟΜΑΔΑΣ', 'CURRENTDOW'           ),
	'localweek'              => array( 1,    'ΤΟΠΙΚΗΕΒΔΟΜΑΔΑ',	'LOCALWEEK'              ),
	'localdow'               => array( 1,    'ΤΟΠΙΚΗΜΕΡΑΕΒΔΟΜΑΔΑΣ',	'LOCALDOW'               ),
	'revisionid'             => array( 1,    'ΚΩΔΙΚΟΣΑΛΛΑΓΗΣ',	'REVISIONID'             ),
	'revisionday'            => array( 1,    'ΜΕΡΑΑΛΛΑΓΗΣ',		'REVISIONDAY'            ),
	'revisionday2'           => array( 1,    'ΜΕΡΑΑΛΛΑΓΗΣ2',	'REVISIONDAY2'           ),
	'revisionmonth'          => array( 1,    'ΜΗΝΑΣΑΛΛΑΓΗΣ',	'REVISIONMONTH'          ),
	'revisionyear'           => array( 1,    'ΕΤΟΣΑΛΛΑΓΗΣ',		'REVISIONYEAR'           ),
	'revisiontimestamp'      => array( 1,    'ΧΡΟΝΟΣΗΜΑΝΣΗΑΛΛΑΓΗΣ',	'REVISIONTIMESTAMP'      ),
	'plural'                 => array( 0,    'ΠΛΗΘΥΝΤΙΚΟΣ:',	'PLURAL:'                ),
	'fullurl'                => array( 0,    'ΠΛΗΡΕΣURL:',		'FULLURL:'               ),
	'fullurle'               => array( 0,    'ΠΛΗΡΕΣURLΚ:',		'FULLURLE:'              ),
	'lcfirst'                => array( 0,    'ΠΡΩΤΟΠΕΖΟ:',		'LCFIRST:'               ),
	'ucfirst'                => array( 0,    'ΠΡΩΤΟΚΕΦΑΛΑΙΟ:',	'UCFIRST:'               ),
	'lc'                     => array( 0,    'ΠΕΖΑ:',		'LC:'                    ),
	'uc'                     => array( 0,    'ΚΕΦΑΛΑΙΑ:',		'UC:'                    ),
	'raw'                    => array( 0,    'ΓΥΜΝΑ:',		'RAW:'                   ),
	'displaytitle'           => array( 1,    'ΔΕΙΞΕΤΙΤΛΟ',		'DISPLAYTITLE'           ),
	'rawsuffix'              => array( 1,    'Γ',			'R'                      ),
	'newsectionlink'         => array( 1,    '__ΔΕΣΜΟΣΝΕΑΣΕΝΟΤΗΤΑΣ__', '__NEWSECTIONLINK__'  ),
	'currentversion'         => array( 1,    'ΤΡΕΧΟΥΣΑΕΚΔΟΣΗ',	'CURRENTVERSION'         ),
	'urlencode'              => array( 0,    'ΚΩΔΙΚΟΠΟΙΗΣΗURL:',	'URLENCODE:'             ),
	'anchorencode'           => array( 0,    'ΚΩΔΙΚΟΠΟΙΗΣΗΑΓΚΥΡΑΣ',	'ANCHORENCODE'           ),
	'currenttimestamp'       => array( 1,    'ΤΡΕΧΟΥΣΑΧΡΟΝΟΣΗΜΑΝΣΗ','CURRENTTIMESTAMP'       ),
	'localtimestamp'         => array( 1,    'ΤΟΠΙΚΗΧΡΟΝΟΣΗΜΑΝΣΗ',	'LOCALTIMESTAMP'         ),
	'directionmark'          => array( 1,    'ΚΩΔΙΚΟΣΦΟΡΑΣ',	'DIRECTIONMARK', 'DIRMARK' ),
	'language'               => array( 0,    '#ΓΛΩΣΣΑ:',		'#LANGUAGE:'             ),
	'contentlanguage'        => array( 1,    'ΓΛΩΣΣΑΠΕΡΙΕΧΟΜΕΝΟΥ',	'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'       => array( 1,    'ΣΕΛΙΔΕΣΣΤΗΝΠΕΡΙΟΧΗΟΝΟΜΑΤΩΝ:', 'ΣΕΛΙΔΕΣΣΤΗΝΠΟ:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'         => array( 1,    'ΑΡΙΘΜΟΣΔΙΑΧΕΙΡΙΣΤΩΝ',	'NUMBEROFADMINS'         ),
	'formatnum'              => array( 0,    'ΜΟΡΦΟΠΟΙΗΣΗΑΡΙΘΜΟΥ',	'FORMATNUM'              ),
	'padleft'                => array( 0,    'ΑΡΙΣΤΕΡΟΠΑΡΑΓΕΜΙΣΜΑ',	'PADLEFT'                ),
	'padright'               => array( 0,    'ΔΕΞΙΠΑΡΑΓΕΜΙΣΜΑ',	'PADRIGHT'               ),
	'special'                => array( 0,    'λειτουργία',		'special',               ),
	'defaultsort'            => array( 1,    'ΠΡΟΚΑΘΟΡΙΣΜΕΝΗΤΑΞΙΝΟΜΗΣΗ:', 'ΚΛΕΙΔΙΠΡΟΚΑΘΟΡΙΣΜΕΝΗΣΤΑΞΙΝΟΜΗΣΗΣ:', 'ΠΡΟΚΑΘΟΡΙΣΜΕΝΗΤΑΞΙΝΟΜΗΣΗΚΑΤΗΓΟΡΙΑΣ:', 'ΠΡΟΚΤΑΞ:', 	'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Υπογράμμιση συνδέσμων',
'tog-highlightbroken'         => 'Κατεστραμένοι σύνδεσμοι μορφοποίησης <a href="" class="new">όπως αυτός</a> (εναλλακτικά: όπως αυτός<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Στοίχιση παραγράφων',
'tog-hideminor'               => 'Απόκρυψη αλλαγών μικρής κλίμακας',
'tog-extendwatchlist'         => 'Ανάπτυξη λίστας παρακολούθησης ώστε να δείχνει όλες τις εφαρμόσιμες αλλαγές',
'tog-usenewrc'                => 'Ανάδειξη πρόσφατων αλλαγών (δεν λειτουργεί σε όλους τους browser)',
'tog-numberheadings'          => 'Αυτόματη αρίθμιση τίτλων',
'tog-showtoolbar'             => 'Εμφάνιση μπάρας εργαλείων επεξεργασίας',
'tog-editondblclick'          => 'Επεξεργασία σελίδων με διπλό κλικ (JavaScript)',
'tog-editsection'             => 'Ενεργοποίηση επεξεργασίας τμημάτων μέσω των συνδέσμων [επεξεργασία]',
'tog-editsectiononrightclick' => 'Ενεργοποίηση επεξεργασίας τμήματος με δεξύ κλικ<br /> στους τίτλους των τμημάτων (JavaScript)',
'tog-showtoc'                 => 'Εμφάνιση πίνακα περιεχομένων <br />(για σελίδες με περισσότερες από τρεις κεφαλίδες)',
'tog-rememberpassword'        => 'Διατήρηση του κωδικού πρόσβασης σε αυτό τον υπολογιστή',
'tog-editwidth'               => 'Πλαίσιο επεξεργασίας στο μέγιστο πλάτος',
'tog-watchcreations'          => 'Πρόσθεσε τις σελίδες που δημιουργώ στη λίστα παρακολούθησής μου',
'tog-watchdefault'            => 'Προσθήκη των σελίδων που επεξεργάζεστε στη λίστα παρακολούθησης.',
'tog-watchmoves'              => 'Πρόσθεσε τις σελίδες που μετακινώ στη λίστα παρακολούθησής μου',
'tog-watchdeletion'           => 'Πρόσθεσε τις σελίδες που διαγράφω στη λίστα παρακολούθησής μου',
'tog-minordefault'            => 'Προκαθορίστε να χαρακτηρίζονται όλες οι αλλαγές "μικρής κλίμακας".',
'tog-previewontop'            => 'Εμφάνιση προεπισκόπησης πριν από το πλαίσο επεξεργασίας και όχι μετά',
'tog-previewonfirst'          => 'Εμφάνιση προεπισκόπησης κατά την πρώτη επεξεργασία',
'tog-nocache'                 => 'Απενεργοποίηση της δυνατότητας δημιουργίας cache των σελίδων',
'tog-enotifwatchlistpages'    => 'Ειδοποίηση με e-mail σχετικά με αλλαγές στις σελίδες που παρακολουθώ.',
'tog-enotifusertalkpages'     => 'Ειδοποίηση με e-mail σχετικά με αλλαγές στη συζήτηση της δικής μου σελίδας χρήστη',
'tog-enotifminoredits'        => 'Ειδοποίηση με e-mail και για τις αλλαγές μικρής κλίμακας σε αυτή τη σελίδα',
'tog-enotifrevealaddr'        => 'Εμφάνιση της ηλεκτρονικής μου διεύθυνσης στις ειδοποιήσεις που μου αποστέλλονται.',
'tog-shownumberswatching'     => 'Εμφάνιση του αριθμού των συνδεδεμένων χρηστών',
'tog-fancysig'                => 'Απλή υπογραφή (χωρίς τη χρήση αυτόματου συνδέσμου)',
'tog-externaleditor'          => 'Εξ αρχής χρήση εξωτερικού επεξεργαστή κειμένου',
'tog-externaldiff'            => 'Εξ αρχής χρήση εξωτερικού λογισμικού αντιπαραβολής (diffing)',
'tog-showjumplinks'           => 'Ενεργοποίησε τους συνδέσμους προσβασιμότητας του τύπου "jump to"',
'tog-uselivepreview'          => 'Χρησιμοποίησε άμεση προεπισκόπηση (JavaScript) (Πειραματικό)',
'tog-forceeditsummary'        => 'Ειδοποίησέ με όταν εισάγω μια κενή σύνοψη επεξεργασίας',
'tog-watchlisthideown'        => 'Απέκρυψε τις επεξεργασίες μου απο τη λίστα παρακολούθησης',
'tog-watchlisthidebots'       => 'Απέκρυψε τις επεξεργασίες των bots από τη λίστα παρακολούθησης',
'tog-watchlisthideminor'      => 'Απέκρυψε τις μικρής σημασίας επεξεργασίες από τη λίστα παρακολούθησης',
'tog-nolangconversion'        => 'Απενεργοποίησε τη μετατροπή μεταβλητών',
'tog-ccmeonemails'            => 'Στείλε μου αντίγραφα των μηνυμάτων ηλεκτρονικού ταχυδρομείου που στέλνω σε άλλους χρήστες',
'tog-diffonly'                => 'Μην εμφανίζεις το περιεχόμενο της σελίδας κάτω από τις διαφορές των εκδόσεων',

'underline-always'  => 'Πάντα',
'underline-never'   => 'Ποτέ',
'underline-default' => 'Όπως ορίζεται από το browser σας.',

'skinpreview' => '(προεπισκόπηση)',

# Dates
'sunday'        => 'Κυριακή',
'monday'        => 'Δευτέρα',
'tuesday'       => 'Τρίτη',
'wednesday'     => 'Τετάρτη',
'thursday'      => 'Πέμπτη',
'friday'        => 'Παρασκευή',
'saturday'      => 'Σαββάτο',
'sun'           => 'Κυ',
'mon'           => 'Δε',
'tue'           => 'Τρ',
'wed'           => 'Τε',
'thu'           => 'Πε',
'fri'           => 'Πα',
'sat'           => 'Σα',
'january'       => 'Ιανουάριος',
'february'      => 'Φεβρουάριος',
'march'         => 'Μάρτιος',
'april'         => 'Απρίλιος',
'may_long'      => 'Μάιος',
'june'          => 'Ιούνιος',
'july'          => 'Ιούλιος',
'august'        => 'Αύγουστος',
'september'     => 'Σεπτέμβριος',
'october'       => 'Οκτώβριος',
'november'      => 'Νοέμβριος',
'december'      => 'Δεκέμβριος',
'january-gen'   => 'Ιανουαρίου',
'february-gen'  => 'Φεβρουαρίου',
'march-gen'     => 'Μαρτίου',
'april-gen'     => 'Απριλίου',
'may-gen'       => 'Μαΐου',
'june-gen'      => 'Ιουνίου',
'july-gen'      => 'Ιουλίου',
'august-gen'    => 'Αυγούστου',
'september-gen' => 'Σεπτεμβρίου',
'october-gen'   => 'Οκτωβρίου',
'november-gen'  => 'Νοεμβρίου',
'december-gen'  => 'Δεκεμβρίου',
'jan'           => 'Ιαν',
'feb'           => 'Φεβρ',
'mar'           => 'Μαρτ',
'apr'           => 'Απρ',
'may'           => 'Μαΐου',
'jun'           => 'Ιουν',
'jul'           => 'Ιουλ',
'aug'           => 'Αυγ',
'sep'           => 'Σεπτ',
'oct'           => 'Οκτ',
'nov'           => 'Νοε',
'dec'           => 'Δεκ',

# Bits of text used by many pages
'categories'            => 'Κατηγορίες',
'pagecategories'        => '{{PLURAL:$1|Κατηγορία|Κατηγορίες}}',
'category_header'       => 'Άρθρα στην κατηγορία "$1"',
'subcategories'         => 'Υποκατηγορίες',
'category-media-header' => 'Πολυμέσα στην κατηγορία «$1»',
'category-empty'        => "''Αυτή η κατηγορία δεν περιέχει άρθρα ή εικόνες.''",

'mainpagetext'      => 'To λογισμικό Wiki εγκαταστάθηκε επιτυχώς.',
'mainpagedocfooter' => 'Περισσότερες πληροφορίες σχετικά με τη χρήση και με τη ρύθμιση παραμέτρων θα βρείτε στους συνδέσμους: [http://meta.wikimedia.org/wiki/MediaWiki_localisation Οδηγίες για τροποποίηση του περιβάλλοντος εργασίας] και [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Εγχειρίδιο χρήστη].',

'about'          => 'Σχετικά',
'article'        => 'Σελίδα κειμένου (ή άλλου περιεχομένου)',
'newwindow'      => '(ανοίγει σε ξεχωριστό παράθυρο)',
'cancel'         => 'Ακύρωση',
'qbfind'         => 'Εύρεση',
'qbbrowse'       => 'Περιήγηση',
'qbedit'         => 'Επεξεργασία',
'qbpageoptions'  => 'Αυτή η σελίδα',
'qbpageinfo'     => 'Συμφραζόμενα',
'qbmyoptions'    => 'Οι σελίδες μου',
'qbspecialpages' => 'Σελίδες λειτουργιών',
'moredotdotdot'  => 'Περισσότερα...',
'mypage'         => 'Η σελίδα μου',
'mytalk'         => 'Οι συζητήσεις μου',
'anontalk'       => 'Οι συζητήσεις αυτής της διεύθυνσης IP',
'navigation'     => 'Πλοήγηση',

# Metadata in edit box
'metadata_help' => 'Μεταδεδομένα:',

'errorpagetitle'    => 'Σφάλμα',
'returnto'          => 'Επιστροφή στη σελίδα $1.',
'tagline'           => 'Από {{SITENAME}}',
'help'              => 'Βοήθεια',
'search'            => 'Αναζήτηση',
'searchbutton'      => 'Αναζήτηση',
'go'                => 'Μετάβαση',
'searcharticle'     => 'Μετάβαση',
'history'           => 'Ιστορικό σελίδας',
'history_short'     => 'Ιστορικό',
'updatedmarker'     => 'ενημερωμένα από την τελευταία επίσκεψή μου',
'info_short'        => 'Πληροφορίες',
'printableversion'  => 'Εκτυπώσιμη έκδοση',
'permalink'         => 'Μόνιμος σύνδεσμος',
'print'             => 'Εκτύπωση',
'edit'              => 'Επεξεργασία',
'editthispage'      => 'Επεξεργασία αυτής της σελίδας',
'delete'            => 'Διαγραφή',
'deletethispage'    => 'Διαγραφή αυτής της σελίδας',
'undelete_short'    => 'Να αναστραφεί η διαγραφή $1 επεξεργασιών.',
'protect'           => 'Προστασία',
'protect_change'    => 'άλλαξε προστασία',
'protectthispage'   => 'Κλείδωμα της σελίδας',
'unprotect'         => 'Άρση προστασίας',
'unprotectthispage' => 'Άρση προστασίας αυτής της σελίδας',
'newpage'           => 'Νέα σελίδα',
'talkpage'          => 'Συζήτηση για αυτή τη σελίδα',
'talkpagelinktext'  => 'Συζήτηση',
'specialpage'       => 'Σελίδα λειτουργιών',
'personaltools'     => 'Προσωπικά εργαλεία',
'postcomment'       => 'Καταχωρίστε ένα σχόλιο.',
'articlepage'       => 'Εμφάνιση σελίδας κειμένου',
'talk'              => 'Συζήτηση',
'views'             => 'Εμφανίσεις',
'toolbox'           => 'Εργαλεία',
'userpage'          => 'Εμφάνιση σελίδας χρήστη',
'projectpage'       => 'Εμφάνιση σελίδας βοήθειας',
'imagepage'         => 'Εμφάνιση σελίδας εικόνων',
'mediawikipage'     => 'Προβολή σελίδας μηνύματος',
'templatepage'      => 'Προβολή σελίδας προτύπου',
'viewhelppage'      => 'Προβολή σελίδας βοήθειας',
'categorypage'      => 'Προβολή σελίδας κατηγορίας',
'viewtalkpage'      => 'Εμφάνιση συζήτησης',
'otherlanguages'    => 'Άλλες γλώσσες',
'redirectedfrom'    => '(Ανακατεύθυνση από $1)',
'redirectpagesub'   => 'Σελίδα ανακατεύθυνσης',
'lastmodifiedat'    => 'Η σελίδα αυτή τροποποιήθηκε τελευταία φορά στις $2, $1.', # $1 date, $2 time
'viewcount'         => 'Αυτή η σελίδα έχει προσπελαστεί $1 φορές.',
'protectedpage'     => 'Κλειδωμένη σελίδα',
'jumpto'            => 'Μετάβαση σε:',
'jumptonavigation'  => 'πλοήγηση',
'jumptosearch'      => 'αναζήτηση',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Σχετικά με τον ιστότοπο {{SITENAME}}',
'aboutpage'         => '{{ns:4}}:Σχετικά',
'bugreports'        => 'Αναφορές σφαλμάτων',
'bugreportspage'    => '{{ns:4}}:Αναφορά_σφάλματος',
'copyright'         => 'Το περιεχόμενο είναι διαθέσιμο σύμφωνα με την $1.',
'copyrightpagename' => '{{SITENAME}} δικαιώματα πνευματικής ιδιοκτησίας',
'copyrightpage'     => 'Project:Πνευματικά Δικαιώματα',
'currentevents'     => 'Τρέχοντα γεγονότα',
'currentevents-url' => 'Τρέχοντα γεγονότα',
'disclaimers'       => 'Αποποίηση ευθυνών',
'disclaimerpage'    => '{{ns:4}}:Αποποίηση_ευθύνης',
'edithelp'          => 'Βοήθεια σχετικά με την επεξεργασία',
'edithelppage'      => '{{ns:12}}:Επεξεργασία',
'faq'               => 'Συνήθεις ερωτήσεις (FAQ)',
'faqpage'           => '{{ns:12}}:Συνήθεις ερωτήσεις (FAQ)',
'helppage'          => '{{ns:12}}:Περιεχόμενα',
'mainpage'          => 'Αρχική σελίδα',
'policy-url'        => 'Project:Πολιτική',
'portal'            => 'Ο ιστοχώρος της κοινότητας',
'portal-url'        => '{{ns:4}}:Ο_ιστοχώρος_της_κοινότητας',
'privacy'           => 'Πολιτική ιδιωτικού απορρήτου',
'privacypage'       => 'Project:Πολιτική ιδιωτικού απορρήτου',
'sitesupport'       => 'Υποστήριξη ιστοχώρου',
'sitesupport-url'   => '{{ns:4}}:Υποστήριξη_ιστοχώρου',

'badaccess'        => 'Ακατάλληλη άδεια',
'badaccess-group0' => 'Δεν επιτρέπεται να εκτελέσετε την ενέργεια που ζητήσατε.',
'badaccess-group1' => 'Η ενέργεια που ζητήσατε είναι περιορισμένη σε χρήστες στην ομάδα $1.',
'badaccess-group2' => 'Η ενέργεια που ζητήσαςτε είναι περιορισμένη σε χρήστες σε μία από τις ομάδες $1.',
'badaccess-groups' => 'Η ενέργεια που ζητήσατε είναι περιορισμένη σε χρήστες σε μία από τις ομάδες $1.',

'versionrequired'     => 'Απαιτείται η έκδοση $1 του MediaWiki.',
'versionrequiredtext' => 'Για να χρησιμοποιήσετε αυτή τη σελίδα απαιτείται η έκδοση $1 του MediaWiki . Βλ. [[Special:Έκδοση]]',

'ok'                      => 'Εντάξει',
'retrievedfrom'           => 'Ανακτήθηκε από το "$1".',
'youhavenewmessages'      => 'Έχετε $1 ($2).',
'newmessageslink'         => 'νέο μήνυμα',
'newmessagesdifflink'     => 'τελευταία αλλαγή',
'youhavenewmessagesmulti' => 'Έχετε νέα μηνύματα στο $1',
'editsection'             => 'επεξεργασία',
'editold'                 => 'επεξεργασία',
'editsectionhint'         => 'Επεξεργασία ενότητας: $1',
'toc'                     => 'Πίνακας περιεχομένων',
'showtoc'                 => 'εμφάνιση',
'hidetoc'                 => 'απόκρυψη',
'thisisdeleted'           => 'Εμφάνιση ή αποκατάσταση της $1;',
'viewdeleted'             => 'Δείτε το $1;',
'restorelink'             => '$1 επεξεργασίες έχουν διαγραφεί.',
'feedlinks'               => 'Ροή δεδομένων:',
'feed-invalid'            => 'Άκυρος τύπος συνδρομής σε feed.',
'site-rss-feed'           => '$1 RSS Συνδρομή',
'site-atom-feed'          => '$1 Atom Συνδρομή',
'page-rss-feed'           => '"$1" RSS Συνδρομή',
'page-atom-feed'          => '"$1" Atom Συνδρομή',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Άρθρο',
'nstab-user'      => 'Σελίδα χρήστη',
'nstab-media'     => 'Ηλεκτρονικά μέσα',
'nstab-special'   => 'Σελίδα λειτουργιών',
'nstab-project'   => 'Σχετικά με',
'nstab-image'     => 'Εικόνα',
'nstab-mediawiki' => 'Μήνυμα',
'nstab-template'  => 'Πρότυπο',
'nstab-help'      => 'Βοήθεια',
'nstab-category'  => 'Κατηγορία',

# Main script and global functions
'nosuchaction'      => 'Δεν υπάρχει τέτοια ενέργεια.',
'nosuchactiontext'  => 'Η ενέργεια που καθορίστηκε από την διεύθυνση URL δεν αναγνωρίζεται από το Wiki.',
'nosuchspecialpage' => 'Δεν υπάρχει τέτοια σελίδα λειτουργιών.',
'nospecialpagetext' => 'Έχετε ζητήσει μια ειδική σελίδα που δεν αναγνωρίζεται από το Wiki.',

# General errors
'error'                => 'Σφάλμα',
'databaseerror'        => 'Σφάλμα στη βάση δεδομένων',
'dberrortext'          => 'Σημειώθηκε συντακτικό σφάλμα σε αίτημα προς τη βάση δεδομένων. Πιθανόν να πρόκειται για ένδειξη σφάλματος στο λογισμικό. Το τελευταίο αίτημα προς τη βάση δεδομένων που επιχειρήθηκε ήταν: <blockquote><tt>$1</tt></blockquote> μέσα από τη λειτουργία "<tt>$2</tt>".  Το MySQL επέστρεψε σφάλμα "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Σημειώθηκε συντακτικό σφάλμα σε αίτημα προς τη βάση δεδομένων. Το τελευταίο αίτημα που επιχειρήθηκε ήταν: "$1" μέσα από τη λειτουργία "$2". Το MySQL επέστρεψε σφάλμα "$3: $4".',
'noconnect'            => 'Συγγνώμη! Η εφαρμογή συνάντησε κάποιες τεχνικές δυσκολίες και δεν μπορεί να επικοινωνήσει με τον διακομιστή της βάσης δεδομένων. <br />
$1',
'nodb'                 => 'Αδύνατη η επιλογή της βάσης δεδομένων $1',
'cachederror'          => 'Το ακόλουθο κείμενο προέρχεται από το προσωρινό αρχείο (cache) και πιθανόν να μην περιέχει τις πιο πρόσφατες αλλαγές.',
'laggedslavemode'      => 'Προειδοποίηση: Η σελίδα μπορεί να μην έχει ενημερωθεί με τις πρόσφατες αλλαγές.<br />',
'readonly'             => 'Κλειδωμένη βάση δεδομένων',
'enterlockreason'      => 'Εξηγήστε τους λόγους για το κλείδωμα και το πότε, κατά την εκτίμησή σας, το κλείδωμα αυτό θα αναιρεθεί.',
'readonlytext'         => 'Η βάση δεδομένων είναι προσωρινά κλειδωμένη και δεν μπορεί να δεχθεί νέα λήμματα και άλλες τροποποιήσεις -πιθανότατα λόγω συντήρησης. Μετά τη συντήρηση θα επανέλθει σε κανονική λειτουργία.  Η αιτιολογία για το κλείδωμα της βάσης δεδομένων ήταν η εξής: <p>$1',
'missingarticle'       => "Η βάση δεδομένων δεν βρήκε το αναμενόμενο περιεχόμενο σελίδας κάτω από το όνομα \"\$1\".

<p> Αυτό συμβαίνει όταν ακολουθούμε συνδέσμους μιας παλιάς 'αντιπαραβολής' ή 'ίστορικού' σε σελίδες που έχουν διαγραφεί. Αν δεν πρόκειται για τέτοια περίπτωση, είναι πιθανόν να υπάρχει σφάλμα στο λογισμικό. Σας παρακαλούμε να το αναφέρετε στους διαχειριστές, παραπέμποντας στο συγκεκριμένο URL.",
'readonly_lag'         => 'Η βάση δεδομένων έχει κλειδωθεί αυτόματα για να μπορέσουν οι επιμέρους servers των αντιγράφων της βάσης δεδομένων (slaves) να καλύψουν τη διαφορά με τον κεντρικό server του πρωτοτύπου της βάσης (master).',
'internalerror'        => 'Εσωτερικό σφάλμα',
'internalerror_info'   => 'Εσωτερικό λάθος: $1',
'filecopyerror'        => 'Δεν ήταν δυνατή η αντιγραφή του αρχείου "$1" στο "$2".',
'filerenameerror'      => 'Δεν είναι δυνατή η μετονομασία του αρχείου "$1" σε "$2".',
'filedeleteerror'      => 'Δεν ήταν δυνατή η διαγραφή του αρχείου "$1".',
'directorycreateerror' => 'Δεν μπορούσε να δημιουργηθεί η κατηγορία "$1".',
'filenotfound'         => 'Δεν είναι δυνατή η ανεύρεση του αρχείου "$1".',
'fileexistserror'      => 'Αδύνατον να εγγραφεί στο αρχείο "$1": το αρχείο υπάρχει',
'unexpected'           => 'Μη προσδοκώμενη τιμή: "$1"="$2"',
'formerror'            => 'Λάθος: Δεν ολοκληρώθηκε η υποβολή της φόρμας!',
'badarticleerror'      => 'Η ενέργεια αυτή δεν μπορεί να εκτελεσθεί στη συγκεκριμένη σελίδα.',
'cannotdelete'         => 'Αδύνατη η διαγραφή της συγκεκριμένης σελίδας ή εικόνας (Ενδεχομένως να έχει ήδη διαγραφεί από άλλο χρήστη.)',
'badtitle'             => 'Ακατάλληλος τίτλος',
'badtitletext'         => 'Ο τίτλος της σελίδας που ζητήσατε είναι άκυρος ή κενός ή πρόκειται για έναν εσφαλμένα συνδεδεμένο διαγλωσσικό τίτλο (ή εσφαλμένα συνδεδεμένο τίτλο ανάμεσα σε διαφορετικά Wiki).',
'perfdisabled'         => 'Λυπούμαστε! Αυτή η λειτουργία έχει προσωρινά απενεργοποιηθεί επειδή επιβραδύνει τη βάση δεδομένων σε βαθμό που κανένας χρήστης δεν μπορεί να χρησιμοποιήσει το Wiki.',
'perfcached'           => 'Τα δεδομένα που ακολουθούν είναι cached και είνα πιθανόν να μην είναι πλήρως ενημερωμένα:',
'perfcachedts'         => 'Τα ακόλουθα δεδομένα είναι καταχωρημένα στη λανθάνουσα μνήμη, και ενημερώθηκαν τελευταία στις $1.',
'querypage-no-updates' => 'Οι ενημερώσεις για αυτή τη σελίδα είναι απενεργοποιημένες. Τα δεδομένα εδώ δεν θα ανανεωθούν προς το παρόν.',
'wrong_wfQuery_params' => 'Λανθανσμένες παράμετροι στο wfQuery()<br />
Λειτουργία: $1<br />
Αίτημα: $2',
'viewsource'           => 'Εμφάνιση κώδικα',
'viewsourcefor'        => 'για $1',
'protectedpagetext'    => 'Αυτή η σελίδα έχει κλειδωθεί για αποτροπή επεξεργασίας της.',
'viewsourcetext'       => 'Μπορείτε να δείτε και να αντιγράψετε τον κώδικα αυτής της σελίδας:',
'protectedinterface'   => 'Αυτή η σελίδα παρέχει κείμενο διεπαφής για το λογισμικό, και έχει κλειδωθεί για πρόληψη τυχόν βανδαλισμού.',
'editinginterface'     => "'''Προσοχή:''' Επεξεργάζεστε μια σελίδα η οποία χρησιμοποιείται για να παρέχει κείμενο διεπαφής για το λογισμικό. Αλλαγές σε αυτή τη σελίδα θα επηρεάσουν την εμφάνιση της διεπαφής χρήστη για τους άλλους χρήστες.",
'sqlhidden'            => '(το αίτημα SQL δεν εμφανίζεται)',
'cascadeprotected'     => 'Αυτή η σελίδα έχει προστατευθεί από επεξεργασία, επειδή περιλαμβάνεται στις ακόλουθες {{PLURAL:$1|σελίδα|σελίδες}}, που είναι προστατευμένες με ενεργοποιημένη την "διαδοχική" προστασία:',
'namespaceprotected'   => "Δεν έχετε άδεια να επεξεργάζεστε σελίδες στον τομέα '''$1'''.",
'customcssjsprotected' => 'Δεν έχετε δικαίωμα να επεξεργαστείτε αυτή τη σελίδα, γιατί περιέχει προσωπικές ρυθμίσεις άλλου χρήστη.',
'ns-specialprotected'  => 'Σελίδες στον τομέα {{ns:special}} δεν γίνεται να επεξεργαστούν.',

# Login and logout pages
'logouttitle'                => 'Έξοδος χρήστη',
'logouttext'                 => 'Έχετε αποσυνδεθεί.
Μπορείτε να παραμείνετε στο {{SITENAME}} ανώνυμα, ή μπορείτε να συνδεθείτε ξανά με το ίδιο ή με διαφορετικό (εάν έχετε) όνομα χρήστη. Έχετε υπόψη σας πως αρκετές σελίδες θα συνεχίσουν να εμφανίζονται κανονικά, σαν να μην έχετε αποσυνδεθεί, μέχρι να καθαρίσετε τη λανθάνουσα μνήμη του φυλλομετρητή σας.',
'welcomecreation'            => '== Καλώς ήλθατε, $1! ==

Ο λογαριασμός σας έχει δημιουργθεί. Μπορείτε να εξατομικεύσετε το {{SITENAME}} σύμφωνα με τις ανάγκες σας μέσα από το σύνδεσμο [[Special:Preferences|Προτιμήσεις]].',
'loginpagetitle'             => 'Είσοδος χρήστη',
'yourname'                   => 'Όνομα χρήστη',
'yourpassword'               => 'Κωδικός',
'yourpasswordagain'          => 'Πληκτρολογήστε ξανά τον κωδικό',
'remembermypassword'         => 'Διατήρηση του κωδικού πρόσβασης σε αυτόν τον υπολογιστή',
'yourdomainname'             => 'Το domain σας:',
'externaldberror'            => 'Συνέβη εξωτερικό σφάλμα πιστοποίησης στη βάση δεδομένων ή δεν σας έχει επιτραπεί να ενημερώσετε τον εξωτερικό σας λογαριασμό.',
'loginproblem'               => '<b>Εμφανίστηκε πρόβλημα κατά την είσοδό σας.</b><br />Παρακαλούμε δοκιμάστε ξανά!',
'login'                      => 'Είσοδος',
'loginprompt'                => 'Πρέπει να έχετε ενεργοποιήσει τα cookies για να συνδεθείτε στο {{SITENAME}}.',
'userlogin'                  => 'Δημιουργία Λογαριασμού/Είσοδος',
'logout'                     => 'Έξοδος',
'userlogout'                 => 'Έξοδος χρήστη',
'notloggedin'                => 'Δεν έχετε συνδεθεί.',
'nologin'                    => 'Δεν είστε εγγεγραμμένος χρήστης; $1.',
'nologinlink'                => 'Δημιουργήστε έναν λογαριασμό',
'createaccount'              => 'Δημιουργία νέου λογαριασμού',
'gotaccount'                 => 'Έχετε ήδη έναν λογαριασμό; $1.',
'gotaccountlink'             => 'Συνδεθείτε',
'createaccountmail'          => 'Με ηλεκτρονικό ταχυδρομείο',
'badretype'                  => 'Οι κωδικοί που έχετε δηλώσει δεν συμφωνούν μεταξύ τους.',
'userexists'                 => 'Το όνομα χρήστη που συμπληρώσατε είναι ήδη σε χρήση. Παρακαλούμε διαλέξτε ένα άλλο όνομα.',
'youremail'                  => 'Ηλεκτρονική διεύθυνση*',
'username'                   => 'Όνομα χρήστη:',
'uid'                        => 'Αριθμός αναγνώρισης χρήστη:',
'yourrealname'               => 'Όνομα και επώνυμο*',
'yourlanguage'               => 'Γλώσσα διασύνδεσης',
'yourvariant'                => 'Η γλώσσα που χρησιμοποιείτε',
'yournick'                   => 'Το ψευδώνυμό σας (για την υπογραφή)',
'badsig'                     => 'Άκυρη υπογραφή raw: ελέγξτε τις ετικέτες HTML.',
'badsiglength'               => 'Το όνομα ειναι πολυ μακρύ; πρέπει να είναι κάτω από $1 χαρακτήρες.',
'email'                      => 'αλληλογραφία',
'prefs-help-realname'        => '* Πραγματικό όνομα (προαιρετικό): εφόσον εισάγετε το όνομά σας, αυτό θα μπορεί να χρησιμοποιηθεί για να αναγνωριστεί ευρύτερα η δουλειά σας.',
'loginerror'                 => 'Λάθος σύνδεσης',
'prefs-help-email'           => '* Email (προαιρετικό): Δίνει τη δυνατότητα σε άλλους χρήστες να επικοινωνήσουν μαζί σας μέσω της σελίδας χρήστη (ή της συζήτησης για την σελίδα χρήστη) χωρίς να εμφανίζεται η ταυτότητά σας.',
'prefs-help-email-required'  => 'Απαιτείται η διεύθυνση e-mail.',
'nocookiesnew'               => 'Ο λογαριασμός χρήστη έχει δημιουργηθεί, αλλά δεν έχετε ακόμα συνδεθεί. Το {{SITENAME}} χρησιμοποιεί cookies κατά τη σύνδεση των χρηστών. Τα cookies είναι απενεργοποιημένα στον υπολογιστή σας. Παρακαλούμε ενεργοποιήστε τα και στη συνέχεια συνδεθείτε χρησιμοποιώντας το νέο όνομα χρήστη σας και τον κωδικό σας.',
'nocookieslogin'             => 'Το {{SITENAME}} χρησιμοποιεί cookies κατά τη σύνδεση των χρηστών. Τα cookies είναι απενεργοποιημένα στον υπολογιστή σας. Παρακαλούμε ενεργοποιήστε τα και ξαναδοκιμάστε!',
'noname'                     => 'Το όνομα χρήστη που έχετε καθορίσει δεν είναι έγκυρο.',
'loginsuccesstitle'          => 'Επιτυχής σύνδεση',
'loginsuccess'               => 'Είστε συνδεδεμένος(-η) στο {{SITENAME}} ως "$1".',
'nosuchuser'                 => 'Δεν υπάρχει χρήστης με το όνομα "$1".
Ελέγξτε την ορθογραφία ή χρησιμοποιείστε την παρακάτω φόρμα για να δημιουργήσετε ένα νέο λογαριασμό.',
'nosuchusershort'            => 'Δεν υπάρχει χρήστης με το όνομα "$1". Παρακαλούμε ελέγξτε την ορθογραφία.',
'nouserspecified'            => 'Πρέπει να ορίσετε ένα όνομα χρήστη.',
'wrongpassword'              => 'Ο κωδικός που πληκτρολογήσατε είναι λανθασμένος. Παρακαλούμε προσπαθήστε ξανά.',
'wrongpasswordempty'         => 'Ο κωδικός πρόσβασης που εισάχθηκε ήταν κενός. Παρακαλώ προσπαθήστε ξανά.',
'passwordtooshort'           => 'Ο κωδικός σας είναι πολύ σύντομος. Πρέπει να περιέχει τουλάχιστον $1 χαρακτήρες.',
'mailmypassword'             => 'Στείλτε μου ένα νέο κωδικό.',
'passwordremindertitle'      => 'Υπενθύμιση κωδικού από το {{SITENAME}}',
'passwordremindertext'       => 'Κάποιος (πιθανώς εσείς, από την διεύθυνση IP $1) ζήτησε να σας στείλουμε ένα νέο κωδικό πρόσβασης για τον ιστότοπο {{SITENAME}} ($4). Ο κωδικός πρόσβασης για το χρήστη "$2" είναι τώρα "$3". Θα πρέπει να συνδεθείτε και να αλλάξετε τον κωδικό πρόσβασής σας τώρα.

Αν κάποιος άλλος έκανε αυτή την αίτηση ή αν έχετε θυμηθεί τον κωδικό πρόσβασής σας και δεν επιθυμείτε πλέον να τον αλλάξετε, μπορείτε να αγνοήσετε αυτό το μήνυμα και να συνεχίσετε να χρησιμοποιείτε τον παλιό κωδικό πρόσβασής σας.',
'noemail'                    => 'Δεν υπάρχει ηλεκτρονική διεύθυνση για το χρήστη "$1".',
'passwordsent'               => 'Σας έχει σταλεί ένας νέος κωδικός στην ηλεκτρονική διέθυνση που δηλώσατε για "$1".
Σας παρακαλούμε να ξανασυνδεθείτε μόλις τον λάβετε.',
'blocked-mailpassword'       => 'Η διεύθυνση IP σας είναι αποκλεισμένη από επεξεργασία, και έτσι
δεν επιτρέπεται να χρησιμοποιήσει την λειτουργία ανάκτησης κωδικού πρόσβασης, για την αποφυγή κατάχρησης.',
'eauthentsent'               => 'Ένα μήνυμα επαλήθευσης έχει σταλεί στην ηλεκτρονική διεύθυνση που έχετε δηλώσει στο σύστημα. Πριν αρχίσει η αποστολή μηνυμάτων στη συγκεκριμένη διεύθυνση, πρέπει να ακολουθήσετε τις οδηγίες που βρίσκονται στο μήνυμα που σας έχει σταλεί για να επαληθεύσετε ότι η συγκεκριμένη ηλεκτρονική διεύθυνση ανήκει πραγματικά σε εσάς.',
'throttled-mailpassword'     => 'Μια υπενθύμιση για τον κωδικό πρόσβασης έχει ήδη σταλεί, μέσα στις
τελευταίες $1 ώρες. Για την αποφυγή κατάχρησης, μόνο μια υπενθύμιση για τον κωδικό πρόσβασης θα στέλνεται ανά
$1 ώρες.',
'mailerror'                  => 'Λάθος στην αποστολή του μηνύματος: $1',
'acct_creation_throttle_hit' => 'Λυπούμαστε, έχετε ήδη δημιουργήσει $1 λογαριασμούς και δεν μπορείτε να δημιουργήσετε άλλους.',
'emailauthenticated'         => 'Η ηλεκτρονική σας διεύθυνση επιβεβαιώθηκε στις $1.',
'emailnotauthenticated'      => 'Η ηλεκτρονική σας διεύθυνση δεν έχει επαληθευθεί ακόμα. Μέχρι να ολοκληρώσετε την επαλήθευση της διεύθυνσής σας, δεν είναι δυνατόν το σύστημα να σας αποστείλει αλληλογραφία για καμμία από τις ακόλουθες λειτορυγίες.',
'noemailprefs'               => '<strong>Δεν έχει ορισθεί ηλεκτρονική διέυθυνση</strong>, οι λειτουργίες που ακολουθούν δεν θα είναι δυνατόν να ολοκληρωθούν.',
'emailconfirmlink'           => 'Επαληθεύστε την ηλεκτρονική σας διεύθυνση',
'invalidemailaddress'        => 'Η ηλεκτρονική διεύθυνση δεν έγινε δεκτή γιατί ενδεχομένως δεν είχε έγκυρη μορφή. Παρακαλούμε συμπληρώστε μια σωστά διαμορφωμένη διεύθυνση ή αφήστε το πεδίο κενό.',
'accountcreated'             => 'Ο λογαριασμός δημιουργήθηκε',
'accountcreatedtext'         => 'Ο λογαριασμός χρήστη για τον/την $1 έχει δημιουργηθεί.',
'loginlanguagelabel'         => 'Γλώσσα: $1',

# Password reset dialog
'resetpass'               => 'Επαναφορά κωδικού πρόσβασης για τον λογαριασμό',
'resetpass_announce'      => 'Συνδεθήκατε με ένα προσωρινό κωδικό, σταλμένο με e-mail. Για να ολοκληρώσετε την σύνδεση, πρέπει να στείλετε ένα νέο κωδικό εδώ:',
'resetpass_text'          => '<!-- Προσθέστε κείμενο εδώ -->',
'resetpass_header'        => 'Επαναφορά κωδικού πρόσβασης',
'resetpass_submit'        => 'Δώστε κωδικό πρόσβασης και συνδεθείτε',
'resetpass_success'       => 'Ο κωδικός πρόσβασής σας άλλαξε επιτυχώς! Τώρα σας συνδέουμε...',
'resetpass_bad_temporary' => 'Άκυρος προσωρινός κωδικός πρόσβασης. Μπορεί ήδη να έχετε αλλάξει επιτυχώς τον κωδικό πρόσβασής σας ή να έχετε ζητήσει ένα νέο προσωρινό κωδικό πρόσβασης.',
'resetpass_forbidden'     => 'Οι κωδικοί πρόσβασης δεν μπορούν να αλλαχθούν σε αυτό το wiki',
'resetpass_missing'       => 'Η φόρμα δεν περιέχει δεδομένα.',

# Edit page toolbar
'bold_sample'     => 'Έντονο κείμενο',
'bold_tip'        => 'Έντονο κείμενο',
'italic_sample'   => 'Κείμενο με πλάγιους χαρακτήρες',
'italic_tip'      => 'Κείμενο με πλάγιους χαρακτήρες',
'link_sample'     => 'Τίτλος συνδέσμου',
'link_tip'        => 'Εσωτερικός σύνδεσμος',
'extlink_sample'  => 'http://www.paradeigma.com τίτλος συνδέσμου',
'extlink_tip'     => 'Εξωτερικός σύνδεσμος (μην ξεχάστε το πρόθεμα http:// )',
'headline_sample' => 'Κείμενο τίτλου',
'headline_tip'    => 'Δεύτερος τίτλος (επίπεδο 2)',
'math_sample'     => 'Εισαγωγή τύπου εδώ',
'math_tip'        => 'Μαθηματικός τύπος (LaTeX)',
'nowiki_sample'   => 'Εισάγετε εδώ το μη μορφοποιημένο κείμενο.',
'nowiki_tip'      => 'Να αγνοηθεί η μορφοποίηση Wiki.',
'image_sample'    => 'paradeigma.jpg',
'image_tip'       => 'Ενσωματωμένη εικόνα',
'media_sample'    => 'paradeigma.mp3',
'media_tip'       => 'Σύνδεσμος αρχείου πολυμέσων',
'sig_tip'         => 'Υπογραφή με ημερομηνία',
'hr_tip'          => 'Οριζόντια γραμμή (να χρησιμοποιείται με μέτρο!)',

# Edit pages
'summary'                   => 'Σύνοψη',
'subject'                   => 'Θέμα/επικεφαλίδα',
'minoredit'                 => 'Αλλαγή μικρής κλίμακας',
'watchthis'                 => 'Παρακολούθηση αυτής της σελίδας',
'savearticle'               => 'Αποθήκευση σελίδας',
'preview'                   => 'Προεπισκόπηση',
'showpreview'               => 'Προεπισκόπηση',
'showlivepreview'           => 'Άμεση προεπισκόπιση',
'showdiff'                  => 'Δείτε τις αλλαγές',
'anoneditwarning'           => "'''Προσοχή:''' Δεν έχετε συνδεθεί. Η διεύθυνση IP σας θα καταγραφεί στο ιστορικό επεξεργασίας αυτής της σελίδας.",
'missingsummary'            => "'''Υπενθύμιση:''' Δεν έχετε παρέχει μια σύνοψη επεξεργασίας. Αν κάνετε κλικ στο κουμπί Αποθήκευση πάλι, η επεξεργασία σας θα αποθηκευτεί χωρίς μια σύνοψη.",
'missingcommenttext'        => 'Παρακαλώ εισάγετε ένα σχόλιο παρακάτω.',
'missingcommentheader'      => "'''Υπενθύμιση:''' Δεν έχετε παρέχει ένα θέμα/επικεφαλίδα για αυτό το σχόλιο. Αν κάνετε κλικ στο κουμπί Αποθήκευση πάλι, η επεξεργασία σας θα αποθηκευτεί χωρίς ένα θέμα ή μια επικεφαλίδα.",
'summary-preview'           => 'Προεπισκόπηση σύνοψης',
'subject-preview'           => 'Προεπισκόπηση θέματος/επικεφαλίδας',
'blockedtitle'              => 'Ο χρήστης έχει υποστεί φραγή.',
'blockedtext'               => "<big>'''Το όνομα χρήστη σας ή η διεύθυνση IP σας έχει υποστεί φραγή.'''</big>

Η φραγή έγινε από τον/την $1. Η αιτιολογία που δόθηκε είναι: ''$2''.

Λήξη φραγής: $6

Μπορείτε να απευθυνθείτε στον/στην $1 ή σε κάποιον άλλον [[{{MediaWiki:grouppage-sysop}}|διαχειριστή]] για να συζητήσετε τη φραγή.
Δεν μπορείτε να χρησιμοποιήσετε τη δυνατότα «αποστολή e-mail σε αυτό το χρήστη» εκτός αν μια έγκυρη διεύθυνση e-mail έχει οριστεί στις [[Special:Preferences|προτιμήσεις χρήστη]] σας. Η τρέχουσα διεύθυνση IP σας είναι $3, και ο αριθμός αναγνώρισης της φραγής είναι #$5. Παρακαλώ περιλαμβάνετε οποιοδήποτε ή και τα δύο από αυτά σε οποιαδήποτε ερωτήματα σας.",
'autoblockedtext'           => 'Η διεύθυνση IP σας έχει φραγεί αυτόματα επειδή χρησιμοποιήθηκε από έναν άλλο χρήστη, ο οποίος και αποκλείστηκε από τον/την $1.
Ο λόγος που δόθηκε είναι ο εξής:

:\'\'$2\'\'

Λήξη φραγής: $6

Μπορείτε να επικοινωνήσετε με τον/την $1 ή με έναν από τους άλλους
[[{{MediaWiki:grouppage-sysop}}|διαχειριστές]] για να συζητήσετε τη φραγή.

Σημειώστε ότι δεν μπορείτε να χρησιμοποιήσετε το χαρακτηριστικό "στείλτε e-mail σε αυτό τον χρήστη" εκτός αν έχετε μια έγκυρη διεύθυνση ηλεκτρονικού ταχυδρομείου καταχωρημένη στις [[Special:Preferences|προτιμήσεις χρήστη]] σας.

Ο αριθμός αναγνώρισης της φραγής σας είναι $5. Παρακαλώ συμπεριλάβετε αυτό τον αριθμό σε όποια ερωτήματα κάνετε.',
'blockedoriginalsource'     => "Η πηγή του '''$1''' φαίνεται παρακάτω:",
'blockededitsource'         => "Το κείμενο των '''επεξεργασιών σας''' στο '''$1''' φαίνεται παρακάτω:",
'whitelistedittitle'        => 'Για να επεξεργαστείτε μια σελίδα πρέπει πρώτα να συνδεθείτε.',
'whitelistedittext'         => 'Πρέπει να $1 για να επεξεργαστείτε σελίδες.',
'whitelistreadtitle'        => 'Για να διαβάσετε πρέπει πρώτα να συνδεθείτε.',
'whitelistreadtext'         => 'Πρέπει να [[Special:Userlogin|συνδεθείτε]] για να διαβάσετε σελίδες.',
'whitelistacctitle'         => 'Δεν έχετε το δικαίωμα να δημιουργήσετε λογαριασμό.',
'whitelistacctext'          => 'Για να σας επιτραπεί η δημιουργία λογαριασμού σε αυτό το Wiki πρέπει να [[Special:Userlogin|συνδεθείτε]] και να κατέχετε την κατάλληλη άδεια.',
'confirmedittitle'          => 'Απαιτείται επιβεβαίωση e-mail για την επεξεργασία',
'confirmedittext'           => 'Πρέπει να επιβεβαιώσετε την διεύθυνση e-mail σας πριν μπορέσετε να επεξεργαστείτε σελίδες. Παρακαλώ θέστε και επικυρώστε την διεύθυνση e-mail σας μέσω των [[Special:Preferences|προτιμήσεων χρήστη]] σας.',
'nosuchsectiontitle'        => 'Δεν υπάρχει τέτοια ενότητα',
'nosuchsectiontext'         => 'Προσπαθήσατε να επεξεργαστείτε μια ενότητα η οποία δεν υπάρχει. Εφόσον δεν υπάρχει ενότητα $1, δεν υπάρχει κάποιο μέρος για να αποθηκεύσετε την επεξεργασία σας.',
'loginreqtitle'             => 'Απαιτείται η σύνδεση του χρήστη.',
'loginreqlink'              => 'συνδεθείτε',
'loginreqpagetext'          => 'Πρέπει να $1 για να δείτε άλλες σελίδες.',
'accmailtitle'              => 'Ο κωδικός έχει σταλεί.',
'accmailtext'               => "Ο κωδικός για τον/την '$1' έχει σταλεί στο $2.",
'newarticle'                => '(Νέο)',
'newarticletext'            => "Έχετε ακολουθήσει ένα σύνδεσμο που δεν υπάρχει ακόμα. Για να δημιουργήσετε μια νέα σελίδα εδώ, αρχίστε να γράφετε το κείμενό σας στο πλαίσιο.(Βλ. [[{{MediaWiki:helppage}}|Σελίδα βοήθειας]] για περισσότερες πληροφορίες).
Αν έχετε βρεθεί εδώ κατά λάθος, απλώς πατήστε '''επιστροφή (back)''' στον browser του υπολογιστή σας.",
'anontalkpagetext'          => "----''Αυτή η σελίδα συζήτησης προορίζεται για ανώνυμους χρήστες που δεν έχουν δημιουργήσει ακόμα λογαριασμό (ή που δεν τον χρησιμοποιούν). Έτσι για την ταυτοποίηση ενός ανώνυμου χρήστη χρησιμοποιείται η [[{{ns:12}}:διεύθυνση IP  |διεύθυνση IP ]] του. Είναι όμως πιθανόν η διεύθυνση αυτή να είναι κοινή για πολλούς διαφορετικούς χρήστες (όπως π.χ. για τους χρήστες ενός Internet Cafe ή ενός [[{{ns:12}}:proxy server|proxy server]]). Αν είστε ανώνυμος χρήστης και έχετε δεχθεί σχόλια άσχετα με τα θέματά σας (κάτι που μπορεί να συμβεί αν χρησιμοποιείτε την ίδια [[{{ns:12}}:διεύθυνση IP|διεύθυνση IP]] με κάποιον άλλο ανώνυμο χρήστη) θα ήταν καλό να [[Special:Userlogin|δημιουργήσετε ένα λογαριασμό χρήστη ή να συνδεθείτε]] για να αποφεύγεται η σύγχυση''.",
'noarticletext'             => '(Δεν υπάρχει κείμενο στη σελίδα)',
'clearyourcache'            => "'''Σημείωση:''' Μετά την αποθήκευση, θα χρειαστεί να καθαρίσετε  το cache στον browser για να μπορέσετε να δείτε τις αλλαγές: '''Mozilla:''' click ''Reload'' (or ''Ctrl-R''), '''IE / Opera:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Ctrl-R''.<br />",
'usercssjsyoucanpreview'    => '<strong>Χρήσιμη συμβουλή:</strong> Χρησιμοποιήστε το κουμπί "Προεπισκόπηση" για να ελέγξτε τα νέα σας CSS/JS πριν τα αποθηκεύσετε.',
'usercsspreview'            => "'''Σας υπενθυμίζουμε ότι κάνετε απλώς έλεγχο/προεπισκόπηση του CSS του χρήστη -δεν το έχετε ακόμα αποθηκεύσει! '''",
'userjspreview'             => "'''Σας υπενθυμίζουμε ότι κάνετε απλώς έλεγχο/προεπισκόπηση του JavaScript του χρήστη -δεν το έχετε ακόμα αποθηκεύσει!'''",
'userinvalidcssjstitle'     => "'''Προσοχή:''' Δεν υπάρχει skin με τίτλο \"\$1\". Θυμηθείτε οι προσαρμοσμένες σελίδε .css και .js χρησιμοποιούν ένα τίτλο με μικρά γράμματα, π.χ. {{ns:user}}:Foo/monobook.css σε αντίθεση με το {{ns:user}}:Foo/Monobook.css.",
'updated'                   => '(Ενημερώθηκε.)',
'note'                      => '<strong>Προσοχή: </strong>',
'previewnote'               => 'Σας υπενθυμίζουμε ότι βλέπετε μόνον την προεπισκόπηση -δεν έχετε ακόμα αποθηκεύσει τις αλλαγές σας!',
'previewconflict'           => 'Αυτή η προεπισκόπηση απεικονίζει το κείμενο στην επάνω περιοχή επεξεργασίας κειμένου, όπως θα εμφανιστεί εάν επιλέξετε να το αποθηκεύσετε.',
'session_fail_preview'      => '<strong>Συγγνώμη! Δεν μπορούσαμε να διεκπεραιώσουμε την επεξεργασία σας λόγω απώλειας των δεδομένων της συνεδρίας.
Παρακαλώ προσπαθήστε ξανά. Αν δεν δουλεύει ξανά, δοκιμάστε να αποσυνδθείτε και να συνδεθείτε πάλι.</strong>',
'session_fail_preview_html' => "<strong>Συγγνώμη! Δεν μπορούσαμε να διεκπεραιώσουμε την επεξεργασία σας λόγω απώλειας των δεδομένων της συνεδρίας.</strong>

''Επειδή αυτό το wiki έχει ενεργοποιημένη την raw HTML, η προεπισκόπηση είναι κρυμμένη ως προφύλαξη ενάντια σε επιθέσεις Javascript.''

<strong>Αν αυτή είναι μια έγκυρη προσπάθεια επεξεργασίας, παρακαλώ προσπαθήστε ξανά. Αν πάλι δε δουλεύει, δοκιμάστε να αποσυνδεθείτε και να συνδεθείτε πάλι.</strong>",
'token_suffix_mismatch'     => '<strong>Η επεξεργασία σας απορρίφθηκε γιατί το πρόγραμμα-πελάτη σας κατακρεούργησε τους χαρακτήρες στίξης στο κουπόνι επεξεργασίας. Η επεξεργασία απορρίφθηκε για να αποφευχθεί η παραφθορά του κειμένου της σελίδας. 
Αυτό μερικές φορές συμβαίνει όταν χρησιμοποιείται ένας ανώνυμος διακομιστής μεσολάβησης διαθέσιμος μέσω του παγκόσμιου ιστού με σφάλματα.</strong>',
'editing'                   => 'Επεξεργασία $1',
'editinguser'               => 'Επεξεργασία $1',
'editingsection'            => 'Επεξεργασία $1 (τμήμα)',
'editingcomment'            => 'Επεξεργασία $1 (σχόλια)',
'editconflict'              => 'Ανταγωνιστικές επεξεργασίες: $1',
'explainconflict'           => 'Κάποιος άλλος χρήστης έχει αλλάξει αυτή τη σελίδα από τότε που αρχίσατε να την επεξεργάζεστε. Στο επάνω τμήμα βρίσκεται το τρέχον κείμενο της σελίδας. Οι δικές σας αλλαγές εμφανίζονται στο κάτω τμήμα. Θα πρέπει να ενσωματώσετε εσείς τις αλλαγές σας στο τρέχον κείμενο. <b>Μόνον</b> το επάνω τμήμα θα αποθηκευθεί όταν πατήσετε "Αποθήκευση σελίδας".<p>',
'yourtext'                  => 'Το κείμενό σας',
'storedversion'             => 'Αποθηκευμένη έκδοση',
'nonunicodebrowser'         => '<strong>ΠΡΟΣΟΧΗ! Ο browser σας δεν είναι συμβατός με unicode. Παρακαλούμε χρησιμοποιήστε έναν άλλο browser για επεξεργαστείστε αυτό το άρθρο έτσι ώστε να αποδοθούν σωστά όλοι οι ελληνικοί χαρακτήρες. </strong><br />',
'editingold'                => '<strong>ΠΡΟΕΙΔΟΠΟΙΗΣΗ: Επεξεργάζεστε μια παλιότερη αναθεώρηση αυτής της σελίδας. Αν προσπαθείστε να την αποθηκεύσετε, όσες αλλαγές έχουν γίνει πριν από αυτή την αναθεώρηση θα χαθούν.</strong><br />',
'yourdiff'                  => 'Διαφορές',
'copyrightwarning'          => 'Ολες οι προσθήκες/ αλλαγές στο {{SITENAME}} θα πρέπει να συμφωνούν με την $2 (Βλ. $1 για λεπτομέρειες).
Αν δεν επιθυμείτε τα κείμενά σας να τα επεξεργαστούν κατά την κρίση τους άλλοι χρήστες και να τα διαδώσουν κατά βούληση παρακαλούμε να μην τα αναρτήσετε σε αυτό το χώρο. Ότι συνεισφέρετε στο χώρο αυτό σε κείμενα, διαγράμματα, στοιχεία ή εικόνες πρέπει να είναι δικά σας έργα ή να ανήκουν στο δημόσιο τομέα (public domain) ή να προέρχονται από ελεύθερες ή ανοιχτές πηγές με ρητή άδεια αναδημοσίευσης. <br />

Τέλος μας υπόσχεστε και δηλώνετε πως ότι γράφετε σε αυτό τον χώρο είναι πρωτότυπο δικό σας έργο και, άσχετα με την έκτασή του, δεν έχει εκχωρηθεί σε τρίτους η δημοσίευση και η εκμετάλλευσή του.
<strong>ΠΑΡΑΚΑΛΟΥΜΕ ΝΑ ΜΗΝ ΑΝΑΡΤΗΣΕΤΕ ΚΕΙΜΕΝΑ ΤΡΙΤΩΝ ΕΑΝ ΔΕΝ ΕΧΕΤΕ ΤΗΝ ΑΔΕΙΑ ΤΟΥ ΙΔΙΟΚΤΗΤΗ ΤΟΥ COPYRIGHT!</strong>',
'copyrightwarning2'         => 'Σημειώστε ότι όλες οι συνεισφορές στον ιστότοπο {{SITENAME}} μπορούν να υποστούν επεξεργασία, να αλλαχθούν, ή να αφαιρεθούν από άλλους συνεισφέροντες. Αν δεν θέλετε τα γραπτά σας να υποστούν επεξεργασία κατά βούληση, τότε μην τα τοποθετήσετε σε αυτό το χώρο.<br />
Επίσης μας υπόσχεστε ότι γράφετε είναι δικό σας, ή αντιγραμμένο από μια πηγή που είναι κοινό κτήμα, ή μια παρόμοια ελεύθερη πηγή (δείτε $1 για λεπτομέρειες).
<strong>ΠΑΡΑΚΑΛΟΥΜΕ ΝΑ ΜΗΝ ΤΟΠΟΘΕΤΕΙΤΕ ΠΝΕΥΜΑΤΙΚΑ ΚΑΤΟΧΥΡΩΜΕΝΟ ΕΡΓΟ ΧΩΡΙΣ ΑΔΕΙΑ!</strong>',
'longpagewarning'           => 'ΠΡΟΕΙΔΟΠΟΙΗΣΗ: Η σελίδα έχει μέγεθος $1kb. Είναι πιθανόν μερικοί browser να παρουσιάσουν προβλήματα στην επεξεργασία σελίδων της τάξης των 32kb και άνω. Μπορείτε να αποφύγετε το πρόβλημα κόβωντας τη σελίδα σε μικρότερα τμήματα.<br />',
'longpageerror'             => '<strong>ΣΦΑΛΜΑ: Το κείμενο που αποστείλατε έχει μήκος $1 κιλομπάιτ,
το οποίο είναι μεγαλύτερο από το μέγιστο των $2 κιλομπάιτ. Δεν μπορεί να αποθηκευτεί.</strong>',
'readonlywarning'           => 'ΠΡΟΕΙΔΟΠΟΙΗΣΗ: Η βάση δεδομένων έχει κλειδωθεί προσωρινά για συντήρηση και δεν θα μπορέσετε να αποθηκεύσετε αυτά που έχετε επεξεργαστεί.  Μπορείτε αν θέλετε να αποθηκεύσετε το κείμενο στον υπολογιστή σας (με αποκοπή-και-επικόλληση) και να το ξαναχρησιμοποιήσετε αργότερα όταν η συντήρηση θα έχει ολοκληρωθεί.',
'protectedpagewarning'      => 'ΠΡΟΕΙΔΟΠΟΙΗΣΗ:  Η σελίδα αυτή έχει κλειδωθεί -η οποιαδήποτε επεξεργασία της μπορεί να γίνει μόνον από διαχειριστές. Βεβαιωθείτε πως ακολουθείτε [[{{ns:4}}:Σελίδες_υπό_προστασία|τους κανόνες για τις υπό προστασία σελίδες]].<br />',
'semiprotectedpagewarning'  => "'''Σημείωση:''' Αυτή η σελίδα έχει κλειδωθεί ώστε μόνο εγγεγραμμένοι χρήστες να μπορούν να την επεξεργαστούν.",
'cascadeprotectedwarning'   => "'''Προσοχή:''' Αυτή η σελίδα έχει κλειδωθεί ώστε μόνο χρήστες με δικαιώματα διαχειριστή συστήματος (sysop) να μπορούν να την επεξεργαστούν, επειδή περιλαμβάνεται {{PLURAL:$1|στην|στις}} {{PLURAL:$1|ακόλουθη|ακόλουθες}} διαδοχικά (cascaded) {{PLURAL:$1|προστατευμένη|προστατευμένες}} {{PLURAL:$1|σελίδα|σελίδες}}:",
'templatesused'             => 'Πρότυπα που χρησιμοποιήθηκαν στη σελίδα αυτή:',
'templatesusedpreview'      => 'Πρότυπα που χρησιμοποιούνται σε αυτή την προεπισκόπηση:',
'templatesusedsection'      => 'Πρότυπα που χρησιμοποιούνται σε αυτή την ενότητα:',
'template-protected'        => '(προστατευμένη)',
'template-semiprotected'    => '(ημιπροστατευμένη)',
'edittools'                 => '<!-- Το κείμενο εδώ θα φαίνεται κάτω από τις φόρμες επεξεργασίας και επιφόρτωσης. -->',
'nocreatetitle'             => 'Περιορισμένη δημιουργία σελίδων',
'nocreatetext'              => 'Αυτός ο ιστότοπος έχει περιορίσει την ικανότητα δημιουργίας νέων σελίδων.
Μπορείτε να πάτε πίσω και να επεξεργαστείτε μια υπάρχουσα σελίδα, να [[Special:Userlogin|συνδεθείτε ή να δημιουργήσετε ένα λογαριασμό]].',
'nocreate-loggedin'         => 'Δεν έχετε άδεια να δημιουργήσετε νέες σελίδες σε αυτό το βίκι.',
'permissionserrors'         => 'Σφάλματα άδειας.',
'permissionserrorstext'     => 'Δεν έχετε άδεια να το κάνετε αυτό, για τους εξής {{PLURAL:$1|reason|λόγους}}:',
'recreate-deleted-warn'     => "'''Προειδοποίηση: Ξαναδημιουργήτε μια σελίδα που είχε προηγουμένως διαγραφεί.'''

Θα πρέπει να σκεφτείτε αν θα έπρεπε να συνεχίσετε να επεξεργάζεστε αυτή τη σελίδα.
Το αρχείο διαγραφής δίνεται εδώ για διευκόλυνση:",

# "Undo" feature
'undo-success' => 'Η επεξεργασία μπορεί να αναστραφεί. Παρακαλώ ελέγξτε την σύγκριση παρακάτω για να επιβεβαιώσετε ότι αυτό είναι το οποίο θέλετε να κάνετε, και έπειτα αποθηκεύστε τις αλλαγές παρακάτω για να τελειώσετε την αναστροφή της επεξεργασίας.',
'undo-failure' => 'Η επεξεργασία δεν μπορούσε να αναστραφεί λόγω αντικρουόμενων ενδιάμεσων επεξεργασιών.',
'undo-summary' => 'Αναίρεση αναθεώρησης $1 υπό τον/την [[Special:Contributions/$2|$2]] ([[Συζήτηση χρήστη:$2|Συζήτηση]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ο λογαριασμός δεν μπορεί να δημιουργηθεί',
'cantcreateaccount-text' => "Η δημιουργία λογαριασμού από αυτή τη διεύθυνση IP (<b>$1</b>) έχει αποτραπεί από τον [[User:$3|$3]].

Ο λόγος που δόθηκε από τον $3 είναι ''$2''",

# History pages
'revhistory'          => 'Ιστορικό αναθεωρήσεων',
'viewpagelogs'        => 'Δείτε τα αρχεία καταγραφών για αυτή τη σελίδα',
'nohistory'           => 'Δεν υπάρχει ιστορικό επεξεργασίας για αυτή τη σελίδα.',
'revnotfound'         => 'Η αναθεώρηση δεν βρέθηκε.',
'revnotfoundtext'     => 'Η παλιά αναθεώρηση της σελίδας που ζητήσατε δεν ήταν δυνατόν να βρεθεί. Παρακαλούμε ελέγξτε τo URL που χρησιμοποιήσατε για να φτάσετε σε αυτήν τη σελίδα.',
'loadhist'            => 'Φόρτωση ιστορικού σελίδας',
'currentrev'          => 'Τρέχουσα αναθεώρηση',
'revisionasof'        => 'Αναθεώρηση της $1',
'revision-info'       => 'Έκδοση στις $1 υπό τον/την $2',
'previousrevision'    => '&larr;Παλιότερη αναθεώρηση',
'nextrevision'        => 'Νεώτερη αναθεώρηση&rarr;',
'currentrevisionlink' => 'εμφάνιση της τρέχουσας αναθεώρησης',
'cur'                 => 'τρέχουσα',
'next'                => 'επόμενη',
'last'                => 'τελευταία',
'orig'                => "'πρωτότυπη'",
'page_first'          => 'πρώτη',
'page_last'           => 'τελευταία',
'histlegend'          => 'Σύγκριση διαφορών: Επιλέξτε τις εκδόσεις που θέλετε να συγκρίνετε και πατήστε enter ή κάντε κλικ στην μπάρα "Σύγκριση...". <br />
Υπόμνημα: (τρέχον) = διαφορές με την τρέχουσα έκδοση,
(τελευταίο) = διαφορές με την προηγούμενη έκδοση, μ = αλλαγές μικρής κλίμακας.',
'deletedrev'          => '[διαγράφτηκε]',
'histfirst'           => 'Η πιο παλιά',
'histlast'            => 'Η πιο πρόσφατη',
'historysize'         => '($1 μπάιτ)',
'historyempty'        => '(άδειο)',

# Revision feed
'history-feed-title'          => 'Ιστορικό εκδόσεων',
'history-feed-description'    => 'Ιστορικό αναθεωρήσεων για αυτή τη σελίδα στο wiki',
'history-feed-item-nocomment' => '$1 στο $2', # user at time
'history-feed-empty'          => 'Η ζητούμενη σελίδα δεν υπάρχει.
Μπορεί να έχει διαγραφεί από το wiki, ή να μετονομάστηκε.
Δοκιμάστε [[Special:Search|να αναζητήσετε στο wiki]] για σχετικές νέες σελίδες.',

# Revision deletion
'rev-deleted-comment'         => '(σχόλιο αφαιρέθηκε)',
'rev-deleted-user'            => '(όνομα χρήστη αφαιρέθηκε)',
'rev-deleted-event'           => '(καταχώριση αφαιρέθηκε)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Αυτή η αναθεώρηση σελίδας έχει αφαιρεθεί από τα δημόσια αρχεία.
Μπορεί να υπάρχουν λεπτομέρειες στο [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} αρχείο καταγραφής διαγραφών].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Αυτή η αναθεώρηση σελίδας έχει αφαιρεθεί από τα δημόσια αρχεία.
Ως διαχειριστής σε αυτόν τον ιστότοπο μπορείτε να τη δείτε.
Μπορεί να υπάρχουν λεπτομέρειες στο [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} αρχείο καταγραφής διαγραφών].
</div>',
'rev-delundel'                => 'εμφάνισε/κρύψε',
'revisiondelete'              => 'Διέγραψε/επανέφερε αναθεωρήσεις',
'revdelete-nooldid-title'     => 'Καμία αναθεώρηση-στόχος',
'revdelete-nooldid-text'      => 'Δεν έχετε ορίσει αναθεωρήσεις-στόχους για να εκτελεστεί η λειτουργία σε αυτές.',
'revdelete-selected'          => "{{PLURAL:$2|Επιλεγμένη αναθεώρηση|Επιλεγμένες αναθεωρήσεις}} της '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Επιλεγμένο γεγονός αρχείου καταγραφής|Επιλεγμένα γεγονότα αρχείου καταγραφής}} για '''$1:'''",
'revdelete-text'              => 'Οι διεγραμμένες αναθεωρήσεις και τα γεγονότα θα εμφανίζονται ακόμα στο ιστορικό της σελίδας και στα αρχεία καταγραφών,
αλλά μέρη του περιεχομένου τους θα είναι απροσπέλαστα στο κοινό.

Άλλοι διαχειριστές σε αυτό το wiki θα είναι ακόμα ικανοί να προσπελάσουν το κρυμμένο περιεχόμενο και μπορούν
να το επαναφέρουν ξανά μέσω αυτής της διεπαφής, εκτός αν τεθούν πρόσθετοι περιορισμοί.',
'revdelete-legend'            => 'Θέστε περιορισμούς:',
'revdelete-hide-text'         => 'Κρύψε κείμενο αναθεώρησης',
'revdelete-hide-name'         => 'Κρύψε ενέργεια και στόχο',
'revdelete-hide-comment'      => 'Κρύψε σχόλιο επεξεργασίας',
'revdelete-hide-user'         => 'Κρύψε όνομα χρήστη/IP συντάκτη',
'revdelete-hide-restricted'   => 'Εφάρμοσε αυτούς τους περιορισμούς σε διαχειριστές όπως και σε άλλους',
'revdelete-suppress'          => 'Απέκρυψε δεδομένα από διαχειριστές όπως και από άλλους',
'revdelete-hide-image'        => 'Κρύψε περιεχόμενο αρχείου',
'revdelete-unsuppress'        => 'Αφαίρεσε περιορισμούς στις αποκατεστημένες αναθεωρήσεις',
'revdelete-log'               => 'Κατέγραψε σχόλιο:',
'revdelete-submit'            => 'Εφάρμοσε στην επιλεγμένη αναθεώρηση',
'revdelete-logentry'          => 'η ορατότητα της αναθεώρησης του [[$1]] αλλάχθηκε',
'logdelete-logentry'          => 'η ορατότητα γεγονότος του [[$1]] αλλάχθηκε',
'revdelete-logaction'         => '$1 {{PLURAL:$1|αναθεώρηση τέθηκε|αναθεωρήσεις τέθηκαν}} σε κατάσταση $2',
'logdelete-logaction'         => '$1 {{PLURAL:$1|γεγονός|γεγονότα}} σε [[$3]] {{PLURAL:$1|τέθηκε|τέθηκαν}} σε κατάσταση $2',
'revdelete-success'           => 'Ορατότητα αναθεώρησης τέθηκε επιτυχώς.',
'logdelete-success'           => 'Ορατότητα γεγονότος τέθηκε επιτυχώς.',

# Oversight log
'oversightlog'    => 'Αρχείο καταγραφής παραδρομών',
'overlogpagetext' => 'Παρακάτω είναι μια λίστα με τις πιο πρόσφατες διαγραφές και φραγές που περιλαμβάνουν περιεχόμενο
κρυμμένο από τους Sysops. Δείτε τη [[Special:Ipblocklist|λίστα φραγών IP]] για τη λίστα με τις τρέχουσες φραγές και αποκλεισμούς',

# Diffs
'history-title'             => 'Ιστορικό αλλαγών για τη σελίδα "$1"',
'difference'                => '(Διαφορές μεταξύ αναθεωρήσεων)',
'loadingrev'                => "φόρτωση αναθεώρησης για 'σύγκριση'",
'lineno'                    => 'Γραμμή $1:',
'editcurrent'               => 'Επεξεργασία της τρέχουσας έκδοσης της σελίδας',
'selectnewerversionfordiff' => 'Επιλέξτε μια πιο πρόσφατη έκδοση για σύγκριση.',
'selectolderversionfordiff' => 'Επιλέξτε μια παλιότερη έκδοση για σύγκριση.',
'compareselectedversions'   => 'Σύγκριση των εκδόσεων που έχουν επιλεγεί',
'editundo'                  => 'αναίρεση',
'diff-multi'                => '({{PLURAL:$1|Μία ενδιάμεση αναθεώρηση|$1 ενδιάμεσες αναθεωρήσεις}} δεν εμφανίζονται.)',

# Search results
'searchresults'         => 'Αποτελέσματα αναζήτησης',
'searchresulttext'      => 'Για περισσότερες πληροφορίες σχετικά με την αναζήτηση στο {{SITENAME}}, βλ. [[{{MediaWiki:helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Κριτήρια αναζήτησης: "[[:$1]]"',
'searchsubtitleinvalid' => 'Κριτήρια αναζήτησης: "$1"',
'noexactmatch'          => "'''Δεν υπάρχει καμία σελίδα με τίτλο «$1».''' Μπορείτε να [[:$1|δημιουργήσετε αυτή τη σελίδα]].",
'titlematches'          => 'Τίτλοι άρθρων που ανταποκρίνονται',
'notitlematches'        => 'Δεν υπάρχουν αντίστοιχοι τίτλοι σελίδων.',
'textmatches'           => 'Κείμενα σελίδων που ανταποκρίνονται:',
'notextmatches'         => 'Δεν υπάρχουν αντίστοιχα κείμενα σελίδων.',
'prevn'                 => '$1 προηγουμένων',
'nextn'                 => '$1 επομένων',
'viewprevnext'          => 'Εμφάνιση ($1) ($2) ($3).',
'showingresults'        => 'Εμφάνιση <b>$1</b> αποτελεσμάτων που αρχίζουν με #<b>$2</b>',
'showingresultsnum'     => 'Εμφάνιση <b>$3</b> αποτελεσμάτων που αρχίζουν με #<b>$2</b>',
'nonefound'             => "'''Σημείωση''': Οι ανεπιτυχείς αναζητήσεις οφείλονται συνήθως στο ότι έχουμε συμπεριλάβει στα κριτήρια πολύ συνηθισμένες λέξεις, όπως \"έχει\" ή \"από\" (που δεν υπάρχουν στο ευρετήριο) ή στο ότι προκαθορίσαμε πολλά κριτήρια αναζήτησης ταυτοχρόνως. (Στην τελευταία περίπτωση, μόνον οι σελίδες που περιέχουν ''όλα'' τα κριτήρια αναζήτησης θα εμφανιστούν στα αποτελέσματα).<br />",
'powersearch'           => 'Αναζήτηση',
'powersearchtext'       => 'Αναζήτηση στις περιοχές :<br />

$1<br />
$2 Εμφάνιση ανακατευθύνσεων &nbsp; Κριτήρια αναζήτησης $3 $9',
'searchdisabled'        => 'Η αναζήτηση για τον ιστότοπο "{{SITENAME}}" είναι απενεργοποιημένη. Μπορείτε να αναζητήσετε μέσω του Google εν τω μεταξύ. Σημειώστε ότι οι κατάλογοί τους για το περιεχόμενο του ιστοτόπου "{{SITENAME}}" μπορεί να είναι απαρχαιωμένοι.',

# Preferences page
'preferences'              => 'Προτιμήσεις',
'mypreferences'            => 'Οι προτιμήσεις μου',
'prefs-edits'              => 'Αριθμός επεξεργασιών:',
'prefsnologin'             => 'Δεν έχετε συνδεθεί.',
'prefsnologintext'         => 'Πρέπει να έχετε [[Special:Userlogin|συνδεθεί]]
για να καθορίσετε τις προτιμήσεις χρήστη.',
'prefsreset'               => 'Οι προτιμήσεις σας έχουν αποκατασταθεί σύμφωνα με την αποθηκευμένη έκδοσή τους.',
'qbsettings'               => 'Γρήγορη πρόσβαση',
'qbsettings-none'          => 'Καμία',
'qbsettings-fixedleft'     => 'Σταθερά αριστερά',
'qbsettings-fixedright'    => 'Σταθερά δεξιά',
'qbsettings-floatingleft'  => 'Πλανώμενα αριστερά',
'qbsettings-floatingright' => 'Πλανώμενα δεξιά',
'changepassword'           => 'Αλλαγή κωδικού',
'skin'                     => 'Οπτική οργάνωση (skin)',
'math'                     => 'Απόδοση μαθηματικών',
'dateformat'               => 'Μορφή ημερομηνίας',
'datedefault'              => 'Χωρίς προτίμηση',
'datetime'                 => 'Ημερομηνία και ώρα',
'math_failure'             => 'Δεν μπόρεσε να γίνει ανάλυση του όρου.',
'math_unknown_error'       => 'Άγνωστο λάθος',
'math_unknown_function'    => 'άγνωστη συνάρτηση',
'math_lexing_error'        => 'Σφάλμα στην λεξική ανάλυση',
'math_syntax_error'        => 'Λάθος σύνταξης',
'math_image_error'         => 'Η μετατροπή σε PNG απέτυχε. Παρακαλούμε ελέγξτε ότι έχουν εγκατασταθεί σωστά τα latex, dvips, gs, και ξαναπροσπαθήστε!',
'math_bad_tmpdir'          => 'Δεν είναι δυνατή η δημιουργία μαθηματικών δεδομένων (ή η εγγραφή σε προσωρινό κατάλογο)',
'math_bad_output'          => 'Δεν είναι δυνατή η δημιουργία  μαθηματικών δεδομένων (ή η εγγραφή σε κατάλογο εξόδου)',
'math_notexvc'             => 'Αγνοείται το εκτελέσιμο texvc -παρακαλούμε συμβουλευτείτε το math/README για να ρυθμίσετε τις παραμέτρους.',
'prefs-personal'           => 'Στοιχεία χρήστη',
'prefs-rc'                 => 'Πρόσφατες αλλαγές και εμφάνιση πολύ σύντομων άρθρων',
'prefs-watchlist'          => 'Λίστα παρακολούθησης',
'prefs-watchlist-days'     => 'Αριθμών ημερών προς εμφάνιση στη λίστα παρακολούθησης:',
'prefs-watchlist-edits'    => 'Αριθμός επεξεργασιών προς εμφάνιση στην εκτεταμένη λίστα παρακολούθησης:',
'prefs-misc'               => 'Διάφορες ρυθμίσεις',
'saveprefs'                => 'Αποθήκευση προτιμήσεων',
'resetprefs'               => 'Επαναφορά προτιμήσεων',
'oldpassword'              => 'Παλιός κωδικός',
'newpassword'              => 'Νέος κωδικός πρόσβασης',
'retypenew'                => 'Πληκτρολογήστε ξανά το νέο κωδικό.',
'textboxsize'              => 'Επεξεργασία',
'rows'                     => 'Σειρές',
'columns'                  => 'Στήλες',
'searchresultshead'        => 'Αποτελέσματα αναζήτησης/Ρυθμίσεις',
'resultsperpage'           => 'Αποτελέσματα ανά σελίδα',
'contextlines'             => 'Σειρές που θα εμφανίζονται ανά αποτέλεσμα',
'contextchars'             => 'Αριθμός χαρακτήρων στο εμφανιζόμενο κείμενο',
'stub-threshold'           => 'Κατώφλι για μορφοποίηση <span class="mw-stub-example">συνδέσμου επεκτάσιμου</span>:',
'recentchangesdays'        => 'Ημέρες προς εμφάνιση στις πρόσφατες αλλαγές:',
'recentchangescount'       => 'Αριθμός τίτλων στις πρόσφατες αλλαγές',
'savedprefs'               => 'Οι προτιμήσεις σας έχουν αποθηκευθεί.',
'timezonelegend'           => 'Ζώνη ώρας (Time zone)',
'timezonetext'             => 'Συμπληρώστε τον αριθμό των ωρών κατά τις οποίες η τοπική σας ώρα διαφέρει από την ώρα του server (UTC).',
'localtime'                => 'Εμφάνιση τοπικής ώρας',
'timezoneoffset'           => 'Διαφορά ωρών',
'servertime'               => 'Η ώρα του server είναι:',
'guesstimezone'            => 'Συμπλήρωση μέσω του browser',
'allowemail'               => 'Ενεργοποίηση παραλαβής e-mail από άλλους χρήστες',
'defaultns'                => 'Προκαθορισμένη αναζήτηση στις περιοχές:',
'default'                  => 'Προκαθορισμένο',
'files'                    => 'Αρχεία',

# User rights
'userrights-lookup-user'      => 'Διαχείριση ομάδων χρηστών',
'userrights-user-editname'    => 'Δηλώστε όνομα χρήστη:',
'editusergroup'               => "Επεξεργασία 'Ομάδα Χρηστών'",
'userrights-editusergroup'    => 'Επεξεργασία ομάδων χρηστών',
'saveusergroups'              => 'Αποθήκευση ομάδων χρηστών',
'userrights-groupsmember'     => 'Μελος της ομάδας:',
'userrights-groupsavailable'  => 'Υπάρχουσες ομάδες:',
'userrights-groupshelp'       => 'Επιλέξτε όμάδες στις οποίες επιθυμείτε να προστεθεί ο χρήστης ή ομάδες από τις οποίες επιθυμείτε να αφαιρεθεί ο χρήστης. Μπορείτε να αναιρέσετε την επιλογή μιας ομάδας με το πλήκτο CTRL + αριστερό κλικ',
'userrights-reason'           => 'Λόγος για αλλαγή:',
'userrights-available-none'   => 'Δεν μπορείτε να τροποποιήσετε τη συμμετοχή στην ομάδα μελών.',
'userrights-available-add'    => 'Μπορείτε να προσθέσετε χρήστες στο $1.',
'userrights-available-remove' => 'Μπορείτε να αφαιρέσετε χρήστες από το $1.',

# Groups
'group'               => 'Ομάδα:',
'group-autoconfirmed' => 'Αυτοεπιβεβαιωμένοι χρήστες',
'group-bot'           => 'Bots',
'group-sysop'         => 'Διαχειριστές',
'group-bureaucrat'    => 'Γραφειοκράτες',
'group-all'           => '(όλοι)',

'group-autoconfirmed-member' => 'Αυτοεπιβεβαιωμένος χρήστης',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Διαχειριστής συστήματος (Sysop)',
'group-bureaucrat-member'    => 'Γραφειοκράτης',

'grouppage-autoconfirmed' => '{{ns:project}}:Αυτοεπιβεβαιωμένοι χρήστες',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => 'Project:Διαχειριστές',
'grouppage-bureaucrat'    => '{{ns:project}}:Γραφειοκράτες',

# User rights log
'rightslog'      => 'Αρχείο καταγραφών δικαιωμάτων χρηστών',
'rightslogtext'  => 'Καταγραφές των αλλαγών στα δικαιώματα χρηστών.',
'rightslogentry' => 'η ιδιότητα μέλους ομάδας για τον/την $1 από $2 σε $3 άλλαξε',
'rightsnone'     => '(κανένα)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|αλλαγή|αλλαγές}}',
'recentchanges'                     => 'Πρόσφατες αλλαγές',
'recentchangestext'                 => 'Παρακολουθείστε σε αυτή τη σελίδα τις πιο πρόσφατες αλλαγές στο Wiki.',
'recentchanges-feed-description'    => 'Παρακολούθησε τις πιο πρόσφατες αλλαγές στο wiki σε αυτή την περίληψη.',
'rcnote'                            => "Παρακάτω είναι {{PLURAL:$1|'''1''' αλλαγή|οι τελευταίες '''$1''' αλλαγές}} στο διάστημα {{PLURAL:$2|της τελευταίας ημέρας|των τελευταίων '''$2''' ημερών}}, από τις $3 και εξής.",
'rcnotefrom'                        => 'Ακολουθούν οι αλλαγές από <b>$2</b> (εμφάνιση <b>$1</b> αλλαγών max).',
'rclistfrom'                        => 'Εμφάνιση νέων αλλαγών αρχίζοντας από $1',
'rcshowhideminor'                   => '$1 μικρής σημασίας επεξεργασιών',
'rcshowhidebots'                    => '$1 bots',
'rcshowhideliu'                     => '$1 συνδεδεμένων χρηστών',
'rcshowhideanons'                   => '$1 ανωνύμων χρηστών',
'rcshowhidepatr'                    => '$1 επεξεργασιών υπό περιπολία',
'rcshowhidemine'                    => '$1 των επεξεργασιών μου',
'rclinks'                           => 'Εμφάνιση των τελευταίων $1 αλλαγών στο διάστημα των τελευταίων $2 ημερών <br />$3',
'diff'                              => "'διαφορά'",
'hist'                              => "'ιστορικό'",
'hide'                              => 'απόκρυψη',
'show'                              => 'εμφάνιση',
'minoreditletter'                   => 'μ',
'newpageletter'                     => 'Ν',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 χρήστης (-ες) που παρακολουθούν]',
'rc_categories'                     => 'Περιόρισε στις κατηγορίες (διαχωρίστε τις με "|")',
'rc_categories_any'                 => 'Οποιαδήποτε',
'newsectionsummary'                 => '/* $1 */ νέα ενότητα',

# Recent changes linked
'recentchangeslinked'          => 'Σχετικές αλλαγές',
'recentchangeslinked-title'    => 'Αλλαγές σχετικές με το $1',
'recentchangeslinked-noresult' => 'Δεν υπάρχουν αλλαγές στις συνδεδεμένες σελίδες κατά τη διάρκεια της δοσμένης περιόδου.',
'recentchangeslinked-summary'  => "Αυτή η ειδική σελίδα απαριθμεί τις τελευταίες αλλαγές σε σελίδες που είναι σύνδεσμοι. Οι σελίδες στη λίστα παρακολούθησής σας είναι '''έντονα γραμμένες'''.",

# Upload
'upload'                      => 'Φόρτωση αρχείου',
'uploadbtn'                   => 'Φόρτωση αρχείου',
'reupload'                    => 'Επανάληψη φόρτωσης',
'reuploaddesc'                => 'Επιστροφή στη φόρμα φόρτωσης',
'uploadnologin'               => 'Δεν έχετε συνδεθεί!',
'uploadnologintext'           => 'Για να φορτώσετε αρχεία πρέπει πρώτα να [[Special:Userlogin|συνδεθείτε]].',
'upload_directory_read_only'  => 'Δεν είναι δυνατή η εγγραφή στον κατάλογο ($1) από τον server.',
'uploaderror'                 => 'Σφάλμα στη φόρτωση αρχείου',
'uploadtext'                  => "'''ΠΕΡΙΜΕΝΕΤΕ!''' Πριν προχωρήσετε στη φόρτωση αρχείων σε αυτό το χώρο βεβαιωθείτε πως διαβάσατε και πως ακολουθείτε τους [[{{ns:4}}:Κανόνες_χρήσης_εικόνων|Κανόνες χρήσης εικόνων]].

Μπορείτε να δείτε ή να αναζητήσετε εικόνες που έχουν φορτωθεί κατά το παρελθόν κάτω από το σύνδεσμο [[Special:Imagelist|Κατάλογος εικόνων που έχουν φορτωθεί]].
Οι φορτώσεις και οι διαγραφές έχουν καταγραφεί στη σελίδα
[[{{ns:4}}:Καταγραφές_φόρτωσης|Καταγραφές φόρτωσης]].

Χρησιμοποιήστε την παρακάτω φόρμα για να φορτώσετε νέα αρχεία εικόνας που θα χρησιμοποιηθούν στον οπτικό εμπλουτισμό των σελίδων. Στους περισσότερους browsers υπάρχει ένα κουμπί \"Browse...\" το οποίο εμφανίζει το πεδίο διαλόγου του συστήματός σας για το άνοιγμα αρχείων. Αν επιλέξετε ένα αρχείο, το όνομά τoυ θα συμπληρωθέι αυτόματα στο πεδίο κειμένου που βρίσκεται δίπλα στο κουμπί. Μην ξεχάστε να επιβεβαιώσετε (σημειώνοντας το ανάλογο κουτάκι) πως με τη φόρτωση του συγκεκριμένου αρχείου δεν παραβιάζετε πνευματικά δικαιώματα.

Πατήστε το κουμπί \"Upload\" για να ολοκληρωθέι η φόρτωση.
Η διαδικασία μπορεί να διαρκέσει λίγο περισσότερο αν διαθέτετε αργή σύνδεση με το internet.

Οι προτιμώμενες μορφές αρχείου είναι: JPEG για φωτογραφίες, PNG για σχήματα και άλλες εικόνες και OGG για αρχεία ήχου. Δώστε περιγραφικά ονόματα στα αρχεία σας για να αποφευχθεί τυχόν σύγχυση.

Για να συμπεριληφθεί μια εικόνα σε μια σελίδα, χρησιμοποιήστε συνδέσμους της μορφής
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:file.jpg]]</nowiki>''' ή
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:file.png|alt text]]</nowiki>''' ή
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:file.ogg]]</nowiki>''' για αρχεία ήχου.

Σας παρακαλούμε να λάβετε υπ΄όψη σας ότι, ακριβώς όπως συμβαίνει με τις σελίδες Wiki, είναι δυνατόν άλλοι χρήστες να επεξεργαστούν ή να διαγράψουν κατά την κρίση τους αρχεία που έχετε φορτώσει. Έχετε επίσης υπ΄όψη σας πως είναι δυνατόν να υποστείτε φραγή σαν χρήστης αν διαπιστωθεί πως έχετε κάνει κατάχρηση του συστήματος.<br />",
'uploadlog'                   => 'Φόρτωση αρχείου γεγονότων',
'uploadlogpage'               => 'Καταγραφές φόρτωσης',
'uploadlogpagetext'           => 'Παρακάτω είναι ο κατάλογος με τις πιο πρόσφατες αποθηκεύσεις αρχείων.',
'filename'                    => 'Όνομα αρχείου',
'filedesc'                    => 'Σύνοψη',
'fileuploadsummary'           => 'Περιγραφή:',
'filestatus'                  => 'Κατάσταση του copyright',
'filesource'                  => 'Πηγή',
'uploadedfiles'               => 'Αρχεία που έχουν φορτωθεί',
'ignorewarning'               => 'Αγνόησε την προειδοποίηση και αποθήκευσε το αρχείο οποσδήποτε.',
'ignorewarnings'              => 'Αγνόησε οποιεσδήποτε προειδοποιήσεις',
'minlength1'                  => 'Τα ονόματα αρχείων πρέπει να είναι τουλάχιστον ένα γράμμα.',
'illegalfilename'             => 'Το όνομα του αρχείου "$1" περιέχει χαρακτήρες που δεν επιτρέπονται στους τίτλους των σελίδων. Παρακαλούμε δώστε άλλο όνομα στο αρχείο και προσπαθήστε ξανά να το ανεβάσετε.',
'badfilename'                 => 'Το όνομα της εικόνας άλλαξε σε "$1".',
'filetype-badmime'            => 'Αρχεία του τύπου MIME "$1" δεν επιτρέπεται να επιφορτωθούν.',
'filetype-badtype'            => "Ο τύπος αρχείου '''\".\$1\"''' είναι ανεπιθύμητος
: Λίστα επιτρεπόμενων τύπων αρχείων: \$2",
'filetype-missing'            => 'Το αρχείο δεν έχει καμία επέκταση (όπως ".jpg").',
'large-file'                  => 'Προτείνεται τα αρχεία να μην είναι μεγαλύτερα από $1; αυτό το αρχείο είναι $2.',
'largefileserver'             => 'Το μέγεθος αυτού του αρχείο είναι μεγαλύτερο από το μέγιστο μέγεθος που ο εξυπηρετητής είναι ρυθμισμένος να επιτρέπει.',
'emptyfile'                   => 'Το αρχείο που φορτώσατε φαίνεται να είναι κενό. Αυτό μπορεί να οφείλεται σε λάθος πληκτρολόγησης του ονόματος του αρχείου. Παρακαλούμε ελέγξτε εαν αυτό είναι πραγματικά το αρχείο που θέλετε να φορτώσετε.',
'fileexists'                  => 'Υπάρχει ήδη αρχείο με αυτό το όνομα -παρακαλούμε ελέγξτε στο $1. Είστε βέβαιος (-η) πως θέλετε να αλλάξετε το όνομα του αρχείου;',
'fileexists-extension'        => 'Ένα αρχείο με παρόμοιο όνομα υπάρχει:<br />
Όνομα του προς επιφόρτωση αρχείου: <strong><tt>$1</tt></strong><br />
Όνομα υπάρχοντος αρχείου: <strong><tt>$2</tt></strong><br />
Παρακαλώ διαλέξτε ένα διαφορετικό όνομα.',
'fileexists-thumb'            => "'''<center>Υπάρχουσα εικόνα</center>'''",
'fileexists-thumbnail-yes'    => 'Το αρχείο φαίνεται ότι είναι μια εικόνα μειωμένου μεγέθους <i>(μικρογραφία)</i>. Παρακαλώ ελέγξτε το αρχείο <strong><tt>$1</tt></strong>.<br />
Αν το ελεγμένο αρχείο είναι η ίδια εικόνα στο αρχικό μέγεθος δεν είναι απαραίτητο να επιφορτώσετε μια επιπλέον μικρογραφία.',
'file-thumbnail-no'           => 'Το όνομα αρχείου αρχίζει με <strong><tt>$1</tt></strong>. Φαίνεται ότι το αρχείο είναι μια εικόνα μειωμένου μεγέθους <i>(μικρογραφία)</i>.
Αν έχετε αυτή την εικόνα σε πλήρη ανάλυση, επιφορτώστε τη, αλλιώς αλλάξτε παρακαλώ το όνομα του αρχείου.',
'fileexists-forbidden'        => 'Ένα αρχείο με αυτό το όνομα υπάρχει ήδη˙ παρακαλώ πηγαίνετε πίσω και επιφορτώστε αυτό το αρχείο υπό ένα νέο όνομα. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ένα αρχείο με αυτό το όνομα υπάρχει ήδη στο χώρο φύλαξης κοινών αρχείων˙ παρακαλώ πηγαίνετε πίσω και επιφορτώστε αυτό το αρχείο υπό ένα νέο όνομα. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Επιτυχής φόρτωση',
'uploadwarning'               => 'Προειδοιποίηση φόρτωσης',
'savefile'                    => 'Αποθήκευση αρχείου',
'uploadedimage'               => 'Η φόρτωση του "$1" ολοκληρώθηκε.',
'overwroteimage'              => 'ανέβασμα νέας έκδοσης του "[[$1]]"',
'uploaddisabled'              => 'Λυπούμαστε, η φόρτωση έχει απενεργοποιηθεί.',
'uploaddisabledtext'          => 'Οι επιφορτώσεις αρχείων είναι απενεργοποιημένες σε αυτό το wiki.',
'uploadscripted'              => 'Αυτό το αρχείο περιέχει κώδικα HTML ή script που μπορεί να παρερμηνευθεί από μερικούς browser.',
'uploadcorrupt'               => 'Το αρχείο είναι κατεστραμένο ή έχει κάποια λανθασμένη επέκταση. Παρακαλούμε ελέγξτε το και ξαναδοκιμάστε να το ανεβάσετε.',
'uploadvirus'                 => 'Το αρχείο περιέχει ιό! Λεπτομέρειες: $1',
'sourcefilename'              => 'Όνομα πηγαίου αρχείου',
'destfilename'                => 'Όνομα αρχείου προορισμού',
'watchthisupload'             => 'Παρακολουθήστε αυτή τη σελίδα',
'filewasdeleted'              => 'Ένα αρχείο με αυτό το όνομα είχε επιφορτωθεί προηγουμένως και επακολούθως διαγράφηκε. Θα έπρεπε να ελέγξετε το $1 πριν προσπαθήσετε να το επιφορτώσετε ξανά.',
'upload-wasdeleted'           => "'''Προειδοποίηση: Ανεβάζετε ένα αρχείο που είχε προηγουμένως διαγραφεί.'''

Θα πρέπει να σκεφτείτε αν θα έπρεπε να συνεχίσετε να ανεβάζετε αυτό το αρχείο.
Το αρχείο διαγραφής αυτού του αρχείου δίνεται εδώ για διευκόλυνση:",
'filename-bad-prefix'         => 'Το όνομα του αρχείου που ανεβάζετε ξεκινά με <strong>"$1"</strong>, που είναι ένα μη περιγραφικό όνομα που συνήθως εκχωρείται αυτόματα από ψηφιακές φωτογραφικές μηχανές. Παρακαλώ διαλέξτε ένα πιο περιγραφικό όνομα για το αρχείο σας.',

'upload-proto-error'      => 'Λανθασμένο πρωτόκολλο',
'upload-proto-error-text' => 'Η απομακρυσμένη επιφόρτωση απαιτεί URL με πρόθεμα <code>http://</code> ή <code>ftp://</code>.',
'upload-file-error'       => 'Εσωτερικό σφάλμα',
'upload-file-error-text'  => 'Ένα εσωτερικό σφάλμα εμφανίστηκε στην προσπάθεια δημιουργίας ενός προσωρινού αρχείου στον εξυπηρετητή. Παρακαλώ επικοινωνήστε με ένα διαχειριστή του συστήματος.',
'upload-misc-error'       => 'Άγνωστο σφάλμα επιφόρτωσης',
'upload-misc-error-text'  => 'Ένα άγνωστο σφάλμα εμφανίστηκε κατά τη διάρκεια της επιφόρτωσης. Παρακαλώ επιβεβαιώστε ότι το URL είναι έγκυρο και προσβάσιμο, και προσπαθήστε ξανά. Αν το πρόβλημα παραμένει, επικοινωνήστε με ένα διαχειριστή του συστήματος.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Το URL δεν ήταν προσβάσιμο',
'upload-curl-error6-text'  => 'Το παρεχόμενο URL δεν μπόρεσε να προσπελαστεί. Παρακαλώ εξετάστε διπλά, ότι το URL είναι ορθό και ότι ο ιστότοπος είναι διαθέσιμος.',
'upload-curl-error28'      => 'Λήξη χρόνου αναμονής (timeout) για την επιφόρτωση',
'upload-curl-error28-text' => 'Ο ιστότοπος άργησε πολύ να αποκρυθεί. Παρακαλώ ελεγξτε ότι ο ιστότοπος είναι διαθέσιμος, περιμένετε για ένα σύντομο χρονικό διάστημα και προσπαθήστε ξανά. Μπορεί να θέλετε να δοκιμάσετε σε μια λιγότερο πολυσύχναστη ώρα.',

'license'            => 'Άδεια χρήσης',
'nolicense'          => 'Καμία επιλεγμένη',
'license-nopreview'  => '(Μη διαθέσιμη προεπισκόπηση)',
'upload_source_url'  => ' (ένα έγκυρο, δημόσια προσβάσιμο URL)',
'upload_source_file' => ' (ένα αρχείο στον υπολογιστή σας)',

# Image list
'imagelist'                 => 'Κατάλογος εικόνων',
'imagelisttext'             => 'Ακολουθεί κατάλογος $1 εικόνων ταξινομημένων κατά σειρά $2.',
'getimagelist'              => 'Προσκόμιση καταλόγου εικόνων',
'ilsubmit'                  => 'Αναζήτηση',
'showlast'                  => 'Εμφάνιση των $1 πιο πρόσφατων εικόνων κατά σειρά $2.',
'byname'                    => 'ονόματος',
'bydate'                    => 'ημερομηνίας',
'bysize'                    => 'μεγέθους',
'imgdelete'                 => "'διαγραφή'",
'imgdesc'                   => "'περιγραφή'",
'imgfile'                   => 'αρχείο',
'filehist'                  => 'Ιστορικό αρχείου',
'filehist-help'             => 'Πατήστε σε μια ημερομηνία/ώρα για να δείτε το αρχείο όπως εμφανιζόταν εκείνη την ώρα.',
'filehist-deleteall'        => 'διαγραφή όλων',
'filehist-deleteone'        => 'διαγραφή έκδοσης',
'filehist-revert'           => 'αναστροφή',
'filehist-current'          => 'τελευταία',
'filehist-datetime'         => 'Ώρα/Ημερομ.',
'filehist-user'             => 'Χρήστης',
'filehist-dimensions'       => 'Διαστάσεις',
'filehist-filesize'         => 'Μέγεθος',
'filehist-comment'          => 'Σχόλια',
'imagelinks'                => 'Σύνδεσμοι εικόνων',
'linkstoimage'              => 'Οι ακόλουθες σελίδες συνδέονται με αυτή την εικόνα:',
'nolinkstoimage'            => 'Δεν υπάρχουν σελίδες που συνδέονται με αυτήν την εικόνα.',
'sharedupload'              => 'Το αρχείο αυτό φορτώθηκε για κοινή χρήση και είναι δυνατόν να χρησιμοποιείται ταυτοχρόνως σε περισσότερα από ένα έργα.',
'shareduploadwiki'          => 'Παρακαλούμε συμβουλευθείτε την [$1 σελίδα περιγραφής αρχείου] για περισσότερες πληροφορίες.',
'shareduploadwiki-linktext' => 'σελίδα περιγραφής αρχείου',
'noimage'                   => 'Δεν υπάρχει αρχείο με αυτό το όνομα, μπορείτε να [$1 το φορτώσετε].',
'noimage-linktext'          => 'επιφορτώστε το',
'uploadnewversion-linktext' => 'Φορτώστε μια νέα έκδοση αυτού του αρχείου',
'imagelist_date'            => 'Ημερομηνία',
'imagelist_name'            => 'Όνομα',
'imagelist_user'            => 'Χρήστης',
'imagelist_size'            => 'Μέγεθος',
'imagelist_description'     => 'Περιγραφή',
'imagelist_search_for'      => 'Αναζήτηση για όνομα εικόνας:',

# File reversion
'filerevert'                => 'Επαναφορά $1',
'filerevert-legend'         => 'Επαναφορά αρχείου',
'filerevert-intro'          => '<span class="plainlinks">Επαναφέρετε το \'\'\'[[Media:$1|$1]]\'\'\' στην [$4 εκδοχή της $3, $2].</span>',
'filerevert-comment'        => 'Σχόλιο:',
'filerevert-defaultcomment' => 'Αναστράφηκε στην εκδοχή της $2, $1',
'filerevert-submit'         => 'Αναστροφή',
'filerevert-success'        => 'Το <span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' έχει αναστραφεί στην [$4 εκδοχή της $3, $2].</span>',
'filerevert-badversion'     => 'Δεν υπάρχει προηγούμενη τοπική έκδοση αυτού του αρχείου με την χρονική σφραγίδα που παραχωρήθηκε.',

# File deletion
'filedelete'             => 'Διέγραψε $1',
'filedelete-legend'      => 'Διέγραψε το αρχείο',
'filedelete-intro'       => "Διαγράφετε το '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Διαγράφετε την έκδοση του \'\'\'[[Media:$1|$1]]\'\'\' σαν του [$4 $3, $2].</span>',
'filedelete-comment'     => 'Σχόλιο:',
'filedelete-submit'      => 'Διέγραψε',
'filedelete-success'     => "'''$1''' έχει διαγραφεί.",
'filedelete-success-old' => '<span class="plainlinks">Η έκδοση του \'\'\'[[Media:$1|$1]]\'\'\' σαν του $3, $2 έχει διαγραφεί.</span>',
'filedelete-nofile'      => "Το '''$1''' δεν υπάρχει σε αυτή την ιστοσελίδα.",
'filedelete-nofile-old'  => "Δεν υπάρχει καταγεγραμμένη έκδοση του '''$1''' με τα συγκεκριμένα χαρακτηριστικά.",
'filedelete-iscurrent'   => 'Προσπαθείτε να διαγράψετε την πιο πρόσφατη έκδοση αυτού του αρχείου. Παρακαλώ επιστρέψτε σε μια παλαιότερη πρώτα.',

# MIME search
'mimesearch'         => 'Αναζήτηση MIME',
'mimesearch-summary' => 'Αυτή η σελίδα ενεργοποιεί το φιλτράρισμα αρχείων σύμφωνα με τον τύπο MIME τους. Είσοδοςt: contenttype/subtype, π.χ. <tt>image/jpeg</tt>.',
'mimetype'           => 'Τύπος MIME:',
'download'           => 'μεταφόρτωση',

# Unwatched pages
'unwatchedpages' => 'Μη παρακολουθούμενες σελίδες',

# List redirects
'listredirects' => 'Λίστα ανακατευθύνσεων',

# Unused templates
'unusedtemplates'     => 'Αχρησιμοποίητα πρότυπα',
'unusedtemplatestext' => 'Αυτή η σελίδα περιέχει όλες τις σελίδες στην περιοχή ονομάτος των προτύπων, οι οποίες δεν περιλαμβάνονται σε άλλη σελίδα. Θυμηθείτε να ελέγξετε για άλλους συνδέσμους προς τα πρότυπα πριν τα διαγράψετε.',
'unusedtemplateswlh'  => 'άλλοι σύνδεσμοι',

# Random pages
'randompage'         => 'Σελίδα στην τύχη',
'randompage-nopages' => 'Δεν υπάρχουν σελίδες σε αυτή την περιοχή ονομάτων.',

# Random redirect
'randomredirect'         => 'Τυχαία ανακατεύθυνση',
'randomredirect-nopages' => 'Δεν υπάρχουν ανακατευθύνσεις σε αυτή την περιοχή ονόματος.',

# Statistics
'statistics'             => 'Στατιστικές',
'sitestats'              => 'Στατιστικές ιστοχώρου',
'userstats'              => 'Στατιστικές χρηστών',
'sitestatstext'          => "Συνολικά {{PLURAL:$1|υπάρχει '''1''' σελίδα|υπάρχουν '''$1''' σελίδες}} στη βάση δεδομένων.
Συμπεριλαμβάνονται σελίδες «συζήτησης», σελίδες για τον ιστότοπο {{SITENAME}}, πολύ μικρές σελίδες, ανακατευθύνσεις, και άλλες που πιθανώς να μην πληρούν τις προϋποθέσεις για να χαρακτηρίζονται «σελίδες με ουσιώδες περιεχόμενο».
Αποκλείοντας αυτές, {{PLURAL:$2|υπάρχει '''1''' σελίδα η οποία|υπάρχουν  '''$2''' σελίδες οι οποίες}} είναι πιθανώς θεμιτές {{PLURAL:$2|σελίδα|σελίδες}} περιεχομένου.

'''$8''' {{PLURAL:$8|αρχείο|αρχεία}} έχουν επιφορτωθεί.

Έχουν καταγραφεί συνολικά '''$3''' {{PLURAL:$3|επίσκεψη σε σελίδα|επισκέψεις σε σελίδες}}, και '''$4''' {{PLURAL:$4|επεξεργασία σελίδας|επεξεργασίες σελίδων}}
από τότε που ο ιστότοπος {{SITENAME}} δημιουργήθηκε.
Αυτό αναλογεί σε '''$5''' κατά μέσο όρο επεξεργασίες ανά σελίδα, και σε '''$6''' επισκέψεις ανά επεξεργασία.",
'userstatstext'          => "{{PLURAL:$1|Υπάρχει '''1''' εγγεγραμένος χρήστης|Υπάρχουν '''$1''' εγγεγραμένοι χρήστες}}, από τους οποίους '''$2''' (ή το '''$4%''') {{PLURAL:$2|έχει|έχουν}} δικαιώματα $5.",
'statistics-mostpopular' => 'Οι πιο δημοφιλείς σελίδες',

'disambiguations'      => 'Σελίδες αποσαφήνισης',
'disambiguationspage'  => '{{ns:4}}:Σύνδεσμοι_προς_τις_σελίδες_αποσαφήνισης',
'disambiguations-text' => "Οι ακόλουθες σελίδες συνδέουν σε μια '''σελίδα αποσαφήνισης'''. Αντιθέτως πρέπει να συνδέουν στο κατάλληλο θέμα.<br />Μια σελίδα μεταχειρίζεται ως σελίδα αποσαφήνισης αν χρησιμοποιεί ένα πρότυπο το οποίο συνδέεται από το [[MediaWiki:disambiguationspage]]",

'doubleredirects'     => 'Διπλές ανακατευθύνσεις',
'doubleredirectstext' => 'Κάθε σειρά περιέχει συνδέσμους προς την πρώτη και τη δεύτερη σελίδα ανακατεύθυνσης, όπως επίσης και την πρώτη αράδα του κειμένου στη δεύτερη σελίδα ανακατεύθυνσης η οποία και είναι, κανονικά, ο πραγματικός προορισμός της ανακατεύθυνσης -εκεί δηλαδή όπου θα έπρεπε να είχατε οδηγηθεί από την αρχή.',

'brokenredirects'        => 'Κατεστραμένες ανακατευθύνσεις',
'brokenredirectstext'    => 'Οι παρακάτω ανακατευθύνσεις οδηγούν σε σελίδες που δεν υπάρχουν.',
'brokenredirects-edit'   => '(επεξεργασία)',
'brokenredirects-delete' => '(διαγραφή)',

'withoutinterwiki'        => 'Σελίδες χωρίς διαγλωσσικούς συνδέσμους',
'withoutinterwiki-header' => 'Οι ακόλουθες σελίδες δεν συνδέεουν σε εκδόσεις σε άλλες γλώσσες:',

'fewestrevisions' => 'Άρθρα με τις λιγότερες αναθεωρήσεις',

# Miscellaneous special pages
'nbytes'                  => '$1 bytes',
'ncategories'             => '$1 {{PLURAL:$1|κατηγορία|κατηγορίες}}',
'nlinks'                  => '$1 σύνδεσμοι',
'nmembers'                => '$1 {{PLURAL:$1|μέλος|μέλη}}',
'nrevisions'              => '$1 {{PLURAL:$1|αναθεώρηση|αναθεωρήσεις}}',
'nviews'                  => '$1 επισκέψεις',
'specialpage-empty'       => 'Αυτή η σελίδα είναι κενή.',
'lonelypages'             => 'Ορφανές σελίδες',
'lonelypagestext'         => 'Οι ακόλουθες σελίες δεν συνδέεονται από άλλες σελίδες σε αυτό το wiki.',
'uncategorizedpages'      => 'Αταξινόμητες σελίδες',
'uncategorizedcategories' => 'Αταξινόμητες κατηγορίες',
'uncategorizedimages'     => 'Ακατηγοριοποίητες εικόνες',
'uncategorizedtemplates'  => 'Μη κατηγοριοποιημένα πρότυπα',
'unusedcategories'        => 'Κενές κατηγορίες',
'unusedimages'            => 'Αχρησιμοποίητες εικόνες',
'popularpages'            => 'Δημοφιλείς σελίδες',
'wantedcategories'        => 'Ζητούμενες κατηγορίες',
'wantedpages'             => 'Σελίδες σε ζήτηση',
'mostlinked'              => 'Οι σελίδες με τις περισσότερες αναφορές',
'mostlinkedcategories'    => 'Περισσότερο χρησιμοποιούμενες κατηγορίες',
'mostlinkedtemplates'     => 'Τα πιο διασυνδεμένα πρότυπα',
'mostcategories'          => 'Άρθρα με τις περισσότερες κατηγορίες',
'mostimages'              => 'Περισσότερο χρησιμοποιούμενες εικόνες',
'mostrevisions'           => 'Άρθρα με τις περισσότερες αναθεωρήσεις',
'allpages'                => 'Όλες οι σελίδες',
'prefixindex'             => 'Κατάλογος κατά πρόθεμα',
'shortpages'              => 'Σύντομες σελίδες',
'longpages'               => 'Εκτενείς σελίδες',
'deadendpages'            => 'Αδιέξοδες σελίδες',
'deadendpagestext'        => 'Οι ακόλουθες σελίδες δεν συνδέουν σε άλλες σελίδες σε αυτό το wiki.',
'protectedpages'          => 'Προστατευμένες σελίδες',
'protectedpagestext'      => 'Οι ακόλουες σελίες είναι προστατευμένες από μετακίνηση ή επεξεργασία',
'protectedpagesempty'     => 'Καμία σελίδα με αυτές τις παραμέτρους δεν είναι προς το παρόν προστατευμένη.',
'listusers'               => 'Κατάλογος χρηστών',
'specialpages'            => 'Σελίδες λειτουργιών',
'spheading'               => 'Σελίδες λειτουργιών για όλους τους χρήστες',
'restrictedpheading'      => 'Ειδικές σελίδες με περιορισμούς πρόσβασης',
'rclsub'                  => '(σε σελίδες που συνδέονται από το "$1")',
'newpages'                => 'Νέες σελίδες',
'newpages-username'       => 'Όνομα χρήστη:',
'ancientpages'            => 'Οι παλιότερες σελίδες',
'intl'                    => 'Διαγλωσσικοί σύνδεσμοι',
'move'                    => 'Μετακίνηση',
'movethispage'            => 'Μετακίνηση αυτής της σελίδας',
'unusedimagestext'        => '<p>Παρακαλούμε να λάβετε υπ` όψη σας πως άλλες ιστοσελίδες είναι δυνατόν να  συνδέονται με μια εικόνα με απευθείας URL - για το λόγο αυτό μπορεί μερικές εικόνες να εμφανίζονται ακόμα εδώ παρόλο που στην πραγματικότητα είναι σε χρήση.</p>',
'unusedcategoriestext'    => 'Οι ακόλουθες κατηγοριοποιημένες σελίδες δεν συνδέονται με άλλο άρθρο ή κατηγορία.',
'notargettitle'           => 'Δεν έχει καθοριστεί προορισμός.',
'notargettext'            => 'Δεν έχετε καθορίσει ένα χρήστη ή μια σελίδα προορισμού για να εκτελεσθεί αυτή η λειτουργία.',

# Book sources
'booksources'               => 'Πηγές βιβλίων',
'booksources-search-legend' => 'Αναζήτηση για πηγές βιβλίων',
'booksources-go'            => 'Πήγαινε',
'booksources-text'          => 'Παρακάτω είναι μια λίστα συνδέσμων σε άλλους ιστοτόπους οι οποίοι πωλούν νέα και μεταχειρισμένα βιβλία, και μπορεί επίσης να έχουν περισσότερες πληροφορίες για βιβλία για τα οποία ψάχνετε:',

'categoriespagetext' => 'Υπάρχουν οι ακόλουθες κατηγορίες στο Wiκi.',
'data'               => 'Δεδομένα',
'userrights'         => 'Διαχείριση δικαιωμάτων χρηστών',
'groups'             => 'Ομάδες χρηστών',
'alphaindexline'     => '$1 έως $2',
'version'            => 'Έκδοση',

# Special:Log
'specialloguserlabel'  => 'Χρήστης:',
'speciallogtitlelabel' => 'Τίτλος:',
'log'                  => 'Καταγραφές γεγονότων',
'all-logs-page'        => 'Όλες οι καταγραφές γεγονότων',
'log-search-legend'    => 'Αναζήτηση για αρχεία καταγραφών',
'log-search-submit'    => 'Πήγαινε',
'alllogstext'          => 'Εποπτική εμφάνιση όλων των ενεργειών φόρτωσης αρχείων, διαγραφής, προστασίας, φραγής και όλων των καταγραφών των διαχειριστών στο αρχείο γεγονότων. Μπορείτε να περιορίσετε τα αποτελέσματα που εμφανίζονται επιλέγοντας συγκεκριμένο είδος γεγονότων, όνομα χρήστη ή τη σελίδα που επηρεάστηκε.',
'logempty'             => 'Δεν υπάρχουν στοιχεία που να ταιριάζουν στο αρχείο καταγραφών.',
'log-title-wildcard'   => 'Αναζήτησε τίτλους που αρχίζουν με αυτό το κείμενο',

# Special:Allpages
'nextpage'          => 'Επόμενη σελίδα ($1)',
'prevpage'          => 'Προηγούμενη σελίδα ($1)',
'allpagesfrom'      => 'Εμφάνιση όλων των σελίδων που αρχίζουν στο:',
'allarticles'       => 'Όλα τα άρθρα',
'allinnamespace'    => 'Όλες οι σελίδες (στην περιοχή $1)',
'allnotinnamespace' => 'Όλες οι σελίδες (που δεν βρίσκονται στην περιοχή $1)',
'allpagesprev'      => 'Προηγούμενες',
'allpagesnext'      => 'Επόμενες',
'allpagessubmit'    => 'Μετάβαση',
'allpagesprefix'    => 'Πρόβαλε σελίδες με πρόθεμα:',
'allpagesbadtitle'  => 'Ο δοσμένος τίτλος σελίδας ήταν άκυρος ή είχε ένα διαγλωσσικό ή δια-wiki πρόθεμα. Μπορεί να περιέχει έναν ή περισσότερους χαρακτήρες οι οποίοι δεν μπορούν να χρησιμοποιοθούν σε τίτλους.',
'allpages-bad-ns'   => 'Το {{SITENAME}} δεν έχει τον τομέα "$1".',

# Special:Listusers
'listusersfrom'      => 'Πρόβαλε χρήστες ξεκινώντας από:',
'listusers-submit'   => 'Δείξε',
'listusers-noresult' => 'Δεν βρέθηκε χρήστης.',

# E-mail user
'mailnologin'     => 'Δεν υπάρχει διεύθυνση παραλήπτη.',
'mailnologintext' => 'Πρέπει να έχετε [[Special:Userlogin|συνδεθεί]] και να έχετε δηλώσει
μια έγκυρη ηλεκτρονική διεύθυνση στις [[Special:Preferences|Προτιμήσεις]]
για να στείλετε e-mail σε άλλους χρήστες.',
'emailuser'       => 'Στείλτε e-mail σε αυτόν το χρήστη',
'emailpage'       => 'Αποστολή e-mail σε χρήστη',
'emailpagetext'   => "Συπληρώνοντας την παρακάτω φόρμα θα στείλετε ένα μήνυμα εφόσον ο παραλήπτης έχει δηλώσει μια έγκυρη διεύθυνση ηλεκτρονικού ταχυδρομείου στις 'προτιμήσεις χρήστη'. Η διεύθυνση ηλεκτρονικού ταχυδρομείου που έχετε δηλώσει στις δικές σας 'προτιμήσεις χρήστη' θα εμφανιστεί ως διεύθυνση αποστολέα του μηνύματος, ούτως ώστε ο παραλήπτης να μπορέσει να σας απαντήσει.",
'usermailererror' => 'Σφάλμα ηλεκτρονικού ταχυδρομείου:',
'defemailsubject' => 'Ηλεκτρονικό ταχυδρομείο {{SITENAME}}',
'noemailtitle'    => 'Δεν υπάρχει ηλεκτρονική διεύθυνση.',
'noemailtext'     => 'Ο χρήστης αυτός δεν έχει δηλώσει την ηλεκτρονική του διέθυνση ή έχει επιλέξει να μην δέχεται μηνύματα από άλλους χρήστες.',
'emailfrom'       => 'Αποστολέας',
'emailto'         => 'Προς',
'emailsubject'    => 'Θέμα',
'emailmessage'    => 'Μήνυμα',
'emailsend'       => 'Αποστολή',
'emailccme'       => 'Στείλε μου ένα αντίγραφο του μηνύματος μου με ηλεκτρονικό ταχυδρομείο.',
'emailccsubject'  => 'Αντίγραφο του μηνυματός σας στο $1: $2',
'emailsent'       => 'Το μήνυμα έχει σταλεί',
'emailsenttext'   => 'Το μήνυμά σας έχει σταλεί.',

# Watchlist
'watchlist'            => 'Λίστα παρακολούθησης',
'mywatchlist'          => 'Λίστα παρακολούθησης',
'watchlistfor'         => "(για '''$1''')",
'nowatchlist'          => 'Δεν υπάρχουν εγγραφές στη λίστα παρακολούθησης.',
'watchlistanontext'    => 'Παρακαλώ $1 για να δείτε ή να επεξεργαστείτε στοιχεία στη λίστα παρακολούθησής σας.',
'watchnologin'         => 'Δεν έχετε συνδεθεί.',
'watchnologintext'     => 'Για να κάνετε αλλαγές στη λίστα παρακολούθησης πρέπει να <a href="{{localurl:Special:Userlogin}}"> συνδεθείτε </a>.',
'addedwatch'           => 'Η σελίδα έχει προστεθεί στη λίστα παρακολούθησης.',
'addedwatchtext'       => "Η σελίδα \"\$1\" έχει προστεθεί στη [[Special:Watchlist|λίστα παρακολούθησης]].
Μελλοντικές αλλαγές στη σελίδα καθώς και στη σχετική με τη σελίδα συζήτηση θα φαίνονται '''με έντονα γράμματα''' στη [[Special:Recentchanges|λίστα πρόσφατων αλλαγών]] έτσι ώστε να διευκολύνεται η παρακολούθηση.


<p>Αν θελήσετε να αφαιρέσετε τη σελίδα αυτή από τη λίστα παρακολούθησης, κάνετε κλικ στην επιλογή \"παύση παρακολούθησης\" στην μπάρα ενεργειών.",
'removedwatch'         => 'Αφαιρέθηκε απο τη λίστα παρακολούθησης.',
'removedwatchtext'     => 'Η σελίδα "$1" έχει αφαιρεθεί από τη λίστα παρακολούθησής σας.',
'watch'                => 'Παρακολούθηση',
'watchthispage'        => 'Παρακολούθηση αυτής της σελίδας',
'unwatch'              => 'Παύση παρακολούθησης',
'unwatchthispage'      => 'Παύση παρακολούθησης αυτής της σελίδας',
'notanarticle'         => 'Η σελίδα αυτή δεν είναι σελίδα περιεχομένου.',
'watchnochange'        => 'Δεν υπήρξε δραστηριότητα επεξεργασίας στις σελίδες που παρακολουθείτε κατά την εμφανιζόμενη χρονική περίοδο.',
'watchlist-details'    => '(Υπό παρακολούθηση: $1 σελίδες, χωρίς τις σελίδες συζήτησης.',
'wlheader-enotif'      => '* Η ειδοποίηση με ηλεκτρονικό ταχυδρομείο έχει ενεργοποιηθεί.',
'wlheader-showupdated' => "* Σελίδες που έχουν υποστεί αλλαγές από την τελευταία φορά που τις επισκεφθήκατε εμφανίζονται με '''έντονους χαρακτήρες'''.",
'watchmethod-recent'   => 'Έλεγχος πρόσφατων αλλαγών σε σελίδες υπό παρακολούθηση',
'watchmethod-list'     => 'Έλεγχος σελίδων υπό παρακολούθηση για πρόσφατες αλλαγές',
'watchlistcontains'    => 'Η λίστα παρακολούθησής σας περιέχει $1 σελίδες.',
'iteminvalidname'      => 'Πρόβλημα με το στοιχείο "$1", άκυρο όνομα...',
'wlnote'               => 'Ακολουθούν οι $1 πιο πρόσφατες αλλαγές κατά τη διάρκεια των τελευταίων <b>$2</b> ωρών.',
'wlshowlast'           => 'Εμφάνιση των τελευταίων $1 ωρών $2 ημερών $3',
'watchlist-show-bots'  => 'Δείξε τις επεξεργασίες από bots',
'watchlist-hide-bots'  => 'Κρύψε τις επεξεργασίες από bots',
'watchlist-show-own'   => 'Δείξε τις επεξεργασίες μου',
'watchlist-hide-own'   => 'Κρύψε τις επεξεργασίες μου',
'watchlist-show-minor' => 'Δείξε τις μικρής σημασίας επεξεργασίες',
'watchlist-hide-minor' => 'Κρύψε τις μικρής σημασίας επεξεργασίες',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Παρακολούθηση...',
'unwatching' => 'Μη παρακολούθηση...',

'enotif_mailer'                => 'Σύστημα ειδοποίησης μέσω αλληλογραφίας του {{SITENAME}}',
'enotif_reset'                 => 'Σημειώστε όλες τις σελίδες ως αναγνωσμένες.',
'enotif_newpagetext'           => 'Αυτή είναι μια νέα σελίδα.',
'enotif_impersonal_salutation' => 'Χρήστης του ιστοτόπου "{{SITENAME}}"',
'changed'                      => 'έχει αλλάξει',
'created'                      => 'δημιουργήθηκε',
'enotif_subject'               => 'Η σελίδα $PAGETITLE του {{SITENAME}}έχει $CHANGEDORCREATED από το χρήστη $PAGEEDITOR',
'enotif_lastvisited'           => 'Δείτε το $1 για όλες τις αλλαγές που έγιναν από την τελευταία σας επίσκεψη.',
'enotif_lastdiff'              => 'Δείτε το $1 για να εμφανίσετε αυτή την αλλαγή.',
'enotif_anon_editor'           => 'ανώνυμος χρήστης $1',
'enotif_body'                  => 'Αγαπητέ $WATCHINGUSERNAME...

Η σελίδα $PAGETITLE του {{SITENAME}}έχει $CHANGEDORCREATED στις $PAGEEDITDATE από το χρήστη $PAGEEDITOR -ακολουθήστε το σύνδεσμο $PAGETITLE_URL για να δείτε την τρέχουσα αναθεώρηση.

$NEWPAGE

Περιγραφή: $PAGESUMMARY $PAGEMINOREDIT

Επικοινωνήστε με το συγκεκριμένο χρήστη:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Δεν θα υπάρξει άλλη ειδοποίηση για περαιτέρω αλλαγές αν δεν επισκεφθείτε τη σελίδα. Μπορείτε επίσης να επαναφέρετε την επιλογή ειδοποίησης για όλες τις σελίδες στη λίστα παρακολούθησής σας.

Φιλικά,<br />
Tο σύστημα ειδοποίησης του {{SITENAME}}

--
Για να αλλάξετε τις προτιμήσεις της λίστας παρακολούθησής σας, ακολουθήστε το σύνδεσμο:
{{fullurl:Special:Watchlist/edit}}

Ερωτήσεις και περισσότερες πληροφορίες:
{{fullurl:{{MediaWiki:helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Διαγραφή σελίδας',
'confirm'                     => 'Επιβεβαίωση',
'excontent'                   => "το περιεχόμενο ήταν: '$1'",
'excontentauthor'             => "το περιεχόμενο ήταν: '$1' (και οι μοναδικές συνεισφορές ήταν του '$2')",
'exbeforeblank'               => "το περιεχόμενο πριν απο την εκκαθάριση ήταν: '$1'",
'exblank'                     => 'η σελίδα ήταν κενή',
'confirmdelete'               => 'Επιβεβαίωση διαγραφής',
'deletesub'                   => '(Διαγραφή της "$1")',
'historywarning'              => 'ΠΡΟΕΙΔΟΠΟΙΗΣΗ! Η σελίδα που πρόκειται να διαγράψετε έχει ιστορικό.<br />',
'confirmdeletetext'           => 'Πρόκειται να διαγράψετε οριστικά από τη βάση δεδομένων μια σελίδα (ή μια εικόνα) μαζί με το ιστορικό της. Παρακαλούμε επιβεβαιώστε ότι θέλετε πραγματικά να το κάνετε, ότι αντιλαμβάνεσθε τις συνέπειες και ότι το κάνετε σύμφωνα με τους [[{{ns:4}}:Κανόνες|Κανόνες]].',
'actioncomplete'              => 'Η ενέργεια ολοκληρώθηκε.',
'deletedtext'                 => 'Η "$1" έχει διαγραφεί.
Για το ιστορικό των πρόσφατων διαγραφών ανατρέξτε στο σύνδεσμο $2',
'deletedarticle'              => 'Η $1 διαγράφτηκε.',
'dellogpage'                  => 'Καταγραφές διαγραφών',
'dellogpagetext'              => 'Λίστα των πιο πρόσφατων διαγραφών',
'deletionlog'                 => 'Καταγραφές διαγραφών',
'reverted'                    => 'Επαναφορά σε προηγούμενη αναθεώρηση',
'deletecomment'               => 'Αιτιολογία διαγραφής',
'rollback'                    => 'Επαναφορά επεξεργασιών',
'rollback_short'              => 'Επαναφορά',
'rollbacklink'                => 'Επαναφορά στην προηγούμενη',
'rollbackfailed'              => 'Η επαναφορά απέτυχε.',
'cantrollback'                => 'Δεν είναι δυνατή η αναίρεση αυτής της αλλαγής, πρόκειται για την αρχική ενέργεια δημιουργίας της σελίδας.',
'alreadyrolled'               => 'Αδύνατον να αναιρεθεί η τελευταία αλλαγή της σελίδας [[:$1]]
από το χρήστη [[User:$2|$2]] ([[User talk:$2|Συζήτηση]]), κάποιος έχει ήδη αναιρέσει την αλλαγή ή έχει αλλάξει εκ νέου τη σελίδα.

Τελευταία αλλαγή από το χρήστη [[User:$3|$3]] ([[User talk:$3|Συζήτηση]]).',
'editcomment'                 => 'Το σχόλιο της επεξεργασίας ήταν: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Ανάκληση των αλλαγών $2 (επιστροφή στην προηγούμενη αναθεώρηση $1)',
'rollback-success'            => 'Ανεστραμμένες εκδόσεις από $1, αλλάχθηκαν στην προηγούμενη έκδοση από $2.',
'sessionfailure'              => 'Υπάρχει πρόβλημα με τη σύνδεσή σας -η ενέργεια αυτή ακυρώθηκε προληπτικά για την αντιμετώπιση τυχόν πειρατείας συνόδου (session hijacking). Παρακαλoύμε πατήστε "Επιστροφή", ξαναφορτώστε τη σελίδα από την οποία φθάσατε εδώ και προσπαθήστε ξανά.',
'protectlogpage'              => 'Καταγραφές προστασίας (κλειδώματος)',
'protectlogtext'              => 'Ακολουθεί κατάλογος ενεργειών κλειδώματος και ξεκλειδώματος σελίδων.
(Βλ. [[{{ns:4}}:Σελίδες_υπό_προστασία]] για περισσότερες πληροφορίες).',
'protectedarticle'            => 'Κλειδωμένο $1',
'modifiedarticleprotection'   => 'αλλαγή επιπέδου προστασίας για "[[$1]]"',
'unprotectedarticle'          => 'έχει αρθεί η προστασία του $1',
'protectsub'                  => '(Κλειδώνεται η "$1")',
'confirmprotect'              => 'Επιβεβαίωση κλειδώματος',
'protectcomment'              => 'Αιτιολογία προστασίας',
'protectexpiry'               => 'Λήξη',
'protect_expiry_invalid'      => 'Ο χρόνος λήξης είναι άκυρος.',
'protect_expiry_old'          => 'Ο χρόνος λήξης είναι αναφέρεται στο παρελθόν.',
'unprotectsub'                => '(Άρση προστασίας για το "$1")',
'protect-unchain'             => 'Ξεκλείδωσε τα δικαιώματα μετακίνησης',
'protect-text'                => 'Μπορείτε να δείτε και να αλλάξετε το επίπεδο προστασίας εδώ για τη σελίδα <strong>$1</strong>.',
'protect-locked-blocked'      => 'Δεν μπορείτε να αλλάξετε επίπεδα προστασίας ενώ είστε σε φραγή.
Εδώ είναι οι τρέχουσες ρυθμίσεις για τη σελίδα <strong>$1</strong>:',
'protect-locked-dblock'       => 'Τα επίπεδα προστασίας δεν μπορούν να αλλαχθούν λόγω ενός ενεργού κλεδώματος της βάσης δεδομένων.
Εδώ είναι οι τρέχουσες ρυθμίσεις για τη σελίδα <strong>$1</strong>:',
'protect-locked-access'       => 'Ο λογαριασμός σας δεν έχει δικαίωμα να αλλάξει τα επίπεδα προστασίας σελίδας.
Εδώ είναι οι τρέχουσες ρυθμίσεις γαι τη σελίδα <strong>$1</strong>:',
'protect-cascadeon'           => 'Αυτή η σελίδα είναι προς το παρόν προστατευμένη επειδή περιλαμβάνεται {{PLURAL:$1|στην ακόλουθη σελίδα, η οποία έχει|στις ακόλουθες σελίδες, οι οποίες έχουν}} τη διαδοχική προστασία ενεργοποιημένη. Μπορείτε να αλλάξετε το επίπεδο προστασίας αυτής της σελίδας, αλλά δεν θα επηρεάσει τη διαδοχική προστασία.',
'protect-default'             => '(προεπιλεγμένο)',
'protect-fallback'            => 'Χρειάζεται την "$1" άδεια',
'protect-level-autoconfirmed' => 'Φράξε μη εγγεγραμμένους χρήστες',
'protect-level-sysop'         => 'Μόνο διαχειριστές',
'protect-summary-cascade'     => 'διαδοχική',
'protect-expiring'            => 'λήγει στις $1 (UTC)',
'protect-cascade'             => 'Διαδοχική προστασία - προστάτευσε όποιες σελίδες περιλαμβάνονται σε αυτή τη σελίδα.',
'restriction-type'            => 'Δικαίωμα:',
'restriction-level'           => 'Επίπεδο περιορισμού:',
'minimum-size'                => 'Ελάχιστο μέγεθος',
'maximum-size'                => 'Μέγιστο μέγεθος',
'pagesize'                    => '(μπάιτ)',

# Restrictions (nouns)
'restriction-edit' => 'Επεξεργασία',
'restriction-move' => 'Μετακίνηση',

# Restriction levels
'restriction-level-sysop'         => 'πλήρως προστατευμένη',
'restriction-level-autoconfirmed' => 'ημιπροστατευμένη',
'restriction-level-all'           => 'οποιοδήποτε επίπεδο',

# Undelete
'undelete'                     => 'Αποκατάσταση σελίδων που έχουν διαγραφεί',
'undeletepage'                 => 'Εμφάνιση και αποκατάσταση σελίδων που έχουν διαγραφεί',
'viewdeletedpage'              => 'Εμφάνιση διεγραμμένων σελίδων',
'undeletepagetext'             => 'Οι σελίδες που ακολουθούν έχουν διαγραφεί αλλά βρίσκονται ακόμα αποθηκευμένες στο αρχείο και μπορούν να αποκατασταθούν. (Κατά καιρούς γίνεται εκκαθάριση του αρχείου.)',
'undeleteextrahelp'            => "Για να επαναφέρετε όλη τη σελίδα, αφήστε όλα τα κουτιά επιλογής ατσεκάριστα και
κάντε κλικ στο κουμπί '''''Επαναφορά'''''. Για να εκτελέσετε μια επιλεκτική επαναφορά, τσεκάρετε τα κουτιά που αντιστοιχούν στις
αναθεωρήσεις προς επαναφορά, και κάντε κλικ στο κουμπί '''''Επαναφορά'''''. Κάνοντας κλικ στην '''''Ανανέωση''''' θα καθαρίσετε το
πεδίο σχολίων και όλα τα κουτιά επιλογής.",
'undeleterevisions'            => '$1 αναθεωρήσεις έχουν αρχειοθετηθεί.',
'undeletehistory'              => 'Αν επαναφέρετε την σελίδα, όλες οι εκδόσεις θα επαναφερθούν στο ιστορικό.
Αν μια νέα σελίδα με το ίδιο όνομα δημιουργήθηκε μετά την διαγραφή, οι επαναφερμένες
εκδόσεις θα εμφανιστούν στο πρότερο ιστορικό, και η τρέχουσα έκδοση της υπάρχουσας σελίδας
δεν θα αντικατασταθεί αυτόματα.',
'undeleterevdel'               => 'Η επαναφορά δεν θα εκτελεστεί αν έχει ως αποτέλεσμα η πρώτη αναθεώρηση της σελίδας να διαγραφεί μερικώς. Σε τέτοιες περιπτώσεις, πρέπει να απεπιλέξετε ή να εμφανίσετε τις νεότερες διεγραμμένες αναθεωρήσεις. Αναθεωρήσεις αρχείων για τις οποίες δεν έχετε δικαίωμα εμφάνισης δεν θα επαναφερθούν.',
'undeletehistorynoadmin'       => 'Αυτό το άρθρο έχει διαγραφεί. Ο λόγος για τη διαγραφή φαίνεται
στη σύνοψη παρακάτω, μαζί με λεπτομέρειες των χρηστών που επεξεργάστηκαν τη σελίδα
πριν τη διαγραφή. Το αρχικό κείμενο αυτών των διεγραμμένων αναθεωρήσεων είναι διαθέσιμο μόνο στους διαχειριστές.',
'undelete-revision'            => 'Διεγραμμένη αναθεώρηση του $1 από $2:',
'undeleterevision-missing'     => 'Άκυρη ή ανύπαρκτη αναθεώρηση. Μπορεί να έχετε έναν κακό σύνδεσμο, ή η
αναθεώρηση μπορεί να έχει επαναφερθεί ή αφαιρεθεί από το αρχείο.',
'undelete-nodiff'              => 'Δεν βρέθηκε προηγούμενη αναθεώρηση.',
'undeletebtn'                  => 'Αποκατάσταση!',
'undeletereset'                => 'Ανανέωση',
'undeletecomment'              => 'Σχόλιο:',
'undeletedarticle'             => 'αποκατάσταση "$1"',
'undeletedrevisions'           => 'Αποκατάσταση $1 αναθεωρήσεων',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|αναθεώρηση|αναθεωρήσεις}} και $2 {{PLURAL:$2|αρχείο|αρχεία}} επαναφέρθηκαν',
'undeletedfiles'               => '$1 {{PLURAL:$1|αρχείο|αρχεία}} επαναφέρθηκαν',
'cannotundelete'               => 'Η επαναφορά απέτυχε: κάποιος άλλος μπορεί να έχει επαναφέρει τη σελίδας πρώτος.',
'undeletedpage'                => "<big>'''Η $1 έχει επαναφερθεί'''</big>

Συμβουλευτείτε το [[Special:Log/delete|αρχείο καταγραφής διαγραφών]] για ένα μητρώο των πρόσφατων διαγραφών και επαναφορών.",
'undelete-header'              => 'Δείτε [[Special:Log/delete|το αρχείο καταγραφής διαγραφών]] για πρόσφατα διεγραμμένες σελίδες.',
'undelete-search-box'          => 'Αναζήτηση διεγραμμένων σελίδων',
'undelete-search-prefix'       => 'Εμφάνισε σελίδες που αρχίζουν με:',
'undelete-search-submit'       => 'Αναζήτηση',
'undelete-no-results'          => 'Δεν βρέθηκαν σελίδες που να ταιριάζουν στο αρχείο διαγραφών.',
'undelete-filename-mismatch'   => 'Αδύνατη η επαναφορά της αναθεώρησης αρχείου με χρονική σφραγίδα $1: αναντιστοιχία ονόματος αρχείου',
'undelete-bad-store-key'       => 'Αδύνατη η επαναφορά της αναθεώρησης αρχείου με χρονική σφραγιδα $1: το αρχείο δεν υπήρχε πριν τη διαγραφή.',
'undelete-cleanup-error'       => 'Σφάλμα κατά τη διαγραφή του αχρησιμοποίητου αρχείου καταγραφής "$1".',
'undelete-missing-filearchive' => 'Αδύνατον να επαναφερθεί το ID $1 του καταλόγου αρχείων γιατί δεν υπάρχει στη βάση δεδομένων. Μπορεί να έχει ήδη επαναφερθεί.',
'undelete-error-short'         => 'Σφάλμα κατά τη διαγραφή του αρχείου: $1',
'undelete-error-long'          => 'Αντιμετωπίστηκαν σφάλματα καθώς επαναφερόταν το αρχείο:

$1',

# Namespace form on various pages
'namespace'      => 'Περιοχή:',
'invert'         => 'Αντιστροφή της επιλογής',
'blanknamespace' => '(Αρχική περιοχή)',

# Contributions
'contributions' => 'Συνεισφορές χρήστη',
'mycontris'     => 'Οι προσθήκες μου',
'contribsub2'   => 'Για τον/την $1 ($2)',
'nocontribs'    => 'Δεν βρέθηκαν αλλαγές με αυτά τα κριτήρια.',
'ucnote'        => 'Ακολουθούν οι τελευταίες <b>$1</b> αλλαγές του χρήστη κατά τη διάρκεια των τελευταίων <b>$2</b> ημερών.',
'uclinks'       => 'Εμφάνιση των τελευταίων $1 αλλαγών - Εμφάνιση των τελευταίων $2 ημερών',
'uctop'         => ' (τελευταία)',
'month'         => 'Από τον μήνα (και νωρίτερα):',
'year'          => 'Από τη χρονιά (και νωρίτερα):',

'sp-contributions-newest'      => 'Οι πιο νέες',
'sp-contributions-oldest'      => 'Οι πιο παλαιές',
'sp-contributions-newer'       => 'Νεότερες $1',
'sp-contributions-older'       => 'Παλαιότερες $1',
'sp-contributions-newbies'     => 'Εμφάνισε τις συνεισφορές μόνο των νέων λογαριασμών',
'sp-contributions-newbies-sub' => 'Για νέους λογαριασμούς',
'sp-contributions-blocklog'    => 'Αρχείο καταγραφής φραγών',
'sp-contributions-search'      => 'Αναζήτηση για συνεισφορές',
'sp-contributions-username'    => 'Διεύθυνση IP ή όνομα χρήστη:',
'sp-contributions-submit'      => 'Αναζήτηση',

'sp-newimages-showfrom' => 'Εμφάνισε νέες εικόνες ξεκινώντας από $1',

# What links here
'whatlinkshere'       => 'Αναφορές στη σελίδα',
'whatlinkshere-title' => 'Σελίδες που οδηγούν στο $1',
'whatlinkshere-page'  => 'Σελίδα:',
'linklistsub'         => '(Κατάλογος συνδέσμων)',
'linkshere'           => "Οι ακόλουθες σελίδες συνδέουν στη σελίδα '''[[:$1]]''':",
'nolinkshere'         => "Δεν υπάρχουν σελίδες που να συνδέουν στη σελίδα '''[[:$1]]'''.",
'nolinkshere-ns'      => "Καμία σελίδα δεν συνδέει στο '''[[:$1]]''' στη επιλεγμένη περιοχή ονομάτων.",
'isredirect'          => 'ανακατεύθυνση σελίδας',
'istemplate'          => 'ενσωμάτωση',
'whatlinkshere-prev'  => '{{PLURAL:$1|προηγούμενη|προηγούμενες $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|επόμενη|επόμενες $1}}',
'whatlinkshere-links' => '← συνδέσεις',

# Block/unblock
'blockip'                     => 'Φραγή χρήστη ή διεύθυνσης IP',
'blockiptext'                 => 'Χρησιμοποιήστε την παρακάτω φόρμα για να εμποδίσετε παρεμβάσεις στο κείμενο από μια συγκεκριμένη διεύθυνση IP ή όνομα χρήστη.
Το μέτρο αυτό πρέπει να λαμβάνεται μόνο σε περιπτώσεις βανδαλισμού σελίδων και πάντα σύμφωνα με τους [[{{ns:4}}:Κανόνες|Κανόνες]].
Παρακαλούμε να αιτιολογήσετε την ενέργειά σας (παραπέμποντας π.χ. σε συγκεκριμένες σελίδες που υπέστησαν βανδαλισμό).',
'ipaddress'                   => 'Διεύθυνση IP/όνομα χρήστη',
'ipadressorusername'          => 'Διεύθυνση IP ή όνομα χρήστη',
'ipbexpiry'                   => 'Λήξη',
'ipbreason'                   => 'Αιτιολογία',
'ipbreasonotherlist'          => 'Άλλος λόγος',
'ipbreason-dropdown'          => '*Κοινοί λόγοι φραγής
** Εισαγωγή λανθασμένων πληροφοριών
** Αφαίρεση περιεχομένου από σελίδες
** Σύνδεσμοι τύπου spam σε εξωτερικούς ιστοτόπους
** Εισαγωγή ασυναρτησιών σε σελίδες
** Εκφοβιστική συμπεριφορά/παρενόχληση
** Κατάχρηση πολλαπλών λογαριασμών
** Μη αποδεκτό όνομα χρήστη',
'ipbanononly'                 => 'Φράξε μόνο ανώνυμους χρήστες',
'ipbcreateaccount'            => 'Απέτρεψε δημιουργία λογαριασμού',
'ipbemailban'                 => 'Εμπόδισε το χρήστη να στείλει e-mail',
'ipbenableautoblock'          => 'Φράξε αυτόματατα την τελευταία διεύθυνση IP που χρησιμοποιήθηκε από αυτό τον χρήστη, και όποιες ακόλουθες IP από τις οποίες δοκιμάζει να επεξεργαστεί',
'ipbsubmit'                   => 'Φραγή σε αυτό το χρήστη',
'ipbother'                    => 'Άλλη ώρα',
'ipboptions'                  => '2 ώρες:2 hours,1 ημέρα:1 day,3 ημέρες:3 days,1 εβδομάδα:1 week,2 εβδομάδες:2 weeks,1 μήνα:1 month,3 μήνες:3 months,6 μήνες:6 months,1 χρόνο:1 year,αόριστα:infinite',
'ipbotheroption'              => 'άλλη',
'ipbotherreason'              => 'Άλλος/επιπλέον λόγος:',
'ipbhidename'                 => 'Κρύψες το όνομα χρήστη/την IP από το αρχείο καταγραφής φραγών, την ενεργή λίστα φραγών και τη λίστα χρηστών.',
'badipaddress'                => 'Άκυρη διεύθυνση IP.',
'blockipsuccesssub'           => 'Η φραγή ολοκληρώθηκε επιτυχώς.',
'blockipsuccesstext'          => 'Η διεύθυνση "$1" έχει υποστεί φραγή. <br />Δείτε τη [[Special:Ipblocklist|λίστα διευθύνσεων IP που έχουν υποστεί φραγή]] για να το επιβεβαιώσετε.',
'ipb-edit-dropdown'           => 'Επεξεργασία λόγων φραγής',
'ipb-unblock-addr'            => 'Τερμάτισε τη φραγή του/της $1',
'ipb-unblock'                 => 'Τερμάτισε τη φραγή για ένα όνομα χρήστη ή μια διεύθυνση IP',
'ipb-blocklist-addr'          => 'Δες τις υπάρχουσες φραγές για $1',
'ipb-blocklist'               => 'Δες τις υπάρχουσες φραγές',
'unblockip'                   => 'Άρση φραγής χρήστη',
'unblockiptext'               => 'Χρησιμοποιήστε την παρακάτω φόρμα για να αποκαταστήσετε την πρόσβαση σε επεξεργασία, σε μια διεύθυνση IP ή σε ένα χρήστη που είχε αποκλειστεί με φραγή.',
'ipusubmit'                   => 'Άρση φραγής αυτής της διεύθυνσης',
'unblocked'                   => 'Η φραγή για τον/την [[User:$1|$1]] έχει τερματιστεί',
'unblocked-id'                => 'Η φραγή του $1 έχει τερματιστεί',
'ipblocklist'                 => 'Λίστα διευθύνσεων IP και ονομάτων χρηστών που έχουν υποστεί φραγή.',
'ipblocklist-legend'          => 'Βρες έναν χρήστη που έχει υποστεί φραγή',
'ipblocklist-username'        => 'Όνομα χρήστη ή IP διεύθυνση:',
'ipblocklist-submit'          => 'Αναζήτηση',
'blocklistline'               => 'Φραγή του/της $3 από τους $1, $2 (λήγει $4)',
'infiniteblock'               => 'αόριστη',
'expiringblock'               => 'λήγει στις $1',
'anononlyblock'               => 'μόνο τους ανώνυμους',
'noautoblockblock'            => 'αυτόματη φραγή απενεργοποιημένη',
'createaccountblock'          => 'δημιουργία λογαριασμού μπλοκαρισμένη',
'emailblock'                  => 'Το e-mail έχει φραγεί',
'ipblocklist-empty'           => 'Η λίστα φραγών είναι άδεια.',
'ipblocklist-no-results'      => 'Η ζητούμενη διεύθυνση IP ή το όνομα χρήστη δεν είναι φραγμένα.',
'blocklink'                   => 'φραγή',
'unblocklink'                 => 'Άρση φραγής',
'contribslink'                => 'Συνεισφορές/Προσθήκες',
'autoblocker'                 => 'Έχετε υποστεί αυτόματα φραγή από το σύστημα επειδή χρησιμοποιείτε την ίδια διεύθυνση IP με το χρήστη "$1". Αιτιολογία "$2".',
'blocklogpage'                => 'Καταγραφές φραγής',
'blocklogentry'               => 'O/H "[[$1]]" φράχθηκε με χρόνο λήξης $2 $3',
'blocklogtext'                => 'Σε αυτή τη σελίδα υπάρχουν οι καταγραφές φραγής και κατάργησης φραγής των χρηστών (αρχείο γεγονότων).

<br />Δεν συμπεριλαμβάνονται οι διευθύνσεις IP που υπέστησαν αυτόματα φραγή. <br />Στο σύνδεσμο [[Special:Ipblocklist|διευθύνεις IP που έχουν υποστεί φραγή]] θα βρείτε τον πλήρη κατάλογο με τις τρέχουσες φραγές.',
'unblocklogentry'             => 'Άρση φραγής του "$1"',
'block-log-flags-anononly'    => 'μόνο ανώνυμοι χρήστες',
'block-log-flags-nocreate'    => 'δημιουργία λογαριασμού απενεργοποιημένη',
'block-log-flags-noautoblock' => 'αυτόματη φραγή απενεργοποιημένη',
'block-log-flags-noemail'     => 'Το e-mail έχει φραγεί',
'range_block_disabled'        => 'Η δυνατότητα του διαχειριστή να δημιουργεί περιοχές φραγής είναι απενεργοποιημένη.',
'ipb_expiry_invalid'          => 'Άκυρος χρόνος λήξης',
'ipb_already_blocked'         => 'η διεύθυνση IP «$1» είναι ήδη φραγμένη',
'ipb_cant_unblock'            => 'Σφάλμα: Ο αριθμός αναγνώρισης φραγής $1 δεν βρέθηκε. Μπορεί να έχει ξεμπλοκαριστεί ήδη.',
'ip_range_invalid'            => 'Το εύρος των διευθύνσεων IP δεν είναι έγκυρο.',
'blockme'                     => 'Φραγή σε μένα',
'proxyblocker'                => 'Εργαλείο φραγής διακομιστών (proxy blocker)',
'proxyblocker-disabled'       => 'Η λειτουργία αυτή έχει απενεργοποιηθεί.',
'proxyblockreason'            => 'Η διεύθυνση IP σας έχει υποστεί φραγή γιατί είναι open proxy. Παρακαλούμε επικοινωνείστε με τον παροχέα υπηρεσιών Διαδικτύου που χρησιμοποιείτε ή με την τεχνική υποστήριξη, για να θέσετε υπ΄ όψη τους αυτό το σοβαρό θέμα ασφάλειας.',
'proxyblocksuccess'           => 'Ολοκληρώθηκε!',
'sorbsreason'                 => 'Η διεύθνυση IP σας έχει χαρακτηρισθεί ως open proxy στο DNSBL.',
'sorbs_create_account_reason' => 'Η διεύθυνση IP σας έχει χαρακτηρισθεί open proxy στο DNSBL. Δεν μπορείτε να δημιουργήσετε λογαριασμό χρήστη.',

# Developer tools
'lockdb'              => 'Κλείδωμα βάσης δεδομένων',
'unlockdb'            => 'Ξεκλείδωμα βάσης δεδομένων',
'lockdbtext'          => 'Το κλείδωμα της βάσης δεδομένων αναιρεί τη δυνατότητα όλων των χρηστών να επεξεργαστούν σελίδες, να αλλάξουν τις προτιμήσεις τους, να επεξεργαστούν τις λίστες παρακολούθησης και να εκτελέσουν οποιαδήποτε ενέργεια επηρεάζει τη βάση δεδομένων. Παρακαλούμε να επιβεβαιώσετε ότι γνωρίζετε τις επιπτώσεις της ενέργειάς σας και ότι θα ξεκλειδώσετε τη βάση δεδομένων μόλις ολοκληρωθεί η συντήρηση.',
'unlockdbtext'        => 'Το ξεκλείδωμα της βάσης δεδομένων θα αποκαταστήσει τη δυνατότητα των χρηστών να επεξεργάζονται σελίδες, να αλλάζουν τις προτιμήσεις τους, να τροποποιούν τις λίστες παρακολούθησης και να προβαίνουν γενικότερα σε ενέργειες που επιφέρουν αλλαγές στη βάση δεδομένων. Παρακαλούμε επιβεβαιώστε πως θέλετε να προχωρήσετε.',
'lockconfirm'         => 'Ναι, επιθυμώ να κλειδώσω τη βάση δεδομένων.',
'unlockconfirm'       => 'Ναι, επιθυμώ να ξεκλειδώσω τη βάση δεδομένων.',
'lockbtn'             => 'Κλείδωμα βάσης δεδομένων',
'unlockbtn'           => 'Ξεκλείδωμα βάσης δεδομένων',
'locknoconfirm'       => 'Δεν έχετε σημειώσει το κουτάκι της επιβεβαίωσης.',
'lockdbsuccesssub'    => 'Η βάση δεδομένων κλειδώθηκε επιτυχώς.',
'unlockdbsuccesssub'  => 'Άρση κλειδώματος τη βάσης δεδομένων',
'lockdbsuccesstext'   => 'Η βάση δεδομένων έχει κλειδωθεί.
<br />Μην ξεχάσετε να την ξεκλειδώσετε όταν τελειώσετε τη συντήρηση.',
'unlockdbsuccesstext' => 'Η βάση δεδομένων έχει ξεκλειδωθεί.',
'lockfilenotwritable' => 'Το αρχείο κλειδώματος της βάσης δεδομένων δεν είναι εγγράψιμο. Για να κλειδώσετε ή να ξεκλειδώσετε τη βάση δεδομένων, αυτό το αρχείο πρέπει να είναι εγγράψιμο από τον εξυπηρετητή web.',
'databasenotlocked'   => 'Η βάση δεδομένων δεν είναι κλειδωμένη.',

# Move page
'movepage'                => 'Μετακίνηση σελίδας',
'movepagetext'            => "Χρησιμοποιήστε τη φόρμα που ακολουθεί για να μετονομάσετε σελίδες και για να μεταφέρετε όλο το ιστορικό τους κάτω από το νέο όνομα. Κάτω από τον παλιό τίτλο της σελίδας θα παραμείνει μια σελίδα ανακατεύθυνσης στο νέο τίτλο. Οι τυχόν σύνδεσμοι που οδηγούσαν στην παλιά σελίδα δεν θα επηρεαστούν. Βεβαιωθείτε πως [[Special:Maintenance|ελέγξατε]] τα διπλά διαστήματα και τους κατεστραμένους συνδέσμους. Αναλαμβάνετε την ευθύνη να επιβεβαιώσετε ότι οι συνδεσμοι εξακολουθούν να οδηγούν προς τις κατευθύνσεις που πρέπει.

Λάβετε υπ` όψη σας ότι η σελίδα '''δεν''' θα μετακινηθεί αν υπάρχει ήδη μια άλλη σελίδα κάτω από το νέο τίτλο, εκτός αν η σελίδα αυτή είναι κενή '''και''' χωρίς ιστορικό επεξεργασίας. Αυτό σημαίνει ότι, στην περίπτωση που έχετε κάνει λάθος, μπορείτε να μετονομάσετε μια σελίδα ξαναδίνοντας της την αρχική της ονομασία αλλά δεν μπορείτε να αντικαταστήσετε μια υπάρχουσα σελίδα.


<b>ΠΡΟΣΟΧΗ!</b>
Η μετονομασία σελίδας είναι μια αιφνίδια και δραστική αλλαγή όταν πρόκειται για δημοφιλείς σελίδες. Παρακαλούμε, πριν το αποφασίσετε, να εξετάσετε προσεκτικά τις πιθανές επιπτώσεις αυτής της ενέργειας .",
'movepagetalktext'        => "Η σελίδα συζήτησης που αντιστοιχεί, εάν υπάρχει, θα μετακινηθεί αυτόματα μαζί με αυτήν '''έκτός αν:'''
*Μετακινείτε τη σελίδα σε διαφορετική περιοχή (namespace),
*Υπάρχει κάτω από το νέο όνομα μια σελίδα συζήτησης που δεν είναι κενή, ή
*Έχετε αφαιρέσει τη σημείωση (check) από το κουτάκι που υπάρχει παρακάτω.

Σε αυτές τις περιπτώσεις, θα πρέπει να μετακινήσετε (ή να ενσωματώσετε αν το θέλετε) τη σελίδα με αντιγραφή-και-επικόλληση.",
'movearticle'             => 'Μετακίνηση σελίδας',
'movenologin'             => 'Δεν έχετε συνδεθεί.',
'movenologintext'         => 'Για να μετακινήσετε μια σελίδα πρέπει να είστε εγγεγραμένος χρήστης και [[Special:Userlogin|να έχετε συνδεθεί]] στο Wiκi.',
'movenotallowed'          => 'Δεν έχεις την άδεια να μετακινείς σελίδες σε αυτό το wiki.',
'newtitle'                => 'νέος τίτλος',
'move-watch'              => 'Παρακολούθησε αυτή τη σελίδα',
'movepagebtn'             => 'Μετακίνηση σελίδας',
'pagemovedsub'            => 'Η μετακίνηση ήταν επιτυχής',
'movepage-moved'          => '<big>\'\'\'Το "$1" έχει μετακινηθεί στο "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Υπάρχει ήδη σελίδα με αυτό το όνομα. Παρακαλούμε δώστε άλλο όνομα στη σελίδα.',
'talkexists'              => "Η ίδια η σελίδα μετακινήθηκε επιτυχώς αλλά όχι και η σελίδα συζήτησης, λόγω του ότι υπάρχει ήδη άλλη σελίδα συζήτησης κάτω από το νέο τίτλο. Παρακαλούμε ενοποιήστε τις δύο σελίδες με 'αντιγραφή-και-επικόλληση'.",
'movedto'                 => 'Μεκακινήθηκε στο',
'movetalk'                => 'Μετακίνηση της σελίδας "συζήτηση" (εάν υπάρχει)',
'talkpagemoved'           => 'Η αντίστοιχη σελίδα συζήτησης έχει επίσης μεταφερθεί.',
'talkpagenotmoved'        => 'Η σελίδα συζήτησης που αντιστοιχεί <strong>δεν</strong> έχει μεταφερθεί.',
'1movedto2'               => 'Η $1 μετονομάστηκε σε $2',
'1movedto2_redir'         => 'Η $1 μετακινήθηκε στη θέση $2 (με ανακατεύθυνση)',
'movelogpage'             => 'Αρχείο καταγραφής μετακινήσεων',
'movelogpagetext'         => 'Ακολουθεί η λίστα με τις σελίδες που έχουν μετακινηθεί.',
'movereason'              => 'Αιτιολογία',
'revertmove'              => 'έπαναφορά',
'delete_and_move'         => 'Διαγραφή και μετακίνηση',
'delete_and_move_text'    => '==Χρειάζεται διαγραφή.==

Το άρθρο [[$1]] υπάρχει ήδη. Θέλετε να το διαγράψετε για να εκτελεσθεί η μετακίνηση;',
'delete_and_move_confirm' => 'Ναι, διέγραψε τη σελίδα',
'delete_and_move_reason'  => 'Διαγράφτηκε για να εκτελεθεί η μετακίνηση.',
'selfmove'                => 'Ο τίτλος προέλευσης είναι ο ίδιος με τον τίτλο προορισμού -δεν είναι δυνατόν να μετακινηθεί μια σελίδα προς τον εαυτό της.',
'immobile_namespace'      => 'Ο τίτλος του προορισμού είναι ειδικού τύπου -δεν είναι δυνατή η μετακίνηση σελίδων σε εκείνη την περιοχή.',

# Export
'export'            => 'Εξαγωγή σελίδων',
'exporttext'        => 'Μπορείτε να κάνετε εξαγωγή του κειμένου και του ιστορικού επεξεργασίας μιας συγκεκριμένης σελίδας (ή περισσοτέρων σελίδων που έχουν ομαδοποιηθεί με χρήση XML).

Για την εξαγωγή ολόκληρων άρθρων, συμπληρώστε τους τίτλους στο παρακάτω πλαίσιο (ένα τίτλο σε κάθε σειρά) και επιλέξτε ανάμεσα από το να εξαγάγετε μόνο την τρέχουσα έκδοση (με τις πληροφορίες της πιο πρόσφατης επεξεργασίας) ή εναλλακτικά και τις παλιότερες εκδόσεις (με τις αντίστοιχες καταγραφές στη σελιδα του ιστορικού).

Στην τελευταία περίπτωση μπορείτε να κάνετε και χρήση συνδέσμου, π.χ. [[{{ns:Special}}:Export/{{MediaWiki:mainpage}}]] για το άρθρο {{MediaWiki:mainpage}}.',
'exportcuronly'     => 'Να συμπεριληφθεί μόνον η τρέχουσα αναθεώρηση, όχι το πλήρες ιστορικό.',
'exportnohistory'   => "----
'''Σημείωση:''' Η εξαγωγή του πλήρους ιστορικού σελίδων μέσω αυτής της φόρμας έχει απενεργοποιηθεί λόγω θεμάτων απόδοσης.",
'export-submit'     => 'Εξαγωγή',
'export-addcattext' => 'Πρόσθετε σελίδες από την κατηγορία:',
'export-addcat'     => 'Πρόσθεσε',
'export-download'   => 'Δυνατότητα αποθήκευσης ως αρχείου',

# Namespace 8 related
'allmessages'               => 'Μηνύματα συστήματος',
'allmessagesname'           => 'Όνομα',
'allmessagesdefault'        => 'Προκαθορισμένο κείμενο',
'allmessagescurrent'        => 'Παρόν κείμενο',
'allmessagestext'           => 'Η λίστα με όλα τα μηνύματα συστήματος που βρίσκονται στην περιοχή MediaWiki:',
'allmessagesnotsupportedDB' => 'Special:Το AllMessages δεν υποστηρίζεται επειδή το wgUseDatabaseMessages είναι απενεργοποιημένο.',
'allmessagesfilter'         => 'Φίλτρο ονόματος μηνύματος:',
'allmessagesmodified'       => 'Δείξε μόνο τα τροποποιημένα',

# Thumbnails
'thumbnail-more'           => 'Μεγέθυνση',
'missingimage'             => '<b>Αγνοούμενη εικόνα</b><br /><i>$1</i>',
'filemissing'              => 'Αγνοούμενο αρχείο',
'thumbnail_error'          => 'Σφάλμα στη δημιουργία μικρογραφίας: $1',
'djvu_page_error'          => 'Σελίδα DjVu εκτός ορίων',
'djvu_no_xml'              => 'Αδυναμία προσκόμισης XML για το αρχείο DjVu',
'thumbnail_invalid_params' => 'Άκυρες παράμετροι μικρογραφίας',
'thumbnail_dest_directory' => 'Αδυναμία δημιουργίας καταλόγου προορισμού',

# Special:Import
'import'                     => 'Εισαγωγή σελίδων',
'importinterwiki'            => 'Εισαγωγή από άλλο Wiki',
'import-interwiki-text'      => 'Επιλέξτε ένα wiki και τίτλο σελίδας για την εισαγωγή.
Οι ημερομηνίες των αναθεωρήσεων και τα ονόματα των συντακτών θα διατηρηθούν.
Όλες οι ενέργειες εισαγωγής μεταξύ wiki καταγράφονται στο [[Special:Log/import|αρχείο καταγραφής εισαγωγών]].',
'import-interwiki-history'   => 'Αντέγραψε όλες τις εκδόσεις του ιστορικού για αυτή τη σελίδα',
'import-interwiki-submit'    => 'Εισαγωγή',
'import-interwiki-namespace' => 'Μετέφερε τις σελίδες στην περιοχή ονομάτων:',
'importtext'                 => 'Παρακαλούμε εξάγετε το αρχείο από το πηγαίο Wiki (χρησιμοποιώντας Special:Export), αποθηκεύστε το στο δίσκο του υπολογιστή σας και φορτώστε το από εκεί.',
'importstart'                => 'Η εισαγωγή των σελίδων είναι σε εξέλιξη...',
'import-revision-count'      => '$1 {{PLURAL:$1|αναθεώρηση|αναθεωρήσεις}}',
'importnopages'              => 'Δεν υπάρχουν σελίδες για εισαγωγή.',
'importfailed'               => 'Η εισαγωγή απέτυχε: $1',
'importunknownsource'        => 'Άγνωστος τύπος πηγής για την εισαγωγή',
'importcantopen'             => 'Το αρχείο εισαγωγής δεν ήταν δυνατόν να ανοιχθεί',
'importbadinterwiki'         => 'Εσφαλμένος διαγλωσσικός σύνδεσμος',
'importnotext'               => 'Κενό (-ή) ή χωρίς κείμενο',
'importsuccess'              => 'Η εισαγωγή επέτυχε!',
'importhistoryconflict'      => 'Υπάρχει αντιφατικό ιστορικό αναθεωρήσεων (μπορεί να έχετε κάνει παλιότερα  εισαγωγή αυτής της σελίδας).',
'importnosources'            => 'Δεν έχουν καθοριστεί πηγές για την εισαγωγή από άλλο Wiki και η απευθείας φόρτωση στο ιστορικό έχει απενεργοποιηθεί.',
'importnofile'               => 'Δεν επιφορτώθηκε κανένα αρχείο εισαγωγής.',
'importuploaderror'          => 'Η επιφόρτωση του αρχείου εισαγωγής απέτυχε˙ ίσως το αρχείο είναι μεγαλύτερο από το επιτρεπόμενο μέγεθος για επιφόρτωση.',

# Import log
'importlogpage'                    => 'Αρχείο καταγραφής εισαγωγών',
'importlogpagetext'                => 'Διαχειριστικές εισαγωγές σελίδων με ιστορικό επεξεργασίας από άλλα wiki.',
'import-logentry-upload'           => 'εισάχθηκε η σελίδα [[$1]] με επιφόρτωση αρχείου',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|αναθεώρηση|αναθεωρήσεις}}',
'import-logentry-interwiki'        => 'η σελίδα $1 εισάχθηκε μεταξύ wiki',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|αναθεώρηση|αναθεωρήσεις}} από $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Η προσωπική μου σελίδα στο Wiκi',
'tooltip-pt-anonuserpage'         => 'Η σελίδα χρήστη στον οποίο αντιστοιχεί η διεύθυνση IP που έχετε',
'tooltip-pt-mytalk'               => 'Η σελίδα συζητήσεών μου',
'tooltip-pt-anontalk'             => 'Συζήτηση σχετικά με τις αλλαγές που έγιναν από αυτή τη διεύθυνση IP',
'tooltip-pt-preferences'          => 'Οι προτιμήσεις μου',
'tooltip-pt-watchlist'            => 'Η λίστα με τις σελίδες που παρακολουθείτε για αλλαγές',
'tooltip-pt-mycontris'            => 'Κατάλογος των συνεισφορών μου',
'tooltip-pt-login'                => 'Σας προτείνουμε να συνδεθείτε παρόλο που δεν είναι αναγκαίο.',
'tooltip-pt-anonlogin'            => 'Σας προτείνουμε να συνδεθείτε παρόλο που δεν είναι αναγκαίο.',
'tooltip-pt-logout'               => 'Αποσύνδεση',
'tooltip-ca-talk'                 => 'Συζήτηση για το παρόν άρθρο',
'tooltip-ca-edit'                 => 'Μπορείτε να επεξεργαστείτε αυτό το άρθρο. Χρησιμοποιείστε την "Προεπισκόπηση',
'tooltip-ca-addsection'           => 'Προσθέστε σχόλιο στη συζήτηση.',
'tooltip-ca-viewsource'           => 'Αυτό το άρθρο είναι κλειδωμένο. Μπορείτε να δείτε τον πηγαίο κώδικά του.',
'tooltip-ca-history'              => 'Παλιές αναθεωρήσεις του άρθρου.',
'tooltip-ca-protect'              => 'Κλείδωμα αυτού του άρθρου',
'tooltip-ca-delete'               => 'Διαγραφή αυτής της σελίδας',
'tooltip-ca-undelete'             => 'Αποκαταστήστε τις αλλαγές που έγιναν σε αυτή τη σελίδα πριν διαγραφεί.',
'tooltip-ca-move'                 => 'Μετακινήστε αυτή τη σελίδα',
'tooltip-ca-watch'                => 'Προσθήκη της σελίδας στη λίστα παρακολούθησης',
'tooltip-ca-unwatch'              => 'Αφαίρεση της σελίδας από τη λίστα παρακολούθησης',
'tooltip-search'                  => 'Αναζήτηση στο WiKi',
'tooltip-search-go'               => 'Πήγαινε σε μια σελίδα με το ακριβές όνομα εάν υπάρχει',
'tooltip-search-fulltext'         => 'Ψάξε τις σελίδες για αυτό το κείμενο',
'tooltip-p-logo'                  => 'Αρχική σελίδα',
'tooltip-n-mainpage'              => 'Δείτε την Αρχική σελίδα',
'tooltip-n-portal'                => 'Σχετικά με το Wiκi - πώς μπορείτε να βοηθήσετε, πού μπορείτε να απευθυνθείτε',
'tooltip-n-currentevents'         => 'Πληροφορίες για πρόσφατα γεγονότα',
'tooltip-n-recentchanges'         => 'Η λίστα με τις πρόσφατες αλλαγές στο WiKi',
'tooltip-n-randompage'            => 'Επισκεφθείτε μια τυχαία σελίδα του Wiκi',
'tooltip-n-help'                  => 'Το μέρος για να βρείτε τις απαντήσεις που ψάχνετε.',
'tooltip-n-sitesupport'           => 'Βοηθήστε το έργο.',
'tooltip-t-whatlinkshere'         => 'Λίστα από άρθρα που αναφέρουν το παρόν άρθρο',
'tooltip-t-recentchangeslinked'   => 'Πρόσφατες αλλαγές σε άρθρα που συνδέονται με το παρόν',
'tooltip-feed-rss'                => 'RSS feed για',
'tooltip-feed-atom'               => 'Atom feed για',
'tooltip-t-contributions'         => 'Δείτε τη λίστα με τις συνεισφορές αυτού του χρήστη στο Wiκi',
'tooltip-t-emailuser'             => 'Αποστολή μηνύματος σε αυτό το χρήστη',
'tooltip-t-upload'                => 'Φόρτωση εικόνας ή αρχείου πολυμέσων',
'tooltip-t-specialpages'          => 'Η λίστα με όλες τις σελίδες λειτουργιών',
'tooltip-t-print'                 => 'Εκτυπώσιμη έκδοση αυτής της σελίδας',
'tooltip-t-permalink'             => 'Μόνιμος σύνδεσμος σε αυτή την έκδοση της σελίδας',
'tooltip-ca-nstab-main'           => 'Άρθρο',
'tooltip-ca-nstab-user'           => 'Δείτε τη σελίδα του χρήστη',
'tooltip-ca-nstab-media'          => 'Δείτε τη σελίδα πολυμέσων',
'tooltip-ca-nstab-special'        => 'Αυτή είναι ειδική σελίδα και δεν μπορείτε να την επεξεργαστείτε.',
'tooltip-ca-nstab-project'        => 'Δείτε τη σελίδα του συστήματος',
'tooltip-ca-nstab-image'          => 'Δείτε την εικόνα',
'tooltip-ca-nstab-mediawiki'      => 'Δείτε το μήνυμα του συστήματος',
'tooltip-ca-nstab-template'       => 'Δείτε το πρότυπο',
'tooltip-ca-nstab-help'           => 'Δείτε τη σελίδα βοήθειας',
'tooltip-ca-nstab-category'       => 'Δείτε τη σελίδα κατηγοριών',
'tooltip-minoredit'               => 'Χαρακτηρήστε τις αλλαγές "μικρής κλίμακας"',
'tooltip-save'                    => 'Αποθήκευση αλλαγών',
'tooltip-preview'                 => 'Προεπισκόπηση - Παρακαλούμε να χρησιμοποιήτε αυτή την επιλογή πριν αποθηκεύσετε τις αλλαγές σας!',
'tooltip-diff'                    => 'Προβολή των αλλαγών που κάνατε στο κείμενο.',
'tooltip-compareselectedversions' => 'Εμφάνιση των διαφορών ανάμεσα στις δύο αναθεωρήσεις της σελίδας που έχετε επιλέξει.',
'tooltip-watch'                   => 'Προσθήκη της σελίδας στη λίστα παρακολούθησης',
'tooltip-recreate'                => 'Ξαναδημιούργησε τη σελίδα παρόλο που έχει διαγραφεί',
'tooltip-upload'                  => 'Άρχισε τη φόρτωση',

# Stylesheets
'common.css'   => '/** CSS τα οποία τοποθετούνται εδώ θα εφαρμοστούν σε όλα τα skins */',
'monobook.css' => '/* edit this file to customize the monobook skin for the entire site */',

# Scripts
'common.js'   => '/* Οποιοσδήποτε κώδικας JavaScript εδώ θα φορτωθεί για όλους τους χρήστες σε κάθε φόρτωση σελίδας. */',
'monobook.js' => '/* Παρωχημένο, χρισιμοποίησε το [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Τα μεταδεδομένα RDF που αφορούν στο Dublin Core έχουν απενεργοποιηθεί σε αυτό τον server.',
'nocreativecommons' => 'Τα μεταδεδομένα RDF που αφορούν στο Creative Commons έχουν απενεργοποιηθεί σε αυτό τον server.',
'notacceptable'     => 'Ο server του Wiki δεν μπορεί να αποδόσει δεδομένα σε μορφή που τα διαβάζει ο client',

# Attribution
'anonymous'        => 'Ανώνυμος(-οι) χρήστης(-ες) του {{SITENAME}}',
'siteuser'         => '{{SITENAME}} χρήστης $1',
'lastmodifiedatby' => 'Η σελίδα αυτή τροποποιήθηκε τελευταία φορά στις  $2, $1 από το χρήστη $3.', # $1 date, $2 time, $3 user
'and'              => 'και',
'othercontribs'    => 'Βασισμένο στη δουλειά του/των $1',
'others'           => 'άλλοι',
'siteusers'        => 'χρηστών του ιστοχώρου $1',
'creditspage'      => 'Αναγνώριση συνεισφοράς στη σελίδα',
'nocredits'        => 'Δεν υπάρχουν πληροφορίες σχετικά με την αναγνώριση συνεισφοράς σε αυτή τη σελίδα.',

# Spam protection
'spamprotectiontitle'    => 'Φίλτρο προστασίας από spam',
'spamprotectiontext'     => 'Η σελίδα που επιχειρήσατε να αποθηκεύσετε απομονώθηκε από το φίλτρο spam. Αυτό οφείλεται, πιθανότατα, στην ύπαρξη ενός (ή περισσότερων) συνδέσμων προς εξωτερικές σελίδες.',
'spamprotectionmatch'    => 'Το φίλτρο spam έχει τεθεί σε ενέργεια εξ αιτίας του εξής κειμένου: $1',
'subcategorycount'       => 'Υπάρχουν $1 υποκατηγορίες σε αυτή την κατηγορία.',
'categoryarticlecount'   => 'Υπάρχουν $1 άρθρα σε αυτή την κατηγορία.',
'category-media-count'   => '{{PLURAL:$1|Υπάρχει ένα αρχείο|υπάρχουν $1 αρχεία}} σε αυτή την κατηγορία.',
'listingcontinuesabbrev' => 'συνεχίζεται...',
'spambot_username'       => 'Καθαρισμός spam από το MediaWiki',
'spam_reverting'         => 'Επαναφορά στην τελευταία έκδοση που δεν περιέχει συνδέσμους στο $1',
'spam_blanking'          => 'Όλες οι αναθεωρήσεις περιείχαν συνδέσμους προς το $1, εξάλειψη',

# Info page
'infosubtitle'   => 'Πληροφορίες για τη σελίδα',
'numedits'       => 'Αριθμός επεξεργασιών (στο άρθρο): $1',
'numtalkedits'   => 'Αριθμός επεξεργασιών (στη σελίδα συζήτησης): $1',
'numwatchers'    => 'Αριθμός παρακολουθήσεων: $1',
'numauthors'     => 'Αριθμός διακριτών συγγραφέων (στο άρθρο): $1',
'numtalkauthors' => 'Αριθμός διακριτών συγγραφέων (στη σελίδα συζήτησης): $1',

# Math options
'mw_math_png'    => 'Απόδοση πάντα σε PNG',
'mw_math_simple' => 'HTML αν είναι αρκετά απλό, διαφορετικά PNG',
'mw_math_html'   => 'HTML αν είναι δυνατόν, διαφορετικά PNG',
'mw_math_source' => 'Να παραμείνει ως TeX (για text browsers)',
'mw_math_modern' => 'Προτεινόμενο για σύγχρονους browser',
'mw_math_mathml' => 'MathML όποτε είναι δυνατόν (πειραματικό)',

# Patrolling
'markaspatrolleddiff'                 => "Να σημειωθεί 'υπό παρακολούθηση'",
'markaspatrolledtext'                 => "Να σημειωθεί αυτό το άρθρο ως 'υπό παρακολούθηση'.",
'markedaspatrolled'                   => "Σημειωμένο ως 'υπό παρακολούθηση'",
'markedaspatrolledtext'               => "Η αναθεώρηση που έχει επιλεγεί έχει σημειωθεί ως 'υπό παρακολούθηση'.",
'rcpatroldisabled'                    => "Η λειτουργία 'Παρακολούθηση Πρόσφατων Αλλαγών' έχει απενεργοποιηθεί.",
'rcpatroldisabledtext'                => "Η λειτουργία 'Παρακολούθηση Πρόσφατων Αλλαγών' είναι αυτή τη στιγμή απενεργοποιημένη.",
'markedaspatrollederror'              => 'Δεν μπορεί να σημανθεί ως υπό περιπολία',
'markedaspatrollederrortext'          => 'Πρέπει να ορίσετε μια αναθεώρηση για να σημανθεί ως υπό περιπολία',
'markedaspatrollederror-noautopatrol' => 'Δεν επιτρέπεται να σημάνετε τις δικές σας αλλάγες ως υπό περιπολία.',

# Patrol log
'patrol-log-page' => 'Αρχείο καταγραφής περιπολιών',
'patrol-log-line' => 'σημάνθηκε το $1 του $2 υπό περιπολία $3',
'patrol-log-auto' => '(αυτόματα)',

# Image deletion
'deletedrevision'                 => 'Η παλιά έκδοση της $1 διαγράφτηκε',
'filedeleteerror-short'           => 'Λάθος στη διαγραφή του αρχείου: $1',
'filedeleteerror-long'            => 'Αντιμετωπίστηκαν προβλήματα κατά τη διαγραφή του αρχείου:

$1',
'filedelete-missing'              => 'Το αρχείο "$1" δεν μπορεί να διαγραφεί, γιατί δεν υπάρχει.',
'filedelete-old-unregistered'     => 'Η συγκεκριμένη αναθεώρηση αρχείου "$1" δεν υπάρχει στη βάση δεδομένων.',
'filedelete-current-unregistered' => 'Το συγκεκριμένο αρχείο "$1" δεν υπάρχει στη βάση δεδομένων.',
'filedelete-archive-read-only'    => 'Το αρχείο καταλόγου "$1" είναι μη εγγράψιμο από τον διακομιστή.',

# Browsing diffs
'previousdiff' => "&larr; Δείτε την προηγούμενη 'διαφορά'",
'nextdiff'     => "Μετάβαση στην επόμενη 'διαφορά' &rarr;",

# Media information
'mediawarning'         => "'''Προειδοποίηση''': Το αρχείο αυτό μπορεί να περιέχει κακοπροαίρετο κώδικα που μπορεί να βλάψει το σύστημα του υπολογιστή σας.

<hr />",
'imagemaxsize'         => 'Περιορισμός του μεγέθους των εικόνων (στις σελίδες περιγραφής εικόνων) σε:',
'thumbsize'            => 'Μεγεθος μινιατούρας:',
'widthheightpage'      => '$1×$2, $3 σελίδες',
'file-info'            => '(μέγεθος αρχείου: $1, τύπος MIME: $2)',
'file-info-size'       => '($1 × $2 εικονοστοιχεία, μέγεθος αρχείου: $3, τύπος MIME: $4)',
'file-nohires'         => '<small>Δεν διατίθεται υψηλότερη ανάλυση.</small>',
'svg-long-desc'        => "(Αρχείο SVG, κατ' όνομα $1 × $2 εικονοστοιχεία, μέγεθος αρχείου: $3)",
'show-big-image'       => 'Πλήρης ανάλυση',
'show-big-image-thumb' => '<small>Μέγεθος αυτής της προεπισκόπησης: $1 × $2 εικονοστοιχεία</small>',

# Special:Newimages
'newimages'    => 'Πινακοθήκη νέων εικόνων',
'showhidebots' => '($1 bots)',
'noimages'     => 'Δεν υπάρχουν εικόνες.',

# Bad image list
'bad_image_list' => 'Η σύνταξη είναι ως εξής:

Μόνο τα αντικείμενα λίστας (γραμμές που ξεκινάνε με *) λαμβάνονται υπόψιν. Ο πρώτος σύνδεσμος σε μια γραμμή πρέπει να είναι σύνδεσμος σε μια κακή εικόνα.
Οποιοιδήποτε σύνδεσμοι ακολουθούν στην ίδια γραμμή θεωρούνται εξαιρέσεις, δηλαδή σελίδες όπου η εικόνα μπορεί να συναντάται σε σύνδεση.',

# Metadata
'metadata'          => 'Μεταδεδομένα',
'metadata-help'     => 'Αυτό το αρχείο περιέχει πρόσθετες πληροφορίες, πιθανόν από την ψηφιακή φωτογραφική μηχανή ή τον σαρωτή που χρησιμοποιήθηκε για την δημιουργία ή την ψηφιοποίησή της. Αν το αρχείο έχει τροποποιηθεί από την αρχική του κατάσταση, ορισμένες λεπτομέρειες πιθανόν να μην αντιστοιχούν πλήρως στην τροποποιημένη εικόνα.',
'metadata-expand'   => 'Εμφάνιση εκτεταμένων λεπτομερειών',
'metadata-collapse' => 'Απόκρυψη εκτεταμένων λεπτομερειών',
'metadata-fields'   => 'Τα πεδία μεταδεδομένων EXIF που υπάρχουν σε αυτό το μήνυμα θα
περιλαμβάνονται στη σελίδα εμφάνισης εικόνας όταν ο πίνακας μεταδεδομένων
θα αποκρύπτεται. Τα άλλα πεδία θα είναι κρυμμένα από προεπιλογής.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Πλάτος',
'exif-imagelength'                 => 'Υψος',
'exif-bitspersample'               => 'Bits ανά στοιχείο',
'exif-compression'                 => 'Σχήμα συμπίεσης',
'exif-photometricinterpretation'   => 'Σύνθεση των pixel',
'exif-orientation'                 => 'Προσαντολισμός',
'exif-samplesperpixel'             => 'Αριθμός στοιχείων',
'exif-planarconfiguration'         => 'Διάταξη δεδομένων',
'exif-ycbcrsubsampling'            => 'Αναλογικό δείγμα σε φωτεινότητα και χρώμα',
'exif-ycbcrpositioning'            => 'Ρύθμιση φωτεινότητας και χρώματος',
'exif-xresolution'                 => 'Οριζόντια ανάλυση',
'exif-yresolution'                 => 'Κατακόρυφη ανάλυση',
'exif-resolutionunit'              => 'Μονάδα μέτρησης ανάλυσης X και Y',
'exif-stripoffsets'                => 'Τοποθέτηση δεδομένων εικόνας',
'exif-rowsperstrip'                => 'Αριθμός σειρών ανά λωρίδα',
'exif-stripbytecounts'             => 'Bytes ανά συμπιεσμένη λωρίδα',
'exif-jpeginterchangeformat'       => 'Μετάθεση σε JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes δεδομένων JPEG',
'exif-transferfunction'            => 'Λειτουργία μεταφοράς',
'exif-whitepoint'                  => 'Χρωματικός προσδιορισμός λευκού',
'exif-primarychromaticities'       => 'Πρωτεύοντες χρωματισμοί',
'exif-ycbcrcoefficients'           => 'Συντελεστές μητρών μετασχηματισμού χρώματος',
'exif-referenceblackwhite'         => 'Ζεύγος μαύρων και άσπρων αξιών αναφοράς',
'exif-datetime'                    => 'Δηλώστε την ημερομηνία και την ώρα της επεξεργασίας.',
'exif-imagedescription'            => 'Τίτλος εικόνας',
'exif-make'                        => 'Κατασκευαστής φωτογραφικής μηχανής',
'exif-model'                       => 'Μοντέλο φωτογραφικής μηχανής',
'exif-software'                    => 'Λογισμικό που χρησιμοποιήθηκε',
'exif-artist'                      => 'Δημιουργός',
'exif-copyright'                   => 'Ιδιοκτήτης του copyright',
'exif-exifversion'                 => 'Έκδοση exif',
'exif-flashpixversion'             => 'Υποστηριζόμενη έκδοση Flashpix',
'exif-colorspace'                  => 'Χρωματική περιοχή',
'exif-componentsconfiguration'     => 'Νόημα του κάθε στοιχείου',
'exif-compressedbitsperpixel'      => 'Κατάσταση συμπίεσης εικόνας',
'exif-pixelydimension'             => 'Έγκυρο πλάτος εικόνας',
'exif-pixelxdimension'             => 'Έγκυρο ύψος εικόνας',
'exif-makernote'                   => 'Σημειώσεις του κατασκευαστή',
'exif-usercomment'                 => 'Σχόλια χρήστη',
'exif-relatedsoundfile'            => 'Σχετικό αρχείο ήχου',
'exif-datetimeoriginal'            => 'Ημερομηνία και ώρα της παραγωγής ψηφιακών δεδομένων',
'exif-datetimedigitized'           => 'Ημερομηνία και ώρα της μετατροπής σε ψηφιακή μορφή',
'exif-subsectime'                  => 'ΗμερομηνίαΏρα κλάσματα δευτερολέπτου',
'exif-subsectimeoriginal'          => 'ΗμερομηνίαΏραΑρχικά κλάσματα δευτερολέπτου',
'exif-subsectimedigitized'         => 'ΗμερομηνίαΏρα κλάσματα δευτερολέπτου ψηφιοποίησης',
'exif-exposuretime'                => 'Χρόνος έκθεσης',
'exif-exposuretime-format'         => '$1 δευτ ($2)',
'exif-fnumber'                     => 'Αριθμός F',
'exif-exposureprogram'             => 'Πρόγραμμα έκθεσης',
'exif-spectralsensitivity'         => 'Ευαισθησία φάσματος',
'exif-isospeedratings'             => 'Βαθμολόγηση ταχύτητας ISO',
'exif-oecf'                        => 'Οπτικοηλεκτρονικός συντελεστής μετατροπής',
'exif-shutterspeedvalue'           => 'Ταχύτητα κλείστρου',
'exif-aperturevalue'               => 'Διάφραγμα',
'exif-brightnessvalue'             => 'Φωτεινότητα',
'exif-exposurebiasvalue'           => 'Προτεραιότητα έκθεσης',
'exif-maxaperturevalue'            => 'Μέγιστο διάφραγμα ξηράς',
'exif-subjectdistance'             => 'Απόσταση αντικειμένου',
'exif-meteringmode'                => 'Κατάσταση λειτουργίας φωτόμετρου',
'exif-lightsource'                 => 'Πηγή φωτός',
'exif-flash'                       => 'Φλας',
'exif-focallength'                 => 'Εστιακή απόσταση του φακού',
'exif-subjectarea'                 => 'Θεματική περιοχή',
'exif-flashenergy'                 => 'Ενέργεια του φλας',
'exif-spatialfrequencyresponse'    => 'Χωρική απόκριση συχνότητας',
'exif-focalplanexresolution'       => 'Ανάλυση εστιακού επιπέδου Χ',
'exif-focalplaneyresolution'       => 'Ανάλυση εστιακού πειπέδου Υ',
'exif-focalplaneresolutionunit'    => 'Μονάδα μέτρησης ανάλυσης εστιακού επιπέδου',
'exif-subjectlocation'             => 'Τοποθέτηση του αντικειμένου',
'exif-exposureindex'               => 'Δείκτης έκθεσης',
'exif-sensingmethod'               => 'Μέθοδος αισθητήρα',
'exif-filesource'                  => 'Πηγή αρχείου',
'exif-scenetype'                   => 'Τύπος σκηνής',
'exif-cfapattern'                  => 'Πρότυπο CFA',
'exif-customrendered'              => 'Ειδική επεξεργασία εικόνας',
'exif-exposuremode'                => 'Κατάσταση λειτουργίας έκθεσης',
'exif-whitebalance'                => 'Ισορροπία των λευκών',
'exif-digitalzoomratio'            => 'Αναλογία ψηφιακού zoom',
'exif-focallengthin35mmfilm'       => 'Εστιακή απόσταση σε φιλμ 35 mm',
'exif-scenecapturetype'            => 'Τύπος σύλληψης της σκηνής',
'exif-gaincontrol'                 => 'Έλεγχος πεδίου',
'exif-contrast'                    => 'Αντίθεση',
'exif-saturation'                  => 'Κορεσμός',
'exif-sharpness'                   => 'Όξυνση',
'exif-devicesettingdescription'    => 'Περιγραφή των ρυθμίσεων του μηχανήματος',
'exif-subjectdistancerange'        => 'Περιοχή διακύμανσης της απόστασης του αντικειμένου',
'exif-imageuniqueid'               => 'Μονοσήμαντη ταυτοποίηση εικόνας',
'exif-gpsversionid'                => 'Έκδοση με GPS tag',
'exif-gpslatituderef'              => 'Βόρειο ή Νότιο γεωγραφικό πλάτος',
'exif-gpslatitude'                 => 'Γεωγραφικό πλάτος',
'exif-gpslongituderef'             => 'Ανατολικό ή Δυτικό γεωγραφικό μήκος',
'exif-gpslongitude'                => 'Γεωγραφικό μήκος',
'exif-gpsaltituderef'              => 'Αναφορές υψομέτρου',
'exif-gpsaltitude'                 => 'Υψόμετρο',
'exif-gpstimestamp'                => 'Ώρα GPS (ατομικό ρολόι)',
'exif-gpssatellites'               => 'Δορυφόροι που χρησιμοποιήθηκαν για τις μετρήσεις',
'exif-gpsstatus'                   => 'Κατάσταση δέκτη',
'exif-gpsmeasuremode'              => 'Τρόπος λειτουργίας μετρήσεων',
'exif-gpsdop'                      => 'Ακρίβεια μέτρησης',
'exif-gpsspeedref'                 => 'Μονάδα μέτρησης ταχύτητας',
'exif-gpsspeed'                    => 'Ταχύτητα δέκτη GPS',
'exif-gpstrackref'                 => 'Αναφορές για την κατεύθυνση της κίνησης',
'exif-gpstrack'                    => 'Κατεύθυνση κίνησης',
'exif-gpsimgdirectionref'          => 'Αναφορές για την κατεύθυνση της εικόνας',
'exif-gpsimgdirection'             => 'Κατεύθυνση της εικόνας',
'exif-gpsmapdatum'                 => 'Στοιχεία γεωδετικών μετρήσεων ΄που έχουν χρησιμοποιηθεί',
'exif-gpsdestlatituderef'          => 'Αναφορές για το γεωγραφικό πλάτος του προορισμού',
'exif-gpsdestlatitude'             => 'Αναφορές γεωγραφικού πλάτους',
'exif-gpsdestlongituderef'         => 'Αναφορές για το γεωγραφικό μήκος του προορισμού',
'exif-gpsdestlongitude'            => 'Γεωγραφικό πλάτος προορισμού',
'exif-gpsdestbearingref'           => 'Αναφορές για τις συντεταγμένες προορισμού',
'exif-gpsdestbearing'              => 'Συντεταγμένες προορισμού',
'exif-gpsdestdistanceref'          => 'Αναφορές για την απόσταση μέχρι τον προορισμό',
'exif-gpsdestdistance'             => 'Απόσταση μέχρι τον προορισμό',
'exif-gpsprocessingmethod'         => 'Όνομα μεθόδου επεξεργασίας GPS',
'exif-gpsareainformation'          => 'Όνομα περιοχής GPS',
'exif-gpsdatestamp'                => 'Ημερομηνία GPS',
'exif-gpsdifferential'             => 'Διαφορική διόρθωση GPS',

# EXIF attributes
'exif-compression-1' => 'Έχει αποσυμπιεστεί.',

'exif-unknowndate' => 'Άγνωστη ημερομηνία',

'exif-orientation-1' => 'Φυσικός', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Έχει αντιστραφεί οριζόντια.', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Έχει περιστραφεί κατά 180° μοίρες.', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Έχει αντιστραφεί κατακόρυφα.', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Έχει περιστραφεί κατά 90° μοίρες με φορά αντίθετα προς τη φορά των δεικτών του ρολογιού και έχει αντιστραφεί κατακόρυφα.', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Έχει περιστραφεί κατά 90° μοίρες κατά τη φορά των δεικτών του ρολογιού.', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Έχει περιστραφεί κατά 90° μοίρες κατά τη φορά των δεικτών του ρολογιού και έχει αντιστραφεί κατακόρυφα.', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Έχει περιστραφή κατά 90° μοίρες αντίθετα προς τη φορά των δεικτών του ρολογιού.', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'πεπλατυσμένος σχηματισμός',
'exif-planarconfiguration-2' => 'επίπεδος σχηματισμός',

'exif-componentsconfiguration-0' => 'δεν υπάρχει',

'exif-exposureprogram-0' => 'Δεν έχει προκαθοριστεί',
'exif-exposureprogram-1' => 'Χειροκίνητο',
'exif-exposureprogram-2' => 'Κανονικό πρόγραμμα',
'exif-exposureprogram-3' => 'Προτεραιότητα διαφράγματος',
'exif-exposureprogram-4' => 'Προτεραιότητα κλείστρου',
'exif-exposureprogram-5' => 'Δημιουργικό πρόγραμμα (με προτεραιότητα το βάθος πεδίου)',
'exif-exposureprogram-6' => 'Δημιουργικό πρόγραμμα (με προτεραιόττηα την ταχύτητα του κλείστρου)',
'exif-exposureprogram-7' => 'Επιλογή λειτουργίας "πορτραίτου" (για φωτογραφίες closeup με το φόντο εκτός εστίασης)',
'exif-exposureprogram-8' => 'Επιλογή λειτουργίας "τοπίου" (για φωτογραφίες τοπίου με εστιασμένο φόντο)',

'exif-subjectdistance-value' => '$1 μέτρα',

'exif-meteringmode-0'   => 'Άγνωστη',
'exif-meteringmode-1'   => 'Μέση τιμή',
'exif-meteringmode-2'   => 'Μέση τιμή με έμφαση στο κέντρο',
'exif-meteringmode-3'   => 'Ένα σημείο',
'exif-meteringmode-4'   => 'Πολλά σημεία',
'exif-meteringmode-5'   => 'Μοτίβο',
'exif-meteringmode-6'   => 'Μερική',
'exif-meteringmode-255' => 'Άλλο',

'exif-lightsource-0'   => 'Άγνωστη',
'exif-lightsource-1'   => 'Φως ημέρας',
'exif-lightsource-2'   => 'Φωσφορίζον',
'exif-lightsource-3'   => 'Tungsten (φωτισμός από λυχνίες πυράκτωσης)',
'exif-lightsource-4'   => 'Φλας',
'exif-lightsource-9'   => 'Αίθριος καιρός',
'exif-lightsource-10'  => 'Συννεφιά',
'exif-lightsource-11'  => 'Σκιά',
'exif-lightsource-12'  => 'Φως ημέρας φωσφορίζον (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Λευκό φως ημέρας  (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Κρύο λευκό φως fluorescent (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Λευκό φως φωσφορίζον (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Τυποποιημένος φωτισμός A',
'exif-lightsource-18'  => 'Τυποποιημένος φωτισμός B',
'exif-lightsource-19'  => 'Τυποποιημένος φωτισμός C',
'exif-lightsource-24'  => 'Βολφραίμιο του εργαστηρίου ISO',
'exif-lightsource-255' => 'Άλλη πηγή φωτός',

'exif-focalplaneresolutionunit-2' => 'ίντσες',

'exif-sensingmethod-1' => 'Δεν έχει καθοριστεί',
'exif-sensingmethod-2' => 'Αισθητήρας χρωματικής περιοχής ενός τσιπ',
'exif-sensingmethod-3' => 'Αισθητήρας χρωματικής περιοχής δύο τσιπ',
'exif-sensingmethod-4' => 'Αισθητήρας χρωματικής περιοχής ενός τσιπ',
'exif-sensingmethod-5' => 'Περιοχή συνεχούς χρώματος',
'exif-sensingmethod-7' => 'Τριγραμμικός αισθητήρας',
'exif-sensingmethod-8' => 'Γραμμικό συνεχές χρώμα',

'exif-scenetype-1' => 'Εικόνα που φωτογραφήθηκε απ` ευθείας',

'exif-customrendered-0' => 'Κανονική επεξεργασία',
'exif-customrendered-1' => 'Ειδική επεξεργασία',

'exif-exposuremode-0' => 'Αυτόματη έκθεση',
'exif-exposuremode-1' => 'Χειροκίνητη έκθεση',
'exif-exposuremode-2' => 'Αυτόματο bracket',

'exif-whitebalance-0' => 'Αυτόματη ισορροπία των λευκών',
'exif-whitebalance-1' => 'Χειροκίνητη ισορροπία των λευκών',

'exif-scenecapturetype-0' => 'Συνήθης',
'exif-scenecapturetype-1' => 'Τοπίο',
'exif-scenecapturetype-2' => 'Πορτραίτο',
'exif-scenecapturetype-3' => 'Νυκτερινή σκηνή',

'exif-gaincontrol-0' => 'Κανένα',
'exif-gaincontrol-1' => 'Χαμηλό κέρδος επάνω',
'exif-gaincontrol-2' => 'Υψηλό κέρδος επάνω',
'exif-gaincontrol-3' => 'Χαμηλό κέρδος κάτω',
'exif-gaincontrol-4' => 'Υψηλό κέρδος κατω',

'exif-contrast-0' => 'Φυσικό',
'exif-contrast-1' => 'Απαλό',
'exif-contrast-2' => 'Ισχυρό',

'exif-saturation-0' => 'Φυσικός',
'exif-saturation-1' => 'Χαμηλός κορεσμός',
'exif-saturation-2' => 'Υψηλός κορεσμός',

'exif-sharpness-0' => 'Φυσική',
'exif-sharpness-1' => 'Απαλή',
'exif-sharpness-2' => 'Σκληρή',

'exif-subjectdistancerange-0' => 'Άγνωστη',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Κοντινή λήψη',
'exif-subjectdistancerange-3' => 'Μακρίνή λήψη',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Βόρειο γεωγραφικό πλάτος',
'exif-gpslatitude-s' => 'Νότιο γεωγραφικό πλάτος',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Ανατολικό γεωγραφικό μήκος',
'exif-gpslongitude-w' => 'Δυτικό γεωγραφικό μήκος',

'exif-gpsstatus-a' => 'Μέτρηση εν εξελίξει',
'exif-gpsstatus-v' => 'Διαλειτουργικότητα μετρήσεων',

'exif-gpsmeasuremode-2' => 'μέτρηση δύο διαστάσεων',
'exif-gpsmeasuremode-3' => 'μέτρηση τριών διαστάσεων',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Χιλιόμετρα/ώρα',
'exif-gpsspeed-m' => 'Μίλια/ώρα',
'exif-gpsspeed-n' => 'Κόμβοι',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Πραγματική κατεύθυνση',
'exif-gpsdirection-m' => 'Μαγνητική κατεύθυνση',

# External editor support
'edit-externally'      => 'Επεξεργαστείτε το συγκεκριμένο αρχείο χρησιμοποιώντας μια από τις εξωτερικές εφαρμογές.',
'edit-externally-help' => 'Για περισσότερες πληροφορίες ακολουθήστε το σύνδεσμο: [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'όλες',
'imagelistall'     => 'όλες',
'watchlistall2'    => 'όλες',
'namespacesall'    => 'όλα',
'monthsall'        => 'όλα',

# E-mail address confirmation
'confirmemail'            => 'Επαλήθευση διεύθυνσης e-mail',
'confirmemail_noemail'    => 'Δεν έχετε ορίσει μια έγκυρη διεύθυνση ηλεκτρονικού ταχυδρομείου στις [[Special:Preferences|προτιμήσεις χρήστη]] σας.',
'confirmemail_text'       => 'Το σύστημα χρειάζεται να επαληθεύσει τη διεύθυνση e-mail που δώσατε για να χρησιμοποιήσετε τις δυνατότητες αλληλογραφίας. Κάνετε κλικ στο παρακάτω κουμπί και θα σας αποσταλεί μήνυμα επαλήθευσης στη διεύθυνσή σας. Στο μήνυμα αυτό θα εμφανίζεται ένας σύνδεσμος που Θα περιέχει τον κωδικό επαλήθευσης -ακολουθήστε το σύνδεσμο αυτό για να μπορέσει το σύστημα να επαληθεύσει τη διεύθυνση αλληλογραφίας σας.',
'confirmemail_pending'    => '<div class="error">
Ένας κωδικός επιβεβαίωσης σας έχει ήδη σταλεί μέσω μηνύματος e-mail. Αν δημιουργήσατε
πρόσφατα το λογαριασμό σας, μπορεί να θέλετε να περιμένετε μερικά λεπτά
για να φτάσει αυτό πριν προσπαθήσετε να ζητήσετε ένα νέο κωδικό.
</div>',
'confirmemail_send'       => 'Αποστολή κωδικού επαλήθευσης με e-mail .',
'confirmemail_sent'       => 'Το μήνυμα επαλήθευσης έχει σταλεί, ελέγξτε την αλληλογραφία σας.',
'confirmemail_oncreate'   => 'Ένας κωδικός επιβεβαίωσης σας έχει σταλεί στην διεύθυνση e-mail σας.
Αυτός ο κωδικός δεν είναι απαραίτητος για να συνδεθείτε, αλλά θα χρειαστεί
να τον παρέχετε πριν ενεργοποιήσετε οποιαδήποτε χαρακτηριστικά βασισμένα σε e-mail, σε αυτό το wiki.',
'confirmemail_sendfailed' => 'Δεν ήταν δυνατή η αποστολή του e-mail επαλήθευσης. Ελέγξτε την ηλεκτρονική διεύθυνση που συμπληρώσατε για άκυρους χαρακτήρες.

Το πρόγραμμα ηλεκτρονικού ταχυδρομείου επέστρεψε το ακόλουθο μήνυμα: $1',
'confirmemail_invalid'    => 'Λάθος κωδικός επαλήθευσης. Είναι πιθανόν ο κωδικός σας να έχει λήξει.',
'confirmemail_needlogin'  => 'Χρειάζετε να $1 για να επιβεβαιώσετε τη διεύθυνση e-mail σας.',
'confirmemail_success'    => 'Η ηλεκτρονική σας διεύθυνση σας επαληθεύτηκε. Μπορείτε πλέον να συνδεθείτε και να απολαύσετε τις δυνατότητες του Wiκi.',
'confirmemail_loggedin'   => 'Η ηλεκτρονική σας διεύθυνση επαληθεύτηκε.',
'confirmemail_error'      => 'Παρουσιάστηκε λάθος κατά την αποθήκευση των ρυθμίσεών σας.',
'confirmemail_subject'    => 'Επαλήθευση ηλεκτρονικής διεύθυνσης του {{SITENAME}}',
'confirmemail_body'       => 'Κάποιος, πιθανόν εσείς από τη διεύθυνση IP $1, δημιούργησε στο {{SITENAME}} ένα λογαριασμό χρήστη "$2" με τη συγκεκριμένη ηλεκτρονική διεύθυνση.

Για να επιβεβαιώσετε ότι αυτός ο λογαριασμός χρήστη ανήκει πραγματικά σε εσάς και για να ενεργοποιηθούν οι δυνατότητες e-mail του {{SITENAME}}, ακολουθήστε αυτό το σύνδεσμο:

$3

Αν ο χρήστης που δημιούργησε το συγκεκριμένο λογαριασμό δεν είστε εσείς, μην ακολουθήστε το σύνδεσμο. Ο κωδικός επιβεβαίωσης θα λήξει στις $4',

# Scary transclusion
'scarytranscludedisabled' => '[Η ενσωμάτωση εξωτερικών ιστοσελίδων σε αυτό το Wiki είναι απενεργοποιημένη.]',
'scarytranscludefailed'   => '[Λυπούμαστε, η προσκόμιση προτύπου για το $1 απέτυχε.]',
'scarytranscludetoolong'  => '[Λυπούμαστε η διεύθυνση URL είναι πολύ μεγάλη.]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Trackbacks για αυτό το άρθρο:<br />
$1
</div>',
'trackbackremove'   => ' ([$1΄- Διαγραφή])',
'trackbacklink'     => 'Επιστροφή για αναζήτηση',
'trackbackdeleteok' => 'Η επιστροφή για αναζήτηση έχει διαγραφεί επιτυχώς.',

# Delete conflict
'deletedwhileediting' => 'Προσοχή: Αυτή η σελίδα έχει διαγραφεί αφότου ξεκινήσατε την επεξεργασία!',
'confirmrecreate'     => "Ο χρήστης [[User:$1|$1]] ([[User talk:$1|συζήτηση]]) διέγραψε αυτή τη σελίδα αφότου ξεκινήσατε την επεξεργασία με αιτιολόγηση:
: ''$2''
Παρακαλώ επιβεβαιώστε ότι θέλετε πραγματικά να ξαναδημιουργήσετε αυτή τη σελίδα.",
'recreate'            => 'Επαναδημιουργία',

# HTML dump
'redirectingto' => 'Ανακατεύθυνση στη σελίδα [[$1]]...',

# action=purge
'confirm_purge'        => 'Καθαρισμός της λανθάνουσας μνήμης αυτής της σελίδας;

$1',
'confirm_purge_button' => 'Εντάξει',

# AJAX search
'searchcontaining' => "Αναζήτηση για άρθρα που περιέχουν ''$1''.",
'searchnamed'      => "Αναζήτηση για άρθρα με την ονομασία ''$1''.",
'articletitles'    => "Άρθρα που αρχίζουν από ''$1''",
'hideresults'      => 'Απόκρυψη αποτελεσμάτων',

# Multipage image navigation
'imgmultipageprev'   => '← προηγούμενη σελίδα',
'imgmultipagenext'   => 'επόμενη σελίδα →',
'imgmultigo'         => 'Πήγαινε!',
'imgmultigotopre'    => 'Πήγαινε στη σελίδα',
'imgmultiparseerror' => 'Αυτό το αρχείο εικόνας φαίνεται ότι είναι φθαρμένο ή εσφαλμένο, οπότε ο ιστότοπος {{SITENAME}} δεν μπορεί να ανακτήσει μια λίστα των σελίδων.',

# Table pager
'ascending_abbrev'         => 'αυξ',
'descending_abbrev'        => 'φθιν',
'table_pager_next'         => 'Επόμενη σελίδα',
'table_pager_prev'         => 'Προηγούμενη σελίδα',
'table_pager_first'        => 'Πρώτη σελίδα',
'table_pager_last'         => 'Τελευταία σελίδα',
'table_pager_limit'        => 'Εμφάνισε $1 στοιχεία ανά σελίδα',
'table_pager_limit_submit' => 'Πήγαινε',
'table_pager_empty'        => 'Κανένα αποτέλεσμα',

# Auto-summaries
'autosumm-blank'   => 'Αφαίρεση όλου του περιεχομένου από σελίδα',
'autosumm-replace' => "Αντικατάσταση σελίδας με '$1'",
'autoredircomment' => 'Ανακατεύθυνση στη σελίδα [[$1]]',
'autosumm-new'     => 'Νέα σελίδα: $1',

# Live preview
'livepreview-loading' => 'Φόρτωση…',
'livepreview-ready'   => 'Φόρτωση… Έτοιμο!',
'livepreview-failed'  => 'Η άμεση προεπισκόπηση απέτυχε!
Δοκιμάστε την κανονική προεπισκόπηση.',
'livepreview-error'   => 'Αποτυχία σύνδεσης: $1 "$2"
Δοκιμάστε την κανονική προεπισκόπηση.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Αλλαγές νεότερες από $1 δευτερόλεπτα μπορεί να μην φαίνονται σε αυτή τη λίστα.',
'lag-warn-high'   => 'Εξαιτίας υψηλής καθυστέρησης της βάσης δεδομένων του εξυπηρετητή, αλλαγές νεότερες από $1 δευτερόλεπτα
μπορεί να μην φαίνονται σε αυτή τη λίστα.',

# Watchlist editor
'watchlistedit-numitems'       => 'Η λίστα παρακολούθησής σας περιέχει {{PLURAL:$1|1 σελίδα|$1 σελίδες}}, χωρίς να συμπεριλαμβάνονται οι σελίδες συζήτησης.',
'watchlistedit-noitems'        => 'Η λίστα παρακολούθησης σας δεν περιέχει καμιά εγγραφή.',
'watchlistedit-normal-title'   => 'Επεξεργασία λίστας παρακολούθησης',
'watchlistedit-normal-legend'  => 'Αφαίρεση σελίδων από τη λίστα παρακολούθησης',
'watchlistedit-normal-explain' => 'Οι σελίδες στη λίστα παρακολούθησής σας φαίνονται παρακάτω. Για να αφαιρέσετε μια σελίδα σημειώστε 
	το κουτάκι δίπλα από τον τίτλο και κάντε κλικ στο Αφαίρεση Σελίδων. Μπορείτε επίσης να [[Special:Watchlist/raw|επεξεργαστείτε την πηγαία λίστα]],
	ή [[Special:Watchlist/clear|να αφαιρέσετε όλες τις σελίδες]].',
'watchlistedit-normal-submit'  => 'Αφαίρεση Σελίδων',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 τίτλος|$1 τίτλοι}} αφαιρέθηκαν από τη λίστα παρακολούθησής σας:',
'watchlistedit-raw-title'      => 'Επεξεργασία πηγαίας λίστας παρακολούθησης',
'watchlistedit-raw-legend'     => 'Διόρθωσε την αδούλευτη λίστα παρακολούθησης',
'watchlistedit-raw-explain'    => 'Οι σελίδες στη λίστα παρακολούθησής σας φαίνονται παρακάτω και μπορείτε να τις επεξεργαστείτε
	προσθαφαιρώντας από τη λίστα, έναν τίτλο ανά σειρά. Όταν ολοκληρώσετε την επεξεργασία, κάντε κλικ στο Ενημέρωση Λίστας Παρακολούθησης.
	Μπορείτε επίσης να χρησιμοποιήσετε την [[Special:Watchlist/edit|προεπιλεγμένη μέθοδο επεξεργασίας]].',
'watchlistedit-raw-titles'     => 'Σελίδες:',
'watchlistedit-raw-submit'     => 'Ενημέρωση Λίστας Παρακολούθησης',
'watchlistedit-raw-done'       => 'Η λίστα παρακολούθησής σας ενημερώθηκε.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 σελίδα|$1 σελίδες}} προστέθηκαν:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 σελίδα|$1 σελίδες}} αφαιρέθηκαν:',

# Watchlist editing tools
'watchlisttools-view' => 'Δείτε τις σχετικές αλλαγές',
'watchlisttools-edit' => 'Δες και διόρθωσε την λίστα παρακολούθησης',
'watchlisttools-raw'  => 'Διόρθωσε την αδούλευτη λιστα παρακολούθησης',

);
