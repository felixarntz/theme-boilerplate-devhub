( function() {

	function syntaxHighlight() {
		var collapsedHeight = 196;

		if ( window.SyntaxHighlighter ) {
			window.SyntaxHighlighter.highlight();
		}

		Array.from( document.querySelectorAll( '.source-content' ) ).forEach( function( sourceContent ) {
			var container = sourceContent.querySelector( '.source-code-container' );
			var showMore  = sourceContent.querySelector( '.show-complete-source' );
			var showLess  = sourceContent.querySelector( '.less-complete-source' );

			function listenMoreClick() {
				container.style.setProperty( 'height', 'auto' );
				showMore.style.setProperty( 'display', 'none' );
				showLess.style.setProperty( 'display', 'inline-block' );
			}

			function listenLessClick() {
				container.style.setProperty( 'height', '' + collapsedHeight + 'px' );
				showMore.style.setProperty( 'display', 'inline-block' );
				showLess.style.setProperty( 'display', 'none' );
			}

			if ( container.clientHeight > collapsedHeight ) {
				container.style.setProperty( 'height', '' + collapsedHeight + 'px' );
				showMore.style.setProperty( 'display', 'inline-block' );

				showMore.addEventListener( 'click', listenMoreClick );
				showLess.addEventListener( 'click', listenLessClick );
			}
		});
	}

	syntaxHighlight();
})();
