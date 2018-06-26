=== WooCommerce AJAX Products Filter ===
Plugin Name: WooCommerce AJAX Products Filter
Contributors: dholovnia, berocket
Donate link: http://berocket.com/product/woocommerce-ajax-products-filter
Tags: filters, product filters, ajax product filters, advanced product filters, woocommerce filters, woocommerce product filters, woocommerce ajax product filters
Requires at least: 4.0
Tested up to: 4.9.5
Stable tag: 2.0.9.2
License: Berocket License
License URI: http://berocket.com/license

WooCommerce AJAX Products Filter - advanced AJAX product filters plugin for WooCommerce.

== Description ==

WooCommerce AJAX Products Filter - advanced AJAX product filters plugin for WooCommerce. Add unlimited filters with one widget.

= Features: =

* Filter by Attribute, Tag and Custom Taxonomy
* AJAX Filters, Pagination and Sorting!
* Customize filters look through admin
* Unlimited Filters
* Tag Cloud for Tag filter
* SEO Friendly Urls ( with HTML5 PushState )
* Filters can be collapsed, option to collapse filter on start
* Price Filter Custom Min and Max values
* Add custom CSS on admin settings page
* Multiple User Interface Elements
* Show icons before/after widget title and/or before/after values
* Filter Visibility By Product Category And Globals.
* Accessible through shortcode
* Filter box height limit with scroll themes
* Working great with custom widget area
* Drag and Drop Filter Building
* Optimization to handle up to 10,000 products total. Next step is 100,000 products
* Selected Filters area. When you select any filter value it will be shown in the widget
* Added option to set values for the price slider manually(eg 1,2,6,50,200,1000)
* Added option to hide widget on selected pages
* Option to set values order right in widget
* Added Checkbox, Radio, Slider style customization
* Added cache for filters with cache plugins or with WordPress fucntionality
* Added nice URLs with customization
* Added shortcode builder for more easier short code creation
* Added Parent/Child widget with ajax load on parent changes
* Added update plugin from BeRocket site using account/product key
* And More...

= Demo =
http://woocommerce-product-filter.berocket.com

= How It Works: =
*check installation*


= New in recent version(s): =
* Show all values - on plugin settings page you can enable option to show all values no matter if they are used or not
* Values order - you can set values order when editing attribute. You can set how to order (by id, name or custom). If
you set to order `by custom` you can drag&amp;drop values up and down and set your own order.
* Fixes


= Shortcode: =
* In editor `[br_filters attribute=price type=slider title="Price Filter"]`
* In PHP `do_shortcode('[br_filters attribute=price type=slider title="Price Filter"]');`

= Shortcode Options: =
* `attribute`(required) - product attribute, eg price or length. Don't forget that woocommerce adding pa_ suffix for created attributes.
 So if you create new attribute `jump` its name is `pa_jump`
* `type`(required) - checkbox, radio, slider or select
* `operator` - OR or AND
* `title` - whatever you want to see as title. Can be empty
* `product_cat` - parent category id
* `cat_propagation` - should we propagate this filter to child categories? set 1 to turn this on
* `height` - max filter box height. When height is met scroll will be added
* `scroll_theme` - pretty clear name, scroll theme. Will be used if height is set and real height of box is more


= Advanced Settings (Widget area): =

* Product Category - if you want to pin your filter to category of the product this is good place to do it.
 Eg. You selling Phones and Cases for them. If user choose Category "Phones" filter "Have Wi-Fi" will appear
 but if user will choose "Cases" it will not be there as Admin set that "Have Wi-Fi" filter will be visible only on
 "Phones" category.
* Filter Box Height - if your filter have too much options it is nice to limit height of the filter to not prolong
 the page too much. Scroll will appear.
* Scroll theme - if "Filter Box Height" is set and box length is more than "Filter Box Height" scroll appear and
 how it looks depends on the theme you choose.


= Advanced Settings (Plugin Settings): =
* Plugin settings can be found in admin area, WooCommerce -> Product Filters
* "No Products" message - Text that will be shown if no products found
* "No Products" class - Add class and use it to style "No Products" box
* Products selector - Selector for tag that is holding products
* Sorting control - Take control over WooCommerce's sorting selectbox
* SEO friendly urls - url will be changed when filter is selected/changed
* Turn all filters off - If you want to hide filters without losing current configuration just turn them off



== Installation ==

= Step 1: =
* First you need to add attributes to the products ( WooCommerce plugin should be installed and activated already )
* Go to Admin area -> Products -> Attributes and add attributes your products will have, add them all
* Click attribute's name where type is select and add values to it. Predefine product options
* Go to your products and add attributes to each of them

= Step 2: =
* Install and activate plugin
* Go to Admin area -> Appearance -> Widgets
* In Available Widgets ( left side of the screen ) find AJAX Product Filters
* Drag it to Sidebar you choose for it
* Enter title, choose attribute that will be used for filtering products, choose filter type,
 choose operator( whether product should have all selected values (AND) or one of them (OR) ),
* Click save and go to your shop to check how it work.
* That's it =)


== Frequently Asked Questions ==

---

== Screenshots ==

---

== Changelog ==

= 2.0.9.2 =
* Enhancement - Hide variable products without needed variation
* Enhancement - Hide groups and filters for mobile/tablet/desktop devices
* Fix - Sorting doesn't work correct with default sort by Date

= 2.0.9.1 =
* Enhancement - Compatibility with next version of WooCommerce Product Table 
* Enhancement - Position of Elements above products to fix incompatibility with some theme
* Fix - Additional styles to fix incompatibility with some theme
* Fix - Update/Reset button display inline

= 2.0.9 =
* Enhancement - Custom CSS class for filters and groups
* Enhancement - Option to show group above products
* Enhancement - Hide sidebar if filters hidden and sidebar doesn't have other widgets
* Enhancement - Examples for custom CSS
* Enhancement - Use links in checkbox/radio/color/image. Option to improve SEO
* Enhancement - Display filters in group inline
* Enhancement - Display filters hidden. Only title will be displayed
* Enhancement - Option to change size of color/image element
* Enhancement - Option to change selected style of color/image element
* Enhancement - Option to use custom CSS for selected color/image element
* Enhancement - Use filtered variation link for variable products
* Enhancement - Always display or hide Show/Hide button when attribute count setted
* Enhancement - Added product Category type of filter
* Fix - Slider with a lot options
* Fix - Selected filters with Select2 script
* Fix - Nice URL for different permalink settings
* Fix - Position for description symbol
* Fix - Empty categories always visible, when must be hidden
* Fix - Date filter doesn't work with sorting by popularity
* Fix - Show/hide arrow style and position
* Fix - Pagination with nice URL
* Fix - Show/Hide button correct after filtering

= 2.0.8.1 =
* Enhancement - Multiple color for Color type of widget
* Fix - Slider recount work incorrect with some settings
* Fix - Option to fix sliders with a lot of values
* Fix - Categories product count in subcategories widget
* Fix - Option to disable eecode + symbol in URL
* Fix - Move some option to Advanced tab

= 2.0.8 =
* Enhancement - WPBakery Page Builder(Visual Composer) Module
* Enhancement - Divi Builder module
* Enhancement - Option to use GET query instead POST
* Enhancement - Select2 script for Selectbox in filters
* Enhancement - Multiple Selectbox option
* Enhancement - Option to order attribute values ascending or descending
* Enhancement - Added title for group widgets(only for admin panel)
* Fix - Select type of widget with Child/Parent option
* Fix - Compatibility with Divi shop module
* Fix - Compatibility with other filters
* Fix - Count of element per row
* Fix - Option "Show products count before filtering"
* Fix - Filter by Date
* Fix - Show/Hide widget after filtering(filters hides same as before filtering)
* Fix - Use default WooCommerce variable for Orderby
* Fix - Tags cloud element position and auto height
* Fix - Condition attribute not use values without products

= 2.0.7.14 =
* Fix - Fix Problems with child/parent widgets
* Fix - Fix products load on pages without products
* Fix - WPML attribute slug translate
* Fix - Tags cloud element position
* Fix - Error in group widget

= 2.0.7.13 =
* Fix - Fix errors on old version of PHP
* Fix - "Display products" option compatibility with WooCommerce 3.3

= 2.0.7.12 =
* Fix - Better compatibility with theme The7
* Fix - Font Awesome 5 icons for rating
* Fix - Errors on WooCommerce older then 3.3
* Fix - Option to disable collapse action for widgets

= 2.0.7.11 =
* Fix - Fatal errors in Divi builder and Visual composer
* Fix - Sorting control doesn't work on latest WooCommerce
* Fix - Sale filter doesn't work on latest WooCommerce
* Fix - Fatal error on some page that used WooCommerce query
* Fix - Permalinks without trailing slash doesn't work correct

= 2.0.7.10 =
* Fix - Categories product count in widgets
* Fix - Table for plugin custom post type on mobile device
* Fix - Description position on widgets
* Fix - Compatibility with WooCommerce widgets
* Fix - Compatibility with new features in Load More plugin
* Fix - Updater fix

= 2.0.7.9 =
* Fix - Scroll on widgets doesn't work after filtering
* Fix - Show products count before filtering work incorrect with scroll in widget
* Fix - Added empty elements to widget to fix errors with saving

= 2.0.7.8 =
* Fix - Error on page without pagination and disabled SEO option

= 2.0.7.7 =
* Fix - Limitation will be added to new filters, when you creating it from deprecated filters
* Fix - Ordering doesn't work for categories
* Fix - WooCommerce shortcode pagination doesn't work correct

= 2.0.7.6 =
* Fix - Fix for pages with WooCommerce shortcodes
* Fix - Page with disabled "Template ajax load fix" option
* Fix - Rating filter doesn't work correct
* Fix - Tag cloud
* Fix - Canonical option for WooCommerce pages

= 2.0.7.5 =
* Enhancement - Categories condition uses checkboxes instead dropdown menu
* Fix - Hide empty widgets fix
* Fix - WPML fix
* Fix - Description position fix
* Fix - Selected filter area name displayed twice

= 2.0.7.4 =
* Enhancement - Custom first element in dropdown menu
* Enhancement - Conditions for single filters
* Enhancement - Search box option for groups
* Fix - Hide widgets without attributes

= 2.0.7.3 =
* Fix - SSL Fonts load fix
* Fix - Check WC_Query method before use it
* Fix - Wizard setup
* Deprecated - Remove old shortcode builder

= 2.0.7.2 =
* Fix - Fatal error on multisite

= 2.0.7.1 =
* Enhancement - Filter by product rating
* Enhancement - Display only part of attribute values on page load
* Enhancement - Decimal settings for each slider filter
* Enhancement - New filters as custom post type
* Enhancement - Group filters post type for filter limitation
* Enhancement - New shortcodes

= 2.0.7 =
* Enhancement - Function to get selectors for your theme automatically
* Enhancement - Setup Wizard with more explanation for easy plugin setup
* Fix - Was added fix for The 7 theme, but you need additional JS for your setup

= 2.0.6.66 =
* Fix - Fatal error in attribute widgets

= 2.0.6.65 =
* Fix - Price filter generates errors with WooCommerce older than 3.0
* Fix - Fatal error when plugin uses with old version of any other BeRocket plugins

= 2.0.6.64 =
* Fix - Reset button doesn't work correct
* Fix - Selected filters area work incorrect with Update button
* Fix - Selected filters area generate incorrect links
* Fix - Remove some PHP notices and errors
* Fix - Nice URL option doesn't work with some WooCommerce language files

= 2.0.6.63 =
* Fix - Slider filters doesn't work after filtering
* Fix - Slider filters doesn't work correct with reset button
* Fix - Shortcode with option "Reload amount of products" incorrect after filtering

= 2.0.6.62 =
* Fix - Sliders with price range doesn't work correct with option "Reload amount of products"
* Fix - Checkbox style disapears when uses scroll in filters
* Fix - Scroll in filters disapears after filtering with option "Reload amount of products"
* Fix - Plugin doesn't use correct query on default WooCommerce pages, when WooCommerce shortcodes uses on same pages
* Fix - Slider doesn't work correct when slug uses not latin alphabet symbols

= 2.0.6.61 =
* Fix - Warning and notice with disabled option "Hide out of stock items from the catalog"
* Fix - Price filter generate incorrect query with WPML
* Fix - Plugin uses DEBUG MODE from settings instead BeRocket Account settings

= 2.0.6.60 =
* Enhancement - Slider is reloading its limits same as other filters
* Enhancement - Price limits counted within filtered products
* Fix/Enhancement - Products search is updated to the recent version of WooCoomerce
* Fix/Enhancement - Updated speed of replacing reloaded filter widgets
* Fix/Enhancement - Parent category recount upgraded. Not all subcategories was counted
* Fix - Deep level was broken in previous version. Fixed now
* Fix - Alpha sorting is fixed for categories
* Fix - Better sliders compatibility with other filters
* Fix - Slider hidden when no values available(was using possible min+max values before)
* Fix - Added all options from widget to shortcode builder
* Fix - Selected filter area hides with "Show if nothing is selected"
* Fix - Caching for categories page doesn't work correct

= 2.0.6.59 =
* Fix/Enhancement - Fixing Hidden, private and product recount
* Fix/Enhancement - Don't count subcategories products in the parent category
* Fix/Enhancement - Fixing issue with subcategories that have same name start as parent
* Fix/Enhancement - Fix Update popup issue when height is used
* Fix/Enhancement - Better location for Update popup
* Fix/Enhancement - Correct search for the products, must search same as woocommerce
* Fix/Enhancement - For common attributes search wasn't applied, no verification for
* Fix/Enhancement - Sale for product_count

= 2.0.6.58 =
* Fix/Enhancement - Small fixes

= 2.0.6.56 =
* Fix/Enhancement - Permalink settings error fix
* Fix/Enhancement - Notices style fix

= 2.0.6.55 =
* Fix/Enhancement - Fix compatibility with other plugins

= 2.0.6.54 =
* Fix/Enhancement - New version of Font Awesome
* Fix/Enhancement - Fixed cache option for categories page
* Fix/Enhancement - Operator AND in filters doesn't work on some sites
* Fix/Enhancement - CSS was not valid, because of error
* Fix/Enhancement - Remove potential notices, that can write to error log

= 2.0.6.53 =
* Fix/Enhancement - Remove a lot of PHP notices for widgets with different settings
* Fix/Enhancement - Updater fix for multisite

= 2.0.6.52 =
* Enhancement - Deep level for widget with "Use current product category to get child" option enabled
* Enhancement - Option to enable inputs in price slider. Inputs will be editable
* Fix/Enhancement - Cache option doesn't work correct on search page
* Fix/Enhancement - Changed "Dispay" to "Display"
* Fix/Enhancement - Removed notices

= 2.0.6.51 =
* Fix/Enhancement - Updater fixes

= 2.0.6.50 =
* Fix/Enhancement - Remove some debug information

= 2.0.6.49 =
* Fix/Enhancement - Slider doesn't displayed on some theme.
* Fix/Enhancement - Remove some PHP notices

= 2.0.6.48 =
* Fix/Enhancement - Nice URL for custom taxonomy
* Fix/Enhancement - Product sub-categories widget duplicates child categories for next categories without child
* Fix/Enhancement - Parent-child widgets doesn't work with product categories
* Fix/Enhancement - Parent-child widgets for select type widget doesn't work correct

= 2.0.6.47 =
* Fix/Enhancement - Compatibility with WooCommerce 3.0.0
* Fix/Enhancement - Filters doesn't work correct with WooCommerce shortcodes
* Fix/Enhancement - PHP notices was removed

= 2.0.6.46 =
* Fix/Enhancement - Compatibility with WooCommerce 3.0.0
* Fix/Enhancement - WPML Compatibility
* Fix/Enhancement - Compatibility with other plugins
* Fix/Enhancement - Russian language update
* Fix/Enhancement - Tag Cloud fix

= 2.0.6.45 =
* Fix/Enhancement - Errors with WooCommerce shortcodes. Incorrect filtering for WooCommerce shortcodes

= 2.0.6.44 =
* Fix/Enhancement - Product categories with WPML
* Fix/Enhancement - Select widgets on mobile devices

= 2.0.6.43 =
* Fix/Enhancement - Widget title compatibility with WPML
* Fix/Enhancement - Updater fix

= 2.0.6.42 =
* Fix/Enhancement - Title in Selected filters widget
* Fix/Enhancement - Display only on products pages, that inside selected categories
* Fix/Enhancement - Filters without "Template ajax load fix" doesn't work on search page
* Fix/Enhancement - Some fixes for "On sale" widget
* Fix/Enhancement - Count of products on color widgets sometimes displayed incorrect
* Fix/Enhancement - Price filter doesn't work correct with Currency Exchange plugin

= 2.0.6.41 =
* Fix/Enhancement - Fix some error in PHP

= 2.0.6.40 =
* Fix/Enhancement - Sorting in sliders with text values
* Fix/Enhancement - Slider on mobile devices
* Fix/Enhancement - Option to fix search pages
* Fix/Enhancement - "Display products" option doesn't work correct on some sites
* Fix/Enhancement - PHP 7 widget fix
* Fix/Enhancement - Attribute values recount for widget with color type
* Fix/Enhancement - Hide widgets with fixed height
* Fix/Enhancement - Remove PHP notices in styles
* Fix/Enhancement - Display styles only for pages with filters
* Fix/Enhancement - Template fixes

= 2.0.6.39 =
* Fix/Enhancement - PHP warning for product category widget
* Fix/Enhancement - Selected filters shortcode work incorrect

= 2.0.6.38 =
* Fix/Enhancement - WordPress widget Live Preview doesn't work
* Fix/Enhancement - Plugin with WPML, but withount WooCommerce multil
* Fix/Enhancement - Hash doesn't work correct with some other plugins

= 2.0.6.37 =
* Fix/Enhancement - Products recount doesn't work with checkbox type of widget
* Fix/Enhancement - Some site doesn't load correct page with JavaScript(jQuery) "Template ajax load fix"

= 2.0.6.36 =
* Fix/Enhancement - "berocket_aapf_tax_query_attribute" hook for tax query attributes.
* Fix/Enhancement - Sale widgets always uses checkbox type of widget
* Fix/Enhancement - Sale and Stock status widgets uses same id for checkboxes.
* Fix/Enhancement - Some sliders doesn't work correct
* Fix/Enhancement - "Old slider compatibility" option doesn't work correct

= 2.0.6.35 =
* Fix/Enhancement - Slider optimization
* Fix/Enhancement - Variable to enter currency symbol before or after values
* Fix/Enhancement - Fatal error with some type of custom taxonomy
* Fix/Enhancement - Fatal error with some type of custom taxonomy sorting
* Fix/Enhancement - Remove Show/Hide value(s) button from widget
* Fix/Enhancement - Hide widgets without values
* Fix/Enhancement - Incorrect values in selected filters area
* Fix/Enhancement - Options to remove Font Awesome styles from page

= 2.0.6.34 =
* Fix/Enhancement - Fixes errors in custom JavaScript codes
* Fix/Enhancement - More texts can be translated with WPML
* Fix/Enhancement - Price filter uses 0.00 price
* Fix/Enhancement - Child values displayed with "Hide all child values?" option, when parent values doesn't displayed

= 2.0.6.33 =
* Enhancement - "Hide values without products" option work without "Show product count per attributes?" option in widgets
* Enhancement - Option "Hide all child values?" in widgets settings to hide child values in widgets and add "+" button rightside from parent values
* Fix/Enhancement - Some errors with Media Library
* Fix/Enhancement - Updated translation files
* Fix/Enhancement - Values without products hides in select type widgets
* Fix/Enhancement - Stock status filter doesn't work with some settings

= 2.0.6.32 =
* Fix/Enhancement - URL generation fixes
* Fix/Enhancement - Categories slug fixes for widget limitations

= 2.0.6.31 =
* Enhancement - Select attribute values to remove from list or select attribute values to display in widget
* Enhancement - Widget to filter products by modified date
* Enhancement - Widget to filter products by sale status
* Fix/Enhancement - Important fixes for WooCommerce shortcodes
* Fix/Enhancement - Fixes for slugs in different languages

= 2.0.6.30 =
* Fix/Enhancement - Compatibility of new features with older WooCommerce
* Fix/Enhancement - Product count on product categories sometimes doesn't display
* Fix/Enhancement - Code optimization

= 2.0.6.29 =
* Enhancement - Compatibility with some custom paginations
* Enhancement - Recount products for price ranges
* Enhancement - Hide first and latest ranges without products
* Fix/Enhancement - Price filter doesn't work correct with decimal values
* Fix/Enhancement - On sites with WPML sometimes used incorrect URL for filters
* Fix/Enhancement - Shortcode has some incorrect attributes
* Fix/Enhancement - Slider work incorrect with another same slider
* Fix/Enhancement - Some features aren't work on page with WooCommerce shortcode

= 2.0.6.28 =
* Fix/Enhancement - Price filter uses incorrect values from database
* Fix/Enhancement - Reload amount of products sometimes was slow(uses old functions to get product count)
* Fix/Enhancement - Sometimes widgets can be hidden

= 2.0.6.27 =
* Fix/Enhancement - FATAL ERROR fix

= 2.0.6.26 =
* Fix/Enhancement - Correct product count for Stock status filter (WooCommerce 2.6.2 and newer)
* Fix/Enhancement - Compatibility with attribute or custom taxonomy pages
* Fix/Enhancement - Better compatibility with WPML (WooCommerce 2.6.2 and newer)
* Fix/Enhancement - Filter names sometimes duplicates on selected filters area

= 2.0.6.25 =
* Fix/Enhancement - Values without products are displayed with disabled option "Show all values"
* Fix/Enhancement - Count of products on attribute values is not correct on search page
* Fix/Enhancement - Minimum and maximum value for price filter can be set to any values, also less than maximum products price and more than minimum products price

= 2.0.6.24 =
* Fix/Enhancement - Filters can be displayed on product page( only Search box widget work on single product page)
* Fix/Enhancement - Speed up for "Show all values" and "Reload amount of products" option

= 2.0.6.23 =
* Fix/Enhancement - Translation for all text
* Fix/Enhancement - Pagination doesn't work correct with history API on some sites

= 2.0.6.22 =
* Enhancement - Always display only products on shop pages with filters
* Enhancement - Scripts and styles will be displayed only on pages with filters
* Fix/Enhancement - Slider type of widget doesn't work correct for decimal values

= 2.0.6.21 =
* Fix/Enhancement - Added new fields to the shortcode builder
* Fix/Enhancement - Pagination doesn't work after click on next page link multiple times
* Fix/Enhancement - Title doesn't display in search box widgets
* Fix/Enhancement - Sliders with decimal values sometimes uses incorrect slider style and ordering
* Fix/Enhancement - Compatibility with another plugin
* Fix/Enhancement - Other fixes

= 2.0.6.20 =
* Fix/Enhancement - Stylization for numeric value in sliders didn't worked with decimal values
* Fix/Enhancement - Optimization for sites with disabled option "Show all values" ( now uses cache )
* Fix/Enhancement - Optimization for price filters ( faster page loads with price filter )
* Fix/Enhancement - Ordering drop down didn't worked with nice URL

= 2.0.6.19 =
* Enhancement - Limitation for specific users(Logged In or Not Logged In)
* Fix/Enhancement - Option to fix widgets on sites with AJAX page load
* Fix/Enhancement - Fix for order by drop down menu with "Sorting control" disabled

= 2.0.6.18 =
* Fix/Enhancement - Values from same attributes hides
* Fix/Enhancement - Fix for accessibility mode
* Fix/Enhancement - Some sliders work incorrect

= 2.0.6.17 =
* Fix/Enhancement - Product selector with spaces doesn't work
* Fix/Enhancement - Filters doesn't work on some sites with WooCommerce shortcodes
* Fix/Enhancement - Errors were removed  from Product sub-categories widget

= 2.0.6.16 =
* Fix/Enhancement - Widgets with operator AND doesn't work correct
* Fix/Enhancement - Price filter doesn't work on some sites
* Fix/Enhancement - Filters doesn't return products on sites with products and categories in query
* Fix/Enhancement - Next and previous buttons on pagination works correct

= 2.0.6.15 =
* Enhancement - Reset button widget type to remove all selected filters
* Fix/Enhancement - Price filter doesn't work with WooCommerce shortcode
* Fix/Enhancement - Sometimes filters are applied to the wrong query

= 2.0.6.14 =
* Enhancement - Search box widget, that can be placed anywhere
* Enhancement - Easiest way to create child - parent filters
* Fix/Enhancement - Shortcode not displayed
* Fix/Enhancement - Slider fix for attributes

= 2.0.6.13 =
* Fix/Enhancement - Fix for color/image type of widgets
* Fix/Enhancement - Fix for slider type of widgets
* Fix/Enhancement - Better compatibility with other plugins

= 2.0.6.12 =
* Enhancement - More easiest parent/child setup for attributes and custom taxonomy
* Enhancement - Product sub-category option to show child categories for current category

= 2.0.6.11 =
* Fix/Enhancement - removes errors in javascript admin panel
* Fix/Enhancement - shortcode shows in input
* Fix/Enhancement - removes some errors in updater

= 2.0.6.10 =
* Fix/Enhancement - Fix for price filter
* Fix/Enhancement - Scrollbar fix in widgets

= 2.0.6.9 =
* Enhancement - Russian Language
* Fix/Enhancement - Added additional classes to attributes list in widgets
* Fix/Enhancement - Added some hooks to send informations to page via AJAX

= 2.0.6.8 =
* Enhancement - Russian Language
* Fix/Enhancement - Custom taxonomies did not working
* Fix/Enhancement - Translation fix

= 2.0.6.7 =
* Fix/Enhancement - Filter product tags as attribute or as custom taxonomy( can fix some errors with product tags )
* Fix/Enhancement - Fix translation domain
* Fix/Enhancement - Uses term_id instead slug( slug didn't work on some sites )
* Fix/Enhancement - Fix for HTML5 pushstate. Back button works correct
* Fix/Enhancement - Remove errors from pages with WooCommerce shortcodes, but without filters
* Fix/Enhancement - Fix errors for widgets in list block

= 2.0.6.6 =
* Fix/Enhancement - Displayed only the first widget or none widget is displayed
* Fix/Enhancement - Added default HTML code from theme before widget and after widget

= 2.0.6.5 =
* Fix/Enhancement - Remove selections for slider in selected area didn't work
* Fix/Enhancement - Price slider use correct minimum and maximum values on tag pages
* Fix/Enhancement - Widgets now support attribute pages
* Fix/Enhancement - BeRocket account settings in WordPress network works correct

= 2.0.6.4 =
* Fix/Enhancement - BeRocket Account save key in WordPress network admin area
* Fix/Enhancement - Product count for attribute values on tag pages

= 2.0.6.3 =
* Fix/Enhancement - fatal error fix

= 2.0.6.2 =
* Enhancement - use categories sortings from settings when enable default sorting
* Fix/Enhancement - fix radio buttons in Safari browser
* Fix/Enhancement - save image and color for values in shortcode builder
* Fix/Enhancement - add default widget block. Must fix issues with plugins, that change widgets.

= 2.0.6.1 =
* Enhancement - price widget now can be set as checkbox with min and max values
* Fix/Enhancement - attributes values breaks after checkbox, if they have been too long
* Fix/Enhancement - removes some PHP errors and warnings
* Fix/Enhancement - now plugin use only last AJAX request

= 2.0.6 =
* Fix/Enhancement - Slider type of widget have been with JavaScript errors

= 2.0.5.9 =
* Fix/Enhancement - Numeral slider works incorrect with custom thousand and decimal separators
* Fix/Enhancement - Custom height in widget use height instead maximum height
* Fix/Enhancement - Image type of widget use too much inputs for Font Awesome icons
* Fix/Enhancement - Reload amount of products didn't work with WooCommerce shortcodes

= 2.0.5.8 =
* Fix/Enhancement - Price filter sort values like string
* Fix/Enhancement - Pushstate reload page
* Fix/Enhancement - Widgets didn't display on product tag pages

= 2.0.5.7 =
* Fix/Enhancement - Price filter used incorrect values
* Fix/Enhancement - SEO URLs didn't work without trailing slash
* Fix/Enhancement - Pagination with different symbols didn't work
* Fix/Enhancement - Back button didn't work correct with SEO URLs
* Fix/Enhancement - Slider with decimal values works incorrect

= 2.0.5.6 =
* Fix/Enhancement - Fix Incorrect amount of products with attribute values after filtering
* Fix/Enhancement - Fix Do not update the number of products on widget with type color

= 2.0.5.5 =
* Enhancement - Show amount of products before update with Update button
* Fix/Enhancement - Fix Incompatibility with Revolution Slider and other plugins with jQuery UI

= 2.0.5.4 =
* Enhancement - Compatibility with WooCommerce Load More Products plugin
* Fix/Enhancement - Fixes for Color and Image type of widget

= 2.0.5.3 =
* Fix/Enhancement - Fixes can't upload product image
* Fix/Enhancement - Custom Taxonomy and Product Category have AND operator

= 2.0.5.2 =
* Enhancement - When attribute values hidden uses Show value(s) and Hide value(s) instead Show value
* Enhancement - NEW widget type image, use any images or Font Awesome icons instead attribute values
* Enhancement - Checkbox and radio theme selector
* Enhancement - Added WooCommerce shortcode support (but with shortcodes some options will work incorrect)
* Fix/Enhancement - Updater works correct
* Fix/Enhancement - Selecting color in design settings sometime didn't work
* Fix/Enhancement - Hide attribute values didn't work in widget with type radio
* Fix/Enhancement - Color type in widgets now work with custom taxonomy

= 2.0.5.1 =
* Enhancement - Better default design for checkbox and radio
* Enhancement - Description icon stick to top instead of the middle
* Enhancement - BeRocket Account moved to the Admin -> Settings
* Enhancement - Added debug mode
* Fix/Enhancement - Updated nice urls feature
* Fix/Enhancement - Widget title color wasn't working
* Fix/Enhancement - Other enhancements/fixes

= 2.0.5 =
* Enhancement - Optimization to handle up to 10,000 products total. Next step is 100,000 products
* Enhancement - New widget: Selected Filters area. When you select any filter value it will be shown in the widget
* Enhancement - Selected filters area could be set above products
* Enhancement - Added option to set values for the price slider manually(eg 1,2,6,50,200,1000)
* Enhancement - Added option to hide widget on selected pages
* Enhancement - Option to set values order right in widget
* Enhancement - Option to hide collapse arrow
* Enhancement - Option to set order for the categories and other taxonomies
* Enhancement - Added wrapper for widget with class `berocket_aapf_widget-wrapper` that can be used to style widget
* Enhancement - Added Checkbox, Radio, Slider style customization
* Enhancement - Added cache for filters with cache plugins or with WordPress fucntionality
* Enhancement - Added nice URLs with customization
* Enhancement - Added shortcode builder for more easier short code creation
* Enhancement - More friendly widget creating interface
* Enhancement - Added Parent/Child widget with ajax load on parent changes
* Enhancement - Added update plugin from BeRocket site using account/product key
* Enhancement - Added customization for products counter style
* Enhancement - Added number style customization
* Fix/Enhancement - Plugins multisite compatibility
* Fix/Enhancement - Hide filters values without products
* Fix/Enhancement - Works with custom pagination
* Fix/Enhancement - Remove products with visibility hidden
* Fix/Enhancement - WPML loads incorrect language on filtering
* Fix/Enhancement - Better support for attributes order Name(Numeric) from the WooCommerce
* Fix/Enhancement - Categories selected from taxonomy following hierarchy
* Fix/Enhancement - not all elements were really default when in Design tab you set default to the theme
* Fix/Enhancement - Using % symbol in values was lead to not working widgets

= 2.0.4.1 =
* Fix - featured images for post wasn't working because media included 2 times
* Fix - loading image small fix
* Fix - product tag recount

= 2.0.4 =
* Enhancement - option to re-count products amount in values when some value selected
* Enhancement - filter by sub-categories
* Enhancement - description can be added for the attributes
* Enhancement - option to hide selected values and/or without products and add at the bottom button to show them
* Enhancement - Filter by availability ( in stock | out of stock | any )
* Enhancement - option to upload "Loading..." gif image and option to set label after/before/above/under it
* Enhancement - show icons before/after widget title and/or before/after values
* Enhancement - scroll top position can be controlled by the admin
* Enhancement - option to hide on mobile devices
* Fix/Enhancement - Pagination and Order By selectors added to the settings page for better support
* Fix/Enhancement - Design Tab has `Theme Default` buttons to drop custom values
* Fix/Enhancement - color filter enhancements
* Fix/Enhancement - if there are no WooCommerce or it is too old error will be shown
* Fix/Enhancement - plugin wasn't using variations
* Fix/Enhancement - WPML support added
* Fix/Enhancement - option added to control if Order By and Pagination removed on filter updated or not
* Fix/Enhancement - Loading box enhancements
* Fix - extra class wasn't adding
* Fix - radio box enhancements
* Fix - pagination wasn't working because of the "jump to first page" feature
* Fix - url with predefined filters was limiting amount of values in other filter widgets

= 1.1.0.3 =
* Fix - on widgets page widget now has subcategories(hierarchy)
* Fix - all categories are visible, not only that have products inside(popular)

= 1.1.0.2 =
* Fix - another js issue that stops plugin from work
* Fix - order by name, name_numeric and attribute ID wasn't working

= 1.1.0.1 =
* Fix - js issue that stops plugin from work

= 1.1.0 =
* Enhancement - Show all values - on plugin settings page you can enable option to show all values no matter if they are used or not
* Enhancement - Values order - you can set values order when editing attribute. You can set how to order (by id, name or custom). If
you set to order `by custom` you can drag&amp;drop values up and down and set your own order.
* Small fixes

= 1.0.4.5 =
* Enhancement - values order added. Now order of values can be controlled through attribute options
* Enhancement/Fix - Better support for for category pages
* Other small fixes

= 1.0.4.4 =
* Enhancement - adding callback for before_update, on_update, after_update events.
* Other small fixes

= 1.0.4.3 =
* Enhancement - shortcode added
* Critical/Fix - If slider match none its values wasn't counted
* Enhancement/Fix - Changing attribute data location from url to action-element, providing more flexibility for template
* Enhancement/Templating - Using full products loop instead of including product content template
* Fix - Pagination with SEO url issue

= 1.0.4.2 =
* Enhancement/Fix - Better support for SEO urls with permalinks on/off
* Fix - Critical bug that was returning incorrect products.

= 1.0.4.1 =
* Enhancement - Adding AJAX for pagination.
* Enhancement - Adding PushState for pagination.
* Enhancement/Fix - Pagination wasn't updating when filters used.
* Enhancement/Fix - Text with amount of results (Eg "Showing all 2 results") wasn't updating after filters applied
* Enhancement/Fix - When choosing Slider in admin Operator became hidden
* Fix - All sliders except price wasn't working with SEO url
* Fix - When changing attribute to/from price in admin all filters jumping
* Fix - After filter applied all products was showed. Even those with Draft status.

= 1.0.4 =
* Enhancement - SEO friendly urls with possibility for users to share/bookmark their search. Will be shortened in future
* Enhancement - Option added to turn SEO friendly urls on/off. Off by default as this is first version of this feature
* Enhancement - Option to turn filters on/off globally
* Enhancement - Option to take control over (default) sorting function, make it AJAXy and work with filters
* Fix - Sorting remain correct after using filters. Sorting wasn't counted before
* Fix - If there are 2 or more sliders they are not working correctly.
* Fix - Values in slider was converted to float even when value ia not a price.
* Fix - If there are 2 or more values for attribute it was not validated when used in slider.

= 1.0.3.6 =
* Fix - Removed actions that provide warning messages
* Enhancement - Actions and filters inside plugin

= 1.0.3.3 =
* Enhancement/Fix - Showing products and options now depending on woocommerce_hide_out_of_stock_items option
* Enhancement/Fix - If not enough data available( quantity of options < 2 ) filters will not be shown.
* Fix - If in category, only products/options from this category will be shown

= 1.0.3.2 =
* Fix - wrong path was committed in previous version that killed plugin

= 1.0.3 =
* Enhancement - CSS and JavaScript files minimized
* Enhancement - Settings page added
* Enhancement - "No Products" message and it's class can be changed through admin
* Enhancement - Option added that can enable control over sorting( if visible )
* Enhancement - User can select several categories instead of one. Now you don't need to create several same filters
  for different categories.
* Enhancement - Added option "include subcats?". if selected filter will be shown in selected categories and their
  subcategories
* Fix - Adding support to themes that require product div to have "product" class
* Fix - Slider in categories wasn't initialized
* Fix - Subcategories wasn't working. Only Main categories were showing filters
* Templating - return woocommerce/theme default structure for product
* Templating - html parts moved to separate files in templates folder. You can overwrite them by creating folder
  "woocommerce-filters" and file with same name as in plugin templates folder.

= 1.0.2 =
* Fix - better support for older PHP versions

= 1.0.1 =
* First public version
