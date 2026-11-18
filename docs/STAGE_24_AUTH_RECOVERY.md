# Stage 24 — Authentication and account recovery

## Implemented
- Job-seeker and employer password reset request pages and token reset forms, using Laravel's existing password broker.
- Role-aware reset-link requests. A reset request for the wrong portal sends no email, with a neutral response.
- Password minimum 12 characters and Laravel's hashed, expiring reset tokens.
- Laravel email verification notifications on new password-based registrations.
- Signed email verification URL, authenticated user ID/hash checks, throttled resend.
- Google login verifies the Google email claim and records email verification for the signed-in job-seeker.
- Focused regression tests for recovery isolation and signed verification.

## Release blockers and review
- Configure real SMTP and APP_URL before testing delivery. Mail is not production-ready until verified.
- Existing accounts may need a resend workflow; visit /email/verify after login.
- Password reset and verification should be tested end-to-end with a real mailbox and multiple roles.
- Review any Google account recovery implications: password reset for Google-created accounts can enable password login; document this and consider restricting or adding explicit consent.
- Consider session invalidation on password change and account-wide logout.
- Email verification is not yet required by all protected routes. Enable selectively after user migration and test.
- Run php artisan test and npm run build; assistant has not run them.
- Keep JOB_ALERTS_SCHEDULE_ENABLED=false until email delivery is fully hardened.

## Local verification

```powershell
git fetch origin
git switch --track origin/feature/stage-24-auth-recovery
php artisan route:list
php artisan test --filter=StageTwentyFourAuthTest
php artisan test
npm run build
```
