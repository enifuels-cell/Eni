
# Investment/Deposit Platform

## Overview

A robust, secure, and enterprise-level investment/deposit platform built with Laravel. This platform enables users to purchase investment packages, earn daily interest, receive referral bonuses, apply for franchise opportunities, and manage their finances with a corporate-grade user experience.

## Key Features

- Investment packages with multiple tiers and returns
- Automated daily interest calculation and crediting
- Multi-level referral and bonus system
- Payment and banking integration (including QR code payments)
- Administrative dashboard for approvals, analytics, and management
- User account, profile, and bank details management
- Real-time notifications and secure authentication

## Security & Best Practices

- Follows Laravel security best practices (CSRF, XSS, SQL Injection protection)
- Uses Laravel's built-in authentication and authorization
- Follows clean code and SOLID principles
- Modular, scalable, and maintainable architecture

## Getting Started

1. Clone the repository or copy the project files
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your environment variables
4. Run `php artisan key:generate`
5. Run `php artisan migrate`
6. Start the development server: `php artisan serve`

## Next Steps

- Implement core database models and migrations
- Build investment, referral, and payment modules
- Develop admin and user dashboards
- Integrate notification and reporting systems

---

For detailed setup and customization, see `.github/copilot-instructions.md`.

## Email Verification Flow

The application uses Laravel's verification workflow with enhancements:

- `User` now implements `MustVerifyEmail` and sends a queued `CustomVerifyEmail` notification.
- Verification links are valid for 24 hours (custom expiration) and are signed to prevent tampering.
- Post-verification the user is redirected to the dashboard with `?verified=1` so the UI can display a success notice.
- `HandleUserVerified` listens for the `Verified` event and logs the verification (extensible for onboarding actions).
- A feature test `tests/Feature/EmailVerificationTest.php` covers the basic happy path.

Resending Verification:
`POST /email/verification-notification` (authenticated) is throttled (`throttle:6,1`).

To customize further (e.g., bonus credits on verification), extend the `HandleUserVerified` listener.

