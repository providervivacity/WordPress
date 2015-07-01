<?php
//upcoming_matchdays shortcut code start
function list_upcoming_matchdays() {
       $args = array(
        'type' => 'event',
        'child_of' => '',
        'parent' => '0',
        'orderby' => 'name',
        'order' => 'DESC',
        'hide_empty' => 0,
        'hierarchical' => 1,
        'exclude' => '',
        'include' => '',
        'number' => '',
        'taxonomy' => 'event_category'
    );
    $categories = get_categories($args);
    $mainCatTermId = array();
    foreach ($categories as $category) {
        $mainCatTermId[] = $category->term_id;
    }
    $parChildCatTermId = array();
    $monthArr = array();
    $DateArr = array();
    for ($i = 0; $i < count($mainCatTermId); $i++) {
        $chidCategory = get_term_children($mainCatTermId[$i], 'event_category');
        $custom_filed_valueM = get_field('event_category_setting', 'event_category_' . $mainCatTermId[$i]);
        if ($custom_filed_valueM) {
            $parChildCatTermId[] = $mainCatTermId[$i];
        } else {
            foreach ($chidCategory as $child) {
                $term = get_term_by('id', $child, 'event_category');
                $custom_filed_value = get_field('event_category_setting', 'event_category_' . $term->term_id);
                if ($custom_filed_value) {
                    $parChildCatTermId[] = $term->term_id;
                }
            }
        }
    }
    $j = 0;
    for ($i = 0; $i < count($parChildCatTermId); $i++) {
        $custom_filed_val = get_field('event_category_setting', 'event_category_' . $parChildCatTermId[$i]);
        $term = get_term_by('id', $parChildCatTermId[$i], 'event_category');
        //child url get
        $subCatUrl = '<a href="' . get_term_link($term->term_id, 'event_category') . '">' . $term->name . '</a> ';
        $subCatName = $term->name;
        //parent url get 
        if ($term->parent != 0) {
            $parent_term = get_term($term->parent, 'event_category');
            $parCatUrl = get_term_link($parent_term->name, 'event_category');
            $parCatName = $parent_term->name;
            }
        $venue = get_field('venue', 'event_category_' . $parChildCatTermId[$i]);
        foreach ($custom_filed_val as $val) {
            $dayMonth = $val['event_start_date'] . ' ' . $val['event_month_year'];
            $event_opponent = $val['event_opponent'];
            $newformat = $dayMonth;
            $DateArr[$j] = $dayMonth;
            $eventData[$j][0] = $newformat;
            $eventData[$j][1] = $val['event_kick_off'];
            $eventData[$j][2] = $parCatUrl;
            $eventData[$j][3] = $venue;
            $eventData[$j][4] = $parCatName;
            $eventData[$j][5] = $subCatName;
            $eventData[$j][6] = $event_opponent;
            $monthArr[$j] = $val['event_month_year'];
            $j++;
        }
    }
    $temp = array();
    for ($i = 0; $i < count($eventData); $i++) {
        for ($j = 0; $j < count($eventData) - 1; $j++) {
            $s = strtotime($eventData[$j][0]);
            $newformat1 = date('Y-m-d', $s);
            $r = strtotime($eventData[$j + 1][0]);
            $newformat2 = date('Y-m-d', $r);
            if ($newformat1 > $newformat2) {
                $temp = $eventData[$j];
                $eventData[$j] = $eventData[$j + 1];
                $eventData[$j + 1] = $temp;
                unset($temp);
            }
        }
    }
    $w = array();
    $k = 0;
    for ($i = 0; $i <= count($eventData) - 1; $i++) {
        $tmp = explode(" ", $eventData[$i][0]);
        $w[$k] = $tmp[1] . "  " . $tmp[3];
        $k++;
    }
    $sortMonthYear = array_values(array_unique($w));
    $monthArr = array_values(array_unique($monthArr));
    $y = 0;
    $value = 1;
    for ($y = 0; $y < count($eventData); $y++) {
        $tm = explode(" ", $eventData[$y][0]);
        $my = $tm[1] . " " . $tm[3];
        $my = strtotime($my);
        $my = date('m-Y', $my);
        $time = strtotime($eventData[$y][0]);
        $newformat = date('Y-m-d', $time);
        $diff = date_diff(date_create(date('Y-m-d')), date_create($newformat));
        $diff = $diff->format("%R%a");
        if ($diff >= 0) {
            if ($value < 6) {
                $parCatLink = '<a href="' . $eventData[$y][2] . '">View</a>';
                echo'<div  >
                 <h1 style="color: #477079; font-size: 16px; margin:0 auto; padding-top:10px;"><a href="' . $eventData[$y][2] . '" style="color:#477079;">' . $eventData[$y][6] . '</a><span class="upcomingButton"><a href="' . $eventData[$y][2] . '"><span class="upcoming_arrow"><i class="fa fa-chevron-right uparrow"></i></span></a></span></h1>                        
                 <p class="upcomingP" ><span class="upcomingDate">' . $eventData[$y][0] . '</span><span class="upcomingKik">' . $eventData[$y][1] . ' </span><span class="upcomingVenue">' . $eventData[$y][3] . ' </span></p></div>';
               $value++;
            }
        }
    }
}
add_shortcode('upcoming_matchdays', 'list_upcoming_matchdays');
//upcoming_matchdays shortcut code end
