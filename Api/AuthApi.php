<?php

class AuthApi extends Api
{
    public $apiName = 'auth';

    public function loginAction()
    {
        $formData = json_decode(file_get_contents('php://input'), true);
        if (empty($formData['username']) || empty($formData['password'])) {
            return $this->response('Username or password must not be empty', 404);
        } else {
            $username = $formData['username'];
            $hash = User::getPasswordForEmail($username);
            $full_salt = substr($hash['password'], 0, 29);
            $password = crypt($formData['password'], $full_salt);
            $userId = User::checkUser($username, $password);
            $token = User::gen_token();
            if ($userId !== false) {
                $result = User::getUserById($userId);
                $result['token'] = $token;
                return $this->response($result, 200);
            } else {
                return $this->response($userId, 200);
            }
        }
    }

    public function signupAction()
    {
        $formData = json_decode(file_get_contents('php://input'), true);
        if (empty($formData['name']) || empty($formData['email']) || empty($formData['password'])) {
            return $this->response('Username or password must not be empty', 404);
        } else {
            $data['name'] = $formData['name'];
            $data['email'] = $formData['email'];
            $data['password'] = User::hash($formData['password']);
            $id = User::create($data);
            return $this->response($id, 200);
        }
    }
}
