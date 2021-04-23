# MikroTik (RouterOS) to MQTT
![Docker Image Size](https://img.shields.io/docker/image-size/zoilomora/mikrotik2mqtt/latest?label=Docker%20Image%20Size&style=flat-square)
![GitHub Last Release](https://img.shields.io/github/v/release/zoilomora/mikrotik2mqtt?style=flat-square)
![GitHub License](https://img.shields.io/github/license/zoilomora/mikrotik2mqtt?style=flat-square)

## TL;DR
Sends structured information from a [MikroTik] router to an [MQTT] Server.

## Usage
Here are some example snippets to help you get started creating a container.

### docker-compose ([recommended](https://docs.docker.com/compose/))
Compatible with docker-compose v2 schemas.

```yaml
version: "2.1"
services:
  mikrotik2mqtt:
    image: zoilomora/mikrotik2mqtt
    container_name: mikrotik2mqtt
    environment:
      - MIKROTIK_HOST=192.168.88.1
      - MIKROTIK_USER=mikrotik2mqtt
      - MIKROTIK_PASSWORD=mikrotik2mqtt
      - MQTT_HOST=192.168.88.2
    restart: unless-stopped
```

### docker cli

```bash
docker run -d \
  --name=mikrotik2mqtt \
  -e MIKROTIK_HOST=192.168.88.1 \
  -e MIKROTIK_USER=mikrotik2mqtt \
  -e MIKROTIK_PASSWORD=mikrotik2mqtt \
  -e MQTT_HOST=192.168.88.2 \
  --restart unless-stopped \
  zoilomora/mikrotik2mqtt
```

## Parameters
Container images are configured using parameters passed at runtime (such as those above).

| Parameter                            | Function                                                       |
| :----------------------------------: | -------------------------------------------------------------- |
| `-e UPDATE_TIME=60`                  | Time interval (seconds) between data collection                |
| `-e MIKROTIK_HOST=192.168.88.1`      | IP Address of your MikroTik router                             |
| `-e MIKROTIK_USE_SSL=0`              | Enable (1) or disable (0) the use of SSL from the MikroTik API |
| `-e MIKROTIK_USER=mikrotik2mqtt`     | User with read permission of his MikroTik                      |
| `-e MIKROTIK_PASSWORD=mikrotik2mqtt` | Password of your user with read permissions of your MikroTik   |
| `-e MQTT_HOST=192.168.88.2`          | IP Address of your MQTT Server                                 |
| `-e MQTT_PORT=1883`                  | Port of your MQTT server                                       |
| `-e MQTT_USE_TLS=0`                  | Enable (1) or disable (0) the use of TLS on your MQTT Server   |
| `-e MQTT_USERNAME=mikrotik2mqtt`     | User of your MQTT Server                                       |
| `-e MQTT_PASSWORD=mikrotik2mqtt`     | Password of your MQTT Server                                   |
| `-e MQTT_CLIENT_ID=mikrotik2mqtt`    | Client identifier for your MQTT Server                         |
| `-e MQTT_TOPIC_BASE=mikrotik2mqtt`   | Base of the topic in which the information will be published   |

## Updating Info
Below are the instructions for updating containers:

### Via Docker Compose
- Update all images: `docker-compose pull`
    - or update a single image: `docker-compose pull mikrotik2mqtt`
- Let compose update all containers as necessary: `docker-compose up -d`
    - or update a single container: `docker-compose up -d mikrotik2mqtt`
- You can also remove the old dangling images: `docker image prune`

### Via Docker Run
- Update the image: `docker pull zoilomora/mikrotik2mqtt`
- Stop the running container: `docker stop mikrotik2mqtt`
- Delete the container: `docker rm mikrotik2mqtt`
- Recreate a new container with the same docker run parameters as instructed above
- You can also remove the old dangling images: `docker image prune`

## Building locally
If you want to make local modifications to these images for development purposes or just to customize the logic:

```bash
git clone https://github.com/zoilomora/mikrotik2mqtt.git
cd mikrotik2mqtt
docker build \
  --no-cache \
  --pull \
  --target production \
  --tag zoilomora/mikrotik2mqtt:latest .
```

## Dependencies
We must give thanks to these dependencies for which this project would not be in operation:
- [EvilFreelancer/routeros-api-php](https://github.com/EvilFreelancer/routeros-api-php)
- [php-mqtt/client](https://github.com/php-mqtt/client)

## License
Licensed under the [Apache-2.0]

Read [LICENSE] for more information

[Apache-2.0]: https://opensource.org/licenses/Apache-2.0
[LICENSE]: LICENSE
[MikroTik]: https://mikrotik.com/
[MQTT]: https://mqtt.org/
