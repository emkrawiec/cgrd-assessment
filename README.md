# CGRD assessment
Prerequisites:
- Docker with Docker Compose
- Node v18

## Dev setup
1. Clone the repository
2. Run `bootstrap.sh` to setup the project. Script is prepared for UNIX like systems. This
3. Setup `.env` file by copying `.env.example` and filling in the values.
4. Run `npm install` in the root directory.
5. Run `npm run dev` to start development server.
6. Dev server should be available on `localhost:5173`.

## Production setup
1. Clone the repository
2. Run `bootstrap.sh` to setup the project. Script is prepared for UNIX like systems. This
3. Setup `.env` file by copying `.env.example` and filling in the values. `ENV=production` for production.
4. Run `npm install` in the root directory.
5. Run `npm run build` to build the project.
6. App should be available on `localhost:8080`.
  
## Comment
Hey, thanks for the opportunity to take this task. I tried to be as "fullstack" as possible. :D
Some comments about the task.

### Environment
I used Docker and Docker Compose for whole development environment. To view db `adminer` panel is also added to dev env. 

### PHP
Requirements forbid using php frameworks so I thought. "OK, let's build one myself". I prepared some basic framework structure
with routing, controllers, middlewares, services. Is it production ready? Of course not. But I think it is decent enough and can
be easily extended. Framework files are in `Emkrawiec\Framework` namespace. Some app specific implementations are in `Core` namespace.

Framework uses `nikic/fast-route` and some stubbed user auth strategy with native php sessions for authentication. If there is a problem that
it is should be done manually with my own regexes implementations for routing and auth strategy with db sessions and db users - 
no problem, I can implement it easily. Two new implementations for `AuthStrategy.php` and `FrontController.php` and it done. :) Polymorphism is cool.
Code is in `src/backend` directory.

### Templates
Twig is used as a template engine. I prepared some basic templates for the app and my favorite structure with `components` directory and
reusable UI elements.

### CSS
I used native CSS in `src/frontend/css/style.css`. I used only normalize.css to provide a baseline for CSS to start fresh and
not worry about browser specific styles. Would not count it as CSS library. There is a slight preprocessing made with 
vite because it inlines `@import` statements for performance and minifies the output. Not full-blown like Sass or Less.
I also leveraged CSS variables and cascade layers as "newer" CSS features. :)

### TS
I used Typescript because I love it. Would I be able to do it in JS? Sure, but TS is just better and tooling is easy sooo... :D
I used `vite` as a bundler. Nice choice after Webpack, almost everything works out of the box, not complex config.

### Tests
Wrote some basic integration tests, just for showcase. I usually test with db using Testcontainers but no 1st party support for PHP :(
I used `phpunit` for tests with mocked repositories. I also used `playwright` for e2e tests. Backend tests in `tests` and e2e tests in `e2e`.

### Tools
I use `php-cs-fixer` and `phpstan` for some basic code quality checks. It was my first take for `phpstan`. Looks nice, I am so
used to Typescript I really appreciate static analysis. 

