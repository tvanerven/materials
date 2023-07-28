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

<img src="https://github.com/tvanerven/materialsfrontend/raw/main/eu_logo.png" width="64" height="47">This project has received funding from the European Union’s Horizon 2020 research and innovation programme under grant agreement No 101017536. The funding was awarded through the RDA (https://www.rd-alliance.org/) Open Call mechanism (https://eoscfuture-grants.eu/provider/research-data-alliance) based on evaluations of external, independent experts.

