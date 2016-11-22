#Magic Login
Simple and password-less login for [Craft CMS](https://craftcms.com/).

##Installation
Download MagicLogin from [GitHub](https://github.com/aberkie/magiclogin) and upload the `magiclogin` directory to your `craft/plugins` folder. Don't forget to install the plugin in your site's admin (yoursite.com/admin/settings/plugins).

##Usage
To use Magic Login, logout of Craft and head over to yoursite.com/magiclogin/login and enter your email address in the form presented. Click **Get Link**, check your email, click the link, and enjoy being logged-in without ever entering a password! 

##Settings
Magic Login allows you to set Magic Login Expiration time and the Redirect URL after Login. 

Adjust "Magic Login Expiration Time" to allow links to be used for a certain amount of time after being requested. The default is 5 minutes.

Adjust "Redirect URL after Login" to change where the user is sent after they login with a Magic Login link. The default is `/admin`. 

##Disclaimer
Usage of this plugin does not guarantee any security for your Craft CMS site. This is an open-source project and has not been vetted by security or encryption experts.