# Ensure help is shown as the default and fallback target
.DEFAULT_GOAL := help

build:
	docker-compose -f docker-compose.yml down --volumes --rmi all --remove-orphans
	docker-compose -f docker-compose.yml build

up:
	docker-compose -f docker-compose.yml up -d

down:
	docker-compose -f docker-compose.yml down --volumes --rmi all --remove-orphans

stop:
	docker-compose -f docker-compose.yml stop

shell:
	docker exec -it snapp /bin/bash

# Help message
help:
	@echo "Available targets:"
	@echo "  build       Build the environment"
	@echo "  up          Start the environment"
	@echo "  stop       Stop the environment"
	@echo "  down        Stop and remove containers, volumes, images, and orphaned containers"
	@echo "  shell       Open a shell in the environment"

# Fallback for invalid targets
%:
	@echo "Invalid target: $@"
	@echo "Showing help instead..."
	@make help
