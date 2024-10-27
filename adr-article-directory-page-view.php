<?php

$options = get_option('article_directory');

$exclude_cat            = array($options['exclude_cats']);
$show_parent_count      = $options['show_parent_count'];
$show_child_count       = $options['show_child_count'];
$hide_empty             = $options['hide_empty'];
$desc_for_parent_title  = $options['desc_for_parent_title'];
$desc_for_child_title   = $options['desc_for_child_title'];
$child_hierarchical     = $options['child_hierarchical'];
$column_count           = $options['column_count'];
$sort_by                = $options['sort_by'];
$sort_direction         = $options['sort_direction'];
$no_child_alert         = $options['no_child_alert'];
$show_child             = $options['show_child'];
$maximum_child          = $options['maximum_child'];

global $wpdb;
$cal_tree = array();
if (!$column_count) $column_count = 1;

global $rssfeeds;
$feed = '';
if ($rssfeeds) {
	$feed = 'RSS';
	$show_parent_count = 0;
	$show_child_count = 0;
}

if ($sort_by == 0) {
	$order_by = $orderby = 'name';
}
elseif ($sort_by == 1) {
	$order_by = 'term_order'; $orderby = 'term_group';
}


$parent_cats = $wpdb->get_results("SELECT *
	FROM " . $wpdb->term_taxonomy . " term_taxonomy
	LEFT JOIN " . $wpdb->terms . " terms
	ON terms.term_id = term_taxonomy.term_id
	WHERE term_taxonomy.taxonomy = 'category' AND term_taxonomy.parent = 0 " .
	( count($exclude_cat) ? ' AND terms.term_id NOT IN (' . implode(',', $exclude_cat) . ') ' : '' )
	. " ORDER BY terms." . $order_by);

foreach ($parent_cats as $parent) {

	$summ = "SELECT SUM(count) FROM " . $wpdb->term_taxonomy . " WHERE taxonomy = 'category' AND parent = " . $parent->term_id;

//		$child_summ = mysql_result(mysql_query($summ),0); //считаем кол-во статей в подрубрике 1-го уровня
	$aQueryResult_ChildSum = $wpdb->get_results( $summ, ARRAY_N );
	$child_summ = isset( $aQueryResult_ChildSum[0][0] )? $aQueryResult_ChildSum[0][0] : 0;

	$catid = $wpdb->get_var("SELECT term_ID FROM " . $wpdb->term_taxonomy . " WHERE taxonomy = 'category' AND parent = " . $parent->term_id); //определяем ID подрубрики 1-го уровня

	$sub_child_summ = (int)$catid ? $wpdb->get_var("SELECT SUM(count) FROM " . $wpdb->term_taxonomy . " WHERE taxonomy = 'category' AND parent = " . $catid) : 0; //считаем кол-во статей в подрубрике 2-го уровня

	$cat_name = get_the_category_by_ID($parent->term_id);

	$descr = sprintf(__("View all posts filed under %s"), $cat_name);

	if ($desc_for_parent_title == 1) {
		if (empty($parent->description)) {
			$descr = $descr;
		} else {
			$descr = $parent->description;
		}
	}

	$child_summ += $parent->count;  //прибавляем к сумме родительской рубрики сумму в подрубрике 1-го уровня
	$child_summ += $sub_child_summ; //прибавляем к сумме родительской рубрики сумму в подрубрике 2-го уровня

	if ($show_parent_count == 1) {
		$parent_count = ' (' . $child_summ . ')';
	} else {
		$parent_count = '';
	}

	$cal_tree[] = array(
		'cat' => array(
			'href'  => get_category_link($parent->term_id),
			'title' => $descr,
			'name'  => $cat_name,
			'count' => $parent_count
		),
		'cats'=> wp_list_categories( ( count($exclude_cat) ? 'exclude=' . implode(',', $exclude_cat) : '' ) . '&orderby=' . $orderby . '&show_count=' . $show_child_count . '&hide_empty=' . $hide_empty . '&use_desc_for_title=' . $desc_for_child_title . '&child_of=' . $parent->term_id . '&title_li=&hierarchical=' . $child_hierarchical . '&echo=0&feed=' . $feed)
	);

}

$_tree = array();
$count = count($cal_tree);
if ($sort_direction) {
	$line_count = ceil( $count / $column_count );
	$limit      = $count - $line_count * $column_count % $count;
	for ($i = 0; $i < $count; $i++) {
		$index = floor($i / $line_count) + ($limit && $i > $limit ? 1 : 0);
		if (!isset($_tree[$index])) { $_tree[$index] = array(); }
		$_tree[$index][] = &$cal_tree[$i];
	}
}
else {
	for ($i = 0; $i < $count; $i++) {
		$index = $i % $column_count;
		if (!isset($_tree[$index])) { $_tree[$index] = array(); }
		$_tree[$index][] = &$cal_tree[$i];
	}
}


if (count($_tree)) {

	$write = '
<div id="categories">';

	for ($j = 0, $count = count($_tree); $j < $count; $j++) {

		// вывод столбца
		$write .= '
		<ul class="column">';

		// вывод рубрик для столбца
		for ($i = 0, $icount = count($_tree[$j]); $i < $icount; $i++) {

			$catcount = $i + 11;
			if ($j == 1) $catcount = $i + 21;
			if ($j == 2) $catcount = $i + 31;
			if ($j == 3) $catcount = $i + 41;
			if ($j == 4) $catcount = $i + 51;

			if ($rssfeeds) {

				$write .= '

			<li id="cat-'. $catcount .'"><div><a href="' . esc_html($_tree[$j][$i]['cat']['href']) . '" title="' . esc_html($_tree[$j][$i]['cat']['title']) . '">' . esc_html($_tree[$j][$i]['cat']['name']) . '</a> (<a href="' . esc_html($_tree[$j][$i]['cat']['href']) . '/feed/" title="' . esc_html($_tree[$j][$i]['cat']['title']) . '">RSS</a>)</div>';

			} else {

				$write .= '

			<li id="cat-'. $catcount .'"><div><a href="' . esc_html($_tree[$j][$i]['cat']['href']) . '" title="' . esc_html($_tree[$j][$i]['cat']['title']) . '">' . esc_html($_tree[$j][$i]['cat']['name']) . '</a>' . $_tree[$j][$i]['cat']['count'] . '</div>';

			}

			// see wp-includes/category-template.php::276
			// $output .= '<li>' . __("No categories") . '</li>';
			$nocats = '<li>' . __("No categories") . '</li>';

			if ($no_child_alert == 1) $nocats = '';

			if ($_tree[$j][$i]['cats'] != $nocats && $show_child == 1) {

				$write .= '
			<ul class="sub-categories">';
				if ($maximum_child) {
					for ($s = 0, $strlen = strlen($_tree[$j][$i]['cats']), $counter = $maximum_child+1, $slevel = 0; $s < $strlen; $s++) {
						if (!$slevel && substr($_tree[$j][$i]['cats'], $s, 3) == '<li' && !(--$counter)) break;
						else if (substr($_tree[$j][$i]['cats'], $s, 3) == '<ul') $slevel++;
						else if ($slevel && substr($_tree[$j][$i]['cats'], $s-4, 4) == '/ul>') $slevel--;
						else if (!$slevel) $write .= substr($_tree[$j][$i]['cats'], $s, 1);
					}
					$licount = substr_count($_tree[$j][$i]['cats'], '<li');
					if ( ($licount > $maximum_child) && ($_tree[$j][$i]['cats'] != '<li>' . __("No categories") . '</li>') ) {
						$write .= '<li>...</li>';
					}
				}
				else $write .= $_tree[$j][$i]['cats'];

				$write .= '
			</ul>';

			}
			$write .= '
		</li>';

		}

		// печать одного столбца
		$write .= '
	</ul><!-- .column -->' . "\r\n";

	}

	$write .= '
</div><!-- #categories -->' . "\r\n";

	if ( $echo == true )
		echo $write;
	else
		return $write;

}