<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResourceAdmin extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    //public properti
    public $status;
    public $message;
    public $link;

    public function __construct($status, $message, $resource, $link)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
        $this->link = $link;
    }

    public function toArray($request)
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'link' => $this->link,
            'data' => $this->resource,
            ];
           
    }
}
