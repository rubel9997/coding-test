<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Account No.') }} : {{ $auth_user->email }}
            </h2>

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Account Type') }} : {{ $auth_user->account_type }}
            </h2>

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Balance') }} : {{ $auth_user->balance }}
            </h2>

        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Transaction Type
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Fee
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($allTransaction as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->transaction_type ?? '' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->amount ?? 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->fee ?? 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->date ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $allTransaction->firstItem() }}</span>
                                    to
                                    <span class="font-medium">{{ $allTransaction->lastItem() }}</span>
                                    of
                                    <span class="font-medium">{{ $allTransaction->total() }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                {{ $allTransaction->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
