<?
namespace IS_PRO\EasyNoCaptcha;

class Common
{
	public static function getOptions(array $customOption = [])
	{
		global $USER;
		include(__DIR__ . "/../install/module.cfg.php");
		$options_list = $arModuleCfg['options_list'];
		foreach ($options_list as $option_name => $arOption) {
			$option[$option_name] = \Bitrix\Main\Config\Option::get($arModuleCfg['MODULE_ID'], $option_name, SITE_ID);
			if ($arOption['type'] == 'json') {
				$option[$option_name . '_VALUE'] = @json_decode($option[$option_name], true);
			}
		}
		$option['DEBUG'] = 'N';
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
		if (!empty($USER) && $USER->IsAdmin()) {
			$option['MODULE_MODE'] = 'off';
		};

		$option['MODULE_CONFIG'] = $arModuleCfg;

		$option['DOCUMENT_ROOT'] = \Bitrix\Main\Application::getDocumentRoot();

		$option['URL_PATH'] = explode($option['DOCUMENT_ROOT'], __DIR__)[1];

		$option['MODULE_ID'] = $arModuleCfg['MODULE_ID'];

		$option['ReturnPureJS'] = false;

		$option['encode'] = true;
		$option['checkDefault'] = true;
		$option['checkIP'] = true;
		$option['script_attributes'] = '';
		$option['debugENC'] = false;


		if (!empty($customOption)) {
			$option = array_merge($option, $customOption);
		}

		return $option;
	}

	public static function ENC(array $option = [])
	{
		if (
			empty($option)
			|| empty($option['encode'])
			|| empty($option['checkDefault'])
			|| empty($option['checkIP'])
			|| empty($option['ReturnPureJS'])
		) {
			$option = self::getOptions($option);
		}

		$ENC = new EasyNoCaptcha([
			'encode' => $option['encode'],
			'checkDefault' => $option['checkDefault'],
			'checkIP' => $option['checkIP'],
			'ReturnPureJS' => $option['ReturnPureJS'],
			'GoogleReCaptcha_key' => $option['USE_RECAPTCHA'] == 'Y' ? $option['RECAPTCHA_SITE_KEY'] : '',
			'GoogleRecaptcha_SecretKey' => $option['USE_RECAPTCHA'] == 'Y' ? $option['RECAPTCHA_SECRET_KEY'] : '',
			'hCaptcha_key' => $option['USE_HCAPTCHA'] == 'Y' ? $option['HCAPTCHA_SITE_KEY'] : '',
			'hCaptcha_SecretKey' => $option['USE_HCAPTCHA'] == 'Y' ? $option['HCAPTCHA_SECRET_KEY'] : '',
			'YandexSmartCaptcha_key' => $option['USE_YANDEXCAPTCHA'] == 'Y' ? $option['YANDEXCAPTCHA_SITE_KEY'] : '',
			'YandexSmartCaptcha_secretkey' => $option['USE_YANDEXCAPTCHA'] == 'Y' ? $option['YANDEXCAPTCHA_SECRET_KEY'] : '',
			'script_attributes' => !empty($option['script_attributes']) ? $option['script_attributes'] : '',
			'debug' => $option['debugENC'] ? true : false,
			'forms_selector' => $option['FORM_SELECTOR'] ? $option['FORM_SELECTOR'] : 'form'
		]);

		return $ENC;
	}
	public static function getScript(array $customOption = [])
	{
		$option = self::getOptions($customOption);
		$ENC     = self::ENC($option);
		$result  = $ENC->SetEasyNoCaptcha($option['PROTECT_LEVEL'], $option['FORM_SELECTOR'] ? $option['FORM_SELECTOR'] : 'form');
		return $result;
	}


	public static function CheckCaptcha(array $customOption = [])
	{
		global $APPLICATION;
		$option = self::getOptions($customOption);
		$ENC = self::ENC( $option);
		$result  = $ENC->CheckEasyNoCaptha();
		$event = new \Bitrix\Main\Event($option['MODULE_ID'], "AfterCheckEasyNoCaptha", [&$result, &$param1, &$param2, &$param3, &$param4, &$param5, &$param6]);
		$event->send();
		if (!$result) {
			if (isset($option['LOG']) && $option['LOG'] == 'Y') {
				\CEventLog::Add(array(
					"SEVERITY" => "SECURITY",
					"AUDIT_TYPE_ID" => "Captcha error",
					"MODULE_ID" => $option['MODULE_CONFIG']['MODULE_ID'],
					"ITEM_ID" => $option['MODULE_CONFIG']['MODULE_ID'],
					"DESCRIPTION" => "Не пройдена каптча. \n" . print_r($_SERVER, true),
				));
			}
			$APPLICATION->ThrowException($option['CAPTCHA_ERROR']);
		}
		return $result;
	}
}