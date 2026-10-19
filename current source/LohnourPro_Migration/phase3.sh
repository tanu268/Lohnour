#!/bin/bash
set -e

echo '=== PRE-FLIGHT ==='
hostname; whoami; nproc; free -h; df -h /
sudo systemctl is-active nginx
sudo systemctl is-active mysql
sudo systemctl is-active php8.1-fpm
sudo ss -lntp | grep -E ':(22|80|3306)' || true

echo '=== DOCKER INSTALL ==='
sudo apt-get update
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --batch --yes --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

echo '=== DOCKER VERIFY ==='
docker --version
docker compose version
sudo systemctl is-active docker
sudo systemctl is-enabled docker

echo '=== DOCKER TEST ==='
sudo docker run --rm hello-world

echo '=== PORTAINER INSTALL ==='
sudo docker volume create portainer_data || true
# Stop and remove existing container if it exists (safety net)
sudo docker rm -f portainer 2>/dev/null || true
sudo docker run -d -p 9443:9443 --name portainer --restart=unless-stopped -v /var/run/docker.sock:/var/run/docker.sock -v portainer_data:/data portainer/portainer-ce:latest

echo '=== POST-FLIGHT ==='
sudo docker ps
sudo docker volume inspect portainer_data
sudo ufw status verbose
free -h
df -h
sudo docker system df
sudo systemctl is-active nginx
sudo systemctl is-active mysql
sudo ss -lntp | grep -E ':(80|3306|9443)' || true
