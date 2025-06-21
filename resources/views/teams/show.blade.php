@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Team Settings</h1>
        <p class="text-gray-600 mt-1">Manage your team details, members, and permissions.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 space-y-10">
        @livewire('teams.update-team-name-form', ['team' => $team])

        @livewire('teams.team-member-manager', ['team' => $team])

        @if (Gate::check('delete', $team) && ! $team->personal_team)
            <x-section-border />
            @livewire('teams.delete-team-form', ['team' => $team])
        @endif
    </div>
</div>
@endsection
