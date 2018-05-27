<?php

ApiHelper::allowOnlyGET();

$sql = 'SELECT id,subject,description,date_from,date_to FROM web_event';
$idParamIsset = isset($_GET['id']);
$futureParamIsset = isset($_GET['future']);
$upcommingParamIsset = isset($_GET['upcomming']);
$pastParamIsset = isset($_GET['past']);
if ($idParamIsset) {
    $sql = 'SELECT id,subject,description,date_from,date_to,author_id FROM web_event';
}

//TODO
//web_event_target_team
//web_event_target_group

$wrapper = function($row, $connection) use($idParamIsset) {
    $copy = array();
    $currentId = (int) $row['id'];
    $copy['id'] = $currentId;
    $copy['subject'] = $row['subject'];
    $copy['description'] = $row['description'];
    $copy['date_from'] = $row['date_from'];
    $copy['date_to'] = $row['date_to'];

    $responsible = array();
    $subQuery = mysqli_query($connection, 'SELECT event_id,user_id FROM web_event_responsible_user WHERE event_id='.$currentId);
    while ($subRow = mysqli_fetch_array($subQuery)) {
        $item = array();
        $item['type'] = 'user';
        $item['other'] = null;
        $item['user'] = ApiHelper::loadUserReduced($subRow['user_id'], $connection);
        $item['team'] = null;
        array_push($responsible, $item);
    }
    $subQuery = mysqli_query($connection, 'SELECT event_id,team_id FROM web_event_responsible_team WHERE event_id='.$currentId);
    while ($subRow = mysqli_fetch_array($subQuery)) {
        $item = array();
        $item['type'] = 'team';
        $item['other'] = null;
        $item['user'] = null;
        $subSubRow = mysqli_fetch_array(mysqli_query($connection, 'SELECT id,name FROM web_team WHERE id='.$subRow['team_id']));
        $item['team'] = ApiHelper::copyIdName($subSubRow);
        array_push($responsible, $item);
    }
    if (!empty($row['responsible_other'])) {
        $item = array();
        $item['type'] = 'other';
        $item['other'] = $row['responsible_other'];
        $item['user'] = null;
        $item['team'] = null;
        array_push($responsible, $item);
    }
    $copy['responsible'] = $responsible;

    if ($idParamIsset) {
        $copy['author'] = ApiHelper::loadUserReduced($row['author_id'], $connection);
        return $copy;
    }
    return $copy;
};

ApiHelper::performOneOrAll($sql, $wrapper);

?>
