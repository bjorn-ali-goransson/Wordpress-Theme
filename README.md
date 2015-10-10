Wordpress theme
===============

WP theme modularity without boilerplate.

I've identified three different types of code that typically go into a Wordpress theme:

1. General useful functions that are re-used between projects, and gradually improved upon. Put these into `functions/a_useful_function.php` to have them `require_once`:d before anything else. For inter-dependencies, use `require_once dirname(__FILE__) . '/my-dependency.php'` .

2. Calling WP-functions (and code from the previous category) with project-specific parameters. This goes into `application.php`. Here, all functions from category 1 are already loaded.

3. Project-specific code, shortcodes, routes. Mainly without inter-dependencies. Separate these into their own `php` files and put them inside `application/` to have them automatically loaded after `application.php` has been run.

Don't put anything inside `functions.php` - you know it's wrong. Resist the temptation!

To see why I like this, check out `application.php`.

Features
--------

* Bootstrap 3 and Font Awesome included with Bower (just run setup.bat)
* Gulp (albeit simplistic) assembly of bower components into `vendor/` directory
* LessJS compilation, minification and watch (using Gulp), along with inclusion from PHP (`add_my_style('style.less')`) without hooks
* Simple inclusion of scripts like `add_my_script('angular.min.js', 'angular')` (from bower) or `add_my_admin_script('admin.js', array('jquery'));` (in your scripts directory)
* Includes your `style.less` in the TinyMCE WYSIWYG editor (see `styles/wp-editor-styles.less`)
* Add custom HTTP routes with `add_route('/register-competitor', function(){ echo 'hello!'; })` or `add_public_route('/register-competitor', function(){ echo 'hello!'; })`
* Removes the "Hello World!" (Post ID: 1) and "Test Page" (Post ID: 2) upon theme activation
* *Function modules*: Drop any php file inside `functions/`, and it will be required (`once`) before `application.php`
* *Application modules*: Drop any php file inside `application/`, and it will be required (`once`) after `application.php`

Instructions
------------

Prerequisites: [node](https://nodejs.org/), `npm install bower -g`, `npm install gulp -g`.

To download, type:

```
git clone https://github.com/bornemix/Wordpress-Theme.git my-new-theme
```

To install dependencies and compile .less files, start `setup.bat`.

To compile-watch your .less files, start `less.bat`.