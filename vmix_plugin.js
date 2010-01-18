(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('vmix');
	
	tinymce.create('tinymce.plugins.vmixPlugin', {
		init : function(ed, url) {
			var t = this;
			t.editor = ed;
			ed.addCommand('mce_vmix', t._vmix, t);
			ed.addButton('vmix',{
				title : 'vmix.desc', 
				cmd : 'mce_vmix',
				image : url + '/img/vmix-button.png'
			});
		},
		
		getInfo : function() {
			return {
				longname : 'VMIX Plugin for Wordpress',
				author : 'Ian D. Miller',
				authorurl : 'http://vmix.com/',
				infourl : 'http://vmix.com/',
				version : '1.0'
			};
		},
		
		// Private methods
		_vmix : function() { // open a popup window
			vmix_insert();
			return true;
		}
	});

	// Register plugin
	tinymce.PluginManager.add('vmix', tinymce.plugins.vmixPlugin);
})();
