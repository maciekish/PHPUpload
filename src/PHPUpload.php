<?php

class PHPUpload {
    static function getLimit() {
        $upload_max_filesize = PHPUpload::normalize(ini_get('upload_max_filesize'));
        $post_max_size = (ini_get('post_max_size') == 0) ? function(){throw new Exception('Check post_max_size in php.ini');} : PHPUpload::normalize(ini_get('post_max_size'));
        $memory_limit = (ini_get('memory_limit') == -1) ? $post_max_size : PHPUpload::normalize(ini_get('memory_limit'));

        if ($memory_limit < $post_max_size || $memory_limit < $upload_max_filesize)
            return $memory_limit;

        if ($post_max_size < $upload_max_filesize)
            return $post_max_size;

        $maxUploadSize = min($upload_max_filesize, $post_max_size, $memory_limit);
            return $maxUploadSize;
    }

    static function normalize($size) {
        if (preg_match('/^([\d\.]+)([KMG])$/i', $size, $match)) {
            $pos = array_search($match[2], array('K', 'M', 'G'));
            if ($pos !== false) {
                $size = $match[1] * pow(1024, $pos + 1);
            }
        }
        return $size;
    }
}

?>