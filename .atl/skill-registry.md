# Skill Registry

**Delegator use only.** Any agent that launches sub-agents reads this registry to resolve compact rules, then injects them directly into sub-agent prompts. Sub-agents do NOT read this registry or individual SKILL.md files.

See `_shared/skill-resolver.md` for the full resolution protocol.

## User Skills

| Trigger | Skill | Path |
|---------|-------|------|
| When creating a pull request, opening a PR, or preparing changes for review | branch-pr | `C:\Users\DEDASEMA\.gemini\skills\branch-pr\SKILL.md` |
| When writing Go tests, using teatest, or adding test coverage | go-testing | `C:\Users\DEDASEMA\.gemini\skills\go-testing\SKILL.md` |
| When creating a GitHub issue, reporting a bug, or requesting a feature | issue-creation | `C:\Users\DEDASEMA\.gemini\skills\issue-creation\SKILL.md` |
| When user says "judgment day", "judgment-day", "review adversarial", "dual review", "doble review", "juzgar", "que lo juzguen" | judgment-day | `C:\Users\DEDASEMA\.gemini\skills\judgment-day\SKILL.md` |
| When user asks to create a new skill, add agent instructions, or document patterns for AI | skill-creator | `C:\Users\DEDASEMA\.gemini\skills\skill-creator\SKILL.md` |

## Compact Rules

Pre-digested rules per skill. Delegators copy matching blocks into sub-agent prompts as `## Project Standards (auto-resolved)`.

### branch-pr
- Every PR MUST link an approved issue (`Closes #N`) — no exceptions
- Every PR MUST have exactly one `type:*` label
- Branch names: `^(feat|fix|chore|docs|style|refactor|perf|test|build|ci|revert)/[a-z0-9._-]+$`
- Commits: `type(scope): description` — conventional commits required
- PR body: linked issue + type checkbox + summary + changes table + test plan + contributor checklist
- Automated checks (issue reference, status:approved, type label, shellcheck) must pass before merge

### go-testing
- Use table-driven tests with `t.Run()` for multiple cases
- Bubbletea TUI: test `Model.Update()` for state transitions, `teatest.NewTestModel()` for integration
- Golden file testing for visual output — `testdata/*.golden` with `-update` flag
- Mock system dependencies via interfaces, use `t.TempDir()` for file ops
- Skip integration tests with `-short` flag
- Commands: `go test ./...`, `go test -cover ./...`, `go test -v -run TestName`

### issue-creation
- Blank issues disabled — MUST use Bug Report or Feature Request template
- Every issue auto-gets `status:needs-review` on creation
- A maintainer MUST add `status:approved` before any PR can be opened
- Questions go to Discussions, not issues
- Bug reports require: description, steps to reproduce, expected/actual behavior, OS, agent/client, shell
- Feature requests require: problem description, proposed solution, affected area

### judgment-day
- Launch TWO blind judge sub-agents in parallel — orchestrator never reviews code itself
- Classify warnings: `real` (normal user can trigger) vs `theoretical` (contrived scenarios)
- Verdict synthesis: Confirmed (both judges) → fix; Suspect (one judge) → triage; Contradiction → flag
- Round 1: present findings, ask user before fixing. Round 2+: only re-judge for confirmed CRITICALs
- After 2 fix iterations with remaining issues → ASK user to continue or escalate
- APPROVED = 0 confirmed CRITICALs + 0 confirmed real WARNINGs

### skill-creator
- SKILL.md is required; optional `assets/` and `references/` dirs
- Frontmatter: name, description (with Trigger), license (Apache-2.0), metadata.author, metadata.version
- Naming: `{technology}` for generic, `{project}-{component}` for project-specific
- `references/` must point to LOCAL files, not web URLs
- Start with Critical Patterns, use tables for decision trees, keep examples minimal
- Register in AGENTS.md after creation

## Project Conventions

| File | Path | Notes |
|------|------|-------|
| AGENTS.md | `c:\Users\DEDASEMA\OneDrive\Documentos\PORTFOLIO\projects\web\dms-arqui-sw\AGENTS.md` | Project constitution — 3-layer architecture rules |
| GEMINI.md | `c:\Users\DEDASEMA\OneDrive\Documentos\PORTFOLIO\projects\web\dms-arqui-sw\GEMINI.md` | References AGENTS.md |

Read the convention files listed above for project-specific patterns and rules. All referenced paths have been extracted — no need to read index files to discover more.
