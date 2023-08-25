<?

namespace IS_PRO\EasyNoCaptcha;

use Bitrix\Main\SystemException;

class Events
{

	public static function getOptions()
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
		if ($USER->IsAdmin()) {
			$option['MODULE_MODE'] = 'off';
		};

		$option['MODULE_CONFIG'] = $arModuleCfg;

		$option['DOCUMENT_ROOT'] = \Bitrix\Main\Application::getDocumentRoot();

		[,$option['URL_PATH']] = explode($option['DOCUMENT_ROOT'], __DIR__);

		return $option;
	}

	public static function ENC(array $customOption = [])
	{
		$option = self::getOptions();
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

	public static function CheckCaptcha(&$param1 = [], &$param2 = [], &$param3 = [], &$param4 = [], &$param5 = [], &$param6 = [])
	{
		global $APPLICATION;
		$option = self::getOptions();
		if ($option['MODULE_MODE'] != 'on') {
			return true;
		}
		$ENC = self::ENC();
		$result  = $ENC->CheckEasyNoCaptha();
		if (!$result) {
			if ($option['LOG'] == 'Y') {
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

	public static function Setup()
	{
		global $EasyNoCaptchaStatus;
		if ($EasyNoCaptchaStatus == 'inited') {
			return true;
		}

		$option = self::getOptions();

		if ($option['MODULE_MODE'] != 'on') {
			return true;
		}

		\Bitrix\Main\Page\Asset::getInstance()->addString('<script data-id="ENC" data-skip-moving="true" src="'.$option['URL_PATH'].'/script.php"></script>');


		$eventManager = \Bitrix\Main\EventManager::getInstance();

		$arEvents = [
			'_blog:OnBeforeBlogAdd',
			'_blog:OnBeforeCommentAdd',
			'_blog:OnBeforePostAdd',
			'_form:onBeforeResultAdd',
			'_iblock:OnBeforeIBlockElementAdd',
			'_iblock:OnBeforeIBlockSectionAdd',
			'_vote:onBeforeVoteAnswerAdd',
			'_forum:onBeforeMessageAdd',
			'_forum:onBeforeUserAdd',
			'_forum:onBeforeTopicAdd',
			'_forum:onBeforeGroupForumsAdd',
		];


		$arCustomEvents = explode("\n",	$option['EVENTS']);

		$arEvents = array_merge($arEvents, $arCustomEvents);

		foreach ($arEvents as $event) {
			$event = trim($event);
			if ($event == '') {
				continue;
			};

			if (mb_strpos($event, ':') !== false) {
				[$module, $event] = explode(':', $event);
			};

			if (mb_substr($module, 0, 1) == "_") {
				$module = trim($module, '_');
				if ($option[mb_strtoupper($module) . '_EVENT'] != 'Y') {
					continue;
				}
			}

			$eventManager->addEventHandler($module, $event, ['IS_PRO\EasyNoCaptcha\Events', 'CheckCaptcha']);
		}

		$EasyNoCaptchaStatus = 'inited';
	}

	public static function getScript()
	{
		$option = self::getOptions();

		if ($option['MODULE_MODE'] != 'on') {
			return '';
		}

		$ENC = new EasyNoCaptcha_v4([
			'encode' => true,
			'checkDefault' => true,
			'checkIP' => true,
			'ReturnPureJS' => true,
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

		$enc_script  = $ENC->SetEasyNoCaptcha($option['PROTECT_LEVEL'], $option['FORM_SELECTOR'] ? $option['FORM_SELECTOR'] : 'form');
		return $enc_script;
	}
}
