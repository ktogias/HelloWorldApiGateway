# HelloWorldApiGateway

## Dev image

### How to build dev image:

    docker build -t hello-world-api-gateway-dev --target dev .

### How to run dev image

    docker run -d --publish 8080:80 --volume ./php:/php:Z --name hello-world-api-gateway-dev --rm hello-world-api-gateway-dev

### View live dev in browser:

    http://localhost:8080

### Run tests from dev image:

    docker exec hello-world-api-gateway-dev ./vendor/bin/codecept run

### Run shell in dev:

    docker exec -it hello-world-api-gateway-dev bash

## Test image

### How to build test image:

    docker build -t hello-world-api-gateway-test --target test .

### How to run tests

    docker run hello-world-api-gateway-test ./vendor/bin/codecept run

### How to run test image for manual testing

    docker run --publish 8080:80 --name hello-world-api-gateway-test --rm hello-world-api-gateway-test

## Production image

### How to build production image:

    docker build -t hello-world-api-gateway-prod --target prod .

## How to run production image:

    docker run --publish 8080:80 --name hello-world-api-gateway-prod --rm hello-world-api-gateway-prod

## View production in browser:

http://localhost:8080