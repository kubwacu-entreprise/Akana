<?php
    namespace Akana\Controller;

    use Exception;
    use Akana\Exceptions\NoRootEndpointException;
    use Akana\Exceptions\HttpVerbNotAuthorizedException;
    use Akana\Utils\Response;

    class Controller{
        // this method help to run the request
        static function execute(string $uri): Response{
            $resource = '';
            $endpoint = '';
            
            // --- if the uri is pointed to root endpoint '/' ---
            if($uri == '/'){

                // --- check if the root endpoint is set in 'config.php' ---
                if(ROOT_ENDPOINT == true && ROOT_ENDPOINT_CONTROLLER != NULL){
                    $root_endpoint_controller_path =  ROOT_ENDPOINT_CONTROLLER[0];
                    $root_endpoint_controller_class_namespace = ROOT_ENDPOINT_CONTROLLER[1];
                    
                    // --- import file of root endpoint controller ---
                    require $root_endpoint_controller_path;
                    
                    // --- check if there is a method with 'http_verb' in root enpoint controller ---
                    if(method_exists($root_endpoint_controller_class_namespace, HTTP_VERB)){
                        
                        // --- execute method with verb name in root endpoint controller ---
                        return call_user_func(array($root_endpoint_controller_class_namespace, HTTP_VERB));
                    }
                    // -- throw HttpVerbNotAuthorizedException if there is not method with 'http_verb' in root 
                    // enpoint controller ---
                    else{
                        throw new HttpVerbNotAuthorizedException();
                    }
                    
                }
                else{
                    throw new NoRootEndPointException();
                }
            }

            // --- if the uri do not pointed to root endpoint ---
            else{

                // --- check if there is not resouce in APP_RESOURCES array set in config.php ---
                if(count(APP_RESOURCES) == 0){
                    echo "throw exception empty app_resouces";
                }
                else{
                    $resource = self::get_resource($uri);
                    
                    // --- check if the given resource exist in APP_RESOURCES array set in config.php ---
                    if(self::resource_exist($resource)){

                        // --- remove resource in the uri and get only endpoint ---
                        $endpoint = self::get_endpoint($resource, $uri);
                        
                        // --- check if the given endpoint exist in RESOURCE_FOLDER/endpoints.php ---
                        if(self::endpoint_exist($resource, $endpoint)){
                            echo "on execute le uri";
                        }
                        else{
                            echo "throw exception endpoint not found in specific resource";  
                        }

                    }
                    else{
                        echo "la resource n'existe pas";
                    }
                }
            }
        }

        static function get_resource(string $uri): string{
            return explode('/', $uri)[0];
        }

        static function get_endpoint(string $resource, string $uri): string{
            $uri_in_part = explode('/', $uri);
            $endpoint = '';

            foreach($uri as $value){
                if($value != $resource){
                    $endpoint += $value . '/';
                }
            }
        }

        static function resource_exist(string $resource_name): bool{
            foreach(APP_RESOURCES as $value){
                if($value == $resource_name){
                    return true;
                }
            }
            return false;
        }

        static function endpoint_exist(string $resource, string $endpoint):bool{
            return false;
        }

        // change type as json and response code (by default: 200)
        static function set_content_to_json(int $status = 200){
            http_response_code($status);

            header('Content-Type: application/json');
        }


    }
