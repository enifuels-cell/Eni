Laravel Cloud: npm ci lockfile / peer-deps workaround
=====================================================

Problem
-------

Laravel Cloud's build environment runs `npm ci` by default. Some recent dependency changes and peer-dependency constraints (notably around rollup/plugin variants) caused `npm ci` to fail in the Cloud because the environment attempted a strict, lockfile-validated install and encountered platform-specific or peer-dep mismatches.

Quick solutions (pick one)
-------------------------

1) Preferred: instruct Laravel Cloud to use the repository's updated lockfile and clear the build cache

   - Make sure the commit that includes the updated `package-lock.json` is deployed (the commit must be present on the branch Cloud builds from).
   - In Laravel Cloud UI, clear the project's build cache and re-deploy. This ensures Cloud uses the bundle you committed.

2) Add a safer install command in Laravel Cloud build settings (recommended if clearing cache/deploy doesn't help):

   - Set the Install command to:

     npm install --legacy-peer-deps

   - Set the Build command to:

     npm run build

   This avoids `npm ci`'s strict behavior and allows installs to succeed where peer dependency conflicts exist.

3) Environment-level option (alternative): set an env var in Laravel Cloud build settings:

     NPM_CONFIG_LEGACY_PEER_DEPS=true

   This makes `npm ci`/`npm install` behave as if `--legacy-peer-deps` was passed.

4) Repo-level option (already added): commit an `.npmrc` with `legacy-peer-deps=true`.

   - This repository now contains `.npmrc` at the repo root which sets `legacy-peer-deps=true`. Many CI environments respect `.npmrc`. If Laravel Cloud honors it, that may be sufficient.

Verification steps (locally)
----------------------------

From a Windows PowerShell terminal in the project root:

```powershell
# Try the strict path (reproduce Cloud):
npm ci

# If it fails, try the workaround locally to confirm it builds:
npm install --legacy-peer-deps
npm run build
```

What I added to the repo
------------------------

- `.npmrc` — sets `legacy-peer-deps=true` and reduces install progress output.

Notes and next steps
--------------------

- If you prefer I can also add a short `scripts/cloud-deploy-check.ps1` that runs the verification steps and reports failures (useful if you want to automate checks before pushing).
- If Cloud still fails after these adjustments, share the exact Cloud build log (the error lines) and I will produce a minimal lockfile-compatible change or propose upgrading/downgrading the specific dependency versions.
