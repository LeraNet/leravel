![Leravel Logo+](https://cdn.discordapp.com/attachments/989920686065725490/1097604721369423953/leravellogo.png)
# leravel

Leravel is a PHP framework that claims to have cool features like a router, template system, built-in admin panel, localization system, and being lightweight.

## Features
- 🧻 A cool router.
- 📜 A template system similar to Blade.
- 🔨 A built-in admin panel.
- 💬 A built-in localization system.
- 👨‍💻 A built-in CLI system.
- 💹 A built in website stats system .
- 🥋 Lightweight (I think).

## Getting Started
To get started with Leravel, follow these steps:

0. Leravel needs gd extention for its captcha. Also you will need php and mysql
1. Download the latest Leravel release from the releases(Download leravelApp.zip)
2. Extract the leravel app anywhere.
3. By the default leravel tries to connect to a MySQL server. If you dont have a MySQL server. Disable the default connection in the `app/settings.json` file.
4. Run cli.bat or cli.sh.
5. Type `>start` to start your web application.
6. In order to use the admin panel you need to go to `http://localhost:8000?admin` not `/admin` it is `?admin`

## Leravel Admin
![leravel admin screenshot](https://cdn.discordapp.com/attachments/989920686065725490/1098666798129360967/image.png)

To access the Leravel admin, go to `/?admin` on your website. The admin username and password can be found in `/leravel/admin/account.ini`. If you want to disable the admin panel, you can do so from `settings.json`.

## Staying Up-to-Date
When you enter the Leravel admin, it will check for updates. If an update is found, it will remind you to update.

![update screenshot](https://cdn.discordapp.com/attachments/989920686065725490/1098668180479676519/image.png)
