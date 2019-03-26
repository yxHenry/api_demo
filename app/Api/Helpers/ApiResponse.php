<?php
namespace App\Api\Helpers;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Response;

trait ApiResponse
{
    protected $statusCode = FoundationResponse::HTTP_OK;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode,$httpCode=null)
    {
        $httpCode = $httpCode ?? $statusCode;
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respond($data,$header = [])
    {
        return Response::json($data,$this->getStatusCode(),$header);
    }

    public function status($status,array $data,$code=null)
    {
        if($code){
            $this->setStatusCode($code);
        }
        $status = [
            'status'=>$status,
            'code'=>$this->statusCode
        ];

        $data = array_merge($status,$data);
        return $this->respond($data);
    }

    public function failed($message, $code = FoundationResponse::HTTP_BAD_REQUEST,$status = 'error'){

        return $this->setStatusCode($code)->message($message,$status);
    }

    /**
     * @param $message
     * @param string $status
     * @return mixed
     */
    public function message($message, $status = "success"){

        return $this->status($status,[
            'message' => $message
        ]);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function internalError($message = "Internal Error!"){

        return $this->failed($message,FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function created($message = "created")
    {
        return $this->setStatusCode(FoundationResponse::HTTP_CREATED)
            ->message($message);

    }

    /**
     * @param $data
     * @param string $status
     * @return mixed
     */
    public function success($data, $status = "success"){

        return $this->status($status,compact('data'));
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFond($message = 'Not Fond!')
    {
        return $this->failed($message,Foundationresponse::HTTP_NOT_FOUND);
    }
}