Wordpress theme
===============

For project-specific code, use `application.php` and `application-functions.php`. If you want to add functions generic to your work in general, use `functions.php`, or even better, add your own function module by putting the function in a `.php` file in `functions/`.

To see why I like this, check out `application.php`.

Features
--------

* Bootstrap 3 and Font Awesome included with Bower (just run setup.bat)
* Gives you the site typography in the TinyMCE WYSIWYG editor
* Removes the "Hello World!" (Post ID: 1) and "Test Page" (Post ID: 2) upon theme activation

Instructions
------------

Prerequisites: [node](https://nodejs.org/), `npm install bower -g`, `npm install gulp -g`.

To download, type:

```
git clone https://github.com/bornemix/Wordpress-Theme.git my-new-theme
```

To install dependencies and compile .less files, start `setup.bat`.

To compile-watch your .less files, start `less.bat`.