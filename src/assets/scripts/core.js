/*
 * Copyright 2020 The Ranger
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

const dos_message = jQuery('#dos__message');
const dos_test_connection = jQuery('.dos__test__connection');
const dos_sync = jQuery('.dos__sync');
const dos_sync_log = jQuery('#dos_sync_log');

jQuery(function () {

	// check connection button
	dos_test_connection.on('click', function () {
		const data = {
			dos_key: jQuery('input[name=dos_key]').val(),
			dos_secret: jQuery('input[name=dos_secret]').val(),
			dos_endpoint: jQuery('input[name=dos_endpoint]').val(),
			dos_prefix: jQuery('input[name=dos_prefix]').val(),
			action: 'dos_test_connection'
		};

		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: data,
			dataType: 'html'
		}).done(function (res) {
			dos_message.show();
			dos_message.html(res);
			jQuery('html,body').animate({scrollTop: 0}, 1000)
		})

	});

	dos_sync.on("click", function () {
		let pos = 0;

		dos_message.html("<p>Synchronizing...</p>");
		dos_message.show();

		jQuery.ajax({
			type: "post",
			url: ajaxurl,
			data: {
				action: "dos_sync"
			},
			xhrFields: {
				onprogress: function (e) {
					dos_message.html(e.currentTarget.response.slice(pos));
					pos = e.currentTarget.response.length;
					dos_message.animate({scrollTop: 1000}, 500);
				}
			}
		}).done(function (data) {
			dos_message.html(dos_message.html() + "<p><b>Synchronization completed</b></p>");
		}).fail(function (data) {
			dos_message.html(dos_message.html() + "<p><b>Synchronization failed:</b> " + data + "</p>");
		});
	});
});
