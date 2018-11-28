<?php
/**
 * Custom exception for MarketSharp API
 *
 * @author Monique <monique.dingding@gmail.com>
 * created Nov 21, 2018
 */
class MarketSharpException extends Exception {
    public function __construct($message = '', $code = 400) {
        parent::__construct($message, $code);
    }
}
?>
