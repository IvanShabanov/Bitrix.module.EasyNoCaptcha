<?php
namespace IS_PRO\EasyNoCaptcha;
if (!class_exists('EasyNoCaptcha')) {
	class EasyNoCaptcha
	{
		private $_ENC_shuffle = [];
		private $_ENC_AllReadyFunc = [];
		private $_ENC_string = [];
		private $curArray = array();
		private $_ENC_setting = [];

		public static function i18($message = '', $lang = null)
		{
			if (empty($lang)) {
				$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2); //Берем первые буквы языка браузера пользователя.
			}

			$mess = [
				'ru' => [
					"I'm not robot" => 'Я не робот',
					"I'm human"     => 'Я человек',
				],
				'en' => [
					"I'm not robot" => "I'm not robot",
					"I'm human"     => "I'm human",
				]
			];

			if (empty($mess[$lang])) {
				$lang = 'en';
			}
			if (empty($mess[$lang][$message])) {
				$mess[$lang][$message] = $message;
			}
			return $mess[$lang][$message];
		}

		public function __construct(array $setting = [])
		{
			$this->_ENC_setting = [
				'encode'                       => true,  // Обсуфикация кода
				'checkDefault'                 => true,  // Проверять каптчу по дефолту
				'checkIP'                      => true,  // Проверяем IP
				'ReturnPureJS'                 => false,  // Вернуть чистый JS, без обертки в тег script
				'GoogleReCaptcha_key'          => '',  // GoogleRecaptcha
				'GoogleRecaptcha_SecretKey'    => '',
				'hCaptcha_key'                 => '',  // HCaptcha
				'hCaptcha_SecretKey'           => '',
				'YandexSmartCaptcha_key'       => '', // YandexSmartCaptcha
				'YandexSmartCaptcha_SecretKey' => '',
				'useAlertCaptcha'              => false, // Использовать Alert каптча
				'AlertCaptchaText'             => "I'm human", // Текст
				'script_attributes'            => '',  // - пока нигде не используется
				'form_selector'                => 'form', // CSS селекторр форм
				'enc_place'                    => '.enc_place', // CSS селектор элемента в форме куда размещать каптчу
				'InitOnJsEvent'                => 'DOMContentLoaded', // JS event - которое запустит скрипт

				'debug'                        => false, // Дебаг
				'debug_to_file'                => true, // Записывать лог в файл
				'debug_to_global_array'        => false, // Записывать лог в глобавльный массив
			];

			if (count($setting) > 0) {
				foreach ($setting as $key => $val) {
					if (isset($this->_ENC_setting[$key])) {
						$this->_ENC_setting[$key] = $val;
					}
				}
			}

			$this->curArray = [
				'DATE'    => time(),
				'IP'      => $_SERVER['REMOTE_ADDR'],
				'URL'     => $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
				'REFERER' => $_SERVER['HTTP_REFERER']
			];

			if ($this->_ENC_setting['checkDefault']) {
				if (is_array($_SESSION['MYHASH'])) {
					foreach ($_SESSION['MYHASH'] as $key => $val) {
						if (isset($_SESSION['MYHASH'][$key]['DATE'])) {
							/* Delete hash livetime > 10 min */
							if ($this->curArray['DATE'] - $_SESSION['MYHASH'][$key]['DATE'] > 600) {
								unset($_SESSION['MYHASH'][$key]);
							}
						} else {
							/* Delete hash without DATE */
							unset($_SESSION['MYHASH'][$key]);
						}
					}
				}
			}
		}

		/**********************/
		/* ENC */
		/**********************/
		public function SetEasyNoCaptcha($_protect = 3, $_form = null)
		{
			$this->_ENC_setting['form_selector'] = !empty($_form) ? $_form : $this->_ENC_setting['form_selector'];
			if (empty($this->_ENC_setting['form_selector'])) {
				$this->_ENC_setting['form_selector'] = 'form';
			}
			$HASHCODE = substr(md5(uniqid()), 0, rand(10, 32));
			$HASH     = substr(md5(uniqid()), 0, rand(10, 32));
			if (!$this->_ENC_setting['checkDefault']) {
				$HASHCODE = md5('HASHCODE');
				$HASH     = md5('HASH');
			}
			$_SESSION['MYHASH'][$HASHCODE]          = $this->curArray;
			$_SESSION['MYHASH'][$HASHCODE]['VALUE'] = $HASH;
			$_SESSION['MYHASH'][$HASHCODE]['DATE']  = date('YmdHis');

			$_InitedForm  = substr(base64_encode(md5(uniqid())), 0, rand(10, 32));
			$_CheckedForm = substr(base64_encode(md5(uniqid())), 0, rand(10, 32));




			$this->_ENC_AllReadyFunc[] = $this->T('ENC_check');
			$this->_ENC_AllReadyFunc[] = $this->T('ENC_InitENC');

			$_ENC_script['code'] = '';
			$_ENC_script['init'] = '';
			if (($this->_ENC_setting['GoogleReCaptcha_key'] != '') && ($this->_ENC_setting['GoogleRecaptcha_SecretKey'] != '')) {
				$_ENC_script['code'] .= $this->SetGoogleReCaptcha();
				$_ENC_script['init'] .= $this->getCryptWord('ENC_initGR') . '();';
			}

			if (($this->_ENC_setting['hCaptcha_key'] != '') && ($this->_ENC_setting['hCaptcha_SecretKey'] != '')) {
				$_ENC_script['code'] .= $this->SetHCaptcha();
				$_ENC_script['init'] .= $this->getCryptWord('ENC_initHC') . '();';
			}

			if (($this->_ENC_setting['YandexSmartCaptcha_key'] != '') && ($this->_ENC_setting['YandexSmartCaptcha_SecretKey'] != '')) {
				$_ENC_script['code'] .= $this->SetYandexSmartCaptcha();
				$_ENC_script['init'] .= $this->getCryptWord('ENC_initYSC') . '();';
			}

			$result = '
				document["addEventListener"]("' . $this->_ENC_setting['InitOnJsEvent'] . '", function(event) {
					const ' . $this->T('document2') . ' = document;
					let ' . $this->T('chechsum') . ' = 0;

					const ' . $this->T('ENC_check') . ' = () => {
						' . $this->T('chechsum') . ' ++;
						if (' . $this->T('chechsum') . ' > ' . $_protect . ') {
							const ' . $this->T('forms2') . ' = ' . $this->T('document2') . '["querySelectorAll"]( ".' . $_InitedForm . ':not(.' . $_CheckedForm . ')" );
							' . $this->T('forms2') . '["forEach"](function(' . $this->T('form3') . ') {
								let ' . $this->T('HASHCODE') . ' = ' . $this->T('document2') . '["createElement"]("input");
								let ' . $this->T('HASH') . ' = ' . $this->T('document2') . '["createElement"]("input");
								' . $this->T('HASHCODE') . '["type"] = "hidden";
								' . $this->T('HASHCODE') . '["name"] = "HASHCODE";
								' . $this->T('HASHCODE') . '["value"] = "' . $HASHCODE . '";
								' . $this->T('HASH') . '["type"] = "hidden";
								' . $this->T('HASH') . '["name"] = "HASH";
								' . $this->T('HASH') . '["value"] = "' . $HASH . '";
								' . $this->T('form3') . '["appendChild"](' . $this->T('HASHCODE') . ');
								' . $this->T('form3') . '["appendChild"](' . $this->T('HASH') . ');
								' . $this->T('form3') . '["classList"]["add"]("' . $_CheckedForm . '");
							});
						};
					};
					const ' . $this->T('ENC_InitENC') . ' = () => {
						const ' . $this->T('forms3') . ' =  ' . $this->T('document2') . '["querySelectorAll"]("' . $_form . '");
						' . $this->T('forms3') . '["forEach"](function(' . $this->T('form4') . ') {
							if (!' . $this->T('form4') . '["classList"]["contains"]("' . $_InitedForm . '")) {
								' . $this->T('form4') . '["classList"]["add"]("' . $_InitedForm . '");
								' . $this->T('form4') . '["addEventListener"]("click", ' . $this->T('ENC_check') . ', false);
								' . $this->T('form4') . '["addEventListener"]("keydown", ' . $this->T('ENC_check') . ', false);
								' . $this->T('form4') . '["addEventListener"]("keyup", ' . $this->T('ENC_check') . ', false);
								' . $this->T('form4') . '["addEventListener"]("touchstart", ' . $this->T('ENC_check') . ', false);
								' . $this->T('form4') . '["addEventListener"]("touchmove", ' . $this->T('ENC_check') . ', false);
								' . $this->T('form4') . '["addEventListener"]("touchend", ' . $this->T('ENC_check') . ', false);
								' . $this->T('form4') . '["addEventListener"]("mouseenter", ' . $this->T('ENC_check') . ', false);
								' . $this->T('form4') . '["addEventListener"]("keyup", ' . $this->T('ENC_check') . ', false);
								' . $this->T('form4') . '["addEventListener"]("mouseleave", ' . $this->T('ENC_check') . ', false);
							};
						});
						' .
				$_ENC_script['init'] .
				'
					};
					' .
				$_ENC_script['code'] .
				'
					setTimeout(function() {
						' . $this->T('ENC_InitENC') . '();
						const MutationObserver	= window.MutationObserver;
						const ' . $this->T('ENCMutationObserver') . ' = new MutationObserver(' . $this->T('ENC_InitENC') . ');
						' . $this->T('ENCMutationObserver') . '["observe"](' . $this->T('document2') . '["querySelectorAll"]("body")[0],{childList:true,subtree:true});

					}, 1000);

				});
			';

			preg_match_all('/\"[^\"]*\"/', $result, $matches);
			if (is_array($matches)) {
				if (is_array($matches[0])) {
					foreach ($matches[0] as $string) {
						$result = $this->str_replace_once($string, $this->EncodeJsString($string), $result);
					}
				}
			}

			$this->AddToShuffle($result);
			$result = $this->ShuffleText();
			if ($this->_ENC_setting['encode']) {
				$arResult = explode("\n", $result);
				if (is_array($arResult)) {
					$result = '';
					foreach ($arResult as $r) {
						$result .= trim($r);
					}
				}
			}

			if (!$this->_ENC_setting['ReturnPureJS']) {
				$result = '<script type="text/javascript">' . $result . '</script>';
			}

			return $result;
		}
		/**********************/
		public function CheckEasyNoCaptha()
		{
			$result = false;
			if ((isset($_REQUEST['HASHCODE'])) && (isset($_REQUEST['HASH']))) {
				$result =
					(
						$this->CheckHash() &&
						$this->CheckIP()
					) && (
						$this->CheckGoogleRecaptcha()
					) && (
						$this->CheckHCaptcha()
					) && (
						$this->CheckYandexSmartCaptcha()
					);
				unset($_SESSION['MYHASH'][$_REQUEST['HASHCODE']]);
			}
			return $result;
		}
		/**********************/
		private function CheckHash()
		{
			$result = true;
			if ($this->_ENC_setting['checkDefault']) {
				if (
					($_REQUEST['HASHCODE'] == '') ||
					($_REQUEST['HASH'] == '') ||
					($_SESSION['MYHASH'][$_REQUEST['HASHCODE']]['VALUE'] != $_REQUEST['HASH'])
				) {
					$result = false;
				}
			}

			return $result;
		}

		/**********************/
		private function CheckIP()
		{
			$result = true;
			if ($this->_ENC_setting['checkIP']) {
				$session = $_SESSION['MYHASH'][$_REQUEST['HASHCODE']];
				if ($this->curArray['IP'] != $session['IP']) {
					/* IP не совпадают */
					$result = false;
				}
			}
			return $result;
		}

		/**********************/
		/* ReCaptcha */
		/**********************/

		public function AddGoogleRecaptcha($key, $secret)
		{
			$this->_ENC_setting['GoogleReCaptcha_key']       = $key;
			$this->_ENC_setting['GoogleRecaptcha_SecretKey'] = $secret;
		}
		/**********************/
		private function SetGoogleReCaptcha()
		{
			$_form                     = $this->_ENC_setting['form_selector'];
			$this->_ENC_AllReadyFunc[] = $this->T('ENC_initGR');
			$this->_ENC_AllReadyFunc[] = $this->T('ENC_GR_Set');

			$GoogleRecaptcha_Action = $this->T('GoogleRecaptcha_Action');
			$result                 = '
				let ' . $this->T('GR_need_add_script') . '=1;
				const ' . $this->T('GR_add_script') . ' = () => {
					if (' . $this->T('GR_need_add_script') . ') {
						let ' . $this->T('document1') . ' = document;
						let ' . $this->T('script1') . ' = ' . $this->T('document1') . '["createElement"]("script");
						' . $this->T('script1') . '["type"] = \'text/javascript\';
						' . $this->T('script1') . '["src"] = \'https://www.google.com/recaptcha/api.js?render=' . $this->_ENC_setting['GoogleReCaptcha_key'] . '\';
						' . $this->T('document1') . '["getElementsByTagName"]("head")[0].appendChild(' . $this->T('script1') . ');
						' . $this->T('GR_need_add_script') . '=0;
					};
				};
				const ' . $this->T('ENC_initGR') . ' = () => {
					let ' . $this->T('document1') . ' = document;
					' . $this->T('GR_add_script') . '();
					const ' . $this->T('forms1') . ' = ' . $this->T('document1') . '["querySelectorAll"]( "' . $_form . ':not(.' . $this->T('GR_checked') . ')" );
					setTimeout(function() {
						' . $this->T('forms1') . '["forEach"](function(' . $this->T('form2') . ') {
							' . $this->T('ENC_GR_Set') . '(' . $this->T('form2') . ');
							' . $this->T('form2') . '["classList"]["add"]("' . $this->T('GR_checked') . '");
						});
					}, 1000);
				};
				const ' . $this->T('ENC_GR_Set') . ' = (' . $this->T('form1') . ') => {
					if (!' . $this->T('form1') . '["classList"]["contains"]("' . $this->T('GR_checked') . '")) {
						' . $this->T('form1') . '["classList"]["add"]("' . $this->T('GR_checked') . '");
						let ' . $this->T('grecaptcha') . ' = grecaptcha;
						' . $this->T('grecaptcha') . '["ready"](function() {
							' . $this->T('grecaptcha') . '["execute"]("' . $this->_ENC_setting['GoogleReCaptcha_key'] . '", {action: "' . $GoogleRecaptcha_Action . '"})
							.then(function(token) {
								let d = document;
								let ' . $this->T('GR') . ' = d["createElement"]("input");
								let ' . $this->T('GR_action') . ' = d["createElement"]("input");
								' . $this->T('GR') . '["type"] = "hidden";
								' . $this->T('GR') . '["name"] = "gresponse";
								' . $this->T('GR') . '["value"] = token;
								' . $this->T('GR_action') . '["type"] = "hidden";
								' . $this->T('GR_action') . '["name"] = "gaction";
								' . $this->T('GR_action') . '["value"] = "' . $GoogleRecaptcha_Action . '";
								' . $this->T('form1') . '["appendChild"](' . $this->T('GR') . ');
								' . $this->T('form1') . '["appendChild"](' . $this->T('GR_action') . ');
							});;
						});
					};
				};
			';
			return $result;
		}


		/**********************/
		private function CheckGoogleRecaptcha()
		{
			$result = false;
			if (empty($this->_ENC_setting['GoogleReCaptcha_key'])) {
				$result = true;
			} else if ((isset($_REQUEST['gresponse'])) && (isset($_REQUEST['gaction']))) {
				$gaction  = $_REQUEST['gaction'];
				$url      = "https://www.google.com/recaptcha/api/siteverify";
				$postdata = [
					'secret'   => $this->_ENC_setting['GoogleRecaptcha_SecretKey'],
					'response' => $_REQUEST['gresponse']
				];
				if ($response = $this->curl($url, $postdata)) {
					$arrResponse = $this->json_decode($response);
					if ($this->_ENC_setting['debug']) {
						$this->log($arrResponse);
					}
					if (
						isset($arrResponse['success']) &&
						($arrResponse['success'] == 1) &&
						($arrResponse['action'] == $gaction) &&
						($arrResponse['score'] > 0.5)
					) {
						$result = true;
					}
				}
			}
			return $result;
		}


		/**********************/
		/* hCaptcha */
		/**********************/

		public function AddHCaptcha($key, $secret)
		{
			$this->_ENC_setting['hCaptcha_key']       = $key;
			$this->_ENC_setting['hCaptcha_SecretKey'] = $secret;
		}
		/**********************/
		private function SetHCaptcha()
		{
			$_form                     = $this->_ENC_setting['form_selector'];
			$this->_ENC_AllReadyFunc[] = $this->T('ENC_initHC');
			$this->_ENC_AllReadyFunc[] = $this->T('ENC_HC_Set');

			$result = '
				let ' . $this->T('HC_need_add_script') . '=1;
				const ' . $this->T('HC_add_script') . ' = () => {
					if (' . $this->T('HC_need_add_script') . ') {
						let ' . $this->T('document1') . ' = document;
						let ' . $this->T('script1') . ' = ' . $this->T('document1') . '["createElement"]("script");
						' . $this->T('script1') . '["type"] = \'text/javascript\';
						' . $this->T('script1') . '["src"] = \'https://www.hCaptcha.com/1/api.js' . $this->_ENC_setting['GoogleReCaptcha_key'] . '\';
						' . $this->T('document1') . '["getElementsByTagName"]("head")[0].appendChild(' . $this->T('script1') . ');
						' . $this->T('HC_need_add_script') . '=0;
					};
				};
				const ' . $this->T('ENC_initHC') . ' = () => {
					const ' . $this->T('document1') . ' = document;

					const ' . $this->T('forms1') . ' = ' . $this->T('document1') . '["querySelectorAll"]( "' . $_form . ':not(.' . $this->T('HC_checked') . ')" );

					' . $this->T('forms1') . '["forEach"](function(' . $this->T('form2') . ') {
						' . $this->T('ENC_HC_Set') . '(' . $this->T('form2') . ');
						' . $this->T('form2') . '["classList"]["add"]("' . $this->T('HC_checked') . '");
					});
					' . $this->T('HC_add_script') . '();
				};
				const ' . $this->T('ENC_HC_Set') . ' = (' . $this->T('form1') . ') => {
					if (!' . $this->T('form1') . '["classList"]["contains"]("' . $this->T('HC_checked') . '")) {
						' . $this->T('form1') . '["classList"]["add"]("' . $this->T('HC_checked') . '");
						let d = document;
						let ' . $this->T('enc_place') . ' = ' . $this->T('form1') . '["querySelector"]("' . $this->_ENC_setting['enc_place'] . '") ?? ' . $this->T('form1') . ';
						let ' . $this->T('HC') . ' = d["createElement"]("div");
						' . $this->T('HC') . '["setAttribute"]("class", "h-captcha");
						' . $this->T('HC') . '["setAttribute"]("data-sitekey", "' . $this->_ENC_setting['hCaptcha_key'] . '");
						' . $this->T('enc_place') . '["appendChild"](' . $this->T('HC') . ');
					};
				};
			';
			return $result;
		}
		/**********************/
		private function CheckHCaptcha()
		{
			$result = false;
			if (empty($this->_ENC_setting['hCaptcha_key'])) {
				$result = true;
			} else if (isset($_REQUEST['h-captcha-response'])) {
				$url      = "https://hcaptcha.com/siteverify";
				$postdata = array(
					'secret'   => $this->_ENC_setting['hCaptcha_SecretKey'],
					'response' => $_REQUEST['h-captcha-response']
				);
				if ($response = $this->curl($url, $postdata)) {
					$arrResponse = $this->json_decode($response);
					if ($this->_ENC_setting['debug']) {
						$this->log($arrResponse);
					}
					if (
						isset($arrResponse['success']) &&
						$arrResponse['success'] === true
					) {
						$result = true;
					}
				}
			}
			return $result;
		}

		/**********************/
		/* YandexSmartCaptcha */
		/**********************/
		public function AddYandexSmartCaptcha($key, $secret)
		{
			$this->_ENC_setting['YandexSmartCaptcha_key']       = $key;
			$this->_ENC_setting['YandexSmartCaptcha_SecretKey'] = $secret;
		}
		/**********************/
		private function SetYandexSmartCaptcha()
		{
			$_form = $this->_ENC_setting['form_selector'];

			$this->_ENC_AllReadyFunc[] = $this->T('ENC_initYSC');
			$this->_ENC_AllReadyFunc[] = $this->T('ENC_YSC_Set');
			$this->_ENC_AllReadyFunc[] = $this->T('ENC_onloadYSC');

			$this->addCryptWord('ENC_callbackYSC');
			$this->_ENC_AllReadyFunc[] = $this->T('ENC_callbackYSC');

			$result = '
				let ' . $this->T('YSC_need_add_script') . '=1;
				const ' . $this->T('YSC_add_script') . ' = () => {
					if (' . $this->T('YSC_need_add_script') . ') {
						let ' . $this->T('document1') . ' = document;
						let ' . $this->T('script1') . ' = ' . $this->T('document1') . '["createElement"]("script");
						' . $this->T('script1') . '["type"] = \'text/javascript\';
						' . $this->T('script1') . '["src"] = \'https://captcha-api.yandex.ru/captcha.js?render=onload&onload=' . $this->T('ENC_onloadYSC') . '\';
						' . $this->T('document1') . '["getElementsByTagName"]("head")[0].appendChild(' . $this->T('script1') . ');
						' . $this->T('YSC_need_add_script') . '=0;
					};
				};
				const ' . $this->T('ENC_initYSC') . ' = () => {
					let ' . $this->T('document1') . ' = document;
					' . $this->T('YSC_add_script') . '();
					const ' . $this->T('forms1') . ' = ' . $this->T('document1') . '["querySelectorAll"]( "' . $_form . ':not(.' . $this->T('YSC_checked') . ')" );
					setTimeout(function() {
						' . $this->T('forms1') . '["forEach"](function(' . $this->T('form2') . ') {
							' . $this->T('ENC_YSC_Set') . '(' . $this->T('form2') . ');
							' . $this->T('form2') . '["classList"]["add"]("' . $this->T('YSC_checked') . '");
						});
						' . $this->T('ENC_onloadYSC') . '();
					}, 1000);
				};
				const ' . $this->T('ENC_YSC_Set') . ' = (' . $this->T('form1') . ') => {
					if (!' . $this->T('form1') . '["classList"]["contains"]("' . $this->T('YSC_checked') . '")) {
						' . $this->T('form1') . '["classList"]["add"]("' . $this->T('YSC_checked') . '");
						let dy = document;
						let ' . $this->T('enc_place') . ' = ' . $this->T('form1') . '["querySelector"]("' . $this->_ENC_setting['enc_place'] . '") ?? ' . $this->T('form1') . ';
						let ' . $this->T('YSC') . ' = dy["createElement"]("div");
						' . $this->T('YSC') . '["setAttribute"]("class", "smart-captcha");
						' . $this->T('YSC') . '["setAttribute"]("style", "max-width: 300px");
						' . $this->T('enc_place') . '["appendChild"](' . $this->T('YSC') . ');

					};
				};
				const ' . $this->T('ENC_onloadYSC') . ' = () => {
					let dyYsc = document;
					if (!window.smartCaptcha) {
						return;
					}
					let containers = dyYsc["querySelectorAll"](".smart-captcha:not(.rendered)");
					if (containers.length > 0) {
						containers.forEach((cont) => {
							if (!cont["classList"]["contains"](\'rendered\')) {
								cont["classList"]["add"](\'rendered\');
								window.smartCaptcha.render(cont, {
									sitekey: "' . $this->_ENC_setting['YandexSmartCaptcha_key'] . '",
									invisible: false,
									shieldPosition: "top-left",
									callback: ' . $this->T('ENC_callbackYSC') . ',
								});
							}
						});
					}
				};
				const ' . $this->T('ENC_callbackYSC') . ' = (token) => {
					window.smartCaptcha.execute();
				};
			';
			return $result;
		}


		/**********************/
		private function CheckYandexSmartCaptcha()
		{
			$result = false;
			if (empty($this->_ENC_setting['YandexSmartCaptcha_key'])) {
				$result = true;
			} else if (isset($_REQUEST['smart-token'])) {
				$url      = "https://captcha-api.yandex.ru/validate";
				$postdata = [
					'secret' => $this->_ENC_setting['YandexSmartCaptcha_SecretKey'],
					'IP'     => $this->curArray['IP'],
					'token'  => $_REQUEST['smart-token']
				];
				if ($response = $this->curl($url, $postdata)) {
					$arrResponse = $this->json_decode($response);
					if ($this->_ENC_setting['debug']) {
						$this->log($arrResponse);
					}
					if (
						isset($arrResponse['status']) &&
						$arrResponse['status'] == 'ok'
					) {
						$result = true;
					}
				}
			}
			return $result;
		}


		private function SetAlertCaptcha()
		{

			$result = '
					function ' . $this->T('checkFunction') . '(funcName) {
						const ' . $this->T('iframe') . ' = document["createElement"]("iframe");
						' . $this->T('iframe') . '.style.display = "none";
						document.body.appendChild(' . $this->T('iframe') . ');
						const ' . $this->T('cleanFunc') . ' = ' . $this->T('iframe') . '.contentWindow[funcName];
						const ' . $this->T('currentFunc') . ' = window[funcName];
						const ' . $this->T('isOverwritten') . ' = ' . $this->T('currentFunc') . '.toString() !== ' . $this->T('cleanFunc') . '.toString();
						document.body.removeChild(' . $this->T('iframe') . ');
						return ' . $this->T('isOverwritten') . ';
					}
					const ' . $this->T('SetConfirmCaptcha') . ' = () => {};
			';
			return $result;
		}


		/**********************/
		/* functions */
		/**********************/
		private function AddToShuffle($text)
		{
			$this->_ENC_shuffle[] = $text;
		}

		/**********************/
		private function ShuffleText()
		{
			$result = '';
			foreach ($this->_ENC_shuffle as $t) {
				if (rand(0, 100) < 50) {
					$result .= $t;
				} else {
					$result = $t . $result;
				}
			}
			return $result;
		}
		/**********************/
		private function EncodeJsString($string, $level = 0)
		{

			$result     = '';
			$needEncode = $this->_ENC_setting['encode'];
			if ($level >= 3) {
				$needEncode = false;
			}
			if (!$needEncode) {
				$result = $string;
			}

			while ($needEncode) {
				$needEncode = false;
				$i          = 0;
				while ($i <= strlen($string)) {
					$char = substr($string, $i, 1);
					if (($i > 0) and ($i < strlen($string) - 1)) {
						if ((rand(0, 100) < 20) || ($char == '<')) {
							$d    = dechex(ord($char));
							$c    = (strlen($d) == 1) ? '0' . $d : $d;
							$char = '\x' . $c;
						} else if (rand(0, 100) < 20) {
							$char = '"+/*' . substr(md5(uniqid()), 0, rand(1, 32)) . '*/"' . $char;
						} else if (((rand(0, 100) < 20) && ($level < 3)) && (true)) {

							$val          = substr('enc' . md5(uniqid()), 0, rand(10, 32));
							$functionName = substr('enc' . md5(uniqid()), 0, rand(10, 32));
							if ((is_array($this->_ENC_AllReadyFunc)) && (count($this->_ENC_AllReadyFunc) > 0)) {
								while (in_array($functionName, $this->_ENC_AllReadyFunc)) {
									$functionName = substr('bf' . md5(uniqid()), 0, rand(10, 32));
								}
							}
							$this->_ENC_AllReadyFunc[] = $functionName;
							$c                         = rand(0, strlen($string) - 2 - $i);
							if ($c > 0) {
								$char          = substr($string, $i, $c);
								$i            += $c - 1;
								$functiontext  = 'function ' . $functionName . '(){return "' . $this->EncodeJsString($char, $level + 1) . '";};';
								$char          = '"+' . $functionName . '()+"';
								$this->AddToShuffle($functiontext);
							}
						}
					}
					$i++;
					$result .= $char;
				}
			}
			return $result;
		}


		/**********************/
		private function addCryptWord($str)
		{
			$val = $str;
			if (!empty($this->_ENC_string[$str])) {
				return;
			}
			if ($this->_ENC_setting['encode']) {
				$val = 't' . substr(md5(uniqid()), 0, rand(10, 32));
				if ((is_array($this->_ENC_string)) && (count($this->_ENC_string) > 0)) {
					while (in_array($val, $this->_ENC_string)) {
						$val = substr(md5(uniqid()), 0, rand(10, 32));
					}
				}
			}
			$this->_ENC_string[$str] = $val;
		}
		/**********************/
		private function T($str)
		{
			return $this->getCryptWord($str);
		}
		private function getCryptWord($str)
		{
			if (empty($this->_ENC_string[$str])) {
				$this->addCryptWord($str);
			}
			return $this->_ENC_string[$str];
		}
		/**********************/
		private function str_replace_once($search, $replace, $text)
		{
			$pos = strpos($text, $search);
			return $pos !== false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
		}

		private function json_decode($string)
		{
			$result = @json_decode($string, true);
			if (json_last_error() === JSON_ERROR_NONE) {
				return $result;
			} else {
				return false;
			}
		}

		private function curl($url, $postdata = [])
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($this->_ENC_setting['debug']) {
				$this->log($url . "\t" . $httpcode);
				$this->log($response);
			}

			if ($httpcode !== 200) {
				return false;
			}
			return $response;
		}

		private function log($text)
		{
			if (is_array($text)) {
				$text = "\n" . print_r($text, true);
			}

			$trace    = debug_backtrace();
			$class    = $trace[0]["class"];
			$function = $trace[0]["function"];
			$line     = $trace[0]["line"];

			$text =
				date('Y.m.d H:i:s') .
				"\t" .
				$class . '->' . $function . ':' . $line . "\n"
				. $text . "\n";

			if ($this->_ENC_setting['debug_to_file']) {
				file_put_contents(
					__DIR__ . '/enc.log',
					$text,
					FILE_APPEND
				);
			} elseif ($this->_ENC_setting['debug_to_global_array']) {
				$GLOBALS['ENC_LOG'][] = $text;
			} else {
				echo '<pre>' . $text . '</pre>';
			}
		}
	}
}