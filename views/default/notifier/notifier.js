// <script>

/**
 * Javascript for the notifier plugin
 */
define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	/**
	 * Repositions the notifier popup
	 *
	 * @param {String} hook    'getOptions'
	 * @param {String} type    'ui.popup'
	 * @param {Object} params  An array of info about the target and source.
	 * @param {Object} options Options to pass to
	 *
	 * @return {Object}
	 */
	function popupHandler(hook, type, params, options) {
		if (!params.target.hasClass('elgg-notifier-popup')) {
			return;
		}

		// Due to elgg.ui.popup's design, there's no way to verify in a click handler whether the
		// click will actually be closing the popup after this hook. This is the only chance to verify
		// whether the popup is visible or not.
		if (params.target.is(':visible')) {
			// user clicked the icon to close, we're just letting it close
			return;
		}

		populatePopup();

		options.my = 'left top';
		options.at = 'left bottom+5px';
		options.collision = 'fit fit';
		return options;
	}

	/**
	 * Dismiss all unread notifications and then remove related UI elements.
	 *
	 * @return void
	 */
	function dismissAll() {
		// start delete on server but don't block
		elgg.action(this.href);

		// Update count
		updateUnreadCount(0);

		$('.elgg-notifier-unread').removeClass('elgg-notifier-unread');

		return false;
	}

	/**
	 * Fetch notifications and display them in the popup module.
	 *
	 * @return void
	 */
	function populatePopup() {
		var $loader = $('<div>').addClass('elgg-ajax-loader');

		elgg.action('notifier/load', {
			beforeSend: function () {
				$('#notifier-messages').html($loader);
			},
			complete: function () {
				$loader.remove();
			},
			success: function (response) {

				if (response.status !== 0) {
					return;
				}

				// Populate the list
				$('#notifier-messages').html(response.output.list);

				// Toggle the "View all" link
				if (response.output.count > 0) {
					$('#notifier-view-all').removeClass('hidden');
				} else {
					$('#notifier-view-all').addClass('hidden');
				}

				updateUnreadCount(response.output.unread);

				// Bind lightbox to the new links
				elgg.ui.lightbox.bind(".elgg-lightbox");
			}
		});
	}

	function updateUnreadCount(unread) {
		console.log(unread);
		// Toggle the "Dismiss all" icon
		if (unread > 0) {
			$('#notifier-dismiss-all').removeClass('hidden');
			$('#notifier-new').text(unread).removeClass('hidden');
		} else {
			$('#notifier-dismiss-all').addClass('hidden');
			$('#notifier-new').text(unread).addClass('hidden');
		}
	}

	$(document).ajaxSuccess(function (event, xhr, settings) {
		if (typeof xhr.responseJSON !== 'undefined' && xhr.responseJSON.counters) {
			console.log(xhr.responseJSON.counters);
			updateUnreadCount(xhr.responseJSON.counters.notifier || 0);
		}
	});

	$('#notifier-dismiss-all').on('click', dismissAll);

	elgg.register_hook_handler('getOptions', 'ui.popup', popupHandler);
});
