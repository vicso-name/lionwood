<?php
/**
 * Partial: Breadcrumbs
 *
 * File: template-parts/partials/breadcrumbs.php
 *
 * Best practices:
 * - Schema.org BreadcrumbList structured data
 * - aria-label on nav, aria-current on last item
 * - Supports custom post types with archive links
 * - No plugin dependency
 *
 * Usage: get_template_part( 'template-parts/partials/breadcrumbs' );
 */

defined( 'ABSPATH' ) || exit;

// Build breadcrumb items: [ 'label' => '', 'url' => '', 'current' => bool ]
$items = [];

// 1. Home
$items[] = [
    'label'   => __( 'Home', 'theme' ),
    'url'     => home_url( '/' ),
    'current' => false,
];

// 2. CPT archive (if post type has one)
if ( is_singular() ) {
    $post_type = get_post_type();
    $pt_obj    = get_post_type_object( $post_type );

    if ( $pt_obj && $post_type !== 'post' && $post_type !== 'page' ) {
        // CPTs that use a custom page as their archive instead of a native CPT archive.
        // The page is identified by its page template, so renaming its slug is safe.
        $custom_archive_templates = [
            'industry'    => 'page-templates/industries-archive.php',
            'service'     => 'page-templates/services-archive.php',
            'sub_service' => 'page-templates/services-archive.php',
            'career'      => 'page-templates/careers-page.php',
        ];

        if ( isset( $custom_archive_templates[ $post_type ] ) ) {
            $archive_page_id = lionwood_get_template_page_id( $custom_archive_templates[ $post_type ] );
            if ( $archive_page_id ) {
                $items[] = [
                    'label'   => esc_html( get_the_title( $archive_page_id ) ),
                    'url'     => get_permalink( $archive_page_id ),
                    'current' => false,
                ];
            }
        } else {
            $archive_url = get_post_type_archive_link( $post_type );
            if ( $archive_url ) {
                $items[] = [
                    'label'   => $pt_obj->labels->name,
                    'url'     => $archive_url,
                    'current' => false,
                ];
            }
        }
    }

    // 3. Parent page/post
    if ( $post_type === 'sub_service' ) {
        // sub_service uses ACF meta field instead of native post_parent
        $parent_id = (int) get_post_meta( get_the_ID(), 'parent_service', true );
    } else {
        $parent_id = (int) wp_get_post_parent_id( get_the_ID() );
    }

    if ( $parent_id ) {
        $items[] = [
            'label'   => esc_html( get_the_title( $parent_id ) ),
            'url'     => esc_url( get_permalink( $parent_id ) ),
            'current' => false,
        ];
    }

    // 4. Current page
    $items[] = [
        'label'   => esc_html( get_the_title() ),
        'url'     => '',
        'current' => true,
    ];

} elseif ( is_archive() ) {
    $items[] = [
        'label'   => esc_html( get_the_archive_title() ),
        'url'     => '',
        'current' => true,
    ];
} elseif ( is_search() ) {
    $items[] = [
        'label'   => sprintf( __( 'Search: %s', 'theme' ), esc_html( get_search_query() ) ),
        'url'     => '',
        'current' => true,
    ];
} elseif ( is_404() ) {
    $items[] = [
        'label'   => __( '404 Not Found', 'theme' ),
        'url'     => '',
        'current' => true,
    ];
}

if ( count( $items ) < 2 ) return; // don't show on homepage

// Arrow separator SVG
$arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true" focusable="false">
    <path d="M2.00391 6L10.0039 6M7.00391 3L10.0039 6L7.00391 9" stroke="#848588" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

$last_index = count( $items ) - 1;
?>

<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'theme' ); ?>">

    <?php /* Schema.org structured data */ ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            <?php foreach ( $items as $i => $item ) : ?>
            {
                "@type": "ListItem",
                "position": <?php echo $i + 1; ?>,
                "name": "<?php echo esc_js( $item['label'] ); ?>"
                <?php if ( ! $item['current'] && $item['url'] ) : ?>
                ,"item": "<?php echo esc_js( $item['url'] ); ?>"
                <?php endif; ?>
            }<?php echo $i < $last_index ? ',' : ''; ?>
            <?php endforeach; ?>
        ]
    }
    </script>

    <ol class="breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ( $items as $i => $item ) : ?>
            <li
                class="breadcrumbs__item<?php echo $item['current'] ? ' breadcrumbs__item--current' : ''; ?>"
                itemprop="itemListElement"
                itemscope
                itemtype="https://schema.org/ListItem"
            >
                <?php if ( ! $item['current'] && $item['url'] ) : ?>
                    <a
                        class="breadcrumbs__link"
                        href="<?php echo esc_url( $item['url'] ); ?>"
                        itemprop="item"
                    ><span itemprop="name"><?php echo $item['label']; ?></span></a>
                <?php else : ?>
                    <span
                        class="breadcrumbs__current"
                        itemprop="name"
                        aria-current="page"
                    ><?php echo $item['label']; ?></span>
                <?php endif; ?>

                <meta itemprop="position" content="<?php echo $i + 1; ?>">

                <?php if ( $i < $last_index ) : ?>
                    <span class="breadcrumbs__sep" aria-hidden="true"><?php echo $arrow; ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>

</nav>
