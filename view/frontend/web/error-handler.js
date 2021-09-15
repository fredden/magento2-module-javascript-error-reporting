(function () {
    'use strict';

    window.addEventListener('error', function (event) {
        var url = (window.BASE_URL || '') + '/rest/V1/fredden/javascript-error-reporting';
        var xhr = new XMLHttpRequest();

        if (!event) {
            return;
        }

        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify({
            browser: {
                height: window.innerHeight,
                url: window.location.href,
                width: window.innerWidth,
            },
            event: {
                colno: event.colno,
                filename: event.filename,
                lineno: event.lineno,
                message: event.message,
                stack: event.error && event.error.stack,
                timer: (performance.now() / 1000).toFixed(2),
            },
        }));
    });
}());
