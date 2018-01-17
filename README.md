MyCoachVideo
===============================


INSTALLATION
-------------------

```
#required soft
--------------------------------------------------------

install ffmpeg
install  php x.x  curl


#php  configuration file 
--------------------------------------------------------

post_max_size = 2G
upload_max_filesize = 2G
max_input_time -1
make script execution to unlimited 


# In root directory
--------------------------------------------------------
# setup environment
Run command
php init 
then choose 0 as development
then Initialize the application under 'Development' environment? //choose Yes
then ...overwrite? // choose No

# vendor folder
Run command  composer update  in "root" directory
Will create vendor folder in "root" directory  and  will add dependencies

#db
Following name must be replaced
development   db   name  devmycoachdb   file location	/common/config/main-local.php

```
DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
 
```
