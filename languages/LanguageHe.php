<?

include_once("Utf8Case.php");

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesHe = array(
	-1	=> "מיוחד",
	0	=> "",
	1	=> "שיחה",
	2	=> "משתמש",
	3	=> "שיחת_משתמש",
	4	=> "ויקיפדיה",
	5	=> "שיחת_ויקיפדיה",
	6	=> "תמונה",
	7	=> "שיחת_תמונה"
);

/* private */ $wgDefaultUserOptionsHe = array(
	"quickbar" => 1, "underline" => 1, "hover" => 1,
	"cols" => 80, "rows" => 25, "searchlimit" => 20,
	"contextlines" => 5, "contextchars" => 50,
	"skin" => 0, "math" => 1, "rcdays" => 7, "rclimit" => 50,
	"highlightbroken" => 1, "stubthreshold" => 0
);

/* private */ $wgQuickbarSettingsHe = array(
	"ללא", "קבוע משמאל", "קבוע מימין", "צף משמאל"
);

/* private */ $wgSkinNamesHe = array(
	"רגיל", "נוסטלגי", "מים כחולים"
);

/* private */ $wgMathNamesHe = array(
	"תמיד PNG צור",
	"PNG אם פשוט, אחרת HTML",
	"PNG אם אפשר, אחרת HTML",
	"(עבור דפדפני טקסט) TeX-השאר כ"
);

/* private */ $wgUserTogglesHe = array(
	"hover"		=> "הצג תיבה מרחפת מעל קישורי ויקי",
	"underline" => "סמן קישורים בקו תחתי",
	"highlightbroken" => "הדגש קישורים לנושאים שטרם נכתבו",
	"justify"	=> "ישר פסקאות",
	"hideminor" => "הסתר שינויים קטנים ברשימת השינויים האחרונים",
	"usenewrc" => "(רשימת שינויים אחרונים משופרת (לא מתאים לכל דפדפן",
	"numberheadings" => "מספר ראשי-פרקים אוטומטית",
	"rememberpassword" => "זכור את הסיסמא שלי בפעמים הבאות",
	"editwidth" => "תיבת העריכה ברוחב מלא",
	"editondblclick" => "(ערוך דפים בלחיצה כפולה (ג'אווה סקריפט",
	"watchdefault" => "עקוב אחרי מאמרים שערכתי או יצרתי",
	"minordefault" => "הגדר כל פעולת עריכה כמשנית אם לא צויין אחרת"
	
);

/* private */ $wgBookstoreListHe = array(
	"מיתוס" => "http://www.mitos.co.il/ ",
	"ibooks" => "http://www.ibooks.co.il/",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* private */ $wgLanguageNamesHe = array(
	"aa"    => "Afar",
	"ab"    => "Abkhazian",
	"af"	=> "Afrikaans - אפריקנס",
	"am"	=> "Amharic - אמהרית",
	"ar" => "&#8238;&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;&#8236;  - ערבית",
	"as"	=> "Assamese",
	"ay"	=> "Aymara",
	"az"	=> "Azerbaijani - אזרית",
	"ba"	=> "Bashkir",
	"be" => "&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1080;",
	"bh"	=> "Bihara",
	"bi"	=> "Bislama",
	"bn"	=> "Bengali",
	"bo"	=> "Tibetan",
	"br" => "Brezhoneg",

	"ca" => "Catal&#224; - קטאלאן",
	"ch" => "Chamoru",
	"co"	=> "Corsican",
	"cs" => "&#268;esk&#225; - צ'כית",
	"cy" => "Cymraeg",
	"da" => "Dansk - דנית", # Note two different subdomains.
	"dk" => "Dansk - דנית", # 'da' is correct for the language.
	"de" => "Deutsch - גרמנית",
	"dz"	=> "Bhutani",
	"el" => "&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940; - יוונית",
	"en"	=> "English - אנגלית",
	"eo"	=> "Esperanto - אספרנטו",
	"es" => "Espa&#241;ol - ספרדית",
	"et" => "Eesti",
	"eu" => "Euskara",
	"fa" => "&#8238;&#1601;&#1585;&#1587;&#1609;&#8236; - פרסית",
	"fi" => "Suomi - פינית",
	"fj"	=> "Fijian",
	"fo"	=> "Faeroese",
	"fr" => "Fran&#231;ais - צרפתית",
	"fy" => "Frysk",
	"ga" => "Gaelige",
	"gl"	=> "Galician",
	"gn"	=> "Guarani",
	"gu" => "&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752; (Gujarati)",
	"ha"	=> "Hausa",
	"he" => "&#1506;&#1489;&#1512;&#1497;&#1514; (Ivrit)",
	"hi" => "&#2361;&#2367;&#2344;&#2381;&#2342;&#2368; - הינדי",
	"hr" => "Hrvatski",
	"hu" => "Magyar - הונגרית",
	"hy"	=> "Armenian - ארמנית",
	"ia"	=> "Interlingua",
	"id"	=> "Indonesia",
	"ik"	=> "Inupiak",
	"is" => "&#205;slenska",
	"it" => "Italiano - איטלקית",
	"iu"	=> "Inuktitut",
	"ja" => "&#26085;&#26412;&#35486; (Nihongo) - יפנית",
	"jv"	=> "Javanese",
	"ka" => "&#4325;&#4304;&#4320;&#4311;&#4309;&#4308;&#4314;&#4312; (Kartuli)",
	"kk"	=> "Kazakh",
	"kl"	=> "Greenlandic",
	"km"	=> "Cambodian",
	"kn"	=> "Kannada",
	"ko" => "&#54620;&#44397;&#50612; (Hangukeo) - קוריאנית",
	"ks"	=> "Kashmiri",
	"kw" => "Kernewek",
	"ky"	=> "Kirghiz",
	"la" => "Latina",
	"ln"	=> "Lingala",
	"lo"	=> "Laotian",
	"lt" => "Lietuvi&#371;",
	"lv"	=> "Latvian",
	"mg" => "Malagasy",
	"mi"	=> "Maori",
	"mk"	=> "Macedonian",
	"ml"	=> "Malayalam",
	"mn"	=> "Mongolian",
	"mo"	=> "Moldavian",
	"mr"	=> "Marathi",
	"ms" => "Bahasa Melayu",
	"my"	=> "Burmese",
	"na"	=> "Nauru",
	"ne" => "&#2344;&#2375;&#2346;&#2366;&#2354;&#2368; - נפלית",
	"nl" => "Nederlands - הולנדית",
	"no" => "Norsk - נורבגית",
	"oc"	=> "Occitan",
	"om"	=> "Oromo",
	"or"	=> "Oriya",
	"pa"	=> "Punjabi",
	"pl" => "Polski - פולנית",
	"ps"	=> "Pashto",
	"pt" => "Portugu&#234;s - פורטוגלית",
	"qu"	=> "Quechua",
	"rm"	=> "Rhaeto-Romance",
	"rn"	=> "Kirundi",
	"ro" => "Rom&#226;n&#259; - רומנית",
	"ru" => "&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081; - רוסית",
	"rw"	=> "Kinyarwanda",
	"sa" => "&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340; (Samskrta)",
	"sd"	=> "Sindhi",
	"sg"	=> "Sangro",
	"sh"	=> "Serbocroatian",
	"si"	=> "Sinhalese",
	"simple" => "Simple English - אנגלית פשוטה",
	"sk"	=> "Slovak",
	"sl"	=> "Slovensko",
	"sm"	=> "Samoan",
	"sn"	=> "Shona",
	"so" => "Soomaali",
	"sq" => "Shqiptare",
	"sr" => "Srpski",
	"ss"	=> "Siswati",
	"st"	=> "Sesotho",
	"su"	=> "Sudanese",
	"sv" => "Svenska",
	"sw" => "Kiswahili",
	"ta"	=> "Tamil",
	"te"	=> "Telugu",
	"tg"	=> "Tajik",
	"th"	=> "Thai",
	"ti"	=> "Tigrinya",
	"tk"	=> "Turkmen",
	"tl"	=> "Tagalog",
	"tn"	=> "Setswana",
	"to"	=> "Tonga",
	"tr" => "T&#252;rk&#231;e - תורכית",
	"ts"	=> "Tsonga",
	"tt"	=> "Tatar",
	"tw"	=> "Twi",
	"ug"	=> "Uighur",
	"uk" => "&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072; - אוקראינית",
	"ur"	=> "Urdu",
	"uz"	=> "Uzbek",
	"vi"	=> "Vietnamese",
	"vo" => "Volap&#252;k",
	"wo"	=> "Wolof",
	"xh" => "isiXhosa",
	"yi"	=> "Yiddish - יידיש",
	"yo"	=> "Yoruba",
	"za"	=> "Zhuang",
	"zh" => "&#20013;&#25991; (Zhongwen) - סינית",
	"zu"	=> "Zulu"
);

/* private */ $wgWeekdayNamesHe = array(
	"ראשון", "שני", "שלישי", "רביעי", "חמישי",
	"שישי", "שבת"
);

/* private */ $wgMonthNamesHe = array(
	"ינואר", "פברואר", "מרץ", "אפריל", "מאי", "יוני",
	"יולי", "אוגוסט", "ספטמבר", "אוקטובר", "נובמבר",
	"דצמבר"
);

/* private */ $wgMonthAbbreviationsHe = array(
	"ינו", "פבר", "מרץ", "אפר", "מאי", "יונ", "יול", "אוג",
	"ספט", "אוק", "נוב", "דצמ"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesHe = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "קבע העדפות משתמש",
	"Watchlist"		=> "רשימת המעקב שלי",
	"Recentchanges" => "דפים שעודכנו לאחרונה",
	"Upload"		=> "העלה קבצי תמונה לשרת",
	"Imagelist"		=> "רשימת תמונות",
	"Listusers"		=> "משתמשים רשומים",
	"Statistics"	=> "סטטיסטיקות אתר",
	"Randompage"	=> "מאמר אקראי",

	"Lonelypages"	=> "מאמרים יתומים",
	"Unusedimages"	=> "תמונות יתומות",
	"Popularpages"	=> "מאמרים פופלריים",
	"Wantedpages"	=> "מאמרים מבוקשים ביותר",
	"Shortpages"	=> "מאמרים קצרים",
	"Longpages"		=> "מאמרים ארוכים",
	"Newpages"		=> "מאמרים חדשים",
	"Allpages"		=> "כל הדפים לפי כותרת",

	"Ipblocklist"	=> "חסומות IP כתובות",
	"Maintenance" => "דף תחזוקה",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "משאבי ספרות חיצוניים"
);

/* private */ $wgSysopSpecialPagesHe = array(
	"Blockip"		=> "IP חסום כתובת",
	"Asksql"		=> "שאילתא למסד-הנתונים",
	"Undelete"		=> "צפה ושחזר דפים מחוקים"
);

/* private */ $wgDeveloperSpecialPagesHe = array(
	"Lockdb"		=> "הפוך את בסיס הנתונים לקריא-בלבד",
	"Unlockdb"		=> "החזר יכולת לכתוב לבסיס הנתונים",
	"Debug"			=> "נתוני ניפוי שגיאות"
);

/* private */ $wgAllMessagesHe = array(

# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "עמוד ראשי",
"about"			=> "אודות",
"aboutwikipedia" => "אודות ויקיפדיה",
"aboutpage"		=> "ויקיפדיה:אודות",
"help"			=> "עזרה",
"helppage"		=> "ויקיפדיה:עזרה",
"wikititlesuffix" => "ויקיפדיה",
"bugreports"	=> "התראות על באגים",
"bugreportspage" => "ויקיפדיה:התראות_על_באגים",
"faq"			=> "שאלות ותשובות",
"faqpage"		=> "ויקיפדיה:שאלות_ותשובות",
"edithelp"		=> "עזרה לעריכה",
"edithelppage"	=> "ויקיפדיה:איך_עורכים_דף",
"cancel"		=> "בטל",
"qbfind"		=> "חיפוש",
"qbbrowse"		=> "דפדוף",
"qbedit"		=> "ערוך",
"qbpageoptions" => "אפשרויות דף",
"qbpageinfo"	=> "מידע על הדף",
"qbmyoptions"	=> "האפשרויות שלי",
"mypage"		=> "הדף שלי",
"mytalk"		=> "דף השיחה שלי",
"currentevents" => "חדשות",
"errorpagetitle" => "שגיאה",
"returnto"		=> ".$1 -חזור ל",
"fromwikipedia"	=> "מתוך ויקיפדיה, האנציקלופדיה החופשית.",
"whatlinkshere"	=> "דפים עם קישורים לכאן",
"help"			=> "עזרה",
"search"		=> "חיפוש",
"history"		=> "גרסאות קודמות",
"printableversion" => "גרסה להדפסה",
"editthispage"	=> "ערוך דף זה",
"deletethispage" => "מחק דף זה",
"protectthispage" => "הפוך דף זה למוגן",
"unprotectthispage" => "הסר הגנה מדף זה",
"talkpage"		=> "שוחח על דף זה",
"articlepage"	=> "צפה במאמר",
"subjectpage"	=> "צפה בנושא", # For compatibility
"userpage" => "צפה בדף המשתמש",
"wikipediapage" => "צפה בדף המטא",
"imagepage" => 	"צפה בדף התמונה",
"otherlanguages" => "שפות אחרות",
"redirectedfrom" => "(הפנייה מ - $1)",
"lastmodified"	=> ".$1 דף זה שונה לאחרונה ב",
"viewcount"		=> "פעמים. $1 דף זה נצפה",
"gnunote" => "<a class=internal href='/wiki/GNU_FDL'>GNU FDL</a> - GNU דף זה מוגש תחת הרשיון לשימוש חופשי במסמכים של",
"printsubtitle" => "(http://he.wikipedia.org - מ)",
"protectedpage" => "דף מוגן",
"administrators" => "ויקיפדיה:אחראי_מערכת",
"sysoptitle"	=> "דרושה הרשאת מפעיל מערכת",
"sysoptext"		=> ". ראה $1.\"sysop\"בכדי לבצע את פעולה זו דרושה הרשאת 
",
"developertitle" => "דרושה הרשאת מפתח מערכת",
"developertext"	=> ". ראה $1.\"developer\"בכדי לבצע את פעולה זו דרושה הרשאת 
",

"nbytes"		=> "בתים $1",
"go"			=> "לך",
"ok"			=> "אישור",
"sitetitle"		=> "ויקיפדיה",
"sitesubtitle"	=> "האנציקלופדיה החופשית",
"retrievedfrom" => "\"$1\" נשלף מ",

# Main script and global functions
#
"nosuchaction"	=> "אין פעולה כזו",
"nosuchactiontext" => " תוכנת ויקיפדיה אינה מכירה את הפעולה המצויינת ב-URL.",
"nosuchspecialpage" => "אין דף מיוחד בשם זה",
"nospecialpagetext" => "ביקשת דף מיוחד שאינו מוכר למערכת ויקיפדיה. ",

# General errors
#
"error"			=> "שגיאה",
"databaseerror" => "שגיאת מסד-נתונים",
"dberrortext"	=> "ארעה שגיאת תחביר בשאילתא למסד-הנתונים. שגיאה זו יכולה להיות כתוצאה משאילתת חיפוש לא-חוקית (ראה 5$), או שהיא עלולה להעיד על באג בתוכנה.
השאילתה האחרונה שבוצעה למסד-הנתונים היתה: 
<blockquote><tt>$1</tt></blockquote>
\"<tt>$2</tt>\מהפונקציה ".
"\"<tt>$3: $4</tt>\"  החזיר את השגיאהMySQL",
"noconnect"		=> "לא הצליח $1נסיון ההתחברות למסד-הנתונים על ",
"nodb"			=> "$1לא ניתן לבחור את מסד-הנתונים ",
"readonly"		=> "מסד-הנתונים נעול",
"enterlockreason" => " הזן סיבה לנעילת מסד-הנתונים, וכלול הערכה לגבי מתי הנעילה תשוחרר.",
"readonlytext"	=> "מסד-נתונים זה של ויקיפדיה נעול ברגע זה להזנת נתונים ושיוניים. ככל הנראה מדובר בתחזוקה שותפת, שלאחריה הוא יחזור לפעולתו הרגילה.
מפעיל המערכת שנעל אותו סיפק את ההסבר הבא:
<p>$1",
"missingarticle" => "מסד-הנתונים לא מצא את הטקסט של דף שאותו הוא אמור היה למצוא, בשם \"$1\".
זוהי אינה שגיאת מסד-נתונים, אבל סביר להניח שמדובר בבאג בתוכנה.
אנא דווח זאת לאחראי מערכת, תוך שמירת פרטי ה-URL.",
"internalerror" => "שגיאה פנימית",
"filecopyerror" => "העתקת \"$1\" ל-\"$2\" לא הצליחה.",
"filerenameerror" => "שינוי השם של \"$1\" ל-\"$2\" לא הצליח.",
"filedeleteerror" => " לא הצליחה.\"$1\"מחיקת ",
"filenotfound"	=> " לא נמצא.\"$1\"הקובץ ",
"unexpected"	=> "\"$1\"=\"$2\"ערך לא צפוי: ",
"formerror"		=> "Error: could not submit form",	
"badarticleerror" => "לא ניתן לבצע פעולה זו בדף זה.",
"cannotdelete"	=> "מחיקת הדף או קובץ התמונה לא הצליחה. (יתכן שנמחקה כבר על-ידי מישהו אחר.)",
"badtitle"		=> "כותרת שגויה",
"badtitletext"	=> "כותרת הדף המבוקש היתה לא-חוקית, ריקה או קישור פנים ויקי, או פנים שפה שגוי. ",
"perfdisabled" => "שירות זה הופסק זמנית בזמן שעות העומס בכדי לא לפגוע בביצועי המערכת; חזרו בין השעות 02:00 ו-14:00(UTC) ונסו שנית. עמכם הסליחה! ",

# Login and logout pages
#
"logouttitle"	=> "יציאת משתמש",
"logouttext"	=> "יצאת עתה מהאתר. את/ה יכול להמשיך ולעשות שימוש בויקיפדיה בצורה אנונימית, או שתוכל/י לשוב ולהכנס לאתר שנית עם שם משתמש זהה או אחר. \n",

"welcomecreation" => "<h2>ברוך הבא, $1 !
</h2><p>חשבונך נפתח. אל תשכח להתאים את הגדרות המשתמש שלך.",
"loginpagetitle" => "כניסת משתמש",
"yourname"		=> "שם משתמש ",
"yourpassword"	=> "סיסמא",
"yourpasswordagain" => "הקש סיסמא שנית",
"newusersonly"	=> " (רק אם את/ה משתמש חדש)",
"remembermypassword" => "זכור את הסיסמא שלי מכניסה לכניסה.",
"loginproblem"	=> "<b>ארעה שגיאה בכניסה לאתר. </b><br>!נסה שנית",
"alreadyloggedin" => "<font color=red><b> משתמש $1, כבר ביצעת כניסה לאתר!</b></font><br>\n",

"areyounew"		=> "אם הנך משתמש/ת חדש/ה בויקיפדיה וברצונך לפתוח חשבון, מלא/י שם משתמש, ואז הקלד/י את הסיסמא המבוקשת בשני המקומות המתאימים.
הזנת כתובת דואר-אלקטרוני הינה אופציונלית; אם תאבד/י את סיסמתך תוכל/י לבקש שהיא תשלח לכתובת שהזנת..<br>\n",

"login"			=> "כניסה לחשבון",
"userlogin"		=> "כניסה לחשבון",
"logout"		=> "יציאה מהחשבון",
"userlogout"	=> "יציאה מהחשבון",
"createaccount"	=> "צור משתמש חדש",
"badretype"		=> "הסיסמאות שהזנת אינן מתאימות.",
"userexists"	=> "שם המשתמש שבחרת נמצא בשימוש. אנא בחר/י שם אחר. ",
"youremail"		=> "כתובת הדואר האלקטרוני שלך",
"yournick"		=> "בחר כינוי (לחתימות)",
"emailforlost"	=> "אם תשכח/י את סיסמתך, תוכל/י לבקש שסיסמא חדשה תשלח לך לכתובת הדואר האלקטרוני. ",
"loginerror"	=> "שגיאה בכניסה לאתר",
"noname"		=> "לא הזנת שם משתמש חוקי",
"loginsuccesstitle" => "הכניסה הצליחה",
"loginsuccess"	=> ".\"$1\"נכנסת לויקיפדיה בשם ",
"nosuchuser"	=> ".\"$1\"אין משתמש בשם 
וודא/י שהאיות נכון, או השתמש/י בטופס שלהלם ליצור חשבון משתמש חדש.",
"wrongpassword"	=> "הסיסמא שהקלדת שגויה. אנא נסה/י שנית.",
"mailmypassword" => "שלחו לי סיסמא חדשה",
"passwordremindertitle" => "תזכורת סיסמא מויקיפדיה",
"passwordremindertext" => "מישהו (ככה\"נ את/ה, מכתובת IP מספר $1) ביקש/ה שנשלח לך סיסמת ויקיפדיה חדשה.
הסיסמא עבור משתמש \"$2\" היא עתה \"$3\".
עליך להכנס לאתר ולשנות את סיסמתך בהקדם האפשרי.",
"noemail"		=> "לא רשומה כתובת דואר אלקטרוני עבור משתמש  \"$1\".",
"passwordsent"	=> "סיסמא חדשה נשלחה לכתובת הדואר האלקטרוני הרשומה עבור \"$1\".
אנא הכנס חזרה לאתר אחרי שתקבל אותה.",

# Edit pages
#
"summary"		=> "תקציר",
"minoredit"		=> "זהו שינוי משני",
"watchthis"		=> "עקוב אחרי דף זה",
"savearticle"	=> "שמור דף",
"preview"		=> "תצוגה מקדימה",
"showpreview"	=> "הראה תצוגה מקדימה",
"blockedtitle"	=> "המשתמש חסום",
"blockedtext"	=> "שם המשתמש או כתובת ה-IP שלך נחסמו על-ידי $1.
הסיבה שניתנה היא:<br>''$2''<p>אתה יכול ליצור קשר עם $1 או אחד מ[[ויקיפדיה|מנהלי_האתר]] כדי לדון בחסימה.",
"newarticle"	=> "(חדש)",
"newarticletext" => "שים את הטקסט עבור הדף החדש כאן.",
"noarticletext" => "(אין עדיין טקסט בדף זה)",
"updated"		=> "(מעודכן)",
"note"			=> "<strong>הערה:</strong> ",
"previewnote"	=> "זכור שזו רק תצוגה מקדימה, והדף עדיין לא נשמר!",
"previewconflict" => "תצוגה מקדימה זו מציגה כיצד יראה הטקסט בחלון העריכה העליון אם תבחר לשמור אותו.",
"editing"		=> "עורך את $1",
"editconflict"	=> "קונפליקט עריכה: $1",
"explainconflict" => "<div dir=RTL>מישהו אחר שינה את הדף מאז התחלת לערוך אותו.
חלון העריכה העליון מכיל את הטקסט בדף כפי שהוא עתה.
השינויים שלך מוצגים בחלון העריכה התחתון.
עליך למזג את שינוייך לתוך הטקסט הקיים.
<b>רק</b> הטקסט בחלון העריכה העליון ישמר כשתלחץ על \"שמור\".\n</div><p>",
"yourtext"		=> "הטקסט שלך",
"storedversion" => "גרסה שמורה",
"editingold"	=> "<strong>זהירות: את/ה עורך/ת גרסא לא עדכנית של הדף הזה.
אם תשמור/י, כל השינויים שנעשו מאז גרסא זו יאבדו. </strong>\n",
"yourdiff"		=> "הבדלים",
"copyrightwarning" => " אנא שים/י לב שכל תרומה לוויקיפידיה נחשבת כטקסט ששוחרר תחת הרשיון לשימוש חופשי במסמכים של גנו (ראה $1 לפרטים). אם אינך רוצה שעבודתך תחשף לעריכה חסרת-רחמים, והפצה בחסדי-כל, אל תשים/י אותה פה. כמו-כן, את/ה מבטיח/ה לנו כי את/ה כתבת את הטקסט הזה בעצמך, או העתקת אותו ממקור שאינו מוגן על-ידי זכויות יוצרים. 
<strong> אל תעשה שימוש בחומר המוגן בזכויות יוצרים ללא רשות! </strong>",


# History pages
#
"revhistory"	=> "היסטוריית שינויים",
"nohistory"		=> "אין היסטוריית שינויים עבור דף זה",
"revnotfound"	=> "גרסה זו לא נמצאה",
"revnotfoundtext" => " שהפנה אותך לדף זה.URL  הגרסה הישנה של דף זה לא נמצאה. אנא בדוק/י את ה-\n",
"loadhist"		=> "טוען את היסטוריית השינויים של הדף",
"currentrev"	=> "גרסה נוכחית",
"revisionasof"	=> "$1שינוי נכון ל-",
"cur"			=> "נוכ",
"next"			=> "הבא",
"last"			=> "אחרון",
"orig"			=> "מקור",
"histlegend"	=> "מקרא: (נוכ) = הבדלים עם הגרסה הנוכחית, (אחרון) = הבדלים עם הגרסה הקודמת, ק = שינוי קטן",

# Diffs
#
"difference"	=> "(הבדלים בין גרסאות)",
"loadingrev"	=> "טוען גרסה להצגת הבדלים",
"lineno"		=> " :$1 שורה ",
"editcurrent"	=> "ערוך גרסה נוכחית של הדף",

# Search results
#
"searchresults" => "תוצאות חיפוש",

"searchhelppage" => "ויקיפדיה:חיפוש",
"searchingwikipedia" => "מחפש בויקיפדיה",
"searchresulttext" => "למידע נוסף על חיפוש בויקיפדיה, ראה $1.",
"searchquery"	=> "For query \"$1\"",
"badquery"		=> "שגיאה בניסוח שאילתא. ",
"badquerytext"	=> " לא הצלחנו לבצע את השאילתא. ככל הנראה כיוון שניסית לחפש מילה בעלת פחות משלוש אותיות. חיפוש כזה עדיין אינו נתמך במערכת. יתכן גם ששגית בהקלדת השאילתא לדוגמה  \"fish and and scales\".
אנא נסה שאילתא אחרת.",
"matchtotals"	=> "The query \"$1\" matched $2 article titles
and the text of $3 articles.",
"titlematches"	=> "כותרות מאמרים תואמות",
"notitlematches" => "אין כותרות מאמרים תואמות",
"textmatches"	=> "מאמרים עם תוכן תואם",
"notextmatches"	=> "אין מאמרים עם תוכן תואם",
"prevn"			=> "$1 הקודם ",
"nextn"			=> "$1 הבא ",
"viewprevnext"	=> " ($1) ($2) ($3)צפה ב - .",
"showingresults" => ".#<b>$2</b>תוצאות ממספר <b>$1</b>מציג ",
"nonefound"		=> "<strong>Note</strong>: unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).",
"powersearch" => "חפש",
"powersearchtext" => "
Search in namespaces :<br>
$1<br>
$2 List redirects &nbsp; Search for $3 $9",


# Preferences page
#
"preferences"	=> "העדפות",
"prefsnologin" => "לא נרשמת באתר",
"prefsnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to set user preferences.",
"prefslogintext" => "You are logged in as \"$1\".
Your internal ID number is $2.",
"prefsreset"	=> "העדפותיך שוחזרו מהגיבוי. ",
"qbsettings"	=> "הגדרות סרגל מהיר", 
"changepassword" => "שנה סיסמא",
"skin"			=> "רקע",
"math"			=> "עיבוד נוסחאות מתמטיות",
"math_failure"		=> "עיבוד הנוסחה נכשל",
"math_unknown_error"	=> "שגיאה לא ידועה",
"math_unknown_function"	=> "פונקציה לא מוכרת ",
"math_lexing_error"	=> "lexing error",
"math_syntax_error"	=> "שגיאת תחביר",
"saveprefs"		=> "שמור העדפות",
"resetprefs"	=> "שחזר העדפות קודמות",
"oldpassword"	=> "סיסמא ישנה",
"newpassword"	=> "סיסמא חדשה",
"retypenew"		=> "הקלד סיסמא חדשה שנית",
"textboxsize"	=> "גודל תיבת טקסט",
"rows"			=> "שורות",
"columns"		=> "עמודות",
"searchresultshead" => "הגדרות תוצאות חיפוש",
"resultsperpage" => "תוצאות חיפוש בעמוד",
"contextlines"	=> "שורות מכל תוצאה להציג",
"contextchars"	=> "מספר תווי קונטקסט בשורה",
"stubthreshold" => "סף להצגת מאמרים קצרים (קצרמרים).",
"recentchangescount" => "מספר מאמרים להציג בשינויים האחרונים",
"savedprefs"	=> "העדפותיך נשמרו.",
"timezonetext"	=> "הפרש השעות בינך לבין השרת (UTC).",
"localtime"	=> "זמן מקומי",
"timezoneoffset" => "הפרש",
"emailflag"		=> "הסתר כתובת דואר-אלקטרוני ממשתמשים אחרים.",

# Recent changes
#
"changes" => "שינויים",
"recentchanges" => "השינויים האחרונים",
"recentchangestext" => "עקוב אחר השינויים האחרונים בויקיפדיה בדף זה.",
"rcloaderr"		=> "טוען שינויים אחרונים",
"rcnote"		=> "הימים האחרונים. <strong>$2</strong> השינויים האחרונים שבוצעו ב- <strong>$1</strong> להלן.",
"rcnotefrom"	=> "להלן השינויים שבוצעו החל מתאריך <b>$2</b> (ועד לתאריך <b>$1</b>).",
"rclistfrom"	=> "הצג שינויים חדשים החל מ-$1",
# "rclinks"		=> "הצג $1 שינויים אחרונים ב-$2 השעות האחרונות / $3 הימים האחרונים",
"rclinks"		=> "הצג $1 שינויים אחרונים ב-$2 הימים האחרונים.",
"rchide"		=> "ב-$4 טפסים; $1 שינויים קטנים; $2 מרחבי שמות משניים; $3 multiple edits.",
"diff"			=> "שינוי",
"hist"			=> "היסטוריה",
"hide"			=> "הסתר",
"show"			=> "הצג",
"tableform"		=> "טבלה",
"listform"		=> "רשימה",
"nchanges"		=> "$1 שינויים",
"minoreditletter" => "ק",
"newpageletter" => "ח",

# Upload
#
"upload"		=> "העלה קובץ לשרת",
"uploadbtn"		=> "העלה קובץ",
"uploadlink"	=> "העלה תמונות",
"reupload"		=> "העלה שנית",
"reuploaddesc"	=> "חזור לטופס העלאת קבצים לשרת.",
"uploadnologin" => "לא נכנסת לאתר",
"uploadnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to upload files.",
"uploadfile"	=> "העלה קובץ",
"uploaderror"	=> "שגיאה בהעלאת הקובץ",
"uploadtext"	=> "<strong>STOP!</strong> Before you upload here,
make sure to read and follow Wikipedia's <a href=\"" .
wfLocalUrlE( "Wikipedia:Image_use_policy" ) . "\">image use policy</a>.
<p>To view or search previously uploaded images,
go to the <a href=\"" . wfLocalUrlE( "Special:Imagelist" ) .
"\">list of uploaded images</a>.
Uploads and deletions are logged on the <a href=\"" .
wfLocalUrlE( "Wikipedia:Upload_log" ) . "\">upload log</a>.
<p>השתמש בטופס שלהלן להעלות קובץ תמונה חדש לשימוש במאמר שלך.
במרבית הדפדנים תראה כפתור \"Browse...\", שיפתח את חלון פתיחת הקבצים הסטנדרטי של מערכת ההפעלה שלך.
בחירת קובץ תציג את שמו בשדה הטקסט שליד הכפתור.
עליך גם לסמן את התיבה בה אתה מצהיר שאינך מפר זכויות יוצרים בהעלתך את הקובץ לשרת.
לחץ על כפתור \"העלה קובץ\" כדי לסיים את ההעלאה.
התהליך עלול לקחת זמן מה אם אתה גולש בקישור אינטרנט איטי.
<p>הפורמט המועדף הוא JPEG לתצלומים, PNG לאיורים, שרטוטים וסמלים, ו-OGG לקבצי קול.

אנא תן לקובץ שם המייצג היטב את תוכנו כדי למנוע בלבול.
כדי לכלול את התמונה במאמר, צור קישור מסוג 
<b>[[image:file.jpg]]</b> או <b>[[image:file.png|alt text]]</b>
או <b>[[media:file.ogg]]</b> לקבצי קול.
<p>שימו לב בבקשה שבדומה לדפי ויקיפדיה אחרים, אחרים רשאים לערוך או למחוק קבצים שהעלית לשרת אם הם/ן חושבים/ות שהדבר משרת את האנציקלופדיה. ושאת/ה עלול להחסם מלבצע העלאות קבצים אם תעשה פעולות לא חוקיות כנגד המערכת.",
"uploadlog"		=> "יומן העלאות קבצים",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "להלן רשימה של העלאות הקבצים האחרונות שבוצעו.
כל הזמנים לפי שעון השרת (UTC).
<ul>
</ul>
",
"filename"		=> "שם הקובץ",
"filedesc"		=> "תקציר",
"affirmation"	=> "אני מצהיר שבעל זכויות היוצרים על קובץ זה מתיר להפיץ אותו תחת תנאי $1.",
"copyrightpage" => "ויקיפדיה:זכויות_יוצרים",
"copyrightpagename" => "זכויות היוצרים של ויקיפדיה",
"uploadedfiles"	=> "קבצים שהועלו",
"noaffirmation" => "עליך להבטיח שהעלאת הקובץ אינה מפירה זכויות יוצרים.",
"ignorewarning"	=> "התעלם מהאזהרה ושמור את הקובץ בכל זאת.",
"minlength"		=> "שמות של קובצי תמונה צריכים להיות בני שלושה תווים לפחות.",
"badfilename"	=> "שם התמונה שונה ל- \"$1\".",
"badfiletype"	=> "\".$1\" אינו פורמט מומלץ לשמירת תמונות.",
"largefile"		=> "מומלץ שגודל התמונה לא יחרוג מ-100 קילוביט.",
"successfulupload" => "העלאת הקובץ הצליחה",
"fileuploaded"	=> "הקובץ \"$1\" הועלה לשרת בהצלחה.
אנא השתמש/י בקישור זה: ($2) כדי לעבור לדף התיאור והזן/י בו פרטים אודות הקובץ כדוגמת,
מהיכן הגיע, מתי נוצר ועל ידי מי, וכל פרט אחר שאתה יודע לגביו. תודה.",
"uploadwarning" => "אזהרת העלאת קבצים",
"savefile"		=> "שמור קובץ",
"uploadedimage" => "העליתי את הקובץ \"$1\"",

# Image list
#
"imagelist"		=> "רשימת תמונות",
"imagelisttext"	=> "Below is a list of $1 images sorted $2.",
"getimagelist"	=> "מביא את רשימת התמונות",
"ilshowmatch"	=> "הצג תמונות שבשמם יש",
"ilsubmit"		=> "חפש",
"showlast"		=> "Show last $1 images sorted $2.",
"all"			=> "הכל",
"byname"		=> "לפי שם",
"bydate"		=> "לפי תאריך",
"bysize"		=> "לפי גודל",
"imgdelete"		=> "מחק",
"imgdesc"		=> "תיאור",
"imglegend"		=> "מקרא: (תיאור) הצג/ערוך תיאור התמונה.",
"imghistory"	=> "היסטורית קובץ תמונה",
"revertimg"		=> "חזור",
"deleteimg"		=> "מחק",
"imghistlegend" => "מקרא (נוכ) = זו התמונה הנוכחית, (מחק) = מחק גרסה ישנה זו, (חזור) חזור לגרסה ישנה זו.
<br><i>הקש על תאריך לראות את התמונה שהועלתה בתאריך זה</i>.",
"imagelinks"	=> "קישורי תמונות",
"linkstoimage"	=> "הדפים הבאים מקושרים לתמונה זו:",
"nolinkstoimage" => "אין דפים המקושרים לתמונה זו",

# Statistics
#
"statistics"	=> "סטטיסטיקות",
"sitestats"		=> "סטטיסטיקות האתר",
"userstats"		=> "סטטיסטיקות משתמש",
"sitestatstext" => "בבסיס הנתונים יש <b>$1</b> דפים בסך הכל.
אלה כוללים דפי \"שיחה\", דפים על ויקיפדיה, \"קצרמר\"ים, הפניות, ודפים אחרים שלא ממש נחשבים כמאמר לכל דבר.
אם לא מחשבים את אלה, ישנם <b>$2</b> דפים שככל הנראה הינם מאמרים לכל דבר.<p>
מאז ששודרגה התוכנה (03/03) בוצעו <b>$3</b> צפיות בדפים, ו-<b>$4</b> עריכות.
כלומר בממוצע <b>$5</b> עריכות לדף, ו-<b>$6</b> צפיות לכל עריכה.",
"userstatstext" => "ישנם <b>$1</b> משתמשים רשומים.
<b>$2</b> מתוכם הם מנהלי מערכת (ראה $3).",

# Maintenance Page
#
"maintenance"		=> "דף תחזוקה",
"maintnancepagetext"	=> "דף זה מכיל מספר כלים שימושים לתחזוקה יומיומית. חלק מהפעולות הללו מעלות מאוד את העומס על בסיס הנתונים. אנו מבקשים שלא תרעננו את הדף לאחר כל תיקון שאתם מבצעים ;-)",
"maintenancebacklink"	=> "חזרה לדף התחזוקה",
"disambiguations"	=> "דפי רב-משמעות",
"disambiguationspage"	=> "Wikipedia:Links_to_disambiguating_pages",
"disambiguationstext"	=> "המאמרים שלהלן מצביעים אל <i>דפי רב-משמעות</i>. מה שהם אמורים לעשות, זה להפנות לדף הנושא הרלוונטי.<br>אנו מתייחסים לדף כרב-משמעות אם מצביע אליו $1.<br>קישורים המגיעים אל דף ממרחבי שם אחרים <i>אינם</i> מוצגים כאן.",
"doubleredirects"	=> "הפניות כפולות",
"doubleredirectstext"	=> "<b>שים לב:</b> רשימה זו עלולה לכלול דפים שנמצאו בטעות. זאת אומרת, שבדפים שנמצאו ישנו טקסט נוסף עם קישורים מתחת ל-#REDIRECT הראשון.<br>\nכל שורה מכילה קישור להפנייה הראשונה והשנייה, וכן את שורת הטקסט הראשונה של ההפניה השניה, שלרוב נמצא בה היעד האמיתי של ההפניה, אליו אמורה ההפניה הראשונה להצביע.",
"brokenredirects"	=> "הפניות מקולקלות",
"brokenredirectstext"	=> "ההפניות שלהלן מצביעות למאמרים שאינם קיימים.",
"selflinks"		=> "דפים המפנים לעצמם.",
"selflinkstext"		=> "הדפים שלהלן מצביעים על עצמם, הם לא אמורים לעשות זאת.",
"mispeelings"           => "דפים עם שגיאות כתיב",
"mispeelingstext"               => "בדפים הבאים קיימות שגיאות כתיב נפוצות, רשימה של שגיאות אלו נמצאת ב-$1. הכתיב הנכון עשוי להיות מוצג (כך).",
"mispeelingspage"       => "רשימת שגיאות כתיב נפוצות",
"missinglanguagelinks"  => "קישורים חסרים בין שפות",
"missinglanguagelinksbutton"    => "מצא קישורי שפה חסרים עבור",
"missinglanguagelinkstext"      => "מאמרים אלו <i>לא</i> מקושרים למאמרים הדומים להם ב-$1. הפניות, ותתי-דפים <i>אינם</i> מוצגים.",


# Miscellaneous special pages
#
"orphans"		=> "דפים יתומים",
"lonelypages"	=> "דפים יתומים",
"unusedimages"	=> "דפים לא משומשים",
"popularpages"	=> "דפים פופולריים",
"nviews"		=> "$1 צפיות",
"wantedpages"	=> "דפים מבוקשים",
"nlinks"		=> "$1 קישורים",
"allpages"		=> "כל הדפים",
"randompage"	=> "דף מקרי",
"shortpages"	=> "דפים קצרים",
"longpages"		=> "דפים ארוכים",
"listusers"		=> "רשימת משתמשים",
"specialpages"	=> "דפים מיוחדים",
"spheading"		=> "דפים מיוחדים",
"sysopspheading" => "דפים מיוחדים למפעילי מערכת.",
"developerspheading" => "דפים מיוחדים למפתחים",
"protectpage"	=> "הפוך דף למוגן",
"recentchangeslinked" => "שינויים בעלי הקשר דומה",
"rclsub"		=> "(לדפים המקושרים מ-\"$1\")",
"debug"			=> "Debug",
"newpages"		=> "דפים חדשים",
"movethispage"	=> "העבר דף זה",
"unusedimagestext" => "<p>שים לב בבקשה שאתרים אחרים כדוגמת
ויקיפדיות בינלאומיות אחרות עשויות לבצע קישור לתמונה באמצעות  הפניה ישירה לכתובתה, ולכן עלולות להופיע כאן תמונות שנמצאות בשימוש שוטף.",
"booksources"	=> "משאבי ספרות חיצוניים",
"booksourcetext" => "להלן רשימה של קישורים לאתרים אחרים המוכרים ספרים חדשים ויד-שניה, ושבהם עשוי להיות מידע נוסף לגבי ספרים שאת/ה מחפש/ת.
לויקיפדיה אין קשר לעסקים אלה, ואין לראות ברשימה זו המלצה, פרסום או עידוד לעשות שימוש באתרים אלו ספציפית.",

# Email this user

#
"mailnologin"	=> "אין כתובת לשליחה",
"mailnologintext" => "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
and have a valid e-mail address in your <a href=\"" .
  wfLocalUrl( "Special:Preferences" ) . "\">preferences</a>
to send e-mail to other users.",
"emailuser"		=> "של דואר-אלקטרוני למשתמש זה",
"emailpage"		=> "שלח דואר למשתמש",
"emailpagetext"	=> "אם המשתמש הזין כתובת דואר-אלקטרוני חוקית בהעדפותיו האישיות, הטופס שלהלן ישלח אליו הודעת דואר אחת. כתובת הדואר-האלקטרוני שהזנת בהעדפותיך האישיות תופיע בשדה ה-\"מאת\" של הדואר כדי שהמשתמש יוכל לענות.",
"noemailtitle"	=> "אין כתובת דואר-אלקטרוני",
"noemailtext"	=> "משתמש זה לא הזין כתובת דואר-אלקטרוני חוקית או בחר שלא לקבל דואר אלקטרוני ממשתמשים אחרים.",
"emailfrom"		=> "מאת",
"emailto"		=> "אל",
"emailsubject"	=> "נושא",
"emailmessage"	=> "הודעה",
"emailsend"		=> "שלח",
"emailsent"		=> "הדואר נשלח",
"emailsenttext" => "הודעת הדואר האלקטרוני שלך נשלחה.",

# Watchlist
#
"watchlist"		=> "רשימת המעקב שלי",
"watchlistsub"	=> "(עבור משתמש \"$1\")",
"nowatchlist"	=> "אין לך דפים ברשימת המעקב.",
"watchnologin"	=> "לא נכנסת לאתר",
"watchnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to modify your watchlist.",
"addedwatch"	=> "הדף הוסף לרשימת המעקב",
"addedwatchtext" => "The page \"$1\" has been added to your <a href=\"" .
  wfLocalUrl( "Special:Watchlist" ) . "\">watchlist</a>.
Future changes to this page and its associated Talk page will be listed there,
and the page will appear <b>bolded</b> in the <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">list of recent changes</a> to
make it easier to pick out.</p>

<p>אם ברצונך להסיר את הדף מרשימת המעקב, לחץ/י על \"הפסק לעקוב\" ברשימה שבצד הדף.",
"removedwatch"	=> "הדף הוסר מרשימת המעקב",
"removedwatchtext" => "הדף \"$1\" הוסר מרשימת המעקב שלך.",
"watchthispage"	=> "עקוב אחר דף זה",
"unwatchthispage" => "הפסק לעקוב",
"notanarticle"	=> "זהו אינו מאמר",

# Delete/protect/revert
#
"deletepage"	=> "מחק דף",
"confirm"		=> "אשר",
"confirmdelete" => "אשר מחיקת הדף",
"deletesub"		=> "(מוחק את \"$1\")",
"confirmdeletetext" => "אתה עומד למחוק דף או תמונה יחד עם כל ההיסטוריה שלה מבסיס הנתונים.
אנא אשר שזה מה שאתה מתכוון לעשות, שאתה מבין את התוצאות של מעשה כזה, ושאתה מבצע אותו בהתאם ל[[ויקיפדיה:נהלים]].",
"confirmcheck"	=> "כן. אני באמת רוצה למחוק את הדף הזה.",
"actioncomplete" => "הפעולה בוצעה",
"deletedtext"	=> "\"$1\" נמחק.
ראה $2 לרשימת המחיקות האחרונות.",
"deletedarticle" => "מחקתי את \"$1\"",
"dellogpage"	=> "יומן_מחיקות",
"dellogpagetext" => "להלן רשימה של המחיקות האחרונות שבוצעו.
כל הזמנים המוצגים עם לפי שעון השרת (UTC).

<ul>
</ul>
",
"deletionlog"	=> "יומן מחיקות",
"reverted"		=> "חוזר לגרסה קודמת",
"deletecomment"	=> "סיבת המחיקה",
"imagereverted" => "החזרה לגרסה קודמת הצליחה.",
"rollback"		=> "Roll back edits",
"rollbacklink"	=> "rollback",
"cantrollback"	=> "Can't revert edit; last contributor is only author of this article.",
"revertpage"	=> "Reverted to last edit by $1",

# Undelete
"undelete" => "שחזר דף מחוק",
"undeletepage" => "צפה ושחזר דפים מחוקים",
"undeletepagetext" => "הדפים שלהלן נמחקו אך עדיין בארכיון ואפשר לשחזר אותם. הארכיון מנוקה מעת לעת.",
"undeletearticle" => "שחזר מאמר מחוק",
"undeleterevisions" => "$1 גרסאות נשמרו בארכיון",
"undeletehistory" => "אם תשחזר את הדף, כל הגרסאות ישוחזרו בדף ההיסטוריה שלו.
אם כבר יש דף חדש באותו שם, הגרסאות והשינויים יופיעו בהיסטוריה קודמת, והגרסה הנוכחית לא תוחלף אוטומטית.",
"undeleterevision" => ".מחקתי גרסאות החל מ-$1",
"undeletebtn" => "שחזר!",
"undeletedarticle" => "\"$1\" שוחזר",
"undeletedtext"   => "המאמר [[$1]] שוחזר בהצלחה.
ראה את [[ויקיפדיה:יומן_מחיקות]] לרשימה של מחיקות ושיחזורים אחרונים.",

# Contributions
#
"contributions"	=> "תרומת המשתמש",
"contribsub"	=> "$1 עבור",
"nocontribs"	=> ".לא נמצאו שינויים המתאימים לקריריונים אלו",
"ucnote"		=> ".להלן <b>$1</b> השינויים האחרונים שביצע משתמש זה ב-<b>$2</b> הימים האחרונים",
"uclinks"		=> ".צפה ב-$1 השינויים האחרונים; צפה ב-$2 הימים האחרונים",
"uctop"		=> " (למעלה)" ,

# What links here
#
"whatlinkshere"	=> "דפים המצביעים לכאן",
"notargettitle" => "אין דף מטרה",
"notargettext"	=> ".לא ציינת דף מטרה או משתמש לגביו תבוצע פעולה זו",
"linklistsub"	=> "(רשימת קישורים)",
"linkshere"		=> ":הדפים שלהלן מצביעים לכאן",
"nolinkshere"	=> ".אין דפים המצביעים לכאן",
"isredirect"	=> "דף הפנייה",

# Block/unblock IP
#
"blockip"		=> "חסום כתובת IP",
"blockiptext"	=> ".השתמש בטופס שלהלן בכדי לחסום הרשאות כתיבה מכתובת IP ספציפית
פעולה זו צריכה להתבצע אך ורק בכדי למנוע ונדליזם, ובהתאם ל-[[ויקיפדיה:נהלים]]. פרט את הסיבה הספציפית למטה
.(לדוגמה - ציין דפים ספציפיים שהושחתו על ידי המשתמש)",
"ipaddress"		=> "IP כתובת",
"ipbreason"		=> "סיבה",
"ipbsubmit"		=> "חסום כתובת זו",
"badipaddress"	=> ".כתובת ה-IP אינה כתובה נכון",
"noblockreason" => ".עליך לציין סיבה לחסימה",
"blockipsuccesssub" => "החסימה הצליחה",
"blockipsuccesstext" => ".כתובת ה-IP \"$1\" נחסמה
<br>.ראה את [[מיוחד:כתובות_חסומות|רשימת הכתובות החסומות]] כדי לצפות בחסימות",
"unblockip"		=> "שחרר כתובת IP",
"unblockiptext"	=> ".השתמש בטופס שלהלן בכדי להחזיר הרשאות כתיבה לכתובת IP חסומה",
"ipusubmit"		=> "שחרר כתובת זו",
"ipusuccess"	=> ".כתובת IP \"$1\" שוחררה",
"ipblocklist"	=> "רשימת כתובת IP חסומות",
"blocklistline"	=> "$1, $2 חסם את $3",
"blocklink"		=> "חסום",
"unblocklink"	=> "שחרר חסימה",
"contribslink"	=> "תרומות המשתמש",

# Developer tools
#
"lockdb"		=> "נעל בסיס-נתונים",
"unlockdb"		=> "שחרר בסיס נתונים מנעילה",
"lockdbtext"	=> "נעילת בסיס הנתונים ימנע ממשתמשים את האפשרות לערוך דפים, לשנות את העדפותיהם, את רשימות המעקב שלהם, .ופעולות אחרות הדורשות ביצוע שינויים בבסיס הנתונים
אנא אשר/י שזה מה שאת/ה מתכוון/ת לעשות, ושתשחרר/י את בסיס הנתונים מנעילה כאשר פעולת התחזוקה תסתיים.",
"unlockdbtext"	=> "שחרור בסיס הנתונים מנעילה יחזיר למשתמשים את היכולת לערוך דפים, לשנות את העדפותיהם, לערוך את רשימות .המעקב שלהם, ולבצע פעולות אחרות הדורשות ביצוע שינויים בבסיס הנתונים
אנא אשר/י שזה מה שבכוונתך לעשות.",
"lockconfirm"	=> ".כן, אני באמת רוצה לנעול את בסיס הנתונים",
"unlockconfirm"	=> ".כן, אני באמת רוצה לשחרר את בסיס הנתונים מנעילה",
"lockbtn"		=> "נעל בסיס נתונים",
"unlockbtn"		=> "שחרר בסיס נתונים מנעילה",
"locknoconfirm" => ".לא סימנת את תיבת האישור",
"lockdbsuccesssub" => "נעילת בסיס הנתונים הצליחה",
"unlockdbsuccesssub" => "שוחררה הנעילה מבסיס הנתונים",
"lockdbsuccesstext" => ".בסיס הנתונים של ויקיפדיה ננעל
<br>.זכור לשחרר את הנעילה לאחר שפעולת התחזוקה הסתיימה",
"unlockdbsuccesstext" => "שוחררה הנעילה מבסיס הנתונים של ויקיפדיה",

# SQL query
#
"asksql"		=> "שאילתת SQL",
"asksqltext"	=> "השתמש/י בטופס שלהלן לביצוע שאילתא ישירה לבסיס הנתונים של ויקיפדיה.
עשה/י שימוש בפסיקים בודדים ('כך') כדי לתחום שרשראות תווים. (String literals).
פעולה זו יוצרת לעיתים קרובות עומס רק על השרת, אנא השתמשו באפשרות זו כמה שפחות.",
"sqlquery"		=> "הזן שאילתא",
"querybtn"		=> "בצע שאילתא",
"selectonly"	=> "שאילתאות שאינן \"SELECT\" אפשריות רק למפתחי ויקיפדיה.",
"querysuccessful" => "השאילתא הצליחה",

# Move page
#
"movepage"		=> "העבר דף",
"movepagetext"	=> "שימוש בטופס שלהלן ישנה את שמו של דף, ויעביר את כל ההיסטוריה שלו לשם חדש.
השם הישן יהפוך לדף הפניה אל הדף עם השם החדש.
קישורים לשם הישן לא ישונו; וודא/י לבצע [[מיוחד:תחזוקה|בדיקה]] שאין הפניות כפולות, או מקולקלות.
את/ה אחראי/ת לוודא שכל הקישורים ממשיכים להצביע למקום שאליו הם אמורים להצביע.

שים/י לב הדף '''לא''' יועבר אם כבר יש דף תחת השם החדש, אלא אם הדף הזה ריק, או שהוא הפנייה, ואין לו היסטוריה של שינויים. משמעות הדבר, שאפשר לשנות חזרה את שמו של דף לשם המקורי, אם נעשתה טעות, ולא ימחק דף קיים במערכת.

<b>אזהרה!</b>
שינויי זה עשוי להיות שינוי דרסטי ובלתי צפוי לדף פופולרי;
אנא וודא/י שאת/ה מבין/ה את השלכות המעשה לפני שאת/ה ממשיך/ה.",
"movepagetalktext" => "אם קיים לדף זה דף שיחה, הוא יועבר אוטומטית '''אלא אם:'''
*הדף מועבר ממרחב שם אחד לשני,
*קיים דף שיחה שאינו ריק תחת השם החדש אליו מועבר הדף, או
*הורדת את הסימון בהתיבה שלהלן.

במקרים אלה, תצטרך/י להעביר או לשלב את הדפים באופן ידני אם תרצה/י.",
"movearticle"	=> "העבר דף",
"movenologin"	=> "לא נכנסת לאתר",
"movenologintext" => "עליך להיות משתמש רשום ו<a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">בתוך האתר</a> כדי להזיז דף.",
"newtitle"		=> "לשם החדש",
"movepagebtn"	=> "העבר דף",
"pagemovedsub"	=> "ההעברה הצליחה",
"pagemovedtext" => "Page \"[[$1]]\" moved to \"[[$2]]\".",
"articleexists" => "קיים כבר דף עם אותו שם, או שהשם שבחרת אינו חוקי.
אנא  בחר/י שם אחר.",
"talkexists"	=> "הדף עצמו הועבר בהצלחה, אבל דף השיחה לא הועבר כיוון שקיים  כבר דף שיחה במיקום החדש. אנא מזג/י אותם ידנית.",
"movedto"		=> "הועבר ל",
"movetalk"		=> "העבר גם את דף \"שיחה\", אם רלוונטי.",
"talkpagemoved" => "דף השיחה המשוייך הועבר גם כן.",
"talkpagenotmoved" => "דף השיחה המשוייך <strong>לא</strong> הועבר.",

);

class LanguageHe extends LanguageUtf8 {

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsHe ;
		return $wgDefaultUserOptionsHe ;
	}

	function getBookstoreList () {
		global $wgBookstoreListHe ;
		return $wgBookstoreListHe ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesHe;
		return $wgNamespaceNamesHe;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesHe;
		return $wgNamespaceNamesHe[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesHe;

		foreach ( $wgNamespaceNamesHe as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsHe;
		return $wgQuickbarSettingsHe;
	}

	function getSkinNames() {
		global $wgSkinNamesHe;
		return $wgSkinNamesHe;
	}

	function getMathNames() {
		global $wgMathNamesHe;
		return $wgMathNamesHe;
	}

	function getUserToggles() {
		global $wgUserTogglesHe;
		return $wgUserTogglesHe;
	}


	function getMonthName( $key )
	{
		global $wgMonthNamesHe;
		return $wgMonthNamesHe[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsHe;
		return $wgMonthAbbreviationsHe[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesHe;
		return $wgWeekdayNamesHe[$key-1];
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesHe;
		return $wgValidSpecialPagesHe;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesHe;
		return $wgSysopSpecialPagesHe;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesHe;
		return $wgDeveloperSpecialPagesHe;
	}

        function getMessage( $key )
        {
                global $wgAllMessagesHe;
                if(array_key_exists($key, $wgAllMessagesHe))
                        return $wgAllMessagesHe[$key];
                else
                        return Language::getMessage($key);
        }

        function isRTL() { return true; }

        function fallback8bitEncoding() { return "iso8859-8"; }                

}

?>
