<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Api\ApiResponseTrait;

/**
 * Class BaseController.
 *
 * @package App\Http\Controllers\Api
 */
abstract class BaseController extends Controller
{
    use ApiResponseTrait;
}
