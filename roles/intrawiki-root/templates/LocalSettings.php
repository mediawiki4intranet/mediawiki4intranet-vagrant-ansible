{% include "templates/LocalSettings.j2.php" %}

require_once "$IP/extensions/ConfirmEdit/QuestyCaptcha.php";
$wgCaptchaClass = 'QuestyCaptcha';
$arr = array (
        'Mediawiki*Intranet' => 'Mediawiki4Intranet',
);
foreach ( $arr as $key => $value ) {
        $wgCaptchaQuestions[] = array( 'question' => $key, 'answer' => $value );
}



$wgSitename = "IntraWiki";
#$wgGoogleAnalyticsAccount = "UA-1033555-55"; 
$egSubpagelistAjaxDisableRE = '#^Blog:[^/]*$#s';

unset( $wgFooterIcons['poweredby'] );
$wgRightsIcon = null;

// Enable by default for everybody
$wgDefaultUserOptions['visualeditor-enable'] = 1;

// Don't allow users to disable it
$wgHiddenPrefs[] = 'visualeditor-enable';

$wgVirtualRestConfig['modules']['parsoid'] = array(
  'url' => 'http://localhost:8000',
  'domain' => 'wiki',
);

$wgSessionsInObjectCache = true;
$wgVirtualRestConfig['modules']['parsoid']['forwardCookies'] = true;
