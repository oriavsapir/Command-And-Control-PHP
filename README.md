# Command-And-Control-PHP

## Demo:  
https://user-images.githubusercontent.com/85383966/172116231-e8ba1f94-cb7a-4332-ae61-5125cbf19d5a.mp4

## Getting Started

#### Prerequisites

1. Docker
2. Docker Compose  

If you don't have these tools installed, you can download them from the Docker website: https://www.docker.com/
Running the Application

To run the application, follow these steps:

Clone this repository to your local machine, and run docker compose.:  

```sh
git clone https://github.com/oriavsapir/Command-And-Control-PHP.git
cd Command-And-Control-PHP
docker compose up -d
```
username : admin  
password : 1  

### Tests using postman on docker.

 The tests are written in Postman and run using the Postman command-line tool on docker, Newman.  
```sh
docker run --network command-and-control-php_my-network -v "$(pwd)/tests/postman_collection.json:/etc/newman/postman_collection.json" postman/newman run /etc/newman/postman_collection.json
```


# WorkFlow:  
![workflow](https://user-images.githubusercontent.com/85383966/172119858-380f1671-bc75-440a-900e-869ca8a66b50.png) 



## GUI Preview  

Dashborad Page:  
<img width="953" alt="Dashborad" src="https://user-images.githubusercontent.com/85383966/172116572-b5a9575b-8eba-4ae7-891a-7027b7ad9690.png">


Victim Page:  
<img width="955" alt="victim" src="https://user-images.githubusercontent.com/85383966/172116509-528fc7b6-1a33-402f-976a-2030bfaed6a3.png">

Logs Page:  

<img width="948" alt="log" src="https://user-images.githubusercontent.com/85383966/172116621-74c70b12-9540-4039-87ba-90f5bfc989c9.png">

### Contributing

If you find any issues with this sample application, please open a new issue in the GitHub repository: https://github.com/oriavsapir/Command-And-Control-PHP/issues

If you would like to contribute to the application, please fork the repository and submit a pull request.
### License

This application is licensed under the MIT license. See the LICENSE file for more information.  
# Disclaimer:

The instructions provided here are for informational purposes only. The owner and contributors of this repository are not responsible for any damage or loss caused by the use of these instructions. It is your responsibility to ensure that you understand the risks involved and to take appropriate measures to protect your system. Use at your own risk.
