<?php
 namespace ChatApp;
 use Ratchet\MessageComponentInterface;
 use Ratchet\ConnectionInterface;
 use ChatApp\Models\Message;

 class Chat implements MessageComponentInterface{
     protected $clients;
     protected $clientId;
     public function __construct()
     {
         $this->clients = new \SplObjectStorage;
         $this->clientId =  array();
     }
     function onOpen(ConnectionInterface $conn)
     {
         $this->clients->attach($conn);
         echo "New connection ".$conn->resourceId;
     }

     function onClose(ConnectionInterface $conn)
     {
        $this->clients->detach($conn);
        echo "Conection $conn->resourceId is disconnected";
     }

     function onError(ConnectionInterface $conn, \Exception $e)
     {
         echo $e->getMessage();
     }

     function onMessage(ConnectionInterface $from, $msg)
     {
         $msg = json_decode($msg);
         switch ($msg->type){
             case 'message':
                 try{
                     $toId = $msg->to;
                     foreach ($this->clients as $client) {
                       if($client->resourceId == $this->clientId[$msg->to]){
                        $meso = '{"text":"'.$msg->text.'","from":"'.$msg->from.'"}';
                        $client->send($meso);
                       }
                     }
                 }catch(Exception $e){
                       echo "logged off";
                 }
                
                
                  Message::create(
                   [ 
                    'body' => $msg->text,
                    'sender' => $msg->sender,
                    'receiver' => $msg->to
                    ]); 
                 break;
              case 'id':
                   $this->clientId[$msg->id] = $from->resourceId;
                   print(" client id = ".$msg->id);
                   break;
                       
         }
        
     }




 }