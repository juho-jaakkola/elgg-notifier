// <script>

elgg.provide('elgg.notifier.admin');

elgg.notifier.admin.enableForBatch = function(offset, operation) {

	var options = {
		data: {
			offset: offset
		},
		dataType: 'json'
	};

	options.data = elgg.security.addToken(options.data);
	options.success = function(json) {
		if (json.output.count) {
			var oldValue = $('#notifier-progressbar-' + operation).progressbar("value");
			var newValue = oldValue + offset;

			elgg.notifier.admin.enableForBatch(offset + 10, operation);
		} else {
			newValue = 100;
		}

		$('#notifier-progressbar-' + operation).progressbar({value: newValue});
	};

	var action = 'action/notifier/admin/enable_' + operation;
	elgg.post(action, options);
}

elgg.notifier.admin.enable = function(e) {
	elgg.notifier.admin.enableForBatch(0, this.dataset.operation);
}

elgg.notifier.admin.init = function() {
	$('#notifier-enable-personal').live('click', elgg.notifier.admin.enable);
	$('#notifier-enable-collections').live('click', elgg.notifier.admin.enable);
	$('#notifier-enable-groups').live('click', elgg.notifier.admin.enable);

	$('.elgg-notifier-progressbar').each(function (key, value) {
		$(this).progressbar({
			value: 0,
			total: this.dataset.total
		});
	});
};

elgg.register_hook_handler('init', 'system', elgg.notifier.admin.init);