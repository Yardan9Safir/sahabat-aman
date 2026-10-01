---
name: Sahabat Aman Guidance & Well-being
colors:
  surface: '#fff8f5'
  surface-dim: '#e2d8d2'
  surface-bright: '#fff8f5'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#fcf2eb'
  surface-container: '#f6ece6'
  surface-container-high: '#f0e6e0'
  surface-container-highest: '#eae1da'
  on-surface: '#1f1b17'
  on-surface-variant: '#3f4850'
  inverse-surface: '#342f2b'
  inverse-on-surface: '#f9efe8'
  outline: '#707881'
  outline-variant: '#bfc7d2'
  surface-tint: '#006398'
  primary: '#006194'
  on-primary: '#ffffff'
  primary-container: '#007bb9'
  on-primary-container: '#fdfcff'
  inverse-primary: '#93ccff'
  secondary: '#855300'
  on-secondary: '#ffffff'
  secondary-container: '#fea619'
  on-secondary-container: '#684000'
  tertiary: '#904800'
  on-tertiary: '#ffffff'
  tertiary-container: '#b55d00'
  on-tertiary-container: '#fffbff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#cce5ff'
  primary-fixed-dim: '#93ccff'
  on-primary-fixed: '#001d31'
  on-primary-fixed-variant: '#004b73'
  secondary-fixed: '#ffddb8'
  secondary-fixed-dim: '#ffb95f'
  on-secondary-fixed: '#2a1700'
  on-secondary-fixed-variant: '#653e00'
  tertiary-fixed: '#ffdcc5'
  tertiary-fixed-dim: '#ffb783'
  on-tertiary-fixed: '#301400'
  on-tertiary-fixed-variant: '#713700'
  background: '#fff8f5'
  on-background: '#1f1b17'
  surface-variant: '#eae1da'
typography:
  display:
    fontFamily: Plus Jakarta Sans
    fontSize: 3rem
    fontWeight: '800'
    lineHeight: 3.5rem
  display-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 2.25rem
    fontWeight: '800'
    lineHeight: 2.75rem
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 2rem
    fontWeight: '700'
    lineHeight: 2.5rem
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 1.625rem
    fontWeight: '700'
    lineHeight: 2.125rem
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 1.5rem
    fontWeight: '600'
    lineHeight: 2rem
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 1.25rem
    fontWeight: '600'
    lineHeight: 1.75rem
  title-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 1.125rem
    fontWeight: '600'
    lineHeight: 1.625rem
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 1.125rem
    fontWeight: '400'
    lineHeight: 1.75rem
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 1rem
    fontWeight: '400'
    lineHeight: 1.5rem
  body-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 0.875rem
    fontWeight: '400'
    lineHeight: 1.375rem
  label-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 0.875rem
    fontWeight: '600'
    lineHeight: 1.25rem
  label-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 0.75rem
    fontWeight: '600'
    lineHeight: 1rem
  label-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 0.6875rem
    fontWeight: '500'
    lineHeight: 0.875rem
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1.5rem
  gutter-mobile: 1rem
  margin: 2rem
  margin-mobile: 1rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2.5rem
---

## Brand & Style
The design system communicates safety, approachability, and optimism for school students, educators, and counselors. The atmosphere balances professional mental health reassurance with cheerful, non-intimidating friendliness.

The visual direction leans into **Modern Soft Tactile with Warm Tonal Layers**. It avoids clinical coldness through rounded contours, buoyant micro-interactions, sunlit ambient accents, and warm off-white canvas foundations. Students should feel unburdened and heard, while counselors maintain structured, focused clarity for records and confidential sessions.

## Colors
The palette evokes an open sky meeting warm sunlight, providing clarity without sterile hospital tones.

- **Primary (`#0284c7` / `#38bdf8` Sky/Cyan):** Communicates clarity, psychological safety, and level-headed guidance. Used for primary calls-to-action, focus indicators, navigation anchors, and vital state updates.
- **Secondary (`#f59e0b` / `#fde047` Sun Amber):** Infuses warmth, encouragement, and energy. Reserved for achievement milestones, active listening badges, highlights, and emotional check-in prompts.
- **Tertiary (`#fb923c` / `#fda4af` / `#ffedd5` Peach Glow):** Used for gentle restorative contexts, mood journals, calm alert tags, and soft notification badges.
- **Neutral (`#78716c` Stone & Warm Slate):** Grounding surfaces rest on `#fafaf9` (Warm Canvas Base) and `#f5f5f4` (Card Container Layers), avoiding stark pure grays in favor of compassionate, grounded warmth. Text hierarchy uses `#1c1917` (Stone 900) for headers and `#57534e` (Stone 600) for body narrative.

## Typography
Plus Jakarta Sans provides balanced geometry with humanist terminals, striking an optimal balance between structured institutional rigor and adolescent warmth.

- **Headlines:** Set with confident semi-bold and bold weights to provide structural clarity and calm reassurance. Tight letter-spacing (`-0.02em`) keeps titles cohesive.
- **Body:** Uses generous line height (1.5–1.6x) to reduce visual cognitive load, which is critical when students are expressing distress or reading self-help materials.
- **Labels & Microcopy:** Clean medium and semi-bold weights for badges, mood tags, and action buttons ensuring legibility at small sizes.

## Layout & Spacing
The layout follows a fluid 12-column grid on desktop (`1280px+`), collapsing into an 8-column structure on tablet (`768px - 1024px`), and a single or 4-column flow on mobile screens (`<768px`).

- **Breathing Space:** Spacing is generous to preserve a sense of mental calm. Dense packing of data is restricted to counselor clinical dashboards, while student-facing interfaces utilize relaxed container paddings (`space-lg` to `space-xl`).
- **Rhythm:** Modular increments based on a 4px/8px baseline rule ensure predictable vertical tracking. Component internal padding never drops below `0.5rem` (`space-sm`) for interactive touch targets, satisfying 44x44px mobile accessibility constraints.

## Elevation & Depth
Depth is articulated through **tonal surface stacking** paired with **sun-tinted ambient shadows**, avoiding dark, foreboding drop shadows.

- **Base Layer:** `#fafaf9` (warm stone background).
- **Surface Elevation 1 (Cards, panels):** Background `#ffffff` with a subtle warm border (`1px solid #f2ece4`) and an ambient shadow: `0 4px 20px -2px rgba(120, 113, 108, 0.06), 0 2px 6px -1px rgba(2, 132, 199, 0.04)`.
- **Surface Elevation 2 (Dropdowns, modals, floating action bars):** Background `#ffffff`, elevated with `0 12px 32px -4px rgba(120, 113, 108, 0.12), 0 4px 12px -2px rgba(245, 158, 11, 0.06)`.
- **Active State / Floating Interaction:** A soft inner highlight ring tinted with warm amber or cyan glow (`0 0 0 3px rgba(56, 189, 248, 0.25)`).

## Shapes
A roundedness value of `2` provides a friendly, welcoming, and safe aesthetic without appearing childish. 

- Standard interactive controls (buttons, inputs) utilize `0.5rem` (`rounded-md`).
- Primary content modules, cards, and dialogue prompts use `1rem` (`rounded-lg`).
- Feature cards, mood reflection bubbles, and hero modules expand to `1.5rem` (`rounded-xl`).
- Status chips, mood pills, and circular avatars use full circular pill geometry (`9999px`).

## Components

### Buttons
- **Primary:** Filled with Sky Blue (`#0284c7`), text in `#ffffff`, hover transition to `#0369a1`. Padding: `0.75rem 1.5rem` with a subtle cyan lift shadow.
- **Secondary (Encouragement/Care):** Sunny Amber fill (`#f59e0b`), dark stone text (`#451a03`), ideal for positive actions like "Book Counseling" or "Save Reflection".
- **Tertiary / Ghost:** Transparent base with `#0284c7` label and `#e0f2fe` hover surface fill.

### Chips & Badges
- **Mood & Topic Tags:** Fully rounded pill shapes (`9999px`) with soft pastel backgrounds:
  - Sky Tint (`#e0f2fe` background, `#0369a1` label) for academic queries.
  - Peach Tint (`#ffedd5` background, `#c2410c` label) for emotional health and social topics.
  - Amber Tint (`#fef3c7` background, `#b45309` label) for career and general motivation.

### Input Fields & Textareas
- Built with a warm `#ffffff` fill, framed by a soft border (`#e7e5e4`).
- Focus state brings a sharp 1.5px `#0284c7` border paired with an ambient cyan focus aura (`0 0 0 3px rgba(56, 189, 248, 0.2)`).
- Generous internal padding (`0.75rem 1rem`) ensures ease of use during high-stress inputs (e.g., student crisis reporting or anonymous messaging).

### Cards
- **Session & Diary Cards:** Pure white background over stone-50 canvas, rounded by `1rem`, bordered with `#f5f5f4`, accompanied by gentle top-edge color accents (a 4px horizontal stroke in Sky Blue or Peach).

### Checkboxes & Radio Buttons
- Rounded check indicators (`4px` for checkboxes, circular for radios) utilizing `#0284c7` when checked, featuring clear white glyphs. Inactive state features a `#d6d3d1` border with gentle hover feedback in `#f0fdf4`.

### Domain-Specific Components
- **Mood Tracker Carousel:** Segmented round emotion selectors paired with warm emoji iconography and tertiary peach/amber active glow indicators.
- **Confidentiality Indicator:** A prominent, soft sky-blue badge reassuring users of encrypted, safe-space protection across all chat and booking interfaces.