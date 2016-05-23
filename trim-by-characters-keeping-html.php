<?php
/**
 * Trims a string to no longer than the specified number of characters,
 * gracefully stopping at white spaces and closing any allowed html tags.
 *
 * @param string $text The string of words to be trimmed.
 * @param int $length Maximum number of characters; defaults to 45.
 * @param string $append String to append to end, when trimmed; defaults to ellipsis.
 * @param string $allowable_tags String of html tags that should not be stripped. Default: '<b><em><a>'
 * @return String of words capped at specified length with tags balanced.
 */
function me_trim_by_characters_with_html( $text, $length = 45, $append = '&hellip;', $allowable_tags = '<b><em><a>' ) {
    $length = (int) $length;
    $text = trim( strip_tags( $text, $allowable_tags ) );
    // if the length without tags is less than our target we are done
    if ( strlen( strip_tags( $text ) ) < $length )
        return $text;
    // count forward to find the $length character in unstripped $text not counting tags
    for ($i = 0, $j = 0, $l = strlen( $text ), $in_tag = false; $i < $l && ( $in_tag || $j < $length ); $i++) :
        switch ( $text[$i] ) :
            case '<': $in_tag = true; break;
            case '>': $in_tag = false; break;
            default :
                if ( ! $in_tag ) $j++;
        endswitch;
    endfor;
    // Step forward one and check for whitespace. If none, go back and find the last place we ended a word or html tag
    if ( isset( $text[$i++] ) )
        while ( ' ' != $text[$i] && '&nbsp;' != $text[$i] && '>' != $text[$i - 1] ) $i--;

    return balanceTags( substr( $text, 0, $i ), true ) . $append;
}
