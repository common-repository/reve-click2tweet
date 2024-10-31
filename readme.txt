=== Reve Click2Tweet ===
Stable tag:			1.3.0
Tags:				click to tweet, twitter, twitter share, share buttons, shortcode
Requires PHP:       5.6
Requires at least:	4.0
Tested up to:		5.6.0
Contributors:		promostudio
Donate link:		http://www.promostudio.es/support-revec2t
License:			GPL2
License URI:		http://www.gnu.org/licenses/gpl-2.0.html


Add totally custom, responsive and fast Click to tweet boxes to your WordPress site.


== Description ==

= Add totally custom, responsive and fast Click to tweet boxes to your WordPress site. =

= Reve Click2Tweet is totally free, superfast and very light-weight (only 58Kb!). =


== How to use ==

Simply insert the shortcode `[revec2t]` in any post, page or HTML/text widget. To add it you can use the Reve Click2Tweet classic editor button, if option is enabled (by default).

The shortcode will be replaced by the linkable box that allows your visitors to share any content on Twitter.

You can use the following attributes into the shortcode: text, url, hashtags, via, label, icon and short.

All attributes are optional. If you don't set the text, the shortcode will display the URL to share. Also, you can set manually the URL, or the shortcode will generate the current page URL.

The attributes hashtags, via, label, icon and short allows you to custom each shortcode. If set, they will overwrite the saved options. Also, if you use the special value 0 to any attribute, the saved option will be deactivated.

Usually you only need to use the text attribute to set the text of the tweet, and allow the shortcode to do the rest.


== Some examples ==

* [revec2t] : The simplest example. As you don't set the text, the current page URL will be shown in its place.

* [revec2t text="Text of the tweet"] : The recommended use. Displays the box with the text, the current URL and the saved options.

* [revec2t text="Text of the tweet" url="http://..."] : To use a custom URL. Note that URLs must be valid.

* [revec2t text="Text of the tweet" label="Click here"] : Changes the default call to action label.

* [revec2t text="Text of the tweet" hashtags="hashtag1,hashtag2"] : Sets the hashtags of the tweet, or changes the default hashtags parameter, if set.

* [revec2t text="Text of the tweet" via="twitter_user"] : Sets or changes the default via parameter.

* [revec2t text="Text of the tweet" via="0" hashtags="0"] : Disables the default via and hashtags parameters, if set in options.


== About the Twitter API ==

Note that the Twitter API will receive and validate all the submitted params. So, is a best practice to test each shortcode when it is published, especially if the text or the URL are too long.

Currently the maximum length of a tweet is 280 characters, including the text, the URL, the via parameter, the hashtags and the blanks.


== Screenshots ==

1. The plugin options page, in Settings > Reve Click2Tweet (screenshot-1.png).


== Installation ==

1. Download this plugin to the "/wp-content/plugins/" directory of your site.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Go to "Settings > Reve Click2Tweet" admin page to set the default options.


== Need help? ==

* For help use the [WordPress Support](https://wordpress.org/support/plugin/reve-click2tweet/).
* Also you can [write a review](https://wordpress.org/support/plugin/reve-click2tweet/reviews/#new-post).


== Contribute development ==

You can contribute as follow:

* [If you like this plugin, give us a five stars rating clicking here.](https://wordpress.org/support/plugin/reve-click2tweet/reviews/)

* [If you make this plugin profitable, give us any Paypal donation clicking here.](http://www.promostudio.es/support-revec2t)


== Changelog ==

= 1.3.0 =
* 2021-02-02: Tested in WordPress 5.6.

= 1.2.0 =
* 2018-04-01: Fixed some issues.

= 1.1.0 =
* 2018-03-20: Fixed some issues.

= 1.0.0 =
* 2018-03-20: Initial stable version.