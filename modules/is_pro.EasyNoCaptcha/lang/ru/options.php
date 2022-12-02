<?
$MESS['ISPRO_EasyNoCaptcha_TAB_SET_DESC'] = 'Описание';
$MESS['ISPRO_EasyNoCaptcha_TAB_TITLE_DESC'] = 'Описание модуля';

$MESS['ISPRO_EasyNoCaptcha_DESCRIPTION'] = '
<p>Каптча на формы сайта.</p>
<p>Возможности:
	<ul>
		<li>По умолчанию ставит скрытую каптчу</li>
		<li>Можно подключить дополнительно <a href="https://www.google.com/recaptcha/about/">Google ReCaptcha</a></li>
		<li>Можно подключить дополнительно <a href="https://www.hcaptcha.com/">hCaptcha</a></li>
	</ul>
</p>
<h3>Для разработчиков</h3>
<p>Проверить

if (\ISPRO\ENC->CheckEasyNoCaptha ()) {
	echo '<p>You are human</p>';
 } else {
	echo '<p>You are robot</p>';
 }
</p>
';

$MESS['ISPRO_EasyNoCaptcha_TAB_SET_OPTION'] = 'Настройки';
$MESS['ISPRO_EasyNoCaptcha_TAB_TITLE_OPTION'] = 'Настройки иодуля';

$MESS['ISPRO_EasyNoCaptcha_CHECBOX_SETTING'] = 'Чекбокс';
$MESS['ISPRO_EasyNoCaptcha_STRING_SETTING'] = 'Строка';
$MESS['ISPRO_EasyNoCaptcha_TEXTAREA_SETTING'] = 'Текст';

$MESS['ISPRO_EasyNoCaptcha_MODULE_MODE'] = 'Режим работы модуля';
$MESS['ISPRO_EasyNoCaptcha_MODULE_MODE_off'] = 'Выключен';
$MESS['ISPRO_EasyNoCaptcha_MODULE_MODE_test'] = 'Тестирование';
$MESS['ISPRO_EasyNoCaptcha_MODULE_MODE_on'] = 'Включен';

$MESS['ISPRO_EasyNoCaptcha_SAVE'] = 'Сохранить';
$MESS['ISPRO_EasyNoCaptcha_DEFAULT'] = 'Сбросить все настройки по умолчанию';

$MESS['ISPRO_EasyNoCaptcha_USE_RECAPTCHA'] = 'Применять <a href="https://www.google.com/recaptcha/admin/create" target="_blank">Google ReCaptcha v3</a>';
$MESS['ISPRO_EasyNoCaptcha_RECAPTCHA_SITE_KEY'] = 'ReCaptcha Ключ сайта';
$MESS['ISPRO_EasyNoCaptcha_RECAPTCHA_SECRET_KEY'] = 'ReCaptcha Секретный Ключ';

$MESS['ISPRO_EasyNoCaptcha_USE_HCAPTCHA'] = 'Применять <a href="https://www.hcaptcha.com/" target="_blank">hCaptcha<a>';
$MESS['ISPRO_EasyNoCaptcha_HCAPTCHA_SITE_KEY'] = 'hCaptcha Ключ сайта';
$MESS['ISPRO_EasyNoCaptcha_HCAPTCHA_SECRET_KEY'] = 'hCaptcha Секретный Ключ <a href="https://dashboard.hcaptcha.com/settings" target="_blank">отсюда</a>';

$MESS['ISPRO_EasyNoCaptcha_PROTECT_LEVEL'] = 'Уровень защиты';
$MESS['ISPRO_EasyNoCaptcha_FORM_SELECTOR'] = 'Селектор форм';

$MESS['ISPRO_EasyNoCaptcha_BLOG_EVENT'] = 'Защитить модуль "Блоги"';
$MESS['ISPRO_EasyNoCaptcha_IM_EVENT'] = 'Защитить модуль "Веб-мессенджера"';
$MESS['ISPRO_EasyNoCaptcha_FORM_EVENT'] = 'Защитить модуль "Веб-формы"';
$MESS['ISPRO_EasyNoCaptcha_IBLOCK_EVENT'] = 'Защитить модуль "Информационные блоки"';
$MESS['ISPRO_EasyNoCaptcha_VOTE_EVENT'] = 'Защитить модуль "Опросы, голосования"';
$MESS['ISPRO_EasyNoCaptcha_SUPPORT_EVENT'] = 'Защитить модуль "Техподдержка"';
$MESS['ISPRO_EasyNoCaptcha_SALE_EVENT'] = 'Защитить модуль "Интернет-магазин"';
$MESS['ISPRO_EasyNoCaptcha_FORUM_EVENT'] = 'Защитить модуль "Форум"';

$MESS['ISPRO_EasyNoCaptcha_EVENTS'] = 'Еще события на которые надо повесить проверку Каптчи (одна строчка = одно событие "module:onEvent")';
