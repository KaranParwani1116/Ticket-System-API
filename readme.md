# TICKET BOOKING REST API

Ticket booking rest api is based on the idea how tickets get booked on real life. It is based on PHP's Slim microframework and My Sql. It Consist of endpoints to book a ticket, fetch user details on the basis of ticket id, fetch tickets on the basis of timings, delete a particular ticket, updating ticket time e.t.c.</br>
It consist of a magic feature of deleting tickets automatically which got expired. This is achieved through My Sql events.

Installation <a name="installation"></a>
------------

1. [PHP 5.4 or higher](http://www.php.net/downloads.php) is required to use it.
2. [Slim framework 4.0 or higher](http://www.slimframework.com/docs/v4/start/installation.html) is required to use it.
3. [Xampp for apache server and my sql](https://www.apachefriends.org/download.html) is required to use it.

Installation of slim is recommended to be done via [composer](https://getcomposer.org/download/) by running:

	composer require slim/slim:"4.*"

Installation of slim/PSR-7 also recommended to be done via [composer](https://getcomposer.org/download/) by running:

    composer require slim/psr7


## Endpoints

Book Tickets:

    $app->post('/booktickets', function($request, $response, $args) {
       
        //ticket count function to check number of tickets booked at particular time doesn't exceed 20.

        if(ticketCount($response, $requestData['timing']) > 20) {

        }

        //inserting the booked ticket in database

        $query = $pdo->prepare("INSERT INTO `tickets` (`name`, `age`, `gender`, `number`, `t_id`, `timing`) 
                           VALUES(:name, :age, :gender, :number, :t_id, :timing); ");

    }

Get user details by ticket id:

     $app->get('/getDetailById', function($request, $response, $args) {

         //query to fetch ticket based on id
         $query = $pdo->prepare("SELECT * FROM `tickets` WHERE `t_id`=:ticketId");

     }

Get set of tickets on particular timing:

    $app->get('/getDetailByDate', function($request, $response, $args) {
        
        //sql query to fetch set of tickets on particular timing
        $query = $pdo->prepare("SELECT * FROM `tickets` WHERE `timing`=:timings");
    }

Update ticket timing:

    $app->post('/updatetiming', function($request, $response, $args) {
        //sql query to update tickets timing
        $query = $pdo->prepare("UPDATE `tickets` SET `timing` = :timing WHERE `t_id` = :t_id");
    }

Deleting a particular ticket:

    $app->delete('/deleteById/[{t_id}]', function($request, $response, $args) {
        //query to delete a particular ticket.

        $ticketId = $args['t_id'];

        $query = $pdo->prepare("DELETE FROM `tickets` WHERE `t_id` = :t_id");
    }


## Automatic Expiration and Deletion of tickets

[Sql Events](https://www.mysqltutorial.org/mysql-triggers/working-mysql-scheduled-event/) are tasks that execute according to specified schedule. Therefore, sometimes MySQL events are referred to as scheduled events.</br> MySQL Events are named object which contains one or more SQL statement. They are stored in the database and executed at one or more intervals.

### Event Query

    CREATE EVENT e_store_ts
    ON SCHEDULE
      EVERY 60 SECOND
    DO
      DELETE FROM tickets WHERE TIMESTAMPDIFF(HOUR, timing, NOW()) > 8      

## Authors

* **Karan Parwani** - *Initial work* - [Repo](https://github.com/KaranParwani1116?tab=repositories)


## Security

If you discover any security related issues, please email karanparwani.parwani102@gmail.com instead of using the issue tracker.    

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details