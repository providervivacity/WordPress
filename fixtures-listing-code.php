<?php
$args = array(
    'post_type' => 'event',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'event_category',
            'field' => 'slug',
            //'terms' => $current_cat_slug
            'terms' => $archive_subject
        )
    ),
);
$postcount = 0;
$the_querys = new WP_Query($args);
while ($the_querys->have_posts()) {
    $the_querys->the_post();
    $postcount++;
}
wp_reset_query();
$args = array(
    'post_type' => 'event',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'tax_query' => array(
        array(
            'taxonomy' => 'event_category',
            'field' => 'slug',
            'terms' => $archive_subject,
            'include_children' => false
        )
    ),
);
$the_query = new WP_Query($args);
$i = 1;
$eventform = 1;
if ($the_query->have_posts()) {

    while ($the_query->have_posts()) {
        $the_query->the_post();
    }
    $counter = 1;
    $custom_filed_value = get_field('event_category_setting', 'event_category_' . $term->term_id);
    if (is_array($custom_filed_value)) {
        foreach ($custom_filed_value as $fieldkey) {
            if (!empty($fieldkey['event_end_date'])) {
                $event_end_dateA = explode(",", $fieldkey['event_end_date']);
                if (count($event_end_dateA) >= 1)
                    $event_end_dateAV = end($event_end_dateA);
                $dayMY = $event_end_dateAV . ' ' . $fieldkey['event_month_year'];
                if (count($event_end_dateA) > 1) {
                    $eventDateRange = $fieldkey['event_start_date'] . $fieldkey['event_end_date'] . ' ' . $fieldkey['event_month_year'];
                } else {
                    $eventDateRange = $fieldkey['event_start_date'] . ' - ' . $event_end_dateAV . ' ' . $fieldkey['event_month_year'];
                }
            } else {
                $dayMY = $fieldkey['event_start_date'] . ' ' . $fieldkey['event_month_year'];
                $eventDateRange = $fieldkey['event_start_date'] . ' ' . $fieldkey['event_month_year'];
            }
            $postCat = get_the_terms(get_the_ID(), 'event_category');
            foreach ($postCat as $postCatName) {
                $postCatNames = $postCatName->name;
            }
            $time = strtotime($dayMY);
            $newformat = date('Y-m-d', $time);
            $diff = date_diff(date_create(date('Y-m-d')), date_create($newformat));
            $diff = $diff->format("%R%a");
            if ($diff >= 0) {
                ?>
                <tr>
                    <td>
                        <?php echo $eventDateRange;
                        ?></td>
                    <?php if ($term->parent == 31 || $term->parent == 93 || $term->parent == 30) { ?> 
                        <td><?= $fieldkey['event_kick_off']; ?></td>
                    <?php } ?>
                    <td><?= $fieldkey['event_opponent']; ?></td>
                    <td class="enquire_td" ><a class="wpb_button_a fancybox"  href="#contact_form_pop<?php echo $counter; ?>"  target="_blank"  rel="nofollow"><?php
                            if ($fieldkey['event_price']) {
                                echo $fieldkey['event_price'];
                            } else {
                                echo 'Enquire';
                            }
                            ?></a>
                        <div style="display:none" class="fancybox-hidden">
                            <div id="contact_form_pop<?php echo $counter; ?>">
                                <?php
                                echo '<form action="" method="post" id="contactformfix' . $counter . '">';
                                echo '<p>';
                                echo 'Your Name (required) <br />';
                                echo '<input id="namerr' . $counter . '" type="text" name="namerr' . $counter . '"  value="" size="40" />';
                                echo '<p>';
                                echo 'Phone Number (required) <br />';
                                echo '<input id="pnumber' . $counter . '" type="tel" name="pnumber' . $counter . '"  value="" />';
                                echo '</p>';
                                echo '<p>';
                                echo 'Your Email (required) <br />';
                                echo '<input type="email" name="cf-email' . $counter . '" id="cf-email' . $counter . '" value="' . ( isset($_POST["cf-email"]) ? esc_attr($_POST["cf-email"]) : '' ) . '" size="40" />';
                                echo '</p>';
                                echo '<p>';
                                echo 'Subject (required) <br />';
                                echo '<input type="text" id="cf-subjectcf-email' . $counter . '" name="cf-subjectcf-email' . $counter . '"  value="' . $postCatNames . ' - ' . $eventDateRange . ' - ' . $fieldkey['event_opponent'] . '" size="100" readonly />';
                                echo '</p>';
                                echo '<p>';
                                echo 'Your Message<br />';
                                echo '<textarea rows="10" cols="35" id="cf-message' . $counter . '" name="cf-message' . $counter . '">' . ( isset($_POST["cf-message"]) ? esc_attr($_POST["cf-message"]) : '' ) . '</textarea>';
                                echo '</p>';
                                echo '<p><input type="button" name="cf-submitted" id="contactbutton"  value="Send" onclick="return makeSearch(' . $counter . ')" /></p>';
                                echo '<p  id="contact-msg' . $counter . '"></p>';
                                echo '</form>';
                                ?></div>
                        </div>
                    </td>
                </tr>
                <?php
                $counter++;
            }
        }
    }
}
    ?>
