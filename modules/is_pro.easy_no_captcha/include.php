<?php
if (file_exists(__DIR__ . "/install/module.cfg.php")) {
	include(__DIR__ . "/install/module.cfg.php");
};

use Bitrix\Main\Loader;
//Loader::includeModule($arModuleCfg['MODULE_ID']);

$arClasses=array(
	/* Библиотеки и слассы для авто загрузки */
	'IS_PRO\EasyNoCaptcha\EasyNoCaptcha_v4'=>'lib/easynocaptcha_v4.php',
	'IS_PRO\EasyNoCaptcha\events'=>'lib/events.php',
);

Loader::registerAutoLoadClasses($arModuleCfg['MODULE_ID'], $arClasses);

IS_PRO\EasyNoCaptcha\events::Setup();