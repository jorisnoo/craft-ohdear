export default {
    checks: {
        badgeDisabled: 'Disabled',
        bodyDisabled: 'This check is currently disabled.',
        uptime: {
            badge: {
                good: 'Site is up',
                bad: 'Site is down'
            },
            body: {
                good: 'Your site is up. We last checked {:fromNow}.',
                bad: 'Your site is down. We last checked {:fromNow}.'
            }
        },
        performance: {
            body: {
                good: 'We are collecting performance metrics every time we check your site uptime.',
                bad: 'We are collecting performance metrics every time we check your site uptime.'
            }
        },
        broken_links: {
            badge: {
                good: 'None',
                bad: 'Found'
            },
            body: {
                good: 'No broken links found. We last checked {:fromNow}.',
                bad: 'We found broken links. We last checked {:fromNow}.'
            }
        },
        mixed_content: {
            badge: {
                good: 'Secure',
                bad: 'Not secure'
            },
            body: {
                good: 'No mixed content found, your site is secure. We last checked {:fromNow}.',
                bad: 'We found mixed content, your site is insecure. We last checked {:fromNow}.'
            }
        },
        lighthouse: {
            badge: {
                good: 'Healthy',
                bad: 'Not healthy'
            },
            body: {
                good: 'Lighthouse did not report any issues. We last checked {:fromNow}.',
                bad: 'Lighthouse reported issues. We last checked {:fromNow}.'
            }
        },
        certificate_health: {
            badge: {
                good: 'Healthy',
                bad: 'Not healthy'
            },
            body: {
                good: 'Your certificate is healthy. We last checked {:fromNow}.',
                bad: 'Your certificate is unhealthy. We last checked {:fromNow}.'
            }
        },
        certificate_transparency: {
            badge: {
                good: 'Always running',
                bad: 'Always running'
            },
            body: {
                good: 'We\'ll notify you when someone issues a new certificate for your site. This check is always running.',
                bad: 'We\'ll notify you when someone issues a new certificate for your site. This check is always running.'
            }
        },
        dns: {
            badge: {
                good: 'good',
                bad: 'bad',
            },
            body: {
                good: 'DNS records found. We last checked {:fromNow}.',
                bad: 'bad',
            }
        },
        cron: {
            badge: {
                good: 'good',
                bad: 'bad',
                empty: 'empty',
            },
            body: {
                good: 'good',
                bad: 'bad',
            }
        },
        application_health: {
            badge: {
                good: 'Healthy',
                bad: 'Not healthy',
            },
            body: {
                good: 'Your application seems to be healthy. We last checked {:fromNow}.',
                bad: 'We detected some problems with your application. We last checked {:fromNow}.',
            }
        }
    }
}
