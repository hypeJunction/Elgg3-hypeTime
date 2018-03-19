<?php

$user = elgg_get_page_owner_entity();

if (!$user instanceof ElggUser) {
	return;
}

$dt = new DateTime();

$content = elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('user:settings:format:time'),
	'name' => 'format_time',
	'value' => elgg_get_plugin_user_setting('format:time', $user->guid, 'hypeTime'),
	'options_values' => [
		'H:i' => $dt->format('H:i'),
		'h:ia' => $dt->format('h:ia'),
	],
]);

$content .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('user:settings:format:date'),
	'name' => 'format_date',
	'value' => elgg_get_plugin_user_setting('format:date', $user->guid, 'hypeTime'),
	'options_values' => [
		'M j, Y' => $dt->format('M j, Y'),
		'Y/m/j' => $dt->format('Y/m/j'),
		'Y-m-j' => $dt->format('Y-m-j'),
		'm/j/Y' => $dt->format('m/j/Y'),
		'm-j-Y' => $dt->format('m-j-Y'),
		'j/m/Y' => $dt->format('j/m/Y'),
		'j-m-Y' => $dt->format('j-m-Y'),
		'j.m.Y' => $dt->format('j.m.Y'),
	],
]);

$content .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('user:settings:week:starts'),
	'name' => 'week_starts',
	'value' => elgg_get_plugin_user_setting('week:starts', $user->guid, 'hypeTime'),
	'options_values' => [
		\hypeJunction\Time::MONDAY => elgg_echo('date:weekday:1'),
		\hypeJunction\Time::SUNDAY => elgg_echo('date:weekday:0'),
	],
]);

$content .= elgg_view_field([
	'#type' => 'timezone',
	'#label' => elgg_echo('user:settings:timezone'),
	'name' => 'timezone',
	'value' => elgg_get_plugin_user_setting('timezone', $user->guid, 'hypeTime'),
]);

$title = elgg_echo('user:settings:heading:time');
echo elgg_view_module('info', $title, $content);
