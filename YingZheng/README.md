# YingZheng

This repository is designed to store prototype of LAMP Docker environment for other laravel projects.
In order to combine this prototype of LAMP Docker environment, this repository should be store in the working laravel project's root dirctory, then following the following steps to jump start the project on docker.

## Setup

1. Copy this folder to the working project's root dir

    ```bash
    cp ./YingZheng /path/to/projectRoot/YingZheng
    ```

2. Modify container_name to the project name in @docker-compose.yml`

3. Ensure DB connection.

    Note that this docker env assume that the DB is hosted externally, such as AWS RDS or local mysql machine if local env.

    e.g. connect to a MySQL instance running locally on your macOS via Homebrew
    
    ```bash
    ### Open the MySQL configuration file and update the following lines:
    sudo nano /opt/homebrew/etc/my.cnf

    [mysqld]
    bind-address = 0.0.0.0 [<-added]

    ### Restart MySQL
    brew services restart mysql

    ### Configure your Laravel application to connect to your locally running MySQL:
    sudo /path/to/projectRoot/.env

    DB_HOST=host.docker.internal [<-edited]
    ```

4. Build docker environment

    ```bash
    docker-compose up --build
    ```

5. Confirm access to the project: `http://localhost:8001`

6. Run Migrations (Optional)

    ```bash
    docker-compose run --rm app php artisan migrate
    ```

Done!


***
Gratitude for all the mentors I have met, and the Greate respect to King of Qin, Yíng Zhèng.
***
