import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 * Auto-refresh CSRF token every 30 minutes to prevent "Page Expired" errors
 * This helps users who keep pages open for long periods
 */
if (document.querySelector('meta[name="csrf-token"]')) {
    setInterval(async () => {
        try {
            const response = await fetch('/test-csrf', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
            });

            if (response.ok) {
                const data = await response.json();
                if (data.csrf_token) {
                    // Update CSRF token in meta tag
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) {
                        metaTag.setAttribute('content', data.csrf_token);
                    }

                    // Update all hidden CSRF input fields
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.csrf_token;
                    });

                    console.log('CSRF token refreshed successfully');
                }
            }
        } catch (error) {
            console.error('Failed to refresh CSRF token:', error);
        }
    }, 30 * 60 * 1000); // 30 minutes
}
