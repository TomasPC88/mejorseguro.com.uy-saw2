document.addEventListener("DOMContentLoaded", function () {

    let portadas = document.querySelector('.portadas');
    let preloader = document.querySelector('.wo-slide-overlay');

    if( portadas !== null ) {
        portadas.classList.remove('hidden');
        portadas.offsetHeight;

        portadas = new Gallery('.portadas', {
            cellSelector: '.slide-cell',
            pageDots: false,
            percentPosition: false,
            lazyLoad: 1,
            wrapAround: true,
            autoPlay: 4000,
            pauseAutoPlayOnHover: false,
            adaptiveHeight: true,
            prevNextButtons: false
        });

        // Arregla el bug del margen
        portadas.on('settle', function(){
            fadeOut(preloader);
            portadas.resize()
        });

        window.addEventListener('resize',function(){
            portadas.resize()
        });
    }
});
