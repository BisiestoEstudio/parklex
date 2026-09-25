( function () {
	'use strict';

	if ( typeof window.bisLunchLearn === 'undefined' ) {
		return;
	}

	var config = window.bisLunchLearn;

	document.addEventListener( 'DOMContentLoaded', function () {
		initRequestForm();
		initSingleRequest();
	} );

	function postForm( action, data ) {
		var body = new URLSearchParams( data );
		body.set( 'action', action );
		body.set( 'nonce', config.nonce );

		return fetch( config.ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: body.toString(),
		} ).then( function ( response ) { return response.json(); } );
	}

	function postFormData( action, formData ) {
		formData.set( 'action', action );
		formData.set( 'nonce', config.nonce );

		return fetch( config.ajaxUrl, { method: 'POST', body: formData } ).then( function ( response ) { return response.json(); } );
	}

	function showMessage( el, text, isError ) {
		if ( ! el ) {
			return;
		}
		el.hidden = false;
		el.textContent = text;
		el.classList.toggle( 'is-error', !! isError );
		el.classList.toggle( 'is-success', ! isError );
	}

	/* ---- Public request form ---- */

	function initRequestForm() {
		var form = document.getElementById( 'lunch-learn-request-form' );

		if ( ! form ) {
			return;
		}

		var typeSelect       = form.querySelector( '[data-role="type-event"]' );
		var courseSelect     = form.querySelector( '[data-role="name-course"]' );
		var nameInput         = form.querySelector( '[data-role="name-presentation"]' );
		var nameRow           = form.querySelector( '[data-role="name-presentation-row"]' );
		var courseRow         = form.querySelector( '[data-role="name-course-row"]' );
		var courseCodeInput   = form.querySelector( '[data-role="course-name-code"]' );
		var courseTextInput   = form.querySelector( '[data-role="course-name-text"]' );
		var messageEl         = form.querySelector( '[data-role="message"]' );
		var typeOfEvents      = config.typeOfEvents || [];

		typeOfEvents.forEach( function ( eventType ) {
			var option = document.createElement( 'option' );
			option.value = eventType.type;
			option.textContent = eventType.type;
			typeSelect.appendChild( option );
		} );

		function coursesForType( type ) {
			var eventType = typeOfEvents.filter( function ( item ) { return item.type === type; } )[0];
			return eventType && eventType.course_names ? eventType.course_names : [];
		}

		typeSelect.addEventListener( 'change', function () {
			var courses = coursesForType( typeSelect.value );

			courseSelect.innerHTML = '<option value=""></option>';
			courseCodeInput.value = '';
			courseTextInput.value = '';

			if ( courses.length ) {
				courses.forEach( function ( course ) {
					var option = document.createElement( 'option' );
					option.value = course.code;
					option.textContent = course.name;
					courseSelect.appendChild( option );
				} );

				nameRow.hidden = true;
				nameInput.required = false;
				nameInput.value = '';
				courseRow.hidden = false;
				courseSelect.required = true;
			} else {
				courseRow.hidden = true;
				courseSelect.required = false;
				nameRow.hidden = false;
				nameInput.required = true;
			}
		} );

		courseSelect.addEventListener( 'change', function () {
			var selectedOption = courseSelect.options[ courseSelect.selectedIndex ];
			courseCodeInput.value = courseSelect.value;
			courseTextInput.value = courseSelect.value ? selectedOption.textContent : '';
		} );

		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();

			if ( ! form.reportValidity() ) {
				return;
			}

			var submitButton = form.querySelector( 'button[type="submit"]' );
			submitButton.disabled = true;

			postForm( 'bis_lunch_learn_create_request', new FormData( form ) ).then( function ( response ) {
				submitButton.disabled = false;

				if ( response.success ) {
					showMessage( messageEl, response.data, false );
					form.querySelector( '.c-lunch-learn-form__actions' ).hidden = true;
				} else {
					showMessage( messageEl, response.data, true );
				}
			} ).catch( function () {
				submitButton.disabled = false;
				showMessage( messageEl, 'Something went wrong, please try again.', true );
			} );
		} );
	}

	/* ---- My Account: single request ---- */

	function initSingleRequest() {
		var root = document.querySelector( '[data-role="single-request"]' );

		if ( ! root ) {
			return;
		}

		var requestId  = root.getAttribute( 'data-request-id' );
		var typeEvent  = root.getAttribute( 'data-type-event' );
		var typeOfEvents = config.typeOfEvents || [];

		initAssistants( root, requestId, typeEvent, typeOfEvents );
		initInvoiceFiles( root, requestId );
	}

	function initAssistants( root, requestId, typeEvent, typeOfEvents ) {
		var rowsContainer = root.querySelector( '[data-role="assistants-rows"]' );
		var template       = root.querySelector( '[data-role="assistant-row-template"]' );

		if ( ! template ) {
			return; // Already approved: read-only, nothing to wire up.
		}

		var dataScript = root.querySelector( '[data-role="assistants-data"]' );
		var initialData = dataScript ? JSON.parse( dataScript.textContent || '[]' ) : [];

		function addRow( assistant ) {
			var row = template.content.firstElementChild.cloneNode( true );
			var certificateSelect = row.querySelector( '[data-role="certificate-select"]' );

			typeOfEvents.forEach( function ( eventType ) {
				var option = document.createElement( 'option' );
				option.value = eventType.type;
				option.textContent = eventType.type;
				certificateSelect.appendChild( option );
			} );

			if ( assistant ) {
				Object.keys( assistant ).forEach( function ( field ) {
					var input = row.querySelector( '[data-field="' + field + '"]' );
					if ( input ) {
						input.value = assistant[ field ];
					}
				} );
			}

			if ( ! certificateSelect.value ) {
				certificateSelect.value = typeEvent;
			}

			rowsContainer.appendChild( row );
		}

		if ( initialData.length ) {
			initialData.forEach( addRow );
		} else {
			addRow( null );
		}

		root.querySelector( '[data-role="add-assistant"]' ).addEventListener( 'click', function () {
			addRow( null );
		} );

		rowsContainer.addEventListener( 'click', function ( e ) {
			var removeButton = e.target.closest( '[data-role="remove-assistant"]' );
			if ( removeButton ) {
				removeButton.closest( '[data-role="assistant-row"]' ).remove();
			}
		} );

		function collectAssistants() {
			return Array.prototype.map.call( rowsContainer.querySelectorAll( '[data-role="assistant-row"]' ), function ( row ) {
				var assistant = {};
				row.querySelectorAll( '[data-field]' ).forEach( function ( field ) {
					assistant[ field.getAttribute( 'data-field' ) ] = field.value;
				} );
				return assistant;
			} );
		}

		root.collectAssistants = collectAssistants;

		var saveButton = root.querySelector( '[data-role="save-assistants"]' );

		saveButton.addEventListener( 'click', function () {
			saveButton.disabled = true;

			var body = new URLSearchParams();
			body.set( 'request_id', requestId );
			collectAssistants().forEach( function ( assistant, index ) {
				Object.keys( assistant ).forEach( function ( field ) {
					body.set( 'data[' + index + '][' + field + ']', assistant[ field ] );
				} );
			} );

			postForm( 'bis_lunch_learn_save_assistants', body ).then( function ( response ) {
				saveButton.disabled = false;
				window.alert( response.data );
			} );
		} );
	}

	function initInvoiceFiles( root, requestId ) {
		var fileList  = root.querySelector( '[data-role="file-list"]' );
		var fileInput = root.querySelector( '[data-role="invoice-file-input"]' );
		var messageEl = root.querySelector( '[data-role="message"]' );
		var sendButton = root.querySelector( '[data-role="send-invoice"]' );

		if ( fileInput ) {
			fileInput.addEventListener( 'change', function () {
				if ( ! fileInput.files.length ) {
					return;
				}

				var formData = new FormData();
				Array.prototype.forEach.call( fileInput.files, function ( file, index ) {
					formData.append( 'upl_file_' + index, file );
				} );
				formData.set( 'request_id', requestId );

				postFormData( 'bis_lunch_learn_upload_invoice_file', formData ).then( function ( response ) {
					fileInput.value = '';

					if ( response.success ) {
						fileList.innerHTML = response.data.file_list;
						showMessage( messageEl, response.data.text, false );
					} else {
						showMessage( messageEl, response.data, true );
					}
				} );
			} );
		}

		fileList.addEventListener( 'click', function ( e ) {
			var removeButton = e.target.closest( '[data-role="remove-file"]' );

			if ( ! removeButton ) {
				return;
			}

			postForm( 'bis_lunch_learn_remove_invoice_file', new URLSearchParams( {
				request_id: requestId,
				atach_id: removeButton.getAttribute( 'data-attachment-id' ),
			} ) ).then( function ( response ) {
				if ( response.success ) {
					fileList.innerHTML = response.data;
				} else {
					window.alert( response.data );
				}
			} );
		} );

		if ( sendButton ) {
			sendButton.addEventListener( 'click', function () {
				if ( ! fileList.querySelector( 'tr' ) ) {
					showMessage( messageEl, 'Upload at least one file before sending.', true );
					return;
				}

				if ( ! window.confirm( config.i18n.confirmSend ) ) {
					return;
				}

				sendButton.disabled = true;

				var body = new URLSearchParams();
				body.set( 'request_id', requestId );

				var assistants = root.collectAssistants ? root.collectAssistants() : [];
				assistants.forEach( function ( assistant, index ) {
					Object.keys( assistant ).forEach( function ( field ) {
						body.set( 'data[' + index + '][' + field + ']', assistant[ field ] );
					} );
				} );

				postForm( 'bis_lunch_learn_send_invoice_files', body ).then( function ( response ) {
					if ( response.success ) {
						window.location.reload();
					} else {
						sendButton.disabled = false;
						showMessage( messageEl, response.data, true );
					}
				} );
			} );
		}
	}
} )();
