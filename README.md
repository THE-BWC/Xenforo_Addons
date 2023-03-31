# BWC Xenforo Addons
This repository contains all the addons that have been created for BWC Xenforo.

## Current Addons
* ApiExtension
* LogAllModActions
* PostEditor

### ApiExtension
This addon adds a new API endpoint to Xenforo. This endpoint allows you to sign in a user using their session ID or Remember cookie. This is useful for applications that reside on the same domain as Xenforo and wants to allow for seamless sign in.
The API currently adds the following actions:
* `auth/from-session` - Logs in a user using their session ID or Remember cookie. This is useful for applications that reside on the same domain as Xenforo and wants to allow for seamless sign in.

Note: The `remember_cookie` is the `xf_user` cookie. To use it for authentication, you must alter the string by replacing `%2C` with `,`. This is because the cookie is URL encoded.

### LogAllModActions
This addon logs all moderator actions to the database. Even if the member is not a moderator, the action will be logged. This is useful for auditing purposes.

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

## License
All rights reserved to Black Widow Company. This repository is for internal use only. No redistribution is allowed.  
[<img alt="Black Widow Company" height="50" src="https://the-bwc.com/PAO/BannerStandard.png"/>](https://www.the-bwc.com)


## Disclaimer
If you are not a member of Black Widow Company, you are not allowed to use, modify or distribute any of the files in this repository without the express permission of the S-1 Technical Officer or the S-1 Officer in Charge.

## Contact
If you have any questions, feel free to contact me on Discord: `[BWC] Patrick#4943`, or on the [BWC Discord server](https://discord.com/invite/the-bwc) or the [BWC forums](https://the-bwc.com/forum/index.php).

## Credits
* [Xenforo](https://xenforo.com/)
* [Black Widow Company](https://the-bwc.com/)
* [BWC Discord server](https://discord.com/invite/the-bwc)
* [BWC Forums](https://the-bwc.com/forum/index.php)