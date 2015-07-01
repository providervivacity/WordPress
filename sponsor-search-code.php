<?php

if (isset($_REQUEST['sponsearch'])) {
    $sector = $_REQUEST['search_sector'];
    $budget = $_REQUEST['search_budget'];
    $location = $_REQUEST['search_location'];
}
$category_id = array();
$args = array(
    'type' => 'post',
    'child_of' => '',
    'parent' => '0',
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => 0,
    'hierarchical' => 1,
    'taxonomy' => 'sponsorcategory'
);
$categories = get_categories($args);
foreach ($categories as $category) {
    $category_id[] = $category->term_id;
}
$args = array('hide_empty' => false);
$sector_terms = get_terms(array('sectors'), $args);
$budgets_terms = get_terms(array('budgets'), $args);
$locations_terms = get_terms(array('locations'), $args);
if ($sector == 'any' && $location == 'any' && $budget == 'any') {
    $args = array(
        'post_type' => 'sponsor',
        'posts_per_page' => -1,
    );
} else if ($location != 'any' && $budget == 'any' && $sector == 'any') {
    $args = array(
        'post_type' => 'sponsor',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'locations',
                'field' => 'slug',
                'terms' => $location
            )
        )
    );
} else if ($location == 'any' && $budget != 'any' && $sector == 'any') {
    $args = array(
        'post_type' => 'sponsor',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'budgets',
                'field' => 'slug',
                'terms' => $budget
            )
        )
    );
} else if ($location == 'any' && $budget == 'any' && $sector != 'any') {
    $args = array(
        'post_type' => 'sponsor',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'sponsorcategory',
                'field' => 'slug',
                'terms' => $sector
            )
        )
    );
} else if ($location != 'any' && $budget == 'any' && $sector != 'any') {
    $args = array(
        'post_type' => 'sponsor',
        'posts_per_page' => -1,
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'locations',
                'field' => 'slug',
                'terms' => $location
            ),
            array(
                'taxonomy' => 'sponsorcategory',
                'field' => 'slug',
                'terms' => $sector
            )
        )
    );
} else if ($location != 'any' && $budget != 'any' && $sector == 'any') {
    $args = array(
        'post_type' => 'sponsor',
        'posts_per_page' => -1,
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'locations',
                'field' => 'slug',
                'terms' => $location
            ),
            array(
                'taxonomy' => 'bugets',
                'field' => 'slug',
                'terms' => $budget
            )
        )
    );
} else if ($location == 'any' && $budget != 'any' && $sector != 'any') {
    $args = array(
        'post_type' => 'sponsor',
        'posts_per_page' => -1,
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'budgets',
                'field' => 'slug',
                'terms' => $budget
            ),
            array(
                'taxonomy' => 'sponsorcategory',
                'field' => 'slug',
                'terms' => $sector
            )
        )
    );
} else if ($location != 'any' && $budget != 'any' && $sector != 'any') {
    $args = array(
        'post_type' => 'sponsor',
        'posts_per_page' => -1,
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'locations',
                'field' => 'slug',
                'terms' => $location
            ), array(
                'taxonomy' => 'budgets',
                'field' => 'slug',
                'terms' => $budget
            ),
            array(
                'taxonomy' => 'sponsorcategory',
                'field' => 'slug',
                'terms' => $sector
            )
        )
    );
}
$count = 0;
$the_query = new WP_Query($args);
while ($the_query->have_posts()) : $the_query->the_post();
    $count++;
endwhile;
?>
