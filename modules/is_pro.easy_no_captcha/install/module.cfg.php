<?
$arModuleCfg = [
	'MODULE_ID' => 'is_pro.easy_no_captcha',
	'options_list' => [
		'MODULE_MODE' => [ 					/* Имя настройки */
			'type' => 'select', 			/* Тип поля настройки */
			'values' => [					/* Значения настройки */
				'off',
				'test',
				'on'
			],
			'default' => 'off'				/* Значение по умолчанию */
		],

		'USE_RECAPTCHA' => [
			'type' => 'checkbox',
			'default' => 'N'
		],

		'RECAPTCHA_SITE_KEY' => [
			'type' => 'text',
			'default' => ''
		],

		'RECAPTCHA_SECRET_KEY' => [
			'type' => 'text',
			'default' => ''
		],

		'USE_HCAPTCHA' => [
			'type' => 'checkbox',
			'default' => 'N'
		],

		'HCAPTCHA_SITE_KEY' => [
			'type' => 'text',
			'default' => ''
		],

		'HCAPTCHA_SECRET_KEY' => [
			'type' => 'text',
			'default' => ''
		],

		'USE_YANDEXCAPTCHA' => [
			'type' => 'checkbox',
			'default' => 'N'
		],

		'YANDEXCAPTCHA_SITE_KEY' => [
			'type' => 'text',
			'default' => ''
		],

		'YANDEXCAPTCHA_SECRET_KEY' => [
			'type' => 'text',
			'default' => ''
		],

		'PROTECT_LEVEL' => [
			'type' => 'text',
			'default' => '3'
		],

		'FORM_SELECTOR' => [
			'type' => 'text',
			'default' => 'form'
		],

		'FORM_EVENT' => [
			'type' => 'checkbox',
			'default' => 'N'
		],

		'BLOG_EVENT' => [
			'type' => 'checkbox',
			'default' => 'N'
		],

		'IBLOCK_EVENT' => [
			'type' => 'checkbox',
			'default' => 'N'
		],

		'VOTE_EVENT' => [
			'type' => 'checkbox',
			'default' => 'N'
		],

		'FORUM_EVENT' => [
			'type' => 'checkbox',
			'default' => 'N'
		],

		'EVENTS' => [
			'type' => 'textarea',
			'default' => ''
		],

		'CAPTCHA_ERROR' => [
			'type' => 'text',
			'default' => 'Защита от автоматического заполнения'
		],

		'LOG' => [
			'type' => 'checkbox',
			'default' => 'N'
		],

	]
];