(function () {
	'use strict';

	function uniqueIndex() {
		return 'n' + Date.now().toString(36) + Math.floor(Math.random() * 1000);
	}

	document.addEventListener('click', function (e) {
		var addBtn = e.target.closest('.rafiki-repeater-add');
		if (addBtn) {
			e.preventDefault();
			var repeater = addBtn.closest('.rafiki-repeater');
			var template = repeater.querySelector('.rafiki-repeater-template');
			var rows = repeater.querySelector('.rafiki-repeater-rows');
			var clone = template.content.cloneNode(true);
			var index = uniqueIndex();

			clone.querySelectorAll('[name*="__INDEX__"]').forEach(function (el) {
				el.name = el.name.replace('__INDEX__', index);
			});
			rows.appendChild(clone);
			return;
		}

		var removeBtn = e.target.closest('.rafiki-repeater-remove');
		if (removeBtn) {
			e.preventDefault();
			removeBtn.closest('.rafiki-repeater-row').remove();
			return;
		}

		var selectBtn = e.target.closest('.rafiki-image-select');
		if (selectBtn) {
			e.preventDefault();
			var field = selectBtn.closest('.rafiki-image-field');
			var frame = wp.media({ title: 'Elegir imagen', multiple: false, library: { type: 'image' } });
			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				var preview = field.querySelector('.rafiki-image-preview');
				var value = field.querySelector('.rafiki-image-value');
				var clearBtn = field.querySelector('.rafiki-image-clear');
				var src = (attachment.sizes && attachment.sizes.medium) ? attachment.sizes.medium.url : attachment.url;
				preview.src = src;
				preview.style.display = 'block';
				value.value = attachment.id;
				clearBtn.style.display = 'inline-block';
			});
			frame.open();
			return;
		}

		var clearBtn2 = e.target.closest('.rafiki-image-clear');
		if (clearBtn2) {
			e.preventDefault();
			var field2 = clearBtn2.closest('.rafiki-image-field');
			field2.querySelector('.rafiki-image-preview').style.display = 'none';
			field2.querySelector('.rafiki-image-value').value = '';
			clearBtn2.style.display = 'none';
			return;
		}
	});
})();
