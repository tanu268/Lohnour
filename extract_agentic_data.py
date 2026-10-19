import re
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')

with open(r'current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php', 'r', encoding='utf-8') as f:
    text = f.read()

def clean_php_text(raw):
    # Extracts text from esc_html_e('...', ...) or strip tags
    if not raw:
        return ""
    # Replace PHP esc_html_e / esc_html__
    cleaned = re.sub(r'<\?php\s+esc_html_[e_]\(\s*[\'"](.*?)[\'"]\s*,\s*[\'"][^\'"]*[\'"]\s*\);\s*\?>', r'\1', raw)
    cleaned = re.sub(r'<\?php.*?\?>', '', cleaned)
    # Strip HTML tags
    cleaned = re.sub(r'<[^>]+>', '', cleaned)
    # Normalize whitespace
    cleaned = re.sub(r'\s+', ' ', cleaned).strip()
    return cleaned

def extract_anchor_text(html_block):
    if not html_block:
        return ""
    # First convert esc_html_e/esc_html__ calls into their plain-text arguments
    converted = re.sub(r'<\?php\s+esc_html_[e_]\(\s*[\'"](.*?)[\'"]\s*,\s*[\'"][^\'"]*[\'"]\s*\);\s*\?>', r'\1', html_block)
    # Then strip any remaining PHP blocks (e.g. <?php echo esc_url(...); ?>) so embedded ?> does not prematurely close HTML tags
    converted = re.sub(r'<\?php.*?\?>', '', converted, flags=re.DOTALL)
    # Now match <a ...> and extract only the anchor inner text
    m = re.search(r'<a\b[^>]*>(.*?)</a>', converted, re.DOTALL | re.IGNORECASE)
    if m:
        cleaned = re.sub(r'<[^>]+>', '', m.group(1))
        return re.sub(r'\s+', ' ', cleaned).strip()
    return ""

data = {
    "title": "Agentic AI Foundations",
    "slug": "agentic-ai-foundations",
    "hero_desc": "Build practical AI agents, automate workflows, and understand how intelligent systems are designed.",
    "duration_hours": "30 Hours",
    "length_text": "~2 Months (~5 hrs/wk)",
    "max_participants": "Max 5 Seats",
    "delivery_style": "Live Founder-Led",
    "cta_label": "Enquire About Next Cohort",
    "cta_url": "/contact",
    "secondary_cta_label": "Explore 30-Hour Curriculum ↓",
    "secondary_cta_url": "#curriculum",
    "banner_alt": "Agentic AI Foundations participants building AI agent systems"
}

# 1. Overview Section
sec_overview = re.search(r'<section id="overview"[^>]*>(.*?)</section>', text, re.DOTALL)
if sec_overview:
    ov_html = sec_overview.group(1)
    badge = clean_php_text(re.search(r'<span class="card-badge"[^>]*>(.*?)</span>', ov_html).group(1))
    title = clean_php_text(re.search(r'<h2 class="section-title"[^>]*>(.*?)</h2>', ov_html).group(1))
    sub = clean_php_text(re.search(r'<p class="section-subtitle"[^>]*>(.*?)</p>', ov_html).group(1))
    
    # main card ps
    main_card = re.search(r'<div class="overview-main[^"]*"[^>]*>(.*?)</div>', ov_html, re.DOTALL).group(1)
    main_h3 = clean_php_text(re.search(r'<h3>(.*?)</h3>', main_card).group(1))
    ps = re.findall(r'<p>(.*?)</p>', main_card, re.DOTALL)
    main_ps = [clean_php_text(p) for p in ps]
    
    # highlights card: match the complete <ul> to avoid premature truncation on inner <div> tags
    hl_h3_m = re.search(r'<div class="overview-highlights[^"]*"[^>]*>.*?<h3>(.*?)</h3>', ov_html, re.DOTALL)
    hl_h3 = clean_php_text(hl_h3_m.group(1)) if hl_h3_m else "Key Cohort Highlights"
    
    ul_m = re.search(r'<ul class="highlights-list"[^>]*>(.*?)</ul>', ov_html, re.DOTALL)
    highlights = []
    if ul_m:
        lis = re.findall(r'<li>.*?<strong>(.*?)</strong>\s*<p>(.*?)</p>.*?</li>', ul_m.group(1), re.DOTALL)
        highlights = [{"title": clean_php_text(t), "desc": clean_php_text(d)} for t, d in lis]
    
    data["overview"] = {
        "badge": badge,
        "title": title,
        "subtitle": sub,
        "main_h3": main_h3,
        "paragraphs": main_ps,
        "highlights_h3": hl_h3,
        "highlights": highlights
    }

# 2. Why This Program
sec_why = re.search(r'<section id="why-program"[^>]*>(.*?)</section>', text, re.DOTALL)
if sec_why:
    why_html = sec_why.group(1)
    badge = clean_php_text(re.search(r'<span class="card-badge"[^>]*>(.*?)</span>', why_html).group(1))
    title = clean_php_text(re.search(r'<h2 class="section-title"[^>]*>(.*?)</h2>', why_html).group(1))
    sub = clean_php_text(re.search(r'<p class="section-subtitle"[^>]*>(.*?)</p>', why_html).group(1))
    lead = clean_php_text(re.search(r'<p class="lead-text"[^>]*>(.*?)</p>', why_html, re.DOTALL).group(1))
    
    # progression pills
    flow_m = re.search(r'<div class="progression-flow"[^>]*>(.*?)</div>', why_html, re.DOTALL)
    pills = re.findall(r'<div class="step-pill[^"]*"[^>]*><span>(.*?)</span>\s*(.*?)</div>', flow_m.group(1), re.DOTALL)
    flow_steps = [{"num": clean_php_text(n), "label": clean_php_text(l)} for n, l in pills]
    
    # cols
    cols = re.findall(r'<div class="phil-col"[^>]*>\s*<h3>(.*?)</h3>\s*<p>(.*?)</p>', why_html, re.DOTALL)
    phil_cols = [{"title": clean_php_text(t), "desc": clean_php_text(d)} for t, d in cols]
    
    data["why_program"] = {
        "badge": badge,
        "title": title,
        "subtitle": sub,
        "lead_text": lead,
        "flow_steps": flow_steps,
        "columns": phil_cols
    }

# 3. What You'll Learn (Curriculum Overview)
sec_learn = re.search(r'<section id="what-youll-learn"[^>]*>(.*?)</section>', text, re.DOTALL)
if sec_learn:
    learn_html = sec_learn.group(1)
    badge = clean_php_text(re.search(r'<span class="card-badge"[^>]*>(.*?)</span>', learn_html).group(1))
    title = clean_php_text(re.search(r'<h2 class="section-title"[^>]*>(.*?)</h2>', learn_html).group(1))
    sub = clean_php_text(re.search(r'<p class="section-subtitle"[^>]*>(.*?)</p>', learn_html).group(1))
    
    cards = re.findall(r'<div class="module-summary-card[^"]*"[^>]*>\s*<div class="mod-num">(.*?)</div>\s*<h3>(.*?)</h3>\s*<span class="mod-time">(.*?)</span>\s*<p>(.*?)</p>', learn_html, re.DOTALL)
    learn_cards = [{"num": clean_php_text(n), "title": clean_php_text(t), "time": clean_php_text(tm), "desc": clean_php_text(d)} for n, t, tm, d in cards]
    
    data["what_youll_learn"] = {
        "badge": badge,
        "title": title,
        "subtitle": sub,
        "cards": learn_cards
    }

# 4. Detailed Curriculum Modules
sec_curriculum = re.search(r'<section id="curriculum"[^>]*>(.*?)</section>', text, re.DOTALL)
if sec_curriculum:
    curr_html = sec_curriculum.group(1)
    badge = clean_php_text(re.search(r'<span class="card-badge"[^>]*>(.*?)</span>', curr_html).group(1))
    title = clean_php_text(re.search(r'<h2 class="section-title"[^>]*>(.*?)</h2>', curr_html).group(1))
    sub = clean_php_text(re.search(r'<p class="section-subtitle"[^>]*>(.*?)</p>', curr_html).group(1))
    
    # modules blocks
    mod_articles = re.findall(r'<article id="curriculum-mod-(\d+)"[^>]*data-module="(\d+)"[^>]*>(.*?)</article>', curr_html, re.DOTALL)
    modules = []
    for mod_id1, mod_id2, art_html in mod_articles:
        num_m = re.search(r'<span class="module-number-pill">(.*?)</span>', art_html)
        num = clean_php_text(num_m.group(1)) if num_m else f"0{mod_id1}"
        
        prefix_m = re.search(r'<span class="module-prefix">(.*?)</span>', art_html)
        prefix = clean_php_text(prefix_m.group(1)) if prefix_m else ""
        
        title_m = re.search(r'<span class="module-title-text">(?:<span class="module-prefix">.*?</span>)?(.*?)</span>', art_html, re.DOTALL)
        mod_title = clean_php_text(title_m.group(1)) if title_m else ""
        
        dur_m = re.search(r'<span class="module-duration-badge">(.*?)</span>', art_html)
        duration = clean_php_text(dur_m.group(1)) if dur_m else ""
        
        ov_m = re.search(r'<p class="module-block-overview"[^>]*>(.*?)</p>', art_html, re.DOTALL)
        overview = clean_php_text(ov_m.group(1)) if ov_m else ""
        
        # What You'll Learn topics
        learn_block = re.search(r'id="curriculum-mod-\d+-learn".*?<div class="guides-accordion-inner"[^>]*>(.*?)</div>', art_html, re.DOTALL)
        topics = []
        if learn_block:
            topics = [clean_php_text(li) for li in re.findall(r'<li>(.*?)</li>', learn_block.group(1), re.DOTALL)]
            
        # Practical Hands-on
        pract_block = re.search(r'id="curriculum-mod-\d+-practical".*?<div class="guides-accordion-inner"[^>]*>(.*?)</div>', art_html, re.DOTALL)
        practical = ""
        progression_pills = []
        if pract_block:
            p_inner = pract_block.group(1)
            p_box = re.search(r'<div class="guide-activity-box"[^>]*>(.*?)</div>', p_inner, re.DOTALL)
            if p_box:
                practical = clean_php_text(p_box.group(1))
            pill_row = re.search(r'<div class="progression-pill-row"[^>]*>(.*?)</div>', p_inner, re.DOTALL)
            if pill_row:
                progression_pills = [clean_php_text(p) for p in re.findall(r'<span class="pill-step[^"]*">(.*?)</span>', pill_row.group(1), re.DOTALL)]
                
        # Outcome
        out_block = re.search(r'id="curriculum-mod-\d+-outcome".*?<div class="guides-accordion-inner"[^>]*>(.*?)</div>', art_html, re.DOTALL)
        outcome = ""
        if out_block:
            o_box = re.search(r'<div class="guide-outcome-box"[^>]*>(.*?)</div>', out_block.group(1), re.DOTALL)
            if o_box:
                outcome = clean_php_text(o_box.group(1))
                
        modules.append({
            "number": num,
            "prefix": prefix,
            "title": mod_title,
            "duration": duration,
            "overview": overview,
            "learn_topics": topics,
            "practical": practical,
            "progression_pills": progression_pills,
            "outcome": outcome
        })
        
    data["curriculum"] = {
        "badge": badge,
        "title": title,
        "subtitle": sub,
        "modules": modules
    }

# 5. What You'll Build
sec_build = re.search(r'<section id="what-youll-build"[^>]*>(.*?)</section>', text, re.DOTALL)
if sec_build:
    b_html = sec_build.group(1)
    badge = clean_php_text(re.search(r'<span class="card-badge"[^>]*>(.*?)</span>', b_html).group(1))
    title = clean_php_text(re.search(r'<h2 class="section-title"[^>]*>(.*?)</h2>', b_html).group(1))
    sub = clean_php_text(re.search(r'<p class="section-subtitle"[^>]*>(.*?)</p>', b_html).group(1))
    
    cards = re.findall(r'<div class="build-card[^"]*">\s*<div class="build-step">(.*?)</div>\s*<h3>(.*?)</h3>\s*<p>(.*?)</p>', b_html, re.DOTALL)
    build_cards = [{"step": clean_php_text(s), "title": clean_php_text(t), "desc": clean_php_text(d)} for s, t, d in cards]
    
    data["what_youll_build"] = {
        "badge": badge,
        "title": title,
        "subtitle": sub,
        "cards": build_cards
    }

# 6. Outcomes
sec_outcomes = re.search(r'<section id="outcomes"[^>]*>(.*?)</section>', text, re.DOTALL)
if sec_outcomes:
    o_html = sec_outcomes.group(1)
    badge = clean_php_text(re.search(r'<span class="card-badge"[^>]*>(.*?)</span>', o_html).group(1))
    title = clean_php_text(re.search(r'<h2 class="section-title"[^>]*>(.*?)</h2>', o_html).group(1))
    sub = clean_php_text(re.search(r'<p class="section-subtitle"[^>]*>(.*?)</p>', o_html).group(1))
    
    quote_m = re.search(r'<div class="north-star-quote"[^>]*>\s*<p>\s*"(.*?)"\s*</p>', o_html, re.DOTALL)
    quote = clean_php_text(quote_m.group(1)) if quote_m else ""
    
    items = re.findall(r'<div class="outcome-item"[^>]*>.*?<strong>(.*?)</strong>\s*<p>(.*?)</p>', o_html, re.DOTALL)
    out_items = [{"title": clean_php_text(t), "desc": clean_php_text(d)} for t, d in items]
    
    data["outcomes"] = {
        "badge": badge,
        "title": title,
        "subtitle": sub,
        "north_star_quote": quote,
        "items": out_items
    }

# 7. Skills You'll Build
sec_skills = re.search(r'<section class="program-section">\s*<div class="section-heading-wrapper">\s*<span class="card-badge">.*?Toolbox &amp; Disciplines.*?</h2>\s*<p class="section-subtitle">(.*?)</p>\s*</div>\s*<div class="skills-tag-cloud">(.*?)</div>\s*</section>', text, re.DOTALL)
if sec_skills:
    sub = clean_php_text(sec_skills.group(1))
    tags = [clean_php_text(t) for t in re.findall(r'<span class="skill-tag">(.*?)</span>', sec_skills.group(2))]
    data["skills"] = {
        "badge": "Toolbox & Disciplines",
        "title": "Skills You'll Build",
        "subtitle": sub,
        "tags": tags
    }

# 8. Where These Skills Can Be Applied
sec_career = re.search(r'<section class="program-section">\s*<div class="section-heading-wrapper">\s*<span class="card-badge">.*?Practical Relevance.*?<div class="career-grid">(.*?)</div>\s*<p class="career-disclaimer">(.*?)</p>\s*</section>', text, re.DOTALL)
if sec_career:
    grid_html = sec_career.group(1)
    disclaimer = clean_php_text(sec_career.group(2))
    cards = re.findall(r'<div class="career-card[^"]*">\s*<h3>(.*?)</h3>\s*<p>(.*?)</p>', grid_html, re.DOTALL)
    career_cards = [{"title": clean_php_text(t), "desc": clean_php_text(d)} for t, d in cards]
    data["career"] = {
        "badge": "Practical Relevance",
        "title": "Where These Skills Can Be Applied",
        "subtitle": "Foundational competencies for modern AI implementation across engineering, operations, and product roles.",
        "cards": career_cards,
        "disclaimer": disclaimer
    }

# 9. How Coaching Works
sec_format = re.search(r'<section id="format"[^>]*>(.*?)</section>', text, re.DOTALL)
if sec_format:
    f_html = sec_format.group(1)
    badge = clean_php_text(re.search(r'<span class="card-badge"[^>]*>(.*?)</span>', f_html).group(1))
    title = clean_php_text(re.search(r'<h2 class="section-title"[^>]*>(.*?)</h2>', f_html).group(1))
    sub = clean_php_text(re.search(r'<p class="section-subtitle"[^>]*>(.*?)</p>', f_html).group(1))
    
    steps = re.findall(r'<div class="coaching-step[^"]*">\s*<div class="step-num">(.*?)</div>\s*<h3>(.*?)</h3>\s*<p>(.*?)</p>', f_html, re.DOTALL)
    coaching_steps = [{"num": clean_php_text(n), "title": clean_php_text(t), "desc": clean_php_text(d)} for n, t, d in steps]
    
    data["format"] = {
        "badge": badge,
        "title": title,
        "subtitle": sub,
        "steps": coaching_steps
    }

# 10. Scope & Boundaries
sec_scope = re.search(r'<section class="program-section">\s*<div class="section-heading-wrapper">\s*<span class="card-badge">.*?Scope Clarity.*?<div class="scope-grid">(.*?)</div>\s*</section>', text, re.DOTALL)
if sec_scope:
    s_html = sec_scope.group(1)
    # included
    inc_m = re.search(r'<div class="scope-card[^"]*included-card">(.*?)</div>\s*<!-- What\'s Not Included -->', s_html, re.DOTALL)
    inc_lis = re.findall(r'<li>(.*?)</li>', inc_m.group(1), re.DOTALL) if inc_m else []
    included = [clean_php_text(li) for li in inc_lis]
    
    # excluded
    exc_m = re.search(r'<div class="scope-card[^"]*excluded-card">(.*?)</div>\s*$', s_html, re.DOTALL)
    note_m = re.search(r'<p class="scope-note"[^>]*>(.*?)</p>', exc_m.group(1), re.DOTALL) if exc_m else None
    scope_note = clean_php_text(note_m.group(1)) if note_m else ""
    exc_lis = re.findall(r'<li>(.*?)</li>', exc_m.group(1), re.DOTALL) if exc_m else []
    excluded = [clean_php_text(li) for li in exc_lis]
    
    data["scope"] = {
        "badge": "Scope Clarity",
        "title": "Scope & Boundaries",
        "subtitle": "Clear expectations regarding what is taught and what lies outside the foundational focus of this program.",
        "included": included,
        "excluded": excluded,
        "scope_note": scope_note
    }

# 11. Cohort Enrollment Card
sec_enroll = re.search(r'<div class="pricing-card glass-card text-center">(.*?)</section>', text, re.DOTALL)
if sec_enroll:
    e_html = sec_enroll.group(1)
    badge = clean_php_text(re.search(r'<span class="card-badge"[^>]*>(.*?)</span>', e_html).group(1))
    title = clean_php_text(re.search(r'<h2 class="section-title"[^>]*>(.*?)</h2>', e_html).group(1))
    desc = clean_php_text(re.search(r'<p class="pricing-desc"[^>]*>(.*?)</p>', e_html, re.DOTALL).group(1))
    pills = [clean_php_text(p) for p in re.findall(r'<div class="callout-pill"[^>]*>(.*?)</div>', e_html, re.DOTALL)]
    btn_m = re.search(r'<div class="pricing-actions">(.*?)</div>', e_html, re.DOTALL)
    cta_btn = extract_anchor_text(btn_m.group(1)) if btn_m else "Enquire About the Next Cohort"
    if not cta_btn:
        cta_btn = "Enquire About the Next Cohort"
    subtext = clean_php_text(re.search(r'<p class="pricing-subtext"[^>]*>(.*?)</p>', e_html, re.DOTALL).group(1))
    
    data["enrollment"] = {
        "badge": badge,
        "title": title,
        "desc": desc,
        "pills": pills,
        "cta_label": cta_btn,
        "cta_url": "/contact",
        "subtext": subtext
    }

# 12. FAQs
sec_faqs = re.search(r'<section id="faqs"[^>]*>(.*?)</section>', text, re.DOTALL)
if sec_faqs:
    faq_html = sec_faqs.group(1)
    badge = clean_php_text(re.search(r'<span class="card-badge"[^>]*>(.*?)</span>', faq_html).group(1))
    title = clean_php_text(re.search(r'<h2 class="section-title"[^>]*>(.*?)</h2>', faq_html).group(1))
    sub = clean_php_text(re.search(r'<p class="section-subtitle"[^>]*>(.*?)</p>', faq_html).group(1))
    
    items = re.findall(r'<div class="faq-item[^"]*">\s*<button class="faq-header"[^>]*>\s*<span>(.*?)</span>.*?<div class="faq-content"[^>]*>\s*<p>(.*?)</p>', faq_html, re.DOTALL)
    faqs = [{"question": clean_php_text(q), "answer": clean_php_text(a)} for q, a in items]
    
    data["faqs"] = {
        "badge": badge,
        "title": title,
        "subtitle": sub,
        "items": faqs
    }

# 13. Closing CTA
sec_cta = re.search(r'<section class="cta-section glass-card text-center">(.*?)</section>', text, re.DOTALL)
if sec_cta:
    c_html = sec_cta.group(1)
    title = clean_php_text(re.search(r'<h2 class="cta-title"[^>]*>(.*?)</h2>', c_html, re.DOTALL).group(1))
    desc = clean_php_text(re.search(r'<p class="cta-desc"[^>]*>(.*?)</p>', c_html, re.DOTALL).group(1))
    cta_btn = extract_anchor_text(c_html)
    if not cta_btn:
        cta_btn = "Enquire About the Next Cohort"
    data["closing_cta"] = {
        "title": title,
        "desc": desc,
        "cta_label": cta_btn,
        "cta_url": "/contact"
    }

with open(r'd:/lohnourpro/agentic_ai_foundations_data.json', 'w', encoding='utf-8') as f:
    json.dump(data, f, indent=2, ensure_ascii=False)

print(f"Extracted successfully! Total curriculum modules: {len(data.get('curriculum', {}).get('modules', []))}")
print(f"Total FAQs: {len(data.get('faqs', {}).get('items', []))}")
print(f"Total What You'll Build cards: {len(data.get('what_youll_build', {}).get('cards', []))}")
print(f"Total Outcomes: {len(data.get('outcomes', {}).get('items', []))}")
print(f"Total Scope Included: {len(data.get('scope', {}).get('included', []))}")
print(f"Total Scope Excluded: {len(data.get('scope', {}).get('excluded', []))}")
