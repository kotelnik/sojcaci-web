<?php

ApiHelper::allowOnlyGET();

$sql = 'SELECT id,nick_name,is_child FROM web_user';
$idParamIsset = isset($_GET['id']);
if ($idParamIsset) {
    $sql = 'SELECT id,first_name,last_name,nick_name,email,email_notifications_enabled,last_login,notes,is_child FROM web_user';
}

$wrapper = function($row, $connection) use($idParamIsset) {
    if ($idParamIsset) {
        return ApiHelper::copyUser($row);
    }
    return ApiHelper::copyUserReduced($row);
};

ApiHelper::performOneOrAll($sql, $wrapper);

?>