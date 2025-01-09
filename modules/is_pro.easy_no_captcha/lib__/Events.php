<?

namespace IS_PRO\EasyNoCaptcha;

class Events
{
	public static function CheckCaptcha(&$param1 = [], &$param2 = [], &$param3 = [], &$param4 = [], &$param5 = [], &$param6 = [])
	{
		$option = Common::getOptions();
		if ($option['MODULE_MODE'] != 'on') {
			return true;
		}
		$result  = Common::CheckCaptcha();
		return $result;
	}

	public static function Setup()
	{
		global $EasyNoCaptchaStatus;
		if ($EasyNoCaptchaStatus == 'inited') {
			return true;
		}

		$option = Common::getOptions();

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

		if ((int) $option['SORT'] > 0) {
			$sort = (int) $option['SORT'];
		} else {
			$sort = 100;
		}

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

			$eventManager->addEventHandler($module, $event, ['IS_PRO\EasyNoCaptcha\Events', 'CheckCaptcha'], $sort);
		}

		$EasyNoCaptchaStatus = 'inited';
	}
}
