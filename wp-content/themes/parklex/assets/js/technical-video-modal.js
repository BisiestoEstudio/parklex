( function () {
	var modal = document.querySelector( '[data-technical-video-modal]' );

	if ( ! modal ) {
		return;
	}

	var iframe = modal.querySelector( '[data-technical-video-modal-iframe]' );

	function getVimeoEmbedUrl( url ) {
		var match = url.match( /vimeo\.com\/(?:video\/)?(\d+)/ );

		if ( ! match ) {
			return '';
		}

		return 'https://player.vimeo.com/video/' + match[ 1 ] + '?autoplay=1';
	}

	function openModal( vimeoUrl ) {
		var embedUrl = getVimeoEmbedUrl( vimeoUrl );

		if ( ! embedUrl ) {
			return;
		}

		iframe.src = embedUrl;
		modal.hidden = false;
		document.body.classList.add( 'has-technical-video-modal-open' );
	}

	function closeModal() {
		modal.hidden = true;
		iframe.src = '';
		document.body.classList.remove( 'has-technical-video-modal-open' );
	}

	document.addEventListener( 'click', function ( event ) {
		var trigger = event.target.closest( '.js-technical-video-trigger' );

		if ( trigger ) {
			event.preventDefault();
			openModal( trigger.dataset.vimeoUrl );
			return;
		}

		if ( event.target.closest( '[data-technical-video-modal-close]' ) ) {
			closeModal();
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && ! modal.hidden ) {
			closeModal();
		}
	} );
} )();
