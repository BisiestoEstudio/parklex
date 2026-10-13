// Submits the filter form as soon as one of the selects changes.
document.querySelectorAll( '.c-projects-archive__filters select' ).forEach( ( select ) => {
	select.addEventListener( 'change', () => select.form.submit() );
} );
