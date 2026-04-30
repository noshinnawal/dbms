# AGENTS.md

## Project Context
- **Repo**: `cse425/` — CSE 425 course project (no traditional build/test pipeline)
- **Primary content**: `student_management_system/` — Neomorphic UI mockups and design specs for a school management system (no runnable app, no package manager, no framework)
- **No build/test commands** — This is a design/Figma-style prototype collection, not a compiled application

## What This Repo Contains
- `student_management_system/student_management_system/` — 10 feature modules (login, dashboard, faculty, attendance, etc.)
  - Each module has `code.html` (standalone HTML/CSS prototype using Tailwind + Lexend font)
  - `academic_tactility/DESIGN.md` — Design tokens (colors, typography, spacing, neomorphic principles)
- `university_er_diagram_v4.png` — ER diagram
- **No** `package.json`, **no** `node_modules`, **no** framework config, **no** test suite

## Setup (No Build Required)
- Open any `code.html` in a browser to view prototypes
- No `npm install`, no dev server, no compilation step
- For Kilo/OpenCode: workspace root is `/cse425` or `/student_management_system`

## Important: No Executable Source Code
- This repository holds UI mockups only. There is no:
  - Build command (`npm run dev/build/test`)
  - Test command
  - Type check or lint step
  - Framework entrypoint or routing
- If you need to add functionality, you will be creating new files or converting HTML prototypes to a framework (React/Vue/etc.) — agree tooling first.

## Working with Kilo / OpenCode
- Global Kilo config: `~/.config/kilo/kilo.jsonc` (exists, allows all bash)
- To add a local `.kilo/` config: place it at `/cse425/.kilo/` or `/cse425/student_management_system/.kilo/`
- Skills: install via `npx @lobehub/market-cli skills install <id> --agent kilo-code` or manually to `.kilo/skills/`
- No project-level `kilo.json` present — inherits global config only

## Superpowers Skill (Local Setup)
1. Install globally or to this project:
   - Quick (project): `npx @lobehub/market-cli skills install superpowers --agent kilo-code --dir ./.kilo/skills/`
   - Manual: clone `https://github.com/complexthings/superpowers` → copy `SKILL.md` to `.kilo/skills/superpowers/SKILL.md`
2. Create directory if it doesn't exist:
   - `mkdir -p /cse425/.kilo/skills/superpowers`
   - Place `SKILL.md` inside
3. Skills are loaded at session start — begin a new Kilo session after adding
4. Config override (optional): add to project `kilo.json`:
   ```jsonc
   {
     "skills": {
       "paths": ["./.kilo/skills"]
     }
   }
   ```

## Adding Functionality (If Converting to Real App)
If you plan to turn these prototypes into a runnable app, explicitly declare:
- Framework (React, Vue, Svelte, etc.) — affects lint/test/format commands
- Package manager (npm, yarn, pnpm)
- CI/test/lint pipeline or confirm "prototypes only, no tests"

## Known Gotchas
- `code.html` files use Tailwind CDN and Google Fonts — require internet to render styles
- No routing, state management, or backend — pure frontend static prototypes
- `university_er_diagram_v4.png` is a large binary (≈400KB) — avoid regenerating/overwriting accidentally
- `grep`/`find` over system paths on Windows may trigger permission errors — scope searches to `/cse425`