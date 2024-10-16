# Bitrix EasyNoCaptcha (Каптча для Битрикс)

Защищает формы публичной части сайта

Возможности:
- Cкрытая JS каптча
- Google ReCaptcha (опционально)
- hCaptcha (опционально)
- Yandex Smart Captcha (опционально)
- Выбор форм к которым добавляется каптча (css селектор в настройках модуля) - по умолчанию ко всем формам
- Работает со стандартными модулями Form (веб формы), Iblock (Инфоблоки), Blog (Блог), Vote (Опросы, голосования), Forum (Форум)
- Возможноть подключить собственные события на проверку форм.
- Модуль не добавляет каптчу на формы, если авторизован администратор сайта

## Для разработчиков

### Проверить отправлена ли форма ботом или нет

	if (\Bitrix\Main\Loader::includeModule("is_pro.easy_no_captcha")) {
		if (!\IS_PRO\EasyNoCaptcha\Events::CheckCaptcha()) {
			echo "Вы подозрительно похожи на бота";
		}
	}

### Для дополнительной проверки или измененя результатов проверки каптчи

	$eventManager = \Bitrix\Main\EventManager::getInstance();
	$eventManager->addEventHandler("is_pro.easy_no_captcha", "AfterCheckEasyNoCaptha", "AfterCheckEasyNoCaptha");

	function AfterCheckEasyNoCaptha(\Bitrix\Main\Event $event)
	{
		$arParam = $event->getParameters();
		/* в $arParam[1-7] - параметры события, в котором проверялась каптча */

		$arResult = &$arParam[0]; /* после проверки Каптчи тут true/false */

		/* Какой-то код меняющий $arResult */

	}

### Можно использовать установленный модуль, без включения его на сайте (в настройках модуля)

Добавить каптчу к определенной форме, то где либо в некушируемой (!) области вывести (так как скрипт гшенерируется каждый раз новый)

	<?php
	if (\Bitrix\Main\Loader::includeModule("is_pro.easy_no_captcha")) {
		$customOptions = [
			'forms_selector' => '.myformSelector',
			// 'ReturnPureJS' => true // Если надо вернуть script без обрамления в теги <script>
		];
		$script = \IS_PRO\EasyNoCaptcha\Common::getScript($customOptions);
		echo $script;
	}
	?>


Проверка формы

	if (\Bitrix\Main\Loader::includeModule("is_pro.easy_no_captcha")) {
		if (!\IS_PRO\EasyNoCaptcha\Common::CheckCaptcha()) {
			echo "Вы подозрительно похожи на бота";
		}
	}

При такой установке каптча будет в не зависимости от того является ли пользователем администратором или нет