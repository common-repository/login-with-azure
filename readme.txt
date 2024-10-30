=== All-in-One Microsoft Office 365 Apps + Azure/EntraID Login ===
Contributors: cyberlord92
Tags: Azure, Office365, Sharepoint, PowerBI, Dynamics CRM 
Requires at least: 3.0.1
Tested up to: 6.6.2
Requires PHP: 5.4
Stable tag: 2.1.3
License: MIT/Expat
License URI: https://docs.miniorange.com/mit-license


Empower your WordPress site with Azure/Entra SSO & Office365/Microsoft Apps integration for seamless productivity with Azure AD/Entra ID and B2C.


== Description ==
Our Azure Office 365 Suite for WordPress plugin integrates various Microsoft 365 services into your WordPress site, enhancing user experience and productivity with seamless access to Azure AD/ Entra ID, Azure AD B2C, SharePoint, PowerBI, Outlook, and Dynamics CRM.


= Features =

= Zero Configuration / Automatic Connection =

* Setup Sharepoint Online, Power BI, Outlook and Dynamic CRM. with just a single click. Simply enter your Azure credentials and gain access to your documents, slides, reports, calendar events and leads directly. 

== <a href="https://plugins.miniorange.com/wordpress-azure-office365-integrations" target="_blank">Azure Single Sign-On (SSO)</a> ==

* **Enable secure SSO for your WordPress site:** Simplify user authentication into WordPress using Azure AD (Entra ID)/ Azure AD B2C as your Identity Provider, providing a seamless Microsoft Login experience.
* **Simplify user management:** Automatically onboard users from Azure/Entra by performing SSO into WordPress using their Azure/Entra credentials, and sync their security groups and claims into WordPress groups and attributes.
* **Supported Identity Providers:**  The plugin supports Azure AD (Entra ID), Azure AD B2C, and Entra External ID (Azure AD for customers), allowing you to onboard users from different Azure Applications making it compatible for different login scenarios.
* **Protocols:**  Utilize secure OAuth/OpenID Connect and SAML 2.0 protocols for Azure Login/SSO.
* **Assign roles:**  Assign WordPress roles based on Azure/Entra Groups and user attributes, enhancing security and user management.
* End to end setup documentation - <a href="https://plugins.miniorange.com/configure-wordpress-azure-sso?setup_guide=azure_ad&utm_source=wordpress%20azuread%20plugin%20readme&utm_medium=organic&utm_campaign=Traffic%20from%20readme" target="_blank">Azure AD (Entra ID)</a>, <a href="https://plugins.miniorange.com/configure-wordpress-azure-sso?setup_guide=azure_b2c&utm_source=wordpress%20azureb2c%20plugin%20readme&utm_medium=organic&utm_campaign=Traffic%20from%20readme" target="_blank">Azure AD B2C</a>.


== <a href="https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration" target="_blank">SharePoint Integration</a> ==

* **Embed SharePoint content:** Embed SharePoint documents, libraries, and lists within WordPress, enhancing document management and sharing capabilities.
* **Facilitate document management:** Enable easy document management and sharing with direct SharePoint access, integrated into your WordPress environment.
* **Restrict direct access:**  Control access to SharePoint files and folders, ensuring secure document handling.
* **Sync user profiles and sites:**  Keep user profiles and sites synchronized between SharePoint and WordPress, maintaining consistency across platforms.
* **Search and view SharePoint files:**  Easily search and change views of SharePoint files, improving document accessibility.
* **Generate file links:**  Create links for SharePoint file downloads and previews, simplifying file sharing.
* **Edit and upload files:**  Edit and upload SharePoint files directly from WordPress.
* **Sync social feeds:**  Integrate SharePoint social feeds like news and articles into WordPress posts.
* End to end setup documentation - <a href="https://plugins.miniorange.com/configure-wordpress-azure-sso?setup_guide=sharepoint&utm_source=wordpress%20plugin%20readme&utm_medium=organic&utm_campaign=Traffic%20from%20readme" target="_blank">Sharepoint</a>.


== <a href="https://plugins.miniorange.com/microsoft-power-bi-embed-for-wordpress" target="_blank">PowerBI Integration</a> ==

* **Embed interactive reports:**  Embed PowerBI reports and dashboards within WordPress pages and posts for dynamic data visualization, bringing insights to your users.
* **Row-level security (RLS):**  Restrict PowerBI data access with RLS, ensuring secure data handling.
* **Content access control:**  Filter and restrict PowerBI content based on user login status, WordPress roles, membership, and Azure AD security groups.
* **Domain-based content access:**  Control access to PowerBI content based on domains, providing customized data views.
* **Embed specific report pages:**  Embed particular pages of PowerBI reports, offering specific data insights to your users.


== <a href="https://plugins.miniorange.com/wordpress-outlook-calendar-events-integration" target="_blank">Outlook Integration</a> ==

* **Display Outlook data:**  Show Outlook calendars, emails, and tasks directly on your WordPress site.
* **Bi-directional contacts sync:**  Synchronize contacts in both directions between Outlook and WordPress, ensuring up-to-date information across platforms.
* **Event creation and synchronization:**  Create events and sync them in real time.


== <a href="https://plugins.miniorange.com/wordpress-integration-with-dynamics-crm-365-apps" target="_blank">Dynamics CRM Integration</a> ==

* **Sync CRM objects:**  Sync contacts, accounts, leads, and other CRM objects from Dynamics 365 Sales to WordPress, providing a seamless CRM integration.
* **Inventory data sync:**  Synchronize inventory data like orders, products, and purchase history between WordPress and Dynamics Business Central, streamlining e-commerce and inventory management.
* **Interact with CRM data:**  Enable users to interact with CRM data without leaving WordPress, improving workflow efficiency.
* **Real-time updates:**  Support real-time updates and synchronization with Dynamics CRM, ensuring accurate and timely data.
* **CRM support:**  Compatible with Dynamics 365 CRM Online and On-premise applications, offering flexibility for different deployment scenarios.
* **Web-to-lead forms:**  Integrate web-to-lead forms seamlessly, capturing leads directly from your WordPress site.


Enhance your WordPress site with the Microsoft 365 Integration Suite plugin, bringing together the best of Azure, SharePoint, PowerBI, Outlook, and Dynamics CRM. Improve productivity, streamline user management, and leverage powerful Microsoft services—all within your WordPress environment. Install now and transform your WordPress experience with integrated Office 365 features and seamless Microsoft 365 access.


== Installation ==


= From your WordPress dashboard =
1. Visit `Plugins > Add New`
2. Search for `All-in-One Microsoft`. Find and Install `All-in-One Microsoft Office 365 Apps + Azure/EntraID Login` plugin by miniOrange
3. Activate the plugin


= From WordPress.org =
1. Download WordPress All-in-One Microsoft Office 365 Apps + Azure/EntraID Login.
2. Unzip and upload the `All-in-One Microsoft Office 365 Apps + Azure/EntraID Login` directory to your `/wp-content/plugins/` directory.
3. Activate All-in-One Microsoft Office 365 Apps + Azure/EntraID Login from your Plugins page.


= Once Activated =
1. Go to `Settings-> All-in-One Microsoft Office 365 Apps + Azure/EntraID Login -> Configure SSO`, and follow the instructions
2. Go to `Appearance->Widgets` ,in available widgets you will find `miniOrange Login with Azure` widget, drag it to chosen widget area where you want it to appear.
3. Now visit your site and you will see login with widget.




== Frequently Asked Questions ==

= How to configure WordPress Azure SSO? =
* Download and install <a href=”https://wordpress.org/plugins/login-with-azure/” target=”_blank”>All-in-One Microsoft Office 365 Apps + Azure/EntraID Login plugin</a>.
* Add your Redirect/Callback URL from WordPress Azure Office 365 Suite plugin, into your AzureAD(Entra ID)/ AzureB2C application.
* Provide the required fields(ClientID, Client Secret, TenantID/ Tenant-Name, Policy-Name) from your AzureAD(Entra ID)/ AzureB2C application to the WP All-in-One Microsoft Plugin on your WordPress site for a successful WordPress-Azure SSO connection.

= I am not able to configure the Azure SSO with provided settings =
Please email us at <a href="mailto:samlsupport@xecurify.com">samlsupport@xecurify.com</a> or <a href="https://miniorange.com/contact" >Contact us</a>. You can also submit your app request from the plugin's configuration page.

= For any query/problem/request =
Visit Troubleshooting section in the plugin OR email us at <a href="mailto:info@xecurify.com">info@xecurify.com</a> or <a href="https://miniorange.com/contact">Contact us</a>. You can also submit your query from the plugin's configuration page.




== Screenshots ==

1. Connect your WordPress site with AzureAD/EntrID & AzureB2C.
2. Configure Attribute Mapping for Users in WordPress.
3. View your Sharepoint Folder/Files
4. Embed Sharepoint Library on WP Page/Post
5. Configure PowerBI App and Generate Shortcode
6. Embed PowerBI Report on WP Page/Post
7. Azure SSO Login Button on the WP Login Page.




== Changelog ==

= 2.1.3 =
* Fix for session vulnerability

= 2.1.2 =
* Added Premium feature tabs in Sharepoint & Power BI

= 2.1.1 =
* Improved Power BI configuration flow

= 2.1.0 =
* Added premium links in the plugin

= 2.0.9 =
* UI Fixes
* Usability improvements

= 2.0.8 =
* PowerBI integration added
* Sharepoint Manual connection added
* Improvements in SSO test window

= 2.0.7 =
* Compatibility with WordPress 6.6.1
* Plugin Logo change

= 2.0.6 =
* Customizable Redirecdt/Callback URI
* Feedback form improvements

= 2.0.5 =
* Plugin name update
* Added default Role Mapping
* Added plugin banner
* Readme Updates

= 2.0.4 =
* Readme changes
* Support form UI changes

= 2.0.3 =
* Major bugfix in sharepoint automatic connection
* UI changes in Feedback form

= 2.0.2 =
* Added automatic application setup for Sharepoint
* UI fixes

= 2.0.1 =
* Compatability with WordPress 6.5.2
* Login widget UI fixes
* Minor Bug fixes

= 2.0.0 =
* Compatibility with WordPress 6.5
* Sharepoint Integration with Entra-ID
* Major UI Update



== Upgrade Notice ==

= 2.1.3 =
* Fix for session vulnerability

= 2.1.2 =
* Added Premium feature tabs in Sharepoint & Power BI

= 2.1.1 =
* Improved Power BI configuration flow

= 2.1.0 =
* Added premium links in the plugin

= 2.0.9 =
* UI Fixes
* Usability improvements

= 2.0.8 =
* PowerBI integration added
* Sharepoint Manual connection added
* Improvements in SSO test window

= 2.0.7 =
* Compatibility with WordPress 6.6.1
* Plugin Logo change

= 2.0.6 =
* Customizable Redirecdt/Callback URI
* Feedback form improvements

= 2.0.5 =
* Plugin name update
* Added default Role Mapping
* Added plugin banner
* Readme Updates

= 2.0.4 =
* Readme changes
* Support form UI changes

= 2.0.3 =
* Major bugfix in sharepoint automatic connection
* UI changes in Feedback form

= 2.0.2 =
* Added automatic application setup for Sharepoint
* UI fixes

= 2.0.1 =
* Compatability with WordPress 6.5.2
* Login widget UI fixes
* Minor Bug fixes

= 2.0.0 =
* Compatibility with WordPress 6.5
* Sharepoint Integration with Entra-ID
* Major UI Update