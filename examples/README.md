# How to configure and test it

## Steps to check the simple MVP script

Before the start please be sure that you have correct permissions for files and directories in your system.

* `$ cd examples` // I mean the "imgToWeb/examples" directory
* Run the webserver, i.e. `$ php -S localhost:8123`
* Go to http://localhost:8123/00_mvp.php?img=static/uploads/guy.jpg
* Or check the URL: http://localhost:8123/01_src.php?img=static/uploads/guy.jpg
* Check the "tests/resized" directory, expected is to get `examples/resized/static/uploads/guy.webp` file

Have fun.