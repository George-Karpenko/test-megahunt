<?php

namespace App\Http\Controllers\Swagger\API;

use App\Http\Controllers\Swagger\MainController;

/**
 *  @OA\Post(
 *      path="/api/register",
 *      tags={"Register"},
 *      summary="Register",
 *      operationId="register",
 *      @OA\RequestBody(
 *          @OA\JsonContent(
 *              allOf={
 *                  @OA\Schema(
 *                      @OA\Property(property="last_name", type="string", example="Last name"),
 *                      @OA\Property(property="name", type="string", example="Name"),
 *                      @OA\Property(property="middle_name", type="string", example="Middle name"),
 *                      @OA\Property(property="email", type="string", example="email"),
 *                      @OA\Property(property="password", type="string", example="Password"),
 *                      @OA\Property(property="phone", type="string", example="+79500130189"),
 *                  )
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Success",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated"
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="not found"
 *      ),
 *  )
 **/
class AuthController extends MainController {}
