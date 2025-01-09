<?php
set_time_limit(0);
error_reporting(0);
@ini_set('display_errors', 0);

// statistics off
@define('STOP_STATISTIC', true);
@define('NO_KEEP_STATISTIC', true);
@define('NO_AGENT_STATISTIC', 'Y');

// perfmon stop
@define('PERFMON_STOP', true);

// check permissions off
@define('NOT_CHECK_PERMISSIONS', true);

// dont show errors
@define('PUBLIC_AJAX_MODE', true);

// dont run agents
@define('NO_AGENT_CHECK', true);

$server = $_SERVER;
$host     = isset($server['HTTP_X_FORWARDED_HOST']) ? $server['HTTP_X_FORWARDED_HOST']
			: (isset($server['HTTP_HOST']) ? $server['HTTP_HOST'] : null);
[$host, $port] = explode(':', $host);

if (empty($host) || (mb_strpos($_SERVER['HTTP_REFERER'], $host) === false)) {
	die;
};

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
global $APPLICATION, $DB, $USER;
global $arLog;
$APPLICATION->RestartBuffer();
$app = \Bitrix\Main\Application::getInstance();
$response = $app->getContext()->getResponse();
$responseHeadersHandler = $response->getHeaders();
$responseHeadersHandler->set('Content-Type', 'text/javascript; charset=UTF-8');

$buffer = "";

if (\Bitrix\Main\Loader::includeModule("is_pro.easy_no_captcha")) {
	$buffer = \IS_PRO\EasyNoCaptcha\Common::getScript(['ReturnPureJS' => true]);
}

$response->flush($buffer);
$app->terminate();