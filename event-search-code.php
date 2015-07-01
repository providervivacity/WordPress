<?php
$canon_options = get_option('canon_options');
$page_type = mb_get_page_type();
switch ($page_type) {
    case 'category':
        $archive_title = __('category', 'loc_canon');
        $archive_subject = single_cat_title('', false);
        break;
    case 'tag':
        $archive_title = __('tag', 'loc_canon');
        $archive_subject = single_tag_title('', false);
        break;
    case 'search':
        $archive_title = __('search', 'loc_canon');
        $archive_subject = get_search_query();
        break;
    case 'author':
        $archive_title = __('author', 'loc_canon');
        $archive_subject = get_the_author_meta('display_name', $wp_query->post->post_author);
        break;
    case 'day':
        $archive_title = __('day', 'loc_canon');
        $archive_subject = get_the_time('d/m/Y');
        break;
    case 'month':
        $archive_title = __('month', 'loc_canon');
        $archive_subject = get_the_time('m/Y');
        break;
    case 'year':
        $archive_title = __('year', 'loc_canon');
        $archive_subject = get_the_time('Y');
        break;
    case 'tax':
        $archive_title = __('group', 'loc_canon');
        $archive_subject = get_query_var('term');
        break;
    case 'custom_post_type_archive':
        $archive_title = __('custom post type', 'loc_canon');
        $post_type = get_post_type();
        $post_type_object = get_post_type_object($post_type);
        $archive_subject = $post_type_object->label;
        break;
    default:
        $archive_title = __('browsing', 'loc_canon');
        $archive_subject = __('Unknown', 'loc_canon');
        break;
}
$excerpt_length = 360;
// SET MAIN CONTENT CLASS
$main_content_class = "main-content three-fourths";
if ($canon_options['sidebars_alignment'] == 'left') {
    $main_content_class .= " left-main-content";
}
global $wpdb;
$eventCatId = array();
$eventCatTmX = array();
$eventCatOpt = array();
if ($archive_subject == '') {
    echo 'Sorry, no posts matched your criteria.';
} else {

    $data_tmx = $wpdb->get_results(" SELECT tm.term_id FROM ox98_terms tm INNER JOIN ox98_term_taxonomy tt ON tm.term_id = tt.term_id WHERE  tt.description LIKE '%$archive_subject%' OR tm.name LIKE '%$archive_subject%'");
}
foreach ($data_tmx as $result) {
    $eventCatTmX[] = $result->term_id;
}
for ($i = 0; $i < count($eventCatTmX); $i++) {

    $term = get_term_by('id', $eventCatTmX[$i], 'event_category');

    if ($term) {
        ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class("clearfix"); ?>
             <!-- THE TITLE -->
             <h3><?php echo $subCatUrl = '<a href="' . get_term_link($term->term_id, 'event_category') . '">' . $term->name . '</a> '; ?></h3>
            <!-- read more -->
            <?php echo $subCatUrl = '<a href="' . get_term_link($term->term_id, 'event_category') . '" class="more"><span class="wpb_button  wpb_wpb_button wpb_regularsize wpb_button_search">View</span> </a> '; ?>
        </div>    

        <?php
    }
    if (!$data_tmx) {
        $archive_sub = '"%' . $archive_subject . '%"';
        $query = "SELECT * FROM  $wpdb->options opt WHERE  opt.option_value LIKE  " . $archive_sub . " ";
        $data_opt = $wpdb->get_results($query);
        if ($data_opt) {
            foreach ($data_opt as $res) {
                $optval = explode('_', $res->option_name);
                if (is_numeric($optval[2])) {
                    $eventCatOpt[] = $optval[2] . '-' . $res->option_value;
                }
            }
            for ($i = 0; $i < count($eventCatOpt); $i++) {
                $term = get_term_by('id', $eventCatOpt[$i], 'event_category');
                $optfield = explode('-', $eventCatOpt[$i]);
                if ($term) {
                    ?>
                    <div id="post-<?php the_ID(); ?>" <?php post_class("clearfix"); ?>>
                        <!-- THE TITLE -->
                        <h3><?php echo $subCatUrl = '<a href="' . get_term_link($term->term_id, 'event_category') . '">' . $optfield[1] . ' ' . $optfield[2] . '</a> '; ?></h3>
                        <!-- read more -->
                        <?php echo $subCatUrl = '<a href="' . get_term_link($term->term_id, 'event_category') . '" > <span class="wpb_button  wpb_wpb_button wpb_regularsize wpb_button_search">View</span> </a> '; ?>
                    </div>
                    <?php
                }
            }
        }
        if (!$data_opt) {
            $args = array(
                'post_type' => 'event',
                'post_status' => 'publish',
                's' => $archive_subject
            );

            $the_query = new WP_Query($args);
            while ($the_query->have_posts()) : $the_query->the_post();
                $postCat = get_the_terms(get_the_ID(), 'event_category');
                if ($postCat) {
                    foreach ($postCat as $postCatName) {
                        $eventCatId[] = $postCatName->term_taxonomy_id;
                    }
                }
            endwhile;
            wp_reset_postdata();
            for ($i = 0; $i < count($eventCatId); $i++) {
                $term = get_term_by('id', $eventCatId[$i], 'event_category');
                //child url get
                if ($term) {
                    ?>
                    <div id="post-<?php the_ID(); ?>" <?php post_class("clearfix"); ?>>
                        <!-- THE TITLE -->
                        <h3><?php echo $subCatUrl = '<a href="' . get_term_link($term->term_id, 'event_category') . '">' . $term->name . '</a> '; ?></h3>
                        <!-- read more -->
                        <?php echo $subCatUrl = '<a href="' . get_term_link($term->term_id, 'event_category') . '" class="more"> More </a> '; ?>
                    </div>
                    <?php
                }
            }
        }
    }
}





            
