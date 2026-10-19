/**
 * Lohnour Program CMS Admin Scripts
 */
jQuery(document).ready(function($) {

	// 1. Theme-Aware Media Uploaders
	$('.lohnour-upload-btn').on('click', function(e) {
		e.preventDefault();
		var $btn = $(this);
		var $box = $btn.closest('.lohnour-media-box');
		var $idInput = $box.find('.lohnour-img-id');
		var $preview = $box.find('.lohnour-img-preview');
		var $removeBtn = $box.find('.lohnour-remove-img-btn');

		var frame = wp.media({
			title: lohnourProgramAdmin.chooseImage || 'Choose Image',
			button: {
				text: lohnourProgramAdmin.useImage || 'Use This Image'
			},
			multiple: false
		});

		frame.on('select', function() {
			var attachment = frame.state().get('selection').first().toJSON();
			$idInput.val(attachment.id);
			$preview.attr('src', attachment.url);
			$removeBtn.show();
		});

		frame.open();
	});

	$('.lohnour-remove-img-btn').on('click', function(e) {
		e.preventDefault();
		var $btn = $(this);
		var $box = $btn.closest('.lohnour-media-box');
		var $idInput = $box.find('.lohnour-img-id');
		var $preview = $box.find('.lohnour-img-preview');

		$idInput.val('0');
		$preview.attr('src', '');
		$btn.hide();
	});

	// 2. Sortable Lists
	$('#lohnour-modules-list').sortable({
		handle: '.lohnour-drag-handle',
		placeholder: 'lohnour-sortable-placeholder',
		opacity: 0.7,
		update: function() {
			renumberModules();
		}
	});

	$('#lohnour-faqs-list').sortable({
		handle: '.lohnour-drag-handle',
		placeholder: 'lohnour-sortable-placeholder',
		opacity: 0.7
	});

	$('#lohnour-highlights-list').sortable({
		handle: '.lohnour-drag-handle',
		placeholder: 'lohnour-sortable-placeholder',
		opacity: 0.7
	});

	// Live update highlight title in card header
	$(document).on('input', '.lohnour-highlight-title-input', function() {
		var val = $(this).val();
		var $card = $(this).closest('.lohnour-highlight-card');
		$card.find('.highlight-header-title').text(val ? val : '(New Highlight)');
	});

	// Add Highlight
	$('#lohnour-add-highlight-btn').on('click', function(e) {
		e.preventDefault();
		var count = $('#lohnour-highlights-list .lohnour-highlight-card').length;
		var tmpl = $('#tmpl-lohnour-highlight-item').html();
		tmpl = tmpl.replace(/\{\{INDEX\}\}/g, count);

		var $newRow = $(tmpl);
		$('#lohnour-highlights-list').append($newRow);
		$newRow.find('.lohnour-highlight-title-input').focus();
	});

	// Remove Highlight
	$(document).on('click', '.lohnour-remove-highlight-btn', function(e) {
		e.preventDefault();
		e.stopPropagation();
		if (confirm('Remove this highlight?')) {
			$(this).closest('.lohnour-highlight-card').slideUp(200, function() {
				$(this).remove();
			});
		}
	});

	// 3. Module Expand / Collapse Toggle
	$(document).on('click', '.lohnour-module-card-header, .lohnour-toggle-module-btn', function(e) {
		if ($(e.target).hasClass('lohnour-remove-module-btn') || $(e.target).hasClass('lohnour-drag-handle')) {
			return;
		}
		var $card = $(this).closest('.lohnour-module-card');
		var $body = $card.find('.lohnour-module-card-body');
		$body.slideToggle(200);
	});

	// 4. Live update title and number in module header
	$(document).on('input', '.lohnour-mod-title-input', function() {
		var val = $(this).val();
		var $card = $(this).closest('.lohnour-module-card');
		$card.find('.module-title-display').text(val ? val : '(Untitled Module)');
	});

	$(document).on('input', '.lohnour-mod-num-input', function() {
		var val = $(this).val();
		var $card = $(this).closest('.lohnour-module-card');
		$card.find('.module-badge-display').text(val);
	});

	$(document).on('input', '.lohnour-mod-duration-input', function() {
		var val = $(this).val();
		var $card = $(this).closest('.lohnour-module-card');
		$card.find('.module-duration-display').text(val);
	});

	// 5. Add Module
	$('#lohnour-add-module-btn').on('click', function(e) {
		e.preventDefault();
		var count = $('#lohnour-modules-list .lohnour-module-card').length;
		var nextNum = count + 1;
		var nextNumPadded = nextNum < 10 ? '0' + nextNum : nextNum.toString();

		var tmpl = $('#tmpl-lohnour-module-item').html();
		tmpl = tmpl.replace(/\{\{INDEX\}\}/g, count);
		tmpl = tmpl.replace(/\{\{NUM\}\}/g, nextNumPadded);
		tmpl = tmpl.replace(/\{\{NUM_INT\}\}/g, nextNum);

		var $newRow = $(tmpl);
		$('#lohnour-modules-list').append($newRow);
		$newRow.find('.lohnour-module-card-body').show();
		$newRow.find('.lohnour-mod-title-input').focus();
	});

	// 6. Remove Module
	$(document).on('click', '.lohnour-remove-module-btn', function(e) {
		e.preventDefault();
		e.stopPropagation();
		if (confirm(lohnourProgramAdmin.confirmRemoveModule || 'Remove this module?')) {
			$(this).closest('.lohnour-module-card').slideUp(200, function() {
				$(this).remove();
				renumberModules();
			});
		}
	});

	function renumberModules() {
		$('#lohnour-modules-list .lohnour-module-card').each(function(idx) {
			var num = idx + 1;
			var padded = num < 10 ? '0' + num : num.toString();
			$(this).find('.lohnour-mod-num-input').val(padded);
			$(this).find('.module-badge-display').text(padded);
		});
	}

	// 7. FAQ Expand / Collapse
	$(document).on('click', '.lohnour-faq-card-header, .lohnour-toggle-faq-btn', function(e) {
		if ($(e.target).hasClass('lohnour-remove-faq-btn') || $(e.target).hasClass('lohnour-drag-handle')) {
			return;
		}
		var $card = $(this).closest('.lohnour-faq-card');
		$card.find('.lohnour-faq-card-body').slideToggle(200);
	});

	// Live update FAQ title
	$(document).on('input', '.lohnour-faq-question-input', function() {
		var val = $(this).val();
		var $card = $(this).closest('.lohnour-faq-card');
		$card.find('.faq-header-title').text(val ? val : '(New FAQ Item)');
	});

	// Add FAQ
	$('#lohnour-add-faq-btn').on('click', function(e) {
		e.preventDefault();
		var count = $('#lohnour-faqs-list .lohnour-faq-card').length;
		var tmpl = $('#tmpl-lohnour-faq-item').html();
		tmpl = tmpl.replace(/\{\{INDEX\}\}/g, count);

		var $newRow = $(tmpl);
		$('#lohnour-faqs-list').append($newRow);
		$newRow.find('.lohnour-faq-card-body').show();
		$newRow.find('.lohnour-faq-question-input').focus();
	});

	// Remove FAQ
	$(document).on('click', '.lohnour-remove-faq-btn', function(e) {
		e.preventDefault();
		e.stopPropagation();
		if (confirm(lohnourProgramAdmin.confirmRemoveFAQ || 'Remove this FAQ?')) {
			$(this).closest('.lohnour-faq-card').slideUp(200, function() {
				$(this).remove();
			});
		}
	});

});
