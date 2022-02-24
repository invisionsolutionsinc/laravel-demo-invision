<?php

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
     function successResponse($result = [], $message,$code=200,$paginate = FALSE){

        $this->result = $result;
        if($paginate == TRUE){
            $this->paginate($result);
        }

        $response = [
            'success' => true,
            'status_code'    =>$code,
            'message' => [$message],
            'data'   => $this->result
        ];

        return response()->json($response, 200);
    }


    /** 
    * return error response.
    *
    * @return \Illuminate\Http\Response
    */
    function errorResponse($error, $code = 401,$errorMessages = []){

       $statusCode = $code == 0 ? 402 : $code;
       $response = [
           'success' => false,
           'status_code'    =>$statusCode,
           'message' => is_array($error) == TRUE ? $error : [$error],
           'data'    => $errorMessages
       ];


       return response()->json($response, $statusCode);
   }
