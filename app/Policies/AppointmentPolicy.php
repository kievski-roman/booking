<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    private function isClient(User $user): bool
    {
        return $user->role?->slug === 'client';
    }

    private function isMaster(User $user): bool
    {
        return $user->role?->slug === 'master';
    }

    private function isAdmin(User $user): bool
    {
        return $user->role?->slug === 'admin';
    }
    public function viewAny(User $user): bool
    {
        return $this->isClient($user) || $this->isMaster($user) || $this->isAdmin($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        if ($this->isAdmin($user)) return true;

        if ($this->isClient($user)) {
            return $appointment->client_id === $user->id;
        }

        if ($this->isMaster($user)) {
            $masterId = optional($user->master)->id;
            return $masterId && $appointment->master_id === $masterId;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->isClient($user) || $this->isAdmin($user);
    }

    public function confirm(User $user, Appointment $a): bool
    {
        if ($this->isAdmin($user)) return true;

        if ($this->isMaster($user)) {
            $masterId = optional($user->master)->id;
            return $masterId && $a->master_id === $masterId;
        }

        return false;
    }
    public function updateStatus(User $user, Appointment $a): bool
    {
        return $this->isAdmin($user);
    }
    public function delete(User $user, Appointment $a): bool
    {
        return $this->isAdmin($user);
    }
    public function cancel(User $user, Appointment $appointment): bool
    {
        if ($this->isAdmin($user)) return true;

        if ($this->isClient($user) && $appointment->client_id === $user->id) {
            return true;
        }

        if ($this->isMaster($user)) {
            $masterId = $user->master?->id;
            return $masterId && $appointment->master_id === $masterId;
        }

        return false;
    }

}
