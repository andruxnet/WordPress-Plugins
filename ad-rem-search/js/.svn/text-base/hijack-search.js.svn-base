var ajax_request;

jQuery(document).ready(function($) {
	/* override WP search form submit event with our own */
	$("#searchform").live("submit", function() {
		var search_terms = $("#s").val();
		$("#s").val("");

		post_search(search_terms);

		return false;
	});

	/* override pagination links to use ajax */
	/*$("#content").find("a[href*=admin-ajax]").live("click", function() {
		var page_no = $(this).attr("href").split("=")[1] ? $(this).attr("href").split("=")[1] : 1;
		var search_terms = $("#search_terms").html().replace(/\"/g, "");
		$(this).attr("href", "javascript:post_search('" + search_terms + "'," + page_no + ");");
	});*/

});

/* this will post the search form and show the result in our #content div */
function post_search(search_terms) {
	var data = {
		action: "hijack_search",
		search_terms: search_terms
	};

	if ( ajax_request )
		ajax_request.abort();

	ajax_request = jQuery.post( ajax.ajaxurl, data, function( response ) {
		jQuery("#content").html( response );

		/* for when we add pagination
		if ( page_no > 1 ) {
			$(".pagination").find("a[href*=admin-ajax]").removeClass("current");
			$(".pagination").find("a[href$=page\\=" + page_no + "]").addClass("current");
			$(".navigation.right").attr("href", "javascript:post_search('" + search_terms + "'," + (page_no + 1) + ");");
			$(".navigation.left").attr("href", "javascript:post_search('" + search_terms + "'," + (page_no - 1) + ");");
		}
		*/

		/* make all result links to open in a new page */
		jQuery(".result").find("a").attr( "target", "_new" );
	});

}