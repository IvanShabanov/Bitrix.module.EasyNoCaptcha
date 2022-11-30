<?php
if (file_exists(__DIR__ . "/install/module.cfg.php")) {
	include(__DIR__ . "/install/module.cfg.php");
};

CModule::IncludeModule($arModuleCfg['MODULE_ID']);
global $DBType;

$arClasses=array(
	/* Библиотеки и слассы для авто загрузки */
	/*
	'IS_PRO\module_name\lib'=>'lib/lib.php',
    'IS_PRO\module_name\cMain_module_name'=>'classes/general/cMain_module_name.php'
	*/
);

CModule::AddAutoloadClasses($arModuleCfg['MODULE_ID'], $arClasses);
