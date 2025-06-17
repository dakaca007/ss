# Super Fast Docker PHP Project

This project is designed to provide a quick deployment solution for a PHP application using Nginx. It is specifically tailored for testing H5 mobile adaptive game development without the need for a local MySQL database, as it connects to a remote MySQL instance.

## Project Structure

```
super-fast-docker-php
├── src
│   ├── index.php          # Entry point for the application
│   └── assets
│       └── style.css      # CSS styles for the game interface
├── nginx
│   └── default.conf       # Nginx configuration file
├── Dockerfile              # Dockerfile for building the image
├── docker-compose.yml      # Docker Compose configuration
└── README.md               # Project documentation
```

## Getting Started

### Prerequisites

- Docker
- Docker Compose

### Building the Project

1. Clone the repository:
   ```
   git clone <repository-url>
   cd super-fast-docker-php
   ```

2. Build the Docker image:
   ```
   docker-compose build
   ```

### Running the Project

To start the application, run:
```
docker-compose up
```

This command will start the Nginx and PHP services defined in the `docker-compose.yml` file.

### Accessing the Application

Once the containers are running, you can access the application by navigating to `http://localhost` in your web browser.

### Customization

Feel free to modify the `src/index.php` file to implement your game logic and adjust the `src/assets/style.css` for styling as needed.

## License

This project is licensed under the MIT License. See the LICENSE file for more details.