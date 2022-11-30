<?
$arModuleCfg = [
	'MODULE_ID' => 'is_pro.EasyNoCaptcha',
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

		'PROTECT_LEVEL' => [
			'type' => 'text',
			'default' => '3'
		],

		'FORM_SELECTOR' => [
			'type' => 'text',
			'default' => 'form'
		],

		'BLOG_EVENT' => [
			'type' => 'checkbox',
			'default' => 'N'
		],


		'IM_EVENT' => [
			'type' => 'checkbox',
			'default' => 'N'
		],

		'FORM_EVENT' => [
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

		'SUPPORT_EVENT' => [
			'type' => 'checkbox',
			'default' => 'N'
		],

		'SALE_EVENT' => [
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

		'EXCEPTIONS_URLS' => [
			'type' => 'textarea',
			'default' => ''
		],

	]
];