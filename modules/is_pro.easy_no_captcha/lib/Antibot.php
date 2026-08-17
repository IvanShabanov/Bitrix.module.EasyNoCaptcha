<?php

namespace IS_PRO\EasyNoCaptcha;

use Bitrix\Main\Loader;
use CSecurityIPRule;
use Bitrix\Main\Application;

class Antibot {

	const script = '<script id="enc">document.cookie="ENC_AB=ok; path=/;";location.reload();</script>';
	const scriptAlert = '<script id="enc">alert("I am not Robot"); document.cookie="ENC_AB=ok; path=/;";location.reload();</script>';

	public static function isBot()
	{
		$result = false;
		$option = Common::getOptions();
		if ($option['ANTIBOT'] != 'Y') {
			return false;
		}
		if ($_COCKIE['ENC_AB'] == 'ok') {
			return false;
		}

		$userAgent = mb_strtolower($_SERVER['HTTP_USER_AGENT']);
		$option['ANTIBOT_ACTIONS'] = json_decode($option['ANTIBOT_RULES'], true);
		if (!is_array($option['ANTIBOT_ACTIONS'])) {
			return false;
		}

		$guess_bot = '*';
		foreach ($option['ANTIBOT_ACTIONS'] as $name => $values) {
			$name = trim($name);
			if ($name == '*') {
				continue;
			}
			if (mb_strpos($userAgent, mb_strtolower($values['UA'])) !== false) {
				$guess_bot = $name;
				break;
			}
		}

		$script = self::script;

		if ($option['ANTIBOT_ACTIONS'][$guess_bot]['alertCaptcha'] == 'Y') {
			$script = self::scriptAlert;
		}

		if ($option['ANTIBOT_ACTIONS'][$guess_bot]['page'] == 'N') {
			self::showEmptyPage($script);
		}

		\Bitrix\Main\Page\Asset::getInstance()->addString($script);

		if ($option['ANTIBOT_ACTIONS'][$guess_bot]['js'] == 'N') {
			$em = \Bitrix\Main\EventManager::getInstance();
			$em->addEventHandler('main', 'OnEndBufferContent', ["\IS_PRO\EasyNoCaptcha\Antibot", "RemoveJS"]);
		}

		if ($option['ANTIBOT_ACTIONS'][$guess_bot]['css'] == 'N') {
			$em = \Bitrix\Main\EventManager::getInstance();
			$em->addEventHandler('main', 'OnEndBufferContent', ["\IS_PRO\EasyNoCaptcha\Antibot", "RemoveCSS"]);
		}

		return false;
	}

	public static function showEmptyPage($script = '') {
		if (empty($script)) {
			$script = self::script;
		}
		global $APPLICATION;
		$APPLICATION->RestartBuffer();
		while (ob_end_clean()) {
		}
		echo $script;
		die();
	}

	public static function RemoveJS(&$content)
	{
		$content = str_replace('.js', '', $content);
	}

	public static function RemoveCSS(&$content)
	{
		$content = str_replace('.css', '', $content);
	}

}