# Dev Guide

## Setup


#### Step 1. Install the [Bitnami Stack](https://bitnami.com/tag/postgresql) for your OS

#### Step 2. Replace the `htdocs` folder with `/app`

By default files are served from `<BitnamiRoot>/apache2/htdocs`. In this repository, the .php files are stored in the `/app` directory. To link the two folders together, do the following:

1. Backup or remove the default `htdocs` folder
2. Make a symbolic link to the `app` folder
    - *(Windows)* `mklink /D /path/to/htdocs /path/to/app`
    - *(Mac/Unix)* `ln -s /path/to/app /path/to/htdocs`

#### Step 3. Configure the server to refresh files on change

By default, the server takes 60 seconds before the cache is updated and for your browser to reflect file changes. To have the server update immediately, do the following:

1. Modify the file `<BitnamiRoot>/php/php.ini`
2. Configure the option `opcache.revalidate_freq=0` 
3. Restart Apache
