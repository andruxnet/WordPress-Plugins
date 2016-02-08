<?php

	/* get the results from the database */
	global $wpdb;

	$post_data = $_POST["search_terms"];

	/* make all search terms to be required */
	$search_terms = "+" . str_replace( " ", " +", $post_data );

	$query = $wpdb->prepare( "
			SELECT ID, post_title, post_date, post_content, post_author,
				( MATCH( post_title ) AGAINST ( '%s' ) * 1 ) +
				( MATCH( post_content ) AGAINST ( '%s') * 10 ) AS score
			FROM $wpdb->posts
			WHERE MATCH ( post_title, post_content ) AGAINST ('%s' IN BOOLEAN MODE )
			ORDER BY score DESC",
		$search_terms, $search_terms, $search_terms );

	$result = $wpdb->get_results( $query );

?>

<div id="ad-rem-results">
	<h1><?php _e( "Search results for: $post_data" ); ?></h1>
	<hr />
	<?php foreach ( $result as $p ): ?>
	<div class="result">
		<h2><a href="<?php _e( get_permalink( $p->ID ) ); ?>"><?php _e( $p->post_title ); ?></a></h2>
		<p>Category:
			<?php foreach ( get_the_category( $p->ID ) as $category ): ?>
				<?php _e( sprintf( "<a href='%s' title='View all posts in %s'>%s</a> ",
								get_category_link( $category->term_id ),
								$category->name,
								$category->name ) ); ?>
			<?php endforeach; ?>
		</p>
		<p>Score: <?php _e( round( $p->score, 2 ) ); ?></p>
		<p>By <?php
				$author = get_userdata( $p->post_author, true );
				_e( sprintf( "<a href='%s' title='View all posts by %s'>%s</a>",
						get_author_posts_url( $author->ID ),
						$author->display_name,
						$author->first_name . " " . $author->last_name ) ); ?>
			on <?php _e( date( "m-d-Y", strtotime( $p->post_date ) ) ); ?>
		</p>
		<p>
			<?php _e( sprintf( "%s <a href='%s'>More</a>",
							substr( strip_tags( $p->post_content ), 0, 400 ),
							get_permalink( $p->ID ) ) ); ?>
		</p>
	</div>
	<?php endforeach; ?>
	<hr />
</div>