<?php
if (file_exists(__DIR__ . "/install/module.cfg.php")) {
	include(__DIR__ . "/install/module.cfg.php");
};

use Bitrix\Main\Loader;

$arClasses=array(
	/* Библиотеки и слассы для авто загрузки */
	'IS_PRO\EasyNoCaptcha\EasyNoCaptcha'=>'lib/Easynocaptcha.php',
	'IS_PRO\EasyNoCaptcha\Events'=>'lib/Events.php',
	'IS_PRO\EasyNoCaptcha\Common'=>'lib/Common.php',
);

Loader::registerAutoLoadClasses($arModuleCfg['MODULE_ID'], $arClasses);

IS_PRO\EasyNoCaptcha\Events::Setup();