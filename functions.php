<?php

function fileEnCode($path, $filename) {
    return rawurlencode($path . '/') . $filename;
}

function getGalleryName($fullpath) {
    return substr($fullpath, 0, stripos(rawurldecode($fullpath), '/'));
}