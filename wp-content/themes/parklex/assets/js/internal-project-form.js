/* global Muuri, bisInternalProjectForm */
( function () {
	var MAX_FILE_SIZE = 2 * 1024 * 1024; // ~2MB, matches the original uploader's limit.

	function getImagesIdsInput() {
		return document.querySelector( '.bis-internal-project-images-ids input[type="text"]' );
	}

	function uploadFile( file ) {
		var formData = new FormData();
		formData.append( 'action', bisInternalProjectForm.action );
		formData.append( 'nonce', bisInternalProjectForm.nonce );
		formData.append( 'file', file );

		return fetch( bisInternalProjectForm.ajaxUrl, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin',
		} ).then( function ( response ) {
			return response.json();
		} );
	}

	function setSlotImage( item, id, url ) {
		var img = item.querySelector( 'img' );

		img.src = url;
		img.hidden = false;
		item.dataset.attachId = id;
		item.classList.add( 'has-image' );

		if ( ! item.querySelector( '[data-role="remove-image"]' ) ) {
			var removeButton = document.createElement( 'button' );
			removeButton.type = 'button';
			removeButton.className = 'c-internal-project-form__remove';
			removeButton.dataset.role = 'remove-image';
			removeButton.textContent = '×';
			item.appendChild( removeButton );
		}
	}

	function clearSlot( item ) {
		var img = item.querySelector( 'img' );

		img.src = '';
		img.hidden = true;
		delete item.dataset.attachId;
		item.classList.remove( 'has-image' );

		var removeButton = item.querySelector( '[data-role="remove-image"]' );
		if ( removeButton ) {
			removeButton.remove();
		}

		var input = item.querySelector( 'input[type="file"]' );
		if ( input ) {
			input.value = '';
		}
	}

	function uploadIntoSlot( item, file, onDone ) {
		if ( file.size > MAX_FILE_SIZE ) {
			window.alert( 'Image size must be less than 2MB. Image "' + file.name + '" cannot be uploaded.' );
			return;
		}

		item.classList.add( 'is-uploading' );

		uploadFile( file ).then( function ( response ) {
			item.classList.remove( 'is-uploading' );

			if ( ! response.success ) {
				window.alert( ( response.data && response.data.message ) || 'Upload failed.' );
				return;
			}

			setSlotImage( item, response.data.id, response.data.url );
			onDone();
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var gridEl = document.querySelector( '[data-role="gallery-grid"]' );
		var featuredZone = document.querySelector( '[data-role="featured-drop"]' );

		if ( ! gridEl || 'undefined' === typeof Muuri ) {
			return;
		}

		var grid = new Muuri( gridEl, {
			dragEnabled: true,
			dragSortHeuristics: { sortInterval: 0 },
		} );

		function syncImagesIdsField() {
			var input = getImagesIdsInput();

			if ( ! input ) {
				return;
			}

			var ids = [];

			if ( featuredZone && featuredZone.dataset.attachId ) {
				ids.push( featuredZone.dataset.attachId );
			}

			grid.getItems().forEach( function ( muuriItem ) {
				var element = muuriItem.getElement();

				if ( element.dataset.attachId ) {
					ids.push( element.dataset.attachId );
				}
			} );

			input.value = ids.length ? ids.join( ',' ) : 'none';
		}

		/**
		 * Selecting several files in one file input (they're all `multiple`) distributes
		 * them across every currently empty slot — featured first, then gallery slots in
		 * their current (drag-reordered) order — same as the original uploader.
		 */
		function distributeFiles( files ) {
			var emptySlots = [];

			if ( featuredZone && ! featuredZone.classList.contains( 'has-image' ) ) {
				emptySlots.push( featuredZone );
			}

			grid.getItems().forEach( function ( muuriItem ) {
				var element = muuriItem.getElement();

				if ( ! element.classList.contains( 'has-image' ) ) {
					emptySlots.push( element );
				}
			} );

			Array.prototype.slice.call( files, 0, emptySlots.length ).forEach( function ( file, index ) {
				uploadIntoSlot( emptySlots[ index ], file, syncImagesIdsField );
			} );
		}

		if ( featuredZone ) {
			var featuredInput = featuredZone.querySelector( '[data-role="featured-input"]' );
			featuredInput.addEventListener( 'change', function () {
				distributeFiles( featuredInput.files );
				featuredInput.value = '';
			} );
		}

		gridEl.querySelectorAll( '[data-role="gallery-input"]' ).forEach( function ( input ) {
			input.addEventListener( 'change', function () {
				distributeFiles( input.files );
				input.value = '';
			} );
		} );

		grid.on( 'dragReleaseEnd', syncImagesIdsField );

		document.addEventListener( 'click', function ( event ) {
			var removeButton = event.target.closest( '[data-role="remove-image"]' );

			if ( ! removeButton ) {
				return;
			}

			clearSlot( removeButton.closest( '[data-role="featured-drop"], [data-role="gallery-item"]' ) );
			syncImagesIdsField();
		} );

		var form = gridEl.closest( 'form' );
		if ( form ) {
			form.addEventListener( 'submit', syncImagesIdsField );
		}

		syncImagesIdsField();
	} );
} )();
