# Page Header & Breadcrumbs Template Reference

This document archives the standardized top section (Breadcrumbs, Page Title, Subtitle/Description, and spacing) used across all pages, archives, custom post types, and taxonomies in the **Lohnour Pro** theme. It serves as a permanent reference before any layout refactoring.

---

## 1. Core Architecture & CSS Specifications

### Common Wrapper & Container Structure
```html
<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">
    <div class="container">
        <header class="page-header" style="text-align: center; margin-bottom: var(--spacing-lg); max-width: 650px; margin-left: auto; margin-right: auto;">
            <!-- 1. Page Title -->
            <h1 class="page-title section-title headline-lg" style="margin-top: 0; margin-bottom: var(--spacing-xs);">
                <!-- Title text -->
            </h1>
            
            <!-- 2. Subtitle / Description -->
            <p class="section-subtitle" style="color: var(--color-text-muted); font-size: 1.125rem; margin-bottom: var(--spacing-sm);">
                <!-- Description text -->
            </p>

            <!-- 3. Breadcrumbs (Hero Badge) -->
            <div class="hero-badge" style="display: inline-flex; align-items: center; text-transform: uppercase;">
                <!-- Breadcrumb items & separators -->
            </div>
        </header>
    </div>
    
    <!-- Main page content begins below with spacing -->
</main>
```

### Essential Design Tokens & Styles
- **Padding Top on `<main>`:** `var(--header-clearance)` (or `140px`) to clear the sticky glass header.
- **Header Alignment:** Centered text, max-width constrained (`600px` – `800px`), centered via `margin: 0 auto`.
- **Breadcrumbs Container (`.hero-badge`):**
  - Uppercase, inline-flex, badge pill style (`border: 1px solid var(--color-glass-border)`, `border-radius: var(--radius-full)`, `background: var(--color-glass)`).
  - Separator: `<span style="margin: 0 8px; opacity: 0.5;">/</span>`
  - Links: `color: inherit; text-decoration: none;`
- **Heading (`.page-title.section-title.headline-lg`):**
  - Font size clamp/headline style (`font-size: clamp(2rem, 3.5vw, 3rem); font-weight: 700;`).
  - Margins: `margin-top: var(--spacing-xs); margin-bottom: var(--spacing-sm);`.
- **Subtitle (`.section-subtitle`):**
  - Color: `var(--color-text-muted)`
  - Size: `1.125rem` / `18px`, `line-height: 1.6`.

---

## 2. Page-Specific Implementations

### A. Static Pages

#### About Page (`page-about.php`)
```php
<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">
    <div class="container">
        <header class="page-header" style="text-align: center; margin-bottom: var(--spacing-md); max-width: 650px; margin-left: auto; margin-right: auto;">
            <div class="hero-badge" style="display: inline-flex; align-items: center; text-transform: uppercase;">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="color: inherit; text-decoration: none;">HOME</a> 
                <span style="margin: 0 8px; opacity: 0.5;">/</span> ABOUT
            </div>
            <h1 class="page-title section-title headline-lg" style="margin-top: var(--spacing-xs); margin-bottom: var(--spacing-sm);">About Lohnour Pro</h1>
            <p class="section-subtitle" style="color: var(--color-text-muted); font-size: 1.125rem;">Simplifying Artificial Intelligence adoption through practical, business-oriented education.</p>
        </header>
        <!-- Content -->
    </div>
</main>
```

#### Contact Page (`page-contact.php`)
```php
<main id="primary" class="site-main" style="padding-top: 140px; padding-bottom: var(--spacing-md);">
    <div class="container">
        <header class="page-header" style="text-align: center; margin-bottom: var(--spacing-md); max-width: 650px; margin-left: auto; margin-right: auto;">
            <div class="hero-badge" style="display: inline-flex; align-items: center; text-transform: uppercase;">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="color: inherit; text-decoration: none;">HOME</a> 
                <span style="margin: 0 8px; opacity: 0.5;">/</span> CONTACT
            </div>
            <h1 class="page-title section-title headline-lg" style="margin-top: var(--spacing-xs); margin-bottom: var(--spacing-sm);">Contact Us</h1>
            <p class="section-subtitle" style="color: var(--color-text-muted); font-size: 1.125rem;">Have a question, custom training inquiry, or partnership request? We're here to help.</p>
        </header>
        <!-- Content -->
    </div>
</main>
```

---

### B. Blog & Articles

#### Blog Index / Archive (`home.php`)
```php
<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-xl);">
    <div class="container">
        <header class="page-header" style="text-align: center; margin-bottom: var(--spacing-xl); max-width: 650px; margin-left: auto; margin-right: auto;">
            <div class="hero-badge" style="display: inline-flex; align-items: center;">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="color: inherit; text-decoration: none;">HOME</a> 
                <span style="margin: 0 8px; opacity: 0.5;">/</span> BLOG
            </div>
            <h1 class="page-title section-title headline-lg" style="margin-top: var(--spacing-xs); margin-bottom: var(--spacing-sm);">Latest Insights</h1>
            <p class="section-subtitle" style="color: var(--color-text-muted); font-size: 1.125rem;">Explore our latest articles, tutorials, and thoughts on AI, automation, and design.</p>
        </header>
    </div>
    <!-- Blog Classic Container Grid -->
</main>
```

#### Single Blog Post (`single.php`)
```php
<main id="primary" class="site-main" style="padding-top: var(--header-clearance);">
    <?php while ( have_posts() ) : the_post(); ?>
        <div class="container">
            <header class="page-header" style="text-align: center; margin-bottom: var(--spacing-xl); max-width: 800px; margin-left: auto; margin-right: auto;">
                <div class="hero-badge" style="display: inline-flex; align-items: center; text-transform: uppercase;">
                    <a href="<?php echo esc_url(home_url('/')); ?>" style="color: inherit; text-decoration: none;">HOME</a> 
                    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
                    <a href="<?php echo esc_url(home_url('/blog')); ?>" style="color: inherit; text-decoration: none;">BLOG</a> 
                    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
                    <?php 
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" style="color: inherit; text-decoration: none;">' . esc_html($categories[0]->name) . '</a>';
                    }
                    ?>
                </div>
                <?php the_title( '<h1 class="page-title section-title headline-lg" style="margin-top: var(--spacing-xs); margin-bottom: var(--spacing-sm);">', '</h1>' ); ?>
            </header>
        </div>
        <!-- Single Post Layout & Sidebar -->
    <?php endwhile; ?>
</main>
```

#### Category Archive (`category.php`)
```php
<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-xl);">
    <div class="container">
        <header class="page-header" style="text-align: center; margin-bottom: var(--spacing-xl); max-width: 650px; margin-left: auto; margin-right: auto;">
            <div class="hero-badge" style="display: inline-flex; align-items: center; text-transform: uppercase;">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="color: inherit; text-decoration: none;">HOME</a> 
                <span style="margin: 0 8px; opacity: 0.5;">/</span> 
                <a href="<?php echo esc_url(home_url('/blog')); ?>" style="color: inherit; text-decoration: none;">BLOG</a> 
                <span style="margin: 0 8px; opacity: 0.5;">/</span> 
                <?php single_cat_title(); ?>
            </div>
            <h1 class="page-title section-title headline-lg" style="margin-top: var(--spacing-xs); margin-bottom: 0;"><?php single_cat_title(); ?></h1>
            <?php
            $category_description = category_description();
            if ( ! empty( $category_description ) ) {
                echo '<div class="section-subtitle" style="color: var(--color-text-muted); font-size: 1.125rem; margin-top: var(--spacing-sm);">' . $category_description . '</div>';
            }
            ?>
        </header>
    </div>
</main>
```

#### Tag Archive (`tag.php`)
```php
<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-xl);">
    <div class="container">
        <header class="page-header" style="text-align: center; margin-bottom: var(--spacing-xl); max-width: 650px; margin-left: auto; margin-right: auto;">
            <div class="hero-badge" style="display: inline-flex; align-items: center; text-transform: uppercase;">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="color: inherit; text-decoration: none;">HOME</a> 
                <span style="margin: 0 8px; opacity: 0.5;">/</span> 
                <a href="<?php echo esc_url(home_url('/blog')); ?>" style="color: inherit; text-decoration: none;">BLOG</a> 
                <span style="margin: 0 8px; opacity: 0.5;">/</span> 
                TAG
            </div>
            <h1 class="page-title section-title headline-lg" style="margin-top: var(--spacing-xs); margin-bottom: 0;">#<?php single_tag_title(); ?></h1>
            <?php
            $tag_description = tag_description();
            if ( ! empty( $tag_description ) ) {
                echo '<div class="section-subtitle" style="color: var(--color-text-muted); font-size: 1.125rem; margin-top: var(--spacing-sm);">' . $tag_description . '</div>';
            }
            ?>
        </header>
    </div>
</main>
```

#### Author Archive (`author.php`)
```php
<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-xl);">
    <div class="container">
        <header class="page-header" style="text-align: center; margin-bottom: var(--spacing-xl); max-width: 650px; margin-left: auto; margin-right: auto;">
            <div class="hero-badge" style="display: inline-flex; align-items: center; text-transform: uppercase;">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color: inherit; text-decoration: none;">HOME</a> 
                <span style="margin: 0 8px; opacity: 0.5;">/</span> 
                <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" style="color: inherit; text-decoration: none;">BLOG</a> 
                <span style="margin: 0 8px; opacity: 0.5;">/</span> 
                AUTHOR
            </div>
            <!-- Author Profile Card & Bio Header -->
        </header>
    </div>
</main>
```

---

### C. Services & Training Programs

#### Services / Pathways Archive (`archive-lohnour_service.php`)
```php
<main id="primary" class="site-main" style="padding-top: 140px; padding-bottom: var(--spacing-md);">
    <div class="container">
        <header class="page-header" style="text-align: center; margin-bottom: var(--spacing-md); max-width: 600px; margin-left: auto; margin-right: auto;">
            <div class="hero-badge" style="display: inline-flex; align-items: center; text-transform: uppercase;">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="color: inherit; text-decoration: none;">HOME</a> 
                <span style="margin: 0 8px; opacity: 0.5;">/</span> SERVICES
            </div>
            <h1 class="page-title section-title headline-lg" style="margin-top: var(--spacing-xs); margin-bottom: var(--spacing-sm);">Training Programs</h1>
            <p class="section-subtitle" style="color: var(--color-text-muted);">Explore our hands-on, practical AI training programs categorized by learning pathways.</p>
        </header>
    </div>
</main>
```

#### Single Program / Service (`single-lohnour_service.php`)
```php
<main id="primary" class="site-main py-xxl">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); 
            $pathways = get_the_terms( get_the_ID(), 'lohnour_pathway' );
            $pathway_links = array();
            if ( ! empty( $pathways ) && ! is_wp_error( $pathways ) ) {
                foreach ( $pathways as $pw ) {
                    $pathway_links[] = '<a href="' . esc_url( get_term_link( $pw ) ) . '" style="color: var(--color-accent); text-decoration: none;">' . esc_html( $pw->name ) . '</a>';
                }
            }
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'service-detail-article' ); ?>>
                <header class="entry-header" style="margin-bottom: var(--spacing-xl); border-bottom: 1px solid var(--color-glass-border); padding-bottom: var(--spacing-md);">
                    <div class="hero-badge" style="margin-bottom: var(--spacing-xs); text-transform: uppercase;">
                        Program <?php if ( ! empty( $pathway_links ) ) : ?>
                            in <?php echo implode( ', ', $pathway_links ); ?>
                        <?php endif; ?>
                    </div>
                    <h1 class="entry-title section-title headline-lg" style="margin-top: var(--spacing-xs);"><?php the_title(); ?></h1>
                </header>
                <!-- Split Layout Grid -->
            </article>
        <?php endwhile; ?>
    </div>
</main>
```

---

### D. User Guides & Modular Courses

#### User Guides Index (`archive-user_guide.php`)
```php
<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-xl);">
    <div class="container">
        <header class="page-header" style="text-align: center; margin-bottom: var(--spacing-xl); max-width: 650px; margin-left: auto; margin-right: auto;">
            <div class="hero-badge" style="display: inline-flex; align-items: center;">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="color: inherit; text-decoration: none;">HOME</a> 
                <span style="margin: 0 8px; opacity: 0.5;">/</span> GUIDES
            </div>
            <h1 class="page-title section-title headline-lg" style="margin-top: var(--spacing-xs); margin-bottom: var(--spacing-sm);">User Guides</h1>
            <p class="section-subtitle" style="color: var(--color-text-muted); font-size: 1.125rem;">Learn step-by-step with our comprehensive modular guides and resources.</p>
        </header>
    </div>
</main>
```

#### Single User Guide / Topic / Lesson (`single-user_guide.php`)
```php
<?php
$post_id = get_the_ID();
$parent_id = wp_get_post_parent_id( $post_id );
$is_topic = ( $parent_id === 0 );

// Get the primary sector of the parent Topic or current post for the breadcrumbs
$sectors = get_the_terms( $is_topic ? $post_id : $parent_id, 'guide_sector' );
$sector_html = '';
if ( ! empty( $sectors ) && ! is_wp_error( $sectors ) ) {
    $sector = $sectors[0];
    $sector_html = '<a href="' . esc_url( get_term_link( $sector ) ) . '" style="color: inherit; text-decoration: none;">' . esc_html( $sector->name ) . '</a>';
}

$topic_title = $is_topic ? get_the_title( $post_id ) : get_the_title( $parent_id );
?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-xl);">
    <div class="container">
        <header class="page-header" style="text-align: center; margin-bottom: 0; max-width: 800px; margin-left: auto; margin-right: auto;">
            <div class="hero-badge" style="display: inline-flex; align-items: center; text-transform: uppercase;">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a>
                <span style="margin: 0 8px; opacity: 0.5;">/</span>
                <a href="<?php echo esc_url( home_url( '/guides/' ) ); ?>">GUIDES</a> 
                <?php if ( ! empty( $sector_html ) ) : ?>
                    <span style="margin: 0 8px; opacity: 0.5;">/</span>
                    <?php echo $sector_html; ?>
                <?php endif; ?>
                <?php if ( ! $is_topic ) : ?>
                    <span style="margin: 0 8px; opacity: 0.5;">/</span>
                    <a href="<?php echo esc_url( get_permalink( $parent_id ) ); ?>"><?php echo esc_html( $topic_title ); ?></a>
                <?php endif; ?>
            </div>
            <h1 class="page-title section-title headline-lg" style="margin-top: var(--spacing-xs); margin-bottom: var(--spacing-sm);"><?php the_title(); ?></h1>
        </header>
    </div>
</main>
```

---

## 3. Universal Reusable PHP Helper Function

For quick restoration or uniform rendering in future template updates, use this helper snippet:

```php
/**
 * Render standard page header with breadcrumb trail, title, and subtitle
 *
 * @param array $breadcrumbs Array of ['label' => 'Home', 'url' => 'https://...']
 * @param string $title Main page title
 * @param string $subtitle Optional subtitle / description
 * @param array $args Optional styling customizations
 */
function lohnour_render_page_header( $breadcrumbs = array(), $title = '', $subtitle = '', $args = array() ) {
    $max_width = ! empty( $args['max_width'] ) ? $args['max_width'] : '650px';
    $margin_bottom = ! empty( $args['margin_bottom'] ) ? $args['margin_bottom'] : 'var(--spacing-xl)';
    ?>
    <header class="page-header" style="text-align: center; margin-bottom: <?php echo esc_attr( $margin_bottom ); ?>; max-width: <?php echo esc_attr( $max_width ); ?>; margin-left: auto; margin-right: auto;">
        <?php if ( ! empty( $breadcrumbs ) && is_array( $breadcrumbs ) ) : ?>
            <div class="hero-badge" style="display: inline-flex; align-items: center; text-transform: uppercase;">
                <?php 
                $total = count( $breadcrumbs );
                $i = 0;
                foreach ( $breadcrumbs as $crumb ) {
                    $i++;
                    if ( ! empty( $crumb['url'] ) && $i < $total ) {
                        echo '<a href="' . esc_url( $crumb['url'] ) . '" style="color: inherit; text-decoration: none;">' . esc_html( $crumb['label'] ) . '</a>';
                    } else {
                        echo '<span>' . esc_html( $crumb['label'] ) . '</span>';
                    }
                    if ( $i < $total ) {
                        echo '<span style="margin: 0 8px; opacity: 0.5;">/</span>';
                    }
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $title ) ) : ?>
            <h1 class="page-title section-title headline-lg" style="margin-top: var(--spacing-xs); margin-bottom: var(--spacing-sm);">
                <?php echo esc_html( $title ); ?>
            </h1>
        <?php endif; ?>

        <?php if ( ! empty( $subtitle ) ) : ?>
            <p class="section-subtitle" style="color: var(--color-text-muted); font-size: 1.125rem;">
                <?php echo wp_kses_post( $subtitle ); ?>
            </p>
        <?php endif; ?>
    </header>
    <?php
}
```
