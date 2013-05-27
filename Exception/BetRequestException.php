<?php

namespace Qwer\LottoDocumentsBundle\Exception;


class BetRequestException extends \Exception
{
    /**
     * 
     * @param array $violations
     */
    public static function setViolations($violations)
    {
        $message = "";
        foreach($violations as $balls => $viols){
            $message .= "{$balls}: ";
            $errors = array();
            foreach($viols as $violation){
                $errors[] = $violation->getMessage();
            }
            $message .= implode(";", $errors);
            $message .= "\n";
        }
        
        return $message;
        //$this->setMessage($message);
    }
}