<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jerquin\Database\Repositories\UserRepository;
use Illuminate\Validation\ValidationException;
use Jerquin\Database\Models\User;
use Illuminate\Support\Facades\Hash;
use Jerquin\Http\Requests\UserCreateRequest;
use Jerquin\Http\Requests\UserUpdateRequest;
use Jerquin\Enums\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Jerquin\Http\Requests\ChangePasswordRequest;
use Jerquin\Mail\ContactAdmin;
use Jerquin\Database\Models\Permission as ModelsPermission;
use Jerquin\Exceptions\JerquinException;

class UserController extends CoreController
{
    public $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?   $request->limit : 15;
        return $this->repository->with(['profile', 'examTaken'])->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *Í
     * @param UserCreateRequest $request
     * @return bool[]
     */
    public function store(UserCreateRequest $request)
    {
        return $this->repository->storeUser($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        try {
            $user = $this->repository->with(['profile','company'])->findOrFail($id);
            return $user;
        } catch (Exception $e) {
            throw new JerquinException('NOT_FOUND');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param int $id
     * @return array
     */
    public function update(UserUpdateRequest $request, $id)
    {
        if ($request->user()->hasPermissionTo(Permission::SUPER_ADMIN)) {
            $user = $this->repository->findOrFail($id);
            return $this->repository->updateUser($request, $user);
        } elseif ($request->user()->id == $id) {
            $user = $request->user();
            return $this->repository->updateUser($request, $user);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return array
     */
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new JerquinException('NOT_FOUND');
        }
    }

    public function me(Request $request)
    {
        $user = $request->user();
        // return 'working';
        if (isset($user)) {
            return $this->repository->with(['profile'])->find($user->id);
        }
          return response()->json([
        'error' => 'Unauthorized'
    ], Response::HTTP_UNAUTHORIZED);// throw new JerquinException('NOT_AUTHORIZED');
    }



    public function token(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('is_active', true)->first(); 
        if (!$user || !Hash::check($request->password, $user->password)) {
            return ["token" => null, "permissions" => []];
        }
        return ["token" => $user->createToken('auth_token')->plainTextToken, "permissions" => $user->getPermissionNames()];
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return true;
        }
        return $request->user()->currentAccessToken()->delete();
    }

    public function register(UserCreateRequest $request)
    {
        $permissions = [Permission::USER];
        if (isset($request->permission)) {
            $permissions[] = isset($request->permission->value) ? $request->permission->value : $request->permission;
        }
        $user = $this->repository->create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->givePermissionTo($permissions);

        return ["token" => $user->createToken('auth_token')->plainTextToken, "permissions" => $user->getPermissionNames()];
    }

   
    public function forgetPassword(Request $request)
    {
        $user = $this->repository->findByField('email', $request->email);
        if (count($user) < 1) {
            return ['message' => 'NOT_FOUND', 'success' => false];
        }
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();
        if (!$tokenData) {
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => Str::random(16),
                'created_at' => Carbon::now()
            ]);
            $tokenData = DB::table('password_resets')
                ->where('email', $request->email)->first();
        }

        if ($this->repository->sendResetEmail($request->email, $tokenData->token)) {
            return ['message' => 'MESSAGE.CHECK_INBOX_FOR_PASSWORD_RESET_EMAIL', 'success' => true];
        } else {
            return ['message' => 'MESSAGE.SOMETHING_WENT_WRONG', 'success' => false];
        }
    }
    public function verifyForgetPasswordToken(Request $request)
    {
        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();
        $user = $this->repository->findByField('email', $request->email);
        if (!$tokenData) {
            return ['message' => 'MESSAGE.INVALID_TOKEN', 'success' => false];
        }
        $user = $this->repository->findByField('email', $request->email);
        if (count($user) < 1) {
            return ['message' => 'MESSAGE.NOT_FOUND', 'success' => false];
        }
        return ['message' => 'MESSAGE.TOKEN_IS_VALID', 'success' => true];
    }
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|string',
                'email' => 'email|required',
                'token' => 'required|string'
            ]);

            $user = $this->repository->where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_resets')->where('email', $user->email)->delete();

            return ['message' => 'MESSAGE.PASSWORD_RESET_SUCCESSFUL', 'success' => true];
        } catch (\Exception $th) {
            return ['message' => 'MESSAGE.SOMETHING_WENT_WRONG', 'success' => false];
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = $request->user();
            if (Hash::check($request->oldPassword, $user->password)) {
                $user->password = Hash::make($request->newPassword);
                $user->save();
                return ['message' => 'MESSAGE.PASSWORD_RESET_SUCCESSFUL', 'success' => true];
            } else {
                return ['message' => 'MESSAGE.OLD_PASSWORD_INCORRECT', 'success' => false];
            }
        } catch (\Exception $th) {
            throw new JerquinException('ERROR.SOMETHING_WENT_WRONG');
        }
    }
    public function contactAdmin(Request $request)
    {
        try {
            $details = $request->only('subject', 'name', 'email', 'description');
            Mail::to(config('shop.admin_email'))->send(new ContactAdmin($details));
            return ['message' => 'MESSAGE.EMAIL_SENT_SUCCESSFUL', 'success' => true];
        } catch (\Exception $e) {
            throw new JerquinException('ERROR.SOMETHING_WENT_WRONG');
        }
    }

    public function fetchStaff(Request $request)
    {
        if (!isset($request->shop_id)) {
            throw new JerquinException('ERROR.NOT_AUTHORIZED');
        }
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            return $this->repository->with(['profile'])->where('shop_id', '=', $request->shop_id);
        } else {
            throw new JerquinException('ERROR.NOT_AUTHORIZED');
        }
    }

    public function staffs(Request $request)
    {
        $query = $this->fetchStaff($request);
        $limit = $request->limit ?? 15;
        return $query->paginate($limit);
    }
      // Validate the provider
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['google'])) {
            throw new JerquinException('INVALID_PROVIDER');
        }
    }

    public function socialLoginToken(Request $request)
    {
        $provider = $request->provider;
        $token = $request->access_token;

        try {
            $validated = $this->validateProvider($provider);
            if (!is_null($validated)) {
                return $validated;
            }
        } catch (JerquinException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        try {
            $user = Socialite::driver($provider)->stateless()->userFromToken($token);
        } catch (\Exception $e) {
            return response()->json(['error' => 'INVALID_CREDENTIALS'], 401);
        }

        $userCreated = User::updateOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
            ]
        );



        $avatar = [
            'thumbnail' => $user->getAvatar(),
            'original' => $user->getAvatar(),
        ];
        $userCreated->profile()->updateOrCreate(
            [
                'avatar' => $avatar
            ]
        );

        if (!$userCreated->hasPermissionTo(Permission::STAFF)) {
            $userCreated->givePermissionTo(Permission::STAFF);
        }

        // Return the token and permissions for the user
        return ["token" => $userCreated->createToken('auth_token')->plainTextToken, "permissions" => $userCreated->getPermissionNames()];
    }

    public function socialLogin(Request $request)
    {
        return $this->socialLoginToken($request);
    }


}


