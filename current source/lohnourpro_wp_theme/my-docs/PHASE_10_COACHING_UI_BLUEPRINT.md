# LOHNOUR PRO — PHASE 10
# COACHING UI BLUEPRINT

## 1. Architectural Strategy
Since there is no `coach` or `coaching` backend data model, the Coaching experience will be built using standard WordPress Pages mapped to custom theme templates. 
We will NOT build a Coach Directory or Coach Profile because there is no data to support them.

**Target Templates to Create:**
- `page-coaching.php` (for `/coaching/`)
- `page-individual-coaching.php` (for `/coaching/individual/`)
- `page-group-coaching.php` (for `/coaching/group/`)

## 2. Coaching Hub (`/coaching/`)
**Purpose:** A premium discovery page routing users to Individual or Group coaching.

**Structure:**
1. **Hero Section:**
   - Title: "Coaching"
   - Subtitle: "Personalized guidance for meaningful professional growth."
2. **Pathways (Choose Your Experience):**
   - 2-column grid (`1fr 1fr`)
   - Card 1: Individual Coaching (1:1 guidance) -> Link to `/coaching/individual/`
   - Card 2: Group Coaching (Learn together) -> Link to `/coaching/group/`
3. **How It Works:**
   - A simple 3-step visual process: Needs Assessment -> Matching/Scheduling -> Growth Sessions.
4. **Target Audience (Who It's For):**
   - Static badges representing professionals, students, faculty, and organizations.
5. **CTA Section:**
   - Headline: "Ready to start?"
   - Button: "Request a Consultation" -> `/contact/`

## 3. Individual Coaching (`/coaching/individual/`)
**Purpose:** Explain the 1:1 coaching experience and prompt an inquiry.

**Structure:**
1. **Hero Section:**
   - Title: "Individual Coaching"
   - Subtitle: "1:1 AI guidance tailored exclusively to your career goals and workflow."
   - CTA: "Request Individual Coaching" -> `/contact/`
2. **What Individual Coaching Is:**
   - Editorial block explaining the bespoke nature of the sessions.
3. **What You Can Work On (Areas of Support):**
   - Grid of topics (e.g., Workflow Automation, Prompt Engineering, Agentic AI orchestration).
4. **How It Works:**
   - Step-by-step process of engagement.
5. **CTA Section:**
   - "Talk to Our Team" -> `/contact/`

## 4. Group Coaching (`/coaching/group/`)
**Purpose:** Explain the cohort/group coaching experience.

**Structure:**
1. **Hero Section:**
   - Title: "Group Coaching"
   - Subtitle: "Learn AI alongside a small cohort of driven peers guided by an expert."
   - CTA: "Request Group Coaching" -> `/contact/`
2. **What Group Coaching Is:**
   - Editorial block emphasizing collaborative learning and shared problem-solving.
3. **The Learning Experience:**
   - Features list (e.g., Live Q&A, Peer Review, Capstone Projects).
4. **Who It's For:**
   - Teams or individuals seeking a community-driven learning environment.
5. **CTA Section:**
   - "Request Information" -> `/contact/`
   *(No fake "Next Cohort" or pricing strings will be displayed).*

## 5. Data Mapping Matrix

| UI Element | WordPress Source | Fallback / Behavior |
|---|---|---|
| Page Titles | `get_the_title()` | Hardcoded in template if needed. |
| Page Content | `the_content()` | Hardcoded in template sections. |
| Coaching Pricing | N/A | Excluded. |
| Coach Profiles | N/A | Excluded. |
| CTAs | `home_url('/contact/')` | Always routes to the static contact page. |
| Booking Calendar | N/A | Excluded. Replaced by Inquiry CTA. |

## 6. Responsive Behavior
- **Desktop:** Multi-column grids (e.g., 2 columns for Pathways, 3 columns for process steps).
- **Mobile/Tablet:** Stack elements vertically into a single column (`1fr`).
- **Theming:** Full support for dark/light mode toggle utilizing existing CSS variables (`--color-bg-surface`, `--color-text-graphite`, etc.).
