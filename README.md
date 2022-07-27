notes app


postman collection is added in the project as well. 

`description:`<br /><br />
used eloquent orm<br />
jms serializer <br />
getters/setters <br />
versioning (passed via header)<br /><br />


`initial:`<br /><br />
mkdir -p ~/projects/tokio/db<br />
cd ~/projects/tokio/<br />
git clone https://github.com/sublimited/notes-tokio-345433.git .<br />
cd docker/<br />
<br />

`startup docker:`<br />
local$ cd docker && docker-compose up -d <br />
local$ docker exec -it tokio.app bash<br />
<br /><br />

`install db:`<br />
docker# php /var/www/vhosts/tokio/src/artisan migrate:fresh --seed<br />
<br />
<br />
available endpoints for notes:<br />
<br />
<br />

`list all:`<br />
curl --location --request GET 'http://localhost:8005/ux/notes' --header 'x-app-version: 1.0'<br />
<br /><br />

`get by id:`<br />
curl --location --request GET 'http://localhost:8005/ux/notes/1' --header 'x-app-version: 1.0'<br />
<br /><br />

`get by tag id:`<br />
curl --location --request GET 'http://localhost:8005/ux/notes/get-by-tag/1' --header 'x-app-version: 1.0'<br />
<br /><br />

`create:`<br />
curl --location --request POST 'http://localhost:8005/ux/notes' --header 'x-app-version: 1.0' --header 'Content-Type: application/json' --data-raw '{"name": "2nd record","body": "hello there", "tags": [1,2]}'<br />
<br /><br />

`update:`<br />
curl --location --request PUT 'http://localhost:8005/ux/notes/1' --header 'x-app-version: 1.0' --header 'Content-Type: application/json' --data-raw '{"name": "first record","body": "updating, using put", "tags": [1,2]}'<br />
<br /><br />

`soft delete:`<br />
curl --location --request DELETE 'http://localhost:8005/ux/notes/1' --header 'x-app-version: 1.0'<br />
<br /><br />
<br />
<br />
available endpoints for tags:<br />
<br />
<br />

`list all:`<br />
curl --location --request GET 'http://localhost:8005/ux/tags' --header 'x-app-version: 1.0'<br />
<br /><br />

`get by id:`<br />
curl --location --request GET 'http://localhost:8005/ux/tags/1' --header 'x-app-version: 1.0'<br />
<br /><br />

`create:`<br />
curl --location --request POST 'http://localhost:8005/ux/tags' --header 'x-app-version: 1.0' --header 'Content-Type: application/json' --data-raw '{"name": "tag 1"}'<br />
<br /><br />

`update:`<br />
curl --location --request PUT 'http://localhost:8005/ux/tags/1' --header 'x-app-version: 1.0' --header 'Content-Type: application/json' --data-raw '{"name": "tag 2"}'<br />
<br /><br />

`soft delete:`<br />
curl --location --request DELETE 'http://localhost:8005/ux/tags/1' --header 'x-app-version: 1.0'<br />
<br /><br />



`UI:`<br />
(react+babel+axios standalone)<br />
<br />
> http://localhost:8005/notes.html
