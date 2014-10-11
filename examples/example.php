<?php

/**
 * @group USER OBJECT V1
 *
 * @version V1
 * @created 2014-10-11
 *
 */

// Include the responses and models of this function
require 'model' . SLASH . 'UserModel.php';

/**
 * @description Get all users in database
 * @http_method GET
 * @url     https://{URL}/v1/user/
 * @succes  JSON ValidResponse() { statusCode:(int) result:( array( UserModel() ) ) }
 * @fail    JSON ErrorResponse() { statusCode:(int) errorMessage:{...} }
 * @auth    token
 */
$app->get(
    '/user/',
    function ()  use ($app) {
        // Build response
        $allUsers = new ValidResponse(200, \V1\UserModel::getAll());
        $app->response->setStatus(200);
        $app->response->setBody(json_encode($allUsers, 0, PHP_INT_MAX));
        var_dump(json_last_error_msg());
    }
);

/**
 * @description Post a new user in the database
 * @http_method POST
 * @url     https://{URL}/v1/user/
 * @succes  JSON ValidResponse() { statusCode:(int) result:{ UserModel() } }
 * @fail    JSON ErrorResponse() { statusCode:(int) errorMessage:{...} }
 * @auth    token
 */
$app->post(
    '/user/',
    function ()  use ($app) {
        $newUser = \V1\UserModel::newModel(\V1\UserModel::buildFromArray($_POST));
        $response = (!empty($newUser) && $newUser != new \V1\UserModel()) ? new ValidResponse(200, $newUser) : new ErrorResponse(400, OBJECT_NOT_CREATED);

        // Build response
        $app->response->setStatus($response->statusCode);
        $app->response->setBody(json_encode($response));
    }
);

/**
 * @description Get user in the database on the base of ID
 * @http_method GET
 * @url     https://{URL}/v1/user/:id/
 * @succes  JSON ValidResponse() { statusCode:(int) result:( UserModel() ) }
 * @fail    JSON ErrorResponse() { statusCode:(int) errorMessage:{...} }
 * @auth    token
 */
$app->get(
    '/user/:id/',
    function ($id)  use ($app) {
        $userModel = \V1\UserModel::getFromId($id);
        $response = (!empty($userModel)) ? new ValidResponse(200, $userModel) : new ErrorResponse(404, OBJECT_NOT_FOUND);

        // Build response
        $app->response->setStatus($response->statusCode);
        $app->response->setBody(json_encode($response));
    }
);

/**
 * @description Update a user in the database
 * @http_method POST
 * @url     https://{URL}/v1/user/:id/?q=asdljads&limit=2323
 * @input_var id  optional string [dit is een zoek varaiables]
 * @input_var yolo  optional string [dit is een zoek varaiables]
 * @get_params q [dit is een filter op naam]
 * @get_params j [dit is een filter op naam]
 * @param_limit int default(10)
 * @succes  JSON ValidResponse() { statusCode:(int) result:( UserModel() ) }
 * @fail    JSON ErrorResponse() { statusCode:(int) errorMessage:{...} }
 * @auth    token
 */
$app->post(
    '/user/:id/',
    function ($id)  use ($app) {
        $userToUpdate = \V1\UserModel::getFromId($id);
        $editedUser = \V1\UserModel::updateModel($userToUpdate, $_POST);
        $response = (!empty($editedUser)) ? new ValidResponse(200, $editedUser) : new ErrorResponse(400, OBJECT_NOT_UPDATED);

        // Build response
        $app->response->setStatus($response->statusCode);
        $app->response->setBody(json_encode($response));
    }
);