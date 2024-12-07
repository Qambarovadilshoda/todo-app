
## To Do App

- A simple and efficient To-Do application built with Laravel. This app allows users to manage their tasks with CRUD (Create, Read, Update, Delete) functionality.

## Features

- User Authentication:

 - Login, registration.
 - Email verification.

- Task Management:

 - Users can create, update, and delete tasks.
 - Tasks are categorized with a status (Pending, Completed, Cancelled).

- Task Filtering:

 - Filter tasks by date (last month, last week).
 - Filter tasks by status.

- Task Search:

 - Search tasks based on title.

## Technologies Used

- Backend: Laravel 11
- Database: Sqlite
- Authentication: Manually typed starter cit not used
- Version Control: Git & GitHub for versioning

### Installation

- Clone the repository:

```
git clone https://github.com/azizdevfull/todo-app.git
cd todo-app

```

- Install dependencies using Composer:

```
composer install
```

- Create a .env file and configure the environment variables:

```
cp .env.example .env
```

- Generate the application key:

```
php artisan key:generate

```

- Run the database migrations:

```
php artisan migrate

```

- Serve the application:

```
php artisan Serve
```

- Visit the app in your browser at http://localhost:8000.

## Api documentation

- https://documenter.getpostman.com/view/39432331/2sAYBbf9RQ

## Usage

- Authentication: Register and log in to the app to access task management features.
- Managing Tasks: Once logged in, you can create, update, or delete tasks. You can also filter and search tasks based on different criteria.

## Contributing

- If you'd like to contribute, feel free to fork the repository and submit a pull request with your improvements.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
