# Dev Guide

## Setup


#### Step 1. Install the [Bitnami Stack](https://bitnami.com/tag/postgresql) for your OS

#### Step 2. Replace the `htdocs` folder with `/app`

By default files are served from `<BitnamiRoot>/apache2/htdocs`. In this repository, the .php files are stored in the `/app` directory. To link the two folders together, do the following:

1. Backup or remove the default `htdocs` folder
2. Make a symbolic link to the `app` folder
    - *(Windows)* `mklink /D /path/to/htdocs /path/to/app`
    - *(Mac/Unix)* `ln -s /path/to/app /path/to/htdocs`

#### Step 3. Configure the server to make your life easier

1. Edit the file `<BitnamiRoot>/php/php.ini`
2. Set option `opcache.revalidate_freq=0`  (React to file changes immediately)
3. Set option `display_errors = On` (Show me the errors!)
4. Restart Apache

#### Step 4. Configure your database password

Instead of embedding the database password within the source code (super bad practice), we define it in a separate file as an environment variable for use within the code. Still not best practice, but somewhat more secure.

1. Edit the file `<BitnamiRoot>/apache2/conf/httpd.conf`
2. Add `SetEnv DB_PASSWORD <your_database_password>` to the very last line

## Editing CSS source

*(Optional) This section is only required if you wish to edit the website's CSS.*

We use [Sass](http://sass-lang.com/) for our stylesheets, because once you've used Sass or some other CSS pre-processor, you wouldn't ever want to go back to writing CSS directly.

#### (Setup) Install build tools

1. Install [Node.js](https://nodejs.org/en/download/) for your OS
2. Navigate to the Stuffy repository
3. Run the following commands

```bash
# Install Gulp globally
npm install -g gulp-cli

# Install dependencies
npm install 
```

#### Build Sass source files

Run the following in a terminal:

```bash
gulp sass:watch
```

Now any changes you make to the Sass source files will be automatically built and placed in `Stuffy/app/resources/css/style.css`.
