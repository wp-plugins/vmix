<?php

if ( isset($_REQUEST['process']) ) {
	update_option('vmix_atoken', wp_kses(stripslashes($_REQUEST['vmix_atoken']), array()));
	update_option('vmix_player_id', wp_kses(stripslashes($_REQUEST['vmix_player_id']), array()));
}

$vmix_atoken = get_option('vmix_atoken');
$vmix_player_id = get_option('vmix_player_id');

?>

<form method="post">

<div style="border:1px solid #999; padding:5px; background:#fff; margin-top:16px; width:400px">

<h2>VMIX Options</h2>

Auth Token: <input type="text" name="vmix_atoken" value="<?php echo $vmix_atoken; ?>"/><br/>
Player ID: <input type="text" name="vmix_player_id" value="<?php echo $vmix_player_id; ?>"/><br/>

<input type="submit" name="process" value="Save"/>
</div>

</form>
