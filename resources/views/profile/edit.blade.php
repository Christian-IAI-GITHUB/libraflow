<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Mon profil
                </h2>
                <p class="text-gray-600 text-sm mt-1">Gérez vos informations personnelles</p>
            </div>
            <div class="text-right">
                <div class="inline-flex items-center gap-3 bg-slate-100 px-4 py-2 rounded-lg">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                        <div class="text-xs text-gray-600 capitalize">{{ $user->role ?? 'Utilisateur' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Informations de l'utilisateur en résumé -->
            <div class="p-6 sm:p-8 bg-white shadow-sm rounded-lg border-l-4 border-blue-500">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations générales</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Nom</label>
                        <p class="text-lg text-gray-800">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Email</label>
                        <p class="text-lg text-gray-800">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Rôle</label>
                        <p class="text-lg text-gray-800">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                @if($user->role === 'admin')
                                    bg-red-100 text-red-800
                                @elseif($user->role === 'bibliothecaire')
                                    bg-blue-100 text-blue-800
                                @else
                                    bg-green-100 text-green-800
                                @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $user->role ?? 'Lecteur')) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Compte créé</label>
                        <p class="text-lg text-gray-800">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow-sm rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm rounded-lg border-l-4 border-red-500">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
