Wordpress theme
===============

For project-specific code, use `application.php` and `application-functions.php`. If you want to add functions generic to your work in general, use `functions.php`, or even better, add your own function module by putting the function in a `.php` file in `functions/`.

To see why I like this, check out application.php and notice the lack of most hooks.

Instructions
------------

Prerequisites: [node](https://nodejs.org/), `npm install bower -g`, `npm install gulp -g`.

To download, type:

```
git clone https://github.com/bornemix/Wordpress-Theme.git my-new-theme
```

When inside, do:

```
setup
```

To compile-watch your .less files, do:

```
less
```