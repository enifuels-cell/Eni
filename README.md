
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

## Enterprise Hardening & Recent Additions

The platform has been reinforced with several enterprise-grade capabilities focused on integrity, security, observability, and financial precision.

### Monetary Precision (Money Value Object)

All monetary columns now use an immutable `Money` value object (stored internally as integer minor units). This eliminates floating point drift.

Usage example:

```php
$amount = Money::fromDecimal('1234.56');   // 123456 minor units
$net    = $amount->subtract(Money::fromDecimal('34.56'));
echo $net->format(); // 1,200.00 (localized formatting via helper)
```

Casting: Models (e.g. `Investment`, `Transaction`, `DailyInterestLog`, `ReferralBonus`) apply a `MoneyCast` so attributes transparently hydrate to `Money` instances.

When adding a new monetary column:

1. Create the migration column as an integer (e.g. `amount BIGINT` or `unsignedBigInteger`).
2. Add the attribute to the model `$casts` array with `MoneyCast::class`.
3. Always manipulate using the value object (never raw integers in controllers/services where possible).

### Security Headers Middleware

Global middleware adds strict security headers:

- Content-Security-Policy (default restrictive; extend as needed)
- X-Frame-Options: DENY
- X-Content-Type-Options: nosniff
- Referrer-Policy: no-referrer
- Permissions-Policy: disables sensitive browser APIs

To extend CSP (e.g. allow a new script host), edit `app/Http/Middleware/SecurityHeaders.php` and append to the policy directives.

### Audit Trail (Activity Logging)

Every sensitive event can be centrally logged using `AuditLogger` which writes to the `activity_logs` table (plus standard log channel fallback).

Example:

```php
AuditLogger::log(
    action: 'investment.created',
    user: auth()->user(),
    subject: $investment,          // Optional: any model
    meta: ['amount' => $investment->amount->toDecimalString()]
);
```

Currently instrumented events include:

- `user.verified_email`
- `receipt.unauthorized_access`

Add more by calling the logger in services, listeners, or controllers handling business actions.

### Receipt Security & Diagnostics

Receipt access hardened via:

- Path normalization & traversal rejection (`..`, absolute, or unexpected prefixes are blocked)
- Whitelisted storage path enforcement
- Unauthorized access attempts are audited
- A scan command enumerates receipt records vs files to detect mismatches

Command:

```bash
php artisan receipts:scan
```

### Unique Codes & Data Integrity

Unique short codes (`investment_code`, `receipt_code`) are generated and indexed to ensure deterministic referencing without exposing internal IDs. Stress tests validate collision resistance. Daily interest logs enforce a composite uniqueness (investment_id + date) to guarantee idempotency.

### Idempotent Daily Interest Crediting

The daily interest command guards against duplicate credits for the same investment day through a composite DB constraint. Rerunning the command for a past day becomes a safe no-op for existing entries.

Run manually:

```bash
php artisan interest:update
```

### QR Code Payment Integration

QR codes (with optional embedded logos) are generated for supported banks/providers. See `app/Services/QrCodeService.php` and examples in `/public/*qr_with_logo.png`.

Basic generation example (pseudo):

```php
$svg = app(QrCodeService::class)->generatePaymentQr(
    account: '1234567890',
    amount: Money::fromDecimal('500.00'),
    reference: 'INV-ABC123'
);
```

### Artisan Commands Summary

- `interest:update` – Calculate & persist daily interest (idempotent)
- `receipts:scan` – Audit receipt file presence & DB references
- (Others may be added; run `php artisan list` for the latest.)

### Running Tests

Execute the test suite:

```bash
php artisan test
```

or

```bash
vendor\\bin\\phpunit
```

Key test categories:
 
- Email verification (expiry, resends)
- Receipt security (path traversal, authorization)
- Monetary precision (Money arithmetic)
- Audit logging (event capture)
- Security headers (CSP & hardening)
- Idempotency & uniqueness stress

### Troubleshooting

| Issue | Cause | Resolution |
|-------|-------|------------|
| Monetary value shows as integer | Missing cast | Add `MoneyCast` to model `$casts` |
| Duplicate interest credited | Command run before migration | Ensure latest migrations & composite unique index applied |
| Test error: Facade root not set | Bootstrap mismatch | Use base `TestCase` (already configured) & run via `php artisan test` |
| CSP blocks new asset | CSP too strict | Update directives in `SecurityHeaders` middleware |
| Receipt 403 or 404 unexpectedly | Path rejected | Confirm file path not containing traversal or disallowed prefix |

### Extending the Platform

Suggested next enhancements:

- Role-based permission matrix (e.g. using policies or Spatie permissions)
- Rate limiting for investment creation endpoints
- Configurable audit retention & pruning command
- Outbound webhooks for notable audit events (e.g. large withdrawals)

---

## Development Conventions

- Favor service classes or domain actions for multi-step business logic.
- Keep controllers thin; lean on model scopes, value objects, and services.
- All new monetary columns should use the Money pattern for consistency.
- Add feature tests for every security-sensitive code path.

## License

(Add license details here if applicable.)


