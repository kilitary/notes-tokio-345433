<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JMS\Serializer\Annotation as Serializer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notes extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'body',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {

    }

    /**
     * @Serializer\SerializedName("id")
     * @Serializer\Type("integer")
     * @Serializer\Groups({"api", "default"})
     * @Serializer\VirtualProperty()
     * @Serializer\Expose()
     * @return mixed
     * @var string
     */
    public function getId()
    {
        return $this::__get('id');
    }

    /**
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     * @Serializer\Groups({"api", "default"})
     * @Serializer\VirtualProperty()
     * @Serializer\Expose()
     * @return mixed
     * @var string
     */
    public function getName()
    {
        return $this::__get('name');
    }

    /**
     * @Serializer\SerializedName("body")
     * @Serializer\Type("string")
     * @Serializer\Groups({"api", "default"})
     * @Serializer\VirtualProperty()
     * @Serializer\Expose()
     * @return mixed
     * @var string
     */
    public function getBody()
    {
        return $this::__get('body');
    }

    /**
     * @Serializer\SerializedName("created_at")
     * @Serializer\Type("string")
     * @Serializer\Groups({"api", "default"})
     * @Serializer\VirtualProperty()
     * @Serializer\Expose()
     * @return mixed
     * @var string
     */
    public function getCreatedAt()
    {
        return $this::__get('created_at');
    }

}
