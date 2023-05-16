### Tests using postman on docker.

 The tests are written in Postman and run using the Postman command-line tool on docker, Newman.  
```sh
docker run --network command-and-control-php_my-network -v "$(pwd)/postman_collection.json:/etc/newman/postman_collection.json" postman/newman run /etc/newman/postman_collection.json
```
