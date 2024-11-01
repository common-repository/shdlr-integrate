=== Shdlr Integrate ===
Contributors: adi3890, tariquesani
Tags: shdlr, conference, integrate, shortcode, schedule, shdlr.com
Requires at least: 3.4
Tested up to: 3.6.1
Stable tag: 1.0
License: GPL v2 or later

Integrates schedule from shdlr.com into your wordpress site

== Description ==

This plugin will help conference owners to integrate conference schedules generated using shdlr.com into their wordpress sites.
The plugin works for both free, trial accounts and pro accounts of [Shdlr](http://shdlr.com).

The integration is done via shortcode. The admin section of the plugin provides an interface to create the shortcode needed.

NOTE: This plugin communicates with [shdlr.com](http://shdlr.com) site to validate token you obtain from shdlr.com. The shortcode you place in your post integrates schedule from shdlr.com for your conference in your post via an iframe.
 

== Installation ==

You can install this plugin directly from your WordPress dashboard:

 1. Go to the *Plugins* menu and click *Add New*.
 2. Search for *Shdlr Integrate*.
 3. Click *Install Now* next to the *Shdlr Integrate* plugin.
 4. Activate the plugin.

Alternatively, see the guide to [Manually Installing Plugins](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Usage =

Once you have created a conference schedule at [shdlr.com](http://shdlr.com) you can get a token for the wordpress plugin from Shdlr admin panel. Alternatively if you just want to try this plugin with the demo conference located at [http://demo.shdlr.com](http://demo.shdlr.com), use 'demo-token' as the token name.

Add your token in plugin's admin page, click on 'save' button.
If token validates successfully, plugin will generate a shortcode like

`[shdlr conf_id='demo']`

You can further modify shortcode using given options to apply different style.
Paste this shotcode in a Page or Post and integrate your schedule.

We recommend you to use full width template for best view

== Screenshots ==

1. Get your shdlr.com token
2. Plugin admin page
3. Token validates successfully
4. Paste shortcode in a Page or Post
5. Integrated schedule

== Changelog ==

= 1.0 =
* Initial release.
