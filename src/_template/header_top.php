<?
require_once('header_header.php');
header("Content-type:text/html; charset=UTF-8");

$memu_css	= (defined('MENU_CSS'))?	MENU_CSS:'smoothness';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="stylesheet" type="text/css" href="/_css/concerto.css">
<link rel="stylesheet" href="/_js/Jquery/themes/<?= $memu_css; ?>/jquery-ui.css">
