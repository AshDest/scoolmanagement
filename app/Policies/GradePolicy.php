<?php
// Policy: autorise la gestion des notes pour admin/teacher.
namespace App\Policies;

use App\Models\Grade;
use App\Models\User;

class GradePolicy
{
    public function viewAny(User $user): bool { return $user->hasAnyRole(['admin','teacher']); }
    public function view(User $user, Grade $grade): bool { return $user->hasAnyRole(['admin','teacher']); }
    public function create(User $user): bool { return $user->hasAnyRole(['admin','teacher']); }
    public function update(User $user, Grade $grade): bool { return $user->hasAnyRole(['admin','teacher']); }
    public function delete(User $user, Grade $grade): bool { return $user->hasRole('admin'); }
}
