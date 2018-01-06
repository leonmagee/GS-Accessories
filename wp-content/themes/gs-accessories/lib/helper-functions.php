<?php
/**
 * HELPER FUNCTIONS
 */

/**
 * Content Excerpt
 *
 * @param $content
 * @param $length
 * @param string $suffix
 *
 * @return string
 */
function content_excerpt( $content, $length, $suffix = '...' ) {
    $strlen = strlen($content);
    //var_dump($strlen);
    //var_dump($length);
    if ( $strlen > $length ) {
        $string = substr( $content, 0, $length );
        $exploded = explode( ' ', $string );
        array_pop( $exploded );
        $implode = implode( ' ', $exploded );
        $final = $implode . $suffix;
        return $final;
    } else {
        return $content;
    }

}