# wordpress-openid-connect
The best OpenID Connect plugin for WordPress

## Credit & current contributors
- [@bolailumoka](https://github.com/bolailumoka) Thank you for the initial commit! 
- The FusionAuth team

## Installation

- download the fusionauth-wordpress-openid-connect directory in the repository
- compress the fusionauth-wordpress-openid-connect directory into a zip file of the same name and save it to a location on your computer.
- Go to the plugin admin section of your wordpress site.
- Click 'Add New'. 
- Then click 'Upload Plugin'.
- Use the 'Choose File' button to browse to the location of the zip file and install the plugin.
- Once the plugin has been installed and activated, you will see 'FusionAuth SSO Client' on the side menu.
- Click on the 'FusionAuth SSO Client' link which will take you to the admin page of the plugin.

## Configuration

- In the admin page of the plugin, you will see 7 active fields.
- 'App Name' - the name of your your fusion auth application as saved in your fusion auth dashboard.
- 'OpenID Server Url' the url of your fusion auth app. Example 'https://login.piedpiper.com'.
- 'Client Id' - the client id of your fusion auth application.
- 'Client Secret'  the client secret of your fusion auth app.
- 'Redirect Uri' - the redirect uri with the http protocol as configured in your fusion auth dashboard.
- 'Scopes' - the scopes configured for the fusion auth app. the default for fusion auth is the following twom, openid and offline_access.
- 'Force WP Login Query' - this will be a query string determined by the wordpress admin to force the use of the wordpress login page instead of fusion auth login. For example, if you specify 'hihi' as the query in the configuration, you can use this path [wordpresssite]/wp-login.php?hihi which would allow the admin to use the wordpress login page to access the wordpress dashboard. If you don't specify the query, you will be redirected to the fusion auth login page.
- 'Implicit Grant' - this is not active yet. The purpose is to allow for implicit grant. This requires some further development.

## Enable Configuration
- Once you have saved the configuration, you will see it below the form field as  table row. The configuration is not enable until you click the 'Enable' butoon for that row.
- You can save multiple configurations which will appear as a table below the form fields, but only one configuration can be enable at any point in time.


## Bounty Notice
This library is currently in development it is not yet available for public consumption. If you wish to contribute, we are offering bounties for high quality code.

We are building the best OpenID Connect plugin for WordPress designed to work with any OpenID Connect Identity Provider, but built and manintained by FusionAuth. 

> **WARNING**: This project is still in development, feel free to use, but it may not work. You may still feel free to open issues or submit PRs. 
