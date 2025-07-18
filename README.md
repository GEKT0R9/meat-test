DIRECTORY STRUCTURE
-------------------

      config/             contains application configurations
      controllers/        contains Web controller classes
      entity/             contains ActiveRecord entity
      migrations/         contains migrations for database
      models/             contains model classes
      operations/         contains operation classes
      repository/         contains repository classes
      runtime/            contains files generated during runtime
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.4.


INSTALLATION
------------

### Install with Docker
Start the container

    docker-compose up -d

Open php console

    docker exec -it meat-api-php-1 /bin/sh 

Install dependencies

    composer i

Migrate database

    php yii migrate

You can then access the application through the following URL:

    http://127.0.0.1:8000

**NOTES:** 
- Minimum required Docker engine version `17.04` for development (see [Performance tuning for volume mounts](https://docs.docker.com/docker-for-mac/osxfs-caching/))
- The default configuration uses a host-volume in your home directory `.docker-composer` for composer caches

**HELP:**

    chmod 777 runtime