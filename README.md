# OpenLoad
### The open-source Garry's Mod Loading URL Framework

#### [Installation Instructions](https://github.com/Svenskunganka/OpenLoad/wiki/Installation)
#### [Custom Template Instructions](https://github.com/Svenskunganka/OpenLoad/wiki/Making-custom-templates)

OpenLoad is two things; a loading screen and a framework. It is blazing fast due to its primary components - it's AJAX powered, supports caching of Steam data, prepared statements along with more features. It's easy to customize and anyone with basic HTML & CSS knowledge can design their own template.

Anyone can use/modify this software, for commercial or non-commercial use. Please read the [LICENSE](https://github.com/Svenskunganka/OpenLoad/blob/master/LICENSE) for more in-depth information.

#### Basic Features
* **AJAX Powered** - Meanwhile the assets are being loaded, the back-end is executed.
* **Steam Caching** - As Steam's API is slow, OpenLoad caches player info locally until the next connection, when it updates the cache.
* **Map Image** - OpenLoad will try to fetch the current map image from GameTracker if it is available.
* **Custom templates** - OpenLoad comes with built-in templates for you to use if you don't want to design one of your own.
* **Responsive** - All templates are responsive and works on the major resolutions.
* **Customizable** - You can customize anything you want, literally.

#### Advanced Features (for template designers)
* **Simple HTML-markup** - The framework responds to the `id` attribute and inserts the response into the element.
* **GMod JS Functions** - Even though OpenLoad reserves the `GameDetails`, etc functions that GMod provides, it executes custom functions `ol_GameDetails` with the exact same parameters.
* **Simple SQL interface** - You can easily add your own SQL queries to OpenLoad's core PHP class.
* **Documented** - All the core files are heavily documented, so you can easily get an overview of what the functions do.