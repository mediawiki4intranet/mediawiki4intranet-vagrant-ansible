<?php

$wgDBname = '{{dbname}}'; 
$wgDBuser = '{{dbname}}'; 
$wgDBpassword = '{{dbname}}';

$wgDBadminuser = $wgDBuser;
$wgDBadminpassword = $wgDBpassword;

$wgMainCacheType = CACHE_ACCEL; 

{% if debug is defined %}
$wgMainCacheType = CACHE_NONE;

#$wgResourceLoaderDebug = 1;
#$wgResourceLoaderMinifierStatementsOnOwnLine =1;
#$wgResourceLoaderValidateJS  = 1;
{% endif %}

$wgLocalInterwiki   = $wgSitename;

$wgParserCacheType = $wgMessageCacheType = $wgMainCacheType;
$wgGroupPermissions['*']['edit'] = false;
$wgGroupPermissions['*']['delete'] = false;                                                                    
$wgGroupPermissions['*']['undelete'] = false;                                                                  
$wgGroupPermissions['*']['createpage'] = false;                                                                
$wgGroupPermissions['*']['createtalk'] = false;                                                                
$wgGroupPermissions['*']['import'] = false;                                                                    
$wgGroupPermissions['*']['importupload'] = false;   

$wgNamespacesWithSubpages += array(
    NS_MAIN     => true,
    NS_PROJECT  => true,
    NS_TEMPLATE => true,
    NS_HELP     => true,
    NS_CATEGORY => true,
);


error_reporting( -1 );

$first_level_exceptions = array('CategoryTree' => true);
function fullpath_for_ext($extdir)
{
 global $IP;
    $fulldir = realpath("$IP/extensions/$extdir");
    if (is_dir($fulldir) && (strlen($extdir)>2)){
            foreach (scandir($fulldir) as $file) {
                $fullpath = realpath("$IP/extensions/$extdir/$file");
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $basename = pathinfo($fullpath, PATHINFO_FILENAME); 
                if ((strtolower($basename) == strtolower($extdir)) && (strtolower($ext) == 'php')) {
                    return $fullpath;
                }
            }    
    }
    return False;
}

foreach ($first_level_exceptions as $extdir => $value) {
    $fp4ext = fullpath_for_ext($extdir);
    if ($fp4ext) {
        require_once $fp4ext;
    };
}

foreach (scandir(realpath("$IP/extensions")) as $extdir) {
    $fp4ext = fullpath_for_ext($extdir);
    if ($fp4ext) {
        require_once $fp4ext;
    };
}

{% if intraacl is defined %}
# IntraACL
require_once('extensions/IntraACL/includes/HACL_Initialize.php');
enableIntraACL();
$haclgInclusionDeniedMessage = '';
$haclgEnableTitleCheck = true;
$wgImgAuthPublicTest = false;
{% endif %}


if (class_exists('MediawikiQuizzer')){
  MediawikiQuizzer::setupNamespace(104);
  # MWQuizzer
  $egMWQuizzerIntraACLAdminGroup = 'Group/QuizAdmin';
$wgNamespacesWithSubpages += array(
    NS_QUIZ     => true,
    NS_QUIZ_TALK => true,
);

/****************  BLOGS *************************************/
if (class_exists('Wikilog')){

Wikilog::setupBlogNamespace(100);
$wgWikilogPagerDateFormat = 'ymd hms';
$wgNamespacesToBeSearchedDefault[NS_BLOG] = 1;
$wgWikilogMaxCommentSize = 0x7FFFFFFF;
$wgWikilogDefaultNotCategory = 'Скрытые';
$wgWikilogSearchDropdowns = true;
$wgWikilogCommentsOnItemPage = true;
$wgWikilogNumComments = 100;
$wgWikilogExpensiveLimit = 100;
# Enable Wikilog-style threaded talks pages everywhere
$wgWikilogCommentNamespaces = true;
}
};


# Namespaces with subpages
$wgNamespacesWithSubpages += array(
    NS_MAIN     => true,
    NS_PROJECT  => true,
    NS_TEMPLATE => true,
    NS_HELP     => true,
    NS_CATEGORY => true,
    NS_QUIZ     => true,
    NS_QUIZ_TALK => true,
);
/****************  ----- *************************************/

$wgDefaultSkin = 'monobook';
$wgUsePathInfo = true;
$wgScriptPath = '';
$wgArticlePath = '/$1';
$wgUploadDirectory = "{{imagesdir}}";
$wgUploadPath =  $wgScriptPath . "/img_auth.php";

$wgEnableUploads = true;
$wgGroupPermissions['autoconfirmed']['upload'] = true;
$wgGroupPermissions['autoconfirmed']['reupload'] = true;
$wgGroupPermissions['user']['upload'] = true;
$wgGroupPermissions['user']['delete'] = true;
$wgGroupPermissions['user']['undelete'] = true;
$wgGroupPermissions['user']['movefile'] = true;
$wgGroupPermissions['user']['upload_by_url'] = true;
$wgGroupPermissions['user']['import'] = true;
$wgGroupPermissions['user']['importupload'] = true;
$wgGroupPermissions['user']['suppressredirect'] = true;
$wgGroupPermissions['sysop']['deletebatch'] = true;


$wgEmergencyContact = "stas-fomin@yandex.ru";
$wgPasswordSender = "stas-fomin@yandex.ru";


$wgLanguageCode = 'en';

$wgSphinxQL_index = '{{dbname}}';
$wgSphinxQL_port = '/var/run/sphinx/searchd.sock';

$wgLogo = $wgUploadPath . "/6/6f/ThisWikiLogo.png";

$wgRawHtml = true;
$wgAllowUserJs = true;
$wgUseAjax = true;

$wgFileExtensions = array(
    'png', 'gif', 'jpg', 'jpeg', 'svg',
    'zip', 'rar', '7z', 'gz', 'bz2', 'xpi',
    'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'xls', 'xlsx', 'vsd',
    'djvu', 'pdf', 'xml', 'mm'
);

// Allow URL uploads
$wgAllowCopyUploads = true;
$wgCopyUploadsFromSpecialUpload = true;
$wgStrictFileExtensions = false;

{% if debug is defined %}
$wgDebugLogFile = "/tmp/wiki-debug-{$wgDBname}.log";
ini_set( 'display_errors', 1 );
{% endif %}

$wgMaxShellMemory = 1024*1024;

$wgNamespacesToBeSearchedDefault = array(
    NS_MAIN => 1,
    NS_FILE => 1,
    NS_CATEGORY => 1,
);

$wgUseCommaCount = true;

wfLoadSkin( 'monobook' );
wfLoadSkin( 'vector' );

$wgCategorySubcategorizedList = False;

$wgPFEnableStringFunctions = true;


$env_path=getenv('PATH');
if (!$env_path){
    putenv('PATH={$env_path}:/usr/bin:/usr/local/bin');
}

$wgNoCategoryColumns = True;
$wgMinUncatPagesList = 10000;
