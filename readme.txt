=== Osom Author Pro ===
Contributors: nick_thegeek, dreamwhisper, laurenmancke, studiopress, marksabbath, jivedig, osompress, esther_sola, nahuai
Tags: genesis, authors, books
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Osom Author Pro plugin creates a library which allows you to add books to any WordPress theme. 

== Description ==

The Osom Author Pro plugin creates a library which allows you to add books to any WordPress theme. 

In WordPress themes using the Block Editor, you'll be able to display the book details (Price, ISBN, author...) using native blocks and a dedicated block pattern. Additionally, in Genesis child themes it also provides layouts for a single and archive views.

= WordPress Themes with Block Editor =

The Osom Author Pro adds a new category called "Author Pro" containing a native block for each custom book information:

* Featured Text
* Price
* ISBN
* Publisher
* Editor
* Edition
* Publish Date
* Available Editions
* Three custom buttons

More over, the plugin adds a dedicated block pattern including all these new native blocks. 

In addition to the custom book data, there are three taxonomies created: "Author," "Series," and "Tags." These allow you and your site visitors to sort and organize the books in your library.

The Author is handled via the custom taxonomy instead of following the post author. This allows you to have multiple book authors and put books in the library without creating new members on your site. Multiple authors should be output in the byline without the Oxford comma like:

* John Doe
* Jane Doe and John Doe
* Jane Doe, John Doe and John Smith

The publish date is a text-aware date field that attempts to understand standard date formats such as:

* January 1, 1999
* 1 Jan 1999
* 1 1 1999
* 1999 1 1

There are instances where the returned date may not correctly match. `4 10 1999` might be interpreted as `April 10, 1999` instead of the intended `4 October 1999`. Typing out the month or month abbreviation instead of numeric representation of the month should resolve any discrepancies.

The date will be stored in a computer readable format and then output following the date format set in the WordPress options.


= In Genesis Themes =

The Osom Author Pro Featured Book widget will allow you to select a book from the library and feature it in any widgeted area of your website.

Optional output from the widget includes:

* Widget Title
* Book Title
* By Line
* Book Image
* Featured Text
* Content options including: Full content, limited content, and the excerpt for a custom synopsis.
* Price
* Custom link to the single book page

For Genesis child themes it also offer templates that are built into the plugin with default CSS to create the basic layout for all child themes. Templates follow standard WP template hierarchy so if the template is in the child theme, that will override the template in the plugin. Templates include:

* single-books.php
* archive-books.php
* taxonomy-book-authors.php
* taxonomy-book-series.php
* taxonomy-book-tags.php

== Installation ==

1. Upload `genesis-author-pro` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= I don't see the dedicated button blocks under the "Author Pro" category, where are they?  =

The dedicated Author Pro buttons 1, 2 and 3 will appear in your block panel to be selected when you are inside a native button block. If you are not inside a button block you will not see them. 

To help you find all Author Pro Blocks, we created an Author Pro pattern that includes all Author Pro blocks. 

== Screenshots == 
 
1. Blocks to display Book fields under Author Pro category.
2. Block pattern available from the editor inserter.
3. Book block pattern.
4. Book block pattern with information filled and small design modifications.
5. Book fields on the editor.

== Changelog ==

= 2.0 =
* Add the ability to display the book details (Price, ISBN, author...) using native blocks.
* You will find, on the block editor, a new category called "Author Pro" containing all the new available blocks. 

= 1.2.2 =
* Improvement managing the additional image size (author-pro-image).

= 1.2 =
* You can use now the plugin with other themes, not only Genesis child themes.

= 1.1 =
* Added compatibility with the block editor.

= 1.0.3 =
* Changed ownership from StudioPress to OsomPress. You can read more details about it in https://osompress.com/4-new-plugins-join-osompress-family/.

= 1.0.2 =
* Cease use of a deprecated Genesis function. Use standard WordPress function instead.

= 1.0.1 =
* Removes Layouts Settings from Authors, Series and Tags.
* Removes Simple Sidebar controls from Authors, Series and Tags.

= 1.0 =
* Adds filters for the various page slugs so they can be altered.
* Adds support for Genesis Archive Settings.
* Adds support for Genesis Simple Menus.
* Fixes i18n issue.
* Fixes post navigation appearing on archives.
* Fixes pagination issue.
* Various copy changes.

= 0.9 =
* Initial Public Release

== Upgrade Notice ==

= 1.0 =
* Text strings and other changes were made for i18n. Please update your translation files.

= 0.9 =
* Initial Public Release