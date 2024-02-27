<img alt="Black Widow Company" src="https://the-bwc.com/PAO/BannerStandard.png"/>

# BWC Xenforo Addons
This repository contains all the addons that have been created for BWC Xenforo.

## Current Addons
* ApiExtension
* Keycloak
* LogAllModActions
* MembersLocalTime
* Opserv
* PostEditor

### ApiExtension
This addon adds a new API endpoint to Xenforo. This endpoint allows you to sign in a user using their session ID or Remember cookie. This is useful for applications that reside on the same domain as Xenforo and wants to allow for seamless sign in.
The API currently adds the following actions:
* `auth` - Tests a login and password for validity. Only available to super user keys. We strongly recommend the login and password parameters are passed into the request body rather than the query string.
* `auth/from-session` - Looks up the active XenForo user based on session ID or remember cookie value. This can be used to help with seamless SSO with XF, assuming the session or remember cookies are available to your page. At least one of session_id and remember_cookie must be provided. Only available to super user keys.
* `auth/login-token` - Generates a token that can automatically log into a specific XenForo user when the login URL is visited. If the visitor is already logged into a XenForo account, they will not be logged into the specified account. Only available to super user keys.

Note: The `remember_cookie` is the `xf_user` cookie. To use it for authentication, you must alter the string by replacing `%2C` with `,`. This is because the cookie is URL encoded.

### Keycloak
This addon allows for seamless integration with Keycloak. It allows for the following:
* Single Sign On (SSO) with Keycloak
* User creation and updating

### LogAllModActions
This addon logs all moderator actions to the database. Even if the member is not a moderator, the action will be logged. This is useful for auditing purposes.

### MembersLocalTime
This addon adds a new column to the members information that shows the local time of the member. This is useful for scheduling events and knowing when a member is online.

### Opserv
This addon adds several new navigation links to the navigation bar, BB code, and extends the user class.
The following links are added to the navigation bar:
* Opserv - A link to the Opserv page.
  * Ranks - A link to the Ranks page.
  * Chain of Command - A link to the Chain of Command page.

The following BB code is added:
* [FS=userId] - Shows the member's Fruit Salad. Replace `userId` with the member's user ID.

### PostEditor
This addon adds a footnote to a post that shows the last editor of the post. This is useful for auditing purposes.

## Installation
1. Download the addon from the releases page.
2. Upload the addon to your Xenforo server.
3. Install the addon from the AdminCP.
4. Configure the addon as needed.
5. Enjoy!

## Building
Note: You will need to have Xenforo installed on your machine to build the addons.
1. Select the addon you want to build.
2. Download the addon from the repository.
3. Copy the addon to the `src/addons` folder in your Xenforo installation.
4. Open a terminal and run `php cmd.php xf-addon:build-release <addon-author/addon name>`.  
Example: `php cmd.php xf-addon:build-release Patrick/ApiExtension`.  
Note: Patrick is required for the addon to be built due to the way the addon was originally created. More information can be found [here](https://xenforo.com/docs/dev/lets-build-an-add-on/#building-the-add-on).
5. Select the addon you want to build.
6. The addon will be built to the `src/addons/addon-author/addon-name/_releases` folder in your Xenforo installation.'
7. Enjoy!

## Contributing
1. Fork the repository.
2. Create a new branch for your changes.
3. Make your changes.
4. Create a pull request.
5. Enjoy!

## Contact
If you have any questions, feel free to contact me at:
- [BWC Discord](https://discord.the-bwc.com/) `[BWC] Patrick`
- [BWC Forums](https://the-bwc.com/forum/index.php) `Patrick`.

## Credits
* [Xenforo](https://xenforo.com/)
* [Black Widow Company](https://the-bwc.com/)
* [BWC Discord server](https://discord.com/invite/the-bwc)
* [BWC Forums](https://the-bwc.com/forum/index.php)