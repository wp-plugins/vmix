<?php
//if (!defined('ABSPATH')) define('ABSPATH', dirname(__FILE__).'/../../../');
//require_once('../../../wp-admin/admin.php');

if (!defined('ABSPATH')) include_once('./../../../wp-blog-header.php');
require_once(ABSPATH . '/wp-admin/admin.php');

if (isset($_POST['action'])) {

$mimes = is_array($mimes) ? $mimes : apply_filters('upload_mimes', array (
		'avi' => 'video/avi',
		'mov|qt' => 'video/quicktime',
		'mpeg|mpg|mpe' => 'video/mpeg',
		'asf|asx|wax|wmv|wmx' => 'video/asf',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv'
	));

$overrides = array('action'=>'save','mimes'=>$mimes);

$file = wp_handle_upload($_FILES['video'], $overrides);

if ( !isset($file['error']) ) {

	$url = $file['url'];
	$type = $file['type'];
	$file = $file['file'];
	$filename = basename($file);

	// Construct the attachment array
	$attachment = array(
		'post_title' => $_POST['videotitle'] ? $_POST['videotitle'] : $filename,
		'post_content' => $_POST['descr'],
		'post_status' => 'attachment',
		'post_parent' => $_GET['post'],
		'post_mime_type' => $type,
		'guid' => $url
		);

	// Save the data
	$id = wp_insert_attachment($attachment, $file, $post);

	if ( preg_match('!^image/!', $attachment['post_mime_type']) ) {
		// Generate the attachment's postmeta.
		$imagesize = getimagesize($file);
		$imagedata['width'] = $imagesize['0'];
		$imagedata['height'] = $imagesize['1'];
		list($uwidth, $uheight) = get_udims($imagedata['width'], $imagedata['height']);
		$imagedata['hwstring_small'] = "height='$uheight' width='$uwidth'";
		$imagedata['file'] = $file;

		add_post_meta($id, '_wp_attachment_metadata', $imagedata);

		if ( $imagedata['width'] * $imagedata['height'] < 3 * 1024 * 1024 ) {
			if ( $imagedata['width'] > 128 && $imagedata['width'] >= $imagedata['height'] * 4 / 3 )
				$thumb = wp_create_thumbnail($file, 128);
			elseif ( $imagedata['height'] > 96 )
				$thumb = wp_create_thumbnail($file, 96);

			if ( @file_exists($thumb) ) {
				$newdata = $imagedata;
				$newdata['thumb'] = basename($thumb);
				update_post_meta($id, '_wp_attachment_metadata', $newdata, $imagedata);
			} else {
				$error = $thumb;
			}
		}
	} else {
		add_post_meta($id, '_wp_attachment_metadata', array());
	}

	$_GET['tab'] = 'select';
  }

}

if (! current_user_can('edit_others_posts') )
	$and_user = "AND post_author = " . $user_ID;
$and_type = "AND (post_mime_type = 'video/avi' OR post_mime_type = 'video/quicktime' OR post_mime_type = 'video/mpeg' OR post_mime_type = 'video/asf' OR post_mime_type = 'video/x-flv' OR post_mime_type = 'application/x-shockwave-flash')";
if ( 3664 <= $wp_db_version )
  $attachments = $wpdb->get_results("SELECT post_title, guid FROM $wpdb->posts WHERE post_type = 'attachment' $and_type $and_user ORDER BY post_date_gmt DESC LIMIT 0, 10", ARRAY_A);
else
  $attachments = $wpdb->get_results("SELECT post_title, guid FROM $wpdb->posts WHERE post_status = 'attachment' $and_type $and_user ORDER BY post_date_gmt DESC LIMIT 0, 10", ARRAY_A);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo _e('Embed VMIX Video','embeddedvideo'); ?></title>

<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
<script language="javascript" type="text/javascript" src="embedded-video.js"></script>
<style type="text/css">
	#portal_insert, #portal_cancel, #select_insert, #select_cancel, #upload_insert, #upload_cancel, #remote_insert, #remote_cancel {
				font: 13px Verdana, Arial, Helvetica, sans-serif;
				height: auto;
				width: auto;
				background-color: transparent;
				background-image: url(../../../../../wp-admin/images/fade-butt.png);
				background-repeat: repeat;
				border: 3px double;
				border-right-color: rgb(153, 153, 153);
				border-bottom-color: rgb(153, 153, 153);
				border-left-color: rgb(204, 204, 204);
				border-top-color: rgb(204, 204, 204);
				color: rgb(51, 51, 51);
				padding: 0.25em 0.75em;
	}
	#portal_insert:active, #portal_cancel:active, #select_insert:active, #select_cancel:active, #upload_insert:active, #upload_cancel:active, #remote_insert:active, #remote_cancel:active {
				background: #f4f4f4;
				border-left-color: #999;
				border-top-color: #999;
	}
</style>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

<script>
//var atoken = 'a89794b752d37924121f1d9b6ae2483b';
//var player_id = '78d55b0ede7357208ee20bb98549fb38';

var atoken = '<?php echo get_option('vmix_atoken'); ?>';
var player_id = '<?php echo get_option('vmix_player_id'); ?>';

function vmix_insert_link(media_id){
	if(window.tinyMCE) {
		// get the media token from the id
		var url = 'http://api.vmixcore.com/apis/media.php?action=getMedia&atoken='+atoken+'&output=jsonp&callback=?&media_id='+media_id;
		$.getJSON(url, function(data){
			var media_token = data.token;
			var ed = tinyMCE.activeEditor;

			var str = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="390" height="332" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="name" value="player_swf"/><param name="allowScriptAccess" value="always"/><param name="flashvars" value="player_id=' + player_id + '&amp;token=' + media_token + '" /><param name="src" value="http://cdn-akm.vmixcore.com/core-flash/UnifiedVideoPlayer/UnifiedVideoPlayer.swf?player_id=' + player_id + '" /><param name="wmode" value="transparent" /><param name="allowfullscreen" value="true" /><embed type="application/x-shockwave-flash" width="390" height="332" src="http://cdn-akm.vmixcore.com/core-flash/UnifiedVideoPlayer/UnifiedVideoPlayer.swf?player_id=' + player_id + '" allowfullscreen="true" wmode="transparent" allowScriptAccess="always" flashvars="player_id=' + player_id + '&amp;token=' + media_token + '" name="player_swf"></embed></object>';

			ed.execCommand('mceInsertContent', false, str);
			ed.execCommand('mceCleanup');
			tinyMCEPopup.close();
		});
	}
}

function vmix_preview(media_id){
	var url = 'http://api.vmixcore.com/apis/media.php?action=getMedia&atoken='+atoken+'&output=jsonp&callback=?&media_id='+media_id;
	$.getJSON(url, function(data){
		var media_token = data.token;
		var str = '<div id="vmix_preview_content">';
		str += '<div style="text-align:right; padding-bottom:6px; padding-right:6px"><a href="#" onclick="$(\'#vmix_preview_content\').remove(); return false" style="text-decoration:none"><b>X</b></a></div>';
		str += '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="320" height="240" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="name" value="player_swf"/><param name="allowScriptAccess" value="always"/><param name="flashvars" value="player_id=' + player_id + '&amp;token=' + media_token + '" /><param name="src" value="http://cdn-akm.vmixcore.com/core-flash/UnifiedVideoPlayer/UnifiedVideoPlayer.swf?player_id=' + player_id + '" /><param name="wmode" value="transparent" /><param name="allowfullscreen" value="true" /><embed type="application/x-shockwave-flash" width="320" height="240" src="http://cdn-akm.vmixcore.com/core-flash/UnifiedVideoPlayer/UnifiedVideoPlayer.swf?player_id=' + player_id + '" allowfullscreen="true" wmode="transparent" allowScriptAccess="always" flashvars="player_id=' + player_id + '&amp;token=' + media_token + '" name="player_swf"></embed></object></div>';

		$('#vmix_preview').html(str);
	});
}

function vmix_search(q){
	var query = '{"queries":[{"field":["title","description"],"value":["'+q+'"],"match_type":"any","sign":true},{"field":"status_id","value":[20],"match_type":"any","sign":true},{"field":"class_id","value":[1],"match_type":"any","sign":true}]}';
//	var query = '{"queries":[{"field":"description","value":["football"],"sign":true,"match_type":"any"},{"field":"play_count","value":[25,99],"sign":true,"match_type":"range"}]}';

	var url = 'http://searchapi.vmixcore.com/search/search.php?action=media&queries='+query+'&output=jsonp&callback=?&atoken='+atoken;
	$.getJSON(url, function(data){
//console.log(data);
		var str = '';
		for (i in data.result){
			str += '<div class="result"><b>'+data.result[i].title+'</b>';
			str += ' (<a href="#" onclick="vmix_preview('+data.result[i].media_id+'); return false">preview</a>) (<a href="#" onclick="vmix_insert_link('+data.result[i].media_id+'); return false">insert</a>)';
			str += '<div>'+data.result[i].description+'</div></div>';
		}
		$('#vmix_results').html(str);
	});
}
</script>

<style>
#vmix_results .result {padding:3px; border:1px solid #999; margin-bottom:5px; background:#fff}
#vmix_preview {position:fixed}
#vmix_preview_content {background:#fff; padding:3px; border:solid 1px #000}
</style>
</head>

<body id="vmix">

<?php if (get_option('vmix_atoken') && get_option('vmix_player_id')){ ?>
<form onsubmit="vmix_search(this.q.value); return false">
Enter a term that appears in the title or description of the video you would like to embed.<br/><br/>
Search: <input type="text" name="q"/> <input type="submit" value="Go"/>
</form>

<div id="vmix_preview">
</div>

<div id="vmix_results">
</div>

<?php } else { // user has not set up their options yet...make them ?>
 
It looks like you haven't set up your <a href="options-general.php?page=vmix_options_page">VMIX configuration yet</a>.  You must do this prior to using the plugin.<br/><br/>

<a target="_top" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=vmix_options_page">Configure VMIX Settings</a>

<?php } ?>

</body>
</html>
