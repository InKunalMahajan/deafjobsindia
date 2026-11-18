# Stage 21 — Accessibility, privacy and security review

## Added
- Self-service private JSON data export for authenticated job seekers.
- Privacy controls page linking to resume and email-alert preferences.
- Explicit acknowledgement that account deletion requires a verified request and is not automated.
- More accurate robots directives for employer and job-seeker private pages.
- Skip-to-main-content navigation retained; reduced-motion styling and focus visibility enhanced.

## Outstanding release blockers
- Perform a manual WCAG 2.2 AA audit with keyboard, screen reader, zoom, contrast and mobile browsers.
- Produce genuine ISL-first explanatory videos with captions, text alternatives and Deaf review; none are claimed as implemented.
- Add authenticated account deletion with ownership confirmation, dependent-record cleanup, retention policy and safe handling of recruiter records.
- Add password reset and verified email flow for both employer and job seeker accounts.
- Audit private resume file MIME detection, malware scanning, storage permissions and content-disposition headers.
- Add data export limits for very large accounts, CSRF-safe request patterns, rate limiting and audit logs.
- Validate email unsubscribe policy and SMTP delivery; scheduled emails must remain disabled until verified.
- Review third-party consent, data retention and compliance with applicable Indian privacy requirements.
- Full automated test suite and production security audit are not run by this GitHub-only change.
