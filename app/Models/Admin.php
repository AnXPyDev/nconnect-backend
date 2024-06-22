<?php

namespace App\Models;

use App\Http\Codes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Eloquent;
use function Laravel\Prompts\password;

/**
 *
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\AdminFactory factory($count = null, $state = [])
 * @method static Builder|Admin newModelQuery()
 * @method static Builder|Admin newQuery()
 * @method static Builder|Admin query()
 * @method static Builder|Admin whereCreatedAt($value)
 * @method static Builder|Admin whereId($value)
 * @method static Builder|Admin whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Admin extends AuthUser
{

    public const PRIVS = [
        'priv-view',
        'priv-edit',
        'priv-super'
    ];

    public const MAXPRIV = 2;

    use HasFactory, HasApiTokens;

    protected $hidden = [
        'password_hash', 'password_salt'
    ];

    static function passwordStrength(string $password) {
        if (strlen($password) < 5) {
            response()->json([
                'code' => Codes::WRONGPASS,
                'message' => "Password must be at least 5 characters long"
            ])->throwResponse();
        }
        return true;
    }

    static function hashPassword(string $password) {
        return Hash::make($password);
    }

    static function register(string $username, string $password, int $priv) {
        return self::factory()->create([
            'username' => $username,
            'password_hash' => self::hashPassword($password),
            'priv' => $priv
        ]);
    }

    static function checkPriv(int $priv) {
        if ($priv < 0 || $priv > self::MAXPRIV) {
            response()->json([
                'code' => Codes::BADINPUT,
                'message' => "Invalid privilege"
            ])->throwResponse();
        }
    }

    function changePassword(string $password) {
        $this->password_hash = self::hashPassword($password);
        $this->save();
    }

    function checkPassword(string $password) {
        return Hash::check($password, $this->password_hash);
    }

    public function tokenWithPriv() {
        return $this->createToken('auth_token',
            ['admin', ...array_slice(self::PRIVS, 0, $this->priv + 1)]
        );
    }
}
