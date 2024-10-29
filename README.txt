=== Active Catalog ===
Contributors: activeconv
Donate link: https://activeiq.co/
Tags: catalog, product catalog, catalogue, industrial catalog, industrial products
Requires at least: 6.0
Tested up to: 6.3.2
Stable tag: 1.3
Requires PHP: 8.0
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Active Catalog gives industrial B2B companies an easy way to organize and display their product catalog, making it simple for your customers to find exactly what they’re looking for.

Whether you have fifty products or thousands, Active Catalog can quickly import your product catalog and create a unique page to display each product. Use the free Active Catalog plugin alone on your Wordpress site or connect it to ActiveIQ for enhanced visibility into visitor interactions with your products.

The Active Catalog plugin is designed specifically for industrial B2B companies, so you don’t have to deal with all the unnecessary extras found in other catalog plugins. Active Catalog gives your customers the chance to download product brochures, specifications, or other useful documents and the ability to contact you for further information. Want deeper insight into the buyer’s activity? Integrate with ActiveIQ to follow them through the sales pipeline and get notified whenever they return to your website or interact with your brand. We know industrial sales cycles are long, so we let you gate these downloads to identify the interested buyer and nurture them to a sale.

= Add Category =

1. Under the menu, click on Product Catalog -> Category
2. Click on the Add Category button
3. Enter the Name for the category and an optional Image
4. Click on Save button

= Formatting Product Catalog CSV =

Your CSV file should contain the following header titles: product, sku, tags, categories, brochures, spec sheets, manufacturer, price, content, main image and secondary images.

* Product: Name of the product to be displayed on the product page.
* SKU: SKU number. Displayed on the product page.
* Tags: Relevant tags to improve searchability and display of similar products. Not shown on the product page.
* Categories: Category assignment to improve organization and searchability. Categories will be displayed on the product page.
* Brochures: Links to brochures. We suggest using WordPress content uploads. Downloadable from the product page.
* Spec Sheets: Links to technical spec sheets. We suggest using WordPress content uploads. Downloadable from the product page.
* Manufacturer: Product manufacturer. Displayed on the product page.
* Price: Price of the product. Do not include currency symbol. Displayed on the product page.
* Content: The full product description to be displayed on the product page. You can use HTML formatting to change its appearance on the page.
* Main Image: The first image to be displayed on the product page. Include a link to the content URL in WordPress.
* Secondary Images: Other images to be displayed. Include a link to the content URL in WordPress.

If a field is left unpopulated, it will be removed from the product page.

= Import Product Catalog =

1. Log into WordPress admin
2. Go to Active Catalog section
3. Click on Import Catalog button
4. Select the product catalog CSV file that contains the proper header columns and click Upload CSV
5. In the next screen, review and ensure the detected columns are matching the proper field names
6. When ready, click on Continue
7. This confirmation page will show:
    * You are about to import the product catalog. Please confirm:
    * Total items to be imported: 136
    * Items to be created: 89
    * Items to be updated: 46
    * Duplicate items: 2
8. Click on Confirm Import button to import the catalog.

= Add Individual Product =

1. Log into WordPress admin
2. Go to Active Catalog section
3. Click on Add New
4. The product page editor will appear
5. Fill in the content as you would a standard word press page.
6. Publish the page.

== Installation ==

= USING THE WORDPRESS DASHBOARD =

1. Navigate to "Plugins->Add New" from your dashboard
2. Search for "Active Catalog"
3. Click "Install Now"
4. Activate the plugin

= UPLOADING VIA WORDPRESS DASHBOARD =
1. Go to [https://wordpress.org/plugins/active-catalog/](https://wordpress.org/plugins/active-catalog/) and download the plugin
2. Navigate to the “Add New” in the plugins dashboard
3. Navigate to the “Upload” area
4. Select ActiveCatalog.zip from your computer
5. Click “Install Now”
6. Activate the plugin in the Plugin dashboard

= USING FTP =
1. Download the plugin from [https://wordpress.org/plugins/active-catalog/](https://wordpress.org/plugins/active-catalog/)
2. Extract the Active Catalog directory on your computer
3. Upload the Active Catalog directory to the /wp-content/plugins/
4. Activate it from the Plugins dashboard

== Frequently Asked Questions ==

= Where can I find the CSV template to import products? =

You can find an empty CSV file with the column names in the main screen of the plugin. There is a link "Download CSV Template"

== Screenshots ==

1. Main Products View
2. Creating a new product
3. Importing products using a CSV file

== Changelog ==

= 1.2 =
* Fixed menu item add bug
* Updated and tested to WordPress 6.3.2

= 1.1 =
* Added mode information about the plugin and how to use it

= 1.0 =
* Initial public release

== Upgrade Notice ==

= 1.2 =
* Fixed menu item add bug
* Updated and tested to WordPress 6.3.2

= 1.1 =
* Official Public Release

= 1.0 =
* Initial public release