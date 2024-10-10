<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="rounded-md py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">Register</a>
        @endif
    </div>

    <form action="{{ route('search.status') }}" method="GET">
        <div>
            <x-input-label for="nid" :value="__('NID')" />
            <x-text-input id="nid" class="block mt-1 w-full" type="number" name="nid" :value="old('nid')" required />
            <x-input-error :messages="$errors->get('nid')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-4">
                {{ __('Search Status') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
