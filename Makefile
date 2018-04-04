.PHONY: up down test test_in_docker

up:
	docker-compose up -d

down:
	docker-compose down

test:
	./vendor/bin/phpunit --colors

test_all_in_docker:
	docker exec -it lookin_php56 bash -c "make test"
	docker exec -it lookin_php70 bash -c "make test"
	docker exec -it lookin_php71 bash -c "make test"
	docker exec -it lookin_php72 bash -c "make test"
