/* global stbConfig */
( function () {
    'use strict';

    const btn       = document.getElementById( 'stb-btn' );
    const threshold = ( stbConfig && stbConfig.threshold ) ? parseInt( stbConfig.threshold, 10 ) : 300;

    if ( ! btn ) { return; }

    // Show / hide on scroll using IntersectionObserver for performance.
    // We observe a tiny sentinel element placed at the threshold offset.
    const sentinel = document.createElement( 'div' );
    sentinel.style.cssText = 'position:absolute;top:' + threshold + 'px;left:0;width:1px;height:1px;pointer-events:none;';
    document.body.prepend( sentinel );

    const observer = new IntersectionObserver(
        function ( entries ) {
            const past = ! entries[0].isIntersecting;
            btn.hidden = ! past;
            // rAF lets the browser remove hidden before adding the class.
            requestAnimationFrame( function () {
                btn.classList.toggle( 'stb-visible', past );
            } );
        },
        { threshold: 0 }
    );
    observer.observe( sentinel );

    btn.addEventListener( 'click', function () {
        window.scrollTo( { top: 0, behavior: 'smooth' } );
        btn.blur();
    } );
}() );
