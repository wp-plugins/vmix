=== VMIX ===
Contributors: CydeSwype
Donate link: http://www.vmix.com/
Tags: embedding, video, embed, vmix
Requires at least: 2.5
Tested up to: 2.9.2
Stable tag: 1.0.2

Easy embedding of videos from VMIX.

== Description ==

Have a VMIX account and want to embed videos into your WordPress blog posts?  Then this plugin is for you!  Just click the blue "V" icon in the WYSIWYG editor (TinyMCE) and you'll get a popup where you can search for related videos by keyword (searches title and description).  You can then preview and embed any of the results.

This is an initial stab at a plugin.  It's not pretty or complete but it's functional.  Please be gentle in the rating and criticism.  Go ahead and suggest new features and I'll try to implement them.

If you don't have a VMIX account and would like to get one, visit http://vmix.com/

== Installation ==

1. Upload vmix.zip to the '/wp-content/plugins/' directory
2. Create a "vmix" directory
3. Unzip the contents of vmix.zip into the newly created vmix directory
4. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
Q. How do I get my VMIX auth token?
A. In the VMIX Admin Console, go to Applications -> Widgets and view the embed code for any widget there.  The atoken in the embed code will contain your auth token.

== Configuration ==

The configuration options include a player ID (this will go away in future versions when you'll be able to select a player instance when you embed each video) and an auth token.  Auth tokens can be copied from any widget.  Just log into the VMIX Admin Console and go to Applications -> Widgets and click on any of the widgets.  Then go to the "Embed Code" tab and look in the code for an "auth_token=xxxxx" and copy the value there.  Alternatively, if that's too cumbersome, contact your Client Service Manager and they'll get it for you.

== Screenshots ==

1. Icon in the WYSIWYG editor.
2. Search for a video by title or description and get results.
3. Preview the video.
4. Embed it on your site.

== Changelog ==

= 1.0.2 =
* Added confirmation message when config options changed
* Prettier preview player, bigger popup, added some nicer copy
* Now closing the popup when video is embedded (assuming user is finished with the popup at that point)

= 1.0.1 =
* Bug fix: JavaScript include being echoed in too many cases...was interrupting a PHP header directive on story submission

== Upgrade Notice ==

= 1.0.2 =
Minor bug fixes and features, messagine, copy, etc.

= 1.0.1 =
Bug fixes if you were experiencing PHP errors when trying to create/edit a story

