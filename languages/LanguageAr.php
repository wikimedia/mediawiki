<?php
/** Arabic (العربية)
  *
  * @package MediaWiki
  * @subpackage Language
  */

/** This is an UTF-8 language  */
require_once('LanguageUtf8.php');

/* private */ $wgNamespaceNamesAr = array(
	NS_MEDIA            => 'ملف',
	NS_SPECIAL          => 'خاص',
	NS_MAIN             => '',
	NS_TALK             => 'نقاش',
	NS_USER             => 'مستخدم',
	NS_USER_TALK        => 'نقاش_المستخدم',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => 'نقاش' . '_' . $wgMetaNamespace,
	NS_IMAGE            => 'صورة',
	NS_IMAGE_TALK       => 'نقاش_الصورة',
	NS_MEDIAWIKI        => 'ميدياويكي',
	NS_MEDIAWIKI_TALK   => 'نقاش_ميدياويكي',
	NS_TEMPLATE         => 'قالب',
	NS_TEMPLATE_TALK    => 'نقاش_قالب',
	NS_HELP             => 'مساعدة',
	NS_HELP_TALK        => 'نقاش_المساعدة',
	NS_CATEGORY         => 'تصنيف',
	NS_CATEGORY_TALK    => 'نقاش_التصنيف'
) + $wgNamespaceNamesEn;


/* private */ $wgMagicWordsAr = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#REDIRECT'    ,   '#تحويل'                  ),
	'notoc'                  => array( 0,    '__NOTOC__'   ,   '__لافهرس__'               ),
	'forcetoc'               => array( 0,    '__FORCETOC__'    ,   '__لصق_فهرس__'        ),
	'toc'                    => array( 0,    '__TOC__'     ,   '__فهرس__'                ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__' ,   '__لاتحريرقسم__'      ),
	'start'                  => array( 0,    '__START__'   ,   '__ابدأ__'                ),
	'currentmonth'           => array( 1,    'CURRENTMONTH'     ,    'شهر' , 'شهر_حالي'   ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME'    ,   'اسم_شهر', 'اسم_شهر_حالي'),
#	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN'    ),
#	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV'     ),
	'currentday'             => array( 1,    'CURRENTDAY'    ,          'يوم'            ),
#	'currentday2'            => array( 1,    'CURRENTDAY2'            ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME'   ,     'اسم_يوم'          ),
	'currentyear'            => array( 1,    'CURRENTYEAR'    ,    'عام'                 ),
	'currenttime'            => array( 1,    'CURRENTTIME'    ,   'وقت'                  ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES'  ,'عددالمقالات' , 'عدد_المقالات'),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES'  , 'عددالملفات' , 'عدد_الملفات'),
	'pagename'               => array( 1,    'PAGENAME'       ,       'اسم_صفحة'         ),
	'pagenamee'              => array( 1,    'PAGENAMEE'      ,         'عنوان_صفحة'     ),
	'namespace'              => array( 1,    'NAMESPACE'       ,      'نطاق'             ),
	'namespacee'             => array( 1,    'NAMESPACEE'     , 'عنوان_نطاق'             ),
	'fullpagename'           => array( 1,    'FULLPAGENAME', 'اسم_كامل'                  ),
	'fullpagenamee'          => array( 1,    'FULLPAGENAMEE'  , 'عنوان_كامل'             ),
	'msg'                    => array( 0,    'MSG:'         ,          'رسالة:'          ),
	'subst'                  => array( 0,    'SUBST:'      ,     'نسخ:'  , 'نسخ_قالب:'   ),
	'msgnw'                  => array( 0,    'MSGNW:'     ,  'مصدر:' , 'مصدر_قالب:'      ),
	'end'                    => array( 0,    '__END__'       ,  '__نهاية__', '__إنهاء__'   ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb'   ,          'تصغير'    ),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1'  ,'تصغير=$1'      ),
	'img_right'              => array( 1,    'right'       ,       'يمين'                  ),
	'img_left'               => array( 1,    'left'           ,    'يسار'                ),
	'img_none'               => array( 1,    'none'         ,        'بدون'              ),
	'img_width'              => array( 1,    '$1px'  ,    '$1بك'                         ),
	'img_center'             => array( 1,    'center', 'centre'   ,           'وسط'      ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame' , 'إطار', 'اطار'),
	'int'                    => array( 0,    'INT:'        ,        'محتوى:'              ),
	'sitename'               => array( 1,    'SITENAME'    ,          'اسم_الموقع'        ),
	'ns'                     => array( 0,    'NS:'            ,              'نط:'       ),
	'localurl'               => array( 0,    'LOCALURL:'      ,       'عنوان:'           ),
#	'localurle'              => array( 0,    'LOCALURLE:'             ),
	'server'                 => array( 0,    'SERVER'          ,   'العنوان'             ),
	'servername'             => array( 0,    'SERVERNAME'      ,   'اسم_عنوان'           ),
	'scriptpath'             => array( 0,    'SCRIPTPATH'       ,      'مسار'            ),
#	'grammar'                => array( 0,    'GRAMMAR:'               ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__',  'لاتحويل_عنوان'),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', 'لاتحويل_محتوى' ),
	'currentweek'            => array( 1,    'CURRENTWEEK'    ,     'أسبوع'              ),
	'currentdow'             => array( 1,    'CURRENTDOW'      ,     'رقم_يوم'           ),
	'revisionid'             => array( 1,    'REVISIONID'        ,     'نسخة'            ),
#	'plural'                 => array( 0,    'PLURAL:'                ),
	'fullurl'                => array( 0,    'FULLURL:', 'عنوان_كامل:'                   ),
#	'fullurle'               => array( 0,    'FULLURLE:'              ),
#	'lcfirst'                => array( 0,    'LCFIRST:'               ),
#	'ucfirst'                => array( 0,    'UCFIRST:'               ),
#	'lc'                     => array( 0,    'LC:'                    ),
#	'uc'                     => array( 0,    'UC:'                    ),
#	'raw'                    => array( 0,    'RAW:'                   ),
);


if (!$wgCachedMessageArrays) {
	require_once('MessagesAr.php');
}

class LanguageAr extends LanguageUtf8 {
	function digitTransformTable() {
		return array(
			'0' => '٠',
			'1' => '١',
			'2' => '٢',
			'3' => '٣',
			'4' => '٤',
			'5' => '٥',
			'6' => '٦',
			'7' => '٧',
			'8' => '٨',
			'9' => '٩',
			'.' => '٫', // wrong table?
			',' => '٬'
		);
	}

	function getNamespaces() {
		global $wgNamespaceNamesAr;
		return $wgNamespaceNamesAr;
	}

	function getMonthAbbreviation( $key ) {
		/* No abbreviations in Arabic */
		return $this->getMonthName( $key );
	}

	function isRTL() {
		return true;
	}

	function linkPrefixExtension() {
		return true;
	}

	function getDefaultUserOptions() {
		$opt = parent::getDefaultUserOptions();

		# Swap sidebar to right side by default
		$opt['quickbar'] = 2;

		# Underlines seriously harm legibility. Force off:
		$opt['underline'] = 0;
		return $opt ;
	}

	function fallback8bitEncoding() {
		return 'windows-1256';
	}

	function &getMagicWords()  {
		global $wgMagicWordsAr;
		return $wgMagicWordsAr;
	}

	function getMessage( $key ) {
		global $wgAllMessagesAr;
		if( isset( $wgAllMessagesAr[$key] ) ) {
			return $wgAllMessagesAr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}
?>
