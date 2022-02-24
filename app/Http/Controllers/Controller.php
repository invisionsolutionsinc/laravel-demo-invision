<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $result;
    protected $_model, $_storageDir;
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function successResponse($result = [], $message,$code=200,$paginate = FALSE){

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
    public function errorResponse($error, $code = 401,$errorMessages = []){

        $statusCode = $code == 0 ? 402 : $code;
        $response = [
            'success' => false,
            'status_code'    =>$statusCode,
            'message' => is_array($error) == TRUE ? $error : [$error],
            'data'    => $errorMessages
        ];


        return response()->json($response, $statusCode);
    }

    public function uploadFile(UploadedFile $file,$path,$folder = NULL){

        $val = '';
        if($file){
            $extension = $file->getClientOriginalExtension();
            $imageName = uniqid().'_'.time().'.'.$extension;
            $folder = $folder == NULL ? 'misc' : $folder;

            $val = Storage::disk($path)->putFile($folder,$file);
            //$path = Storage::url($resp);
            if($val){
                $val = Storage::disk($path)->url($val);
            }
        }

        return $val;
    }


    public function uploadFileOld(UploadedFile $file,$folder = NULL){

        $folder = $folder == NULL ? 'misc' : $folder;

        try{
            $resp = Storage::putFile($folder,$file,'public');
            $path = Storage::url($resp);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        return $path;
    }

    public function saveBase64Image($file,$path,$folder){
        $val = '';
        if(!empty($file)){

            $pos  = strpos($file, ';');
            $expl = explode('/', substr($file, 0, $pos));
            $extension = (isset($expl[1]))?$expl[1]:'png';

            $value = substr($file, strpos($file, ',') + 1);
            $value = base64_decode($value);
            $imageName = uniqid().'_'.time().'.'.$extension;

            $val = Storage::disk($path)->put($folder.'/'.$imageName, $value);

            if($val){
                $val = Storage::disk($path)->url($folder.'/'.$imageName);
            }
        }
        return $val;
    }

    public function saveBase64ImageOLd($file,$path,$folder){
        $val = '';
        if(!empty($file)){

            $pos  = strpos($file, ';');
            $expl = explode('/', substr($file, 0, $pos));
            $extension = (isset($expl[1]))?$expl[1]:'png';

            $value = substr($file, strpos($file, ',') + 1);
            $value = base64_decode($value);
            $imageName = uniqid().'_'.time().'.'.$extension;

            $val = Storage::disk($path)->put($folder.'/'.$imageName, $value);
            if($val){
                $val = '/storage/'.$folder.'/'.$imageName;
            }
        }
        return $val;
    }


    public function paginate($data = []){

        $paginationArray = NULL;
        if($data != NULL){

            // if($data->hasPages()){

            $items = array_values($data->items());


            $paginationArray = array ('list'=>$items,'pagination'=>[]);
            $paginationArray['pagination']['total'] = $data->total();
            $paginationArray['pagination']['current'] = $data->currentPage();
            $paginationArray['pagination']['first'] = 1;
            $paginationArray['pagination']['last'] = $data->lastPage();

            if($data->hasMorePages()) {
                if ( $data->currentPage() == 1) {
                    $paginationArray['pagination']['previous'] = 0;
                } else {
                    $paginationArray['pagination']['previous'] = $data->currentPage()-1;
                }
                $paginationArray['pagination']['next'] = $data->currentPage()+1;
            }
            else {
                $paginationArray['pagination']['previous'] = $data->currentPage()-1;
                $paginationArray['pagination']['next'] =  $data->lastPage();
            }
            if ($data->lastPage() > 1) {
                $paginationArray['pagination']['pages'] = range(1,$data->lastPage());
            }
            else {
                $paginationArray['pagination']['pages'] = [1];
            }
            $paginationArray['pagination']['from'] = $data->firstItem();
            $paginationArray['pagination']['to'] = $data->lastItem();
            //$paginationArray;
            //
            return $this->result = $paginationArray;

            /// }else {
            //     $paginationArray = $data;
            //     return $this->result = $paginationArray;
            // }
        }
        //return $paginationArray;
    }


    public function delete(Request $request,$id){

        try{
            $obj = $this->_model::find($id)->delete();

            return $this->successResponse($obj,'success');

        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }

    }
}
