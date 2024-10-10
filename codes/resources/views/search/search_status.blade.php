<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        @if (Route::has('search'))
            <a href="{{ route('search') }}" class="rounded-md py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                {{ __('Search Status') }}
            </a>
        @endif
    </div>

    @if(!empty($data) && $data['status'])
        <div class="mt-4">
            @switch($data['status'])
                @case('Not Registered')
                    <a href="{{ route('register') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                        {{ __('Not Registered') }}
                    </a>
                    @break

                @case('Scheduled')
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-2 border border-gray-300 text-left text-gray-600 dark:text-gray-200">{{ __('Vaccine Center') }}</th>
                                <th class="px-4 py-2 border border-gray-300 text-left text-gray-600 dark:text-gray-200">{{ __('Vaccination Date') }}</th>
                                <th class="px-4 py-2 border border-gray-300 text-left text-gray-600 dark:text-gray-200">{{ __('Address') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="px-4 py-2 border border-gray-300">{{ $data['vaccine_center_info']['name'] }}</td>
                                <td class="px-4 py-2 border border-gray-300">{{ $data['vaccine_center_info']['date'] }}</td>
                                <td class="px-4 py-2 border border-gray-300">{{ $data['vaccine_center_info']['address'] }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    @break

                @case('Vaccinated')
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            {{ __('Vaccinated') }}
                        </p>
                    </div>
                    @break

                @default
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            {{ __('Unknown Status') }}
                        </p>
                    </div>
            @endswitch
        </div>
    @endif
</x-guest-layout>
