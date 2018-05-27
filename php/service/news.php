<?php

ApiHelper::allowOnlyGET();

$sql = 'SELECT id,description,created,visible_until,author_id FROM web_news';
$latestParamIsset = isset($_GET['latest']);
$idParamIsset = isset($_GET['id']);
if ($idParamIsset) {
    $sql = 'SELECT id,description,created,visible_until,author_id FROM web_news';
} else if ($latestParamIsset) {
    $sql = 'SELECT id,description,created,visible_until FROM web_news WHERE visible_until > NOW() ORDER BY created DESC LIMIT 5';
}

$wrapper = function($row, $connection) use($latestParamIsset) {
    $copy = array();
    $copy['id'] = (int) $row['id'];
    $copy['description'] = $row['description'];
    $copy['created'] = $row['created'];
    $copy['visible_until'] = $row['visible_until'];
    if (!$latestParamIsset) {
        $copy['author'] = ApiHelper::loadUserReduced($row['author_id'], $connection);
        return $copy;
    }
    return $copy;
};

ApiHelper::performOneOrAll($sql, $wrapper);

?>
