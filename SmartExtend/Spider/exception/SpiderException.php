<?php
namespace SmartSpider\exception;

class SpiderException extends \Exception {
    

    public function __construct(string $message = "", int $code = 0, Exception $previous = NULL) {
        parent::__construct($message,$code,$previous);
    }
    
    

}
