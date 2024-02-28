<?php


namespace Jerquin\Enums;

use BenSampo\Enum\Enum;

/**
 * Class RoleType
 * @package App\Enums
 */
final class Permission extends Enum
{
    public const SUPER_ADMIN = 'super_admin';
    public const ADMIN = 'admin';
    public const STAFF = 'staff';
}
