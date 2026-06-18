<?php
/**
 * Theme tweaks & helpers
 */

add_filter('widget_text', 'do_shortcode', 11);
remove_action('wp_head', 'wp_generator');


remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
add_filter('emoji_svg_url', '__return_false');

function lionwood_nav_active_classes( $classes, $item = null, $args = null ) {
    $markers = ['current-menu-item', 'current-menu-parent', 'current_page_item', 'current_page_parent', 'current-menu-ancestor'];
    $has_current = false;

    foreach ($markers as $m) {
        $key = array_search($m, $classes, true);
        if ($key !== false) {
            unset($classes[$key]);
            $has_current = true;
        }
    }
    if ($has_current && !in_array('active', $classes, true)) {
        $classes[] = 'active';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'lionwood_nav_active_classes', 10, 3);
add_filter('page_css_class', 'lionwood_nav_active_classes', 10, 3);

function lionwood_attachment_fill_alt_from_title( $response ) {
    if (is_array($response) && empty($response['alt']) && !empty($response['title'])) {
        $response['alt'] = sanitize_text_field( $response['title'] );
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'lionwood_attachment_fill_alt_from_title');
add_filter('wpcf7_autop_or_not', '__return_false');

function lionwood_opengraph_namespace( $output ) {
    if (strpos($output, 'og: http://ogp.me/ns#') === false) {
        $output .= ' prefix="og: http://ogp.me/ns# fb: http://www.facebook.com/2008/fbml"';
    }
    return $output;
}
add_filter('language_attributes', 'lionwood_opengraph_namespace');

function lionwood_opengraph_meta_tags() {
    if (!is_singular()) {
        return;
    }

    global $post;
    if (!$post) {
        return;
    }

    $img_src = $img_width = $img_height = '';

    if (function_exists('get_field')) {
        $image_share = get_field('image_share', $post->ID);
    } else {
        $image_share = null;
    }

    if (!empty($image_share['url'])) {
        $img_src    = $image_share['url'];
        $img_width  = isset($image_share['width'])  ? (int)$image_share['width']  : '';
        $img_height = isset($image_share['height']) ? (int)$image_share['height'] : '';
    } elseif (has_post_thumbnail($post->ID)) {
        $img_data = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
        if ($img_data) {
            $img_src    = $img_data[0];
            $img_width  = (int)$img_data[1];
            $img_height = (int)$img_data[2];
        }
    }

    $raw_excerpt = has_excerpt( $post->ID )
        ? get_the_excerpt( $post )
        : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 55 );
    $excerpt = $raw_excerpt ?: get_bloginfo('description');

    $published = get_gmt_from_date( $post->post_date_gmt ? $post->post_date_gmt : $post->post_date, 'c' );
    $modified  = get_gmt_from_date( $post->post_modified_gmt ? $post->post_modified_gmt : $post->post_modified, 'c' );

    echo "\n<!-- Open Graph -->\n";
    echo '<meta property="og:type" content="article" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( get_the_title($post) ) . "\" />\n";
    echo '<meta property="og:description" content="' . esc_attr( $excerpt ) . "\" />\n";
    echo '<meta property="og:url" content="' . esc_url( get_permalink($post) ) . "\" />\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo('name') ) . "\" />\n";
    echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . "\" />\n";
    if ($img_src) {
        echo '<meta property="og:image" content="' . esc_url( $img_src ) . "\" />\n";
        if ($img_width)  echo '<meta property="og:image:width" content="' . esc_attr( $img_width ) . "\" />\n";
        if ($img_height) echo '<meta property="og:image:height" content="' . esc_attr( $img_height ) . "\" />\n";
    }
    echo '<meta property="article:published_time" content="' . esc_attr( $published ) . "\" />\n";
    echo '<meta property="article:modified_time" content="' . esc_attr( $modified ) . "\" />\n";

    echo "\n<!-- Twitter -->\n";
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( get_the_title($post) ) . "\" />\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $excerpt ) . "\" />\n";
    echo '<meta name="twitter:url" content="' . esc_url( get_permalink($post) ) . "\" />\n";
    if ($img_src) {
        echo '<meta name="twitter:image" content="' . esc_url( $img_src ) . "\" />\n";
    }
}
add_action('wp_head', 'lionwood_opengraph_meta_tags', 5);

function lionwood_custom_body_classes( $classes ) {
    if ((is_archive() || is_author() || is_category() || is_home() || is_tag()) && get_post_type() === 'post') {
        // $classes[] = 'is-blog';
    }
    if (is_post_type_archive('project') || is_tax('project_category') || is_singular('project')) {
        $classes[] = 'color-theme-black';
    }
    return $classes;
}
add_filter('body_class', 'lionwood_custom_body_classes');

function lionwood_get_template_page_id( $template_name = '' ) {
    if (!$template_name) return 0;

    $pages = get_posts([
        'post_type'   => 'page',
        'meta_key'    => '_wp_page_template',
        'meta_value'  => $template_name,
        'numberposts' => 1,
        'fields'      => 'ids',
        'no_found_rows' => true,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'suppress_filters' => true,
    ]);
    return !empty($pages[0]) ? (int)$pages[0] : 0;
}

function lionwood_allow_svg_upload_mimes( $mimes ) {
    if ( current_user_can('manage_options') ) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
}
add_filter('upload_mimes', 'lionwood_allow_svg_upload_mimes');


function lionwood_login_logo_css() {
    $url = get_stylesheet_directory_uri() . '/assets/img/login-logo.jpg';
    echo '<style>
        #login h1 a {
            background-image: url("' . esc_url($url) . '");
            background-size: contain;
            width: 100%;
        }
    </style>';
}
add_action('login_head', 'lionwood_login_logo_css');
add_filter('jpeg_quality', function($arg){ return 100; });
add_filter('big_image_size_threshold', '__return_false');


add_filter( 'rank_math/frontend/breadcrumb/args', function( $args ) {
  $args['delimiter']   = '&nbsp;/&nbsp;';
  $args['wrap_before'] = '<nav class="rank-math-breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';
  $args['wrap_after']  = '</nav>';
  $args['before']      = '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
  $args['after']       = '</span>';
  return $args;
});

add_filter( 'rank_math/frontend/breadcrumb/html', function( $html, $crumbs, $class ) {
    ob_start(); ?>
    <nav class="mkdf-container-inner breadcrumbs rank-math-breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php
        $pos = 0;
        $last = count($crumbs);
        foreach ($crumbs as $crumb) {
            $pos++;
            if ($pos === $last) { ?>
                <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span class="last" itemprop="name"><?php echo esc_html($crumb[0]); ?></span>
                    <meta itemprop="position" content="<?php echo esc_attr($pos); ?>" />
                </span>
            <?php } else { ?>
                <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="<?php echo esc_url($crumb[1]); ?>">
                        <span itemprop="name"><?php echo esc_html($crumb[0]); ?></span>
                    </a>
                    <meta itemprop="position" content="<?php echo esc_attr($pos); ?>" />
                </span>
                <span class="separator"> / </span>
            <?php }
        } ?>
    </nav>
    <?php
    return ob_get_clean();
}, 10, 3);

function lionwood_is_blog() {
    return ( is_archive() || is_author() || is_category() || is_home() || is_tag() ) && get_post_type() === 'post';
}

add_action('init', function(){

    // Disable Comments
    if ( function_exists('get_field') && get_field('disable_comments','option') ) {

        add_action('admin_init', function () {
            global $pagenow;
            if ($pagenow === 'edit-comments.php') {
                wp_safe_redirect( admin_url() );
                exit;
            }
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

            foreach ( get_post_types() as $post_type ) {
                if ( post_type_supports($post_type, 'comments') ) {
                    remove_post_type_support($post_type, 'comments');
                    remove_post_type_support($post_type, 'trackbacks');
                }
            }
        });

        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);
        add_filter('comments_array', '__return_empty_array', 10, 2);

        add_action('admin_menu', function () {
            remove_menu_page('edit-comments.php');
        });

        add_action('init', function () {
            if ( is_admin_bar_showing() ) {
                remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
            }
        });

        add_action('admin_menu', function () {
            remove_submenu_page( 'options-general.php', 'options-discussion.php' );
        }, 999);
    }

    // Hide default widgets
    if ( function_exists('get_field') && get_field('disable_widgets','option') ) {

        add_action('wp_dashboard_setup', function () {
            global $wp_meta_boxes;
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_php_nag']);
        });

        remove_action('try_gutenberg_panel', 'wp_try_gutenberg_panel');

        add_action('wp_dashboard_setup', function () {
            remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' );
        });

        add_action('wp_dashboard_setup', function () {
            remove_meta_box( 'dashboard_php_nag', 'dashboard', 'normal' );
        });
    } else {
        add_action( 'widgets_init', function () {
            register_sidebar([
                'name'          => esc_html__( 'Sidebar', 'lionwood' ),
                'id'            => 'sidebar-1',
                'description'   => esc_html__( 'Add widgets here.', 'lionwood' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]);
        });
    }

    // Disable jQuery
    if ( function_exists('get_field') && get_field('disable_jquery','option') ) {
        add_action('wp_enqueue_scripts', function () {
            if ( !is_admin() ) {
                wp_deregister_script('jquery');
            }
        }, 100);
    }
}, 20);


/**
 * Editor guide — single post structure reminder
 */
add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'lionwood_post_guide',
        '📋 Post Structure Guide',
        function () { ?>
            <div style="font-family: -apple-system, sans-serif; font-size: 13px; line-height: 1.6; color: #1e1e1e;">

                <p style="margin: 0 0 12px; font-weight: 600; color: #c83030;">
                    Follow this block order in the editor:
                </p>

                <ol style="margin: 0 0 16px; padding-left: 18px;">
                    <li style="margin-bottom: 6px;">
                        <strong>Article Hero</strong>
                        <span style="color: #666;"> — always first, renders full-width above content</span>
                    </li>
                    <li style="margin-bottom: 6px;">
                        <strong>Article content</strong>
                        <span style="color: #666;"> — paragraphs, headings, images, sliders, numbered lists</span>
                    </li>
                    <li style="margin-bottom: 6px;">
                        <strong>─── Bottom Sections ───</strong>
                    </li>
                    <li style="margin-bottom: 6px;">
                        <strong>About Author</strong>
                        <span style="color: #666;"> — renders full-width below article</span>
                    </li>
                    <li style="margin-bottom: 6px;">
                        <strong>Insights Articles</strong>
                        <span style="color: #666;"> — latest posts section</span>
                    </li>
                    <li style="margin-bottom: 6px;">
                        <strong>FAQ Section</strong>
                        <span style="color: #666;"> — accordion questions</span>
                    </li>
                    <li style="margin-bottom: 6px;">
                        <strong>Contact Section</strong>
                        <span style="color: #666;"> — always last</span>
                    </li>
                </ol>

                <div style="background: #fff3cd; border-left: 3px solid #f0a500; padding: 8px 12px; border-radius: 4px; margin-bottom: 12px;">
                    ⚠️ <strong>Important:</strong> Bottom sections (About Author → Contact) must be placed <em>after</em> all article content. They render outside the article column automatically.
                </div>

                <div style="background: #d4edda; border-left: 3px solid #28a745; padding: 8px 12px; border-radius: 4px;">
                    ✅ <strong>Left sidebar</strong> (Subscribe, AI Summary) and <strong>right sidebar</strong> (Sections, Share) are managed in the <em>Post Sidebar Settings</em> Meta Box.
                </div>

            </div>
        <?php },
        'post',   // post type
        'side',   // position
        'high'    // priority — appears near top
    );
} );

// =============================================================================
// Article Rating — seed initial values on post save
// Runs on create (first save) and on update of posts that predate the feature.
// Never overwrites an existing rating.
// =============================================================================

add_action( 'save_post', function ( $post_id, $post, $update ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) ) return;
    if ( $post->post_type !== 'post' ) return;
    if ( get_post_meta( $post_id, '_aa_rating_count', true ) !== '' ) return;

    $count = wp_rand( 75, 160 );
    $avg   = wp_rand( 490, 500 ) / 100; // 4.90–5.00
    $sum   = (int) round( $avg * $count );

    update_post_meta( $post_id, '_aa_rating_count', $count );
    update_post_meta( $post_id, '_aa_rating_sum',   $sum );
}, 10, 3 );

// =============================================================================
// Article Rating REST API
// POST /wp-json/lionwood/v1/rating  { post_id: int, stars: 1-5 }
// Returns { count: int, average: float }
// =============================================================================

add_action( 'rest_api_init', function () {
    register_rest_route( 'lionwood/v1', '/rating', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'lionwood_handle_rating',
        'permission_callback' => '__return_true',
        'args'                => [
            'post_id' => [
                'required'          => true,
                'type'              => 'integer',
                'minimum'           => 1,
                'sanitize_callback' => 'absint',
            ],
            'stars' => [
                'required'          => true,
                'type'              => 'integer',
                'minimum'           => 1,
                'maximum'           => 5,
                'sanitize_callback' => 'absint',
            ],
        ],
    ] );
} );

function lionwood_handle_rating( WP_REST_Request $request ) {
    $post_id = (int) $request->get_param( 'post_id' );
    $stars   = (int) $request->get_param( 'stars' );

    if ( get_post_type( $post_id ) !== 'post' ) {
        return new WP_Error( 'invalid_post', 'Invalid post ID', [ 'status' => 400 ] );
    }

    // Server-side duplicate vote guard — keyed by IP + post, expires in 24h
    $ip       = $_SERVER['REMOTE_ADDR'] ?? '';
    $vote_key = 'aa_vote_' . md5( $ip . '_' . $post_id );
    if ( get_transient( $vote_key ) ) {
        return new WP_Error( 'already_voted', 'Already rated', [ 'status' => 429 ] );
    }

    // Initialize meta if this is the first interaction with this post
    $count = get_post_meta( $post_id, '_aa_rating_count', true );
    if ( $count === '' ) {
        $count    = wp_rand( 75, 160 );
        $avg_seed = wp_rand( 490, 500 ) / 100;
        $sum      = (int) round( $avg_seed * $count );
        update_post_meta( $post_id, '_aa_rating_count', $count );
        update_post_meta( $post_id, '_aa_rating_sum',   $sum );
    }

    $count = (int) $count + 1;
    $sum   = (int) get_post_meta( $post_id, '_aa_rating_sum', true ) + $stars;

    update_post_meta( $post_id, '_aa_rating_count', $count );
    update_post_meta( $post_id, '_aa_rating_sum',   $sum );

    set_transient( $vote_key, 1, DAY_IN_SECONDS );

    return rest_ensure_response( [
        'count'   => $count,
        'average' => round( $sum / $count, 1 ),
    ] );
}
