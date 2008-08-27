<?php
/** Ancient Greek (Ἀρχαία ἑλληνικὴ)
 *
 * @ingroup Language
 * @file
 *
 * @author AndreasJS
 * @author Lefcant
 * @author LeighvsOptimvsMaximvs
 * @author Neachili
 * @author Nychus
 * @author Omnipaedista
 * @author SPQRobin
 * @author Yannos
 * @author ZaDiak
 */

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y xg j',
	'ymd both' => 'H:i, Y xg j',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Ὑπογραμμίζειν συνδέσμους:',
'tog-highlightbroken'         => 'Μορφοῦν ἀπωλομένους συνδέσμους <a href="" class="new">οὐτωσί</a> (ἄλλως: οὐτωσί <a href="" class="internal">;</a>).',
'tog-justify'                 => 'Στοιχίζειν παραγράφους',
'tog-hideminor'               => 'Κρύπτειν μικρὰς ἐγγραφὰς ἐν προσφάτοις ἀλλαγαῖς',
'tog-extendwatchlist'         => 'Ἐφορώμενα ἐκτείνειν ἵνα ἅπασαι φανῶσιν αἱ ἁρμόδιοι ἀλλαγαὶ',
'tog-usenewrc'                => 'Προσκεκοσμημέναι πρόσφατοι ἀλλαγαί (JavaScript)',
'tog-numberheadings'          => 'Ἐξαριθμεῖν ἐπικεφαλίδας αὐτομάτως',
'tog-showtoolbar'             => 'Δεικνύναι τὴν τῶν ἐργαλείων μεταγραφῆς μετώπην (JavaScript)',
'tog-editondblclick'          => 'Ἐπὶ δέλτων δὶς πιέσας, μετάγραψον αὐτάς (JavaScript)',
'tog-editsection'             => 'Τμῆμα διὰ συνδέσμου [μεταγράφειν] μεταγράφειν παρέχειν',
'tog-editsectiononrightclick' => 'Τμῆμα μεταγράφειν παρέχειν <br /> διὰ τίτλον δεξιῷ ὀμφαλῷ θλίβειν (JavaScript)',
'tog-showtoc'                 => 'Δεικνύναι πίνακα περιεχομένων (ἐν δέλτοις περιεχούσαις πλείους τῶν 3 ἐπικεφαλίδων)',
'tog-rememberpassword'        => 'Ἐνθυμεῖσθαι τὴν ἐμὴν σύνδεσιν ἐν τῇδε τῇ ὑπολογιστικῇ μηχανῇ',
'tog-editwidth'               => 'Πλαίσιον μεταγραφῆς εἰς πλῆρες πλάτος',
'tog-watchcreations'          => 'Προστιθέναι τὰς δέλτους ἃς ποιῶ τοῖς ἐφορωμένοις μου',
'tog-watchdefault'            => 'Προστιθέναι τὰς δέλτους ἃς μεταγράφω τοῖς ἐφορωμένοις μου',
'tog-watchmoves'              => 'Προστιθέναι τὰς δέλτους ἃς κινῶ τοῖς ἐφορωμένοις μου',
'tog-watchdeletion'           => 'Προστιθέναι τὰς δέλτους ἃς διαγράφω τοῖς ἐφορωμένοις μου',
'tog-minordefault'            => 'Σημαίνειν ὡς ἥττονας ἁπάσας τὰς μεταγραφὰς προκαθωρισμένως',
'tog-previewontop'            => 'Δεικνύναι τὸ προεπισκοπεῖν πρὸ τοῦ κυτίου μεταγραφῆς',
'tog-previewonfirst'          => 'Τῆς πρώτης μεταγραφῆς, δεικνύναι τὸ προεπισκοπεῖν',
'tog-nocache'                 => 'Ἀπενεργοποιεῖν τὸ κρύπτειν τὰς δέλτους',
'tog-enotifwatchlistpages'    => 'Ἄγγειλόν μοι ὅτε δέλτος τις ἐν τῇ ἐφοροδιαλογῇ μου μεταβάλληται',
'tog-enotifusertalkpages'     => 'Ἄγγειλόν μοι ὅτε ἡ δέλτος μου διαλέξεως χρωμένου μεταβάλληται',
'tog-enotifminoredits'        => 'Ἄγγειλόν μοι ἐπἴσης τὰς ἥττονας ἀλλαγὰς δέλτων',
'tog-enotifrevealaddr'        => 'Ἀποκαλύπτειν τὴν ταχυδρομικὴν μου διεύθυνσιν ἐν τῇ εἰδοποιητηρίᾳ ἀλληλογραφίᾳ',
'tog-shownumberswatching'     => 'Δεικνύναι ἀριθμὸν παρακολουθούντων χρηστῶν',
'tog-fancysig'                => 'Ἀκατέργασται ὑπογραφαὶ (ἄνευ αὐτομάτου συνδέσμου)',
'tog-externaleditor'          => 'Χρῆσθαι ἐξώτερον πρόγραμμα επεξεργασίας κειμένων κατὰ προεπιλογήν (διὰ εἰδικοῦς μόνον· ἀπαραίτητοι εἰδικαὶ ῥυθμίσεις τινες ἐν τῇ ὑπολογιστικῇ μηχανῇ σου)',
'tog-externaldiff'            => 'Χρῆσθαι ἐξώτερον λογισμικὸν αντιπαραβολῆς κατὰ προεπιλογήν (διὰ εἰδικοῦς μόνον· ἀπαραίτητοι εἰδικαὶ ῥυθμίσεις τινες ἐν τῇ ὑπολογιστικῇ μηχανῇ σου)',
'tog-showjumplinks'           => 'Ἐνεργοποιεῖν τοὺς "ἅλμα πρὸς" συνδέσμους προσβασιμότητος',
'tog-uselivepreview'          => 'Χρῆσθαι ἄμεσον προθεώρησιν (JavaScript) (Πειραστικόν)',
'tog-forceeditsummary'        => 'Προμήνυσόν με εἰ εἰσάγω κενὴ σύνοψιν μεταγραφῆς',
'tog-watchlisthideown'        => 'Οὐ δηλοῦν τὰς ἐμὰς μεταβολὴς ἐν τὰ ἐφορώμενά μου',
'tog-watchlisthidebots'       => 'Ἀποκρύπτειν τὰς αὐτόματους μεταγραφὰς ἐκ τῆς ἐφοροδιαλογῆς',
'tog-watchlisthideminor'      => 'Οὐ δηλοῦν τὰς μικρὰς μεταβολὰς ἐν τὰ ἐφορώμενά μου',
'tog-ccmeonemails'            => "Στεῖλον μοι ἀντίγραφα τῶν ἠλ.-ἐπιστολῶν τῶν ἀπεσταλμένων ὑπ'ἐμοῦ πρὸς ἑτέρους χρήστας",
'tog-diffonly'                => 'Οὐκ ἐμφανίζειν τὸ τῆς δέλτου περιεχόμενον ὑπὸ τῶν διαφορῶν',
'tog-showhiddencats'          => 'Κεκρυμμένας κατηγορίας δηλοῦν',

'underline-always'  => 'Ἀεὶ',
'underline-never'   => 'Οὔποτε',
'underline-default' => 'Πλοηγὸς ὡς προκαθωρισμένως',

# Dates
'sunday'        => 'Κυριακή',
'monday'        => 'Δευτέρα',
'tuesday'       => 'Τρίτη',
'wednesday'     => 'Τετάρτη',
'thursday'      => 'Πέμπτη',
'friday'        => 'Παρασκευή',
'saturday'      => 'Σάββατοv',
'sun'           => 'Κυ',
'mon'           => 'Δε',
'tue'           => 'Τρ',
'wed'           => 'Τε',
'thu'           => 'Πε',
'fri'           => 'Πα',
'sat'           => 'Σα',
'january'       => 'Ἰανουάριος',
'february'      => 'Φεβρουάριος',
'march'         => 'Μάρτιος',
'april'         => 'Ἀπρίλιος',
'may_long'      => 'Μάιος',
'june'          => 'Ἰούνιος',
'july'          => 'Ἰούλιος',
'august'        => 'Αὔγουστος',
'september'     => 'Σεπτέμβριος',
'october'       => 'Ὀκτώβριος',
'november'      => 'Νοέμβριος',
'december'      => 'Δεκέμβριος',
'january-gen'   => 'Ἰανουαρίου',
'february-gen'  => 'Φεβρουαρίου',
'march-gen'     => 'Μαρτίου',
'april-gen'     => 'Ἀπριλίου',
'may-gen'       => 'Μαΐου',
'june-gen'      => 'Ἰουνίου',
'july-gen'      => 'Ἰουλίου',
'august-gen'    => 'Αὐγούστου',
'september-gen' => 'Σεπτεμβρίου',
'october-gen'   => 'Ὀκτωβρίου',
'november-gen'  => 'Νοεμβρίου',
'december-gen'  => 'Δεκεμβρίου',
'jan'           => 'Ἰαν',
'feb'           => 'Φεβ',
'mar'           => 'Μάρ',
'apr'           => 'Ἀπρ',
'may'           => 'Μάϊ',
'jun'           => 'Ἰούν',
'jul'           => 'Ἰούλ',
'aug'           => 'Αὔγ',
'sep'           => 'Σεπτ',
'oct'           => 'Ὀκτ',
'nov'           => 'Νοέμ',
'dec'           => 'Δεκ',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Κατηγορία|Κατηγορίαι}}',
'category_header'                => 'Χρήματα ἐν γένει "$1"',
'subcategories'                  => 'Ὑποκατηγορίαι',
'category-media-header'          => 'Μέσα ἐν κατηγορίᾳ "$1"',
'category-empty'                 => "''Ταύτη ἡ κατηγορία οὐ περιλαμβάνει δέλτους τῷ παρόντι.''",
'hidden-categories'              => '{{PLURAL:$1|Κεκρυμμένη Κατηγορία|Κεκρυμμέναι Κατηγορίαι}}',
'hidden-category-category'       => 'Κεκρυμμέναι κατηγορίαι', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Ἥδε ἡ κατηγορία περίεχει μόνον τὴν ἐξῆς ὑποκατηγορίαν.|Ἥδε ἡ κατηγορία περιέχει τὴν/τὰς ἐξῆς {{PLURAL:$1|ὑποκατηγορίαν|$1 ὑποκατηγορίας}}, ἐκ συνόλου $2.}}',
'category-subcat-count-limited'  => 'Ἥδε ἡ κατηγορία περιέχει τὴν/τὰς ἐξῆς {{PLURAL:$1|ὑποκατηγορίαν|$1 ὑποκατηγορίας}}.',
'category-article-count'         => '{{PLURAL:$2|Ἥδε ἡ κατηγορία περιέχει μόνον τὴν ἐξῆς δέλτον.|Αἱ ἐξῆς {{PLURAL:$1|δέλτος ἐστὶν|$1 δέλτοι εἰσὶν}} ἐν τῇδε τῇ κατηγορίᾳ, ἐκ συνόλου $2.}}',
'category-article-count-limited' => 'Ἡ/αἱ ἐξῆς {{PLURAL:$1|δέλτος ἐστὶν|$1 δέλτοι εἰσὶν}} ἐν τῇ τρεχούσῃ κατηγορίᾳ.',
'category-file-count'            => '{{PLURAL:$2|Ἥδε ἡ κατηγορία περίεχει μόνον τὸ ἐξῆς ἀρχεῖον.|Τὸ/τὰ ἐξῆς {{PLURAL:$1|ἀρχεῖον ἐστὶν|$1 ἀρχεῖα εἰσὶν}} ἐν τῇδε τῇ κατηγορίᾳ, ἐκ συνόλου $2.}}',
'category-file-count-limited'    => 'Τὸ/τὰ ἐξῆς {{PLURAL:$1|ἀρχεῖον εἰσὶν|$1 ἀρχεῖα εἰσὶν}} ἐν τῇ τρεχούσῃ κατηγορίᾳ.',
'listingcontinuesabbrev'         => 'συνεχίζεται',

'mainpagetext'      => "<big>'''Ἡ ἐγκατάστασις τῆς MediaWiki ἦν ἐπιτυχής'''</big>",
'mainpagedocfooter' => 'Συμβουλευθήσεσθε τὸ [http://meta.wikimedia.org/wiki/Help:Contents Ὁδηγίαι τοῖς Χρωμένοις] ἵνα πληροφορηθῇτε ἐπὶ τοῦ οὐίκι λογισμικοῦ.

== Ἄρξασθε ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Κατάλογος παραμέτρων παρατάξεως]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki: τὰ πολλάκις αἰτηθέντα]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Κατάλογος διαλέξεων ἐπὶ τῶν ἐκδόσεων τῆς MediaWiki]',

'about'          => 'Περὶ',
'article'        => 'Ἡ ἐγγραφή',
'newwindow'      => '(ἀνοίξεται ἐν νέᾳ θυρίδι)',
'cancel'         => 'Ἀκυροῦν',
'qbfind'         => 'Εὑρίσκειν',
'qbbrowse'       => 'Νέμου',
'qbedit'         => 'Μεταγράφειν',
'qbpageoptions'  => 'Ἥδε ἡ δέλτος',
'qbpageinfo'     => 'Συγκείμενον',
'qbmyoptions'    => 'Δέλτοι μου',
'qbspecialpages' => 'Εἰδικαὶ δέλτοι',
'moredotdotdot'  => 'πλέον...',
'mypage'         => 'Δέλτος μου',
'mytalk'         => 'Ἡ διάλεξίς μου',
'anontalk'       => 'Διάλεκτος πρὸ τοῦδε τοῦ IP',
'navigation'     => 'Πλοήγησις',
'and'            => 'καί',

# Metadata in edit box
'metadata_help' => 'Μεταδεδομένα:',

'errorpagetitle'    => 'Σφάλμα',
'returnto'          => 'Ἐπανέρχεσθαι εἰς $1.',
'tagline'           => 'Ἀπὸ {{SITENAME}}',
'help'              => 'Βοήθεια',
'search'            => 'Ζητεῖν',
'searchbutton'      => 'Ζητεῖν',
'go'                => 'Ἰέναι',
'searcharticle'     => 'Ἰέναι',
'history'           => 'Αἱ τῆς δέλτου προτέραι',
'history_short'     => 'Αἱ προτέραι',
'updatedmarker'     => 'αἰ δέλτου ἐνήμερώσεις ἀφότε ἐπεσκέφθην αὐτὴν ὑστάτως',
'info_short'        => 'Μάθησις',
'printableversion'  => 'Ἐκτυπωτέα μορφή',
'permalink'         => 'Σύνδεσμος βέβαιος',
'print'             => 'Τυποῦν',
'edit'              => 'Μεταγράφειν',
'create'            => 'Ποιεῖν',
'editthispage'      => 'Μεταγράφειν τήνδε τὴν δέλτον',
'create-this-page'  => 'Ποιεῖν τήνδε τὴν δέλτον',
'delete'            => 'Σβεννύναι',
'deletethispage'    => 'Διαγράφειν τήνδε τὴν δέλτον',
'undelete_short'    => 'Ἐπανορθοῦν {{PLURAL:$1|ἕνα μεταγραφέν|$1 μεταγραφέντα}}',
'protect'           => 'Φυλλάττειν',
'protect_change'    => 'ἀλλάττειν φύλαξιν',
'protectthispage'   => 'Tήνδε τὴν δέλτον φυλάττειν',
'unprotect'         => 'μὴ φυλάττειν',
'unprotectthispage' => 'Tήνδε τὴν δέλτον  μὴ φυλάττειν',
'newpage'           => 'Δέλτος νέα',
'talkpage'          => 'Διαλέγε τήνδε τὴν δέλτον',
'talkpagelinktext'  => 'Διαλέγεσθαι',
'specialpage'       => 'Εἰδικὴ δέλτος',
'personaltools'     => 'Ἴδια ἐργαλεῖα',
'postcomment'       => 'Ἀποστελεῖν σχόλιόν τι',
'articlepage'       => 'Χρήματος δέλτον ὁρᾶν',
'talk'              => 'Διάλεξις',
'views'             => 'Ποσάκις ἔσκεπται',
'toolbox'           => 'Ἐργαλειοκάδος',
'userpage'          => 'Ὁρᾶν δέλτον χρωμένου',
'projectpage'       => 'Ἰδὲ δέλτον σχεδίου',
'imagepage'         => 'Ὁρᾶν μέσων δέλτον',
'mediawikipage'     => 'Ὁρᾶν δέλτον μηνυμάτων',
'templatepage'      => 'Ὁρᾶν δέλτον ἐπιγραμμάτων',
'viewhelppage'      => 'Ὁρᾶν βοηθείας δέλτον',
'categorypage'      => 'Ὁρᾶν τὴν δέλτον κατηγοριῶν',
'viewtalkpage'      => 'Ὁρᾶν διάλεκτον',
'otherlanguages'    => 'Ἀλλογλωσσιστί',
'redirectedfrom'    => '(Ἀποσταλτὸν ἀπὸ $1)',
'redirectpagesub'   => 'Ἐπανάγειν δέλτον',
'lastmodifiedat'    => 'Ἥδε ἡ δέλτος ὕστατον μετεβλήθη $2, $1.', # $1 date, $2 time
'viewcount'         => 'Ἥδε ἡ δέλτος προσεπελάσθη {{PLURAL:$1|ἅπαξ|$1-(άκ)ις}}.',
'protectedpage'     => 'Πεφυλαγμένη δέλτος',
'jumpto'            => 'Ἅλμα πρὸς:',
'jumptonavigation'  => 'περιήγησις',
'jumptosearch'      => 'ἐρευνᾶν',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Περὶ {{SITENAME}}',
'aboutpage'            => 'Project:Περί',
'bugreports'           => 'Ἀναφορὰ πλανῶν',
'bugreportspage'       => 'Project:Ἀναφορὰ πλανῶν',
'copyright'            => 'Ἡ διάθεσις τοῦδε περιεχομένου ἐστιν ὑπὸ $1.',
'copyrightpagename'    => '{{SITENAME}} πνευματικὰ δικαιώματα',
'copyrightpage'        => '{{ns:project}}:Πνευματικὰ Δικαιώματα',
'currentevents'        => 'Τὰ γιγνόμενα',
'currentevents-url'    => 'Project:Τὰ γιγνόμενα',
'disclaimers'          => 'Ἀποποιήσεις',
'disclaimerpage'       => 'Project:Γενικὴ ἀποποίησις',
'edithelp'             => 'Βοήθεια περὶ τοῦ μεταγράφειν',
'edithelppage'         => 'Help:Βοήθεια περὶ τοῦ μεταγράφειν',
'faq'                  => 'Τὰ πολλάκις αἰτηθέντα',
'faqpage'              => 'Project:Πολλάκις αἰτηθέντα',
'helppage'             => 'Help:Περιεχόμενα',
'mainpage'             => 'Κυρία Δέλτος',
'mainpage-description' => 'Κυρία Δέλτος',
'policy-url'           => 'Project:Προαίρεσις',
'portal'               => 'Πύλη πολιτείας',
'portal-url'           => 'Project:Πύλη Πολιτείας',
'privacy'              => 'Ἡ περὶ τῶν ἰδιωτικῶν προαίρεσις',
'privacypage'          => 'Project:Περὶ τῶν ἰδιωτικῶν',

'badaccess'        => 'Σφάλμα ἀδείας',
'badaccess-group0' => 'Οὐκ ἔξεστί σοι ταῦτα διαπράττειν.',

'versionrequired' => 'Ἔκδοσις $1 τῆς MediaWiki ἀπαιτεῖται',

'ok'                      => 'εἶεν',
'retrievedfrom'           => 'Ἀνακτηθεῖσα ὑπὸ "$1"',
'youhavenewmessages'      => 'Ἔχεις $1 ($2).',
'newmessageslink'         => 'νέας ἀγγελίας',
'newmessagesdifflink'     => 'ἐσχάτη μεταβολή',
'youhavenewmessagesmulti' => 'Νέας εἰσί σοι ἀγγελίας ἐν $1',
'editsection'             => 'μεταγράφειν',
'editold'                 => 'μεταγράφειν',
'viewsourceold'           => 'ὁρᾶν πηγήν',
'editsectionhint'         => 'Μεταγράφειν τὸ μέρος: $1',
'toc'                     => 'Περιεχόμενα',
'showtoc'                 => 'δεικνύναι',
'hidetoc'                 => 'κρύπτειν',
'thisisdeleted'           => 'Ὁρᾶν ἢ ἀποκαθίστασθαι $1;',
'viewdeleted'             => 'Ὁρᾶν $1;',
'restorelink'             => '{{PLURAL:$1|μία διεγραμμένη μεταγραφή|$1 διεγραμμέναι μεταγραφαί}}',
'feedlinks'               => 'Βοτήρ:',
'feed-invalid'            => 'Ἄκυρος τύπος συνδρομῆς εἰς ῥοὴν δεδομένων.',
'feed-unavailable'        => 'Αἱ ῥοαὶ οὔκ εἰσι διαθέσιμοι ἐν τῷ {{SITENAME}}',
'site-rss-feed'           => 'Ἡ τοῦ $1 RSS-παρασκευή',
'site-atom-feed'          => 'Ἡ τοῦ $1 Atom-παρασκευή',
'page-rss-feed'           => 'Βοτὴρ RSS "$1"',
'page-atom-feed'          => '"$1" Atom Ῥοή',
'red-link-title'          => '$1 (οὔπω γέγραπται)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Χρῆμα',
'nstab-user'      => 'Δέλτος χρωμένου',
'nstab-media'     => 'Δέλτος μέσων',
'nstab-special'   => 'Εἰδικόν',
'nstab-project'   => 'Δέλτος σχεδίου',
'nstab-image'     => 'Ἀρχεῖον',
'nstab-mediawiki' => 'Ἀγγελία',
'nstab-template'  => 'Πρότυπον',
'nstab-help'      => 'Βοήθεια',
'nstab-category'  => 'Κατηγορία',

# Main script and global functions
'nosuchaction'      => 'Οὐδεμία τοιούτη ἐνέργεια',
'nosuchspecialpage' => 'Οὐδεμία τοιούτη δέλτος',

# General errors
'error'                => 'Σφάλμα',
'databaseerror'        => 'Σφάλμα βάσεως δεδομένων',
'dberrortext'          => 'Σφάλμα τι συντάξεως τῆς πεύσεως βάσεως δεδομένων ἀπήντησεν,
ὅπερ ὑποδηλοῖ τὴν ὕπαρξιν πλάνης τινὸς ἐν τῷ λογισμικῷ.
Ἡ ἐσχάτη ἀποπειραθεῖσα πεῦσις βάσεως δεδομένων ἦν:
<blockquote><tt>$1</tt></blockquote>
ἐξ ἐντὸς τῆς τελέσεως "<tt>$2</tt>".
Ἡ MySQL ἐπέστρεψεν σφάλμα "<tt>$3: $4</tt>".',
'noconnect'            => 'Συγγνώμην! Τὸ ϝίκι ἀντιμετωπεῖ τεχνικὰς δυσχερείας καὶ οὐ δύναται ἐπιμειγνύναι τὴν ἐξυπηρετικὴν μηχανὴν δεδομένων.<br />
$1',
'nodb'                 => 'Ἀδύνατον τὸ ἐπιλέγειν βάσιν δεδομένων $1',
'readonly'             => 'Βάσις δεδομένων ἀποκεκλεισμένη',
'missingarticle-rev'   => '(ἀναθεώρησις#: $1)',
'missingarticle-diff'  => '(Διαφ: $1, $2)',
'internalerror'        => 'Ἐσώτερον σφάλμα',
'internalerror_info'   => 'Ἐσώτερον σφάλμα: $1',
'filecopyerror'        => 'Οὐκ ἦν δυνατὴ ἡ ἀντιγραφὴ τοῦ ἀρχείου "$1" εἰς τὸ "$2".',
'filerenameerror'      => 'Οὐκ ἦν δυνατὴ ἡ μετωνόμασις τοῦ ἀρχείου "$1" ὡς "$2".',
'filedeleteerror'      => 'Οὐκ ἦν δυνατὴ ἡ διαγραφὴ τοῦ ἀρχείου "$1".',
'directorycreateerror' => 'Οὐκ ἦν δυνατὴ ἡ δημιουργία τοῦ ἀρχειοκαταλόγου "$1".',
'filenotfound'         => 'Γραφὴ "$1" οὐχ ηὑρέθη',
'fileexistserror'      => 'Οὐκ ἦν δυνατὴ ἡ ἐγγραφὴ εἰς τὸ ἀρχεῖον "$1": τὸ ἀρχεῖον ὑπάρχει',
'unexpected'           => 'Ἀπροσδόκητος τιμή: "$1"="$2".',
'formerror'            => 'Σφάλμα: οὐ δυναμένη ἡ ὑποβολὴ τοῦ τύπου ἐστίν',
'badarticleerror'      => 'Ἡ πρᾶξις οὐκ ἐκτελέσιμος ἦν ἐν τῇδε δέλτῳ.',
'badtitle'             => 'Κακὸν τὸ ἐπώνυμον',
'badtitletext'         => 'Ἡ ἐπιγραφὴ τῆς ᾐτουμένης δέλτου ἐστὶν ἄκυρος, κενή, ἢ πρόκειται περὶ ἐσφαλμένως συνδεδεμένης ἐπιγραφῆς μεταξὺ διαφόρων οὐίκι· εἰκότως περιέχει χαρακτῆρας μὴ χρηστέους ἐν ἐπιγραφαῖς.',
'wrong_wfQuery_params' => 'Ἐσφαλμέναι παράμετροι εἰς τὸ wfQuery()<br />
Ἐνέργεια: $1<br />
Πεῦσις: $2',
'viewsource'           => 'Πηγὴν ἐπισκοπεῖν',
'viewsourcefor'        => 'διὰ τὸ $1',
'actionthrottled'      => 'Δρᾶσις ἠγχθεῖσα',
'protectedpagetext'    => 'Ἥδε ἡ δέλτος ἀποκεκλῃμένη ἐστὶν εἰς ἀποτροπὴν τοῦ μεταγράφειν.',
'viewsourcetext'       => 'Ἔξεστί σοι ὁρᾶν τε καὶ ἀντιγράφειν τὴν τῆς δέλτου πηγήν:',
'sqlhidden'            => '(πεῦσις SQL κεκρυμμένη)',
'ns-specialprotected'  => 'Αἱ εἰδικαὶ δέλτοι μὴ μεταγραπτέαι εἰσίν.',

# Virus scanner
'virus-badscanner'     => 'Κακὸς σχηματισμός: ἄγνωτος σαρωτὴς ἰῶν: <i>$1</i>',
'virus-scanfailed'     => 'Σάρωσις πταιστή (κῶδιξ $1)',
'virus-unknownscanner' => 'ἄγνωτος ἀντιιός:',

# Login and logout pages
'logouttitle'                => 'Ἀποσυνδεῖσθαι χρήστου',
'welcomecreation'            => '== Ὡς εὖ παρέστης, $1! ==

Λογισμός σὸς πεποίηται. Ἔχε μνήμην μεταβάλλειν τὰς τοῦ [[Special:Preferences|{{SITENAME}} αἱρέσεις σου]].',
'loginpagetitle'             => 'Συνδεῖσθαι χρήστου',
'yourname'                   => 'Ὄνομα χρωμένου:',
'yourpassword'               => 'Σῆμα:',
'yourpasswordagain'          => 'Ἀνατυπῶσαι σύνθημα:',
'remembermypassword'         => 'Μίμνῃσκε ἐνθάδε τὸ συνδεῖσθαι',
'yourdomainname'             => 'Ὁ τομεύς σου:',
'login'                      => 'Συνδεῖσθαι',
'nav-login-createaccount'    => 'Συνδεῖσθαι/λογισμὸν ποιεῖν',
'loginprompt'                => 'Δεῖ ἐνεργὰ τὰ HTTP-πύσματα εἶναι πρὸ τοῦ συνδεῖσθαι τῷ {{SITENAME}}.',
'userlogin'                  => 'Συνδεῖσθαι/λογισμὸν ποιεῖν',
'logout'                     => 'Ἐξέρχεσθαι',
'userlogout'                 => 'Ἐξέρχεσθαι',
'notloggedin'                => 'Οὐ συνδεδεμένος',
'nologin'                    => 'Ἆρα λογισμὸν οὐκ ἔχεις; $1.',
'nologinlink'                => 'Λογισμὸν ποιεῖν',
'createaccount'              => 'Λογισμὸν ποιεῖν',
'gotaccount'                 => 'Ἆρα λογισμὸν ἤδη τινὰ ἔχεις; $1.',
'gotaccountlink'             => 'Συνδεῖσθαι',
'createaccountmail'          => 'ἠλεκτρονικῇ ἐπιστολῇ',
'youremail'                  => 'Ἠλεκτρονικαὶ ἐπιστολαί:',
'username'                   => 'Ὄνομα χρωμένου:',
'uid'                        => 'Ταυτότης χρήστου:',
'prefs-memberingroups'       => 'Μέλος {{PLURAL:$1|ομάδoς|ομάδων}}:',
'yourrealname'               => 'Τὸ ἀληθὲς ὄνομα:',
'yourlanguage'               => 'Γλῶττά σου:',
'yournick'                   => 'Προσωνυμία:',
'badsig'                     => 'Ἄκυρος πρωτογενὴς ὑπογραφή. Ἔλεγξον τὰ HTML-σήμαντρα.',
'email'                      => 'ἠλεκτρονική ἐπιστολή',
'prefs-help-realname'        => 'Ἀληθὲς ὄνομα προαιρετικὸν ἐστίν.
Εἰ εἰσάγεις τὸ ὄνομά σου, ἀναγνωριστέον ἔσται τὸ ἔργον σου.',
'loginerror'                 => 'Ἡμάρτηκας περὶ τοῦ συνδεδεκαῖναι',
'prefs-help-email-required'  => 'Διεύθυνσις ἠλ-ταχυδρομείου προαπαιτεῖται.',
'nocookieslogin'             => 'Ὁ {{SITENAME}} χρῆται πύσματα ἐν τῇ συνδέσει τῶν χρωμένων.
Μὴ ἐνεργὰ τὰ πύσματα διέταξας.
Ἐνεργὰ ποιήσας αὐτὰ πείρασον πάλιν.',
'noname'                     => 'Οὐ καθὠρισας ἔγκυρόν τι ὄνομα χρωμένου.',
'loginsuccesstitle'          => 'Ἐπιτυχὼς συνεδέθης',
'loginsuccess'               => "'''συνδέδεσαι ἤδη τῷ {{SITENAME}} ὡς \"\$1\".'''",
'nosuchuser'                 => 'Οὐκ ἐστὶ χρώμενος ὀνόματι "$1".
Σκόπει τὴν τῶν γραμμάτων ἀκριβείαν ἢ λογισμὸν νέον ποίει.',
'nosuchusershort'            => 'Οὔκ ἐστι χρήστης ἔχων τὸ ὄνομα "<nowiki>$1</nowiki>".
Ἔλεγξον τὴν ὀρθογραφίαν σου.',
'nouserspecified'            => 'Ὄνομα χρωμένου καθοριστέον ὑποχρεωτικώς.',
'wrongpassword'              => 'Εἰσηγμένον σύνθημα ἐσφαλμένον. Πείρασον πάλιν.',
'wrongpasswordempty'         => 'Σύνθημα οὐκ ἔγραψας.
Αὖθις πείρασον.',
'passwordtooshort'           => 'Τὸ σύνθημά σου ἄκυρον ἢ λίαν βραχὺ ἐστίν.
Δεῖ αὐτὸ ἔχειν τοὐλάχιστον {{PLURAL:$1|1 χαρακτὴρ|$1 χαρακτῆρες}} καὶ εἶναι διάφορον τοῦ ὀνόματος χρήστου σου.',
'mailmypassword'             => 'Τὸ σύνθημα ἠλεκτρονικῇ ἐπιστολῇ πέμπειν',
'passwordremindertitle'      => 'Νέον ἐφήμερον σύνθημα διὰ {{SITENAME}}',
'passwordremindertext'       => "Τίς (πιθανὼς σύ, ἔχων τὴν IP-διευθύνσιν \$1) ἐζήτησεν τὴν πρὸς σέ ἀποστολὴν νέου συνθήματος διὰ τὸν ἱστότοπον {{SITENAME}} (\$4). Τὸ σύνθημα διὰ τὸν χρήστην \"\$2\" ἐν τῷ παρόντι \"\$3\" ἐστίν. Δεῖ σοι ''νῦν'' συνδεῖσθαι τε καὶ ἀλλάττειν τὸ σύνθημά σου.

Εἰ τὶς ''ἕτερος'' τήνδε τὴν αἴτησιν ὑπέβαλεν ἢ εἰ οὐκ ἀμνηστεῖς τοῦ συνθήματός σου καὶ ''οὐκ'' ἐπιθυμεῖς τὴν ἀλλαγὴν οὗ, δύνασαι ἀγνοῆσαι τόδε τὸ μήνυμα καὶ διατηρῆσαι τὸ παλαιὸν σύνθημά σου.",
'noemail'                    => 'Οὐδεμία ἠλεκτρονικὴ διεύθυνσις ἐγγεγραμμένη διὰ τὸν χρώμενον "$1".',
'passwordsent'               => 'Νέον τι σύνθημα πρὸς τὴν τοῦ χρωμένου "$1" ὀνομαστὶ ἠλεκτρονικὴ διεύθυνσιν προσπέπεπται.
Τοῦτο δεχόμενος αὖθις συνδεῖσθαι.',
'eauthentsent'               => 'Μήνυμα τι ἐπιβεβαιώσεως ἐστάλη τῇ δεδηλωμένῃ ἠλεκτρονικῇ διευθύνσει σου. Πρὸ τῆς περαιτέρω ἀποστολῆς μηνυμάτων τῷ συγκεκριμένῳ λογισμῷ, δεῖ σοι ἀκολουθῆσαι τὰς ὀδηγίας ἐν τῷ ἀπεσταλμένῳ μηνύματι πρὸς ἐπαλήθευσιν τῆς κυριότητός σου τοῦ συγκεκριμένου λογισμοῦ.',
'mailerror'                  => 'Σφάλμα κατὰ τὴν ἀποστολὴν τῆσδε ἐπιστολῆς: $1',
'acct_creation_throttle_hit' => 'Λυπούμεθα· πεποίηκας ἤδη $1 λογισμοῦς.
Οὐ δύνασαι ἔχειν πλείω τοῦ ἑνός.',
'emailconfirmlink'           => 'Ἐπιβεβαίωσον τὴν διεύθυνσιν ἠλ-ταχυδρομείου σου',
'accountcreated'             => 'Λογισμὸς ποιηθείς',
'createaccount-title'        => 'Ποίησις λογισμοῦ διὰ {{SITENAME}}',
'loginlanguagelabel'         => 'Γλῶσσα: $1',

# Password reset dialog
'resetpass'         => 'Ἀναδιορισμὸς συνθήματος λογισμοῦ',
'resetpass_header'  => 'Ἀναδιορισμὸς συνθήματος',
'resetpass_submit'  => 'Ἀναδιορισμὸς συνθήματος καὶ σύνδεσις',
'resetpass_missing' => 'Οὐδὲν δεδομένον τύπου.',

# Edit page toolbar
'bold_sample'     => 'Γράμματα παχέα',
'bold_tip'        => 'Γράμματα παχέα',
'italic_sample'   => 'Γράμματα πλάγια',
'italic_tip'      => 'Γράμματα πλάγια',
'link_sample'     => 'Συνδέσμου ὄνομα',
'link_tip'        => 'Σύνδεσμος οἰκεῖος',
'extlink_sample'  => 'http://www.example.com ὄνομα συνδέσμου',
'extlink_tip'     => 'Ἐξώτερος σύνδεσμος (μέμνησο τὸ πρόθεμα http:// )',
'headline_sample' => 'Κείμενον ἐπικεφαλίδος',
'headline_tip'    => 'Κλίμακος 2 ἐπικεφαλίς',
'math_sample'     => 'Εἰσάγειν τύπον ὧδε',
'math_tip'        => 'Μαθηματικὸς τύπος (LaTeX)',
'nowiki_sample'   => 'Εἰσάγειν ἀμόρφωτον κείμενον ὧδε',
'nowiki_tip'      => 'Ἀγνοεῖν οὐικιμορφοποιίαν',
'image_tip'       => 'Ἐμβεβαπτισμένον ἀρχεῖον',
'media_tip'       => 'Τὸ προσάγον πρὸς τὸ φορτίον',
'sig_tip'         => 'Ὑπογραφή σου μετὰ χρονοσφραγίδος',
'hr_tip'          => 'Ὁριζόντιος γραμμή (χρηστέα φειδωλώς)',

# Edit pages
'summary'                => 'Σύνοψις',
'subject'                => 'Χρῆμα/ἐπικεφαλίς',
'minoredit'              => 'Μικρὰ ἥδε ἡ μεταβολή',
'watchthis'              => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'savearticle'            => 'Γράφειν τὴν δέλτον',
'preview'                => 'Τὸ προεπισκοπεῖν',
'showpreview'            => 'Προεπισκοπεῖν',
'showlivepreview'        => 'Ἄμεσος προθεώρησις',
'showdiff'               => 'Δεικνύναι τὰς μεταβολάς',
'anoneditwarning'        => "'''Προσοχή:''' Οὐ συνδεδεμένος εἶ.
Ἡ IP-διεύθυνσίς σου καταγεγραμμένη ἔσται ἐν τῇδε δέλτου ἱστορίᾳ.",
'missingcommenttext'     => 'Εἰσάγαγε σχόλιον τι κατωτέρω.',
'summary-preview'        => 'Προθεώρησις συνόψεως',
'subject-preview'        => 'Ἀντικειμένου/ἐπικεφαλίδος προθεώρησις',
'blockedtitle'           => 'Ἀποκεκλεισμένος ὁ χρώμενος',
'blockedtext'            => "<big>'''Τὸ ὄνομα χρωμένου σου ἢ ἡ IP-διεύθυνσις σου πεφραγμένα εἰσίν.'''</big>

Ἡ φραγὴ γέγονε ὑπὸ τὸν/τὴν $1.
Ἡ δεδομένη αἰτιολογία ἐστίν: ''$2''.

* Ἔναρξις φραγῆς: $8
* Λῆξις φραγῆς: $6
* Ἡ φραγὴ προορίζεται διὰ τὸν χρώμενον: $7

Ἀποτάθητι εἰς τὸν/τὴν $1 ἢ ὅντινα ἕτερον [[{{MediaWiki:Grouppage-sysop}}|γέροντα]] διὰ τὸ διαλέγεσθαι περὶ τῆς φραγῆς. 
Οὐ δύνασαι χρῆσθαι τὴν δυνατότητα «αποστολῆς ἠλεκτρονικῆς ἐπιστολῆς τῷδε τῷ χρωμένῳ» εἰ οὐχ ὁρίσεις ἔγκυρόν τινα ἠλεκτρονικὴν διεύθυνσιν ἐν ταῖς [[Special:Preferences|προκρίσεσί]] σου. 
Ἡ τρέχουσα IP-διεύθυνσις σου $3 ἐστίν, καὶ ἡ ἀναγνώρισις τῆς φραγῆς #$5 ἐστίν. 
Παρακαλοῦμεν σε περιλαμβάνειν οἱανδήποτε ἐξ αὐτῶν ἢ καὶ ἀμφοτέρας ἐν ταῖς ἐρωτήσεσί σου.",
'autoblockedtext'        => "Ἡ IP-διεύθυνσις σου ἐφράγη αὐτομάτως ἐπεὶ κεχρησμένη ἦν ὑπὸ ἑτέρου τινὸς χρήστου, ὅπερ ἀποκεκλεισμένος ἐστὶν ἐκ τοῦ/τῆς $1.
Ἡ δεδομένη αἰτία ἐστὶν ὡς ἑξῆς:

:''$2''

*Ἔναρξις φραγῆς: $8
*Λῆξις φραγῆς: $6
*Προκαθωρισμένος πεφραχθείς: $7

Ἀποτάθητι εἰς τὸν/τὴν $1 ἢ εἰς ἑτέρους τινὲς 
[[{{MediaWiki:Grouppage-sysop}}|γέροντας]] διὰ τὸ διαλέγεσθαι περὶ τῆς φραγῆς.

Σημείωσον: οὐ δύνασαι χρῆσθαι τὸ γνώρισμα «αποστολῆς ἠλεκτρονικῆς ἐπιστολῆς τῷδε χρήστη» εἰ μὴ ἔχης ἔγκυρον τινὰ διεύθυνσιν ἠλεκτρονικοῦ ταχυδρομείου κατακεχωρημένην ἐν ταῖς [[Special:Preferences|προτιμήσεσι χρήστου]] σου.

Ἡ τρέχουσα IP-διεύθυνσις σου $3 ἐστίν, καὶ ἡ ἀναγνώρισις τῆς φραγῆς #$5 ἐστίν. 
Παρακαλοῦμεν σε περιλαμβάνειν οἱανδήποτε ἐξ αὐτῶν ἢ καὶ ἀμφοτέρας ἐν ταῖς ἐρωτήσεσί σου.",
'blockednoreason'        => 'οὐδεμία αἰτία ἐδόθη',
'blockedoriginalsource'  => "Ἡ πηγὴ τοῦ '''$1''' δείκνυται κατωτέρω:",
'whitelistedittitle'     => 'Ἀπαιτούμενον τὸ συνδεῖσθαι πρὸ τοῦ μεταγράψειν',
'whitelistedittext'      => 'Ἀπαιτούμενον τὸ $1 πρὸ τοῦ μεταγράψειν δέλτους.',
'confirmedittitle'       => 'Ἐπιβεβαίωσις ἠλ-διευθύνσεως ἀπαραίτητος πρὸ τοῦ μεταγράψειν',
'nosuchsectiontitle'     => 'Οὐδὲν τοιοῦτον τμῆμα',
'loginreqtitle'          => 'Δεῖ σοι συνδεῖσθαι',
'loginreqlink'           => 'συνδεῖσθαι',
'accmailtitle'           => 'Σύνθημα ἀπεστάλη.',
'accmailtext'            => 'Τὸ σύνθημα διὰ τὸν/τὴν "$1" ἐστάλη τῷ $2.',
'newarticle'             => '(νέα)',
'newarticletext'         => "Ἀκολούθησας σύνδεμόν τινα πρὸς δέλτον εἰσέτι μὴ ὑπαρκτήν.
Δύνασαι δημιουργῆσαι τὴν δέλτον, τυπῶν ἐν τῷ κυτίῳ κατωτέρω (ἰδὲ [[{{MediaWiki:Helppage}}|δέλτον βοηθείας]] διά πλείονας πληροφορίας).
Εἰ ὧδε εἶ κατὰ λάθος, πίεσον τὸ κομβίον τοῦ πλοηγοῦ σου ὀνόματι '''ὀπίσω (back)'''.",
'noarticletext'          => 'Οὐδὲν ἐν τῇδε τῇ δέλτῳ γεγραμμένον, ἔξεστί σοι [[Special:Search/{{PAGENAME}}|δέλτον τινὰ ὧδε ὀνόματι ζητήσειν]] ἢ [{{fullurl:{{FULLPAGENAME}}|action=edit}} τήνδε τὴν δέλτον μεταγράψειν].',
'userinvalidcssjstitle'  => "'''Προσοχή:''' Οὐκ ὑφίσταται ''skin'' \"\$1\". Μέμνησο: αἱ προσηρμοσμέναι δέλτοι .css καὶ .js χρῶνται ἐπώνυμον τι ἔχον πεζὰ γράμματα, π.χ. {{ns:user}}:Foo/monobook.css ἐν ἀντίθεσει πρὸς τὸν {{ns:user}}:Foo/Monobook.css.",
'updated'                => '(Ἐνημερωθέν)',
'note'                   => '<strong>Ἐπισήμανσις:</strong>',
'previewnote'            => '<strong>Ἥδε ἐστὶ προθεώρησις, οὐχὶ καταγραφὴ τῶν μεταβολῶν!</strong>',
'editing'                => 'Μεταγράφειν $1',
'editingsection'         => 'Μεταγράφειν $1 (τμῆμα)',
'editingcomment'         => 'Μεταγράφειν $1 (σχόλιον)',
'editconflict'           => 'Ἀντιμαχία μεταγραφῶν: $1',
'yourtext'               => 'Τὰ ὑπὸ ἐσοῦ γραφόμενα',
'storedversion'          => 'Τεταμιευμένη ἔκδοσις',
'yourdiff'               => 'Τὰ διαφέροντα',
'copyrightwarning'       => 'Ἅπασαι αἱ συμβολαὶ εἰς τὸν {{SITENAME}} θεωροῦνται ὡς σύμφωναι πρὸς τὴν $2 (βλ. $1 διὰ τὰς ἀκριβεῖας).
Εἰ οὐκ ἐπιθυμεῖτε τὰ ὑμέτερα κείμενα μεταγράψιμα καὶ διαδόσιμα εἰσὶν ὑπὸ ἄλλων χρωμένων κατὰ τὴν βούλησίν των, παρακαλοῦμεν ὑμᾶς ἵνα μὴ αὐτὰ ἀναρτῆτε ἐν τούτῳ χώρῳ. Ὅ,τι συνεισφέρετε ἐνθάδε (κείμενα, διαγράμματα, στοιχεῖα ἢ εἰκόνας) δεῖ εἶναι ὑμέτερον ἔργον, ἢ ἀνῆκον τῷ δημοσίῳ τομεῖ, ἢ προερχόμενον ἐξ ἐλευθέρων ἢ ἀνοικτῶν πηγῶν μετὰ ῥητῆς ἀδείας ἀναδημοσιεύσεως. <br />

Βεβαιοῦτε ἡμᾶς περὶ τῆς καινοπρεπείας ὅτου ἔργου γραφομένου ὑφ’ ὑμᾶς ἐνθάδε. Βεβαιοῦτε ἡμᾶς, ἐπἴσης, περὶ τῆς μὴ ἐκχωρήσεως εἰς ἀλλοτρίους πρὸς ὑμᾶς τοῦ δικαιώματος δημοσιεύσεως καὶ ὀνήσεως οὗ, ἥντινα ἔκτασιν αὐτὸ ἔχει.
<br />
<strong>ΠΑΡΑΚΑΛΟΥΜΕΝ ΥΜΑΣ ΙΝΑ ΜΗ ΑΝΑΡΤΗΤΕ ΚΕΙΜΕΝΑ ΑΛΛΟΤΡΙΩΝ ΕΙ ΜΗ ΕΧΗΤΕ ΤΗΝ ΑΔΕΙΑΝ ΤΟΥ ΙΔΙΟΚΤΗΤΟΥ ΤΩΝ ΠΝΕΥΜΑΤΙΚΩΝ ΔΙΚΑΙΩΜΑΤΩΝ!</strong>',
'longpagewarning'        => '<strong>ΠΡΟΣΟΧΗ: Ἡδε δέλτος μῆκος $1 χιλιοδυφίων (δυαδικῶν ψηφίων) ἔχει.
Ἐνδέχεται πλοηγοί τινες προβληματικὼς μεταγράφειν δέλτους προσεγγίζοντας τὰ ἢ μακροτέρας τῶν 32ΧΔ.
Θεωρήσατε τὸ διασπάσειν τὴν δέλτον εἰς μικρότερα τεμάχια.</strong>',
'templatesused'          => 'Πρότυπα κεχρησμένα ἐν τοιαύτῃ δελτῳ:',
'templatesusedpreview'   => 'Πρότυπα κεχρησμένα ἐν ταύτῃ προθεωρήσει:',
'template-protected'     => '(φυλλάττεται)',
'template-semiprotected' => '(ἡμιπεφυλαγμένη)',
'nocreatetitle'          => 'Δημιουργία δέλτων περιωρισμένη',
'nocreatetext'           => "{{SITENAME}} οὐκ σ'ἐᾷ νέας δέλτους ποιεῖν.
Ἐᾷ σε δέλτον ἢδη οὖσαν μεταβάλλειν ἢ [[Special:UserLogin|συνδεῖσθαι ἢ λογισμὸν ποιεῖν]].",
'permissionserrors'      => 'Σφάλματα ἀδειῶν',
'recreate-deleted-warn'  => "'''Προσοχή: Ἀναποιεῖς δέλτον πάλαι ποτὲ διαγραφεῖσα.'''

Δεῖ σοι θεωρήσειν εἰ ἁρμοστόν ἐστι τὸ συνεχίζειν μεταγράφειν τήνδε τὴν δέλτον.
Ὁ κατάλογος διαγραφῆς τῆσδε τῆς δέλτου διατίθεται ἐνθάδε πρὸς ἐπικουρίαν σου:",

# Account creation failure
'cantcreateaccounttitle' => 'Μὴ δυνατὴ ἡ ποίησις λογισμοῦ',

# History pages
'viewpagelogs'        => 'Ὁρᾶν καταλόγους διὰ ταύτην τὴν δέλτον',
'revnotfound'         => 'Ἀναθεώρησις μὴ εὑρεθεῖσα',
'currentrev'          => 'Τὸ νῦν',
'revisionasof'        => 'Τὰ ἐπὶ $1',
'revision-info'       => 'Τὸ ἐπὶ $1 ὑπὸ $2',
'previousrevision'    => '←Παλαιοτέρα ἀναθεώρησις',
'nextrevision'        => 'Νεωτέρα ἀναθεώρησις→',
'currentrevisionlink' => 'Τὰ νῦν',
'cur'                 => 'ἡ νῦν',
'next'                => 'ἡ ἐχομένη',
'last'                => 'ἡ ὑστάτη',
'page_first'          => 'πρώτη',
'page_last'           => 'ἐσχάτη',
'histlegend'          => 'Σύγκρισις διαφορῶν: Ἐπιλέξατε τὰς συγκριτέας ἐκδοχὰς καὶ πατήσατε enter ἢ τὸ κομβίον  "Σύγκρισις...". <br />
Ὑπόμνημα: (τρέχον) = διαφοραὶ ὡς πρὸς τὴν τρέχουσαν ἐκδοχήν,
(ὕστατον) = διαφοραὶ ὡς πρὸς τὴν προηγουμένη ἐκδοχήν, μ = ἀλλαγαὶ μικρῆς κλίμακος.',
'history-search'      => 'Ζήτησις ἐν ταῖς προτέραις',
'deletedrev'          => '[διεγράφη]',
'histfirst'           => 'πρώτη',
'histlast'            => 'ἐσχάτη',
'historyempty'        => '(κενόν)',

# Revision feed
'history-feed-title'          => 'Ἱστορία ἀναθεωρήσεων',
'history-feed-description'    => 'Ἱστορία ἀναθεωρήσεων τῆσδε δέλτου ἐν τῷ οὐίκι',
'history-feed-item-nocomment' => '$1 ἐπὶ $2', # user at time

# Revision deletion
'rev-deleted-comment'     => '(σχόλιον ἀφελόμενον)',
'rev-deleted-user'        => '(ὄνομα χρωμένου ἀφελόμενον)',
'rev-deleted-event'       => '(δρᾶσις καταλόγου ἀφελομένη)',
'rev-delundel'            => 'δεικνύναι/κρύπτειν',
'revisiondelete'          => 'Διαγράφειν/ἐκδιαγράφειν ἀναθεωρήσεις',
'revdelete-nooldid-title' => 'Ἄκυρος ἀναθεώρησις-στόχος',
'revdelete-legend'        => 'Θέτειν περιορισμοῦς ὁρατότητος',
'revdelete-hide-text'     => 'Κρύπτειν κείμενον ἀναθεωρήσεως',
'revdelete-hide-name'     => 'Κρύπτειν δρᾶσιν τε καὶ στόχον',
'revdelete-hide-comment'  => 'Κρύπτειν σχόλιον μεταγραφῆς',
'revdelete-hide-user'     => 'Κρύπτειν μεταγραφέως ὄνομα/IP-διεύθυνσιν',
'revdelete-hide-image'    => 'Κρύπτειν περιεχόμενον ἀρχείου',
'revdelete-log'           => 'Σχόλιον καταλόγου:',
'revdel-restore'          => 'Ἀλλάττειν ὁρατότητα',
'pagehist'                => 'Ἱστορία δέλτου',
'deletedhist'             => 'Ἱστορία διεγραμμένη',
'revdelete-content'       => 'περιεχόμενον',
'revdelete-summary'       => 'σύνοψις μεταγραφῶν',
'revdelete-uname'         => 'ὄνομα χρήστου',
'revdelete-restricted'    => 'ἐφῃρμοσμένοι περιορισμοί διὰ τοὺς γέροντας',
'revdelete-unrestricted'  => 'αἱρεθέντες περιορισμοὶ διὰ τοὺς γέροντας',
'revdelete-hid'           => 'κρύπ $1',
'revdelete-unhid'         => 'oὐ κρύπ $1',
'revdelete-log-message'   => '$1 διὰ $2 {{PLURAL:$2|ἀναθεώρησιν|ἀναθεωρήσεις}}',
'logdelete-log-message'   => '$1 διὰ $2 {{PLURAL:$2|γεγονός|γεγονότα}}',

# Suppression log
'suppressionlog' => 'Κατάλογος διαγραφῶν',

# History merging
'mergehistory'        => 'Συγχωνεύειν ἱστορίας δέλτων',
'mergehistory-box'    => 'Συγχωνεύειν τὰς ἀναθεωρήσεις τῶν δύο δέλτων:',
'mergehistory-from'   => 'Δέλτος πηγῶν:',
'mergehistory-into'   => 'Δέλτος προορισμοῦ:',
'mergehistory-list'   => 'Συγχωνεύσιμος ἱστορία μεταγραφῶν',
'mergehistory-go'     => 'Δεικνύναι συγχωνεύσιμους μεταγραφάς',
'mergehistory-submit' => 'Συγχωνεύειν ἀναθεωρήσεις',

# Merge log
'mergelog'    => 'Τῶν συγχωνεύσεων καταλόγος',
'revertmerge' => 'Ἀποσυγχωνεύειν',

# Diffs
'history-title'           => 'Αἱ προτέραι ἐκδόσεις τῆς δέλτου "$1"',
'difference'              => '(Τὰ μεταβεβλημένα)',
'lineno'                  => 'Γραμμή $1:',
'compareselectedversions' => 'Συγκρίνειν τὰς ἐπιλελεγμένας δέλτους',
'editundo'                => 'ἀναίρεσις',
'diff-multi'              => '({{PLURAL:$1|Μία ἐνδιάμεσος ἀναθεώρησις|$1 ἐνδιάμεσοι ἀναθεωρήσεις}} οὐ φαίνονται.)',

# Search results
'searchresults'             => 'Ἀποτελέσματα ἀναζητήσεως',
'searchresults-title'       => 'Ἀποτελέσματα ζητήσεως διὰ τὸ $1',
'noexactmatch'              => "'''Οὐκ ἐστὶ δέλτος ὀνόματι \"\$1\".'''
Ἔξεστί σοι [[:\$1|ταύτην ποιεῖν]].",
'prevn'                     => 'προτέραι $1',
'nextn'                     => 'ἐπομέναι $1',
'viewprevnext'              => 'Ἐπισκοπεῖν ($1) ($2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|1 λέξις|$2 λέξεις}})',
'search-result-score'       => 'Σχετικότης: $1%',
'search-redirect'           => '(ἀναδιευθύνειν $1)',
'search-section'            => '(τμῆμα $1)',
'search-suggest'            => 'Συνίης: $1',
'search-interwiki-caption'  => 'Ἀδελφὰ σχέδια',
'search-interwiki-default'  => '$1 ἀποτελέσματα:',
'search-interwiki-more'     => '(πλείω)',
'search-mwsuggest-enabled'  => "μεθ'ὑποδείξεων",
'search-mwsuggest-disabled' => 'οὐκ αἵτινες ὑποδείξεις',
'search-relatedarticle'     => 'Σχετικά',
'mwsuggest-disable'         => 'Μὴ ἐνεργαὶ αἱ ὑποδείξεις AJAX',
'searchrelated'             => 'σχετικά',
'searchall'                 => 'ἅπασαι',
'nonefound'                 => "'''Ἐπισημείωμα''': Μόνον οἵτινες ὀνοματικοὶ χῶροι ἀναζητοῦνται κατὰ προεπιλογήν.
Πείρασον τὸ προθεματίζειν τὴν πεῦσιν σου μετὰ τοῦ ''ἅπασαι:'' διὰ τὸ ἀναζητεῖν ἐν παντὶ τῷ περιεχομένῳ (δέλτων διαλόγου, προτύπων, κ.λ., περιλαμβανομένων), ἢ χρῆσον τὸν ἐπιθυμητὸν ὀνοματικὸν χῶρον ὡς πρόθεμα.",
'powersearch'               => 'Ζητεῖν ἀναλυτικώς',
'powersearch-legend'        => 'Ἀνωτέρα ἀναζήτησις',
'powersearch-ns'            => 'Ζήτησις ἐν τοῖς ὀνοματεἰοις:',
'powersearch-redir'         => 'Ἀναδιευθύνειν καταλόγου',
'powersearch-field'         => 'Ἀναζήτησις διά',
'search-external'           => 'Ἐξωτέρα ἀναζήτησις',

# Preferences page
'preferences'              => 'Αἱρέσεις',
'mypreferences'            => 'Αἱ αἱρέσεις μου',
'prefs-edits'              => 'Τοσοῦται αἱ μεταβολαί:',
'prefsnologin'             => 'Μὴ συνδεδεμένος',
'prefsreset'               => 'Αἱ αἱρέσεις σου ἀποκατέστηκαν κατὰ τὴν τεταμιευμένην ἔκδοσιν σφῶν.',
'qbsettings'               => 'Ταχεῖα πρόσβασις',
'qbsettings-none'          => 'Οὐδέν',
'qbsettings-fixedleft'     => 'Σταθερὰ ἀριστερώς',
'qbsettings-fixedright'    => 'Σταθερὰ δεξιώς',
'qbsettings-floatingleft'  => 'Πλανώμενα αριστερώς',
'qbsettings-floatingright' => 'Πλανώμενα δεξιώς',
'changepassword'           => 'Ἀλλάττειν σύνθημα',
'skin'                     => 'Ἐμφάνισις',
'skin-preview'             => 'Προεπισκοπεῖν',
'math'                     => 'Τὰ μαθηματικά',
'dateformat'               => 'Μορφοποιία χρονολογίας',
'datedefault'              => 'Οὐδεμία προτίμησις',
'datetime'                 => 'Χρονολογία καὶ ὥρα',
'math_failure'             => 'Λεξιανάλυσις ἀποτετυχηκυῖα',
'math_unknown_error'       => 'ἄγνωστον σφάλμα',
'math_unknown_function'    => 'ἄγνωστος ἐνέργεια',
'math_lexing_error'        => 'σφάλμα λεξικῆς ἀναλύσεως',
'math_syntax_error'        => 'σφάλμα συντάξεως',
'prefs-personal'           => 'Στοιχεῖα χρωμένου',
'prefs-rc'                 => 'Αἱ νέαι μεταβολαί',
'prefs-watchlist'          => 'Τὰ ἐφορώμενα',
'prefs-misc'               => 'Διάφορα',
'saveprefs'                => 'Γράφειν',
'resetprefs'               => 'Ἐκκαθαίρειν ἀσώτους ἀλλαγάς',
'oldpassword'              => 'Πρότερον σύνθημα:',
'newpassword'              => 'Νέον σύνθημα:',
'retypenew'                => 'Ἀνατύπωσις νέου συνθήματος:',
'textboxsize'              => 'Τὸ μεταγράφειν',
'rows'                     => 'Σειραί:',
'columns'                  => 'Στῆλαι:',
'searchresultshead'        => 'Ζητεῖν',
'resultsperpage'           => 'Ἀποτελέσματα ἀνά δέλτον:',
'contextlines'             => 'Σειραὶ ἀνά ἀποτέλεσμα:',
'contextchars'             => 'Συναφὲς κείμενον ἀνά σειράν:',
'timezonelegend'           => 'Χρονικὴ ζώνη',
'localtime'                => 'Τοπικὴ ὥρα',
'timezoneoffset'           => 'Ἐκτόπισμα¹',
'servertime'               => 'Ὥρα ἐξυπηρετικῆς ὑπολογιστικῆς μηχανῆς',
'guesstimezone'            => 'Συμπλήρωσις μέσῳ τοῦ πλοηγοῦ',
'prefs-searchoptions'      => 'Ἐπιλογαὶ ζητήσεως',
'prefs-namespaces'         => 'Ὄνοματικὸς χῶρος',
'defaultns'                => 'Ἀναζήτησις ἐν τοῖσδε ὀνοματικοῖς χώροις κατὰ προεπιλογήν:',
'default'                  => 'προκαθωρισμένον',
'files'                    => 'Ἀρχεῖα',

# User rights
'userrights'                  => 'Διαχείρισις δικαιωμάτων χρωμένου', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'      => 'Χειρίζεσθαι ὁμάδας χρωμένου',
'userrights-user-editname'    => 'Εἰσάγειν ὄνομἀ τι χρήστου:',
'editusergroup'               => 'Μεταγράφειν ὁμάδας χρωμένου',
'userrights-editusergroup'    => 'Μεταγράφειν ὁμάδας χρωμένου',
'saveusergroups'              => 'Σῴζειν ὁμάδας χρωμένου',
'userrights-groupsmember'     => 'Μέλος τοῦ:',
'userrights-reason'           => 'Αἰτία διὰ τὴν ἀλλαγήν:',
'userrights-nodatabase'       => 'Ἡ βάσις δεδομένων $1 οὐκ ὑπάρχει ἢ οὐκ ἔστι τοπική.',
'userrights-changeable-col'   => 'Μεταβλητέαι ὁμάδες',
'userrights-unchangeable-col' => 'Μὴ μεταβλητέαι ὁμάδες',

# Groups
'group'               => 'Ὁμάς:',
'group-user'          => 'Χρώμενοι',
'group-autoconfirmed' => 'Αὐτοεπιβεβαιωθέντες χρώμενοι',
'group-bot'           => 'Αὐτόματα',
'group-sysop'         => 'Γέρoντες',
'group-bureaucrat'    => 'Ἔφοροι',
'group-suppress'      => 'Παροράματα',
'group-all'           => '(ὅλοι)',

'group-user-member'          => 'Χρήστης',
'group-autoconfirmed-member' => 'Αὐτοεπιβεβαιωθεὶς χρώμενος',
'group-bot-member'           => 'Μεταβάλλων μηχανικός',
'group-sysop-member'         => 'Γέρων',
'group-bureaucrat-member'    => 'Ἔφορος',
'group-suppress-member'      => 'Παρόραμα',

'grouppage-user'          => '{{ns:project}}:Χρώμενοι',
'grouppage-autoconfirmed' => '{{ns:project}}:Αὐτοκυρούμενοι χρώμενοι',
'grouppage-bot'           => '{{ns:project}}:Αὐτόματα',
'grouppage-sysop'         => '{{ns:project}}:Γέροντες',
'grouppage-bureaucrat'    => '{{ns:project}}:Ἔφοροι',
'grouppage-suppress'      => '{{ns:project}}:Παρόραμα',

# Rights
'right-read'           => 'Ἀναγιγνώσκειν δέλτους',
'right-edit'           => 'Μεταγράφειν δέλτους',
'right-createpage'     => 'Ποιεῖν δέλτους (ἅσπερ οὐκ ἔσονται δέλτοι διαλέξεως)',
'right-createtalk'     => 'Ποεῖν δέλτους διαλέξεως',
'right-createaccount'  => 'Ποιεῖν νέους λογισμοῦς χρωμένων',
'right-minoredit'      => 'Σημαίνειν μεταγραφὰς ὡς μικράς',
'right-move'           => 'Μετακινεῖν δέλτους',
'right-upload'         => 'Ἐπιφορτίζειν ἀρχεῖα',
'right-reupload'       => 'Ὑπεργράφειν ἐπὶ ὑπάρχοντος τινὸς ἀρχείου',
'right-reupload-own'   => 'Ὑπεργράφειν ἐπὶ ὑπάρχοντος τινὸς ἀρχείου ἐπιφορτισμένου ἐξ ἰδίου τοῦ χρωμένου',
'right-autoconfirmed'  => 'Μεταγράφειν ἡμιφυλαττομένας δέλτους',
'right-apihighlimits'  => 'Χρῆσθαι ἀνώτατα ὅρια ἐν ταῖς API-πεύσεσιν',
'right-writeapi'       => 'Χρῆσις τοῦ γράφειν τὸ API',
'right-delete'         => 'Δέλτους σβεννύναι',
'right-bigdelete'      => 'Διαγράφειν δέλτους ἔχουσας εὐμεγέθη ἱστορικά',
'right-deleterevision' => 'Διαγράφειν καὶ ἐκδιαγράφειν συγκεκριμένας ἀναθεωρήσεις δέλτων',
'right-browsearchive'  => 'Ζητεῖν διεγραμμένας δέλτους',
'right-undelete'       => 'Δέλτον ἐπαναφέρειν',
'right-suppressionlog' => 'Ὁρᾶν ἰδιωτικοὺς καταλόγους',
'right-editinterface'  => 'Μεταγράφειν τὸ τοῦ χρωμένου περιβάλλον ἀλληλεπιδράσεως',
'right-editusercssjs'  => 'Μεταγράφειν ἑτέρων χρωμένων CSS- καὶ JS-ἀρχεῖα',
'right-patrol'         => 'Σημαίνειν τὰς μεταγραφὰς ἑτέρων ὡς φρουρουμένας',
'right-unwatchedpages' => 'Ὀρᾶν κατάλογόν τινα ἀνεφορωμένων δέλτων',
'right-trackback'      => 'Ὀνασύνδεσμον ὑποβάλλειν',
'right-mergehistory'   => 'Συγχωνεύειν τὸ ἱστορικὸν τῶν δέλτων',
'right-userrights'     => 'Μεταγράφειν ἅπαντα τοῦ χρωμένου δικαιώματα',
'right-siteadmin'      => 'Φράττειν καὶ ἐκφράττειν τὴν βάσιν δεδομένων',

# User rights log
'rightslog'  => 'Κατάλογος δικαιωμάτων χρωμένων',
'rightsnone' => '(Οὐδέν)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|μεταβολή|μεταβολαί}}',
'recentchanges'                     => 'Αἱ νέαι μεταβολαί',
'recentchanges-feed-description'    => 'Παρακολουθεῖν τὰς πλείω προσφάτους ἀλλαγὰς τοῦ οὐίκι ἐν ταύτῃ περιλήψει.',
'rcnote'                            => "Κατωτέρω {{PLURAL:$1|ἐστὶ '''1''' ἀλλαγὴ|εἰσὶν αἱ τελευταῖαι '''$1''' ἀλλαγαὶ}} ἐν {{PLURAL:$2|τῇ τελευταίᾳ ἡμέρᾳ|ταῖς τελευταῖαις '''$2''' ἡμέραις}}, ἕως καὶ $5, $4.",
'rcnotefrom'                        => "Ἰδοῦ αἱ ἀλλαγαὶ ἐκ τοῦ '''$2''' (ἕως τὸ '''$1''').",
'rclistfrom'                        => 'Δεικνύναι νέας ἀλλαγάς. Ἐκκίνησις ἐκ τοῦ $1',
'rcshowhideminor'                   => '$1 μικραὶ μεταβολαὶ',
'rcshowhidebots'                    => '$1 αὐτόματα',
'rcshowhideliu'                     => '$1 χρωμένους συνδεδεμένους',
'rcshowhideanons'                   => '$1 χρώμενοι ἀνώνυμοι',
'rcshowhidepatr'                    => '$1 Τὰς φυλασσόμενας μεταβολάς',
'rcshowhidemine'                    => '$1 μεταβολὰς μου',
'rclinks'                           => 'Ἐμφάνισις τῶν τελευταίων $1 ἀλλαγῷν τῷ χρονικῷ διαστήματι τῶν τελευταίων $2 ἡμερῷν <br />$3',
'diff'                              => 'διαφ.',
'hist'                              => 'Προτ.',
'hide'                              => 'Κρύπτειν',
'show'                              => 'Δεικνύναι',
'minoreditletter'                   => 'μ',
'newpageletter'                     => 'Ν',
'boteditletter'                     => 'αὐτ',
'number_of_watching_users_pageview' => '[$1 ἐφορᾶν {{PLURAL:$1|χρώμενον|χρωμένους}}]',
'rc_categories_any'                 => 'Οἵα δήποτε',
'newsectionsummary'                 => '/* $1 */ νέον τμῆμα',

# Recent changes linked
'recentchangeslinked'          => 'Οἰκεῖαι μεταβολαί',
'recentchangeslinked-title'    => 'Μεταβολαὶ οἰκεῖαι "$1"',
'recentchangeslinked-noresult' => 'Οὐδεμία ἀλλαγὴ τῶν συνδεδεμένων δέλτων ἐν τῇ δεδομένῃ χρονικῇ περιόδῳ.',
'recentchangeslinked-summary'  => "Ὅδε ἐστὶ κατάλογος τῶν νέων μεταβόλων κατὰ δέλτους συνδεδεμένας σὺν δέλτῳ τινί (ἢ κατὰ μέλη κατηγορίας τινός).
Δέλτοι ἐν τῷ [[Special:Watchlist|καταλόγῳ ἐφορωμένων]] σου '''ἔντονοι''' εἰσίν.",
'recentchangeslinked-page'     => 'Ὄνομα δέλτου:',

# Upload
'upload'                => 'Ἐπιφορτίζειν ἀρχεῖον',
'uploadbtn'             => 'Φορτίον ἐντιθέναι',
'reupload'              => 'Ἀναεπιφορτίζειν',
'uploadnologin'         => 'Μὴ συνδεδεμένος',
'uploaderror'           => 'Σφάλμα ἐπιφορτίσεως',
'upload-permitted'      => 'Ἐπιτρεπόμενοι τύποι ἀρχείων: $1.',
'upload-preferred'      => 'Προκρινόμενοι τύποι ἀρχείων: $1.',
'upload-prohibited'     => 'Ἀπηγορευμένοι τύποι ἀρχείων: $1.',
'uploadlog'             => 'ἐπιφορτίζειν κατάλογον',
'uploadlogpage'         => 'Ἐπιφόρτωσις καταλόγου',
'filename'              => 'Ὄνομα ἀρχείου',
'filedesc'              => 'Σύνοψις',
'fileuploadsummary'     => 'Σύνοψις:',
'filestatus'            => 'Κατάστασις πνευματικῶν δικαιωμάτων:',
'filesource'            => 'Πηγή:',
'uploadedfiles'         => 'Ἀρχεῖα ἐπιπεφορτισμένα',
'ignorewarnings'        => 'Ἀγνοοῦν ἐνίτινας εἰδήσεις',
'filetype-missing'      => 'Τόδε τὸ ἀρχεῖον οὐκ ἔχει ἐπέκτασιν (ὅπως ".jpg").',
'file-exists-duplicate' => 'Τὸ ἀρχεῖον ἐστὶ διπλότυπον τοῦ/τῶν ἑξῆς {{PLURAL:$1|ἀρχείου|ἀρχείων}}:',
'successfulupload'      => 'Ἐπιφόρτισις ἐπιτυχής',
'uploadwarning'         => 'Προμήνυσις ἐπιφορτώσεως',
'savefile'              => 'Σῴζειν ἀρχεῖον',
'uploadedimage'         => 'ἐπιφορτισμένον "[[$1]]"',
'uploaddisabled'        => 'Μὴ ἐνεργαὶ αἱ ἐπιφορτίσεις',
'uploadvirus'           => 'Τόδε τὸ ἀρχεῖον περιέχει ἰόν τινα! Ἀκρίβειαι: $1',
'sourcefilename'        => 'Ὄνομα πηγαίου ἀρχείου:',
'destfilename'          => 'Ὄνομα τελικοῦ ἀρχείου:',
'upload-maxfilesize'    => 'Μέγιστον μέγεθος ἀρχείου: $1',
'watchthisupload'       => 'Ἐφορᾶν τήνδε τὴν δέλτον',

'upload-proto-error' => 'Ἐσφαλμένον πρωθυπόμνημα',
'upload-file-error'  => 'Ἐσώτερον σφάλμα',
'upload-misc-error'  => 'Ἄγνωτον σφάλμα ἐπιφορτώσεως',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'  => 'URL ἀπρόσβατος',
'upload-curl-error28' => 'Λῆξις χρόνου ἀναμονῆς τῆς ἐπιφορτώσεως',

'license'            => 'Ἀδειηδότησις:',
'nolicense'          => 'Οὐδὲν ἐπιλελεγμένον',
'license-nopreview'  => '(Προθεὠρησις μὴ διαθέσιμος)',
'upload_source_url'  => ' (ἔγκυρος τίς, δημοσίως προσπελάσιμος URL)',
'upload_source_file' => ' (ἀρχεῖον τι ἐν τῇ ὑπολογιστικῇ μηχανῇ σου)',

# Special:ImageList
'imagelist-summary'     => 'Ἡδε εἰδικὴ δέλτος δεικνύει ἅπαντα τὰ ἐπιπεφορτισμένα ἀρχεῖα.
Κατὰ προεπιλογὴν τὰ ὑστάτως ἐπιπεφορτισμένα ἀρχεῖα δεινύονται ἐν τῇ κορυφῇ τοῦ καταλόγου.
Πίεσον ἐπικεφαλίδα τινὰ στήλης ἵνα ἡ καταλογὴ ἀλλάξηται.',
'imagelist_search_for'  => 'Ἀναζήτησις τοῦ τῶν μέσων ὀνόματος:',
'imgfile'               => 'ἀρχεῖον',
'imagelist'             => 'Κατάλογος πάντων τῶν φορτίων',
'imagelist_date'        => 'Χρονολογία',
'imagelist_name'        => 'Ὄνομα',
'imagelist_user'        => 'Χρώμενος',
'imagelist_size'        => 'Ὁπόσος',
'imagelist_description' => 'Διέξοδος',

# Image description page
'filehist'                       => 'Τοῦ ἀρχείου συγγραφή',
'filehist-help'                  => 'Πατήσατε ἐπὶ χρονολογίας/ὥρας τινὸς ἵνα ἴδητε τὸ ἀρχεῖον ὡς ἐμφανισθὲν ἐν ᾗπερ ὥρᾳ ᾗ.',
'filehist-deleteall'             => 'διαγράφειν ἅπαντα',
'filehist-deleteone'             => 'διαγράφειν',
'filehist-revert'                => 'ἀναστρέφειν',
'filehist-current'               => 'Τὸ νῦν',
'filehist-datetime'              => 'Ἡμέρα/Ὥρα',
'filehist-user'                  => 'Χρώμενος',
'filehist-dimensions'            => 'Διαστάσεις',
'filehist-filesize'              => 'Μέγεθος ἀρχείου',
'filehist-comment'               => 'Σχόλιον',
'imagelinks'                     => 'Σύνδεσμοι',
'linkstoimage'                   => '{{PLURAL:$1|Ἡ ἀκόλουθος|Αἱ ἀκόλουθοι $1}} {{PLURAL:$1|δέλτος σύνδεσμος|δέλτοι σύνδεσμοι $1}} πρὸς τήνδε τὴν εἰκόνα {{PLURAL:$1|ἐστίν|εἰσίν $1}}.',
'nolinkstoimage'                 => 'Οὐδένα ἐστὶ προσάγον τόδε τὸ φορτίον.',
'morelinkstoimage'               => 'Ὁρᾶν [[Special:WhatLinksHere/$1|πλείονας συνδέσμους]] πρὸς τήνδε τὴν δέλτον.',
'sharedupload'                   => 'Τόδε τὸ ἀρχεῖον ἐπεφορτίσθη πρὸς κοινὴν χρῆσιν καὶ δύνασαι χρῆσθαι αὐτὸ εἰς ἕτερα σχέδια καὶ δέλτους ἐξἴσου.',
'shareduploadduplicate'          => 'Τόδε τὸ ἀρχεῖον διπλότυπον ἐστὶ τοῦ $1 ἐκ κοινῆς ἀποθήκης.',
'shareduploadduplicate-linktext' => 'ἕτερον ἀρχεῖον',
'shareduploadconflict-linktext'  => 'ἕτερον ἀρχεῖον',
'noimage'                        => 'Οὐδένα ἐστὶ οὕτως ὀνομαστί, ἔξεστί σοι $1.',
'noimage-linktext'               => 'Ἐντιθέναι',
'uploadnewversion-linktext'      => 'Ἐπιφορτίζειν νέαν ἐκδοσίν τινα τοῦδε τοῦ ἀρχείου',
'imagepage-searchdupe'           => 'Ζητεῖν διπλότυπα ἀρχεῖα',

# File reversion
'filerevert'         => 'Ἐπαναφέρειν  $1',
'filerevert-legend'  => 'Ἐπαναφέρειν ἀρχεῖον',
'filerevert-comment' => 'Σχόλιον:',
'filerevert-submit'  => 'Ἀναστρέφειν',

# File deletion
'filedelete'                  => 'Διαγράφειν $1',
'filedelete-legend'           => 'Διαγράφειν ἀρχεῖον',
'filedelete-comment'          => 'Αἰτία διαγραφῆς:',
'filedelete-submit'           => 'Διαγράφειν',
'filedelete-success'          => "'''$1''' διαγραφὲν ἐστίν.",
'filedelete-otherreason'      => 'Ἄλλη/πρόσθετος αἰτία:',
'filedelete-reason-otherlist' => 'Ἑτέρα αἰτία',
'filedelete-reason-dropdown'  => '*Κοιναὶ αἰτίαι διαγραφῆς
** Παραβίασις δικαιωμάτων
** Διπλότυπον ἀρχεῖον',
'filedelete-edit-reasonlist'  => 'Μεταγράφειν αἰτίας διαγραφῆς',

# MIME search
'mimesearch' => 'MIME Ζητεῖν',
'mimetype'   => 'τύπος MIME:',
'download'   => 'καταφορτίζειν',

# Unwatched pages
'unwatchedpages' => 'Μὴ ἐφορωμέναι δέλτοι',

# List redirects
'listredirects' => 'Κατάλογος ἀναδιευθύνσεων',

# Unused templates
'unusedtemplates'    => 'Ἄχρηστα πρότυπα',
'unusedtemplateswlh' => 'οἱ σύνδεσμοι οἱ ἄλλοι',

# Random page
'randompage' => 'Δέλτος τυχοῦσα',

# Random redirect
'randomredirect' => 'Τυχαία ἀναδιεύθυνσις',

# Statistics
'statistics'             => 'Τὰ περὶ τῶν δεδομένων',
'sitestats'              => 'Στατιστικὰ τοῦ {{SITENAME}}',
'userstats'              => 'Χρωμένου στατιστικά',
'statistics-mostpopular' => 'Αἱ πλέον θεωρουμέναι δέλτοι',

'disambiguations'     => 'Αἱ τινὰ ἱστάναι εἰς τὸ ἀναμφισβήτητον δέλτοι',
'disambiguationspage' => 'Template:ἐκσαφήνισις',

'doubleredirects'       => 'Ἀναδιευθύνσεις διπλότυπαι',
'double-redirect-fixer' => 'Διορθωτὴς ἀναδιευθύνσεων',

'brokenredirects'        => 'Ἀναδιευθύνσεις οὐκέτι προὔργου οὖσαι',
'brokenredirects-edit'   => '(μεταγράφειν)',
'brokenredirects-delete' => '(διαγράφειν)',

'withoutinterwiki'        => 'Δέλτοι ἄνευ γλωσσικῶν συνδέσμων',
'withoutinterwiki-legend' => 'Πρόθεμα',
'withoutinterwiki-submit' => 'Δεικνύναι',

'fewestrevisions' => 'Δέλτοι ἔχουσαι τὰς ὀλιγωτέρας ἀναθεωρήσεις',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|δυφιολέξις|δυφιολέξεις}}',
'ncategories'             => '$1 {{PLURAL:$1|κατηγορία|κατηγορίαι}}',
'nlinks'                  => '$1 {{PLURAL:$1|σύνδεσμος|σύνδεσμοι}}',
'nmembers'                => '$1 {{PLURAL:$1|μέλος|μέλη}}',
'nrevisions'              => '$1 {{PLURAL:$1|ἀναθεώρησις|ἀναθεωρήσεις}}',
'nviews'                  => '$1 {{PLURAL:$1|βλέψις|βλέψεις}}',
'lonelypages'             => 'Δέλτοι ὀρφαναί',
'uncategorizedpages'      => 'Αἱ δέλτοι αἱ οὐκ ἐνοῦσαι κατηγορέσι τιναῖς',
'uncategorizedcategories' => 'Αἱ κατηγορίαι αἱ μὴ προσήκουσαι κατηγορέσι',
'uncategorizedimages'     => 'Ἀκατηγοριοποίητα ἀρχεῖα',
'uncategorizedtemplates'  => 'Ἀκατηγοριοποίητα πρότυπα',
'unusedcategories'        => 'Ἄχρησται κατηγορίαι',
'unusedimages'            => 'Ἄχρηστα ἀρχεῖα',
'popularpages'            => 'Δημοφιλεῖς δέλτοι',
'wantedcategories'        => 'Αἰτούμεναι κατηγορίαι',
'wantedpages'             => 'Αἱ δέλτοι οἷας ἱμείρομεν',
'missingfiles'            => 'Ἀπωλολότα ἀρχεῖα',
'mostlinked'              => 'Αἱ πλέον προσσυνδεδεμέναι δέλτοι',
'mostlinkedcategories'    => 'Αἱ πλέον προσσυνδεδεμέναι κατηγορίαι',
'mostlinkedtemplates'     => 'Τὰ πλέον προσσυνδεδεμένα πρότυπα',
'mostcategories'          => 'Δέλτοι ἔχουσαι τὰς πλείονας κατηγορίας',
'mostimages'              => 'Τὰ πλέον προσσυνδεδεμένα ἀρχεῖα',
'mostrevisions'           => 'Αἱ δέλτοι αἱ πλειστάκις μεταβεβλήμεναι',
'prefixindex'             => 'Προθέματος δείκτης',
'shortpages'              => 'Δέλτοι μικραί',
'longpages'               => 'Δέλτοι μακραί',
'deadendpages'            => 'Ἀδιέξοδαι δέλτοι',
'protectedpages'          => 'Αἱ δέλτοι αἱ φυλαττομέναι',
'protectedpages-indef'    => 'Ἀόρισται φυλάξεις μόνον',
'protectedpages-cascade'  => 'Διαδοχικαὶ φυλάξεις μόνον',
'protectedtitles'         => 'Πεφυλαγμέναι ἐπιγραφαί',
'listusers'               => 'Κατάλογος πάντων τῶν χρωμένων',
'newpages'                => 'Δέλτοι νέαι',
'newpages-username'       => 'Ὄνομα χρωμένου:',
'ancientpages'            => 'Αἱ παλαιόταται δέλτοι',
'move'                    => 'κινεῖν',
'movethispage'            => 'Κινεῖν τήνδε τὴν δέλτον',
'notargettitle'           => 'Οὐδεὶς στόχος',
'nopagetitle'             => 'Οὐδεμία τοιούτη δέλτος-στόχος',
'pager-newer-n'           => '{{PLURAL:$1|νεωτέρα 1|νεωτέραι $1}}',
'pager-older-n'           => '{{PLURAL:$1|παλαιοτέρα 1|παλαιοτέραι $1}}',
'suppress'                => 'Παρόραμα',

# Book sources
'booksources'               => 'Αἱ ἐν βίβλοις πηγαί',
'booksources-search-legend' => 'Ζητεῖν πηγὰς βίβλων',
'booksources-go'            => 'Ἰέναι',

# Special:Log
'specialloguserlabel'  => 'Χρώμενος:',
'speciallogtitlelabel' => 'Ὄνομα:',
'log'                  => 'Κατάλογοι',
'all-logs-page'        => 'Κατάλογοι ἅπαντες',
'log-search-legend'    => 'Ἀναζητεῖν καταλόγους',
'log-search-submit'    => 'Ἰέναι',

# Special:AllPages
'allpages'          => 'Πᾶσαι αἱ δέλτοι',
'alphaindexline'    => '$1 ἕως $2',
'nextpage'          => 'Ἡ δέλτος ἡ ἑπομένη ($1)',
'prevpage'          => 'Ἡ δέλτος ἡ προτέρα ($1)',
'allpagesfrom'      => 'Ἐπιδεικνύναι τὰς δέλτους ἐκ:',
'allarticles'       => 'Ἅπασαι αἱ ἐγγραφαί',
'allinnamespace'    => 'Ἅπασαι αἱ δέλτοι (ἐν τῷ ὀνοματείῳ $1)',
'allnotinnamespace' => 'Ἅπασαι αἱ δέλτοι (οὐκ ἐν τῷ ὀνοματείῳ $1)',
'allpagesprev'      => 'Προηγουμέναι',
'allpagesnext'      => 'Ἑπομέναι',
'allpagessubmit'    => 'Ἰέναι',
'allpagesprefix'    => 'Ἐπιδεικνύναι δέλτους ἔχουσας πρόθεμα:',
'allpages-bad-ns'   => 'Τὸ {{SITENAME}} οὐκ ἔχει ὀνοματεῖον "$1".',

# Special:Categories
'categories'                    => 'Κατηγορίαι',
'categoriesfrom'                => 'Δεικνύναι κατηγορίας (γραμμαὶ ἐκκινουμέναι ἐκ/ἐξ):',
'special-categories-sort-count' => 'ἀπαριθμητικὴ ταξινόμησις',
'special-categories-sort-abc'   => 'ἀλφαβητικὴ ταξινόμησις',

# Special:ListUsers
'listusersfrom'      => 'Δεικνύναι χρωμένους (γραμμαὶ ἐκκινουμέναι ἐκ/ἐξ):',
'listusers-submit'   => 'Ἐμφανίζειν',
'listusers-noresult' => 'Οὐδεὶς χρώμενος εὑρεθείς.',

# Special:ListGroupRights
'listgrouprights'                 => 'Δικαιώματα ὁμάδος χρωμένου',
'listgrouprights-group'           => 'Ὁμάς',
'listgrouprights-rights'          => 'Δικαιώματα',
'listgrouprights-helppage'        => 'Help:Δικαιώματα ὁμάδων',
'listgrouprights-members'         => '(διαλογὴ μελῶν)',
'listgrouprights-addgroup'        => 'Δυναμένη ἡ πρόσθεσις {{PLURAL:$2|τῆς ὁμάδος|τῶν ὁμάδων}}: $1',
'listgrouprights-removegroup'     => 'Δυναμένη ἡ ἀφαίρεσις {{PLURAL:$2|τῆς ὁμάδος|τῶν ὁμάδων}}: $1',
'listgrouprights-addgroup-all'    => 'Δυναμένη ἡ πρόσθεσις ἁπασῶν τῶν ὁμάδων',
'listgrouprights-removegroup-all' => 'Δυναμένη ἡ ἀφαίρεσις ἁπασῶν τῶν ὁμάδων',

# E-mail user
'mailnologin'     => 'Οὐδεμία διεύθυνσις παραλήπτου',
'emailuser'       => 'Ἠλεκτρονικὴν ἐπιστολὴν τῷδε τῷ χρήστῃ πέμπειν',
'emailpage'       => 'Χρώμενος ἠλ.-ταχυδρομείου',
'usermailererror' => 'Τὸ ἠλ-ταχυδρομεῖον ἐπέστρεψεν σφάλμα:',
'defemailsubject' => '{{SITENAME}} ἠλ.-ταχυδρομεῖον',
'noemailtitle'    => 'Οὐδεμία ἠλ-διεύθυνσις',
'emailfrom'       => 'Ἐκ',
'emailto'         => 'Πρός',
'emailsubject'    => 'Θέμα',
'emailmessage'    => 'Ἀγγελία',
'emailsend'       => 'Πέμπειν',
'emailsent'       => 'Ἠλ.-ἐπιστολὴ ἀπεστάλη',

# Watchlist
'watchlist'            => 'Τὰ ἐφορώμενά μου',
'mywatchlist'          => 'Τὰ ἐφορώμενά μου',
'watchlistfor'         => "(πρό '''$1''')",
'watchnologin'         => 'Μὴ συνδεδεμένος',
'addedwatch'           => 'Δέλτος προστεθειμένη εἰς τὸν ἐποπτευομένων κατάλογον ἐστίν',
'addedwatchtext'       => "Ἡ δέλτος \"[[:\$1]]\" προσετέθη ἐν τῷ [[Special:Watchlist|καταλόγῳ ἐφορωμένων]].
Μελλοντικαὶ ἀλλαγαὶ τῆσδε δέλτου καὶ τῆς σχετικῆς δέλτου διαλόγου καταλεχθήσονται ὧδε, καὶ ἡ δέλτος ἐμφανίσεται '''ἔντονος''' ἐν τῷ [[Special:RecentChanges|καταλόγῳ προσφάτων ἀλλαγων]] οὕτωσι εὐχερέστερός ἐστιν ἡ διάκρισις αὐτῆς.",
'removedwatch'         => 'Ἀνεώραται ἥδε ἡ δέλτος',
'removedwatchtext'     => 'Ἡ δέλτος "[[:$1]]" ἀφῃρέθη ἐκ τοῦ [[Special:Watchlist|καταλόγου ἐφορωμένων σου]].',
'watch'                => 'Ἐφορᾶν',
'watchthispage'        => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'unwatch'              => 'Ἀνεφορᾶν',
'unwatchthispage'      => 'Παῦσαι τὸ ἐφορᾶν',
'notanarticle'         => 'Μὴ δέλτος χρήματος',
'notvisiblerev'        => 'Ἀναθεώρησις διεγραμμένη',
'watchlist-details'    => '{{PLURAL:$1|$1 δέλτος|$1 δέλτοι}} ἐφορωμέναι, ἄνευ τῶν δέλτων διαλέξεως περιλαμβανομένων.',
'wlheader-enotif'      => '* Σύστημα εἰδήσεως μέσῳ ἠλ-ἐπιστολῶν ἐνεργόν.',
'wlshowlast'           => 'Ἐμφάνισις τῶν τελευταίων $1 ὡρῶν $2 ἡμερῶν $3',
'watchlist-show-bots'  => 'Δεικνύναι τὰς μεταγραφὰς τῶν αὐτομάτων',
'watchlist-hide-bots'  => 'Κρύπτειν τὰς ὑπ᾿ αὐτομάτων μεταβολάς',
'watchlist-show-own'   => 'Δεικνύναι τοὺς ἐράνους μου',
'watchlist-hide-own'   => 'Κρύπτειν τὰς ὑπ᾿ ἐμοῦ μεταβολάς',
'watchlist-show-minor' => 'Δεικνύναι τὰς μικρὰς μεταβολάς',
'watchlist-hide-minor' => 'Κρύπτειν τὰς μικρὰς μεταβολάς',
'watchlist-show-anons' => 'Δεικνύναι ἀνωνύμους μεταγραφάς',
'watchlist-hide-anons' => 'Κρύπτειν ἀνωνύμους μεταγραφάς',
'watchlist-show-liu'   => 'Δεικνύναι μεταγραφάς συνδεδεμένων χρωμένων',
'watchlist-hide-liu'   => 'Κρύπτειν μεταγραφάς συνδεδεμένων χρωμένων',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ἐφορῶν...',
'unwatching' => 'Ἀνεφορῶν...',

'enotif_mailer'                => 'Σύστημα εἰδήσεως τοῦ {{SITENAME}} μέσῳ ἐπιστολῶν',
'enotif_reset'                 => 'Σημαίνειν ἁπάσας τὰς ἐπεσκοπημένας δέλτους',
'enotif_newpagetext'           => 'Ἥδε νέα δέλτος ἐστίν.',
'enotif_impersonal_salutation' => 'Χρώμενος τῷ {{SITENAME}}',
'changed'                      => 'ἀλλαχθέν',
'created'                      => 'δημιουργηθέν',
'enotif_lastvisited'           => 'Ἴδε $1 διὰ ἁπάσας τὰς ἀλλαγὰς ἐκ τῆς ὑστάτης ἐπισκέψεώς σου.',
'enotif_lastdiff'              => 'Ἴδε $1 διὰ τὸ ὀρᾶν τήνδε τὴν ἀλλαγήν.',
'enotif_anon_editor'           => 'ἀνώνυμος χρώμενος $1',

# Delete/protect/revert
'deletepage'                  => 'Διαγράφειν τὴν δέλτον',
'confirm'                     => 'Κυροῦν',
'excontent'                   => "ἡ ὕλη ἦτο: '$1'",
'excontentauthor'             => "ἡ ὕλη ἦτο: '$1' (καὶ ὁ μόνος ἐρανίζων ἦτο ὁ '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'               => "τὸ περιεχόμενον πρὸ τῆς ἐκκαθαρίσεως ἦτο: '$1'",
'exblank'                     => 'δέλτος κενὴ ἦν',
'delete-confirm'              => 'Διαγράφειν "$1"',
'delete-legend'               => 'Διαγράφειν',
'historywarning'              => 'Προσοχή: Ἡ δέλτος ἥντινα βούλεσαι διαγράψειν ἔχει ἱστορίαν:',
'confirmdeletetext'           => 'Πρόκεισαι διαγράψειν ὁριστικὼς ἐκ τῆς βάσεως δεδομένων δέλτον τίνα (ἢ εἰκόνα τινα) μετὰ τῆς ἐῆς ἱστορίας. Παρακαλοῦμεν ὑμᾶς ἵνα ἐπιβεβαιώσητε τὴν θέλησιν ὑμῶν περὶ τοῦ αὐτὸ πράττειν καὶ περὶ τῆς ἀντιλήψεως τῶν συνεπειῶν, και περὶ τοῦ πράττειν ὑμῶν συμφώνως τῶν [[{{MediaWiki:Policy-url}}|κανόνων]].',
'actioncomplete'              => 'Τέλειον τὸ ποιούμενον',
'deletedtext'                 => '"<nowiki>$1</nowiki>" διεγράφηκεν.
Ἰδὲ $2 διὰ τὸ μητρῷον τῶν προσφάτων διαγραφῶν.',
'deletedarticle'              => 'Ἐσβέσθη ἡ δέλτος "[[$1]]"',
'suppressedarticle'           => '"[[$1]]" κατεσταλμένον',
'dellogpage'                  => 'Τὰ ἐσβεσμένα',
'deletionlog'                 => 'κατάλογος διαγραφῶν',
'deletecomment'               => 'Αἰτία τοῦ σβεννύναι:',
'deleteotherreason'           => 'Αἰτία ἄλλη/πρὀσθετος:',
'deletereasonotherlist'       => 'Αἰτία ἄλλη',
'delete-edit-reasonlist'      => 'Μεταγράφειν τὰς αἰτίας διαγραφῆς',
'rollback'                    => 'Ἀναστρέφειν μεταγραφάς',
'rollback_short'              => 'Ἀναστροφή',
'rollbacklink'                => 'ἀναστροφή',
'rollbackfailed'              => 'Ἀναστροφὴ μὴ ἐπιτυχής',
'protectlogpage'              => 'Κατάλογος προφυλάξεων',
'protectedarticle'            => '"[[$1]]" πεφυλαγμένον',
'unprotectedarticle'          => 'ἀπροφύλακτη "[[$1]]"',
'protect-legend'              => 'Ἐπιβεβαιοῦν φύλαξιν',
'protectcomment'              => 'Σχόλιον:',
'protectexpiry'               => 'Ἐξήξει:',
'protect_expiry_invalid'      => 'Ἄκυρος χρόνος λήξεως.',
'protect_expiry_old'          => 'Χρόνος λήξεως ἐν τῷ παρελθόντι ἐστίν.',
'protect-unchain'             => 'Δικαιώματα μετακινήσεως ἐκκλειδοῦν',
'protect-text'                => 'Ὁρᾶν τε καὶ ἀλλάττειν δύνασθε τὴν κλίμακα φυλάξεως ἐνθάδε διὰ τὴν δέλτον <strong><nowiki>$1</nowiki></strong>.',
'protect-locked-access'       => 'Ὁ λογισμός σου οὐκ ἔχει ἄδειαν ἀλλαγῆς τῆς κλίμακος φυλάξεως δέλτων.
Ἐνθάδε εἰσὶν αἱ τρέχουσαι ῥυθμίσεις διὰ τὴν δέλτον <strong>$1</strong>:',
'protect-cascadeon'           => 'Ἡδε δέλτος τῷ παρόντι πεφυλαγμένη ἐστὶν ἐπεὶ περιλαμβάνεται {{PLURAL:$1|τῇ ἀκολούθῳ δέλτῳ, ἥπερ ἔχει|ταῖς ἀκολούθοις δέλτοις, αἵπερ ἔχουσι}} τὴν διαδοχικὴν φύλαξιν ἐνεργόν. Δύνασαι ἀλλάττειν τὴν κλίμακα φύλαξις τῆσδε δέλτου, ἄνευ ἐπηρεασμοῦ τῆς διαδοχικῆς φυλάξεως.',
'protect-default'             => '(κριτήριον)',
'protect-fallback'            => 'Δεῖ σοι ἐχεῖν τὴν "$1" ἄδειαν',
'protect-level-autoconfirmed' => 'Ἀποκλῄειν τοὺς ἀγράφους',
'protect-level-sysop'         => 'Μόνοι οἱ γέροντες',
'protect-summary-cascade'     => 'Διαδεχόμενον',
'protect-expiring'            => 'Ἐξήξει $1 (UTC)',
'protect-cascade'             => 'Προφυλάττειν δέλτους περικεκλεισμένας ἐν τοιαύτῃ δελτῳ (διαδοχικὴ προφύλαξις)',
'protect-cantedit'            => 'Οὐ δύνασθε ἀλλάττειν τὴν κλίμακα φυλάξεως ταύτης τῆς δέλτου, διότι οὐκ ἔχετε τὴν ἀδείαν τοῦ μεταγράφειν αὐτήν.',
'restriction-type'            => 'Ἐξουσία:',
'restriction-level'           => 'Κλῖμαξ περιορισμοῦ:',
'minimum-size'                => 'Ἐλάχιστον μέγεθος',
'maximum-size'                => 'Μέγιστον μέγεθος:',
'pagesize'                    => '(δυφία)',

# Restrictions (nouns)
'restriction-edit'   => 'Μεταγράφειν',
'restriction-move'   => 'Kινεῖν',
'restriction-create' => 'Δημιουργεῖν',
'restriction-upload' => 'Ἐπιφορτίζειν',

# Restriction levels
'restriction-level-sysop'         => 'πλήρως διαφυλαττομένη',
'restriction-level-autoconfirmed' => 'ἡμιδιαφυλαττομένη',
'restriction-level-all'           => 'οἵα δήποτε κλῖμαξ περιορισμοῦ',

# Undelete
'undelete'               => 'Ὁρᾶν τὰς διεγραμμένας δέλτους',
'undeletepage'           => 'Ὁρᾶν καὶ ἀποκαθιστᾶν τὰς διεγραμμένας δέλτους',
'viewdeletedpage'        => 'Δεικνύναι διαγραφείσας δέλτους',
'undeleterevisions'      => '$1 {{PLURAL:$1|ἀναθεώρησις|ἀναθεωρήσεις}} ἀρχειοθετημέν-η/-αι',
'undelete-nodiff'        => 'Οὐδεμία προηγηθεῖσα ἀναθεώρησις εὑρέθη.',
'undeletebtn'            => 'Ἀνορθοῦν',
'undeletelink'           => 'ἐπανίσταναι',
'undeletereset'          => 'Ἐπαναθέτειν',
'undeletecomment'        => 'Σχόλιον:',
'undeletedarticle'       => 'ἐπανιστάν "[[$1]]"',
'undelete-search-box'    => 'Ζητεῖν διεγραμμένας δέλτους',
'undelete-search-prefix' => 'Δεικνύναι δέλτους· ἐκκινεῖν ἐκ:',
'undelete-search-submit' => 'Ζητεῖν',
'undelete-error-short'   => 'Σφαλματικὸν τὸ διαγράφειν τὸ ἀρχεῖον: $1',

# Namespace form on various pages
'namespace'      => 'Ὀνοματεῖον:',
'invert'         => 'Ἀντιστρέφειν ἐπιλογήν',
'blanknamespace' => '(Κύριον ὀνοματεῖον)',

# Contributions
'contributions'       => 'Ἔρανοι χρωμένου',
'contributions-title' => 'Ἔρανοι χρωμένου διὰ τὸ $1',
'mycontris'           => 'Οἱ ἔρανοί μου',
'contribsub2'         => 'Πρὸς $1 ($2)',
'uctop'               => '(ἄκρον)',
'month'               => 'Μήν:',
'year'                => 'Ἔτος:',

'sp-contributions-newbies'       => 'Δεικνύναι ἐράνους νέων λογισμῶν μόνον',
'sp-contributions-newbies-sub'   => 'Ἔρανοι νέων χρωμένων',
'sp-contributions-newbies-title' => 'Ἔρανοι χρωμένου διὰ νέους λογισμούς',
'sp-contributions-blocklog'      => 'Τὰ ἀποκλῄειν',
'sp-contributions-username'      => 'IP-διεύθυνσις ἢ ὄνομα χρωμένου:',
'sp-contributions-submit'        => 'Ζητεῖν',

# What links here
'whatlinkshere'            => 'Τὰ ἐνθάδε ἄγοντα',
'whatlinkshere-title'      => 'Δέλτοι ἐζεύγμεναι ὑπὸ "$1"',
'whatlinkshere-page'       => 'Δέλτος:',
'linkshere'                => "Τάδε ἄγουσι πρὸς '''[[:$1]]''':",
'nolinkshere'              => "Οὐδένα ἄγουσι πρὸς '''[[:$1]]'''.",
'isredirect'               => 'ἀναδιευθύνειν δέλτον',
'istemplate'               => 'περίκλεισις',
'isimage'                  => 'σύνδεσμος εἰκόνος',
'whatlinkshere-prev'       => '{{PLURAL:$1|προτἐρα|Αἱ $1 προτέραι}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ἑξῆς|οἱ $1 ἑξῆς}}',
'whatlinkshere-links'      => '← σύνδεσμοι',
'whatlinkshere-hideredirs' => '$1 ἀναδιευθύνσεις',
'whatlinkshere-hidetrans'  => '$1 ὑπερκλῄσεις',
'whatlinkshere-hidelinks'  => '$1 συνδέσμους',
'whatlinkshere-hideimages' => '$1 συνδέσμους εἰκόνων',
'whatlinkshere-filters'    => 'Ἠθητήρια',

# Block/unblock
'blockip'                         => 'Ἀποκλῄειν τόνδε τὸν χρώμενον',
'blockip-legend'                  => 'Φράττειν χρώμενον',
'ipaddress'                       => 'IP-Διεύθυνσις:',
'ipadressorusername'              => 'IP-Διεύθυνσις ἢ ὄνομα χρωμένου :',
'ipbexpiry'                       => 'Λῆξις:',
'ipbreason'                       => 'Αἰτία:',
'ipbreasonotherlist'              => 'Ἑτέρα αἰτία',
'ipbanononly'                     => 'Φράττειν μόνον ἀνωνύμους χρωμένους',
'ipbcreateaccount'                => 'Ἀποτρέπειν τὴν ποίησιν λογισμοῦ',
'ipbemailban'                     => 'Ἀποτρέπειν τὴν ἀποστολὴν ἠλ-ἀγγελίας ἐκ τοῦ χρωμένου',
'ipbsubmit'                       => 'Φράττειν τόνδε τὸν χρώμενον',
'ipbother'                        => 'Ἄλλη ὥρα:',
'ipboptions'                      => '2 ὥραι:2 hours,1 ἡμέρα:1 day,3 ἡμέραι:3 days,1 ἑβδομάς:1 week,2 ἑβδομάδες:2 weeks,1 μήν:1 month,3 μῆνες:3 months,6 μῆνες:6 months,1 ἔτος:1 year,ἐπἄπειρον:infinite', # display1:time1,display2:time2,...
'ipbotheroption'                  => 'ἄλλη',
'ipbotherreason'                  => 'Πρόσθετος/ἄλλη αἰτία:',
'badipaddress'                    => 'Ἄκυρος IP-διεύθυνσις',
'blockipsuccesssub'               => 'Φραγὴ ἐπιτευχθεῖσα',
'ipb-edit-dropdown'               => 'Μεταγράφειν τὰς αἰτίας διαγραφῆς',
'ipb-unblock-addr'                => 'Ἐκφράττειν $1',
'ipb-unblock'                     => 'Ἐκφράττειν ὄνομα χρωμένου τι ἢ IP-διεύθυνσιν τινά',
'ipb-blocklist-addr'              => 'Ὁρᾶν τὰς ὑπάρχουσας φραγὰς διὰ $1',
'ipb-blocklist'                   => 'Ὁρᾶν τὰς ὑπάρχουσας φραγὰς',
'unblockip'                       => 'Ἐκφράττειν χρώμενον',
'ipusubmit'                       => 'Ἐκφράττειν τήνδε τὴν διεύθυνσιν',
'unblocked'                       => '[[User:$1|$1]] ἐκπεφραγμέν-ος/-η',
'unblocked-id'                    => 'Ἡ φραγὴ τοῦ/τῆς $1 ἀφῃρέθη',
'ipblocklist'                     => 'Πεφραγμέναι IP-διευθύνσεις καὶ ὀνόματα τῶν χρωμένων',
'ipblocklist-legend'              => 'Εὑρίσκειν πεφραγμένον χρώμενον τινά',
'ipblocklist-username'            => 'Ὄνομα χρωμένου ἢ IP-διεύθυνσις:',
'ipblocklist-submit'              => 'Ζητεῖν',
'blocklistline'                   => '$3 ἐφράχθη ὑπὸ $1, $2 ($4)',
'infiniteblock'                   => 'ἄπειρον',
'expiringblock'                   => 'λῆξαν $1',
'anononlyblock'                   => 'ἀνωνύμους μόνον',
'noautoblockblock'                => 'αὐτόματος φραγὴ κατεσταλμένη',
'createaccountblock'              => 'ποίησις λογισμοῦ πεφραγμένη',
'emailblock'                      => 'ἠλεκτρονικὸν ταχυδρομεῖον πεφραγμένον',
'ipblocklist-empty'               => 'Κενὸς ὁ κατάλογος φραγῶν.',
'ipblocklist-no-results'          => 'Τὸ αἰτηθὲν ὄνομα χρωμένου ἢ IP-διεύθυνσις μὴ πεφραγμένα εἰσίν.',
'blocklink'                       => 'ἀπόκλῃσις',
'unblocklink'                     => 'χαλᾶν φραγήν',
'contribslink'                    => 'Ἔρανοι',
'blocklogpage'                    => 'Αἱ ἀποκλῄσεις',
'blocklogentry'                   => 'Κεκλῃμένος [[$1]] μέχρι οὗ $2 $3',
'unblocklogentry'                 => '$1 ἐκπεφραγμένος',
'block-log-flags-anononly'        => 'μόνον ἀνώνυμοι χρώμενοι',
'block-log-flags-nocreate'        => 'ποίησις λογισμοῦ κατεσταλμένη',
'block-log-flags-noautoblock'     => 'αὐτόματος φραγὴ κατεσταλμένη',
'block-log-flags-noemail'         => 'ἠλ-ταχυδρομεῖον πεφραγμένον',
'block-log-flags-angry-autoblock' => 'ἐνισχυμένη αὐτόματος φραγὴ δυνατὴ κατέστη',
'ipb_expiry_invalid'              => 'Χρόνος λήξεως ἄκυρος.',
'ipb_already_blocked'             => '"$1" ἤδη πεφραγμέν-ος/-η ἐστίν',
'ip_range_invalid'                => 'Ἄκυρον IP-εὖρος.',
'blockme'                         => 'Φράξον με',
'proxyblocker'                    => 'Ἐργαλεῖον φραγῆς διακομιστῶν',
'proxyblocker-disabled'           => 'Ἥδε ἡ ἐνέργεια κατεσταλμένη εστίν.',
'proxyblocksuccess'               => 'Γενομένη.',

# Developer tools
'lockdb'             => 'Φράττειν βάσιν δεδομένων',
'unlockdb'           => 'Ἐκφράττειν βάσιν δεδομένων',
'lockbtn'            => 'Φράττειν βάσιν δεδομένων',
'unlockbtn'          => 'Ἐκφράττειν βάσιν δεδομένων',
'lockdbsuccesssub'   => 'Ἡ φραγὴ βάσεως δεδομένων ἐπετεύχθη',
'unlockdbsuccesssub' => 'Ἡ φραγὴ βάσεως δεδομένων ᾐρέθη',
'databasenotlocked'  => 'Ἡ βάσις δεδομένων οὔκ ἐστιν κεκλῃσμένη.',

# Move page
'move-page'               => 'Κινεῖν $1',
'move-page-legend'        => 'Κινεῖν τὴν δέλτον',
'movepagetext'            => "Χρῆτε τὸν ἀκόλουθον τύπον διὰ τὴν μετωνόμασιν τῆς δέλτου καὶ διὰ τὴν μεταφορὰν ὅλου τοῦ ἑοῦ ἱστορικοῦ ὑπὸ τὸ νέον ὄνομα. 
Ἡ προηγουμένη ἐπιγραφὴ τῆς δέλτου ἔσται δέλτος τις ἀνακατευθύνσεως. Οἱ τυχόντες σύνδεσμοι πρὸς τὴν προηγουμένην δέλτον ἀναλλοίωτοι ἔσονται. 
Βεβαιοῦσθε περὶ τῆς μὴ ὑπάρξεως [[Special:DoubleRedirects|διπλῶν]] ἢ [[Special:BrokenRedirects|διεφθαρμένων συνδέσμων]]. 
Ἀναλαμβάνετε τὴν εὐθύνην τοῦ ἐπιβεβαιῶσαι τὴν ὀρθὴν καὶ ὑποτιθεμένην κατεύθυνσιν τῶν συνδέσμων.

Ἐπισημείωμα: ἡ δέλτος '''οὐ''' κινήσεται εἰ ὑπάρχει ἤδη ἑτέρα δέλτος ὑπὸ τὴν νέαν ἐπιγραφήν, εἰ μὴ ἡ δελτος ταύτη κενή ἐστι '''καὶ οὐκ''' ἔχει ἱστορίαν. Δῆλα δή, ἐν περιπτώσει λάθους ὑμῶν, δύνασθε μετωνομάσειν δέλτον τινά, δίδοντες αὐτῇ τὴν ἑὴν πρότερην ὀνομασίαν, ἀλλὰ οὐ δύνασθε ὑποκαταστήσειν προϋπάρχουσαν δέλτον τινά.

'''ΠΡΟΣΟΧΗ!'''
Ἡ μετωνόμασις δέλτου τινὸς αἰφνιδία καὶ δραστικὴ ἀλλαγή ἐστιν ὁπηνίκα πρόκειται περὶ δημοφιλοῦς δέλτου· παρακαλοῦμεν ὑμᾶς ἵνα ἐξετάζητε τὰς πιθανὰς ἐπιπτώσεις ταύτης τῆς ἐνεργείας, πρὸ τῆς ἀποφάσεως ὑμῶν.",
'movepagetalktext'        => "Ἡ σχετικὴ δέλτος διαλέξεως μετακινήσεται αὐτομάτως μετὰ τῆς δέλτου ἐγγραφῆς '''ἐκτός εἰ οὐ(κ):'''
*Μετακινήσεις τὴν δέλτον εἰς διάφορον ὀνοματικὸν χῶρον (namespace), ἢ
*Ὑπάρχει ὑπὸ τὸ νέον ὄνομα μὴ κενὴ δέλτος τις διαλέξεως, ἢ
*Ἀφῄρηκας τὴν κατασήμανσιν (check) ἐκ τοῦ κυτίου κατωτέρω.

Ἐν ταύταις ταῖς περιπτώσεσι, δεῖ σοι μετακινῆσαι ἢ συγχωνεῦσαι τὴν δέλτον μέσῳ ἀντιγραφῆς-καὶ-ἐπικολήσεως.",
'movearticle'             => 'Κινεῖν τὴν δέλτον:',
'newtitle'                => 'Πρὸς τὸ νέον ὄνομα:',
'move-watch'              => 'Ἑφορᾶν τήνδε τὴν δέλτον',
'movepagebtn'             => 'Κινεῖν τὴν δέλτον',
'pagemovedsub'            => 'Κεκίνηται ἡ δέλτος',
'movepage-moved'          => '<big>\'\'\'"$1" κεκίνηται πρὸς "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Ἢ ἐστὶ δέλτος τις οὔτως ὀνομαστὶ ἢ ἀπόρρητον τὸ ὄνομα.
Ἄλλως τὴν δέλτον ὀνόμασον.',
'talkexists'              => "'''Κεκίνηται μὲν ἡ δέλτος αὐτὴ, ἡ δὲ διαλόγου δέλτος οὐ κεκίνηται ὅτι ἤδη ἐστὶ ἐνθάδε διαλόγου δέλτος.
Δεῖ σοι καθ'ἕκαστον συγκεραννύναι.'''",
'movedto'                 => 'Κεκίνηται πρὸς',
'movetalk'                => 'Κινεῖν τὴν διαλόγου δέλτον',
'1movedto2'               => '[[$1]] ἐκινήθη πρὸς [[$2]]',
'movelogpage'             => 'Τὰ κινηθέντα',
'movereason'              => 'Ἀπολογία:',
'revertmove'              => 'Ἐπανέρχεσθαι',
'delete_and_move'         => 'Διαγράφειν καὶ κινεῖν',
'delete_and_move_text'    => '==Διαγραφὴ ἀπαραίτητος==
Ἡ ἐγγραφὴ [[:$1]] ὑπάρχει ἤδη. Βούλῃ διαγράψειν τήνδε ἵνα ἐκτελέσηται ἡ μετακίνησις;',
'delete_and_move_confirm' => 'Ναί, διάγραψον τὴν δέλτον',
'imageinvalidfilename'    => 'Τὸ ὄνομα ἀρχείου - στόχος ἄκυρον ἐστίν',

# Export
'export'            => 'Δέλτους ἐξάγειν',
'export-submit'     => 'Ἐξάγειν',
'export-addcattext' => 'Προστιθέναι δέλτους ἐκ τῆς κατηγορίας:',
'export-addcat'     => 'Προστιθέναι',
'export-download'   => 'Σῴζειν ὡς ἀρχεῖον',
'export-templates'  => 'Περιλαμβάνειν πρότυπα',

# Namespace 8 related
'allmessages'         => 'Μυνήματα συστήματος',
'allmessagesname'     => 'Ὄνομα',
'allmessagesdefault'  => 'Προεπειλεγμένον κείμενον',
'allmessagescurrent'  => 'Τρέχον κείμενον',
'allmessagesfilter'   => 'Διηθητήριον ὀνόματος ἀγγελίας:',
'allmessagesmodified' => 'Δεικνύναι μόνον τὰ μεταβεβλημένα',

# Thumbnails
'thumbnail-more'  => 'Αὐξάνειν',
'filemissing'     => 'Ἀρχεῖον ἐκλιπόν',
'thumbnail_error' => 'Σφάλμα τοῦ δημιουργεῖν σύνοψιν: $1',
'djvu_page_error' => 'DjVu-δέλτος ἐκτὸς ἐμβελείας',
'djvu_no_xml'     => 'Ἀδύνατον τὸ προσκομίζειν τὴν XML διὰ τὸ DjVu-ἀρχεῖον',

# Special:Import
'import'                     => 'Εἰσάγειν δέλτους',
'importinterwiki'            => 'Ὑπερδιαϝίκι-εἰσαγωγή',
'import-interwiki-submit'    => 'Εἰσάγειν',
'import-interwiki-namespace' => 'Μετάφερε τὰς δέλτους ἐν τῷ ὀνοματείῳ:',
'importstart'                => 'Εἰσάγειν δέλτους...',
'import-revision-count'      => '$1 {{PLURAL:$1|ἀναθεώρησις|ἀναθεωρήσεις}}',
'importnopages'              => 'Οὐδεμία εἰσακτέα δέλτος.',
'importcantopen'             => 'Ἀδύνατος ἡ ἄνοιξις τοῦ ἀρχείου εἰσαγωγῆς',
'importbadinterwiki'         => 'Κακὸς διαϝικισύνδεσμος',
'importnotext'               => 'Κενὸν ἢ οὐδὲν κείμενον',
'importsuccess'              => 'Εἰσαγωγὴ τετελεσμένη!',
'importnofile'               => 'Οὐδὲν ἐπιφορτισθὲν ἀρχεῖον εἰσαγωγῆς.',
'import-noarticle'           => 'Οὐδεμία εἰσακτέα δέλτος!',
'import-upload'              => 'Ἐπιφόρτωσις δεδομένων XML',

# Import log
'importlogpage'                    => 'Εἰσάγειν κατάλογον',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|ἀναθεώρησις|ἀναθεωρήσεις}}',
'import-logentry-interwiki'        => 'οὐικιπεποιημένη $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|ἀναθεώρησις|ἀναθεωρήσεις}} ἐκ τοῦ $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Τὴν δέλτον χρωμένου ἐμήν',
'tooltip-pt-mytalk'               => 'Ἡ διάλεξίς μου',
'tooltip-pt-anontalk'             => 'Δίαλεξις περὶ τῶν μεταγραφῶν μέσῳ τῆσδε τῆς IP-διευθύνσεως',
'tooltip-pt-preferences'          => 'Αἱ αἱρέσεις μου',
'tooltip-pt-watchlist'            => 'Κατάλογος τῶν ἐφορωμένων μου',
'tooltip-pt-mycontris'            => 'Κατάλογος τῶν ἐράνων μου',
'tooltip-pt-login'                => 'Ἐνθαρρυντέον τὸ συνδεῖσθαι, οὐκ ὑποχρεωτικόν.',
'tooltip-pt-anonlogin'            => 'Ἐνθαρρυντέον τὸ συνδεῖσθαι, οὐκ ὑποχρεωτικόν.',
'tooltip-pt-logout'               => 'Ἐξέρχεσθαι',
'tooltip-ca-talk'                 => 'Διάλεκτος περὶ τῆς δέλτου',
'tooltip-ca-edit'                 => 'Ἔξεστί σοι μεταγράφειν τήνδε τὴν δέλτον. Προθεωρεῖν πρὶν ἂν γράφῃς τὴν δέλτον.',
'tooltip-ca-addsection'           => 'Προστιθέναι σχόλιόν τι τῇδε τῇ διαλέξει.',
'tooltip-ca-viewsource'           => 'Σῴζεται ἥδε ἡ δέλτος.
Ἔξεστί σοι τὴν πηγήν ἐπισκοπεῖν.',
'tooltip-ca-history'              => 'Προηγουμέναι ἐκδόσεις τῆσδε τῆς δέλτου.',
'tooltip-ca-protect'              => 'Ἀμύνειν τῇδε τῇ δέλτῳ',
'tooltip-ca-delete'               => 'Διαγράφειν τήνδε τὴν δέλτον',
'tooltip-ca-move'                 => 'Κινεῖν τήνδε τὴν δέλτον',
'tooltip-ca-watch'                => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'tooltip-ca-unwatch'              => 'Ἀνεφορᾶν τήνδε τὴν δέλτον',
'tooltip-search'                  => 'Ζητεῖν {{SITENAME}}',
'tooltip-p-logo'                  => 'Δέλτος Μεγίστη',
'tooltip-n-mainpage'              => 'Πορεύεσθαι τὴν κυρίαν Δέλτον',
'tooltip-n-portal'                => 'Τὰ περὶ ταύτης τῆς ἐγκυκλοπαιδείας, τῶν οἵων ἔξεστί σοι πράττεις, ποῦ πάρεστί τινα',
'tooltip-n-currentevents'         => 'Πληροφορίαι ἐπὶ ἐπικαίρων γεγονότων',
'tooltip-n-recentchanges'         => 'Κατάλογος κατὰ πᾶσας τὰς νέας μεταβολάς.',
'tooltip-n-randompage'            => 'Τινὰ δέλτον χύδην ἐπιφορτίζειν',
'tooltip-n-help'                  => 'Μάθησις περὶ τοῦδε τοῦ ϝίκι.',
'tooltip-t-whatlinkshere'         => 'Κατάλογος τῶν ἐνθάδε ἀγόντων',
'tooltip-feed-rss'                => 'RSS Ῥοὴ διὰ τήνδε δέλτον',
'tooltip-feed-atom'               => 'Atom Ῥοὴ διὰ τήνδε δέλτον',
'tooltip-t-contributions'         => 'Ὁρᾶν τοὺς τοῦδε τοῦ χρωμένου ἐράνους',
'tooltip-t-emailuser'             => 'Ἠλεκτρονικὴν ἐπιστολὴν τῷδε τῷ χρήστῃ πέμπειν',
'tooltip-t-upload'                => 'Φορτία ἐντιθέναι',
'tooltip-t-specialpages'          => 'Κατάλογος κατὰ πᾶσας τὰς εἰδικὰς δέλτους',
'tooltip-t-print'                 => 'Ἐκτυπώσιμος ἔκδοσις τῆσδε τῆς δέλτου',
'tooltip-ca-nstab-main'           => 'χρῆμα δέλτον ὁρᾶν',
'tooltip-ca-nstab-user'           => 'Δέλτος χρωμένου ὁρᾶν',
'tooltip-ca-nstab-media'          => 'Ὁρᾶν τὴν δέλτον μέσων',
'tooltip-ca-nstab-project'        => 'Ὁρᾶν τὴν δέλτον τοῦ σχεδίου',
'tooltip-ca-nstab-image'          => 'Ὁρᾶν τὴν τοῦ φορτίου δέλτον',
'tooltip-ca-nstab-mediawiki'      => 'Ὁρᾶν τὸ μήνυμα τοῦ συστήματος',
'tooltip-ca-nstab-template'       => 'Ὁρᾶν πρότυπον',
'tooltip-ca-nstab-help'           => 'Ὁρᾶν δέλτον βοηθείας',
'tooltip-ca-nstab-category'       => 'Ἐπισκοπεῖν τὴν τῆς κατηγορίας δέλτον',
'tooltip-minoredit'               => 'Δεικνύναι ἥδε ἡ μεταβολή μικρά εἴναι',
'tooltip-save'                    => 'Γράφειν τὰς μεταβολάς σου',
'tooltip-preview'                 => 'Προεπισκοπεῖν τὰς ἀλλαγὰς ὑμῶν. Παρακαλοῦμεν ὑμᾶς ἵνα χρῆτε ταύτην τὴν ἐπιλογὴν πρὸ τοῦ αποθηκεύειν!',
'tooltip-diff'                    => 'Δεῖξαι τὰς γεγράμμενας μεταβολάς.',
'tooltip-compareselectedversions' => 'Ὁρᾶν τὰς διαφορὰς μεταξὺ τῶν δύω ἐπιλελεγμένων ἐκδοχῶν ταύτης τῆς δέλτου.',
'tooltip-watch'                   => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'tooltip-upload'                  => 'Ἐκκινεῖν ἐπιφόρτωσιν',

# Metadata
'nodublincore'      => 'Τὰ RDF-μεταδεδομένα τὰ ἀφορῶντα τὸ πρότυπον κανόνων Dublin Core ἀπενεργηθέντα εἰσὶ ἐν τῇδε τῇ ἐξυπηρετικῇ μηχανῇ.',
'nocreativecommons' => 'Τὰ RDF-μεταδεδομένα τὰ ἀφορῶντα τὰ Creative Commons (Δημιουργικὰ Κοινά) ἀπενεργηθέντα εἰσὶ ἐν τῇδε τῇ ἐξυπηρετικῇ μηχανῇ.',

# Attribution
'anonymous'        => 'Ἀνώνυμ-ος,-οι χρώμεν-ος,-οι τῷ {{SITENAME}}',
'siteuser'         => 'Χρώμενος τῷ {{SITENAME}} $1',
'lastmodifiedatby' => 'Ἥδε ἡ δἐλτος ἠλλάχθη ὑστάτως $2, $1 ἐκ τοῦ $3.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Βεβασισμένον τῷ ἔργῳ τοῦ/τῶν $1.',
'others'           => 'ἄλλοι',
'creditspage'      => 'Διαπιστεύσεις δέλτου',

# Info page
'infosubtitle'   => 'Πληροφορίαι περὶ τῆς δἐλτου',
'numedits'       => 'Ἀριθμὸς μεταγραφῶν (ἐν τῇ δέλτῳ): $1',
'numtalkedits'   => 'Ἀριθμὸς μεταγραφῶν (δέλτος διαλέξεως): $1',
'numwatchers'    => 'Ἀριθμὸς ἐφορώντων: $1',
'numauthors'     => 'Ἀριθμὸς διακεκριμένων πρωτουργῶν (ἐν τῇ δέλτῳ): $1',
'numtalkauthors' => 'Ἀριθμὸς διακεκριμένων πρωτουργῶν (ἐν τῇ δέλτῳ διαλέξεως): $1',

# Math options
'mw_math_png'    => 'Ἀπόδοσις PNG πάντοτε',
'mw_math_simple' => 'HTML εἰ λίαν ἁπλοῦν, εἰδἄλλως PNG',
'mw_math_html'   => 'HTML εἰ δυνατὸν, εἰδἄλλως PNG',
'mw_math_source' => 'Ἄφες το ὡς TeX (διὰ πλοηγοὺς κειμένων)',
'mw_math_modern' => 'Προτεινομένη διὰ συγχρόνους πλοηγούς',
'mw_math_mathml' => 'MathML εἰ δυνατόν (πειραστικόν)',

# Patrolling
'markaspatrolleddiff' => 'Σεσημασμένη ὡς φρουρουμένη',
'markaspatrolledtext' => 'Σημαίνειν τήνδε τὴν δέλτον ὡς φρουρούμενη',
'markedaspatrolled'   => 'Σεσημασμένη ὡς ἐπιτηρουμένη',

# Patrol log
'patrol-log-page' => 'Κατάλογος περιπόλων',
'patrol-log-auto' => '(αὐτόματον)',

# Browsing diffs
'previousdiff' => '← ἡ διαφορὰ ἡ προτέρα',
'nextdiff'     => 'ἡ μεταβολὴ ἡ ἐχομένη →',

# Media information
'thumbsize'            => 'Μέγεθος μικρογραφίας:',
'file-info-size'       => '($1 × $2 εἰκονοστοιχεῖα, μέγεθος ἀρχείου: $3, τύπος MIME: $4)',
'file-nohires'         => '<small>Οὐ διατίθεται ὑψηλοτέρα ἀνάλυσις.</small>',
'svg-long-desc'        => '(ἀρχεῖον SVG, ὀνομαστὶ $1 × $2 εἰκονοστοιχεῖα, μέγεθος ἀρχείου: $3)',
'show-big-image'       => 'Πλήρης ἀνάλυσις',
'show-big-image-thumb' => '<small>Τῆσδε τῆς προθεωρήσεως μέγεθος: $1 × $2 εἰκονοστοιχεῖα</small>',

# Special:NewImages
'newimages'        => 'Τὰ νέα φορτία δεῦρο ἀθροίζειν',
'imagelisttext'    => "Κάτωθι κατάλογός ἐστιν '''$1''' {{PLURAL:$1|ἀρχείου|ἀρχείων}} ταξινομημέν-ου/-ων κατὰ σειρὰν $2.",
'newimages-legend' => 'Διηθητήριον',
'newimages-label'  => 'Ἀρχειώνυμον (ἢ μέρος οὗ):',
'showhidebots'     => '($1 αὐτόματα)',
'noimages'         => 'Οὐδεμία εἰκών.',
'ilsubmit'         => 'Ζητεῖν',
'bydate'           => 'κατὰ χρονολογίαν',

# Bad image list
'bad_image_list' => 'Ἡ μορφοποιία ὡς ἑξῆς ἐστίν:

Μόνον ἀντικείμενα διαλογῆς (γραμμαὶ ἐκκινουμέναι μετὰ τοῦ *) δεκτὰ εἰσίν. 
Ὁ πρῶτος σύνδεσμος ἐν ἐνίτινι γραμμῇ ὀφείλει εἶναι σύνδεσμος πρὸς ἕνα κακὸν ἀρχεῖον.
Οἷοι δή ποτε ἐπακόλουθοι σύνδεσμοι ἐν τῇ αὐτῇ γραμμῇ θεωρουμένοι ἐξαιρέσεις εἰσίν, δῆλα δὴ δέλτοι ὅπου ἡ εἰκὼν δύναται ξυμβῆναι ἐν συνδέσει.',

# Metadata
'metadata'          => 'Μεταδεδομένα',
'metadata-help'     => 'Τόδε ἀρχεῖον περιέχει προσθέτους πληροφορίας, πιθανὼς προστεθειμένας ἐκ τῆς ψηφιακῆς φωτογραφικῆς μηχανῆς ἢ τοῦ σαρωτοῦ χρησθέντος τῇ δημιουργίᾳ ἢ τῇ ἑῇ ψηφιοποιήσει. Εἰ τὸ ἀρχεῖον ἤλλακται ἐκ τῆς ἑῆς ἀρχικῆς καταστάσεως, ἀκρίβειαί τινες πιθανὼς μὴ πλήρως τὸ ἠλλαγμενον ἀρχεῖον ἀνακλοῦσιν.',
'metadata-expand'   => 'Δηλοῦν τὰς ἀκριβείας',
'metadata-collapse' => 'Κρύπτειν τὰς ἀκριβείας',
'metadata-fields'   => 'Τὰ πεδία μεταδεδομένων EXIF τοῦδε τοῦ μηνύματος περιλήψονται ἐν τῇ δέλτῳ ἐμφανίσεως εἰκόνος ὁπηνίκα ὁ πίναξ μεταδεδομένων ἀποκρύψηται. Τὰ ἕτερα πεδία ἔσονται κεκρυμμένα κατὰ προεπιλογήν.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'Πλάτος',
'exif-imagelength'                 => 'Ὕψος',
'exif-bitspersample'               => 'Δυφία ἀνὰ συνιστῶσαν',
'exif-compression'                 => 'Σχῆμα συμπιέσεως',
'exif-photometricinterpretation'   => 'Σύνθεσις εἰκονοστοιχείων',
'exif-orientation'                 => 'Προσανατόλισις',
'exif-planarconfiguration'         => 'Παράταξις δεδομένων',
'exif-xresolution'                 => 'Ὁριζόντιος ἀνάλυσις',
'exif-yresolution'                 => 'Κάθετος ἀνάλυσις',
'exif-stripoffsets'                => 'Τοποθεσία δεδομένων εἰκόνος',
'exif-jpeginterchangeformat'       => 'Μετάθεσις εἰς JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Δυφιολέξεις δεδομένων JPEG',
'exif-transferfunction'            => 'Συνάρτησις μεταφορᾶς',
'exif-whitepoint'                  => 'Χρωματικότης λευκοῦ σημείου',
'exif-primarychromaticities'       => 'Πρωτεύουσαι χρωματικότητες',
'exif-imagedescription'            => 'Ἐπιγραφὴ εἰκόνος',
'exif-make'                        => 'Ἐξεργαστὴς τῆς εἰκονοληπτικῆς μηχανῆς',
'exif-model'                       => 'Πρότυπον τῆς εἰκονοληπτικῆς μηχανῆς',
'exif-software'                    => 'Χρησθὲν λογισμικόν',
'exif-artist'                      => 'Πρωτουργός',
'exif-copyright'                   => 'Κάτοχος δικαιωμάτων',
'exif-exifversion'                 => 'ἔκδοσις Exif',
'exif-flashpixversion'             => 'Ὑποστηριζομένη ἔκδοσις τῆς μορφοποιίας Flashpix',
'exif-colorspace'                  => 'Χρωματικὸς χῶρος',
'exif-componentsconfiguration'     => 'Νόημα ἑκάστης συνιστώσης',
'exif-compressedbitsperpixel'      => 'Τρόπος συμπιέσεως εἰκόνος',
'exif-pixelydimension'             => 'Ἔγκυρον πλάτος εἰκόνος',
'exif-pixelxdimension'             => 'Ἔγκυρον ὕψος εἰκόνος',
'exif-makernote'                   => 'Ἐπισημειώσεις ἐξεργαστοῦ',
'exif-usercomment'                 => 'Σχόλια χρωμένου',
'exif-relatedsoundfile'            => 'Σχετιζόμενον ἀρχεῖον ἤχου',
'exif-datetimeoriginal'            => 'Χρονολογία καὶ ὥρα παραγωγῆς δεδομένων',
'exif-datetimedigitized'           => 'Χρονολογία καὶ ὥρα ψηφιοποιήσεως',
'exif-exposuretime'                => 'Χρόνος ἐκθέσεως',
'exif-exposuretime-format'         => '$1 δευτ. ($2)',
'exif-fnumber'                     => 'Ἀριθμός F',
'exif-exposureprogram'             => 'Πρόγραμμα ἐκθέσεως',
'exif-spectralsensitivity'         => 'Φασματικὴ εὐαισθητότης',
'exif-isospeedratings'             => 'Βαθμολόγησις ταχύτητος ISO',
'exif-oecf'                        => 'Παράγων ὀπτοηλεκτρονικῆς μετατροπῆς',
'exif-shutterspeedvalue'           => 'Ταχύτης κλῄσεως',
'exif-aperturevalue'               => 'Ἄνοιξις διαφράγματος',
'exif-brightnessvalue'             => 'Φωτεινότης',
'exif-exposurebiasvalue'           => 'Προτεραιότης ἐκθέσεως',
'exif-subjectdistance'             => 'Ἀπόστασις ἀντικειμένου',
'exif-meteringmode'                => 'Κατάστασις φωτομέτρου',
'exif-lightsource'                 => 'Πηγὴ φωτός',
'exif-flash'                       => 'Ἀστραποβόλον',
'exif-focallength'                 => 'Ἑστιακὸν μῆκος φακοῦ',
'exif-subjectarea'                 => 'Θεματικὸν πεδίον',
'exif-flashenergy'                 => 'Ἐνέργεια τῆς ἀστραποβόλου συσκευῆς',
'exif-spatialfrequencyresponse'    => 'Ἀπόκρισις χωρικῆς συχνότητος',
'exif-focalplanexresolution'       => 'Ἀνάλυσις ἑστιακοῦ ἐπιπέδου X',
'exif-focalplaneyresolution'       => 'Ἀνάλυσις ἑστιακοῦ ἐπιπέδου Y',
'exif-focalplaneresolutionunit'    => 'Μονὰς μετρήσεως ἀναλύσεως ἑστιακοῦ ἐπιπέδου',
'exif-subjectlocation'             => 'Τοποθεσία ἀντικειμένου',
'exif-exposureindex'               => 'Δείκτης ἐκθέσεως',
'exif-sensingmethod'               => 'Μέθοδος αἰσθητῆρος',
'exif-filesource'                  => 'Πηγὴ ἀρχείου',
'exif-scenetype'                   => 'Τύπος σκηνῆς',
'exif-cfapattern'                  => 'Πρὀτυπον CFA',
'exif-customrendered'              => 'Συνήθης ἐπεξεργασία εἰκόνος',
'exif-exposuremode'                => 'Τρόπος ἐκθέσεως',
'exif-whitebalance'                => 'Ἰσορροπία λευκῶν',
'exif-digitalzoomratio'            => 'Ἀναλογία ψηφιακῆς μεταβλητῆς ἑστιάσεως',
'exif-focallengthin35mmfilm'       => 'Ἑστιακὴ ἀπόστασις ἐν πελνῷ 35 χστ.μ.',
'exif-scenecapturetype'            => 'Τύπος συλλήψεως σκηνῆς',
'exif-gaincontrol'                 => 'Ἔλεγχος σκηνῆς',
'exif-contrast'                    => 'Ἀντίθεσις',
'exif-saturation'                  => 'Κορεσμός',
'exif-sharpness'                   => 'Ὀξύτης',
'exif-devicesettingdescription'    => 'Περιγραφὴ ῥυθμίσεων τῆς συσκευῆς',
'exif-subjectdistancerange'        => 'Ἐμβέλεια διακύμανσεως τῆς ἀποστάσεως τοῦ ἀντικειμένου',
'exif-imageuniqueid'               => 'Μονοσήμαντος ταυτοποίησις εἰκόνος',
'exif-gpsversionid'                => 'Ἔκδοσις μετὰ GPS σημάντρου',
'exif-gpslatituderef'              => 'Βόρειον ἢ Νότιον γεωγραφικὸν πλάτος',
'exif-gpslatitude'                 => 'Πλάτος γεωγραφικόν',
'exif-gpslongituderef'             => 'Ἀνατολικὸν ἢ Δυτικὸν γεωγραφικὸν μῆκος',
'exif-gpslongitude'                => 'Γεωγραφικὸν μῆκος',
'exif-gpsaltituderef'              => 'Ἀναφορὰ ὕψους γεωγραφικοῦ',
'exif-gpsaltitude'                 => 'Γεωγραφικὸν ὕψος',
'exif-gpstimestamp'                => 'Χρόνος GPS (ἀτομικὸν ὡρολόγιον)',
'exif-gpssatellites'               => 'Δορυφόροι χρησθέντες ταῖς μετρήσεσιν',
'exif-gpsstatus'                   => 'Κατάστασις δέκτου',
'exif-gpsmeasuremode'              => 'Τρόπος μετρήσεως',
'exif-gpsdop'                      => 'Ἀκριβεία μετρήσεως',
'exif-gpsspeedref'                 => 'Μονὰς ταχύτητος',
'exif-gpsspeed'                    => 'Ταχύτης τοῦ δέκτου GPS',
'exif-gpstrackref'                 => 'Ἀναφορὰ ἐπὶ τῆς κατευθύνσεως τῆς κινήσεως',
'exif-gpstrack'                    => 'Κατεύθυνσις κινήσεως',
'exif-gpsimgdirectionref'          => 'Ἀναφορὰ ἐπὶ τῆς κατευθύνσεως τῆς εἰκόνος',
'exif-gpsimgdirection'             => 'Κατεύθυνσις εἰκόνος',
'exif-gpsmapdatum'                 => 'Κεχρησμένα δεδομένα γεωδαιτικῶν μετρήσεων',
'exif-gpsdestlatituderef'          => 'Ἀναφορὰ ἐπὶ τοῦ γεωγραφικοῦ πλάτους τοῦ προορισμοῦ',
'exif-gpsdestlatitude'             => 'Γεωγραφικὸν πλάτος προορισμοῦ',
'exif-gpsdestlongituderef'         => 'Ἀναφορὰ ἐπὶ τοῦ γεωγραφικοῦ μήκους τοῦ προορισμοῦ',
'exif-gpsdestlongitude'            => 'Γεωγραφικὸν μῆκος προορισμοῦ',
'exif-gpsdestbearingref'           => 'Ἀναφορὰ ἐπὶ τῶν συντεταγμένων προορισμοῦ',
'exif-gpsdestbearing'              => 'Συντεταγμέναι προορισμοῦ',
'exif-gpsprocessingmethod'         => 'Ὄνομα μεθόδου ἐπεξεργασίας τοῦ GPS',
'exif-gpsareainformation'          => 'Ὄνομα GPS-ζώνης',
'exif-gpsdatestamp'                => 'Χρονολογία GPS',
'exif-gpsdifferential'             => 'Διαφορικὴ διόρθωσις τοῦ GPS',

# EXIF attributes
'exif-compression-1' => 'Ἀσυμπίεστος',

'exif-unknowndate' => 'Ἄγνωτος χρονολογία',

'exif-orientation-1' => 'Κανονικόν', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Ἀντεστραμμένη ὁριζοντίως', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Περιεστραμμένη κατὰ 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Ἀντεστραμμένη καθέτως', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Περιεστραμμένη 90° ἀνθωρολογιακώς καὶ ἀντεστραμμένη καθέτως', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Περιεστραμμένη 90° ὡρολογιακώς', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Περιεστραμμένη 90° ὡρολογιακώς καὶ ἀντεστραμμένη καθέτως', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Περιεστραμμένη 90° ἀνθωρολογιακώς', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'πεπλατυσμένη μορφοποιία',
'exif-planarconfiguration-2' => 'ἐπίπεδος μορφοποιία',

'exif-componentsconfiguration-0' => 'Οὐκ ἔστι',

'exif-exposureprogram-0' => 'Ἀκαθόριστον',
'exif-exposureprogram-1' => 'Χειροκίνητον',
'exif-exposureprogram-2' => 'Κανονικὸν πρόγραμμα',
'exif-exposureprogram-3' => 'Προτεραιότης ἀνοἰξεως διαφράγματος',
'exif-exposureprogram-4' => 'Προτεραιότης κλείστρου',

'exif-subjectdistance-value' => '$1 μέτρα',

'exif-meteringmode-0'   => 'Ἄγνωτον',
'exif-meteringmode-1'   => 'Μέσον',
'exif-meteringmode-2'   => 'Κεντροβαρὴς Μέση Τιμή',
'exif-meteringmode-3'   => 'Μονοσημειακόν',
'exif-meteringmode-4'   => 'Πολυσημειακόν',
'exif-meteringmode-5'   => 'Ὑπόδειγμα',
'exif-meteringmode-6'   => 'Μερικόν',
'exif-meteringmode-255' => 'Ἄλλη',

'exif-lightsource-0'   => 'Ἄγνωτη',
'exif-lightsource-1'   => 'Ἡμερινὸν φῶς',
'exif-lightsource-2'   => 'Φθορίζον',
'exif-lightsource-3'   => 'Βαρυλίθιον (πυρακτωσικὸν φῶς)',
'exif-lightsource-4'   => 'Ἀστραποβόλος συσκευή',
'exif-lightsource-9'   => 'Αἴθρια μετέωρα',
'exif-lightsource-10'  => 'Συννεφῆ μετέωρα',
'exif-lightsource-11'  => 'Σκίασις',
'exif-lightsource-12'  => 'Ἡμερινοφωτικὴ φθοριοφάνεια (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Ἡμερινὴ λευκὴ φθοριοφάνεια (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Ψυχρὴ λευκὴ φθοριοφάνεια (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Λευκὴ φθοριοφάνεια (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Τυπικὸν φῶς A',
'exif-lightsource-18'  => 'Τυπικὸν φῶς B',
'exif-lightsource-19'  => 'Τυπικὸν φῶς C',
'exif-lightsource-24'  => 'Βαρυλίθιον τοῦ ἐργαστηρίου ISO',
'exif-lightsource-255' => 'Ἑτέραι φωτοπηγαί',

'exif-focalplaneresolutionunit-2' => 'οὐγκιαί',

'exif-sensingmethod-1' => 'Ἀόριστος',
'exif-sensingmethod-7' => 'Τριγραμμικὸν αἰσθητήριον',

'exif-scenetype-1' => 'Ἀπεὐθείας φωτογραφημένη εἰκών',

'exif-customrendered-0' => 'Κανονικὴ διαδικασία',
'exif-customrendered-1' => 'Συνήθης διαδικασία',

'exif-exposuremode-0' => 'Αὐτοέκθεσις',
'exif-exposuremode-1' => 'Χειροκίνητος ἔκθεσις',
'exif-exposuremode-2' => 'Αὐτόματον ἄγγιστρον',

'exif-whitebalance-0' => 'Αὐτόματος ἰσορροπία λευκῶν',
'exif-whitebalance-1' => 'Χειροκίνητος ἰσορροπία λευκῶν',

'exif-scenecapturetype-0' => 'Συνήθης',
'exif-scenecapturetype-1' => 'Τοπίον',
'exif-scenecapturetype-2' => 'Παράστασις',
'exif-scenecapturetype-3' => 'Νυκτερινὴ σκηνή',

'exif-gaincontrol-0' => 'Οὐδεμία',
'exif-gaincontrol-1' => 'Χθαμηλὸν κέρδος ἄνω',
'exif-gaincontrol-2' => 'Ὑψηλὸν κέρδος ἄνω',
'exif-gaincontrol-3' => 'Χθαμηλὸν κέρδος κάτω',
'exif-gaincontrol-4' => 'Ὑψηλὸν κέρδος κάτω',

'exif-contrast-0' => 'Κανονική',
'exif-contrast-1' => 'Ἁπαλή',
'exif-contrast-2' => 'Σκληρή',

'exif-saturation-0' => 'Κανονικόν',
'exif-saturation-1' => 'Χθαμηλὸς κορεσμός',
'exif-saturation-2' => 'Ὑψηλὸς κορεσμός',

'exif-sharpness-0' => 'Κανονική',
'exif-sharpness-1' => 'Ἁπαλή',
'exif-sharpness-2' => 'Σκληρή',

'exif-subjectdistancerange-0' => 'Ἄγνωτη',
'exif-subjectdistancerange-1' => 'Μακρο.',
'exif-subjectdistancerange-2' => 'Ἐγγεῖα θέα',
'exif-subjectdistancerange-3' => 'Ἀφεστηκυῖα θέα',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Βόρειον γεωγραφικὸν πλάτος',
'exif-gpslatitude-s' => 'Νότιον γεωγραφικὸν πλάτος',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Ἀνατολικὸν γεωγραφικὸν μῆκος',
'exif-gpslongitude-w' => 'Δυτικὸν γεωγραφικὸν μῆκος',

'exif-gpsstatus-a' => 'Μέτρησις ἐν ἐξελίξει',
'exif-gpsstatus-v' => 'Διαχρηστικότης μετρήσεων',

'exif-gpsmeasuremode-2' => '2-διάστατος μέτρησις',
'exif-gpsmeasuremode-3' => '3-διάστατος μέτρησις',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Χιλιόμετρα ἀνὰ ὥρα',
'exif-gpsspeed-m' => 'Μίλια ἀνὰ ὥρα',
'exif-gpsspeed-n' => 'Κόμβοι',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Ἀληθὴς κατεύθυνσις',
'exif-gpsdirection-m' => 'Μαγνητικὴ διεύθυνσις',

# External editor support
'edit-externally'      => 'Μεταγράφειν τόδε τὸ ἀρχεῖον χρώμενος ἐξώτερήν τινα ἐφαρμογήν.',
'edit-externally-help' => 'Εἰ πλείοντα βούλει μαθεῖν, [http://www.mediawiki.org/wiki/Manual:External_editors τὰς περὶ τοῦ σχῆματος διδασκαλίας] λέξε.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Πᾶσαι',
'imagelistall'     => 'Πᾶσαι',
'watchlistall2'    => 'ἅπασαι',
'namespacesall'    => 'ἅπασαι',
'monthsall'        => 'ἅπαντες',

# E-mail address confirmation
'confirmemail'             => 'Ἐπιβεβαίωσον διεύθυνσιν ἠλ-ταχυδρομείου',
'confirmemail_send'        => 'Ταχυδρομήσειν κώδικα ἐπιβεβαιώσεως',
'confirmemail_sent'        => 'Ἐπιβεβαίωσις διευθύνσεως ἠλ.-ταχυδρομείου ἐστάλη.',
'confirmemail_subject'     => 'ἐπιβεβαίωσις διευθύνσεως ἠλ.-ταχυδρομείου τοῦ {{SITENAME}}',
'confirmemail_body'        => 'Τίς (πιθανὼς σύ, ἐκ τῆς IP-διευθύνσέως $1) ἔχων τήνδε τὴν ἠλ-διεύθυνσιν κατέγραψεν λογισμόν τινα "$2" ἐν τω ἱστοτόπω {{SITENAME}}. Διὰ τὸ ἐπιβεβαιώσειν τὴν ἐτεὴ κατοχὴ τοῦ λογισμοῦ χρωμένου ὑπ\' ἐσοῦ καὶ διὰ τὸ ἐνεργοποιἠσειν τὰς δυνατότητας ἠλ-ταχυδρομείου τοῦ {{SITENAME}}, ἀκολούθησον τόνδε τὸν σύνδεσμον:

$3

Εἰ *οὐ* πεποίηκας τόνδε τὸν λογισμὀν, ἀκολοὐθησον τὸν κατωτέρω σύνδεσμον καὶ ἀκύρωσον τῆν ἐπιβεβαίωσιν τῆς ἠλ-διεύθυνσεως:

$5

Ὅδε ὁ κῶδιξ ἐπιβεβαιώσεως λεληγμένος ἔσεται ἐν $4.',
'confirmemail_invalidated' => 'Ἀκυρωθεῖσα ἡ ἐπιβεβαίωσις τῆς ἠλ-διευθύνσεως',
'invalidateemail'          => 'Ἀκυρώσειν τὴν ἐπιβεβαίωσιν ἠλ-διευθύνσεως',

# Scary transclusion
'scarytranscludedisabled' => '[Διαϝίκι-ὑπερκλῄειν μὴ ἐνεργόν]',
'scarytranscludefailed'   => '[Τὸ προσκομίζειν τὸ πρότυπον διὰ τὸ $1 ἀπετεύχθη· συγγνώμην]',
'scarytranscludetoolong'  => '[Ὁ URL ὑπὲρ τὸ δέον μακρύς ἐστιν· συγγνώμην]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">Ὀνασύνδεσμοι διὰ τήνδε ἐγγραφήν:<br />
$1
</div>',
'trackbackremove'   => '  ([$1 Διαγράφειν])',
'trackbacklink'     => 'Ὀνασύνδεσμος',
'trackbackdeleteok' => 'Ὀνασύνδεσμος ἐπιτυχὼς διαγραφείς.',

# Delete conflict
'recreate' => 'Ἀναδημιουργεῖν',

# action=purge
'confirm_purge'        => 'Καθαίρειν τὴν λανθάνουσαν μνήμην τῆσδε δέλτου;

$1',
'confirm_purge_button' => 'εἶεν',

# Multipage image navigation
'imgmultipageprev' => '← Δέλτος προτέρα',
'imgmultipagenext' => 'Δέλτος ἡ ἐχομένη →',
'imgmultigo'       => 'Ἰέναι!',
'imgmultigoto'     => 'Μεταβαίνειν εἰς δέλτον $1',

# Table pager
'ascending_abbrev'         => 'ἀναβ',
'descending_abbrev'        => 'καταβ',
'table_pager_next'         => 'Δέλτος ἡ ἐχομένη',
'table_pager_prev'         => 'Δέλτος προτέρα',
'table_pager_first'        => 'Δέλτος πρώτη',
'table_pager_last'         => 'Δέλτος ἐσχάτη',
'table_pager_limit'        => 'Δεικνύναι $1 στοιχεῖα ἀνἀ δέλτον',
'table_pager_limit_submit' => 'Ἰέναι',
'table_pager_empty'        => 'Οὐδὲν ἀποτέλεσμα',

# Auto-summaries
'autoredircomment' => 'Ἀναδιευθύνειν πρὸς τὸ [[$1]]',
'autosumm-new'     => 'Δέλτος νέα: $1',

# Size units
'size-bytes'     => '$1 Δ',
'size-kilobytes' => '$1 ΧΔ',
'size-megabytes' => '$1 ΜΔ',
'size-gigabytes' => '$1 ΓΔ',

# Live preview
'livepreview-loading' => 'Φορτίζειν…',
'livepreview-ready'   => 'Φορτίζειν… Ἕτοιμον!',

# Watchlist editor
'watchlistedit-noitems'       => 'Οὐδεμία ἐγγραφὴ ἐν τῷ καταλόγῳ ἐφορωμένων σου.',
'watchlistedit-normal-title'  => 'Μεταγράφειν κατάλογον ἐφορωμένων',
'watchlistedit-normal-legend' => 'Ἀφαιρεῖν ἐγγραφὰς ἐκ τῆς ἐφοροδιαλογῆς',
'watchlistedit-normal-submit' => 'Ἀφαιρεῖν ἐπιγραφάς',
'watchlistedit-raw-title'     => 'Μεταγράφειν πρωταρχικὸν κατάλογον ἐφορωμένων',
'watchlistedit-raw-legend'    => 'Μεταγράφειν πρωταρχικὸν κατάλογον ἐφορωμένων',
'watchlistedit-raw-titles'    => 'Ἐπιγραφαί:',
'watchlistedit-raw-submit'    => 'Ἐκσυγχρονίζειν τὸν κατάλογον ἐφορωμένων',
'watchlistedit-raw-done'      => 'Ἡ ἐφοροδιαλογή σου ἐνήμερος ἐστίν.',
'watchlistedit-raw-added'     => '{{PLURAL:$1|1 δέλτος|$1 δέλτοι}} προσετέθησαν:',
'watchlistedit-raw-removed'   => '{{PLURAL:$1|1 δέλτος|$1 δέλτοι}} ἀφῃρέθησαν:',

# Watchlist editing tools
'watchlisttools-view' => 'Ὁρᾶν τὰς πρὸς ταῦτα μεταβολὰς',
'watchlisttools-edit' => 'Ὁρᾶν τε καὶ μεταγράφειν τὰ ἐφορωμένα',
'watchlisttools-raw'  => 'Μεταγράφειν τὸν πρωτογενῆ κατάλογον ἐφορωμένων',

# Core parser functions
'unknown_extension_tag' => 'Ἄγνωτον σήμαντρον ἐπεκτάσεων "$1"',

# Special:Version
'version'                          => 'Ἐπανόρθωμα', # Not used as normal message but as header for the special page itself
'version-extensions'               => 'Ἐγκατεστημέναι ἐπεκτάσεις',
'version-specialpages'             => 'Εἰδικαὶ δέλτοι',
'version-parserhooks'              => 'Ἀγγύλαι λεξιαναλυτικοῦ προγράμματος',
'version-variables'                => 'Μεταβληταί',
'version-other'                    => 'Ἄλλα',
'version-mediahandlers'            => 'Χειρισταὶ μέσων',
'version-hooks'                    => 'Ἀγγύλαι',
'version-extension-functions'      => 'Ἐνέργειαι ἐπεκτάσεων',
'version-parser-extensiontags'     => 'Σἠμαντρα ἐπεκτάσεων λεξιαναλυτικοῦ προγράμματος',
'version-parser-function-hooks'    => 'Ἀγγύλαι ἐνεργειῶν λεξιαναλυτικοῦ προγράμματος',
'version-skin-extension-functions' => 'Ἐνέργειαι ἐπεκτάσεων ἐπικαλύψεων',
'version-hook-name'                => 'Ὄνομα ἀγκύλης',
'version-hook-subscribedby'        => 'Ὑπογεγραφυῖα ὑπὸ',
'version-version'                  => 'Ἐκδοχή',
'version-license'                  => 'Ἄδεια',
'version-software'                 => 'Ἐγκατεστημένον λογισμικόν',
'version-software-product'         => 'Προϊόν',
'version-software-version'         => 'Ἐκδοχή',

# Special:FilePath
'filepath'        => 'Διαδρομὴ ἀρχείου',
'filepath-page'   => 'Ἀρχεῖον:',
'filepath-submit' => 'Διαδρομή',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Ζήτησις διπλότυπων ἀρχείων',
'fileduplicatesearch-summary'  => 'Ζητεῖν διπλότυπα ἀρχεῖα βάσει κερματιζομένων τιμῶν.

Εἰσάγαγε τὸ ὄνομα ἀρχείου ἄνευ τοῦ προθέματος "{{ns:image}}:".',
'fileduplicatesearch-legend'   => 'Ζήτησις διπλότυπου',
'fileduplicatesearch-filename' => 'Ὄνομα ἀρχείου:',
'fileduplicatesearch-submit'   => 'Ἀναζήτησις',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />Μέγεθος ἀρχείου: $3<br />MIME τύπος: $4',
'fileduplicatesearch-result-1' => 'Τὸ ἀρχεῖον "$1" οὐκ ἔχει ταυτοτικὴν διπλοτυπίαν.',
'fileduplicatesearch-result-n' => 'Τὸ ἀρχεῖον "$1" ἔχει {{PLURAL:$2|1 ταυτοτικὴν διπλοτυπίαν|$2 ταυτοτικὰς διπλοτυπίας}}.',

# Special:SpecialPages
'specialpages'                   => 'Εἰδικαὶ δέλτοι',
'specialpages-note'              => '----
* Κανονικαὶ εἰδικαὶ δέλτοι.
* <span class="mw-specialpagerestricted">Περιωρισμέναι εἰδικαὶ δἐλτοι.</span>',
'specialpages-group-maintenance' => 'Ἀναφοραὶ συντηρήσεως',
'specialpages-group-other'       => 'Ἕτεραι εἰδικαὶ δέλτοι',
'specialpages-group-login'       => 'Συνδεῖσθαι / ἐγγράφεσθαι',
'specialpages-group-changes'     => 'Πρόσφατοι ἀλλαγαὶ καὶ κατάλογοι',
'specialpages-group-media'       => 'Ἀναφοραὶ μέσων καὶ ἐπιφορτίσεις',
'specialpages-group-users'       => 'Χρώμενοι καὶ δικαιώματα',
'specialpages-group-highuse'     => 'Ὑψηλῆς χρήσεως δέλτοι',
'specialpages-group-pages'       => 'Κατάλογος δέλτων',
'specialpages-group-pagetools'   => 'Ἐργαλεῖα δέλτου',
'specialpages-group-wiki'        => 'Οὐικιδεδομένα καὶ στοιχεῖα',
'specialpages-group-redirects'   => 'Ἀναδιευθύνειν εἰδικὰς δέλτους',
'specialpages-group-spam'        => 'Ἐργαλεῖα κατὰ τῶν ἀνεπιθυμήτων διαγγελιῶν',

# Special:BlankPage
'blankpage'              => 'Κενὴ δέλτος',
'intentionallyblankpage' => 'Ἥδε ἡ δέλτος ἀφίεται ἐσκεμμένως κενὴ οὖσα χρήσιμος ὡς σημεῖον ἀναφορᾶς, κτλ.',

);
