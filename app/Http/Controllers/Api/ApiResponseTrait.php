<?php
namespace App\Http\Controllers\Api;


 trait  ApiResponseTrait{

    #-------- شكل الResponse
    /*
     * [
         * data =>
         * status =>true or false
         * error =>''
     *
     * ]
         */

    public $paginateNumber = 10 ;

#-------- Function return code and data and error  use in api     -----------

    public function apiResponse($data = null , $error = null , $code = 200){

         $array =
             [
               'data' => $data,
               'status' => in_array($code,$this->SuccessCode()) == 200 ? true : false ,
               'error' => $error,
             ];

         return response($array,$code);
     }
    #-------- Function Code Successfully use in api     -----------
      public function  SuccessCode(){

         return [
           200,201,202
         ];
      }

    #-------- Function return Message Not Fount All Place    -----------

      public function  NotFountResponse()
      {
          return $this->apiResponse(null,'',404);

      }

      # -------------- Unknown Error -------------

      public function UnknownError()
    {
        return $this->apiResponse(null,'',520);
    }

      #---------- Validation Api use in public -------------

      public function ApiValidation($request , $array)
    {
        $validate = \Validator::make($request->all(),$array);

        if($validate->fails())
        {
            return $this->apiResponse(null,$validate->errors(),422);
        }

    }

    #---------------Create Successfully any action ----------
       public function CreatedResponse($data)
       {
           return $this->apiResponse($data ,null , 201 );
       }

#---------------Delete Successfully any action ----------
    public function DeleteResponse()
    {
        return $this->apiResponse(true ,null , 200 );
    }




}