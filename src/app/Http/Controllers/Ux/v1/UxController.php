<?php

namespace App\Http\Controllers\Ux\v1;

use App\Http\Controllers\BaseApiController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class UxController extends BaseApiController
{
    use DispatchesJobs, ValidatesRequests;
}
