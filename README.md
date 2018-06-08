Wordpress theme
===============

There are three different types of code that typically go into a Wordpress theme: General useful functions, calling of functions with project-specific parameters, and project-specific code.

1. General useful functions that are re-used between projects, and gradually improved upon. Put these into `functions/a_useful_function.php` to have them `require_once`:d before anything else. For inter-dependencies, use `require_once dirname(__FILE__) . '/my-dependency.php'` .

2. Calling WP-functions (and code from the previous category) with project-specific parameters. This goes into `application.php`. Here, all functions from category 1 are already loaded.

3. Project-specific code, shortcodes, routes. Mainly without inter-dependencies. Separate these into their own `php` files and put them inside `application/` to have them automatically loaded after `application.php` has been run.

Don't put anything inside `functions.php` - you know it's wrong. Resist the temptation!