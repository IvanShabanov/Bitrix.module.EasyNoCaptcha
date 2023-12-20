<?
$MESS['ISPRO_EasyNoCaptcha_TAB_SET_DESC'] = 'Описание';
$MESS['ISPRO_EasyNoCaptcha_TAB_TITLE_DESC'] = 'Описание модуля';

$MESS['ISPRO_EasyNoCaptcha_DESCRIPTION'] = '
<h3>Каптча на формы сайта.</h3>
<p>Защищает формы публичной части сайта</p>
<p>Модуль не работает если авторизован администратор сайта</p>
<p>Возможности:
	<ul>
		<li>По умолчанию ставит скрытую каптчу</li>
		<li><a href="https://www.google.com/recaptcha/about/" target="_blank">Google ReCaptcha</a></li>
		<li><a href="https://www.hcaptcha.com/" target="_blank">hCaptcha</a></li>
		<li><a href="https://cloud.yandex.ru/services/smartcaptcha" target="_blank">Yandex Smart Captcha</a></li>
	</ul>
</p>
<h3>Для разработчиков</h3>
<p>Проверить отправлена ли форма ботом или нет</p>
<pre>
if (\Bitrix\Main\Loader::includeModule("is_pro.easy_no_captcha")) {
	if (!\IS_PRO\EasyNoCaptcha\Events::CheckCaptcha()) {
		echo "Вы подозрительно похожи на бота";
	}
}
</pre>
';

$MESS['ISPRO_EasyNoCaptcha_TAB_SET_OPTION'] = 'Настройки';
$MESS['ISPRO_EasyNoCaptcha_TAB_TITLE_OPTION'] = 'Настройки иодуля';


$MESS['ISPRO_EasyNoCaptcha_MODULE_MODE'] = 'Режим работы модуля';
$MESS['ISPRO_EasyNoCaptcha_MODULE_MODE_off'] = 'Выключен';
$MESS['ISPRO_EasyNoCaptcha_MODULE_MODE_test'] = 'Тестирование';
$MESS['ISPRO_EasyNoCaptcha_MODULE_MODE_on'] = 'Включен';

$MESS['ISPRO_EasyNoCaptcha_SAVE'] = 'Сохранить';
$MESS['ISPRO_EasyNoCaptcha_RESET'] = 'Сбросить все настройки по умолчанию';

$MESS['ISPRO_EasyNoCaptcha_USE_RECAPTCHA'] = 'Применять <a href="https://www.google.com/recaptcha/admin/create" target="_blank">Google ReCaptcha v3</a>';
$MESS['ISPRO_EasyNoCaptcha_RECAPTCHA_SITE_KEY'] = 'ReCaptcha Ключ сайта';
$MESS['ISPRO_EasyNoCaptcha_RECAPTCHA_SECRET_KEY'] = 'ReCaptcha Секретный Ключ';

$MESS['ISPRO_EasyNoCaptcha_USE_HCAPTCHA'] = 'Применять <a href="https://www.hcaptcha.com/" target="_blank">hCaptcha<a>';
$MESS['ISPRO_EasyNoCaptcha_HCAPTCHA_SITE_KEY'] = 'hCaptcha Ключ сайта';
$MESS['ISPRO_EasyNoCaptcha_HCAPTCHA_SECRET_KEY'] = 'hCaptcha Секретный Ключ <a href="https://dashboard.hcaptcha.com/settings" target="_blank">отсюда</a>';

$MESS['ISPRO_EasyNoCaptcha_USE_YANDEXCAPTCHA'] = 'Применять <a href="https://cloud.yandex.ru/services/smartcaptcha" target="_blank">Yandex Smart Captcha<a>';
$MESS['ISPRO_EasyNoCaptcha_YANDEXCAPTCHA_SITE_KEY'] = 'Yandex Smart Captcha Ключ клиента';
$MESS['ISPRO_EasyNoCaptcha_YANDEXCAPTCHA_SECRET_KEY'] = 'Yandex Smart Captcha Ключ сервера';

$MESS['ISPRO_EasyNoCaptcha_PROTECT_LEVEL'] = 'Уровень защиты';
$MESS['ISPRO_EasyNoCaptcha_FORM_SELECTOR'] = 'Селектор форм';

$MESS['ISPRO_EasyNoCaptcha_BLOG_EVENT'] = 'Защитить модуль "Блоги"';
$MESS['ISPRO_EasyNoCaptcha_FORM_EVENT'] = 'Защитить модуль "Веб-формы"';
$MESS['ISPRO_EasyNoCaptcha_IBLOCK_EVENT'] = 'Защитить модуль "Информационные блоки"';
$MESS['ISPRO_EasyNoCaptcha_VOTE_EVENT'] = 'Защитить модуль "Опросы, голосования"';
$MESS['ISPRO_EasyNoCaptcha_FORUM_EVENT'] = 'Защитить модуль "Форум"';

$MESS['ISPRO_EasyNoCaptcha_EVENTS'] = 'Еще события на которые надо повесить проверку Каптчи (одна строчка = одно событие "module:onEvent")';
$MESS['ISPRO_EasyNoCaptcha_CAPTCHA_ERROR'] = 'Сообщение о подозрении на заполнение форм ботом';
$MESS['ISPRO_EasyNoCaptcha_LOG'] = 'Записывать в журнал неуспешные случаи прохождения каптчи';