<?php

namespace hypeJunction\Time;

use Elgg\IntegrationTestCase;

class BootstrapTest extends IntegrationTestCase {

	public function getPluginID(): string {
		return 'hypetime';
	}

	public function up(): void {}

	public function down(): void {}

	public function testPluginIsActive(): void {
		$plugin = elgg_get_plugin_from_id('hypetime');
		$this->assertInstanceOf(\ElggPlugin::class, $plugin);
		$this->assertTrue($plugin->isActive());
	}

	public function testBootstrapSetsDateFormatConfig(): void {
		$this->assertNotEmpty(elgg_get_config('date_format'));
	}

	public function testBootstrapSetsTimeFormatConfig(): void {
		$this->assertNotEmpty(elgg_get_config('time_format'));
	}

	public function testBootstrapSetsDatepickerFormatConfig(): void {
		$this->assertNotEmpty(elgg_get_config('date_format_datepicker'));
	}

	public function testDefaultDateFormatMatchesPluginSetting(): void {
		$plugin = elgg_get_plugin_from_id('hypetime');
		$expected = $plugin->getSetting('format:date');
		$this->assertEquals($expected, elgg_get_config('date_format'));
	}
}
