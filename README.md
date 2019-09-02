Wordpress theme
===============

This theme implements vertical slicing of functionality through a new folder, `application/`. All PHP files dropped here will be executed on each request. (You may nest it) So if you have mongo-huge `init` hooks in your `functions.php`, start splitting it up into separate files. Better for version control, and can be easily copied between projects.

Before calling your `application/` files, the theme includes all files in `functions/`. Here you should put your own utility functions as well. (perhaps in a separate folder?) Don't put anything auto-executing here, then you should put it in `application/`.

Finally, in `application.php`, do any core startup stuff you like.

It writes code for you
======================

Are you constantly looking at your ACF field names, back and forth, when creating your templates?

This theme actually writes templates for you, if you satisfy some conditions.

1. Be an Administrator.
2. Use ACF Pro, as the regular version doesn't support Flexible Content.
3. Add a field (in a field group) called Modules (`modules`). 
4. For each flexible content layout, name the layout, and add some fields. Try using Heading (naming it this way will be more uniform, and , repeatable content, grouped content...
5. Use the layout on a page. Save it. Publish it.
6. Navigate to your page with your browser. You should see an error message saying "Could not display ...".
7. You will now see a new file created under `modules/`. It should have the same name as your layout.
8. Now, when reloading the page, it should use that module file and show you a basic version of your layout.
9. If you want to regenerate your template, just delete the file and reload the page.

Víola! 🎻
