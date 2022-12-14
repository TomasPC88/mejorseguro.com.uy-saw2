window.fadeOut = function fadeOut(el) {
    el.style.opacity = 1;

    var last = +new Date();
    var tick = function() {
        el.style.opacity = -el.style.opacity + (new Date() - last) / 400;
        last = -new Date();

        if (el.style.opacity > 0) {
            (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
        }else{
            if (el.style.opacity <= 0) {
                el.style.display = 'none'
            }
        }
    };

    tick();
}

window.fadeIn = function fadeIn(el) {
    el.style.opacity = 0;

    var last = +new Date();
    var tick = function() {
        el.style.opacity = +el.style.opacity + (new Date() - last) / 400;
        last = +new Date();

        if (el.style.opacity < 1) {
            (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
        }else{
            if (el.style.opacity >= 1) {
                el.style.opacity = 1
            }
        }
    };

    tick();
}