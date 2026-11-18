# DeafJobsIndia — Naukri-style portal roadmap

This is an original Deaf-first recruitment portal, not a copy of Naukri's interface or brand.

## Stage 16: Rich job-seeker profile and discovery
Profile headline, education, skills, experience, location and work preferences; private resume upload/download/deletion; accessible preference settings; basic deterministic job matching. Implemented as an initial feature set. Validate all tests before merging.

## Stage 17: Job search and applications
Search by role, city, experience, salary, work mode, accessibility. Keep external application links. Add detailed manual tracker history and user-only notes. Do not imply applications were sent through DeafJobsIndia.

## Stage 18: Employer self-service
Separate employer registration and organization verification, job posting, moderation, staff permissions and accessible communication. Never expose candidate resumes without explicit candidate consent.

## Stage 19: Recruiter workflow
Candidate opt-in, applications submitted directly to participating employers, review statuses, interview scheduling and secure messaging. No invented recruiter status updates.

## Stage 20: Job alerts and communication
Saved alert preferences are implemented. Scheduled matching, email delivery, opt-in consent, unsubscribe, frequency controls, queues and delivery tests remain pending.

## Stage 21: Accessibility and trust
ISL video guidance, captions, keyboard and screen-reader QA, clear employer verification, anti-scam reporting, data export and account deletion. Do not require disclosure of disability to register.

## Stage 22: Production and mobile
Security audit, authorization tests, private file review, malware scanning, storage quotas, rate limiting, privacy/retention policy, deployment checks, observability, and responsive PWA. Native apps are a later separate decision.

## Release gates
- Preserve existing admin and job-seeker account separation.
- Additive migrations; never reset production databases.
- Run full PHP tests, npm build, OAuth callback checks and private-file access tests.
- Confirm Google credentials, mail configuration, queues and privacy policy.
- Do not merge until code review and local/CI verification pass.
