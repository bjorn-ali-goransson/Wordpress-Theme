<?php



/* PAGINATION */

function my_pagination(){
  global $wp_query;

  $big = 999999999; // need an unlikely integer

  $links = paginate_links( array(
	  'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	  'format' => '?paged=%#%',
    'type' => 'array',
	  'current' => max( 1, get_query_var('paged') ),
	  'total' => $wp_query->max_num_pages,
	  'prev_text' => '&laquo;',
	  'next_text' => '&raquo;',
  ));

  if(empty($links)){
    return;
  }

  $links_html = '<ul class="pagination"><li>';
  $links_html .= join('</li><li>', $links);
  $links_html .= '</ul>';

  $links_html = str_replace('<li><span class=\'page-numbers current\'>1</span>', '<li class="disabled"><span>&laquo;</span></li><li><span class=\'page-numbers current\'>1</span>', $links_html);
  $links_html = str_replace('<li><span class="page-numbers dots">', '<li class="disabled"><span class=\'page-numbers dots\'>', $links_html);
  $links_html = str_replace('<li><span class=\'page-numbers current\'>', '<li class="active"><span class=\'page-numbers current\'>', $links_html);

  echo $links_html;
}