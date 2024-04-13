<?php declare(strict_types=1);

namespace Jerquin\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SUPER_ADMIN()
 * @method static static SUPER_ADMIN()
 * @method static static STAFF()
 * @method static static USER()
 */
final class Permission extends Enum
{
    public const SUPER_ADMIN = 'super_admin';
    public const ADMIN = 'admin';
    public const STAFF = 'staff';
    public const USER = 'user';
}
