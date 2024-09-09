(function () {
    'use strict';

    var url = (document.currentScript && document.currentScript.dataset && document.currentScript.dataset.reportUrl) ||
        ((window.BASE_URL || '') + '/rest/V1/fredden/javascript-error-reporting');

    var sendDelay = 0;
    var sendQueue = [];
    var sendTimer;

    var sendError = function () {
        var xhr;

        window.clearTimeout(sendTimer);

        if (!sendQueue.length) {
            return;
        }

        xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify(sendQueue.shift()));

        sendDelay += 10;
        sendTimer = window.setTimeout(sendError, sendDelay);
    };


    
    // Override console.error method so these errors are also logged

    var originalConsole = {
        error: console.error
    };


    function logToQueue(type, args) {
        if (sendQueue.length > 100) {
            // That's a lot of errors! Let's not overwhelm the server with more.
            return;
        }
        if (!sendQueue.length) {
            sendTimer = window.setTimeout(sendError, sendDelay);
        }
        sendQueue.push({
            browser: {
                height: window.innerHeight,
                url: window.location.href,
                width: window.innerWidth,
            },
            event: {
                type: type,
                message: Array.from(args).join(' '),
                timer: (performance.now() / 1000).toFixed(2),
            },
        });
    }

    console.error = function() {
        logToQueue('error', arguments);
        originalConsole.error.apply(console, arguments);
    };

    
    window.addEventListener('error', function (event) {
        if (!event) {
            return;
        }

        if (sendQueue.length > 100) {
            // That's a lot of errors! Let's not overwhelm the server with more.
            return;
        }

        if (!sendQueue.length) {
            sendTimer = window.setTimeout(sendError, sendDelay);
        }

        sendQueue.push({
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
        });
    });
}());
