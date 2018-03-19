define(function (require) {

	var Ajax = require('elgg/Ajax');
	var ajax = new Ajax();

	var cache = [];

	function setOptions(self, options) {
		var options = options || [];
		var $parent = self.parents('.elgg-input-timezone').eq(0);
		var $tzIdPicker = $parent.find('select[data-timezone-id]').eq(0);

		$tzIdPicker.children('option').not(':selected').remove();
		$.each(options, function (index, tz) {
			if ($tzIdPicker.find('[value="' + tz.id + '"]').length === 0) {
				var $option = $('<option>').attr({value: tz.id}).text(tz.label);
				$option.appendTo($tzIdPicker);
			}
		});
	}

	$(document).on('change', '.elgg-input-timezone select[data-timezone-country]', function () {
		var self = $(this);
		var country = self.val();

		if (cache[country]) {
			setOptions(self, cache[country]);
		} else {
			ajax.path('data/timezones', {
				data: {
					country: country
				},
				cache: true
			}).done(function (data) {
				cache[country] = data;
				setOptions(self, data);
			});
		}
	});

});

