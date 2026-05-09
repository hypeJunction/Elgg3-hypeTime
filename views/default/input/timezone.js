import $ from 'jquery';
import Ajax from 'elgg/Ajax';

const ajax = new Ajax();

const cache = [];

function setOptions(self, options) {
	const options_ = options || [];
	const $parent = self.parents('.elgg-input-timezone').eq(0);
	const $tzIdPicker = $parent.find('select[data-timezone-id]').eq(0);

	$tzIdPicker.children('option').not(':selected').remove();
	$.each(options_, function (index, tz) {
		if ($tzIdPicker.find('[value="' + tz.id + '"]').length === 0) {
			const $option = $('<option>').attr({value: tz.id}).text(tz.label);
			$option.appendTo($tzIdPicker);
		}
	});
}

$(document).on('change', '.elgg-input-timezone select[data-timezone-country]', function () {
	const self = $(this);
	const country = self.val();

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
