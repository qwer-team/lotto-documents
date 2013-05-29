<?php

namespace Qwer\LottoDocumentsBundle\Exception;

class BetRequestException extends \Exception
{

    private $violations;
    private $errorMessage;
    private $ballsLabel;

    public function __construct($message, $violations, $ballsMessage)
    {
        $this->ballsLabel = $ballsMessage;
        $this->setViolations($violations);
        parent::__construct($message);
    }

    public function setViolations($violations)
    {
        $message = "";
        foreach ($violations as $balls => $viols) {
            $message .= $this->ballsLabel." {$balls}: ";
            $errors = array();
            foreach ($viols as $violation) {
                $errors[] = $violation->getMessage();
            }
            $message .= array_shift($errors);
            /*$message .= implode(";", $errors);
            $message .= "\n";*/
        }
        $this->errorMessage = $message;
        return $message;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getViolations()
    {
        return $this->violations;
    }

}