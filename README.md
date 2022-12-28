# ToDoList [![SymfonyInsight](https://insight.symfony.com/projects/d0d93802-143d-46f5-9e74-6163290ced9c/mini.svg)](https://insight.symfony.com/projects/d0d93802-143d-46f5-9e74-6163290ced9c) [![Codacy Badge](https://app.codacy.com/project/badge/Coverage/e1104474edbb4126b1dd339d5dd7f058)](https://www.codacy.com/gh/TrAsKiN/todo-list/dashboard?utm_source=github.com&utm_medium=referral&utm_content=TrAsKiN/todo-list&utm_campaign=Badge_Coverage)

## Prerequisites

* Docker
* PHP 8.1
* Symfony CLI

## Installation and configuration

1. Clone or download the repository
2. Duplicate and rename the `.env` file to `.env.local` and modify the necessary information (`APP_ENV`, `APP_SECRET`, ...)
3. Install the dependencies with `symfony composer install --optimize-autoloader --classmap-authoritative`
4. Run migrations with `symfony console doctrine:migrations:migrate --no-interaction`
5. Add default datasets with `symfony console doctrine:fixtures:load --no-interaction`

## Launch the local server

Run the command `symfony server:start -d` to start the local server and access the site at the indicated address or type `symfony open:local`.

## Default account credentials

1. * Username: `User`
   * Password: `user`
2. * Username: `Admin`
   * Password: `admin`

## Run the tests

Run the `make tests` command to run the tests and get the code coverage.
