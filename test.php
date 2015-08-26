<?php
/**
 * Created by PhpStorm.
 * User: unreacheble
 * Date: 26.08.15
 * Time: 12:44
 */

$res = ['asd'=>'sss','2'=>3];
if( !empty($_POST) ){
    $res= array_merge($res, $_POST);
}
echo json_encode($res);