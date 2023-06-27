deploy:
	ansible-playbook ansible/release.yml -i ansible/inventory.yml -vv --extra-vars "version=v1.0.5" --ask-vault-pass -u root

start:
	docker-compose up

build:
	docker-compose -f docker-compose.yml build app

push:
	docker-compose -f docker-compose.yml push app