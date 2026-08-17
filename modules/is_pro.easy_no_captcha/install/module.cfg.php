<?php
$searchEnginePatterns = [
	'*' =>
	[
		'UA' => '',
		'page' => 'Y',
		'js' => 'N',
		'css' => 'N',
		'alertCaptcha' => 'N',
	],
	'Google'      =>
	[
		'UA' => 'google',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'Yandex'      =>
	[
		'UA' => 'yandex',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'Bing'        =>
	[
		'UA' => 'bing',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'Msn'         =>
	[
		'UA' => 'msnbot',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'Yahoo'       =>
	[
		'UA' => 'yahoo',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'Baidu'       =>
	[
		'UA' => 'Baidu',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'DuckDuckGo'  =>
	[
		'UA' => 'Duck',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'Naver'       =>
	[
		'UA' => 'Yeti',					 // Корея

		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'Seznam'      =>
	[
		'UA' => 'SeznamBot',				// Чехия
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'Qwant'       =>
	[
		'UA' => 'Qwant',		// Франция
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'OpenAI'      =>
	[
		'UA' => 'GPT',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'OAI'         =>
	[
		'UA' => 'OAI-SearchBot',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'Perplexity'  =>
	[
		'UA' => 'Perplexity',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'Anthropic'   =>
	[
		'UA' => 'Claude',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'MetaAI'      =>
	[
		'UA' => 'Meta-ExternalAgent',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'AppleAI'     =>
	[
		'UA' => 'Applebot',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'ByteDanceAI' =>
	[
		'UA' => 'Bytespider',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'AmazonBot'   =>
	[
		'UA' => 'Amazonbot',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'CohereAI'    =>
	[
		'UA' => 'cohere-ai',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
	'Frog SEO Spider' =>
	[
		'UA' => 'Frog SEO Spider',
		'page' => 'Y',
		'js' => 'Y',
		'css' => 'Y',
		'alertCaptcha' => 'N',
	],
];


$arModuleCfg = [
	'MODULE_ID'    => 'is_pro.easy_no_captcha',
	'options_list' => [
		'MODULE_MODE'              => [ 					/* Имя настройки */
			'type'    => 'select', 			/* Тип поля настройки */
			'values'  => [					/* Значения настройки */
				'off',
				'test',
				'on',
			],
			'default' => 'off',				/* Значение по умолчанию */
		],

		'USE_RECAPTCHA'            => [
			'type'    => 'checkbox',
			'default' => 'N',
		],

		'RECAPTCHA_SITE_KEY'       => [
			'type'    => 'text',
			'default' => '',
		],

		'RECAPTCHA_SECRET_KEY'     => [
			'type'    => 'text',
			'default' => '',
		],

		'USE_HCAPTCHA'             => [
			'type'    => 'checkbox',
			'default' => 'N',
		],

		'HCAPTCHA_SITE_KEY'        => [
			'type'    => 'text',
			'default' => '',
		],

		'HCAPTCHA_SECRET_KEY'      => [
			'type'    => 'text',
			'default' => '',
		],

		'USE_YANDEXCAPTCHA'        => [
			'type'    => 'checkbox',
			'default' => 'N',
		],

		'YANDEXCAPTCHA_SITE_KEY'   => [
			'type'    => 'text',
			'default' => '',
		],

		'YANDEXCAPTCHA_SECRET_KEY' => [
			'type'    => 'text',
			'default' => '',
		],

		'PROTECT_LEVEL'            => [
			'type'    => 'text',
			'default' => '3',
		],

		'FORM_SELECTOR'            => [
			'type'    => 'text',
			'default' => 'form',
		],

		'ENC_SELECTOR'             => [
			'type'    => 'text',
			'default' => '.enc_place',
		],

		'SORT'                     => [
			'type'    => 'text',
			'default' => '100',
		],

		'FORM_EVENT'               => [
			'type'    => 'checkbox',
			'default' => 'N',
		],

		'BLOG_EVENT'               => [
			'type'    => 'checkbox',
			'default' => 'N',
		],

		'IBLOCK_EVENT'             => [
			'type'    => 'checkbox',
			'default' => 'N',
		],

		'VOTE_EVENT'               => [
			'type'    => 'checkbox',
			'default' => 'N',
		],

		'FORUM_EVENT'              => [
			'type'    => 'checkbox',
			'default' => 'N',
		],

		'EVENTS'                   => [
			'type'    => 'textarea',
			'default' => '',
		],

		'CAPTCHA_ERROR'            => [
			'type'    => 'text',
			'default' => 'Защита от автоматического заполнения',
		],

		'INIT_JS_EVENT'            => [
			'type'    => 'text',
			'default' => 'DOMContentLoaded',
		],

		'LOG'                      => [
			'type'    => 'checkbox',
			'default' => 'N'
		],

		'ANTIBOT_TITLE'            => [],

		'ANTIBOT'                  => [
			'type'    => 'checkbox',
			'default' => 'N',
		],

		'ANTIBOT_RULES'       => [
			'type'    => 'json',
			'default' => json_encode($searchEnginePatterns),
		],
	]
];