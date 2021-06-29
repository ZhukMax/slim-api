<?php

namespace Zhukmax\Slim\Controllers;

use ORM;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zhukmax\Slim\Models\User;

/**
 * Class UserController
 * @package Zhukmax\Slim\Controllers
 */
class UserController
{
    public function getMax(Request $request, Response $response, $args): Response
    {
        $data = array('name' => 'Max', 'role' => 'web developer');
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function list(Request $request, Response $response, $args): Response
    {
        $e = ORM::forTable(User::TABLE)->find_array();

        $response->getBody()->write(json_encode($e));
        return $response;
    }

    public function getOne(Request $request, Response $response, $args): Response
    {
        $id = (int)$args['id'] ?? 1;
        $user = ORM::forTable(User::TABLE)->findOne($id);

        if (!$user) {
            return self::errorResponse($response, 404, "Error text");
        }

        $response->getBody()->write(json_encode([
            "id" => $user->id,
            "name" => $user->name
        ]));

        return $response;
    }

    public function add(Request $request, Response $response, $args): Response
    {
        $parsedBody = $request->getParsedBody();

        $user = ORM::forTable(User::TABLE)->create();
        $user->name = $parsedBody['name'] ?? '';

        if ($user->save()) {
            $successRes = $response->withStatus(201);
            $successRes->getBody()->write(json_encode([
                "message" => "Success"
            ]));

            return $successRes;
        } else {
            return self::errorResponse($response, 501, "Error text");
        }
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $parsedBody = $request->getParsedBody();
        $id = $parsedBody['id'];
        if (!$id) {
            return self::errorResponse($response, 404, "Error text");
        }

        $user = ORM::forTable(User::TABLE)->findOne($id);
        $user->name = $parsedBody['name'] ?? '';

        if ($user->save()) {
            $response->getBody()->write(json_encode([
                "message" => "Success update"
            ]));

            return $response;
        } else {
            return self::errorResponse($response, 501, "Error text");
        }
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $parsedBody = $request->getParsedBody();
        $id = $parsedBody['id'];
        if (!$id) {
            return self::errorResponse($response, 404, "Error text");
        }

        $user = ORM::forTable(User::TABLE)->findOne($id);
        if ($user->delete()) {
            $response->getBody()->write(json_encode([
                "message" => "Success delete"
            ]));

            return $response;
        } else {
            return self::errorResponse($response, 501, "Error text");
        }
    }

    /**
     * @param Response $response
     * @param int $code
     * @param string $text
     * @return Response
     */
    private static function errorResponse(Response $response, int $code, string $text): Response
    {
        $errorRes = $response->withStatus($code);
        $errorRes->getBody()->write(json_encode([
            "message" => $text
        ]));

        return $errorRes;
    }
}
