<?php
class MyDB extends SQLite3
{
        function __construct()
        {
                $this->open('elbook.db');
                $this->exec("CREATE TABLE IF NOT EXISTS comments (id INTEGER PRIMARY KEY, date TEXT, name TEXT, message TEXT);"); 
        }
}
function addComment($db, $name, $message){
  $date = time();
  $query_insert = $db->exec("INSERT INTO comments(name, date, message) VALUES ('$name', '$date', '$message');"); 
  if (!$query_insert) return false;
}
function deleteComment($db, $id){
  $db->exec("DELETE FROM comments WHERE id=$id;");
}
function openComments($db){ 
  $res = $db->query("SELECT * FROM comments ORDER BY date DESC;");
  $comments = '';
  while ($comment = $res->fetchArray()) 
  {  
  	$comments .= '<div class="row comment align-items-center" data-id="'.$comment['id'].'">
			 		<div class="col commHead row align-items-center">
            <div class="commAvatar col-6 col-xl-2">
			 			 <img src="img/deadpool.png" >
             </div>
			 			<div class="loginComm col-6 col-xl-10">'.$comment['name'].'<br/><span>'.showDate($comment['date']).'</div>
			 		</div>
		 			<div class="w-100"></div>
		 			<div class="col">
		 				<p>'.$comment['message'].'</p>
		 			</div>
		 			<a href="#" class="commDelete" onclick="deleteComments('.$comment["id"].');return false;"><img src="img/delete.png" /></a>
	 			</div>';
  }
  if($comments != ''){
  	echo '<div class="row commsHead">
			 		<div class="col">
			 			Записи на вашей стене
			 		</div>
			 	</div>'.$comments;
  }
  else{
  	echo '<div class="row commsHead">
			 		<div class="col">
			 			Комментариев пока нет...
			 		</div>
			 	</div';
  }
}
function showDate( $date )
{
    $stf      = 0;
    $cur_time = time();
    $diff     = $cur_time - $date;
 
    $seconds = array( 'секунда', 'секунды', 'секунд' );
    $minutes = array( 'минута', 'минуты', 'минут' );
    $hours   = array( 'час', 'часа', 'часов' );
    $days    = array( 'день', 'дня', 'дней' );
    $weeks   = array( 'неделя', 'недели', 'недель' );
    $months  = array( 'месяц', 'месяца', 'месяцев' );
    $years   = array( 'год', 'года', 'лет' );
    $decades = array( 'десятилетие', 'десятилетия', 'десятилетий' );
 
    $phrase = array( $seconds, $minutes, $hours, $days, $weeks, $months, $years, $decades );
    $length = array( 1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600 );
 
    for ( $i = sizeof( $length ) - 1; ( $i >= 0 ) && ( ( $no = $diff / $length[ $i ] ) <= 1 ); $i -- ) {
        ;
    }
    if ( $i < 0 ) {
        $i = 0;
    }
    $_time = $cur_time - ( $diff % $length[ $i ] );
    $no    = floor( $no );
    $value = sprintf( "%d %s ", $no, getPhrase( $no, $phrase[ $i ] ) );
 
    if ( ( $stf == 1 ) && ( $i >= 1 ) && ( ( $cur_time - $_time ) > 0 ) ) {
        $value .= time_ago( $_time );
    }
 
    return $value . ' назад';
}
 
function getPhrase( $number, $titles ) {
    $cases = array( 2, 0, 1, 1, 1, 2 );
 
    return $titles[ ( $number % 100 > 4 && $number % 100 < 20 ) ? 2 : $cases[ min( $number % 10, 5 ) ] ];
}

$db = new MyDB();
if($_GET['action']=='open'){
	openComments($db);
}
elseif($_GET['action']=='add'){
	addComment($db, $_GET['name'], $_GET['message']);
}
elseif($_GET['action']=='delete'){
	deleteComment($db, $_GET['id']);
}
?>