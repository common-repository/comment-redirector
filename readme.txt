=== Comment Redirector ===
Contributors: daveburke
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QNV6JFQWGSSSC
Tags: comments, hosted comments, Google+ hosted comments, Google+, Google Plus
Requires at least: 3.5.1
Tested up to: 3.6
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Redirect Comment Display and Management to Google+ or other online service.

== Description ==

This plugin redirects users to a Google+ post where all comments for that post will
 be displayed and managed. The plugin displays a "Comments for this post are located at..." message at the
 bottom of each post with a Comment Redirection Url property, entered on the post's Quick Edit form.

Services like Facebook and Google+ embedded comment plugins that display comments
on your blog are awesome. This plugin is for those who want to move comment display off their
blog completely, yet still make it easy for your visitors to comment if they wish.

This plugin also helps centralize a post's comments and tie them to a single permalink. Google+
embedded comments will display on your blog and below the post where comments normally appear. However,
when a visitor posts a comment it creates a new Google+ post and thus disperses the post's comments across multiple
posts on Google+. The Comment Redirector gives you the ability to control the conversation and direct it to a single url.

For more information visit the <a href="http://nixmash.com/projects/wordpress-comment-redirector-plugin/">plugin's page</a>

== Installation ==

1. Upload unzipped comment-redirector folder to /wp-content/plugins/
2. Navigate to Plugins menu from the admin panel and activate Comment Redirector.
3. Go to Settings -> Comment Redirector to make any changes to the Comment Redirection Message Template.
4. For any posts you wish to redirect to a Google+ Post or other url, go to Posts -> All Posts and enter
the Google+ post permalink in the "Comment Redirection Url" field on the Quick Edit Form. The Comment
Redirection Message will display for posts where a Google+ post permalink has been entered.

== Frequently Asked Questions ==

= Tell me more about this Comment Redirection Message =

The Comment Redirection Message displays at the bottom of each post, linking visitors to a Google+ post
you entered where you want to house that post's comments.

= How do I customize the message template for my theme? =

You can use the existing CSS classes on the Comment Redirector Message Template or create a new template
from scratch. You can customize classes in your theme's CSS stylesheet or use /css/public.css in the
comment-redirector plugin folder.

= I have to go to Google+ and first create a link back to the blog post? =

That's right. Then copy the Google+ post permalink and paste it in the "Comment Redirection Url" field on
the post's Quick Edit form.  At that point the Comment Redirection Message will display at the bottom of
the post.

= The comment redirection message is displaying along with my blog comments! =

The Comment Redirector plugin does not hide or disable any existing comments on your blog. It is assumed that
Comment Redirector is your blog's sole comment service and that blog comments are otherwise disabled.

= I performed the installation steps but no Comment Redirection Message displays on my posts  =

The comment redirection message only appears if the post has a Comment Redirection Url property.

= What properties can I, um, plugin to the Comment Redirection Message? =

The Comment Redirection Message supports the following string substitutions:

1. %comment_redirection_url%. This is the url of the Google+ post (or other service) where the post's
comments are displayed and managed
2. %plugin_url%. The location of the comment-redirector plugin used in urls for displaying images located
in a plugin subdirectory
3. %post_title%. The title of the blog post.

= I like the idea of Comment Redirection. What if I want to use Facebook or something else? =

You can use any service you want. You would want to change the Comment Redirection template on
setup. Apart from that all you need is a Comment Redirection Url for each post to link to the permalink
on your service.

== Screenshots ==

1. The Google+ post we create to announce the new blog post
2. Entering the Google+ post url on the blog post's Quick Edit form
3. The post's Comment Redirection message linking to the Google+ post
4. Comment Redirector Message Template and Settings page

== Changelog ==

= 1.0 =
* V1.0 complete

= 1.1 =
Fixed bug where comment redirection message would display without valid Comment Redirection Url

= 1.2 =
Plugin version display fix

== Upgrade Notice ==

= 1.0 =
Initial Release

= 1.1 =
Fixed bug where comment redirection message would display without valid Comment Redirection Url

= 1.2 =
Plugin version display fix

