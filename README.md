# Technical Exam - Vokke

## Tech Stack
- PHP 8.2
- Laravel 11
- Inertia
- Vue 3
- DevExtreme Library for DataGrid
- TailwindCSS

## Local Setup
- Clone the project
```bash
git clone https://github.com/nadlambino/vokke-app.git
```
- Copy `.env.example` to `.env`
```bash
cp .env.example .env
```
- Run `composer install`
- Run `npm install`
- Run `npm run dev`
- If you're running the application on Valet, Herd, or Laragon, you can access the app on `http://vokke-app.test`. Otherwise, run `php artisan serve`

## File Uploads
To understand how file upload works, I am using my own Laravel Package to do the file upload via model event which happens when the model is created or updated.

- Run `php artisan storage:link`

## Features
- User Registration
- User Login
- Add a Kangaroo
- List all user's added Kangaroo
- Update a Kangaroo
- Delete a Kangaroo
- Pet policy to authorize CRUD for model Pet
