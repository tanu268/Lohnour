# User Guides Content Architecture Plan

This document outlines the proposed backend structure and content management approach for the "User Guides" section of the Lohnour Pro website.

## 1. Overview of Content Types

As discussed, the website will have two primary silos of content:
1.  **Blog Posts**: Standard WordPress `post` type for standalone articles, news, and updates, categorized using standard WordPress Categories and Tags.
2.  **User Guides**: A structured, multi-module learning format (e.g., Course -> Module -> Guide).

Because User Guides are deeply nested (e.g., *Agentic AI > Environment Setup > Installing LangGraph*) and have a distinct purpose from blogs, they require their own dedicated architecture in WordPress.

---

## 2. The Core Architecture (How to Implement)

Since you explicitly mentioned that *"this is not just taxonomy"* and that guides exist as a group of modules, we need a structure that supports a strict **3-level hierarchy**. 

There are two main ways to build this in WordPress. I have detailed both below so we can decide which fits your workflow best.

### Approach A: The "Hierarchical CPT" Method (Selected)
*Based on your clarification regarding potentially adding a formal LMS/Courses section later, this approach is firmly recommended. It keeps the "Guides" completely separate from any future Course plugins you might install.*

This approach uses a single Custom Post Type that behaves like standard WordPress "Pages", allowing parent-child relationships natively.

*   **Custom Post Type (CPT)**: `user_guide`
    *   `hierarchical => true` (This allows nesting).
*   **The Structure**:
    *   **Top Level (Parent)**: The Topic (e.g., *Agentic AI*)
    *   **Second Level (Child)**: The Module (e.g., *Setup Environment*)
    *   **Third Level (Grandchild)**: The actual Guide (e.g., *Installing Python*)
*   **URL Structure**: This natively generates clean URLs like:
    `website.com/guides/agentic-ai/setup-environment/installing-python/`
*   **Taxonomy / Categorization**:
    *   We create a custom taxonomy called `guide_sector` (e.g., *Generative AI*, *Soft Skills*).
    *   You assign the "Sector" to the Top Level parent (The Topic), which helps in filtering guides on the frontend.
*   **Video Integration**:
    *   Since most guides will contain a YouTube video, we will add a custom ACF field (or native Meta Box) to the `user_guide` post type specifically for "YouTube Video URL". This allows us to uniformly format the video player at the top of every guide automatically.
*   **Pros**: Entirely native to WordPress. Extremely lightweight. Won't conflict with future LMS plugins (like LearnDash) if you build paid courses later. URLs perfectly map to the structure.
*   **Cons**: The backend list view can get long, though you can filter by parent. The frontend template (`single-user_guide.php`) will need PHP logic to display differently depending on whether the user is viewing a Topic, Module, or Guide.

---

## 3. Taxonomy and Sectors

Regardless of whether we choose Approach A or B, we will register a custom taxonomy to handle the broad industry sectors.

*   **Taxonomy Name**: `guide_sector`
*   **Terms**:
    *   Generative AI
    *   Soft Skills
    *   Content Marketing
    *   (Add more as needed)

By keeping this as a Taxonomy rather than a CPT, we can easily create archive pages (e.g., `website.com/sector/generative-ai/`) that list all the Courses available under that specific umbrella.

---

## 4. Frontend Layout & Navigation Strategy (Blog & Guides)

As discussed, both the **Blog** and **User Guides** sections will act as powerful, standalone hubs. They will have their own distinct, elegant, and user-friendly interfaces that feel like independent applications within the Lohnour Pro ecosystem.

### The "Hub" Interface Design
When a user visits `/blog` or `/guides`, they will not just see a standard feed of posts. They will enter a dedicated "Hub" environment.

*   **Dedicated Layout Structure**: 
    *   **Main Content Area (Center)**: Clean, uncluttered grid or list of the latest/selected articles or guides.
    *   **Contextual Navigation (Left Sidebar)**: A sticky left sidebar for easy navigation. For the blog, this might contain categories. For guides, this will contain the multi-module Table of Contents (Topic > Module > Guide).
    *   **Discovery Panel (Right Sidebar)**: A right-side panel featuring tags, popular/trending posts, and related resources to keep the user engaged without feeling congested.
*   **Independent Branding**: These sections will maintain the Lohnour Pro premium aesthetic (Bento grids, smooth transitions) but will have a focused reading/learning UI that minimizes distractions from the main marketing site.
*   **Video Player Header (Guides)**: If a YouTube URL is provided in the backend, the template will automatically generate a beautiful, responsive video player at the top of the guide.
*   **Next / Previous Buttons**: At the bottom of every guide, we will use PHP to automatically link to the next guide in the module, or the first guide of the next module.
*   **Breadcrumbs**: A dynamic breadcrumb trail (Home > Guides > Agentic AI > Setup Environment > Installing Python).

---

## Next Steps

Based on your input, the architecture is locked in:
- A single hierarchical `user_guide` Custom Post Type for guides.
- Standard `post` type for blogs.
- Terminology will use "Topics", "Modules", and "Guides".
- Both sections will feature a powerful, standalone "Hub" frontend interface with contextual sidebars.

If you approve this finalized architecture and frontend strategy, I can proceed with writing the PHP code in your theme's `functions.php` to register the Post Type and Taxonomy.
