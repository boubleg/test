<?php

$conn = new \mysqli('127.0.0.1', 'root', 'root', 'test');
const VENDOR_TABLE = 'vendor';
const VENDOR_FIELD_ID = 'id';
const VENDOR_FIELD_NAME = 'name';
$sql = 'SELECT * FROM ' . VENDOR_TABLE . ' WHERE ' . VENDOR_FIELD_ID . ' = ' . 1;
$result = $conn->query($sql)->fetch_all();

var_dump($result);die;