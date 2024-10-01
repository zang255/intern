<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tenSach' => $this->title,
            'tacGia' => $this->author,
            'NamPhatHanh' => $this->published_year,
            'maSach' => $this->code,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];
    }
}