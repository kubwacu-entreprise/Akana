<?php
    namespace Akana\Exceptions;

    use Exception;

    interface ExceptionInterface{
        public function getMessage();
        public function getCode();
        public function getFile();
        public function getLine();
        public function getTrace();
        public function getTraceAsString();

        public function __toString();
        public function __construct($message=null, $code=0);

        public function getExceptionName();
    }

    abstract class CustomException extends Exception implements ExceptionInterface{
        protected $message;
        private $string;
        protected $code = 0;
        protected $file;
        protected $line;
        protected $trace;
        protected $exception_name = 'CustomException';

        public function __construct($message=null, $code=0){
            parent::__construct($message, $code);
        }

        public function __toString(){
            return get_class($this). "'".$this.message."' in ".$this->file($this->line)."\n".$this->getTraceAsString();
        }

        public function getExceptionName(){
            return $this->exception_name;
        }

        public function setExceptionName($value){
            $this->exception_name = $value;
        }

    }

    class NoRootEndpointException extends CustomException{
        protected $exception_name = 'NoRootEndpointException';
    }

    class EmptyAppResourcesException extends CustomException{
        protected $exception_name = 'EmptyAppResourcesException';
    }

    class ResourceNotFoundException extends CustomException{
        protected $exception_name = 'ResourceNotFoundException';
    }

    class EndpointNotFoundException extends CustomException{
        protected $exception_name = 'EndpointNotFoundException';
    }

    class HttpVerbNotAuthorizedException extends CustomException{
        protected $exception_name = 'HttpVerbNotAuthorizedException';
    }
