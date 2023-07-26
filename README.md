# Materials browser API

Original repo can be found [here](); all instructions apply unless otherwise noted.

## Changes

In short, I dockerized it. There's a Dockerfile that builds directly to a PHP container, installs symfony, and then runs the application.

1. Clone project
2. `docker compose build`
3. `docker compose up`

This should start containers and project will run on http://localhost:8000

Once you're happy with changes, just push (update the `image` directive) to a registry of your choosing.

Use `docker exec -it materials-api bash` & commands in original repo to load data.

## Known issues

Sometimes the container will die for absolutely no reason (usually after a few weeks). If this happens:

1. `docker compose down`
2. `docker compose up -d`
3. Run ontology import again.
