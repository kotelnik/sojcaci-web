<?php

class LoginHelper {

    static function redirectToLoginPage() {
        header("location: index.php?menu=" . PageCheck::$PAGE_LOGIN);
        exit();
    }

    static function isLogged() {
        return !empty($_SESSION['prihlasen']) && $_SESSION['prihlasen'] === 1;
    }

}

class ErrorBean {
    private $errorCode;
    private $errorMessage;
    private $statusCode;

    public function __construct($errorCode, $errorMessage, $statusCode) {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->statusCode = $statusCode;
    }

    public function errorCode() {
        return $this->errorCode;
    }
    public function errorMessage() {
        return $this->errorMessage;
    }
    public function statusCode() {
        return $this->statusCode;
    }

    public static $WRONG_PARAMETER;
    public static $WRONG_METHOD;
    public static $NO_FREE_CHALLENGE;
    public static $CANNOT_UPDATE_CHALLENGE;
    public static $CANNOT_UPDATE_PERSON;
}

ErrorBean::$WRONG_PARAMETER = new ErrorBean('ERR_001', 'Wrong parameter', 400);
ErrorBean::$WRONG_METHOD = new ErrorBean('ERR_002', 'Wrong method', 400);
ErrorBean::$NO_FREE_CHALLENGE = new ErrorBean('ERR_003', 'No free challenge', 404);
ErrorBean::$CANNOT_UPDATE_CHALLENGE = new ErrorBean('ERR_004', 'Unable to update chosen challenge', 400);
ErrorBean::$CANNOT_UPDATE_PERSON = new ErrorBean('ERR_005', 'Unable to update person', 400);

class ApiHelper {

    static function allowOnlyGET() {
        ApiHelper::allowOnlyMethod('GET');
    }

    static function allowOnlyPOST() {
        ApiHelper::allowOnlyMethod('POST');
    }

    static function allowOnlyMethod($methodName) {
        if ($_SERVER['REQUEST_METHOD'] !== $methodName) {
            ApiHelper::exitWithError(ErrorBean::$WRONG_METHOD);
        }
    }

    static function performOneOrAll($base_url, $callback) {
        // connect to database
        $connection = Connection::connectForRead();

        // get id parameter
        $sql_where = '';
        $sql_where = $sql_where . ApiHelper::getSqlPartForNumberParameter('id', $connection, $sql_where);

        $one_result = $sql_where != '';

        $sql = 'SELECT id,first_name,last_name,nick_name,is_child FROM web_user';
        if ($one_result) {
            $sql = $sql.' WHERE '.$sql_where;
        }
        error_log($sql);

        $query = mysqli_query($connection, $sql);

        $result = null;
        if ($one_result) {
            $row = mysqli_fetch_array($query);
            if ($row) {
                $item = $callback($row, $connection);
                $result = $item;
            }
        } else {
            $result = array();
            while ($row = mysqli_fetch_array($query)) {
                $item = $callback($row, $connection);
                array_push($result, $item);
            }
        }

        ApiHelper::printJsonResult($result);
    }

    static function loadPersonReduced($id, $connection) {
        if ($id == null) {
            return null;
        }
        $row = mysqli_fetch_array(mysqli_query($connection, 'SELECT id,nick_name,is_child FROM web_user WHERE id='.$id));
        $item = ApiHelper::copyUserReduced($row);
        $item = ApiHelper::loadHasChallenge($row['id'], $connection, $item);
        return $item;
    }

    static function loadPerson($id, $connection) {
        if ($id == null) {
            return null;
        }
        $row = mysqli_fetch_array(mysqli_query($connection, 'SELECT id,first_name,last_name,nick_name,is_child FROM web_user WHERE id='.$id));
        $item = ApiHelper::copyUser($row);
        $item = ApiHelper::loadHasChallenge($row['id'], $connection, $item);
        return $item;
    }

    static function copyIdName($db_row) {
        $copy = array();
        $copy['id'] = (int) $db_row['id'];
        $copy['name'] = $db_row['name'];
        return $copy;
    }

    static function copyUserReduced($db_row) {
        $copy = array();
        $copy['id'] = (int) $db_row['id'];
        $copy['nick_name'] = $db_row['nick_name'];
        $copy['is_child'] = (int) $db_row['is_child'];
        return $copy;
    }

    static function copyUser($db_row) {
        $copy = array();
        $copy['id'] = (int) $db_row['id'];
        $copy['first_name'] = $db_row['first_name'];
        $copy['last_name'] = $db_row['last_name'];
        $copy['nick_name'] = $db_row['nick_name'];
        $copy['is_child'] = (int) $db_row['is_child'];
        return $copy;
    }

    static function copyChallenge($db_row, $connection) {
        $copy = array();
        $copy['id'] = (int) $db_row['id'];
        $copy['creator'] = ApiHelper::loadPersonReduced($db_row['creatorId'], $connection);
        $copy['executer'] = ApiHelper::loadPersonReduced($db_row['executerId'], $connection);
        $copy['title'] = $db_row['title'];
        $copy['description'] = $db_row['description'];
        $copy['created'] = $db_row['created'];
        $copy['started'] = $db_row['started'];
        $copy['finished'] = $db_row['finished'];
        $copy['status'] = ApiHelper::loadStatus($db_row['statusId'], $connection);
        $copy['finishStatus'] = ApiHelper::loadFinishStatus($db_row['finishStatusId'], $connection);
        $copy['score'] = (int) $db_row['score'];
        $copy['durationSec'] = (int) $db_row['durationSec'];
        $copy['difficulty'] = ApiHelper::loadDifficulty($db_row['difficultyId'], $connection);
        if ($db_row['started']) {
            date_default_timezone_set("Europe/Prague");
            $started = strtotime($db_row['started']);
            $duration = (int) $copy['durationSec'];
            $dueTime = date("Y-m-d H:i:s", ($started + $duration));
            $copy['dueTime'] = $dueTime;
        }
        return $copy;
    }

    static function copyChallengeReduced($db_row, $connection) {
        $copy = array();
        $copy['id'] = (int) $db_row['id'];
        $copy['title'] = $db_row['title'];
        $copy['started'] = $db_row['started'];
        $copy['status'] = ApiHelper::loadStatus($db_row['statusId'], $connection);
        $copy['finishStatus'] = ApiHelper::loadFinishStatus($db_row['finishStatusId'], $connection);
        $copy['difficulty'] = ApiHelper::loadDifficulty($db_row['difficultyId'], $connection);
        return $copy;
    }

    static function getNumberParameter($paramName, $isMandatory) {
        if (!isset($_GET[$paramName])) {
            if ($isMandatory) {
                ApiHelper::exitWithError(ErrorBean::$WRONG_PARAMETER);
            } else {
                return '';
            }
        }
        if (!is_numeric($_GET[$paramName])) {
            ApiHelper::exitWithError(ErrorBean::$WRONG_PARAMETER);
        }
        return $_GET[$paramName];
    }

    static function getSqlPartForNumberParameter($paramName, $connection, $previous_sql) {
        $paramValue = ApiHelper::getNumberParameter($paramName, false);
        if ($paramValue == '') {
            return '';
        }
        $prefix = '';
        if (!empty($previous_sql)) {
            $prefix = ' AND';
        }
        $numVal = mysqli_real_escape_string($connection, $paramValue);
        $paramNameGreater = $paramName.'Greater';
        if (isset($_GET[$paramNameGreater]) && $_GET[$paramNameGreater] === 'true') {
            return $prefix.' '.$paramName.' >= '.$numVal;
        }
        if (isset($_GET[$paramNameGreater]) && $_GET[$paramNameGreater] === 'false') {
            return $prefix.' '.$paramName.' <= '.$numVal;
        }
        return $prefix.' '.$paramName.' = '.$numVal;
    }

    static function printJsonResult($result) {
        header('Content-Type: application/json; charset=UTF-8');
        if ($result === null) {
            echo '{}';
        } else if (empty($result)) {
            echo '[]';
        } else {
            echo json_encode($result);
        }
    }

    static function exitWithError($errorObject) {
        http_response_code($errorObject->statusCode());
        $result = array();
        $result['errorCode'] = $errorObject->errorCode();
        $result['errorMessage'] = $errorObject->errorMessage();
        ApiHelper::printJsonResult($result);
        exit();
    }

}

?>
