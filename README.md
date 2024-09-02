# Project Setup

This project uses Docker and Docker Compose for the development environment setup. Follow these steps to get the project up and running.

## Prerequisites

Ensure you have Docker and Docker Compose installed on your system.

- **Docker:** [Installation instructions](https://docs.docker.com/get-docker/)
- **Docker Compose:** [Installation instructions](https://docs.docker.com/compose/install/)

## Steps to Set Up the Project

1. **Clone the Repository**

   Clone the repository to your local machine:
   ```bash
   git clone <REPOSITORY_URL>
   cd <PROJECT_DIRECTORY_NAME>
   ```
2. **Start the Project**
    `docker-compose up --build`

3. **Access the Laravel Container**
    `docker-compose exec app bash`

4. **Run Migrations and Seeders**
    `php artisan migrate --seed`

5. **Access Application**
    Open your browser and navigate to http://localhost:8000 (or the URL configured in your environment) to check that the application is running.
