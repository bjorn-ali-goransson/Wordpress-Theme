Wordpress theme
===============

I've identified three different types of code that typically go into a Wordpress theme: General useful functions, calling of functions with project-specific parameters, and project-specific code.

1. General useful functions that are re-used between projects, and gradually improved upon. Put these into `functions/a_useful_function.php` to have them `require_once`:d before anything else. For inter-dependencies, use `require_once dirname(__FILE__) . '/my-dependency.php'` .

2. Calling WP-functions (and code from the previous category) with project-specific parameters. This goes into `application.php`. Here, all functions from category 1 are already loaded.

3. Project-specific code, shortcodes, routes. Mainly without inter-dependencies. Separate these into their own `php` files and put them inside `application/` to have them automatically loaded after `application.php` has been run.

Don't put anything inside `functions.php` - you know it's wrong. Resist the temptation!

Instructions
------------

Prerequisites: [git](https://git-scm.com/) [node](https://nodejs.org/), `npm install brunch -g`.

To download, start a command prompt and create a new directory for your theme:

```
mkdir "my-theme" & cd "my-theme"
```

Then do:

```
git clone https://github.com/bjorn-ali-goransson/Wordpress-Theme.git . & rmdir /S /Q .git & del .git* & del README.md & del application\logout_route.php & setup
```

To build everything, run `build.bat`.

To build everything for production, run `build-production.bat`.

To watch-build everything, run `watch.bat`.

When publishing your theme, there's no need to transfer the `node_modules` to the target server. They are not (and should not) be used by the theme.