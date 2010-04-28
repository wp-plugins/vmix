<?php

if ( isset($_REQUEST['process']) ) {
	update_option('vmix_atoken', wp_kses(stripslashes(trim($_REQUEST['vmix_atoken'])), array()));
	update_option('vmix_player_id', wp_kses(stripslashes(trim($_REQUEST['vmix_player_id'])), array()));
	$success = 1;
}

$vmix_atoken = get_option('vmix_atoken');
$vmix_player_id = get_option('vmix_player_id');

?>

<?php if ($success == 1){ ?>
<div style="padding:10px; border:1px #090 solid; background:#efe; margin-top:10px">Successfully updated configuration for VMIX plugin</div>
<?php } ?>

<form method="post">

<div style="border:1px solid #999; padding:5px; background:#fff; margin-top:16px; width:400px">

<h2>VMIX Options</h2>

Auth Token: <input type="text" name="vmix_atoken" value="<?php echo $vmix_atoken; ?>"/><br/>
Player ID: <input type="text" name="vmix_player_id" value="<?php echo $vmix_player_id; ?>"/><br/>

<input type="submit" name="process" value="Save"/>
</div>

</form>
