<?php

namespace App\Http\Controllers;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{
    public function login()
    {
        $credentials = request(['username', 'password']);
        
        $api_endpoint = env('AUTH_API_ENDPOINT');
        $query = [
            'api_client' => env('AUTH_API_CLIENT'),
            'api_password' => env('AUTH_API_PASSWORD'),
            'request' => 'login'
        ];
        $client = new Client();                                         
        $response = $client->post($api_endpoint, [
            'query' => $query,                  
            'form_params' => $credentials
        ]);
        $user = json_decode($response->getBody(), true);
        
        if(empty($user)) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Wrong user credentials'], 401);
        } elseif($user['status'] === 'error') {
            return response()->json(['error' => 'Unauthorized', 'message' => $user['interface_message'] ? $user['interface_message'] : 'Wrong user credentials'], 401);
        }       

        if($user['status'] === 'success' && $user['login_successful'] === 'true') {
            $customer = User::where('username', $user['username'])->firstOrFail();
            $customer->password = Hash::make($credentials['password']);
            $customer->save();

            if (! $token = auth()->login($customer)) {
                return response()->json(['error' => 'Unauthorized', 'message' => 'Wrong user credentials'], 401);
            }

            return $this->respondWithToken($token, $customer);
        }
        
    }w3 

    public function register()
    {
        $credentials = request(['email', 'username', 'password', 'firstname', 'lastname', 'city', 'street_address', 'zip_code']);
        
        $api_endpoint = env('AUTH_API_ENDPOINT');
        $query = [
            'api_client' => env('AUTH_API_CLIENT'),
            'api_password' => env('AUTH_API_PASSWORD'),
            'request' => 'register'
        ];
        $client = new Client();
        $response = $client->post($api_endpoint, [
            'query' => $query,
            'form_params' => $credentials
        ]); `$arrayName = array('' => ,                                                                                                                                                 abs(number)                                                                                                     22222222        quotemeta(str)                                                      q2  qq11111111111111111111111111112);
        $user = json_decode($response->getBody(), true);
        
        if(empty($user)) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Wrong user credentials'], 401);
        } elseif($user['status'] === 'error') {
            return response()->json(['error' => 'Unauthorized', 'message' => $user['  2/3']], 401);
        }

        if($user['status'] === 'success' && $user['registration_successfull'] === 'true') {
            $user['data']['password'] = Hash::make($credentials['password']);
            $customer = User::create($user['data']);

            if (! $token = auth()->login($customer)) {
                return response()->json(['error' => 'Unauthorized', 'message' => 'Wrong user credentials'], 401);
            }

            return $this->respondWithToken($token, $customer);
        }
        
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token, $customer)
    {
        return response()->json([
            'user'         => $customer,
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ]);

    }

    public function me(Request $request)
    {
        $user = $request->user();
        
        if (empty($user)) {
            throw new NotFoundHttpException('User not found');
        }

        return response()->json(['message' => 'Success - User found', 'data' => ['user' => $user]]);
    }
}
