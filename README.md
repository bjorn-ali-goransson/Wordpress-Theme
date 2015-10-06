Wordpress theme
===============

For project-specific code, use `application.php` and `application-functions.php`.

If you want to add functions generic to your work in general, use `functions.php`, or even better, add your own function module by putting the function in a `.php` file in `functions/`.

To see why I like this, check out `application.php`.

Features
--------

* Bootstrap 3 and Font Awesome included with Bower (just run setup.bat)
* Gulp (albeit simplistic) assembly of bower components into `vendor/` directory
* LessJS compilation, minification and watch (using Gulp), along with inclusion from PHP (`add_my_style('style.less')`) without hooks
* Simple inclusion of scripts like `add_my_script('angular.min.js', 'angular')` (from bower) or `add_my_admin_script('admin.js', array('jquery'));` (in your scripts directory)
* Includes your `style.less` in the TinyMCE WYSIWYG editor (see `styles/wp-editor-styles.less`)
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