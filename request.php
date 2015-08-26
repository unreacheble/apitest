<?php
/**
 * Created by PhpStorm.
 * User: unreacheble
 * Date: 26.08.15
 * Time: 11:40
 */

if( !empty($_POST) ){
    if ( isset($_POST['url']) && !empty($_POST['url']) ) {
        $url = $_POST['url'];
        $post = false;
        if ( isset($_POST['method']) && $_POST['method'] == 'POST' ) {
            $post = true;
        }
        $params = [];
        if( isset($_POST['params']) && is_array($_POST['params']) ) {
            foreach ( $_POST['params'] as $k => $v ) {
                if ( !empty($v) ) {
                    if ( isset($_POST['values']) && is_array($_POST['values']) ) {
                        $params[$v] = $_POST['values'][$k];
                    } else {
                        $params[$v] = '';
                    }
                }
            }

        }
        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, $post);
            if ( !empty($params) ) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            }
            curl_setopt($curl,CURLOPT_CONNECTTIMEOUT ,3);
            curl_setopt($curl,CURLOPT_TIMEOUT, 20);
            $resp = curl_exec($curl);
            $info = curl_getinfo($curl);
            $error = false;
            if( is_array($info) ){
                if ($info['http_code'] != '200') {
                   $error = 'Bad response';
                }
            }
            $out = [];
            $search = false;
            if ( !$error ) {
                $out['result'] = 'success';
                $resp = json_decode($resp, true);
                if ( isset($_POST['keyToFind']) && $_POST['keyToFind'] != '' ) {
                    $search = true;
                    if ( isset($resp[$_POST['keyToFind']]) ) {
                        if ( isset( $_POST['valToFind'] ) && $_POST['valToFind'] != '') {
                            if( $resp[$_POST['keyToFind']] == $_POST['valToFind'] ){
                                $found = true;
                            } else {
                                $found = false;
                            }
                        } else {
                            $found = true;
                        }
                    } else {
                        $found = false;
                    }
                }

                if( $search ) {
                    $out['search'] = $found;
                }

                $out['response'] = json_encode($resp);
            }else{
                $out['result'] = 'error';
                $out['error'] = $error;
                $out['status'] = $info['http_code'];
            }
            echo json_encode($out);
            curl_close($curl);
        } catch(Exception $e) {
            echo $e->getMessage();
        }

    }
}