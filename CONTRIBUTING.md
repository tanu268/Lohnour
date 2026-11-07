# Contributing to Lohnour Pro

Thank you for contributing to **Lohnour Pro**. To ensure code quality, repository cleanliness, and production stability across multiple developers, all contributors are expected to adhere to the guidelines outlined in this document.

---

## 1. Code of Conduct & Core Engineering Principles

1. **Production Safety First:** Never push unreviewed, unvalidated, or disruptive code to `developer` or `main`.
2. **Strict Zero-Secrets Policy:** Never commit passwords, private keys (`.key`, `.pem`), API tokens, live database dumps, or local `.env` files.
3. **Atomic & Focused Changes:** Keep pull requests focused on a single feature, bugfix, or task. Never bundle unrelated refactoring, personal IDE files, or untracked test scripts into a PR.
4. **Reproducibility:** Ensure all local changes can be cleanly verified using the reproducible local development environment (`docker-compose.local.yml`).

---

## 2. Branching Strategy

We follow a structured Git branching workflow based on trunk integration:

```text
main (Production / Tagged Releases)
  │
  └── developer (Integration & Staging)
        │
        ├── feature/contact-form-handler
        ├── feature/curriculum-accordion
        ├── fix/hero-mobile-spacing
        └── hotfix/security-patch (branched from main)
```

### Branch Conventions

| Branch Type | Naming Convention | Description | Merges Into |
|---|---|---|---|
| **Production** | `main` | Production-ready, stable releases. Tagged with semantic versions (`v1.0.0`). | N/A |
| **Integration** | `developer` | Primary development integration branch. Target for all PRs. | `main` |
| **Feature** | `feature/<short-description>` | New user-facing features, components, or CMS enhancements. | `developer` |
| **Bug Fix** | `fix/<short-description>` | Bug fixes, UI adjustments, or performance improvements. | `developer` |
| **Hotfix** | `hotfix/<short-description>` | Urgent production incident patches requiring immediate cutover. | `main` & `developer` |
| **Chore** | `chore/<short-description>` | Tooling, documentation, CI/CD, or dependency maintenance. | `developer` |

---

## 3. Step-by-Step Developer Workflow

### Step 1: Clone and Checkout the Developer Branch

```bash
git clone https://github.com/tanu268/Lohnour.git
cd Lohnour
git checkout developer
git pull origin developer
```

### Step 2: Create a Dedicated Working Branch

Always branch off the latest `developer` branch:

```bash
git checkout -b feature/my-new-feature
```

### Step 3: Configure Local Development Environment

Copy the safe environment template and start the local container stack:

```bash
cp .env.example .env
# Edit .env with your local passwords if needed
docker compose -f docker-compose.local.yml up -d
```

Verify your local WordPress instance is running at `http://localhost:8080`.

### Step 4: Implement and Test Changes

- Place theme changes inside `current source/lohnourpro_wp_theme/`.
- Place custom plugin changes inside `current source/lohnour-program-cms/`.
- Ensure changes pass PHP syntax validation:
  ```bash
  php -l "current source/lohnourpro_wp_theme/functions.php"
  ```

### Step 5: Stage Selectively — Avoid Blind `git add .`

> [!WARNING]
> **NEVER use `git add .` or `git add -A`.**
> Blindly staging entire directories frequently captures personal `.env` files, temporary logs, test dumps, or unrelated files.

Review your modified and untracked files:

```bash
git status
```

Inspect your diff:

```bash
git diff
```

Stage only the specific files relevant to your task:

```bash
git add "current source/lohnourpro_wp_theme/functions.php"
git add "current source/lohnourpro_wp_theme/assets/css/theme.css"
```

Verify staged contents:

```bash
git diff --cached
```

### Step 6: Commit with Conventional Commit Messages

We enforce [Conventional Commits](https://www.conventionalcommits.org/):

```text
<type>(<scope>): <short description in present tense>

[optional body with rationale and details]

[optional issue reference]
```

#### Commit Types:
- `feat`: A new feature or capability.
- `fix`: A bug fix.
- `docs`: Documentation changes only.
- `style`: Formatting, whitespace, semicolon fixes (no code logic change).
- `refactor`: Code restructuring without bug fixes or new features.
- `test`: Adding or updating tests.
- `chore`: Build processes, package configurations, or tooling changes.
- `security`: Security patches, credential hardening, or permission changes.

#### Examples:
```bash
git commit -m "feat(cms): add lesson completion toggle to user guide"
git commit -m "fix(theme): correct flexbox wrapping on mobile navigation"
git commit -m "docs(dev): add local database seeding instructions"
```

### Step 7: Push and Open a Pull Request

Push your branch to GitHub:

```bash
git push -u origin feature/my-new-feature
```

Open a Pull Request on GitHub against the `developer` branch.

---

## 4. Pull Request Requirements

Every PR must satisfy the following checklist before merge approval:

1. **Target Branch:** PR targets `developer` (never `main` directly, except hotfixes).
2. **Clear Description:** PR description clearly states the *what*, *why*, and *how*.
3. **CI Status:** All automated GitHub Actions checks (`lint-and-validate`, `php-lint`, `secret-scan`) must pass.
4. **Zero Secrets:** No credentials, API keys, private keys, or `.env` files committed.
5. **Clean Diffs:** No accidental whitespace thrashing, leftover `console.log()` / `var_dump()` calls, or temporary test scripts.
6. **Code Review:** Minimum of one review approval from the technical lead.

---

## 5. Security Rules & Credential Protection

- **Local Secrets:** All passwords, SSH key locations, and API tokens must live strictly in `.env` (which is excluded by `.gitignore`).
- **Production Secrets:** Production database passwords and keys are injected exclusively on the host server (`/opt/lohnour/.env`) or via Portainer Stack configuration.
- **Accidental Commit Protocol:** If you ever accidentally commit a secret, **do not** attempt to cover it up with another commit. Notify the security lead immediately to rotate the credential.
