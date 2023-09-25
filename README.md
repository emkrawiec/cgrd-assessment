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
  
