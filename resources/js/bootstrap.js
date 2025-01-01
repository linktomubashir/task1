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

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '98ebca8cc9f56c5d773e',
    cluster: 'ap2',
    forceTLS: true
});

window.Echo.channel('item-out-of-stock')
    .listen('.item-out-of-stock', (event) => {
            Swal.fire({
                position: 'top',
                title: 'Out of Stock!',
                text: `The item "${event.item_name}" is now out of stock!`,
                icon: 'warning',
                showCancelButton: true, 
                confirmButtonText: 'View', 
                cancelButtonText: 'Ignore', 
                allowOutsideClick: false, 
                allowEscapeKey: false,    
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/item`; 
                }
            });
    })
console.log('Pusher Key:', '98ebca8cc9f56c5d773e')
console.log('Pusher Cluster:', 'ap2');