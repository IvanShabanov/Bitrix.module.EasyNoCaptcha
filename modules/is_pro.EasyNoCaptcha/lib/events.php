<?
namespace IS_PRO\EasyNoCaptcha;

class Events
{

	public static function ENC() {
		$option = self::GetOptions();
		if ($option['MODULE_MODE'] != 'on') {
			return;
		}

		$ENC = new EasyNoCaptcha_v4([
			'encode' => true,
			'checkDefault' => true,
			'checkIP' => true,
			'ReturnPureJS' => false,
			'GoogleReCaptcha_key' => $option['USE_RECAPTCHA'] == 'Y' ? $option['RECAPTCHA_SITE_KEY'] : '',
			'GoogleRecaptcha_SecretKey' => $option['USE_RECAPTCHA'] == 'Y' ? $option['RECAPTCHA_SECRET_KEY'] : '',
			'hCaptcha_key' => $option['USE_HCAPTCHA'] == 'Y' ? $option['HCAPTCHA_SITE_KEY'] : '',
			'hCaptcha_SecretKey' => $option['USE_HCAPTCHA'] == 'Y' ? $option['HCAPTCHA_SECRET_KEY'] : '',
			'YandexSmartCaptcha_key' => $option['USE_YANDEXCAPTCHA'] == 'Y' ? $option['YANDEXCAPTCHA_SITE_KEY'] : '',
			'YandexSmartCaptcha_secretkey' => $option['USE_YANDEXCAPTCHA'] == 'Y' ? $option['YANDEXCAPTCHA_SECRET_KEY'] : '',
			'script_attributes' => '',
			'debug' => false,
			'forms_selector' => $option['FORM_SELECTOR'] ? $option['FORM_SELECTOR'] : 'form'
		]);

		return $ENC;
	}

	public static function OnBeforeBlogAdd(&$arFields) {
		$option = self::GetOptions();
		if (($option['BLOG_EVENT'] == 'Y') && (!self::Check())) {
			return false;
		}
		return true;
	}

	public static function OnBeforeMessageNotifyAdd(&$arFields) {
		$option = self::GetOptions();
		if (($option['IM_EVENT'] == 'Y') && (!self::Check())) {
			return false;
		}
		return true;
	}

	public static function onBeforeResultAdd($WEB_FORM_ID, &$arFields, &$arrVALUES) {
		$option = self::GetOptions();
		if (($option['FORM_EVENT'] == 'Y') && (!self::Check())) {
			return false;
		}
		return true;
	}

	public static function OnBeforeIBlockAddHandler(&$arFields) {
		$option = self::GetOptions();
		if (($option['IBLOCK_EVENT'] == 'Y') && (!self::Check())) {
			return false;
		}
		return true;
	}

	public static function onBeforeVoteAdd(&$arFields) {
		$option = self::GetOptions();
		if (($option['VOTE_EVENT'] == 'Y') && (!self::Check())) {
			return false;
		}
		return true;
	}

	public static function onBeforeVoteAdd(&$arFields) {
		$option = self::GetOptions();
		if (($option['VOTE_EVENT'] == 'Y') && (!self::Check())) {
			return false;
		}
		return true;
	}


	public static function Check() {
		$option = self::GetOptions();
		if ($option['MODULE_MODE'] != 'on') {
			return true;
		}
		$ENC = self::ENC();
		return $ENC->CheckEasyNoCaptha();
	}

	public static function Setup() {
		$ENC = self::ENC();

		$enc_script  = $ENC->SetEasyNoCaptcha($option['PROTECT_LEVEL'], $option['FORM_SELECTOR'] ? $option['FORM_SELECTOR'] : 'form');

		\Bitrix\Main\Page\Asset::getInstance()->addString($enc_script);
	}



	public static function GetOptions() {
		include(__DIR__ . "/../install/module.cfg.php");
		$options_list = $arModuleCfg['options_list'];
		foreach ($options_list as $option_name => $arOption) {
			$option[$option_name] = \Bitrix\Main\Config\Option::get($arModuleCfg['MODULE_ID'], $option_name, SITE_ID);
			if ($arOption['type'] == 'json') {
				$option[$option_name . '_VALUE'] = @json_decode($option[$option_name], true);
			}
		}
		if ($option['MODULE_MODE'] == 'test') {
			if ($_GET['EasyNoCaptcha']) {
				$_SESSION['EasyNoCaptcha'] = $_GET['EasyNoCaptcha'];
			}
			$option['MODULE_MODE'] = $_SESSION['EasyNoCaptcha'];
			$option['DEBUG'] = 'Y';
		};
		if ($option['MODULE_MODE'] == 'on') {
			if ($_GET['EasyNoCaptchaDebug'] == 'Y') {
				$option['DEBUG'] = 'Y';
			};
		};
		if ($USER->IsAdmin()) {
			$option['MODULE_MODE'] = 'off';
		};
		if (\CSite::InDir('/bitrix/')) {
			$option['MODULE_MODE'] = 'off';
		};


		$option['MODULE_CONFIG'] = $arModuleCfg;
		$option['DOCUMENT_ROOT'] = \Bitrix\Main\Application::getDocumentRoot();
		return $option;
	}
}