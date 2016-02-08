( function() {
	tinymce.create( 'tinymce.plugins.[plugin]', {
		init : function( ed, url ) {
			ed.addCommand( 'mce[plugin]', function() {
				window.parent.tinyMCE.activeEditor.execCommand( 'mceInsertContent', 0, '[shortcode-text]');
				/*ed.windowManager.open({
				file : url + '/tinymce.html',
				width : 480,
				height : 180,
				inline : 1,
				title : 'Window Title'
				}, {});*/
			});

			ed.addButton( 'plugin', {
				cmd : 'mce[plugin]',
				image : url + '/images/coreg-logo.jpg'
			});
		},
		getInfo : function() {
			return {
				longname : 'Plugin Name',
				author : 'Andres Olvera',
				authorurl : 'http://andrux.net',
				infourl : 'http://andrux.net',
				version : "1.0"
			};
		}
	});

	tinymce.PluginManager.add( 'plugin', tinymce.plugins.[plugin] );
})();