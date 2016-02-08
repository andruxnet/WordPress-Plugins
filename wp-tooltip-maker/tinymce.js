( function() {
	tinymce.create( 'tinymce.plugins.cluetip', {
		init : function( ed, url ) {
			ed.addCommand( 'mceCluetip', function() {
				if ( ed.selection.getContent().length > 0 ) {
					ed.windowManager.open({
						file: ajaxurl + '?action=select_image',
						width: 340,
						height: 120,
						inline: 1
					}, {} );
				}
			});

			ed.addButton( 'cluetip', {
				cmd : 'mceCluetip',
				image : url + '/wp-tooltip-maker.png'
			});
		},
		getInfo : function() {
			return {
				longname : 'WP Tooltip Maker',
				author : 'Andres Olvera',
				authorurl : 'http://andrux.net',
				infourl : 'http://andrux.net',
				version : "1.0"
			};
		}
	});

	tinymce.PluginManager.add( 'cluetip', tinymce.plugins.cluetip );
})();