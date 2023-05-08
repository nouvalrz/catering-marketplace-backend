<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    //public properti
    public $status;
    public $message;
    public $profileCustomer;
    public $profileCatering;
    /**
     * __construct
     *
     * @param mixed $status
     * @param mixed $message
     * @param mixed $resource
     * @return void
     */
    public function __construct($status, $message, $resource, $profileCustomer, $profileCatering)
    {
        parent::__construct($resource);
        $this->profileCustomer =$profileCustomer;
        $this->profileCatering =$profileCatering;
        $this->status = $status;
        $this->message = $message;
    }
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'profileCustomer'=> $this->profileCustomer,
            'profileCatering'=> $this->profileCatering,
            'data' => $this->resource,
        ];
    }

}
