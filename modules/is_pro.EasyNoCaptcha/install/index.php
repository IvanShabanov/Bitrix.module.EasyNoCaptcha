<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
Loc::loadMessages(__FILE__);

Class is_pro_easy_no_captcha extends CModule
{
	public function __construct()
	{
		if(file_exists(__DIR__."/module.cfg.php")){
			include(__DIR__."/module.cfg.php");
		}
		if(file_exists(__DIR__."/version.php")){
			$arModuleVersion = array();
			include(__DIR__."/version.php");
			$this->MODULE_ID 		   = $arModuleCfg['MODULE_ID'];
			$this->MODULE_VERSION  	   = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
			$this->MODULE_NAME 		   = Loc::getMessage("ISPRO_module_name_NAME");
			$this->MODULE_DESCRIPTION  = Loc::getMessage("ISPRO_module_name_DESC");
			$this->PARTNER_NAME 	   = Loc::getMessage("ISPRO_module_name_PARTNER_NAME");
			$this->PARTNER_URI  	   = Loc::getMessage("ISPRO_module_name_PARTNER_URI");
		}
		return false;
	}


	public function DoInstall()
	{
		global $DB, $APPLICATION, $step;
		ModuleManager::registerModule($this->MODULE_ID);
		$this->InstallEvents();
		$this->SetDefaultOptions();
		return true;
	}

	public function DoUninstall()
	{
		global $DB, $APPLICATION, $step;
		$this->UnInstallEvents();
		$this->RemoveOptions();
		ModuleManager::unRegisterModule($this->MODULE_ID);
		return true;
	}


	public function InstallEvents()
	{
		RegisterModuleDependences("main", "OnEpilog", $this->MODULE_ID, "IS_PRO\EasyNoCaptcha\Events", "Setup");
		RegisterModuleDependences("blog", "OnBeforeBlogAdd", $this->MODULE_ID, "IS_PRO\EasyNoCaptcha\Events", "OnBeforeBlogAdd");
		RegisterModuleDependences("im", "OnBeforeMessageNotifyAdd", $this->MODULE_ID, "IS_PRO\EasyNoCaptcha\Events", "OnBeforeMessageNotifyAdd");
		RegisterModuleDependences("form", "onBeforeResultAdd", $this->MODULE_ID,  "IS_PRO\EasyNoCaptcha\Events", "onBeforeResultAdd");
		RegisterModuleDependences("iblock", "OnBeforeIBlockAddHandler", $this->MODULE_ID,  "IS_PRO\EasyNoCaptcha\Events", "OnBeforeIBlockAddHandler");
		RegisterModuleDependences("vote", "onBeforeVoteAdd", $this->MODULE_ID,  "IS_PRO\EasyNoCaptcha\Events", "onBeforeVoteAdd");

		/*
		RegisterModuleDependences("main", "OnProlog", $this->MODULE_ID,"IS_PRO\module_name\Main", "OnProlog");
		RegisterModuleDependences("main", "OnEpilog", $this->MODULE_ID, "IS_PRO\module_name\Main", "OnEpilog");
		RegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, "IS_PRO\module_name\Main", "OnEndBufferContent");
		*/
		return false;
	}

	public function SetDefaultOptions()
	{

	}

	public function RemoveOptions()
	{
		include(__DIR__."/module.cfg.php");
		COption::RemoveOption($arModuleCfg['MODULE_ID'], "");
	}

	public function UnInstallEvents()
	{
		/*
		UnRegisterModuleDependences("main", "OnProlog", $this->MODULE_ID, "IS_PRO\module_name\Main", "OnProlog");
		UnRegisterModuleDependences("main", "OnEpilog", $this->MODULE_ID, "IS_PRO\module_name\Main", "OnEpilog");
		UnRegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, "IS_PRO\module_name\Main", "OnEndBufferContent");
		*/
		return false;
	}

}
