<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\HtmlFilter;
?>
<input type="hidden" id="antibot_input<?= $sId;?>" name="option_<?= $option_name; ?>"
	value="<?= HtmlFilter::encode($option[$option_name]); ?>">

<table id="antibot<?= $sId;?>">
	<?= Loc::getMessage('ISPRO_EasyNoCaptcha_ANTIBOT_HEADER') ?>
	<?php
	$arRules   = json_decode($option[$option_name], true);
	$def_array = [
		'UA'           => '',
		'page'         => 'N',
		'js'           => 'N',
		'css'          => 'N',
		'alertCaptcha' => 'N',
	];

	if (empty($arRules['*'])) {
		$arRules['*'] = $def_array;
	}
	$arRules[''] = $def_array;
	if (is_array($arRules)) {
		foreach ($arRules as $name => $values) {


			echo '<tr data-type="antibot">';
			echo '<td><input name="name" type="text" value="' . HtmlFilter::encode($name) . '"></td>';

			foreach ($def_array as $key => $val) {
				if ($key != 'UA') {
					$values[$key] = in_array($values[$key], ['Y', 'N']) ? $values[$key] : $val;

					echo '<td><input name="' . $key . '" type="checkbox" ';
					if (!empty($values[$key]) && $values[$key] == 'Y') {
						echo 'checked';
					}
					echo '></td>';
				} else {
					$values[$key] = is_string($values[$key]) ? $values[$key] : $val;
					echo '<td><input name="UA" type="text" value="' . HtmlFilter::encode($values['UA']) . '"></td>';
				}
			}
			echo '</tr>';
		}
	}
	?>
</table>
<script>
	document.addEventListener('DOMContentLoaded', () => {
		let antibots_inputs = document.querySelectorAll('#antibot<?= $sId;?> input');
		antibots_inputs.forEach((el) => {
			el.addEventListener('change', () => {
				setAntibotValue<?= $sId;?>();
			})
		})
	})

	function setAntibotValue<?= $sId;?>() {
		console.log('setAntibotValue');
		let antibot_input = document.querySelector('#antibot_input<?= $sId;?>');
		let value = {};
		let antibots = document.querySelectorAll('#antibot<?= $sId;?> tr[data-type="antibot"]');
		antibots.forEach((elTr) => {
			let name = elTr.querySelector('input[name="name"]')?.value;
			if (name == '') {
				return;
			}
			value[name] = {};
			value[name]['UA'] = elTr.querySelector('input[name="UA"]').value;
			value[name]['page'] = elTr.querySelector('input[name="page"]').checked ? 'Y' : 'N';
			value[name]['js'] = elTr.querySelector('input[name="js"]').checked ? 'Y' : 'N';
			value[name]['css'] = elTr.querySelector('input[name="css"]').checked ? 'Y' : 'N';
			value[name]['alertCaptcha'] = elTr.querySelector('input[name="alertCaptcha"]').checked ? 'Y' : 'N';
		})
		antibot_input.value = JSON.stringify(value);
	}
</script>