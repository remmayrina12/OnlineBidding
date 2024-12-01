import 'bootstrap';


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY || '79bfb3d761eb821a60c3',
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER || 'ap1',
//     wsHost: process.env.MIX_PUSHER_HOST || 'ws-ap1.pusher.com',
//     wsPort: process.env.MIX_PUSHER_PORT || 80,
//     wssPort: process.env.MIX_PUSHER_PORT || 443,
//     forceTLS: process.env.MIX_PUSHER_SCHEME === 'https',
//     enabledTransports: ['ws', 'wss'],
// });



/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
